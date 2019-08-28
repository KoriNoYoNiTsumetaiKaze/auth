<?php
require_once 'general.php';
require_once 'sql.php';

function removeSess($cook) {
	$mysqli	= sqlConn();
	if ($mysqli==false) return false;
	$cook	= $mysqli->real_escape_string($cook);
	if ($stmt = $mysqli->prepare("DELETE FROM Cook WHERE cook = ?")) {
		$stmt->bind_param("s", trim($cook));
		$stmt->execute();
		$stmt->close();
		}
	$results	= $mysqli->query("DELETE FROM products WHERE added_timestamp > (NOW() - INTERVAL 1 DAY)");
	$mysqli->close();
	}

function getLoginCook($cook) {
	$mysqli	= sqlConn();
	if ($mysqli==false) return false;
	$cook		= $mysqli->real_escape_string($cook);
	$login	= false;
	if ($stmt = $mysqli->prepare("SELECT login FROM Cook WHERE cook=?")) {
		$stmt->bind_param("s", trim($cook));
		$stmt->execute();
		$stmt->bind_result($login);
		$stmt->fetch();
		$stmt->close();
		}
	$mysqli->close();
	return $login;
	}

function getFIO_Login($login) {
	$mysqli	= sqlConn();
	if ($mysqli==false) return false;
	$login	= $mysqli->real_escape_string($login);
	$fio	= false;
	if ($stmt = $mysqli->prepare("SELECT fio FROM users WHERE login=?")) {
		$stmt->bind_param("s", trim($login));
		$stmt->execute();
		$stmt->bind_result($fio);
		$stmt->fetch();
		$stmt->close();
		}
	$mysqli->close();
	return $fio;
	}

function get_list_duplicate_emails() {
	$mysqli	= sqlConn();
	$list	= array();
	if ($mysqli==false) return $list;
	if ($stmt = $mysqli->prepare("SELECT email,COUNT(*) FROM test_quest.users group by email HAVING COUNT(*) > 1")) {
		$stmt->execute();
		$stmt->bind_result($email,$c);
		while($stmt->fetch()) {
			$list[]	= (trim($email)==""?"email не задан":$email);
			}
		$stmt->close();
		}
	$mysqli->close();
	return $list;
	}

function get_list_login_not_orders() {
	$mysqli	= sqlConn();
	$list	= array();
	if ($mysqli==false) return $list;
	if ($stmt = $mysqli->prepare("SELECT login FROM test_quest.users where id not in (SELECT user_id as id FROM test_quest.orders)")) {
		$stmt->execute();
		$stmt->bind_result($login);
		while($stmt->fetch()) {
			$list[]	= $login;
			}
		$stmt->close();
		}
	$mysqli->close();
	return $list;
	}

function get_list_login_2_orders() {
	$mysqli	= sqlConn();
	$list	= array();
	if ($mysqli==false) return $list;
	if ($stmt = $mysqli->prepare("SELECT login FROM test_quest.users where id in (SELECT user_id as id, count(*) as Cid FROM test_quest.orders group by id HAVING COUNT(*) > 2))")) {
		$stmt->execute();
		$stmt->bind_result($login);
		while($stmt->fetch()) {
			$list[]	= $login;
			}
		$stmt->close();
		}
	$mysqli->close();
	return $list;
	}

function ChangePssword($login) {
	$mysqli	= sqlConn();
	if ($mysqli==false) return false;
	$password	= conversionInVar($_POST['password']);
	$password	= $mysqli->real_escape_string($password);
	$up	= false;
	if ($stmt = $mysqli->prepare("UPDATE users SET pass = ? WHERE login = ?")) {
		$stmt->bind_param("ss", trim($password), trim($login));
		$stmt->execute();
		$stmt->close();
		$up	= true;
		}
	$mysqli->close();
	return $up;
	}

function ChangeFIO($login) {
	$mysqli	= sqlConn();
	if ($mysqli==false) return false;
	$fio	= conversionInVar($_POST['fio']);
	$fio	= $mysqli->real_escape_string($fio);
	$up	= false;
	if ($stmt = $mysqli->prepare("UPDATE users SET fio = ? WHERE login = ?")) {
		$stmt->bind_param("ss", trim($fio), trim($login));
		$stmt->execute();
		$stmt->close();
		$up	= true;
		}
	$mysqli->close();
	return $up;
	}

$SessID	= conversionInVar($_POST['PHPSESSID']);
$exit	= conversionInVar($_POST['exit']);
if ($exit) {
	removeSess($SessID);
	exit;
	}
$login	= getLoginCook($SessID);
if ($login===false) {
	echo "";
	exit;
	}
$ChangePssword	= conversionInVar($_POST['ChangePssword']);
if ($ChangePssword) {
	$ChangePssword	= ChangePssword($login);
	$json['ChangePssword']	= $ChangePssword;
	echo json_encode($json);
	exit;
	}
$ChangeFIO	= conversionInVar($_POST['ChangeFIO']);
if ($ChangeFIO) {
	$ChangeFIO	= ChangeFIO($login);
	$json['ChangeFIO']	= $ChangeFIO;
	$json['fio']		= getFIO_Login($login);
	$json['login']		= $login;
	echo json_encode($json);
	exit;
	}
$fio	= getFIO_Login($login);

$json['login']		= $login;
$json['fio']		= $fio;
$json['email']		= get_list_duplicate_emails();
$json['NotOrder']	= get_list_login_not_orders();
$json['Order2']		= get_list_login_2_orders();
echo json_encode($json);
?>

<?php
session_start();

require_once 'general.php';
require_once 'sql.php';

function addUser($login,$pass,$email,$fio) {
	$mysqli	= sqlConn();
	if ($mysqli==false) return false;
	$login	= $mysqli->real_escape_string($login);
	if (trim($login)=="") {
		$mysqli->close();
		return false;
		}
	$pass	= $mysqli->real_escape_string($pass);
	$email	= $mysqli->real_escape_string($email);
	$fio	= $mysqli->real_escape_string($fio);
	$add	= false;
	if ($stmt = $mysqli->prepare("INSERT INTO users (login, pass, email, fio) VALUES (?,?,?,?)")) {
		$stmt->bind_param("ssss", trim($login),trim($pass),trim($email),trim($fio));
		$stmt->execute();
		$stmt->close();
		$add	= true;
		}
	$mysqli->close();
	return $add;
	}

function checkLogin($login) {
	$mysqli	= sqlConn();
	if ($mysqli==false) return false;
	$login		= $mysqli->real_escape_string($login);
	$num_rows	= 0;
	if ($stmt = $mysqli->prepare("SELECT login FROM users WHERE login=?")) {
		$stmt->bind_param("s", trim($login));
		$stmt->execute();
		$stmt->store_result();
		$num_rows	= $stmt->num_rows;
		$stmt->close();
		}
	$mysqli->close();
	if ($num_rows==0) return false;
	return true;
	}

$siteRoot	= "http://127.0.0.1/site/auth/";
//echo $_SERVER['HTTP_HOST'];
$login		= conversionInVar($_POST['login']);
$checkLogin	= conversionInVar($_POST['checkLogin']);
if ($checkLogin==true) {
	echo checkLogin($login);
	exit;
	}
$pass	= conversionInVar($_POST['password']);
$email	= conversionInVar($_POST['email']);
$fio	= conversionInVar($_POST['fio']);
while (strpos($fio,"_")!=false) {
	$fio = str_replace("_", " ", $fio);
	}

if (checkLogin($login)==true) {
	echo $siteRoot.'index.html';
	exit;
	}

if (addUser($login,$pass,$email,$fio)==true) {
	echo $siteRoot."html/lk.html";
	addCook($login,session_id());
	}
	else echo $siteRoot.'index.html';
?>

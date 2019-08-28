<?php
function sqlConn() {
	$mysqli	= new mysqli('localhost', 'root', 'Admin2@19', 'test_quest');
	if (mysqli_connect_errno()) {
		$mysqli	= false;
		}
	return $mysqli;
	}

function checkAccess($login,$pass) {
	$mysqli	= sqlConn();
	if ($mysqli==false) return false;
	$login		= $mysqli->real_escape_string($login);
	$pass		= $mysqli->real_escape_string($pass);
	$num_rows	= 0;
	if ($stmt = $mysqli->prepare("SELECT login FROM users WHERE login=? and pass=?")) {
		$stmt->bind_param("ss", trim($login),trim($pass));
		$stmt->execute();
		$stmt->store_result();
		$num_rows	= $stmt->num_rows;
		$stmt->close();
		}
	$mysqli->close();
	if ($num_rows==0) return false;
	return true;
	}

function addCook($login,$cook) {
	$mysqli	= sqlConn();
	if ($mysqli==false) return false;
	$login	= $mysqli->real_escape_string($login);
	$cook	= $mysqli->real_escape_string($cook);
	
	$num_rows	= 0;
	if ($stmt = $mysqli->prepare("SELECT login FROM Cook WHERE login=?")) {
		$stmt->bind_param("s", trim($login));
		$stmt->execute();
		$stmt->store_result();
		$num_rows	= $stmt->num_rows;
		$stmt->close();
		}
	
	if ($num_rows==0) {
		if ($stmt = $mysqli->prepare("INSERT INTO Cook (login, cook) VALUES (?,?)")) {
			$stmt->bind_param("ss", trim($login),trim($cook));
			$stmt->execute();
			$stmt->close();
			}
		}
		else {
			if ($stmt = $mysqli->prepare("UPDATE Cook SET cook = ? WHERE login = ?")) {
				$stmt->bind_param("ss", trim($cook), trim($login));
				$stmt->execute();
				$stmt->close();
				}
			}
	$mysqli->close();
	}
?>

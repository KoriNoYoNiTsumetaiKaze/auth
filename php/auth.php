<?php
session_start();

require_once 'general.php';
require_once 'sql.php';

$siteRoot	= "http://127.0.0.1/site/auth/";
$login		= conversionInVar($_POST['login']);
$pass		= conversionInVar($_POST['password']);

if (checkAccess($login,$pass)==true) {
	echo $siteRoot.'html/lk.html';
	addCook($login,session_id());
	}
	else echo $siteRoot.'html/index.html';
?>

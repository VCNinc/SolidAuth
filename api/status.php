<?php
session_start();
$db = mysqli_connect("localhost", "root", "", "solid-auth");

if(!isset($_SESSION['token']) || empty($_SESSION['token'])) {
	die("FALSE");
} else {
	$token = $_SESSION['token'];
	$q = mysqli_query($db, "SELECT * FROM `tokens` WHERE `token` = '$token'");
	$d = mysqli_fetch_assoc($q);
	if($d['status'] == "CONFIRMED") {
		session_destroy();
		die("TRUE");
	} else {
		die("FALSE, " . $_SESSION['token']);
	}
}
?>
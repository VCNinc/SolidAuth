<?php
session_start();

$db = mysqli_connect("server212.web-hosting.com", "redbbtua_senmo2", "senmo2", "redbbtua_senmo2");

function user_exists($email) {
	global $db;

	$q = mysqli_query($db, "SELECT * FROM `users` WHERE `email` = '$email'");
	return (mysqli_num_rows($q) > 0);
}

if(isset($_SESSION['id'])) {
	$id = $_SESSION['id'];
	$q = mysqli_query($db, "SELECT * FROM `users` WHERE `id` = '$id'");
	$user_data = mysqli_fetch_assoc($q);
}
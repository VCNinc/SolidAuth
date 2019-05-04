<?php
$db = mysqli_connect("localhost", "root", "", "solid-auth");
session_start();

$token = mysqli_real_escape_string($db, $_GET['token']);

$q = mysqli_query($db, "SELECT * FROM `tokens` WHERE `token` = '$token' AND `status` = 'ISSUED'");

if(mysqli_num_rows($q) > 0) {
	mysqli_query($db, "UPDATE `tokens` SET `status` = 'CONFIRMED' WHERE `token` = '$token'");
	die("Success.");
} else {
	die("Token not found.");
}
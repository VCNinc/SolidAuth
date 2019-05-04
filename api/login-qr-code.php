<?php
session_start();
$db = mysqli_connect("localhost", "root", "", "solid-auth");

if(!isset($_SESSION['token']) || empty($_SESSION['token'])) {
	$token = bin2hex(openssl_random_pseudo_bytes(32));
	mysqli_query($db, "INSERT INTO `tokens` (`action`, `token`, `attributes`) VALUES ('LOGIN', '$token', 'sa-user-id,sa-user-key')");

	$_SESSION['token'] = $token;
} else {
	$token = $_SESSION['token'];
}

$callback = "localhost/solid-auth/api/callback.php";
$protocol = "SOLID-AUTH-1.0";

$data = urlencode("$protocol,$callback,$token");

$url = "https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl=$data&choe=UTF-8";

header("Location: $url");
?>
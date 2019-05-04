<?php
include 'core/init.php';

if(isset($_SESSION['id'])) {
	header("Location: dashboard.php");
	exit();
}

if(isset($_GET['conf'])) {
	$_SESSION['id'] = 1;
	header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include('core/metas.php'); ?>
		<link rel="stylesheet" href="core/css/login.css">
		<title>Login | senmo - Payments Sandbox | Solid Security</title>
	</head>
	<body>
		<div class="container text-center" id="main">
			<img src="core/img/logo.png" id="logo">
			<p>Login</p>
			<img src="//localhost/solid-auth/api/login-qr-code.php" width="300">
		</div>
		<p class="text-muted">&copy;<?=date('Y')?> Solid Security. This is not a real website. It is a software sandbox used by cybersecurity professionals to find security flaws and vulnerabilities. It is intentionally insecure. Please do not submit sensitive data to this website. Any and all data submitted to this website may be easily accessable by other users. Solid Security and "Senmo" have no affiliation with PayPal or their similarly-named product "Venmo".</p>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script>
			$(function(){
				setInterval(function(){
					$.get("//localhost/solid-auth/api/status.php", function(data) {
						console.log(data);
						if(data == "TRUE") {
							window.location.href = "?conf";
						}
					});
				}, 1000);
			});
		</script>
	</body>
</html>
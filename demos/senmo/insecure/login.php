<?php
include 'core/init.php';

if(isset($_SESSION['id'])) {
	header("Location: dashboard.php");
	exit();
}

$error = false;
$error_message = "";

if(isset($_POST["login"])) {
	$email = $_POST['email'];
	if(empty($email)) {
		$error = true;
		$error_message = "You can't log in without an Email Address.";
	}
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error = true;
		$error_message = "That's not a valid Email Address.";
	}
	if(!user_exists($email)) {
		$error = true;
		$error_message = "There's no account with that Email Address.";
	}

	$password = $_POST['password'];
	if(empty($password)) {
		$error = true;
		$error_message = "You can't log in without a Password.";
	}

	if(!$error) {
		$q = mysqli_query($db, "SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password'");
		if(mysqli_num_rows($q) == 0) {
			$error = true;
			$error_message = "Incorrect Password.";
		} else {
			$data = mysqli_fetch_assoc($q);
			$id = $data['id'];
			$_SESSION['id'] = $id;
			header("Location: dashboard.php");
		}
	}
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
		<div class="container" id="main">
			<img src="core/img/logo.png" id="logo">
			<p>Login</p>
			<form action="" method="post">
				<div class="form-group row">
					<label for="email" class="col-sm-2 col-form-label">Email</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="email" placeholder="email@example.com" name="email">
					</div>
				</div>
				<div class="form-group row">
					<label for="password" class="col-sm-2 col-form-label">Password</label>
					<div class="col-sm-10">
						<input type="password" class="form-control" id="password" placeholder="123456" name="password">
					</div>
				</div>
				<?php
				if($error) {
				?>
				<div class="alert alert-danger text-center" role="alert">
					<b>Hey!</b> <?=$error_message?>
				</div>
				<?php
				}
				?>
				<div class="form-group row">
					<div class="col-sm-12">
						<button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
					</div>
				</div>
			</form>
		</div>
		<p class="text-muted">&copy;<?=date('Y')?> Solid Security. This is not a real website. It is a software sandbox used by cybersecurity professionals to find security flaws and vulnerabilities. It is intentionally insecure. Please do not submit sensitive data to this website. Any and all data submitted to this website may be easily accessable by other users. Solid Security and "Senmo" have no affiliation with PayPal or their similarly-named product "Venmo".</p>
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</body>
</html>
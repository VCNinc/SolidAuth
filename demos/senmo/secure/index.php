<?php
include 'core/init.php';

if(isset($_SESSION['id'])) {
	header("Location: dashboard.php");
	exit();
}

$error = false;
$error_message = "";

if(isset($_POST["register"])) {
	$first_name = $_POST['first_name'];
	if(empty($first_name)) {
		$error = true;
		$error_message = "You can't sign up without a First Name.";
	}

	$last_name = $_POST['last_name'];
	if(empty($last_name)) {
		$error = true;
		$error_message = "You can't sign up without a Last Name.";
	}

	$email = $_POST['email'];
	if(empty($email)) {
		$error = true;
		$error_message = "You can't sign up without an Email Address.";
	}
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error = true;
		$error_message = "That's not a valid Email Address.";
	}
	if(user_exists($email)) {
		$error = true;
		$error_message = "There's already an account with that Email Address.";
	}

	$password = $_POST['password'];
	if(empty($password)) {
		$error = true;
		$error_message = "You can't sign up without a Password.";
	}

	if(!$error) {
		mysqli_query($db, "INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`) VALUES ('$first_name', '$last_name', '$email', '$password')");
		$id = mysqli_insert_id($db);
		$_SESSION['id'] = $id;
		header("Location: dashboard.php");
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include('core/metas.php'); ?>
		<link rel="stylesheet" href="core/css/index.css">
		<title>Home | senmo - Payments Sandbox | Solid Security</title>
	</head>
	<body>
		<div class="container" id="main">
			<nav class="navbar navbar-light bg-light">
				<a class="navbar-brand" href="">
					<img src="core/img/logo.png" height="30" class="d-inline-block align-top" alt="senmo">
				</a>
				<form action="login.php" method="post">
					<button class="btn btn-primary my-2 my-sm-0" type="submit">Sign in</button>
				</form>
			</nav>
			<div class="row">
				<div class="col-sm-6">
					<p><del>Venmo</del> Senmo is the hottest new Silicon Valley startup that helps you pay your friends instantly from any device.</p>
					<p>Our revolutionary technology gives you the ability to Deposit Money&trade; and then Transfer It&trade; to another account.</p>
					<p>Senmo is 100% secure and has no security flaws at all. We pinky promise! Now please give us all of your data:</p>
					<form action="" method="post">
						<div class="form-group row">
							<label for="fname" class="col-sm-2 col-form-label">Name</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="fname" placeholder="First" name="first_name">
							</div>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="lname" placeholder="Last" name="last_name">
							</div>
						</div>
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
								<button type="submit" name="register" class="btn btn-primary btn-block">Create Account</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-sm-6">
					<img src="core/img/phone.png" id="big-image">
				</div>
			</div>
			<hr>
			<p class="text-muted">&copy;<?=date('Y')?> Solid Security. This is not a real website. It is a software sandbox used by cybersecurity professionals to find security flaws and vulnerabilities. It is intentionally insecure. Please do not submit sensitive data to this website. Any and all data submitted to this website may be easily accessable by other users. Solid Security and "Senmo" have no affiliation with PayPal or their similarly-named product "Venmo".</p>
		</div>
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</body>
</html>
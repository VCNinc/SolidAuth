<?php
include 'core/init.php';

if(!isset($_SESSION['id'])) {
	header("Location: index.php");
	exit();
}

$error = false;
$error_message = "";

if(isset($_POST["register"])) {
	if(empty($_POST['email'])) {
		$error = true;
		$error_message = "That's not a valid Email Address.";
	} else {
		$email = $_POST['email'];
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = true;
			$error_message = "That's not a valid Email Address.";
		} else if(!user_exists($email)) {
			$error = true;
			$error_message = "There's no account with that Email Address.";
		}
	}

	$amount = $_POST['amount'];
	if(empty($amount)) {
		$error = true;
		$error_message = "There's no account with that Email Address.";
	}
	$amount = intval($amount);
	if($amount < 1) {
		$error = true;
		$error_message = "The minimum transaction is $1.";
	}
	if($amount > $user_data['balance']) {
		$error = true;
		$error_message = "You can't send more than your balance.";
	}

	$message = $_POST['message'];
	if(empty($message)) {
		$error = true;
		$error_message = "You can't send money without a message.";
	}
	$message = mysqli_real_escape_string($db, $message);

	if(!$error) {
		$new_balance = $user_data['balance'] - $amount;
		mysqli_query($db, "UPDATE `users` SET `balance` = '$new_balance' WHERE `id` = '$id'");

		$q = mysqli_query($db, "SELECT * FROM `users` WHERE `email` = '$email'");
		$data = mysqli_fetch_assoc($q);
		$new_balance = $data['balance'] + $amount;
		$id = $data['id'];
		mysqli_query($db, "UPDATE `users` SET `balance` = '$new_balance' WHERE `id` = '$id'");

		$from_id = $user_data['id'];
		$from_name = $user_data['first_name'] . " " . $user_data['last_name'];
		$to_id = $data['id'];
		$to_name = $data['first_name'] . " " . $data['last_name'];

		mysqli_query($db, "INSERT INTO `transactions` (`from_id`, `from_name`, `to_id`, `to_name`, `amount`, `message`) VALUES ('$from_id', '$from_name', '$to_id', '$to_name', '$amount', '$message')") or die(mysqli_error($db));
		header("Location: dashboard.php?success");
		exit();
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include('core/metas.php'); ?>
		<link rel="stylesheet" href="core/css/dashboard.css">
		<title>Dashboard | senmo - Payments Sandbox | Solid Security</title>
	</head>
	<body>
		<div class="container" id="main">
			<nav class="navbar navbar-light bg-light">
				<a class="navbar-brand" href="">
					<img src="core/img/logo.png" height="30" class="d-inline-block align-top" alt="senmo">
				</a>
				<ul class="navbar-nav">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<img class="profile-img" src="<?=$user_data['image']?>"> Welcome, <b><?=$user_data['first_name']?></b>!
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="logout.php">Log Out</a>
						</div>
					</li>
				</ul>
			</nav>
			<div class="row">
				<div class="col-sm-6">
					<p style="margin-bottom: 0;">Balance:</p>
					<h1>$<?=number_format($user_data['balance'], 2)?></h1>
					<div class="send-form">
						<p>Send Money</p>
						<form action="" method="post">
							<div class="form-group row">
								<label for="email" class="col-sm-2 col-form-label">Recipient</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="email" placeholder="email@example.com" name="email">
								</div>
							</div>
							<div class="form-group row">
								<label for="amount" class="col-sm-2 col-form-label">Amount</label>
								<div class="col-sm-10">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">$</span>
										</div>
										<input type="number" class="form-control" placeholder="25" name="amount">
										<div class="input-group-append">
											<span class="input-group-text">.00</span>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="message" class="col-sm-2 col-form-label">Message</label>
								<div class="col-sm-10">
									<textarea name="message" placeholder="Thanks for dinner last night!" class="form-control" rows="4"></textarea>
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
									<button type="submit" name="register" class="btn btn-primary btn-block">Send Money</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="col-sm-6">
					<p style="margin-bottom: 0;">Transactions:</p>
					<div class="txns">
						<?php
						$q = mysqli_query($db, "SELECT * FROM `transactions` WHERE `from_id` = '$id' OR `to_id` = '$id'");
						while($data = mysqli_fetch_assoc($q)) {
						?>
						<h5><?=$data['from_name']?> &#10230; $<?=number_format($data['amount'], 2)?> &#10230; <?=$data['to_name']?></h5>
						<h6 style="font-weight: normal; margin-top: -5px; margin-bottom: 20px;"><?=$data['message']?></h6>
						<?php
						}
						?>
					</div>
				</div>
			</div>
			<hr style="margin-top: 80px;">
			<p class="text-muted">&copy;<?=date('Y')?> Solid Security. This is not a real website. It is a software sandbox used by cybersecurity professionals to find security flaws and vulnerabilities. It is intentionally insecure. Please do not submit sensitive data to this website. Any and all data submitted to this website may be easily accessable by other users. Solid Security and "Senmo" have no affiliation with PayPal or their similarly-named product "Venmo".</p>
		</div>
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</body>
</html>
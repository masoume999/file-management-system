<?php

session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'mysql';
$DATABASE_NAME = 'file_management';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$id = $_GET['id'];

$id1 = explode("*",$id);
$username = $id1[0];
$admin = $id1[1];

$stmt = $con->prepare('SELECT first_name,last_name,email,phone_number,address FROM temporary_users WHERE user_id = ?');

$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($first_name,$last_name,$email,$phone_number,$address);
$stmt->fetch();
$stmt->close();

if(isset($_POST['submit'])){
	session_regenerate_id();
	$_SESSION['id'] = $admin;


	$stmt = $con->prepare('SELECT user_id,password,first_name,last_name,email,phone_number,address FROM temporary_users WHERE user_id = ?');
	$stmt->bind_param('s', $username);
	$stmt->execute();
	$stmt->bind_result($user_id,$password,$first_name,$last_name,$email,$phone_number,$address);
	$stmt->fetch();
	$stmt->close();

	$stmt = $con->prepare('DELETE FROM temporary_users WHERE user_id = ?');
	$stmt->bind_param('s', $username);
	$stmt->execute();
	$stmt->close();

	$stmt = $con->prepare("INSERT INTO users (user_id,password,first_name,last_name,email,phone_number,address) VALUES ('$user_id','$password','$first_name','$last_name','$email','$phone_number','$address')");
	$stmt->execute();
	$stmt->close();
	mkdir('files/'.$username, 0700);

    header('Location: membershipRequests.php');
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Request Details</title>
		<link href="style4.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="style1">
		<nav class="navtop">
			<div>
				<h1>جزئیات درخواست</h1>
				<a href="adminHome.php"><i class="fas fa-home"></i>صفحه اصلی</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>خروج</a>
			</div>
		</nav>
		<div class="content">
			<div>
				<p>جزئیات کاربر در زیر آمده است:</p>
				<table>
                    <tr>
						<td>شناسه کاربری:</td>
						<td><?=$username?></td>
					</tr>

					<tr>
						<td>نام:</td>
						<td><?=$first_name?></td>
					</tr>
					<tr>
						<td>نام خانوادگی:</td>
						<td><?=$last_name?></td>
					</tr>
					<tr>
						<td>شماره موبایل:</td>
						<td><?=$phone_number?></td>
					</tr>
					<tr>
						<td>ایمیل:</td>
						<td><?=$email?></td>
					</tr>
					<tr>
						<td>آدرس:</td>
						<td><?=$address?></td>
					</tr>
				</table>
			</div>
			<form method="post">
			<input type="submit" class="btn btn-success" value="پذیرش کاربر" name="submit">
            </form>
		</div>
	</body>
</html>
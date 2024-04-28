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

$username=$_GET['id'];

$stmt = $con->prepare('SELECT first_name,last_name,email,phone_number,address FROM users WHERE user_id = ?');

$stmt->bind_param('i', $username);
$stmt->execute();
$stmt->bind_result($first_name,$last_name,$email,$phone_number,$address);
$stmt->fetch();
$stmt->close();

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>User Details</title>
		<link href="style4.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="style1">
		<nav class="navtop">
			<div>
				<h1>جزئیات کاربر</h1>
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
		</div>
	</body>
</html>
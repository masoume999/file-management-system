<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'mysql';
$DATABASE_NAME = 'file_management';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if ($stmt = $con->prepare('SELECT first_name, last_name FROM users WHERE user_id = ?')) {
	$stmt->bind_param('s', $_SESSION['id']);
	$stmt->execute();
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($first_name, $last_name);
        $stmt->fetch();
    }
}

$username =  $_SESSION['id'];

if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}

if(isset($_POST['myFiles'])){
	session_regenerate_id();
	$_SESSION['id'] = $username;
	header('Location: myFiles.php');
}
if(isset($_POST['publicFiles'])){
	session_regenerate_id();
	$_SESSION['id'] = $username;
	header('Location: publicFiles.php');
}
if(isset($_POST['sharedForMe'])){
	session_regenerate_id();
	$_SESSION['id'] = $username;
	header('Location: sharedForMe.php');
}
if(isset($_POST['categories'])){
	session_regenerate_id();
	$_SESSION['id'] = $username;
	header('Location: categories.php');
}
if(isset($_POST['tags'])){
	session_regenerate_id();
	$_SESSION['id'] = $username;
	header('Location: tags.php');
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home Page</title>
		<link href="style4.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="style1">
		<nav class="navtop">
			<div>
				<h1>صفحه اصلی</h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>پروفایل</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>خروج</a>
			</div>
		</nav>
		<form method="post">
		<div class="content">
			<h2><?=$first_name." ".$last_name?></h2>
			</br>
            </br>
			<input type="submit" value="فایل های من" name="myFiles">
		    </br>
            <input type="submit" value="فایل های همگانی" name="publicFiles">
            </br>
            <input type="submit" value="فایل های به اشتراک گذاشته شده برای من" name="sharedForMe">
			</br>
            <input type="submit" value="دسته بندی ها" name="categories">
            </br>
            <input type="submit" value="برچسب ها" name="tags">
		</div>
	    </form>
	</body>
</html>
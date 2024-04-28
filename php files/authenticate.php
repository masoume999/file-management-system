<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'mysql';
$DATABASE_NAME = 'file_management';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$id = $_POST['username'];
$password = $_POST['password'];

if ( !isset($_POST['username'], $_POST['password']) ) {
	echo "<script>alert('لطفا هر دو فیلد شناسه کاربری و رمز عبور را پر کنید.');</script>";
}

if($id === '0021942652' && $password === 'admin77'){
	session_regenerate_id();
	$_SESSION['loggedin'] = TRUE;
	$_SESSION['id'] = $id;
	header('Location: adminHome.php');
}

else if ($stmt = $con->prepare('SELECT user_id, password FROM users WHERE user_id = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        if ($_POST['password'] === $password) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['id'] = $id;
            header('Location: home.php');
        } else {
			echo "<script>alert('یکی از فیلدها یا هر دوی آن ها نادرست است');</script>";
			echo "<script>window.location.href='index.html'</script>";
        }
    } else {
        echo "<script>alert('یکی از فیلدها یا هر دوی آن ها نادرست است');</script>";
		echo "<script>window.location.href='index.html'</script>";
    }

	$stmt->close();
}
?>

<!DOCTYPE html>
<html>
	<head>
        <link href="style.css" rel="stylesheet" type="text/css">
		<meta charset="utf-8">
		<title>Login</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
		<div class="login">
			<h1>Login</h1>
			<form method="post">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<input type="submit" value="Login">
			</form>
		</div>
	</body>
</html>
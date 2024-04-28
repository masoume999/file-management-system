<?php

require_once 'dbconfig.php';


if ( !isset($_POST['username'], $_POST['password']) ) {
	exit('لطفا هر دو فیلد شناسه کاربری و رمز عبور را پر کنید.');
}

if ($stmt = $con->prepare('SELECT id, password FROM users WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();;
        if ($_POST['password'] === $password) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header('Location: home.php');
        } else {
            echo 'یکی از فیلدها یا هر دوی آن ها نادرست است!';
        }
    } else {
        echo 'یکی از فیلدها یا هر دوی آن ها نادرست است!';
    }

	$stmt->close();
}
?>
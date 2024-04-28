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

if(isset($_POST['register'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_repeat = $_POST['password-repeat'];
    $fname = $_POST['firstName'];
    $lname = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if($username!=null && $password!=null && $fname!=null && $lname!=null && $email!=null && $phone!=null && $address!=null){
        if(strlen($username) != 10 && strlen($phone) != 11){
            echo "<script>alert('شناسه کاربری و شماره موبایل خود را به درستی وارد کنید.');</script>";
        }
    
        else if(strlen($username) != 10){
            echo "<script>alert('شناسه کاربری خود را به درستی وراد کنید.');</script>";
        }
    
        else if(strlen($phone) != 11){
            echo "<script>alert('شماره موبایل خود را به درستی وارد کنید.');</script>";
        }
        else if($password_repeat != $password){
            echo "<script>alert('رمز عبور ها مطابقت ندارند.');</script>";
        }
        else{
            $i=false;
            $sql = "SELECT user_id FROM users";
            $results = $con->query($sql);
            if ($results->num_rows > 0) {
                while($row = $results->fetch_assoc()) {
                    if($row["user_id"] == $username){
                        echo "<script>alert('این شناسه کاربری قبلا انتخاب شده است.');</script>";
                        $i=true;
                        break;
                    }
                }
            }
            //$con->close();

            if($i == false){
                $stmt = $con->prepare("INSERT INTO users (user_id,password,first_name,last_name,email,phone_number,address) VALUES ('$username','$password','$fname','$lname','$email','$phone','$address')");
                $stmt->execute();
                $stmt->close();
                
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['id'] = $username;
                header('Location: home.php');
            }
        }
    }
    if($username==null || $password==null || $fname==null || $lname==null || $email==null || $phone==null || $address==null){
        echo "<script>alert('لطفا تمام اطلاعات را وارد نمایید.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Register</title>
		<link href="style2.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="style2">
		<nav class="navtop">
			<div>
                <h1>ثبت نام</h1>
			</div>
		</nav>
        <form method="post">
        <div class="form-row">
            <div class="container">
                <label for="username"><b>شناسه کاربری</b></label>
                <input type="username" placeholder="لطفا شناسه کاربری را وارد نمایید." name="username" id="username" required>

                <label for="psw"><b>رمز عبور</b></label>
                <input type="password" placeholder="لطفا رمز عبور را وارد نمایید." name="password" id="psw" required>
  
                <label for="psw-repeat"><b>تکرار رمز عبور</b></label>
                <input type="password" placeholder="لطفا رمز عبور را تکرار کنید." name="password-repeat" id="psw-repeat" required>
                
                <label for="firstname"><b>نام</b></label>
                <input type="text" placeholder="مثال : معصومه" name="firstName" id="firstName" required>

                <label for="lastname"><b>نام خانوادگی</b></label>
                <input type="text" placeholder="مثال : مولائی" name="lastName" id="lastName" required>

                <label for="email"><b>ایمیل</b></label>
                <input type="text" placeholder="مثال : masoume.molaei@gmail.com" name="email" id="email" required>

                <label for="phone"><b>شماره موبایل</b></label>
                <input type="text" placeholder="مثال : 0912813774" name="phone" id="phone" required>

                <label for="address"><b>آدرس</b></label>
                <input type="text" placeholder="لطفا آدرس خود را وارد نمایید." name="address" id="address" required>
                <hr>
                <button type="submit" name="register">ثبت</button>
            </div>
            
            <div class="container signin">
                <p>آیا حساب کاربری دارید؟ <a href="index.html">ورود</a></p>
            </div>
        </form>
    </div>
</body>
</html>
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

if(isset($_POST['submit'])){
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $fname = $_POST['firstName'];
    $lname = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];


    if($fname!=null && $lname!=null && $email!=null && $phone!=null && $address!=null){
        if(strlen($phone) != 11){
            echo "<script>alert('شماره موبایل خود را به درستی وارد کنید.');</script>";
        }
        else{
            $stmt = $con->prepare("INSERT INTO temporary_users (user_id,password,first_name,last_name,email,phone_number,address) VALUES ('$username','$password','$fname','$lname','$email','$phone','$address')");
            $stmt->execute();
            $stmt->close();
                
            session_regenerate_id();
            $_SESSION['register2'] = TRUE;
            $_SESSION['username'] = $username;
            header('Location: register3.php');
        }
    }
    else{
        echo "<script>alert('لطفا تمام اطلاعات را وارد نمایید.');</script>";
    }
}

if(isset($_POST['back'])){
    session_regenerate_id();
    $_SESSION['back'] = TRUE;
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
    header('Location: register1.php');
}

if (!isset($_SESSION['register1'])) {
	header('Location: register1.html');
	exit;
}
?>

<!DOCTYPE html>
<html dir="rtl">
   <head>
      <meta charset="utf-8">
      <title>Register</title>
      <link rel="stylesheet" href="style3.css">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
   <body>
    <div class="container">
        <div class="text">اطلاعات خود را وارد نمایید.</div>
        <form method="post">
           <div class="form-row">
              <div class="input-data">
                 <input type="text" name="firstName" id="firstName" required>
                 <div class="underline"></div>
                 <label for="">نام</label>
              </div>
              <div class="input-data">
                 <input type="text" name="lastName" id="lastName" required>
                 <div class="underline"></div>
                 <label for="">نام خانوادگی</label>
              </div>
           </div>
           <div class="form-row">
              <div class="input-data">
                 <input type="email" name="email" id="email" required>
                 <div class="underline"></div>
                 <label for="">ایمیل</label>
              </div>
              <div class="input-data">
                 <input type="number" name="phone" id="phone" required>
                 <div class="underline"></div>
                 <label for="">شماره موبایل</label>
              </div>
           </div>
           <div class="form-row">
              <div class="input-data">
                 <input type="text" name="address" id="address" required>
                 <br />
                 <div class="underline"></div>
                 <label for="">آدرس</label>
                 <br />
                 <div class="form-row submit-btn">
                    <div class="input-data">
                       <div class="inner"></div>
                       <input type="submit" name="submit" value="ثبت">
                    </div>
                    <p>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
                       &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
                       &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
                       &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</p>
                    <div class="input-data">
                       <div class="inner"></div>
                       <input type="submit" name="back" value="مرحله قبل">
                    </div>
                 </div>
              </div>
           </div>
        </form>
     </div>
   </body>
</html>
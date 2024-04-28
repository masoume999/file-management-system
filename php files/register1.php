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

if(isset($_POST['next'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_repeat = $_POST['password-repeat'];


    if($username!=null && $password!=null && $password_repeat!=null){
        if(strlen($username) != 10){
            echo "<script>alert('شناسه کاربری خود را به درستی وارد کنید.');</script>";
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
                session_regenerate_id();
                $_SESSION['register1'] = TRUE;
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                header('Location: register2.php');
            }
        }
      }
      else{
         echo "<script>alert('لطفا تمام اطلاعات را وارد نمایید.');</script>";
      }
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
                 <input type="text" name="username" id="username" required>
                 <div class="underline"></div>
                 <label for="">شناسه کاربری</label>
              </div>
           </div>
           <div class="form-row">
              <div class="input-data">
                 <input type="text" name="password" id="password" required>
                 <div class="underline"></div>
                 <label for="">رمزعبور</label>
              </div>
           </div>
           <div class="form-row">
              <div class="input-data">
                 <input type="text" name="password-repeat" id="password-repeat" required>
                 <br />
                 <div class="underline"></div>
                 <label for="">تکرار رمزعبور</label>
                 <br />
                 <div class="form-row submit-btn">
                    <div class="input-data">
                       <div class="inner"></div>
                       <input type="submit" name="next" value="مرحله بعد">
                    </div>
                 </div>
              </div>
           </div>
        </form>
     </div>
   </body>
</html>
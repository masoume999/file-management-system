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

$file_id2 = $_SESSION['id'];


$file_id1 = explode("*",$file_id2);
$file_id = $file_id1[0].' '.$file_id1[1];
$username = $file_id1[2];

// $_SESSION['id'] = $username;

function isUser($user_id){
    $sql = "SELECT user_id FROM users";
    $results = $con->query($sql);
                   
    if ($results->num_rows > 0) {
        while($row = $results->fetch_assoc()) {
            if($row["user_id"] == $user_id){
                return $is;
            }
        }
    }
    return $is;
}

if(isset($_POST['insert'])){
  $user_id = $_POST['username'];

  $file_id1 = $_SESSION['id'];
  $file_id2 = explode("*",$file_id1);
  $file_id = $file_id2[0].' '.$file_id2[1];
  $username = $file_id2[2];

  session_regenerate_id();
  $_SESSION['fileId'] = $file_id;
  $_SESSION['id'] = $username;

  if($user_id != null){
    if(strlen($user_id) != 10){
        echo "<script>alert('شناسه کاربری را به درستی وارد کنید!');</script>";
    }
    else{
        $isUser=false;
        $sql = "SELECT user_id FROM users";
        $results = $con->query($sql);
        if ($results->num_rows > 0) {
            while($row = $results->fetch_assoc()) {
                if($row["user_id"] == $user_id){
                    $isUser=true;
                    break;
                }
            }
        }
        if(!$isUser){
            echo "<script>alert('کاربری با این مشخصه وجود ندارد!');</script>";
        }
        else{
            if($username != $user_id){
                $stmt = $con->prepare("INSERT INTO file_access (file_id,user_id) VALUES ('$file_id','$user_id')");
                $stmt->execute();
                $stmt->close();
                
                echo "<script>alert('دسترسی به کاربر داده شد.');</script>";;
                echo "<script>window.location.href='limitedAccess.php'</script>";
            }
            else{
                echo "<script>alert('شناسه کاربری خود را وارد نکنید!');</script>";
            }
        }
    }
  }
  else{
    echo "<script>alert('شناسه کابری را وارد نمایید.');</script>";
  }
}
?>

<!-- Using a local copy -->

<!-- Include the default stylesheet -->
<link rel="stylesheet" type="text/css" href="multiple-select.css">
<!-- Include plugin -->
<script src="multiple-select.js"></script>

<!-- Or using a CDN -->

<!-- Include the default stylesheet -->
<link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/wenzhixin/multiple-select/e14b36de/multiple-select.css">
<!-- Include plugin -->
<script src="https://cdn.rawgit.com/wenzhixin/multiple-select/e14b36de/multiple-select.js"></script>

<!DOCTYPE html>
<html dir="rtl">
  <head>
    <meta charset="utf-8">
    <title>Insert File Access</title>
    <link rel="stylesheet" href="style7.css">
  </head>
  <body>
    <form class="signup-form" method="post" enctype="multipart/form-data">

      <!-- form header -->
      <div class="form-header">
        <div class="text">شناسه کاربری کاربر مورد نظر خود را وارد نمایید.</div>
      </div>

      <!-- form body -->
      <div class="form-body">

        <div class="form-group">
            <label for="username" class="label-title">شناسه کاربری :</label>
            <input type="text" name="username" class="form-input"/>
        </div>

      <!-- form-footer -->
      <div class="form-footer">
        <button type="submit" name="insert" class="btn">ثبت</button>
      </div>

    </form>

  </body>
</html>
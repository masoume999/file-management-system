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

$author_id = $_SESSION['id'];

if(isset($_POST['insert'])){
  $subject = $_POST['subject'];

  if($subject == null){
    echo "<script>alert('عنوان دسته بندی را وارد نمایید.');</script>";
  }
  else{
    $stmt = $con->prepare("INSERT INTO category (subject,user_id) VALUES ('$subject','$author_id')");
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('دسته بندی با موفقیت ثبت شد.');</script>";
    echo "<script>window.location.href='categories.php'</script>";
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
    <title>Insert Ctegory</title>
    <link rel="stylesheet" href="style7.css">
  </head>
  <body>
    <form class="signup-form" method="post" enctype="multipart/form-data">

      <!-- form header -->
      <div class="form-header">
        <div class="text">عنوان دسته بندی را وارد نمایید.</div>
      </div>

      <!-- form body -->
      <div class="form-body">

        <div class="form-group">
            <label for="filename" class="label-title">عنوان دسته بندی:</label>
            <input type="text" name="subject" class="form-input"/>
        </div>

      <!-- form-footer -->
      <div class="form-footer">
        <button type="submit" name="insert" class="btn">ثبت</button>
      </div>

    </form>

  </body>
</html>
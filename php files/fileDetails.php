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

$file_id = $_GET['id'];

$file_id1 = explode("*",$file_id);
$file_id = $file_id1[0].' '.$file_id1[1];
$username = $file_id1[2];


$stmt1 = $con->prepare('SELECT subject, author_id, creation_date, last_edited_date, category, tag1, tag2,
mainFile, relatedFile1, relatedFile2, relatedFile3 FROM files WHERE file_id = ?');
$stmt1->bind_param('s', $file_id);
$stmt1->execute();
$stmt1->bind_result($subject, $author_id, $creation_date, $last_edited_date, $category, $tag1, $tag2,
$mainFile, $relatedFile1, $relatedFile2, $relatedFile3);
$stmt1->fetch();
$stmt1->close();

$stmt1 = $con->prepare('SELECT first_name,last_name FROM users WHERE user_id = ?');
$stmt1->bind_param('i', $author_id);
$stmt1->execute();
$stmt1->bind_result($first_name,$last_name);
$stmt1->fetch();
$stmt1->close();

if(isset($_POST['download-file'])){
  $file_id = $_GET['id'];
  $file_id1 = explode("*",$file_id);
  $username = $file_id1[2];

  $main = 'files/'.$username.'/('.$subject.')'.$mainFile;

  if(file_exists($main))
  {
    header('Content-Description: '.'File Transfer');
    header('COntent-Type: '.'application/octet-stream');
    header('Content-Disposition: '.'attachment'.' ; filename="'.basename($main).'"');
    header('Expires: '.'0');
    header('Cache-Control: '.'must_revalidate');
    header('Progma: '.'public');
    header('Content-Length: '.filesize($main));
    readfile($main);
    exit;
  }
}

if(isset($_POST['download-file1'])){
  $file_id = $_GET['id'];
  $file_id1 = explode("*",$file_id);
  $username = $file_id1[2];

  $related1 = 'files/'.$username.'/('.$subject.')'.$relatedFile1;

  if(file_exists($related1))
  {
    header('Content-Description: '.'File Transfer');
    header('COntent-Type: '.'application/octet-stream');
    header('Content-Disposition: '.'attachment'.' ; filename="'.basename($related1).'"');
    header('Expires: '.'0');
    header('Cache-Control: '.'must_revalidate');
    header('Progma: '.'public');
    header('Content-Length: '.filesize($related1));
    readfile($related1);
    exit;
  }
}

if(isset($_POST['download-file2'])){
  $file_id = $_GET['id'];
  $file_id1 = explode("*",$file_id);
  $username = $file_id1[2];

  $related2 = 'files/'.$username.'/('.$subject.')'.$relatedFile1;

  if(file_exists($related2))
  {
    header('Content-Description: '.'File Transfer');
    header('COntent-Type: '.'application/octet-stream');
    header('Content-Disposition: '.'attachment'.' ; filename="'.basename($related2).'"');
    header('Expires: '.'0');
    header('Cache-Control: '.'must_revalidate');
    header('Progma: '.'public');
    header('Content-Length: '.filesize($related2));
    readfile($related2);
    exit;
  }
}

if(isset($_POST['download-file3'])){
  $file_id = $_GET['id'];
  $file_id1 = explode("*",$file_id);
  $username = $file_id1[2];

  $related3 = 'files/'.$username.'/('.$subject.')'.$relatedFile1;

  if(file_exists($related3))
  {
    header('Content-Description: '.'File Transfer');
    header('COntent-Type: '.'application/octet-stream');
    header('Content-Disposition: '.'attachment'.' ; filename="'.basename($related3).'"');
    header('Expires: '.'0');
    header('Cache-Control: '.'must_revalidate');
    header('Progma: '.'public');
    header('Content-Length: '.filesize($related3));
    readfile($related3);
    exit;
  }
}
?>

<!DOCTYPE html>
<html dir="rtl">
  <head>
    <meta charset="utf-8">
    <title>File Details</title>
    <link rel="stylesheet" href="style8.css">
  </head>
  <body>
    <form class="signup-form" method="post">

      <!-- form body -->
      <div class="form-body">

        <div class="horizontal-group">
          <div class="form-group right">
            <label for="filename" class="label-title">عنوان فایل:</label>
            <div type="text"><?php echo htmlentities($subject) ?></div>
          </div>
          <div class="form-group left">
            <label for="filename" class="label-title">نام دسته:</label>
            <div type="text"><?php echo htmlentities($category) ?></div>
          </div>
        </div>

        <div class="horizontal-group">
          <div class="form-group right">
            <label for="filename" class="label-title">تاریخ ایجاد:</label>
            <div type="text"><?php echo htmlentities($creation_date) ?></div>
          </div>
          <div class="form-group left">
            <label for="filename" class="label-title">تاریخ آخرین ویرایش:</label>
            <div type="text"><?php echo htmlentities($last_edited_date) ?></div>
          </div>
        </div>

        <div class="horizontal-group">
          <div class="form-group right">
            <label for="filename" class="label-title">شناسه کاربری نویسنده:</label>
            <div type="text"><?php echo htmlentities($author_id) ?></div>
          </div>
          <div class="form-group left">
            <label for="filename" class="label-title">نام و نام خانوادگی نویسنده:</label>
            <div type="text"><?php echo htmlentities($first_name.' '.$last_name) ?></div>
          </div>
        </div>

        <div class="horizontal-group">
          <div class="form-group right">
            <label for="tag1" class="label-title">برچسب 1:</label>
            <div type="text"><?php echo htmlentities($tag1) ?></div>
          </div>
          <div class="form-group left">
            <label for="tag2" class="label-title">برچسب 2:</label>
            <div type="text"><?php echo htmlentities($tag2) ?></div>
          </div>
        </div>


        <div class="horizontal-group">
          <div class="form-group right" >
            <button type="submit" id="download-file" name="download-file" class="btn1">بارگیری فایل اصلی</button>
          </div>
          <div class="form-group left">
          <?php
          if($relatedFile1 != null){
          ?>
            <button type="submit" id="download-file1" name="download-file1" class="btn1">بارگیری فایل مرتبط اول</button>
          <?php
          }
          ?>
          </div>
        </div>

        <div class="horizontal-group">
          <div class="form-group right" >
          <?php
          if($relatedFile2 != null){
          ?>
            <button type="submit" id="download-file2" name="download-file2" class="btn1">بارگیری فایل مرتبط دوم</button>
          <?php
          }
          ?>
          </div>
          <div class="form-group left">
          <?php
          if($relatedFile3 != null){
          ?>
            <button type="submit" id="download-file3" name="download-file3" class="btn1">بارگیری فایل مرتبط سوم</button>
          <?php
          }
          ?>
          </div>
        </div>
      </div>

    </form>

  </body>
</html>
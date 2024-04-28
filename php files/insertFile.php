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

$phpFileUploadErrors = array(
  0 => 'There in no error, the file uploaded with success',
  1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
  2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
  3 => 'The uploaded file was only partially uploaded',
  4 => 'No file was uploaded',
  6 => 'Missing a temporary folder',
  7 => 'Failed to write file to disk.',
  8 => 'A PHP extension stopped the file upload.',
);

if(isset($_POST['insert'])){
  $creation_date = date("Y-m-d H:i:s");
  $file_id = $author_id.$creation_date;
  $sharing = 0;
  $last_edited_date = $creation_date;
  $version_number = 1;
  $subject = $_POST['subject'];
  $category = $_POST['category'];
  $tag1 = $_POST['tag1'];
  $tag2 = $_POST['tag2'];

  if($subject == null){
    echo "<script>alert('عنوان فایل را وارد نمایید.');</script>";
  }
  else{
  $is = false;
  $sql = "SELECT subject FROM files WHERE author_id = $author_id";
  $results = $con->query($sql);
  if ($results->num_rows > 0) {
    while($row = $results->fetch_assoc()) {
      if($row["subject"] == $subject){
        $is = true;
        echo "<script>alert('این عنوان قبلا انتخاب شده است!');</script>";
      }
    }
  }

  $phpFileUploadErrors = array(
    0 => 'There in no error, the file uploaded with success',
    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
    3 => 'The uploaded file was only partially uploaded',
    4 => 'No file was uploaded',
    6 => 'Missing a temporary folder',
    7 => 'Failed to write file to disk.',
    8 => 'A PHP extension stopped the file upload.',
  );

  if(!$is){
  if(isset($_FILES['file'])){
    
    $ext_error = false;
    //a list of extensions that we allo to the uploaded 
    $extensions = array('jpg','jpeg','gif','png','svg','tif','tiff','pdf','doc','docx','html','htm','xls','xlsx','txt','mp4','mp3','ppt','pps','csv','log','rtf','key','xml','exe','csr','php','rar','zip');
    $file_ext = explode('.',$_FILES['file']['name']);
    
    $name = $file_ext[0].'.'.$file_ext[1];
    $name = preg_replace("!-!"," ",$name);

    
    $file_ext = end($file_ext);

    if(!in_array($file_ext, $extensions)){
      $ext_error = true;
    }

    //if the error of the upload is not equal to 0
    if($_FILES['file']['error']){
      if($_FILES['file']['error'] == 4){
        echo "<script>alert('فایل اصلی را بارگزاری کنید!');</script>";
      }
      elseif($_FILES['file']['error'] == 1 || $_FILES['file']['error'] == 2){
        echo "<script>alert('حجم فایل بیش از 128 مگابایت است!');</script>";
      }
      elseif($_FILES['file']['error'] == 6 || $_FILES['file']['error'] == 7 || $_FILES['file']['error'] == 8){
        echo "<script>alert('خطایی رخ داده است!');</script>";
      }
    }
    elseif($ext_error){
      echo "<script>alert('این فرمت قابل قبول نیست!');</script>";
    }
    else{
      $mainFile = $_FILES['file']['name'];
      move_uploaded_file($_FILES['file']['tmp_name'], 'files/'.$author_id.'/('.$subject.')'.$_FILES['file']['name']);

      if(isset($_FILES['file1'])){
    
        $ext_error = false;
        //a list of extensions that we allo to the uploaded 
        $extensions = array('jpg','jpeg','gif','png','svg','tif','tiff','pdf','doc','docx','html','htm','xls','xlsx','txt','mp4','mp3','ppt','pps','csv','log','rtf','key','xml','exe','csr','php','rar','zip');
        $file_ext = explode('.',$_FILES['file1']['name']);
        
        $name = $file_ext[0].$file_ext[1];
        $name = preg_replace("!-!"," ",$name);
        
        $file_ext = end($file_ext);
        
        if($_FILES['file1']['error']){
          if($_FILES['file1']['error'] == 1 || $_FILES['file1']['error'] == 2){
            echo "<script>alert('حجم فایل بیش از 128 مگابایت است!');</script>";
          }
          elseif($_FILES['file1']['error'] == 6 || $_FILES['file1']['error'] == 7 || $_FILES['file1']['error'] == 8){
            echo "<script>alert('خطایی رخ داده است!');</script>";
          }
        }
        elseif($ext_error){
          echo "<script>alert('این فرمت قابل قبول نیست!');</script>";
        }
        else{
          $relatedFile1 = $_FILES['file1']['name'];
          move_uploaded_file($_FILES['file1']['tmp_name'], 'files/'.$author_id.'/('.$subject.')'.$_FILES['file1']['name']);
        }
      }
    
      if(isset($_FILES['file2'])){
    
        $ext_error = false;
        //a list of extensions that we allo to the uploaded 
        $extensions = array('jpg','jpeg','gif','png','svg','tif','tiff','pdf','doc','docx','html','htm','xls','xlsx','txt','mp4','mp3','ppt','pps','csv','log','rtf','key','xml','exe','csr','php','rar','zip');
        $file_ext = explode('.',$_FILES['file2']['name']);
        
        $name = $file_ext[0].$file_ext[1];
        $name = preg_replace("!-!"," ",$name);
        
        $file_ext = end($file_ext);
        
        if($_FILES['file2']['error']){
          if($_FILES['file2']['error'] == 1 || $_FILES['file2']['error'] == 2){
            echo "<script>alert('حجم فایل بیش از 128 مگابایت است!');</script>";
          }
          elseif($_FILES['file2']['error'] == 6 || $_FILES['file2']['error'] == 7 || $_FILES['file2']['error'] == 8){
            echo "<script>alert('خطایی رخ داده است!');</script>";
          }
        }
        elseif($ext_error){
          echo "<script>alert('این فرمت قابل قبول نیست!');</script>";
        }
        else{
          $relatedFile2 = $_FILES['file2']['name'];
          move_uploaded_file($_FILES['file2']['tmp_name'], 'files/'.$author_id.'/('.$subject.')'.$_FILES['file2']['name']);
        }
      }
    
      if(isset($_FILES['file3'])){
    
        $ext_error = false;
        //a list of extensions that we allo to the uploaded 
        $extensions = array('jpg','jpeg','gif','png','svg','tif','tiff','pdf','doc','docx','html','htm','xls','xlsx','txt','mp4','mp3','ppt','pps','csv','log','rtf','key','xml','exe','csr','php','rar','zip');
        $file_ext = explode('.',$_FILES['file3']['name']);
        
        $name = $file_ext[0].$file_ext[1];
        $name = preg_replace("!-!"," ",$name);
        
        $file_ext = end($file_ext);
        
        if($_FILES['file3']['error']){
          if($_FILES['file3']['error'] == 1 || $_FILES['file3']['error'] == 2){
            echo "<script>alert('حجم فایل بیش از 128 مگابایت است!');</script>";
          }
          elseif($_FILES['file3']['error'] == 6 || $_FILES['file3']['error'] == 7 || $_FILES['file3']['error'] == 8){
            echo "<script>alert('خطایی رخ داده است!');</script>";
          }
        }
        elseif($ext_error){
          echo "<script>alert('این فرمت قابل قبول نیست!');</script>";
        }
        else{
          $relatedFile3 = $_FILES['file3']['name'];
          move_uploaded_file($_FILES['file3']['tmp_name'], 'files/'.$author_id.'/('.$subject.')'.$_FILES['file3']['name']);
    
        }
      }
    }
  }

  if($mainFile != null){
    $stmt = $con->prepare("INSERT INTO files (file_id,author_id,subject,creation_date,version_number,last_edited_date,sharing,category,tag1,tag2,mainFile,
    relatedFile1,relatedFile2,relatedFile3) VALUES ('$file_id','$author_id','$subject','$creation_date',
    '$version_number','$last_edited_date','$sharing','$category','$tag1','$tag2','$mainFile',
    '$relatedFile1','$relatedFile2','$relatedFile3')");
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('فایل با موفقیت ثبت شد.');</script>";
    echo "<script>window.location.href='myFiles.php'</script>";
  } 
}
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
    <title>Insert File</title>
    <link rel="stylesheet" href="style7.css">
  </head>
  <body>
    <form class="signup-form" method="post" enctype="multipart/form-data">

      <!-- form header -->
      <div class="form-header">
        <div class="text">اطلاعات فایل را وارد نمایید.</div>
      </div>

      <!-- form body -->
      <div class="form-body">

        <div class="horizontal-group">
          <div class="form-group right">
            <label for="filename" class="label-title">عنوان فایل:</label>
            <input type="text" name="subject" class="form-input"/>
          </div>
          <div class="form-group left">
            <label class="label-title">نام دسته:</label>
            <select name="category" class="form-input" id="category">
            <?php
            $sql = "SELECT subject FROM category WHERE user_id = $author_id";
            $results = $con->query($sql);
                        
            if ($results->num_rows > 0) {
            while($row = $results->fetch_assoc()) {
            ?>
              <option value="<?php echo $row["subject"] ?>"><?php echo $row["subject"] ?></option>
            <?php
            }
          }
            ?>
            </select>
          </div>
        </div>


        <div class="horizontal-group">
          <div class="form-group right">
          <label for="tag" class="label-title">برچسب اول:</label>
          <select name="tag1" class="form-input" id="tag1">
          <?php
          $sql = "SELECT subject FROM tag WHERE user_id = $author_id";
          $results = $con->query($sql);
                        
          if ($results->num_rows > 0) {
            while($row = $results->fetch_assoc()) {
          ?>
             <option value="<?php echo $row["subject"] ?>"><?php echo $row["subject"] ?></option>
          <?php
            }
          }
          ?>
          </select>
          </div>

          <div class="form-group left">
          <label for="tag" class="label-title">برچسب دوم:</label>
          <select name="tag2" class="form-input" id="tag2">
          <?php
          $sql = "SELECT subject FROM tag WHERE user_id = $author_id";
          $results = $con->query($sql);
                        
          if ($results->num_rows > 0) {
            while($row = $results->fetch_assoc()) {
          ?>
             <option value="<?php echo $row["subject"] ?>"><?php echo $row["subject"] ?></option>
          <?php
            }
          }
          ?>
          </select>
          </div>
        </div>


        <div class="horizontal-group">
          <div class="form-group right" >
            <label for="choose-file" class="label-title">بارگزاری فایل اصلی:</label>
            <input type="file" name="file">
          </div>
          <div class="form-group left">
            <label for="choose-file" class="label-title">بارگزاری فایل مرتبط اول:</label>
            <input type="file" name="file1">
          </div>
        </div>

        <div class="horizontal-group">
          <div class="form-group right" >
            <label for="choose-file3" class="label-title">بارگزاری فایل مرتبط دوم:</label>
            <input type="file" name="file2">
          </div>
          <div class="form-group left">
            <label for="choose-file4" class="label-title">بارگزاری فایل مرتبط سوم:</label>
            <input type="file" name="file3">
          </div>
        </div>
      </div>

      <!-- form-footer -->
      <div class="form-footer">
        <button type="submit" name="insert" class="btn">ثبت</button>
      </div>

    </form>

  </body>
</html>
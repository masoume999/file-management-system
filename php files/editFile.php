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

$file_id1 = $_GET['id'];

$file_id2 = explode("*",$file_id1);
$file_id = $file_id2[0].' '.$file_id2[1];
$author_id = $file_id2[2];

$stmt1 = $con->prepare('SELECT subject, version_number, category, tag1, tag2, mainFile, relatedFile1, relatedFile2, relatedFile3 FROM files WHERE file_id = ?');
$stmt1->bind_param('s', $file_id);
$stmt1->execute();
$stmt1->bind_result($subject, $version_number, $category, $tag1, $tag2, $mainFile, $relatedFile1, $relatedFile2, $relatedFile3);
$stmt1->fetch();
$stmt1->close();

if(isset($_POST['deleteRelated1'])){
  unlink('files/'.$author_id.'/('.$subject.')'.$relatedFile1);

  $last_edited_date_1 = date("Y-m-d H:i:s");
  $version_number_1 = intval($version_number) + 1;

  $stmt = $con->prepare("UPDATE files SET relatedFile1='',last_edited_date='$last_edited_date_1', version_number='$version_number_1' WHERE file_id='$file_id'");
  $stmt->execute();
  $stmt->close();

  echo "<script>alert('فایل با موفقیت حذف شد.');</script>";
  echo "<script>window.location.href='editFile.php?id=$file_id1'</script>";
}

if(isset($_POST['deleteRelated2'])){
  unlink('files/'.$author_id.'/('.$subject.')'.$relatedFile2);

  $last_edited_date_1 = date("Y-m-d H:i:s");
  $version_number_1 = intval($version_number) + 1;

  $stmt = $con->prepare("UPDATE files SET relatedFile2='',last_edited_date='$last_edited_date_1', version_number='$version_number_1' WHERE file_id='$file_id'");
  $stmt->execute();
  $stmt->close();

  echo "<script>alert('فایل با موفقیت حذف شد.');</script>";
  echo "<script>window.location.href='editFile.php?id=$file_id1'</script>";
}

if(isset($_POST['deleteRelated3'])){
  unlink('files/'.$author_id.'/('.$subject.')'.$relatedFile3);

  $last_edited_date_1 = date("Y-m-d H:i:s");
  $version_number_1 = intval($version_number) + 1;

  $stmt = $con->prepare("UPDATE files SET relatedFile3='',last_edited_date='$last_edited_date_1', version_number='$version_number_1' WHERE file_id='$file_id'");
  $stmt->execute();
  $stmt->close();

  echo "<script>alert('فایل با موفقیت حذف شد.');</script>";
  echo "<script>window.location.href='editFile.php?id=$file_id1'</script>";
}

if(isset($_POST['update'])){
  $file_id = $_GET['id'];

  $file_id1 = explode("*",$file_id);
  $file_id = $file_id1[0].' '.$file_id1[1];
  $author_id = $file_id1[2];

  $stmt1 = $con->prepare('SELECT subject, version_number, category, tag1, tag2, mainFile, relatedFile1, relatedFile2, relatedFile3 FROM files WHERE file_id = ?');
  $stmt1->bind_param('s', $file_id);
  $stmt1->execute();
  $stmt1->bind_result($subject, $version_number, $category, $tag1, $tag2, $mainFile, $relatedFile1, $relatedFile2, $relatedFile3);
  $stmt1->fetch();
  $stmt1->close();

  $subject_1 = $_POST['subject'];
  $last_edited_date_1 = date("Y-m-d H:i:s");
  $version_number_1 = intval($version_number) + 1;

  $category_1 = $_POST['category'];
  $tag1_1 = $_POST['tag1'];
  $tag2_1 = $_POST['tag2'];


  if($subject_1 == null){
    echo "<script>alert('عنوان فایل را وارد نمایید.');</script>";
  }
  else{
  $is = false;
  $sql = "SELECT subject FROM files WHERE author_id = $author_id";
  $results = $con->query($sql);
  if ($results->num_rows > 0) {
    while($row = $results->fetch_assoc()) {
      if($row["subject"] == $subject_1){
        if($subject_1 != $subject){
          $is = true;
          echo "<script>alert('این عنوان قبلا انتخاب شده است!');</script>";
        }
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
        if($subject_1 != $subject){
          rename('files/'.$author_id.'/('.$subject.')'.$mainFile, 'files/'.$author_id.'/('.$subject_1.')'.$mainFile);
        }
        $mainFile_1 = $mainFile;
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
      $mainFile_1 = $_FILES['file']['name'];

      unlink('files/'.$author_id.'/('.$subject.')'.$mainFile);

      if($subject_1 == $subject){
        move_uploaded_file($_FILES['file']['tmp_name'], 'files/'.$author_id.'/('.$subject.')'.$_FILES['file']['name']);
      }
      else{
        move_uploaded_file($_FILES['file']['tmp_name'], 'files/'.$author_id.'/('.$subject_1.')'.$_FILES['file']['name']);
      }
    }
  }

  if(isset($_FILES['file1'])){
    
    $ext_error = false;
    //a list of extensions that we allo to the uploaded 
    $extensions = array('jpg','jpeg','gif','png','svg','tif','tiff','pdf','doc','docx','html','htm','xls','xlsx','txt','mp4','mp3','ppt','pps','csv','log','rtf','key','xml','exe','csr','php','rar','zip');
    $file_ext = explode('.',$_FILES['file1']['name']);
    
    $name = $file_ext[0].$file_ext[1];
    $name = preg_replace("!-!"," ",$name);
    
    $file_ext = end($file_ext);
    
    if($_FILES['file1']['error']){
      if($_FILES['file']['error'] == 4){
        if($relatedFile1 != null){
          $relatedFile1_1 = $relatedFile1;
          if($subject_1 != $subject){
            rename('files/'.$author_id.'/('.$subject.')'.$relatedFile1, 'files/'.$author_id.'/('.$subject_1.')'.$relatedFile1);
          }
        }
      }
      elseif($_FILES['file1']['error'] == 1 || $_FILES['file1']['error'] == 2){
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
      $relatedFile1_1 = $_FILES['file1']['name'];

      if($relatedFile1 != null){
        unlink('files/'.$author_id.'/('.$subject.')'.$relatedFile1);
      }

      if($subject_1 == $subject){
        move_uploaded_file($_FILES['file1']['tmp_name'], 'files/'.$author_id.'/('.$subject.')'.$_FILES['file1']['name']);
      }
      else{
        move_uploaded_file($_FILES['file1']['tmp_name'], 'files/'.$author_id.'/('.$subject_1.')'.$_FILES['file1']['name']);
      }
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
      if($_FILES['file']['error'] == 4){
        if($relatedFile2 != null){
          $relatedFile2_1 = $relatedFile2;
          if($subject_1 != $subject){
            rename('files/'.$author_id.'/('.$subject.')'.$relatedFile2, 'files/'.$author_id.'/('.$subject_1.')'.$relatedFile2);
          }
        }
      }
      elseif($_FILES['file2']['error'] == 1 || $_FILES['file2']['error'] == 2){
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
      $relatedFile2_1 = $_FILES['file2']['name'];

      if($relatedFile2 != null){
        unlink('files/'.$author_id.'/('.$subject.')'.$relatedFile2);
      }

      if($subject_1 == $subject){
        move_uploaded_file($_FILES['file2']['tmp_name'], 'files/'.$author_id.'/('.$subject.')'.$_FILES['file2']['name']);
      }
      else{
        move_uploaded_file($_FILES['file2']['tmp_name'], 'files/'.$author_id.'/('.$subject_1.')'.$_FILES['file2']['name']);
      }
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
      if($_FILES['file']['error'] == 4){
        if($relatedFile3 != null){
          $relatedFile3_1 = $relatedFile3;
          if($subject_1 != $subject){
            rename('files/'.$author_id.'/('.$subject.')'.$relatedFile3, 'files/'.$author_id.'/('.$subject_1.')'.$relatedFile3);
          }
        }
      }
      elseif($_FILES['file3']['error'] == 1 || $_FILES['file3']['error'] == 2){
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
      $relatedFile3_1 = $_FILES['file3']['name'];

      if($relatedFile3 != null){
        unlink('files/'.$author_id.'/('.$subject.')'.$relatedFile3);
      }

      if($subject_1 == $subject){
        move_uploaded_file($_FILES['file3']['tmp_name'], 'files/'.$author_id.'/('.$subject.')'.$_FILES['file3']['name']);
      }
      else{
        move_uploaded_file($_FILES['file3']['tmp_name'], 'files/'.$author_id.'/('.$subject_1.')'.$_FILES['file3']['name']);
      }
    }
  }


  if($mainFile_1 != null){
    $stmt = $con->prepare("UPDATE files SET subject='$subject_1', last_edited_date='$last_edited_date_1', version_number='$version_number_1',
    category='$category_1', tag1='$tag1_1', tag2='$tag2_1', mainFile='$mainFile_1', relatedFile1='$relatedFile1_1',
    relatedFile2='$relatedFile2_1', relatedFile3='$relatedFile3_1' WHERE file_id='$file_id'");
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('فایل با موفقیت ویرایش شد.');</script>";
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
    <title>Edit File</title>
    <link rel="stylesheet" href="style9.css">
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
            <input type="text" name="subject" class="form-input" value=<?php echo htmlentities($subject) ?> />
          </div>
          <div class="form-group left">
            <label class="label-title">نام دسته:</label>
            <select name="category" class="form-input" id="category">
            <?php
            $sql = "SELECT subject FROM category WHERE user_id = $author_id";
            $results = $con->query($sql);
            ?>
                        
            <option value="<?php echo htmlentities($category) ?>"><?php echo htmlentities($category) ?></option>

            <?php
            if ($results->num_rows > 0) {
            while($row = $results->fetch_assoc()) {
              if($row["subject"] != $category){
            ?>
              <option value="<?php echo $row["subject"] ?>"><?php echo $row["subject"] ?></option>
            <?php
              }
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
          ?>
          <option value="<?php echo htmlentities($tag1) ?>"><?php echo htmlentities($tag1) ?></option>

          <?php
          if ($results->num_rows > 0) {
            while($row = $results->fetch_assoc()) {
              if($row["subject"] != $tag1){
          ?>
             <option value="<?php echo $row["subject"] ?>"><?php echo $row["subject"] ?></option>
          <?php
            }
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
          ?>

          <option value="<?php echo htmlentities($tag2) ?>"><?php echo htmlentities($tag2) ?></option>
                        
          <?php
          if ($results->num_rows > 0) {
            while($row = $results->fetch_assoc()) {
              if($row["subject"] != $tag2){
          ?>
             <option value="<?php echo $row["subject"] ?>"><?php echo $row["subject"] ?></option>
          <?php
          }
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
            <?php
            if($relatedFile1 != null){
            ?>
            <button type="submit" name="deleteRelated1" onClick="return confirm('آیا فایل حذف گردد؟');" class="btn1">حذف</button>
            <?php
            }
            ?>
          </div>
        </div>

        <div class="horizontal-group">
          <div class="form-group right" >
            <label for="choose-file3" class="label-title">بارگزاری فایل مرتبط دوم:</label>
            <input type="file" name="file2">
            <?php
            if($relatedFile2 != null){
            ?>
            <button name="deleteRelated2" class="btn1" onClick="return confirm('آیا فایل حذف گردد؟');">حذف</button>
            <?php
            }
            ?>
          </div>
          <div class="form-group left">
            <label for="choose-file4" class="label-title">بارگزاری فایل مرتبط سوم:</label>
            <input type="file" name="file3">
            <?php
            if($relatedFile3 != null){
            ?>
            <button name="deleteRelated3" class="btn1" onClick="return confirm('آیا فایل حذف گردد؟');">حذف</button>
            <?php
            }
            ?>
          </div>
        </div>
      </div>

      <!-- form-footer -->
      <div class="form-footer">
        <button type="submit" name="update" class="btn">ثبت</button>
      </div>

    </form>

  </body>
</html>
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

$username = $_SESSION['id'];

if($username == null){
    $username = $_GET['id'];
}

if(isset($_REQUEST['del'])){
    $file_id = $_GET['del'];

    $file_id1 = explode("*",$file_id);
    $file_id = $file_id1[0].' '.$file_id1[1];


    $stmt = $con->prepare('SELECT subject,mainFile,relatedFile1,relatedFile2,relatedFile3 FROM files WHERE file_id = ?');
    $stmt->bind_param('s', $file_id);
    $stmt->execute();
    $stmt->bind_result($subject, $mainFile, $relatedFile1, $relatedFile2, $relatedFile3);
    $stmt->fetch();
    $stmt->close();


    unlink('files/'.$username.'/('.$subject.')'.$mainFile);
    
    if($relatedFile1 != null){
        unlink('files/'.$username.'/('.$subject.')'.$relatedFile1);
    }
    if($relatedFile2 != null){
        unlink('files/'.$username.'/('.$subject.')'.$relatedFile2);
    }
    if($relatedFile3 != null){
        unlink('files/'.$username.'/('.$subject.')'.$relatedFile3);
    }

    $stmt = $con->prepare('DELETE FROM files WHERE file_id = ?');
    $stmt->bind_param('s', $file_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('فایل با موفقیت حذف شد.');</script>";
    echo "<script>window.location.href='myFiles.php'</script>";
}

if(isset($_POST['insert'])){
	session_regenerate_id();
	$_SESSION['id'] = $username;
	header('Location: insertFile.php');
}

if(isset($_POST['publicAccess'])){
	session_regenerate_id();
	$_SESSION['id'] = $username;
	header('Location: publicAccess.php');
}

if(isset($_POST['search'])){
	session_regenerate_id();
	$_SESSION['id'] = $username;
	header('Location: searchMyFiles.php');
}


?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>My Files</title>
		<link href="style77.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="style1">
		<nav class="navtop">
			<div>
				<h1>فایل های من</h1>
				<a href="home.php"><i class="fas fa-home"></i>صفحه اصلی</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>خروج</a>
			</div>
		</nav>
        <form method="post">
        <div class="container">
		<input type="submit" value="افزودن فایل جدید" name="insert">
        <input type="submit" value="مدیریت دسترسی همگانی" name="publicAccess">
        <input type="submit" value="جستجو" name="search">
        </br>
        </br>
        </div>
        </form>
        <div class="table">
                    <table id="myFiles" class="table table-bordered table-striped m-2">
                        <thead>
                            <th>عنوان فایل</th>
                            <th>شماره نسخه</th>
                            <th>دسترسی محدود</th>
                            <th>جزئیات</th>
                            <th>ویرایش</th>
                            <th>حذف</th>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "SELECT file_id,subject,version_number,sharing FROM files WHERE author_id = $username";
                        $results = $con->query($sql);
                        
                        if ($results->num_rows > 0) {
                            while($row = $results->fetch_assoc()) {
                                $array_id = explode(" ",$row["file_id"]);
                                $fileId = $array_id[0].'*'.$array_id[1];
                                $getFileId = $fileId.'*'.$username;
                        ?>
                            <tr>
                                <td>
                                    <?php echo htmlentities($row["subject"]) ?>
                                </td>
                                <td>
                                    <?php echo htmlentities($row["version_number"]) ?>    
                                </td>
                            
                                <td><a href="limitedAccess.php?id=<?php echo htmlentities($getFileId); ?>"><button class="fas fa-users"></button></a></td>

                                <td><a href="fileDetails.php?id=<?php echo htmlentities($getFileId); ?>"><button i style='font-size:13px' class='fa fa-info-circle'></i></button></a></td>

                                <td><a href="editFile.php?id=<?php echo htmlentities($getFileId); ?>"><button class="fas fa-edit"></button></a></td>

                                <td><a href="myFiles.php?del=<?php echo htmlentities($fileId); ?>"><button class="fas fa-trash" onClick="return confirm('آیا فایل حذف گردد؟');"></button></a></td>
                            </tr>
                            <?php
                        }
                    }
                            ?>
                        </tbody>
                    </table>
                </div>
	</body>
</html>
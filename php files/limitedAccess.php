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

$file_id2 = $_GET['id'];

if($file_id2 == null){
    $file_id2 = $_SESSION['fileId'];
}

$file_id1 = explode("*",$file_id2);
$file_id = $file_id1[0].' '.$file_id1[1];
$username = $file_id1[2];

$stmt = $con->prepare('SELECT sharing FROM files WHERE file_id = ?');
$stmt->bind_param('s', $file_id);
$stmt->execute();
$stmt->bind_result($sharing);
$stmt->fetch();
$stmt->close();

$_SESSION['fileId'] = $file_id1[0].'*'.$file_id1[1];
$_SESSION['id'] = $file_id1[2];


if(isset($_REQUEST['del'])){
    $ids = $_GET['del'];

    $ids1 = explode("*",$ids);
    $file_id = $ids1[0].' '.$ids1[1];
    $user_id = $ids1[3];

    $stmt = $con->prepare("DELETE FROM file_access WHERE (file_id='$file_id' AND user_id='$user_id')");
    $stmt->execute();
    $stmt->close();

    session_regenerate_id();
	$_SESSION['fileId'] = $ids1[0].'*'.$ids1[1];
    $_SESSION['id'] = $ids1[2];

    echo "<script>alert('کاربر با موفقیت حذف شد.');</script>";
    echo "<script>window.location.href='limitedAccess.php'</script>";
}

if(isset($_POST['insert'])){
	session_regenerate_id();
	$_SESSION['id'] = $file_id2;
	header('Location: insertUserAccess.php');
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Limited File Access</title>
		<link href="style89.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="style1">
		<nav class="navtop">
			<div>
				<h1>مدیریت دسترسی محدود فایل ها</h1>
                <a href="myFiles.php"><i class="fas fa-file"></i>فایل های من</a>
				<a href="home.php"><i class="fas fa-home"></i>صفحه اصلی</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>خروج</a>
			</div>
		</nav>
        <div class="container">
        <form method="post">
        <input type="submit" value="افزودن کاربر جدید" name="insert">
        </form>
        </div>
        </br>
        <div class="table1">
                    <table id="accessFiles" class="table table-bordered table-striped m-2">
                        <thead>
                            <th>ردیف</th>
                            <th>شناسه کاربری</th>
                            <th>نام</th>
                            <th>نام خانوادگی</th>
                            <th>حذف</th>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "SELECT user_id FROM file_access WHERE file_id = '$file_id'";
                        $results = $con->query($sql);

                        $id = 1;
                        if ($results->num_rows > 0) {
                            while($row = $results->fetch_assoc()) {
                                $stmt = $con->prepare('SELECT first_name, last_name FROM users WHERE user_id = ?');
                                $stmt->bind_param('s', $row["user_id"]);
                                $stmt->execute();
                                $stmt->bind_result($first_name, $last_name);
                                $stmt->fetch();
                                $stmt->close();
        
                        ?>
                            <tr>
                                <td>
                                    <?php echo htmlentities($id) ?>
                                </td>
                                <td>
                                    <?php echo htmlentities($row["user_id"]) ?>
                                </td>
                                <td>
                                    <?php echo htmlentities($first_name) ?>
                                </td>
                                <td>
                                    <?php echo htmlentities($last_name) ?>
                                </td>

                                <td><a href="limitedAccess.php?del=<?php echo htmlentities($file_id2.'*'.$row["user_id"]); ?>"><button class="fas fa-trash" onClick="return confirm('آیا فایل حذف گردد؟');"></button></a></td>
                            </tr>
                            <?php
                            $id++;
                    }
                }
                            ?>
                        </tbody>
                    </table>
                </div>
	</body>
</html>
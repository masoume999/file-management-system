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


if(isset($_POST['submit'])){
	session_regenerate_id();
	$_SESSION['id'] = $username;
    $checkbox = $_POST['check_gdpr']; 

    for($i=0; $i<count($checkbox); $i++){
        $arrayId = explode("*",$checkbox[$i]);
        $checkbox[$i] = $arrayId[0].' '.$arrayId[1];
    }   

    for($i=0; $i<count($checkbox); $i++){
        $stmt = $con->prepare("UPDATE files SET sharing='1' WHERE file_id='$checkbox[$i]'");
        $stmt->execute();
        $stmt->close();
    }   
    
    $sql = "SELECT file_id FROM files WHERE author_id = '$username' AND sharing = '1'";
    $results = $con->query($sql);
    
    $shared = array();
    $i = 0;
    if ($results->num_rows > 0) {
        while($row = $results->fetch_assoc()) {
            $shared[$i] = $row["file_id"];
            $i++;
        }
    }


    for($i=0; $i<count($shared); $i++){
        if(!in_array($shared[$i], $checkbox)){
            $stmt = $con->prepare("UPDATE files SET sharing='0' WHERE file_id='$shared[$i]'");
            $stmt->execute();
            $stmt->close();
        }
    }   
header('Location: publicAccess.php');
}


?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Public File Access</title>
		<link href="style77.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="style1">
		<nav class="navtop">
			<div>
				<h1>مدیریت دسترسی همگانی فایل ها</h1>
                <a href="myFiles.php"><i class="fas fa-file"></i>فایل های من</a>
				<a href="home.php"><i class="fas fa-home"></i>صفحه اصلی</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>خروج</a>
			</div>
		</nav>
        <form method="post">
        <div class="container">
        <input type="submit" value="اعمال تغییرات" name="submit">
        </br>
        </br>
        </div>
        <div class="table">
                    <table id="myFiles" class="table table-bordered table-striped m-2">
                        <thead>
                            <th>ردیف</th>
                            <th>عنوان فایل</th>
                            <th>دسترسی همگانی</th>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "SELECT file_id,subject,sharing FROM files WHERE author_id = $username";
                        $results = $con->query($sql);
                        

                        $id = 1;
                        if ($results->num_rows > 0) {
                            while($row = $results->fetch_assoc()) {
                                $array_id = explode(" ",$row["file_id"]);
                                $fileId = $array_id[0].'*'.$array_id[1];
                        ?>
                            <tr>
                                <td>
                                    <?php echo htmlentities($id) ?>    
                                </td>
                                <td>
                                    <?php echo htmlentities($row["subject"]) ?>
                                </td>
                                <td>
                                    <?php if($row["sharing"] == '1'){
                                            ?> <input type="checkbox" name="check_gdpr[]" id="check_gdpr" value=<?php echo htmlentities($fileId) ?> checked>
                                    <?php  } 
                                          else if($row["sharing"] == '0'){
                                            ?> <input type="checkbox" name="check_gdpr[]" id="check_gdpr" value=<?php echo htmlentities($fileId) ?>>
                                    <?php  }
                                    ?>  
                                </td>
                            </tr>
                            <?php
                            $id++;
                        }
                    }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>  
	</body>
</html>
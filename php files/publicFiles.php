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

if(isset($_POST['search'])){
	header('Location: searchPublicFiles.php');
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Public Files</title>
		<link href="style66.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="style1">
		<nav class="navtop">
			<div>
				<h1>فایل های همگانی</h1>
				<a href="home.php"><i class="fas fa-home"></i>صفحه اصلی</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>خروج</a>
			</div>
		</nav>
        <form method="post">
        <div class="container">
        <input type="submit" value="جستجو" name="search">
        </br>
        </br>
        </div>
        </form>
        <div class="table1">
                    <table id="myFiles" class="table table-bordered table-striped m-2">
                        <thead>
                            <th>عنوان فایل</th>
                            <th>شناسه کاربری نویسنده</th>
                            <th>نام نویسنده</th>
                            <th>جزئیات</th>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "SELECT file_id,subject, author_id FROM files WHERE sharing = 1";
                        $results = $con->query($sql);
                        

                        if ($results->num_rows > 0) {
                            while($row = $results->fetch_assoc()) {
                                $stmt = $con->prepare('SELECT first_name,last_name FROM users WHERE user_id = ?');

                                $stmt->bind_param('s', $row["author_id"]);
                                $stmt->execute();
                                $stmt->bind_result($first_name,$last_name);
                                $stmt->fetch();
                                $stmt->close();
        
                        ?>
                            <tr>
                                <td>
                                    <?php echo htmlentities($row["subject"]) ?>
                                </td>
                                <td>
                                    <?php echo htmlentities($row["author_id"]) ?>    
                                </td>
                                <td>
                                    <?php echo htmlentities($first_name.' '.$last_name) ?>    
                                </td>

                                <td><a href="fileDetails.php?id=<?php echo htmlentities($row["file_id"]); ?>"><button i style='font-size:13px' class='fa fa-info-circle'></i></button></a></td>
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
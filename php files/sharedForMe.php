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

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Shared Files For Me</title>
		<link href="style66.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="style1">
		<nav class="navtop">
			<div>
				<h1>فایل های به اشتراک گذاشته شده برای من</h1>
				<a href="home.php"><i class="fas fa-home"></i>صفحه اصلی</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>خروج</a>
			</div>
		</nav>
        </br>
        </br>
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
                        $sql = "SELECT file_id FROM file_access WHERE user_id = $username";
                        $results = $con->query($sql);
                        
                        $id = 1;
                        if ($results->num_rows > 0) {
                            while($row = $results->fetch_assoc()) {
                                $stmt = $con->prepare('SELECT subject, author_id FROM files WHERE file_id = ?');

                                $stmt->bind_param('s', $row["file_id"]);
                                $stmt->execute();
                                $stmt->bind_result($subject, $author_id);
                                $stmt->fetch();
                                $stmt->close();

                                $stmt1 = $con->prepare('SELECT first_name,last_name FROM users WHERE user_id = ?');

                                $stmt1->bind_param('s', $author_id);
                                $stmt1->execute();
                                $stmt1->bind_result($first_name,$last_name);
                                $stmt1->fetch();
                                $stmt1->close();
        
                        ?>
                            <tr>
                                <td>
                                    <?php echo htmlentities($subject) ?>
                                </td>
                                <td>
                                    <?php echo htmlentities($author_id) ?>    
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
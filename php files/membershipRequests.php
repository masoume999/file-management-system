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
		<title>Membership Rrequests</title>
		<link href="style6.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="style1">
		<nav class="navtop">
			<div>
				<h1>درخواست های عضویت</h1>
				<a href="adminHome.php"><i class="fas fa-home"></i>صفحه اصلی</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>خروج</a>
			</div>
		</nav>
        <div class="container">
        </br>
        </br>
        <div class="table">
                    <table id="Rrequests" class="table table-bordered table-striped m-2">
                        <thead>
                            <th>ردیف</th>
                            <th>شناسه کاربری</th>
                            <th>نام</th>
                            <th>نام خانوادگی</th>
                            <th>جزئیات</th>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "SELECT user_id,first_name, last_name FROM temporary_users";
                        $results = $con->query($sql);
                        
                        $id = 1;
                        if ($results->num_rows > 0) {
                            while($row = $results->fetch_assoc()) {
                                $getFileId = $row["user_id"].'*'.$username;
        
                        ?>
                            <tr>
                                <td>
                                    <?php echo htmlentities($id) ?>
                                </td>
                                <td>
                                    <?php echo htmlentities($row["user_id"]) ?>
                                </td>
                                <td>
                                    <?php echo htmlentities($row["first_name"]) ?>    
                                </td>
                                <td>
                                    <?php echo htmlentities($row["last_name"]) ?> 
                                </td>
                                
                                <td><a href="requestDetails.php?id=<?php echo htmlentities($getFileId); ?>"><button i style='font-size:13px' class='fa fa-info-circle'></i></button></a></td>

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
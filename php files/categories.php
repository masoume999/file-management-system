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

if(isset($_REQUEST['del'])){
    $sbjct = $_GET['del'];

    $stmt = $con->prepare("DELETE FROM category WHERE user_id='$username' AND subject=?");
    $stmt->bind_param('s', $sbjct);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('دسته بندی با موفقیت حذف شد.');</script>";
    echo "<script>window.location.href='categories.php'</script>";
}

if(isset($_POST['insert'])){
    session_regenerate_id();
	$_SESSION['id'] = $username;
	header('Location: insertCategory.php');
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Categories</title>
		<link href="style6.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="style1">
		<nav class="navtop">
			<div>
				<h1>دسته بندی ها</h1>
				<a href="home.php"><i class="fas fa-home"></i>صفحه اصلی</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>خروج</a>
			</div>
		</nav>
        <form method="post">
        <div class="container">
		<input type="submit" value="افزودن دسته بندی جدید" name="insert">
        </br>
        </br>
        </div>
        </form>
        <div class="table">
                    <table id="Categories" class="table table-bordered table-striped m-2">
                        <thead>
                            <th>ردیف</th>
                            <th>عنوان</th>
                            <th>حذف</th>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "SELECT subject FROM category WHERE user_id=$username";
                        $results = $con->query($sql);
                        
                        $id = 1;
                        if ($results->num_rows > 0) {
                            while($row = $results->fetch_assoc()) {
        
                        ?>
                            <tr>
                                <td>
                                    <?php echo htmlentities($id) ?>
                                </td>
                                <td>
                                    <?php echo htmlentities($row["subject"]) ?>
                                </td>
                                
                                <td><a href="categories.php?del=<?php echo htmlentities($row["subject"]); ?>"><button class="fas fa-trash" onClick="return confirm('آیا این دسته بندی حذف گردد؟');"></button></a></td>

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
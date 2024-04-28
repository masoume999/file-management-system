<?php
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'mysql';
$DATABASE_NAME = 'file_management';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
$author_id = $_SESSION['id'];

$start_search=false;
if(isset($_POST['submit']))
{
    $search = $_POST['search'];
    $start_search=true;

    $sql = "SELECT * FROM  files WHERE subject like '%$search%' or category like '%$search%' or tag1 like '%$search%' or tag2 like '%$search%' AND author_id='$author_id'";
    $results = $con->query($sql);

}
?>

<!DOCTYPE html>
<html lang="fa">
	<head>
		<meta charset="utf-8">
		<title>Search Files</title>
		<link href="style99.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="style1">
		<nav class="navtop">
			<div>
				<h1>جستجو فایل</h1>
                <a href="myFiles.php"><i class="fas fa-file"></i>فایل های من</a>
				<a href="home.php"><i class="fas fa-home"></i>صفحه اصلی</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>خروج</a>
			</div>
		</nav>

        <form method="post">
        <div class="container">
        </br>
        <input type="search" name="search" placeholder="جستجو">
        <button type="submit" name="submit" class="btn">جستجو</button>
        </br>
        </br>
        </div>
        </form>
        
        <?php
        $id=1;
        if ($results->num_rows > 0) {
        ?>
        <div class="table">
            <table id="myFiles" class="table table-bordered table-striped m-2">
                <thead>
                    <th class="text-center">ردیف</th>
                    <th class="text-center">عنوان فایل</th>
                    <th class="text-center">دسته بندی</th>
                    <th class="text-center">برچسب ها</th>
                    <th class="text-center">جزئیات</th>
                </thead>
                <?php
                while($row = $results->fetch_assoc()) {
                    $array_id = explode(" ",$row["file_id"]);
                    $fileId = $array_id[0].'*'.$array_id[1];
                    $getFileId = $fileId.'*'.$author_id;
                ?>
                <tbody>
                    <tr>
                        <td>
                            <?php 
                            echo htmlentities($id)
                            ?>
                        </td>

                        <td>
                            <?php 
                            echo htmlentities($row["subject"]) 
                            ?>
                        </td>

                        <td>
                            <?php 
                            echo htmlentities($row["category"])
                            ?>
                        </td>

                        <td>
                            <?php 
                            $tag1 = $result->tag1;
                            $tag2 = $result->tag2;
                            echo htmlentities($row["tag1"].$row["tag2"])
                            ?>
                        </td>

                        <td>
                        <a href="fileDetails.php?id=<?php echo htmlentities($getFileId); ?>"><button i style='font-size:13px' class='fa fa-info-circle'></i></button></a>
                        </td>

                          </tr>
                          <?php 

                          $id++;

                          }
                      }
                      else{
                      if($start_search == true){
                      ?>
                      <div>
                          فایل مورد نظر پیدا نشد!!!!!
                      </div>
                      <?php
                      }     
                    }
                      ?>
                      </tbody>
                </table>
            </div>

	</body>
</html>
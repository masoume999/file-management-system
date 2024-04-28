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

if(isset($_POST['edit'])){
    $fname = $_POST['firstName'];
    $lname = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $username = $_SESSION['id'];

    if($fname!=null && $lname!=null && $email!=null && $phone!=null && $address!=null){
        if(strlen($phone) != 11){
            echo "<script>alert('شماره موبایل خود را به درستی وارد کنید.');</script>";
        }
        else{
            $stmt = $con->prepare("UPDATE users SET first_name='$fname',last_name='$lname',email='$email',phone_number='$phone',address='$address' WHERE user_id='$username'");
            $stmt->execute();
            $stmt->close();
                
            session_regenerate_id();
            $_SESSION['id'] = $username;
            echo "<script>alert('ویرایش پروفایل با موفقیت انجام شد.');</script>";
            echo "<script>window.location.href='profile.php'</script>";
        }
    }
    if($username==null || $password==null || $fname==null || $lname==null || $email==null || $phone==null || $address==null){
        echo "<script>alert('لطفا تمام اطلاعات را وارد نمایید.');</script>";
    }
}
?>

<!DOCTYPE html>
<html dir="rtl">
    <head>
        <meta charset="utf-8">
		<title>Edit Profile</title>
		<link href="style3.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>

        <div class="container">
        <div class="text">اطلاعات خود را وارد نمایید.</div>
        <form method="post">
           <div class="form-row">
              <div class="input-data">
                 <input type="text" value="<?php echo htmlentities( $_SESSION['firstname'])?>" name="firstName" id="firstName" required>
                 <div class="underline"></div>
              </div>
              <div class="input-data">
                 <input type="text" value="<?php echo htmlentities( $_SESSION['lastname'])?>" name="lastName" id="lastName" required>
                 <div class="underline"></div>
              </div>
           </div>
           <div class="form-row">
              <div class="input-data">
                 <input type="email" value="<?php echo htmlentities( $_SESSION['email'])?>" name="email" id="email" required>
                 <div class="underline"></div>
              </div>
              <div class="input-data">
                 <input type="number" value="<?php echo htmlentities( $_SESSION['phone'])?>" name="phone" id="phone" required>
                 <div class="underline"></div>
              </div>
           </div>
           <div class="form-row">
              <div class="input-data">
                 <input type="text" value="<?php echo htmlentities( $_SESSION['address'])?>" name="address" id="address" required>
                 <br />
                 <div class="underline"></div>
                 <br />
                 <div class="form-row submit-btn">
                    <div class="input-data">
                       <div class="inner"></div>
                       <input type="submit" name="edit" value="ثبت">
                    </div>
                 </div>
              </div>
           </div>
        </form>
     </div>
    </body>
</html>
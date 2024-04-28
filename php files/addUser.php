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


$i=false;
$sql = "SELECT user_id, FROM users";
$results = $con->query($sql);
if ($results->num_rows > 0) {
     while($row = $results->fetch_assoc()) {
        if($row["user_id"] == $_GET['id']){
            $stmt = $con->prepare("INSERT INTO temporary_users (user_id,password,first_name,last_name,email,phone_number,address,is_accepted) VALUES ('$username','$password','$fname','$lname','$email','$phone','$address','0')");
            $stmt->execute();
            $stmt->close();

            $i=true;
            break;
        }
    }
}

$stmt = $con->prepare("INSERT INTO temporary_users (user_id,password,first_name,last_name,email,phone_number,address,is_accepted) VALUES ('$username','$password','$fname','$lname','$email','$phone','$address','0')");
$stmt->execute();
$stmt->close();
?>
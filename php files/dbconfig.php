<?php

$servername = "localhost";
$username = 'root';
$password = "mysql";

define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','mysql');
define('DB_NAME','file_management');

try{
    $conn = new PDO('mysql:host=' . DB_HOST .';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $conn->exec('SET NAMES utf8'); //برای حروف فارسی
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
    
    echo $e->getMessage();
    
    }
    
?>
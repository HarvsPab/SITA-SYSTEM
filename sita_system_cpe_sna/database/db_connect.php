<?php

$host = 'localhost';
$dbname = 'sita_system';
$username = 'root';
$password = '';

$data = mysqli_connect($host, $username, $password, $dbname);
if($data==false){
    die("connection error");
}

?>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "filippobellini619222";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die('connection failed:' . mysqli_connect_error());

if(mysqli_connect_errno())
{
    printf("connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>
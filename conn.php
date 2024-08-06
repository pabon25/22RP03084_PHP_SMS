<?php
$sname = "localhost";
$uname = "root";
$pwd = "";
$dbname = "sms";

$conn = mysqli_connect($sname, $uname, $pwd, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

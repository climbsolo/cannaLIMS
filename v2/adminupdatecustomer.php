<?php 

include "includes.php";

logincheck();

$licensenumber = $_GET["licensenumber"];
$pw = $_GET["password"];

$password = md5($pw . $salt);

$sql = "update tbllicenses set password = :password where license_number = :licensenumber";
$stmt = $dbconn->prepare($sql);
$stmt->execute(array(':licensenumber'=>$licensenumber, ':password'=>$password));

echo "Customer record updated!";

?>
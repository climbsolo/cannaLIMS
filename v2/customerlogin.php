<?php 

include "includes.php";

$str = "failure";

$licensenumber = $_POST["licensenumber"];
$pw = $_POST["password"];

$password = md5($pw . $salt);

$sql = "select license_number from tbllicenses where license_number = :licensenumber and password = :password";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute(array(':licensenumber'=>$licensenumber, ':password'=>$password))) {
    while ($row = $stmt->fetch()) { 
        $_SESSION["cluid"] = $row["license_number"];
        $str = "success";
    }
}

echo $str;

?>
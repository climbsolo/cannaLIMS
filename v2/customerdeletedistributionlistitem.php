<?php 

include "includes.php";

$itemid = $_POST["id"];
$cluid = $_SESSION["cluid"];
if (strlen($cluid) < 1) {
    die();
}

$sql = "delete from tbldistributionlists where id = '$itemid'";
$stmt = $dbconn->prepare($sql);
$stmt->execute();

?>
<?php 

include "includes.php";

$licensenumber = $_SESSION["cluid"];

if (strlen($licensenumber) < 1) {
    die();
}

$email = $_POST["email"];
$receive_invoice_notifications = $_POST["receive_invoice_notifications"];

$sql = "insert into tbldistributionlists (licensenumber, email, receive_invoice_notifications) values (:licensenumber, :email, :receive_invoice_notifications)";
$stmt = $dbconn->prepare($sql);
$stmt->execute(array(':licensenumber'=>$licensenumber, ':email'=>$email, ':receive_invoice_notifications'=>$receive_invoice_notifications));

?>
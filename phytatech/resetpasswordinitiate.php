<?php

include "includes.php";

$resetpasswordid = $inOneDay;

$email = "";
if (isset($_POST["email"])) {
    $email = $_POST["email"];
}

$sql = "update tblusers set resetpasswordid = :resetpasswordid where email = :email";

$stmt = $dbconn->prepare($sql);
$stmt->execute(array(':resetpasswordid'=>$resetpasswordid, ':email'=>$email));

$subject = "Reset Your CannaLIMS Password";
$message = "<p>We have received a request to reset your CannaLIMS password.</p><p>Please click the link below to provide your new password.</p><p>If you feel you have received this email in error, please inform your Lab Director</p><p><a href=\"" . $homeurl . "/resetpassword.php?id=" . $resetpasswordid . "\">" . $homeurl . "/resetpassword.php?id=" . $resetpasswordid . "</a>";


sendemail($subject, $message, $email);


?>
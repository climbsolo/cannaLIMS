<?php 

include "includes.php";

$cluid = $_SESSION["cluid"];

if (strlen($cluid) < 1) {
    die();
}

$str = "";

$sql = "Select * from tbldistributionlists where licensenumber = '$cluid'";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {
        $id = $row["id"];       
        $email = $row["email"];
        $receive_invoice_notifications = $row["receive_invoice_notifications"];
        
       
        
        if ($receive_invoice_notifications == 'true') {
            $str .= "<table class=\"cptable table-striped\" style=\"margin-top:1em;\"><tr><td style=\"width:80%;\">$email (Receives Invoice Notifications)</td><td style=\"width:20%;text-align:right;\"><button onclick=\"deletedistributionlistitem('$id')\">Del</button></td></tr></table>";
        }
        else
        {
            $str .= "<table class=\"cptable table-striped\" style=\"margin-top:1em;\"><tr><td style=\"width:80%;\">$email</td><td style=\"width:20%;text-align:right;\"><button onclick=\"deletedistributionlistitem('$id')\">Del</button></td></tr></table>";
        }
        
        
        
    }
}

echo $str;

?>
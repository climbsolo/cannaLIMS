<?php 

// This file is run by Windows Task Scheduler. 

chdir(dirname(__FILE__));

include "includes.php";


$sql = "select licensenumber, count(*) from tblreports where (approved is not null and approved <> '') and (notified is null or notified = '') group by licensenumber";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {
        $licensenumber = $row["licensenumber"];
        $count = $row["count(*)"];
               
             
               
        $sql1 = "select * from tbldistributionlists where licensenumber = :licensenumber";
        $stmt1 = $dbconn->prepare($sql1);
        if ($stmt1->execute(array(':licensenumber'=>$licensenumber))) {
            while ($row1 = $stmt1->fetch()) {
                
                //$email = $row1["email"];
                
                $email = 'mike@cannasys.com';
                
                
                $message = "Greetings!<p>This is an auto-generated email from Phytatech Metrics & Solutions to notify you that there are $count new Sample Reports available for License Number: $licensenumber via our online Customer Portal.</p>Please visit our Portal at <a href=\"https://cannalims.com/phytatech/customerportal.php\">https://cannalims.com/phytatech/customerportal.php</a>, and login with your License Number and password. If you do not have a password yet, please contact us and we will set one up for you.</p><p>Thanks! - the Team at Phytatech</p>";
                $subject = "New Phytatech Sample Reports are available";
                $recipient = $email;
                
                sendemail($subject, $message, $recipient);
                
                $receive_invoice_notifications = $row1["receive_invoice_notifications"];
                
                echo $licensenumber . "<br />";
                echo "ri: " . $receive_invoice_notifications ."<br />";
                
                $icount = 0;
                
                if ($receive_invoice_notifications == "true") {
                    $sql3 = "select count(*) from tblsamples where (active is null or active = '' or active <> 'false') and quickbooks_insert_date=curdate() and license_number = :licensenumber";
                    $stmt3 = $dbconn->prepare($sql3);
                    if ($stmt3->execute(array(':licensenumber'=>$licensenumber))) {
                        while ($row3 = $stmt3->fetch()) {
                            
                            $icount = $row3["count(*)"];
                            
                            echo $icount;
                            
                            if ($icount > 0) {
                                $imessage = "Greetings!<p>This is an auto-generated email from Phytatech Metrics & Solutions to notify you that there is a new invoice for License Number: $licensenumber.</p>Please visit our Online Customer Portal at <a href=\"https://cannalims.com/phytatech/customerportal.php\">https://cannalims.com/phytatech/customerportal.php</a>, and login with your License Number and password to access the invoice. If you do not have a password yet, please contact us and we will set one up for you.</p><p>Thanks! - the Team at Phytatech</p>";
                                $isubject = "New Phytatech Invoice is available";
                                $irecipient = $email;                                
                                sendemail($isubject, $imessage, $irecipient);
                            }
                        }
                    }
                }
            }
        }       
    }    
}

$sql2 = "update tblreports set notified = '$nDateTimeGlobal' where (approved is not null and approved <> '') and (notified is null or notified = '')";
$stmt2 = $dbconn->prepare($sql2);
//$stmt2->execute();

die();
?>

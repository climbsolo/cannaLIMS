<?php 

// This file is run by Windows Task Scheduler. 

/*

$sql = "update tblsamples set quickbooks_insert_date = STR_TO_DATE('2015-10-01', '%Y-%m-%d') where quickbooks_insert_date = STR_TO_DATE('2015-10-01', '%Y-%m-%d')";

//quickbooks_insert_date = '" .  strtotime(2015-09-30) . "'";

echo $sql;

die();
*/

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
                
                $email = $row1["email"];                
                
                $message = "Greetings!<p>This is an auto-generated email from Phytatech Metrics & Solutions to notify you that there are $count new Sample Reports available for License Number: $licensenumber via our online Customer Portal.</p>Please visit our Portal at <a href=\"https://cannalims.com/phytatech/customerportal.php\">https://cannalims.com/phytatech/customerportal.php</a>, and login with your License Number and password. If you do not have a password yet, please contact us and we will set one up for you.</p><p>Thanks! - the Team at Phytatech</p>";
                $subject = "New Phytatech Sample Reports are available";
                $recipient = $email;
                
                //sendemail($subject, $message, $recipient);
            }
        }       
    }    
}

$sql4 = "select licensenumber, email from tbldistributionlists where receive_invoice_notifications = 'true'";
$stmt4 = $dbconn->prepare($sql4);
if ($stmt4->execute()) {
    while ($row4 = $stmt4->fetch()) {
        $ilicensenumber = $row4["licensenumber"];
        $iemail = $row4["email"];           
        $icount = 0;
      
        $sql3 = "select sample_id from tblsamples where (active is null or active = '' or active <> 'false') and quickbooks_insert_date=curdate() and license_number = :licensenumber";
        $stmt3 = $dbconn->prepare($sql3);
        if ($stmt3->execute(array(':licensenumber'=>$ilicensenumber))) {
            while ($row3 = $stmt3->fetch()) {           
             
                $imessage = "Greetings!<p>This is an auto-generated email from Phytatech Metrics & Solutions to notify you that there is a new invoice for License Number: $ilicensenumber.</p>Please visit our Online Customer Portal at <a href=\"https://cannalims.com/phytatech/customerportal.php\">https://cannalims.com/phytatech/customerportal.php</a>, and login with your License Number and password to access the invoice. If you do not have a password yet, please contact us and we will set one up for you.</p><p>Thanks! - the Team at Phytatech</p>";
                $isubject = "New Phytatech Invoice is available";
                $irecipient = $iemail;                                
                //sendemail($isubject, $imessage, $irecipient);
                
                echo "$ilicensenumber - $irecipient<br />";
                
                $sql5 = "update tblsamples set quickbooks_insert_date = subdate(CURDATE(), 1) where sample_id = :licensenumber and (active is null or active = '' or active <> 'false')";
                $stmt5 = $dbconn->prepare($sql5);
                //$stmt5->execute(array(':licensenumber'=>$ilicensenumber));
               
            }
        }
    }
}        

$sql2 = "update tblreports set notified = '$nDateTimeGlobal' where (approved is not null and approved <> '') and (notified is null or notified = '')";
$stmt2 = $dbconn->prepare($sql2);
//$stmt2->execute();

die();
?>

<?php 

include "includes.php";


$sql1 = "select value, id from tblchangehistory where value like '%September 1, 2015%'";
$stmt1 = $dbconn->prepare($sql1);
if ($stmt1->execute()) {
    while($row1 = $stmt1->fetch()) {
        
        $id = $row1["id"];
       $newdate = date('m/d/Y', strtotime($row1["value"]));
       
       $sql = "update tblchangehistory set value = '$newdate' where id = '$id'";
       $stmt = $dbconn->prepare($sql);
       //$stmt->execute();
    }
}   






?>


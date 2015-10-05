<?php 

include "includes.php";


$sql2 = "select filename, id, sampleid from tblreports";
$stmt2 = $dbconn->prepare($sql2);    
if ($stmt2->execute()) {            
    while($row2= $stmt2->fetch()) {
    
    $a = str_replace(".pdf", "", $row2["filename"]);
    
    $b = explode("-", $a);
    
    $testtype = strtolower($b[1]);
    $id = $row2["id"];
    $sampleid = $row2["sampleid"];
    
    $sql3 = "select count(*) from tblcannabinoids where sampleid like '%$sampleid" . "-%'";
    
    $x = $dbconn->query($sql3)->fetchColumn();
    if ($x > 0) {
       $sql5 = "update tblreports set testtype = 'homogeneity' where id = '$id'";
    }
    else
    {
        $sql5 = "update tblreports set testtype = '$testtype' where id = '$id'";
    }
  
    //$stmt5 = $dbconn->prepare($sql5);
    ///$stmt5->execute();
    
}
}








?>
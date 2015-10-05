<?php 

include "includes.php";

$sql = "select date_accepted, ndate_accepted, sample_id from tblsamples";
$stmt = $dbconn->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {
    $sample_id = $row["sample_id"];
    $adate = $row["date_accepted"];
    $ndate = $row["ndate_accepted"];
    $field_name = 'date_accepted';
    
    $sql1 = "insert into tblchangehistory (field_name, value, datetime, ndatetime, sample_id, table_name) select '$field_name', '$adate', '$adate','$ndate','$sample_id', 'tblsamples' from dual where not exists (select * from tblchangehistory where field_name = '$field_name' and sample_id = '$sample_id')";               
    $stmt1 = $dbconn->prepare($sql1);
    $stmt1->execute(); 
    
    //echo "$sql1<br />";
}    




?>
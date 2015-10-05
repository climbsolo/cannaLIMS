<?php 

include "includes.php";

$sampleid = $_POST["sampleid"];
$reporttype = $_POST["reporttype"];

//if (strlen($reporttype) > 0) {

$col = "date_" . strtolower($reporttype) . "_report_view";

$date = date("M j, Y g:i:s a");  

$sql = "update tblsamples set $col = :date where sample_id = :sampleid";
$stmt = $dbconn->prepare($sql);
$stmt->execute(array(':date'=>$date, ':sampleid'=>$sampleid));


//}

?>
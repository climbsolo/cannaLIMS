<?php 

include "includes.php";

//logincheck();


$arr = [];

$json = $_GET["json"];
$tablename = $_GET["tablename"];

$id_field = "";

if ($tablename == "tblsamples") {
    $id_field = "sample_id";
}

$arr = array();
$arr1 = array();
$cols = array();

//$json = '["204864","204863","204862","204861","204844","204842","204841","204834","204833","204832","204831","204830","204829","204828","204827","204814","204813","204812","204811","204810","204809","204808","204807","204806","204805","204804","204803","204802","204721","204720","204719","204718","204717","204708","204707","204706","204705","204704","204703","204702","204701","204691","204690","204688","204687","204686","204685","204684","204683","204682","204681","204680","204673","204672","204671","204670","204669","204668","204667","204666","204665","204664","204586","204585","204584","204583","204582","204581","204580","204579","204578","204577","204576","204575","204574","204573","204572","204571","204530","204529","204483","204482","204481","204480","204479","204478","204467","204466","204464","204463","204462","204459","204458","204457","204456","204455","204454","204453","204452","204451"]';

$arr = json_decode($json);

//$tablename = 'all_compounds';

if ($tablename == 'all_compounds') {

 
foreach ($arr as $sampleid) {
    
$headers = array();

$counter = 0;

$sql = "select * from tblcannabinoids limit 1";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
   $row = $stmt->fetch(PDO::FETCH_ASSOC);   
    foreach ($row as $key=>$val) {
    array_push($headers, $key);
    }
}

$sql = "select * from tblresidualsolvents limit 1";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
   $row = $stmt->fetch(PDO::FETCH_ASSOC);   
    foreach ($row as $key=>$val) {
    array_push($headers, $key);
    }
}

$sql = "select * from tblterpenes limit 1";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
   $row = $stmt->fetch(PDO::FETCH_ASSOC);   
    foreach ($row as $key=>$val) {
    array_push($headers, $key);
    }
} 

array_unshift($headers , 'METRC Number');
  
foreach ($arr as $sampleid) {
  
  $counter = $counter + 1;
  //echo $sampleid . "<br />";
  
$x = $dbconn->query("select count(*) from tblcannabinoids where sampleid = '$sampleid' and (dup is null or dup = '' or dup <> 'true')")->fetchColumn();
if ($x > 0) {
    $sql = "select * from tblcannabinoids where sampleid = '$sampleid' and (dup is null or dup = '' or dup <> 'true')";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
       $row = $stmt->fetch(PDO::FETCH_ASSOC);   
        foreach ($row as $key=>$val) {
        if (empty($val)) {
            $val = "";
        }
        $cols[$sampleid][$key . "_c"] = $val;
        }
    }
}
else
{
    $sql = "select * from tblcannabinoids limit 1";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
       $row = $stmt->fetch(PDO::FETCH_ASSOC);   
        foreach ($row as $key=>$val) {
       
        $cols[$sampleid][$key . "_c"] = "";
        }
    }    
}

$x = $dbconn->query("select count(*) from tblresidualsolvents where sampleid = '$sampleid' and (dup is null or dup = '' or dup <> 'true')")->fetchColumn();
if ($x > 0) {
    $sql = "select * from tblresidualsolvents where sampleid = '$sampleid' and (dup is null or dup = '' or dup <> 'true')";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
       $row = $stmt->fetch(PDO::FETCH_ASSOC);   
        foreach ($row as $key=>$val) {
        if (empty($val)) {
            $val = "";
        }
        $cols[$sampleid][$key . "_rs"] = $val;
        }
    }
}
else
{
    $sql = "select * from tblresidualsolvents limit 1";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
       $row = $stmt->fetch(PDO::FETCH_ASSOC);   
        foreach ($row as $key=>$val) {
       
        $cols[$sampleid][$key . "_rs"] = "";
        }
    }    
}

$x = $dbconn->query("select count(*) from tblterpenes where sampleid = '$sampleid' and (dup is null or dup = '' or dup <> 'true')")->fetchColumn();
if ($x > 0) {
    $sql = "select * from tblterpenes where sampleid = '$sampleid' and (dup is null or dup = '' or dup <> 'true')";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
       $row = $stmt->fetch(PDO::FETCH_ASSOC);   
        foreach ($row as $key=>$val) {
        if (empty($val)) {
            $val = "";
        }
        $cols[$sampleid][$key . "_t"] = $val;
        }
    }
}
else
{
    $sql = "select * from tblterpenes limit 1";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
       $row = $stmt->fetch(PDO::FETCH_ASSOC);   
        foreach ($row as $key=>$val) {
       
        $cols[$sampleid][$key . "_t"] = "";
        }
    }    
}

$metrcnumber = "";

$sql3 = "select metrc_number from tblsamples where sample_id = '$sampleid'";
$stmt3 = $dbconn->prepare($sql3);
if ($stmt3->execute()) {
    while ($row3 = $stmt3->fetch()) {
        $metrcnumber = $row3["metrc_number"];
    }
}

array_unshift($cols[$sampleid] , $metrcnumber);

 
if ($counter == 1) {array_push ($arr1, $headers);};
array_push ($arr1, $cols[$sampleid]);   

}

/*
?>
<pre>
<?php print_r($arr1) ?>
</pre>
<?php
*/


if(!is_dir('downloads')){
  mkdir ('downloads');
}

if(!is_dir('downloads/' . $_SESSION["luid"])){
  mkdir ('downloads/' . $_SESSION["luid"]);
}


echo json_encode($arr1);

die();   
    
}


}
  
$sql = "select * from $tablename limit 1";
$stmt = $dbconn->prepare($sql);
$stmt->execute();       
$row = $stmt->fetch(PDO::FETCH_ASSOC);

foreach ($row as $key=>$val) {
    array_push($cols, $key);
}

if ($tablename == "tblsamples") {    
    array_unshift($cols, "business_name");
}

array_push($arr1, $cols);


$in = "";

if ($arr[0] != "all") {
    
    foreach ($arr as $sample_id) {
    
    $in .= $sample_id . ",";
    
    }
    
    $in = rtrim($in, ',');
    
    $sql = "SELECT * FROM $tablename WHERE $id_field IN ($in)";
    
    if ($tablename == "tblsamples") {
         $sql = "SELECT tblclients.business_name, tblsamples.* from tblsamples inner join tblclients on tblsamples.client_id = tblclients.client_id WHERE tblsamples.sample_id IN ($in)";
    }
}


if ($arr[0] == "all") {
    
    $sql = "select * from $tablename order by 1 desc";
    
    // For tables with an "active" column
    if ($tablename == "tblsamples" || $tablename == "tblclients") {
        $sql = "select * from $tablename where (active is null or active = '' or active <> 'false')";
    }
}

    
    if(!is_dir('downloads')){
        mkdir ('downloads');
    }

    
    if(!is_dir('downloads/' . $_SESSION["luid"])){
        mkdir ('downloads/' . $_SESSION["luid"]);
    }
    
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
       
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {    
       array_push($arr1, $row);
    }

/*    
?>
<pre>
<?php print_r($arr1) ?>
</pre>
<?php
*/
    
    echo json_encode($arr1);

?>
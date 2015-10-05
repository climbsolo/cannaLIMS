<?php

include "includes.php";

$csv = file('temp/reports.csv');

$arr = [];

foreach ($csv as $line) {
    $arr[] = str_getcsv($line);
}

// Get rid of header row
unset($arr[0]);

foreach ($arr as $key=>$val) {
    
 
    
    $arr1[$val[0]]['filename'] = $val[1];
    $arr1[$val[0]]['filepath'] = $val[2];
    $arr1[$val[0]]['adatecreated'] = $val[3];
    $arr1[$val[0]]['ndatecreated'] = $val[4];
    $arr1[$val[0]]['adatecheckedin'] = $val[5];
    $arr1[$val[0]]['ndatecheckedin'] = $val[6];
    $arr1[$val[0]]['businessname'] = $val[7];
    $arr1[$val[0]]['licensenumber'] = $val[8];
    
    $arr1[$val[0]]['batchid'] = $val[9];
    $arr1[$val[0]]['productname'] = $val[10];
    $arr1[$val[0]]['sampleid'] = $val[11];
    $arr1[$val[0]]['tvsampleid'] = $val[12];
    $arr1[$val[0]]['metrcnumber'] = $val[13];
    $arr1[$val[0]]['approved'] = $val[14];
    $arr1[$val[0]]['notified'] = $val[15];

}

?>
<pre>
<?php print_r($arr1) ?>
</pre>
<?php

foreach($arr1 as $key=>$val) {
    $sql = "insert into tblreports (filename) value ('')";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
    $report_id = $dbconn->lastInsertId();
    
        
    foreach ($val as $key1=>$val1) {
       
        
        $sql = "update tblreports set $key1 = :val1 where id = '$report_id'";
        $stmt = $dbconn->prepare($sql);
       // $stmt->execute(array(':val1'=>$val1));
        
    }
    
}


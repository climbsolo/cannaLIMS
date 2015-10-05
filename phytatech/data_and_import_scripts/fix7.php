<?php

include "includes.php";

$csv = file('temp/samples3.csv');

$arr = [];

foreach ($csv as $line) {
    $arr[] = str_getcsv($line);
}

// Get rid of header row
//unset($arr[0]);

/*
?>
<pre>
<?php print_r($arr) ?>
</pre>
<?php
*/

$arr1 = [];



foreach ($arr as $key=>$val) {
    
    if (strlen($val[4]) > 0 || strlen($val[5]) > 0) {        
        $arr1[$val[0]]['wet_mass'] = $val[4];
        $arr1[$val[0]]['dry_mass'] = $val[5];  
    }    

}


foreach ($arr1 as $key=>$val) {
    
      
        $wetmass = $val['wet_mass'];
        $drymass = $val['dry_mass'];
    
     if (strlen($wetmass) > 0 || strlen($drymass) > 0) {  
    $sql = "update tblsamples set wet_mass = '$wetmass', dry_mass = '$drymass' where sample_id = '$key'";
    $stmt = $dbconn->prepare($sql);
    //$stmt->execute();
    
    echo $sql . "<br />";

    
    }  
}



//###################

/*
foreach ($arr as $key=>$val) {
    
    //if (strlen($val[25]) > 0) {        
        $arr1[$val[0]]['package_amount'] = $val[25]; 
    //}    

}


foreach ($arr1 as $key=>$val) {
    foreach($val as $key1=>$val1) {
        $packageamount = $val1;
    
     if (strlen($packageamount) > 0) {  
    $sql = "update tblsamples set package_amount = '$packageamount' where sample_id = '$key'";
    $stmt = $dbconn->prepare($sql);
    //$stmt->execute();
    
    echo $sql . "<br />";
     }
    
    }  
}

*/

?>


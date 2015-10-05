<?php

include "includes.php";

$csv = file('temp/pricing.csv');

$arr = [];

foreach ($csv as $line) {
    $arr[] = str_getcsv($line);
}

// Get rid of header row
unset($arr[0]);

/*
?>
<pre>
<?php print_r($arr) ?>
</pre>
<?php
*/




foreach ($arr as $key=>$val) {
    
     
   $licensenumber = $val[0];
    
    $sql = "select id from tbllicenses where license_number = :licensenumber";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':licensenumber'=>$licensenumber))) {
        while ($row = $stmt->fetch()) {
            $licenseid = $row["id"];
        }
    }
     
    foreach ($val as $key1=>$val1) {
       
        if ($key1 > 0) {
          
            $sql = "insert into tbllicenseproductpricexref (license_id, product_id, price) values (:license_id, :product_id, :price)";
            $stmt = $dbconn->prepare($sql);
            //$stmt->execute(array(':license_id'=>$licenseid, ':product_id'=>$key1, ':price'=>$val1));
            echo $licenseid . ": " . $key1 . " = " . $val1 . "<br />";
        
        }
    }    
}






?>
<?php

include "includes.php";

$csv = file('temp/samples3.csv');

$arr = [];

foreach ($csv as $line) {
    $arr[] = str_getcsv($line);
}

// Get rid of header row
unset($arr[0]);

/*
?>
<pre>
<?php print_r($arr[0]) ?>
</pre>
<?php
*/


$arr1 = [];
foreach ($arr as $sample) {
    
 
/*
 ?>
<pre>
<?php print_r($sample) ?>
</pre>
<?php
*/
    
    $sampleid = $sample[0];
    $clientid = '';
    
    $businessname = $sample[2];
    $sql = "select client_id from tblclients where business_name = :businessname";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':businessname'=>$businessname))) {
        while ($row = $stmt->fetch()) {
            $clientid = $row["client_id"];
        }
    }
   
   /*
   if (strlen($clientid < 1)) {
        echo $businessname . "<br />";
        continue;        
    }
    
    $licensenumber = $sample[9];
    $x = $dbconn->query("select count(*) from tbllicenses where license_number = '$licensenumber'")->fetchColumn();
    if ($x < 1) {   
        echo "Missing License: $businessname - $licensenumber<br />";
        continue;
    }
    
    */
    
    
    $arr1['batch_id'] = $sample[1];
    $arr1['client_id'] = $clientid;
    $arr1['date_cannabinoids_report_view'] = $sample[3];
    $arr1['ndate_cannabinoids_report_view'] = strtotime(date($sample[3]));
    $arr1['date_data_input_into_metrc_workflow'] = $sample[4];
    $arr1['ndate_data_input_into_metrc_workflow'] = strtotime(date($sample[4]));
    $arr1['date_accepted'] = $sample[5];
    $arr1['ndate_accepted'] = strtotime(date($sample[5]));
    $arr1['date_destroyed'] = $sample[6];
    $arr1['ndate_destroyed'] = strtotime(date($sample[6]));
    $arr1['dry_mass'] = $sample[7];
    $arr1['license_number'] = $sample[9];
    $arr1['manifest_id'] = $sample[10];
    $arr1['metrc_number'] = $sample[11];
    $arr1['notes'] = $sample[12];
    $arr1['package_amount'] = $sample[13];
    $arr1['product_name'] = $sample[14];
    $arr1['product_type'] = $sample[15];
    $arr1['date_report_approval_workflow'] = $sample[16];
    $arr1['ndate_report_approval_workflow'] = strtotime(date($sample[16]));
    $arr1['date_report_generation_workflow'] = $sample[17];
    $arr1['ndate_report_generation_workflow'] = strtotime(date($sample[17]));
    $arr1['date_residualsolvents_report_view'] = $sample[18];
    $arr1['ndate_residualsolvents_report_view'] = strtotime(date($sample[18]));
    $arr1['rush_order'] = $sample[19];
    $arr1['sample_mass'] = $sample[22];
    $arr1['storage_location'] = $sample[26];
    $arr1['date_terpenes_report_view'] = $sample[27];
    $arr1['ndate_terpenes_report_view'] = strtotime(date($sample[27]));
    $arr1['date_test_completion_workflow'] = $sample[28];
    $arr1['ndate_test_completion_workflow'] = strtotime(date($sample[28]));
    $tests_to_perform = $sample[29];
    
    $arrt = explode(",", $tests_to_perform);
    
    $arr5 = array();

    foreach($arrt as $t) {
       //echo $key . " - " . $t . "<br />";
       
       $t = trim($t);
       
      if ($t == "Potency") {           
           $arr5["managesample_potency_checkbox"] = "$t";           
       }
       
       if ($t == "Homogeneity") {
            $arr5["managesample_homogeneity_checkbox"] = "$t";
       }
       
       if ($t == "Residual Solvents") {
            $arr5["managesample_residual_solvents_checkbox"] = "$t";
       }
       
       if ($t == "Terpenes") {
            $arr5["managesample_terpenes_checkbox"] = "$t";
       }       
       
   }
   
    
    $arr1['tests_to_perform'] = json_encode($arr5);
    
    $arr1['wet_mass'] = $sample[32];
    $arr1['created_by'] = $sample[33];
    $arr1['date_created'] = $sample[34];
    
    $arr7[$sampleid] = $arr1;
      
}

/*  
   ?>
   <pre>
   <?php print_r($arr7) ?>
   </pre>
   <?php
*/
  
   foreach($arr7 as $key=>$val) {

        //$sql = "insert into tblsamples (sample_id) values ($key)";
        //$stmt = $dbconn->prepare($sql);
        //$stmt->execute();  

        //echo $sql . "<br />";
        
        foreach ($val as $key1=>$val1) {

        $sql = "update tblsamples set $key1 = :val1 where sample_id = :key";
        $stmt = $dbconn->prepare($sql);
        //$stmt->execute(array(':val1'=>$val1, ':key'=>$key));  
        
        echo "update tblsamples set $key1 = '$val1' where sample_id = '$key'" . "<br />";
        
        }
   
   }
       
       
       

?>
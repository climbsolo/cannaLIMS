<?php 

include "includes.php";

//$csv = file('temp/Clients.csv');
//$licensecsv = file('temp/Licenses.csv');
//$csv = file('temp/samples2.csv');

//$csv = file('temp/v1t.csv');

//$clientdata = [];
//$licensedata = [];
//$sampledata = [];

//$arrclients = [];
//$arrlicenses = [];

////$arr1 = [];
//

//foreach ($csv as $line) {
  //  $arr1[] = str_getcsv($line);
//}


/*
?>
<pre>
<?php print_r($arr1) ?>
</pre>
<?php
*/

/*
foreach($arr1 as $key=>$val) {
    
    $arr = [];
    $id = $val[0];
    
    //Residual Solvents
    /*
    $arr['sampleid'] = $val[1];
    $arr['adatetime'] = $val[2];
    $arr['ndatetime'] = $val[3];
    $arr['butane'] = $val[4];
    $arr['dichloromethane'] = $val[5];
    $arr['hexane'] = $val[6];
    $arr['benzene'] = $val[7];
    $arr['heptane'] = $val[8];
    $arr['toluene'] = $val[9];
    $arr['xylene'] = $val[10];
    $arr['instrumentname'] = $val[11];
    $arr['injectiondate'] = $val[12];
    $arr['ninjectiondate'] = $val[13];
    $arr['sampletype'] = $val[14];
    $arr['isobutane'] = $val[15];
    */
    
    /*
    //Terpenes
    $id = $val[0];
    $arr['sampleid'] = $val[1];
    $arr['adatetime'] = $val[2];
    $arr['ndatetime'] = $val[3];
    $arr['injectiondate'] = $val[4];
    $arr['ninjectiondate'] = $val[5];
    $arr['alphapinene'] = $val[6];
    $arr['betapinene'] = $val[7];
    $arr['myrcene'] = $val[8];
    $arr['limonene'] = $val[9];
    $arr['terpinolene'] = $val[10];
    $arr['linalool'] = $val[11];
    $arr['alphaterpineol'] = $val[12];
    $arr['betacaryophyllene'] = $val[13];
    $arr['humulene'] = $val[14];
    $arr['cisocimene'] = $val[15];
    $arr['sampletype'] = $val[16];
    $arr['instrumentname'] = $val[17];
    */
    
    
   /*
   $x = $dbconn->query("select count(*) from tblterpenes where id = '$id'")->fetchColumn();
    if ($x < 1) {        
        $sql = "insert into tblterpenes (id) value ('$id')";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute();        
    }
    
    foreach ($arr as $key=>$val) {
        $sql = "update tblterpenes set $key = :val where id = :id";
         $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':val'=>$val, ':id'=>$id));       
        
    }
    */
    
    
    
    
    /*
    
    // Cannabinoids
    
    if ($id > 0) {
    
    $arr['sampleid'] = $val[1];
    $arr['adatetime'] = $val[2];
    $arr['ndatetime'] = $val[3];
    $arr['thca'] = $val[4];
    $arr['thc'] = $val[5];
    $arr['cbda'] = $val[6];
    $arr['cbd'] = $val[7];
    $arr['cbca'] = $val[8];
    $arr['cbc'] = $val[9];
    $arr['cbga'] = $val[10] ;
    $arr['cbg'] = $val[11];
    $arr['cbna'] = $val[12];
    $arr['cbn'] = $val[13];
    $arr['thcva'] = $val[14];
    $arr['thcv'] = $val[15];
    $arr['cbdva'] = $val[16];
    $arr['cbdv'] = $val[17];
    $arr['cbdla'] = $val[18];
    $arr['sampletype'] = $val[19];
    $arr['instrumentname'] = $val[20];
    $arr['businessname'] = $val[21];
    $arr['thc_standard_deviation'] = $val[22];
    $arr['thc_relative_standard_deviation'] = $val[23];
    $arr['timestamp'] = $val[25];
    $arr['dup'] = $val[26];
    $arr['cannabinoidsreporttimestamp'] = $val[27];
    $arr['licensenumber'] = $val[28];
    $arr['subsamplemass'] = $val[29];
    $arr['cbla'] = $val[30];
    $arr['tocopherol'] = $val[31];
    
    $x = $dbconn->query("select count(*) from tblcannabinoids where id = '$id'")->fetchColumn();
    if ($x < 1) {        
        $sql = "insert into tblcannabinoids (id) value ('$id')";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute();        
    }
    
    foreach ($arr as $key=>$val) {
        $sql = "update tblcannabinoids set $key = :val where id = :id";
         $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':val'=>$val, ':id'=>$id));       
        
    }
    
    
    ?>
<pre>
<?php print_r($arr) ?>
</pre>
<?php

    
}

*/


//}

/*
?>
<pre>
<?php print_r($arrcannabinoids) ?>
</pre>
<?php
*/

/*
// Get csv rows
foreach ($clientcsv as $line) {
    $clientdata[] = str_getcsv($line);
}

foreach ($licensecsv as $line) {
    $licensedata[] = str_getcsv($line);
}
*/


/*
foreach ($samplecsv as $line) {
    $sampledata[] = str_getcsv($line);
}

echo count($sampledata);

for($i = 0, $l = count($sampledata); $i < $l; ++$i) {

?>
<pre>
<?php print_r($sampledata[$i][46]) ?>
</pre>
<?php

}

*/


/*
$sql = "select business_name, client_id from tblclients";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {
        $business_name = $row["business_name"];
        $client_id = $row["client_id"];
        
        $sql1 = "update tbllicenses set client_id = :client_id where client_id = :business_name";
               
        $stmt1 = $dbconn->prepare($sql1);
        $stmt1->execute(array(':client_id'=>$client_id, ':business_name'=>$business_name));
        
    }
} 

die();

*/

/*

foreach ($licensedata as $license) {
    $license_number = $license[3];
    $date_expiration = $license[4];
    $ndate_expiration = strtotime(date($date_expiration));
    $license_type = $license[5];
    $client_id = $license[8];
    $hide_total_potential_cannabinoids_on_reports = $license[9];
    $created_by = $license[12];
    $date_created = $license[14];
    
    $sql = "insert into tbllicenses (license_number, license_type, date_expiration, ndate_expiration, client_id, hide_total_potential_cannabinoids_on_reports, created_by, date_created) values (:license_number, :license_type, :date_expiration, :ndate_expiration, :client_id, :hide_total_potential_cannabinoids_on_reports, :created_by, :date_created)";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':license_number'=>$license_number, ':license_type'=>$license_type, ':date_expiration'=>$date_expiration, ':ndate_expiration'=>$ndate_expiration, ':client_id'=>$client_id, ':hide_total_potential_cannabinoids_on_reports'=>$hide_total_potential_cannabinoids_on_reports, ':created_by'=>$created_by, ':date_created'=>$date_created));    
    
}

*/

/*
foreach ($clientdata as $client) {
    
    $businessname = $client[0];
    $email = $client[2];
    $address1 = $client[3];
    $address2 = $client[4];
    $city = $client[5];
    $state = $client[6];
    $zip = $client[7];
    $phone = $client[8];
    $createdby = $client[12];
    $datecreated = $client[14];
    
    $sql = "insert into tblclients (business_name, email, business_address1, business_address2, business_city, business_state, business_zip, business_phone, created_by, date_created) values (:business_name, :email, :business_address1, :business_address2, :business_city, :business_state, :business_zip, :business_phone, :created_by, :date_created)";
    $stmt = $dbconn->prepare($sql);
    //$stmt->execute(array(':business_name'=>$businessname, ':email'=>$email, ':business_address1'=>$address1, ':business_address2'=>$address2, ':business_city'=>$city, ':business_state'=>$state, ':business_zip'=>$zip, ':business_phone'=>$phone, ':created_by'=>$createdby, ':date_created'=>$datecreated));
    //$client_id = $dbconn->lastInsertId();
      
    //$arrclients[$client_id] = $businessname;    
}

*/

/*
$sampledata = $arr1;

$arr2 = array();

foreach ($sampledata as $sample) {
    
    if ($sample[0] > 206022 && $sample[0] < 206141) {
    
    $arr = [];
    $arr["batch_id"] = $sample[4];
    $arr["client_id"] = $sample[6];
    $arr["date_cannabinoids_report_view"] = $sample[7];
    $arr["ndate_cannabinoids_report_view"] = strtotime(date($sample[7]));
    $arr["date_data_input_into_metrc_workflow"] = $sample[12];
    $arr["ndate_data_input_into_metrc_workflow"] = strtotime(date($sample[12]));
    $arr["date_accepted"] = $sample[13];
    $arr["ndate_accepted"] = strtotime(date($sample[13]));
    $arr["date_destroyed"] = $sample[14];
    $arr["ndate_destroyed"] = strtotime(date($sample[14]));
    $arr["dry_mass"] = $sample[15];
    $arr["license_number"] = $sample[18];
    $arr["manifest_id"] = $sample[19];
    $arr["metrc_number"] = $sample[20];
    $arr["notes"] = $sample[21];
    $arr["package_amount"] = $sample[23];
    $arr["product_name"] = $sample[24];
    $arr["product_type"] = $sample[25];
    $arr["date_report_approval_workflow"] = $sample[26];
    $arr["ndate_report_approval_workflow"] = strtotime(date($sample[26]));
    $arr["date_report_generation_workflow"] = $sample[28];
    $arr["ndate_report_generation_workflow"] = strtotime(date($sample[28]));
    $arr["rush_order"] = $sample[33];
    $arr["sample_mass"] = $sample[36];
    $arr["sub_sample_mass_cannabinoids"] = $sample[38];
    $arr["sub_sample_mass_residual_solvents"] = $sample[39];
    $arr["sub_sample_mass_terpenes"] = $sample[40];
    $arr["storage_location"] = $sample[41];
    $arr["date_test_completion_workflow"] = $sample[46];
    $arr["ndate_test_completion_workflow"] = strtotime(date($sample[46]));    
    $arr["tests_to_perform"] = $sample[47];
    $arr["unused_mass"] = $sample[49];
    $arr["wet_mass"] = $sample[51];
    $arr["created_by"] = $sample[53];
    $arr["date_created"] = $sample[55];
    
    $arr2[$sample[0]] = $arr;
    
    $dc = $arr["date_test_completion_workflow"];
    $ndc = strtotime(date($dc));
    
    $sql = "update tblsamples set date_test_completion_workflow = :dc, ndate_test_completion_workflow = :ndc where sample_id = :key";
    $stmt = $dbconn->prepare($sql);
    //$stmt->execute(array(':dc'=>$dc, ':ndc'=>$ndc, ':key'=>$sample[0])); 
    
    //echo "update tblsamples set date_test_completion_workflow = $dc, ndate_test_completion_workflow = $ndc where sample_id = " . $sample[0] . "<br />";
    
}
}
*/

/*
foreach ($arr2 as $key=>$val) {
    if (is_int($key)) {
        $sql = "insert into tblsamples (sample_id) value (:key)";
        $stmt = $dbconn->prepare($sql);
        //$stmt->execute(array(':key'=>$key));
    }    
}

*/


/*
foreach ($arr2 as $key=>$val) {  
  
    if (is_int($key)) {
    
    foreach ($val as $key1=>$val1) {
        $sql = "update tblsamples set $key1 = :val1 where sample_id = :key";
        $stmt = $dbconn->prepare($sql);
        //$stmt->execute(array(':val1'=>$val1, ':key'=>$key)); 
        
        //echo "<pre> tblsamples set $key1 = $val1 where sample_id = $key" . "</pre>";
        
    } 
    } 

}    
}

echo 'done';

*/


/*
$sql = "select business_name, client_id from tblclients";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {
        $business_name = $row["business_name"];
        $client_id = $row["client_id"];
        
        $sql1 = "update tblsamples set client_id = :client_id where client_id = :business_name";
               
        $stmt1 = $dbconn->prepare($sql1);
        $stmt1->execute(array(':client_id'=>$client_id, ':business_name'=>$business_name));
        
    }
}  
*/


/*
$arrttp = array();
$arr2 = [];
$arr3 = [];

$sql = "select sample_id, tests_to_perform from tblsamples where tests_to_perform not like '{%'";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {
            
        $ttp = $row["tests_to_perform"];
        $sample_id = $row["sample_id"];
        
        $arr3[$sample_id]["tests_to_perform"] = $ttp;
        
    }  
}



$arr5 = [];

foreach ($arr3 as $key=>$val) {
    
    $tests_to_perform = $val["tests_to_perform"];
   
    $arr = explode(",", $tests_to_perform);

    foreach($arr as $t) {
       //echo $key . " - " . $t . "<br />";
       
       $t = trim($t);
       
      if ($t == "Potency") {           
           $arr5[$key]["managesample_potency_checkbox"] = "$t";           
       }
       
       if ($t == "Homogeneity") {
            $arr5[$key]["managesample_homogeneity_checkbox"] = "$t";
       }
       
       if ($t == "Residual Solvents") {
            $arr5[$key]["managesample_residual_solvents_checkbox"] = "$t";
       }
       
       if ($t == "Terpenes") {
            $arr5[$key]["managesample_terpenes_checkbox"] = "$t";
       } 
if ($t == "Microbial") {
            $arr5[$key]["managesample_microbial_checkbox"] = "$t";
       }

if ($t == "Pesticides") {
            $arr5[$key]["managesample_pesticides_checkbox"] = "$t";
       }
       
   }
    
}
   
   foreach($arr5 as $key=>$val) {       
       $arr6[$key] = json_encode($val);       
   }
   
   foreach($arr6 as $key=>$val) {    
       
    $sql = "update tblsamples set tests_to_perform = :val where sample_id = :key";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':val'=>$val, ':key'=>$key));
    
    echo "<pre>update tblsamples set tests_to_perform = $val where sample_id = $key</pre>";
           
   }
*/

/*

$sql = "select * from tblsamples";
$stmt = $dbconn->prepare($sql);
$stmt->execute();
 //$row = $stmt->fetch(PDO::FETCH_ASSOC);
    
   /*
   ?>
    <pre>
    <?php print_r($row); ?>
    </pre>
    <?php 
    */
 

$sql = "select * from tblsamples where (completed = '' or completed is null or completed <> 'true')";
$stmt = $dbconn->prepare($sql);
$stmt->execute(); 

    while ($row = $stmt->fetch()) {    
        $a = $row["date_data_input_into_metrc_workflow"];
        $b = $row["date_report_approval_workflow"];
        $c = $row["date_report_generation_workflow"];
        $d = $row["date_test_completion_workflow"];
        $e = $row["sample_id"];
    
    //echo "$e: $a - $b - $c - $d<br />";
    
     if ((strlen($a) > 0) && (strlen($b) > 0) && (strlen($c) > 0) && (strlen($d) > 0)) {
        
        $sql1 = "update tblsamples set completed = 'true' where sample_id = '$e'";
        $stmt1 = $dbconn->prepare($sql1);
        //$stmt1->execute(); 
        echo $sql1 . "<br />";
        

        
    }
  
   
     }
     
 
    
     /*
     
     foreach ($arr1 as $key=>$val) {
         
         foreach ($val as $key1=>$val1) {
             
            if (strlen($val1) > 0) {

            $olddate = date("m/d/Y", strtotime($val1));

            //echo $val1 . "<br />";

            $sql = "update tblsamples set $key1 = '$olddate' where sample_id = '$key'";
            $stmt = $dbconn->prepare($sql);
            $stmt->execute();
             
             }
             
         }
         
     }
     
     */
     
     

     
     
     

/*
$sql = "select date_test_completion_workflow, sample_id from tblsamples";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {
        $ndate = $row["date_test_completion_workflow"];
        if(is_numeric($ndate)) {        
            $adate = date("m/d/Y", $ndate);
            $sample_id = $row["sample_id"];
            echo $sample_id . ": " . $adate . " -  $ndate<br />";
            $sql1 = "update tblsamples set date_test_completion_workflow = :adate, ndate_test_completion_workflow = :ndate where sample_id = :sample_id";
            $stmt1 = $dbconn->prepare($sql1);
            $stmt1->execute(array(':adate'=>$adate, ':ndate'=>$ndate, ':sample_id'=>$sample_id));
        }
    }
}
*/


/*
$sql = "SELECT * FROM datapoints where compoundid = '70'";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {
        $sample_id = $row["SampleID"];
        $tocopherol_peak_area = $row["PeakArea"];
        
        $sql1 = "update tblsamples set tocopherol_peak_area = :tocopherol_peak_area where sample_id = :sample_id";
        $stmt1 = $dbconn->prepare($sql1);
        //$stmt1->execute(array(':tocopherol_peak_area'=>$tocopherol_peak_area, ':sample_id'=>$sample_id));
        echo "update tblsamples set tocopherol_peak_area = $tocopherol_peak_area where sample_id = $sample_id<br />";
        
        
        
    }
}

*/

?>



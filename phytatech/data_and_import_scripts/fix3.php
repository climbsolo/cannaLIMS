<?php 

include "includes.php";

 $directory = 'uploads/old';

    if (! is_dir($directory)) {
        exit('Invalid diretory path');
    }

    $files = array();
    
    $filecount = 0;

    foreach (scandir($directory) as $file) {
        if ('.' === $file) continue;
        if ('..' === $file) continue;
        
        $filecount = $filecount + 1;

        
        if(strpos($file, "_") != false) {
            
            $arr = explode("_", $file);
            
            $instrument = $arr[1];
            $type = $arr[2];   
            
            $csv = file($directory . "/" . $file);
            
           // echo "<h1>$file #############################################</h1>";
            
            $arr1 = [];

            $counter = 0;
            foreach ($csv as $line) {
                $arr1[] = str_getcsv($line);
                
                $sampleid = $arr1[$counter][0];               
                
                if (strtolower($sampleid) != "sample name"){
                    if (strtolower($type) == "rs") {
                    //if (strpos($sampleid, "-") > -1) {
                        
                        //$arrsntemp = explode("-", $sampleid);
                        //$sntemp = $arrsntemp[0];
                            
                        //if ((strlen($sampleid) > 0) && (is_numeric($sntemp))) { 
                        if ((strlen($sampleid) > 0) && (is_numeric($sampleid))) {                         
                            
                            $injectiondate = $arr1[$counter][2];
                            $subsample_mass = $arr1[$counter][1];
                            
                            $ninjectiondate = strtotime($injectiondate);
                            
                            $arr2[$sampleid]['filename'] = $file;
                            $arr2[$sampleid]['sntemp'] = $sampleid;
                            $arr2[$sampleid]['subsample_mass'] = $subsample_mass;
                            $arr2[$sampleid]['injection_date'] = $injectiondate;
                            $arr2[$sampleid]['ninjection_date'] = $ninjectiondate;
                            //$arr2[$sampleid]['sntemp'] = $sntemp; 

                            $vals = array(); 
                             
                            for ($i = 4; $i <= sizeof($arr1[$counter]); $i = $i + 2) { 

                                array_push($vals, array($arr1[$counter][$i], $arr1[$counter][$i-1]));

                            }     

                            for ($i = 0; $i <= sizeof($vals); $i++) { 
                                
                                $ret = $vals[$i][0];
                                $area = $vals[$i][1];
                                 
                                if ($instrument == '5890') {
                                    if ($ret >= 2.410 && $ret <= 2.419) {
                                         $arr2[$sampleid]['dichloromethane_peak_area'] = $area; 
                                    }
                                }
                                
                                if ($instrument == '6890') {
                                    if ($ret >= 2.140 && $ret <= 2.149) {
                                         $arr2[$sampleid]['dichloromethane_peak_area'] = $area; 
                                    }
                                }

                           }
                        

                            
                        }
                                            
                    //}
                }
                    /*if ((strlen($sampleid) < 1) || (!is_numeric($sntemp))) {
                        //continue;
                    }
                    //else
                    {                                        
                        //echo $sampleid . "<br />";
                    }*/
                }
                
                $counter = $counter + 1;
              
if ($counter > 3) {
    //break;
}
              
                
            }         
        
        }
        
    }
    
    /*
    ?>
    <pre>
    <?php print_r($arr2) ?>
    </pre>
    <?php 
    */
    
    
    foreach($arr2 as $key=>$val) {
        
    $sntemp = $val['sntemp'];
    $dichloromethane_peak_area = $val['dichloromethane_peak_area'];
    $filename = $val['filename'];
    $injection_date = $val['injection_date'];
    $ninjection_date = $val['ninjection_date'];
    $subsamplemass = $val['subsample_mass'];
  
    $arr2[$sntemp]['subsample_mass'] = $subsamplemass;
    $arr2[$sntemp]['injection_date'] = $injection_date;
    $arr2[$sntemp]['ninjection_date'] = $ninjection_date;
    $arr2[$sntemp]['dichloromethane_peak_area'] = $dichloromethane_peak_area;
    
        
    }
    
    foreach ($arr2 as $key=>$val) {
                
        $injectiondate = $val['injection_date'];
        $ninjectiondate = $val['ninjection_date'];
        $subsamplemass = $val['subsample_mass'];
        $dichloromethane_peak_area = $val['dichloromethane_peak_area'];
        $sampleid = $key;
        
        $sql = "update tblresidualsolvents set injectiondate = '$injectiondate', ninjectiondate = '$ninjectiondate', subsamplemass = '$subsamplemass' where sampleid = '$sampleid'";
        //$stmt = $dbconn->prepare($sql);
        //$stmt->execute();
        echo $sql . "<br />";
        
        $sql = "update tblsamples set sub_sample_mass_residual_solvents = '$subsamplemass' where sample_id = '$sampleid'";
        //$stmt = $dbconn->prepare($sql);
        //$stmt->execute();
        echo $sql . "<br />";
        
        $sql = "update tblsamples set dichloromethane_peak_area = '$dichloromethane_peak_area' where sample_id = '$sampleid'";
        //$stmt = $dbconn->prepare($sql);
        //$stmt->execute();
        echo $sql . "<br /><br />";
        
        
        
    }
    

    ?>
    <pre>
    <?php 
    
    
    echo "files: " . $filecount . " - " . sizeOf($arr2) . "<br />";
    print_r($arr2) 
    
    ?>
    </pre>
    <?php






//###########################################

/*
$arr = array();
$arr1 = [];

$sql = "delete client_id from tblclients where active = 'false'";


$stmt = $dbconn->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

   array_push($arr, $row["client_id"]);
}


foreach ($arr as $key=>$val) {
    
   $x = $dbconn->query("select count(*) from tbllicenses where client_id = '$val'")->fetchColumn();
   
    $arr1[$val] = $x;
    
}


?>
<pre>
<?php print_r($arr1) ?>
</pre>
<?php

*/

?>
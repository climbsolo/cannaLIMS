<?php 


/*

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
        
        if ($filecount != 46) {
            
            continue;
            
            
            echo $file;
            die();
            break;
            
        }
        
        $file1 = explode(".", $file);
        
        $file2 = $file1[0];
                
        if(strpos($file2, "_") != false) {
            
            $arr = explode("_", $file2);
            
            $instrument = $arr[1];
            $type = $arr[2];                        
                      
            $csv = file($directory . "/" . $file, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);         
            
            $arr1 = [];

            foreach ($csv as $line) {
                             
                $arr1[] = str_getcsv($line);
                unset($arr1[0]);
                
                if (count(array_filter($arr1)) == 0) {                    
                    continue;
                }
                
                foreach($arr1 as $key=>$val) {
                    
                     foreach($val as $key1=>$val1) {
                         
                          if (strlen($val1) > 0) {
                    
                        $arr2[$val[0]][$key1] = $val1;
                        
                          }
                    
                     
                     
                    }
               
                }
                
                
                
               /*
               foreach($arr2 as $key=>$val) {
                    
                    //echo $type;
                    if (strtolower($type) == "pot") {
                        
                        $sampleid = $val[0];
                        
                        if (strpos($sampleid, "-") == false && strlen($sampleid > 0)) {
                  
                   
                        $subsamplemass = $val[1];
                        $injectiondate = $val[2];
                        $ninjectiondate = strtotime($injectiondate);
                        
                        $sql = "update tblcannabinoids set injectiondate = '$injectiondate', ninjectiondate = '$ninjectiondate', subsamplemass = '$subsamplemass' where sampleid = '$sampleid'";
                        $stmt = $dbconn->prepare($sql);
                        //$stmt->execute();
                        
                        //echo $file . ": " . $sql . "<br />";
                        
                        $arrsqls[$sql] = $sql;
                        }
                    }
                    
                }
                */
                
                
                
                //echo $file;

                //echo "sampleid: " . $sampleid . "<br />";
                
                /*
                
                if (strtolower($sampleid) != "sample name"){
                    if (strtolower($type) == "pot") {
                        
                        
                        
                        if (strpos($sampleid, "-") > -1) {
                            
                           
                            echo "sampleid1: " . $sampleid . "<br />";
                             continue;
                        }
                        else
                        {
                            
                            echo "sampleid2: " . $sampleid . "<br />";
                        
                        if ((strlen($sampleid) > 0) && (is_numeric($sampleid))) {  
                            
                            $injectiondate = $arr1[$counter][2];
                            $subsample_mass = $arr1[$counter][1];
                            
                            $ninjectiondate = strtotime($injectiondate);
                            
                            $arr2[$sampleid]['filename'] = $file;
                            $arr2[$sampleid]['subsample_mass'] = $subsample_mass;
                            $arr2[$sampleid]['injection_date'] = $injectiondate;
                            $arr2[$sampleid]['ninjection_date'] = $ninjectiondate;
                            $arr2[$sampleid]['sntemp'] = $sntemp; 

                            $vals = array(); 
                             
                            for ($i = 4; $i <= sizeof($arr1[$counter]); $i = $i + 2) { 

                                array_push($vals, array($arr1[$counter][$i], $arr1[$counter][$i-1]));

                            }     

                            for ($i = 0; $i <= sizeof($vals); $i++) { 
                                
                                $ret = $vals[$i][0];
                                $area = $vals[$i][1];
                                 
                                
                                if ($ret >= 18 && $ret <= 22) {
                                     $arr2[$sampleid]['tocopherol_peak_area'] = $area; 
                                }

                            }
                        }                  
                    }
                        
                    }
                }
                */
                
             
                
               
            }
            
        }
        
    }
    
    ?>
    

                <pre>
                <?php print_r($arr2) ?>
                </pre>

                
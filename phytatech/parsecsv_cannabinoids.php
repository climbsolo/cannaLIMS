<?php 

include "includes.php";

if (!logincheck()) {
    header('Location: logout.php');
    die();
}

ob_start();

// Get the uploaded .csv file and instrument name
$filename = $_POST["filename"];
$instrumentname = $_POST["instrumentname"];

if ($instrumentname == "1050") {
    $instrumentname = "HPLC 1050";
}
if ($instrumentname == "1100") {
    $instrumentname = "HPLC 1100";
}


// Each row of the csv file becomes an array. Create an array of these arrays.
$foundcompounds = array();

$csvFile = file('uploads/' . $_SESSION["luid"] . "/" . $filename);
$data = [];

// Get csv rows
foreach ($csvFile as $line) {
    $data[] = str_getcsv($line);
}

/*
?>
<pre>
<?php print_r($data) ?>
</pre>
<?php
*/


// Get rid of header row
unset($data[0]);

$finaldata = array();

$totaldata = array();

// Get rid of duplicate sample names, keep least recent (first one) 
$datasize = sizeof($data);
for ($i = 1; $i <= $datasize; $i++) {  

    // Get rid of any empty rows    
    if (!isset($data[$i])) {
        continue;
    }

   // Grab the sample name, get rid of  the row if the sample name is blank or is not numeric
    $sampleid = $data[$i][0];
    $sntemp = $sampleid;
    
    if (strpos($sampleid, "-") > -1) {
        $arrsntemp = explode("-", $sampleid);
        $sntemp = $arrsntemp[0];
    }
    if ((strlen($sampleid) < 1) || (!is_numeric($sntemp))) {
        unset($data[$i]);
        continue;
    }
    
    $x = $dbconn->query("select count(*) from tblsamples where sample_id = '$sntemp' and (active <> 'false' or active = '' or active is null)")->fetchColumn();
    if ($x < 1) {
        ob_end_clean();
        echo "The Sample ID \"" .  $sntemp . "\" in the import file does not exist in the system.</p><p>Please check that all of the Sample IDs are correct in the import file, and if so, please ensure that those Sample IDs exist in the system.</p><p>The import process has been aborted.</p>";
        break;
        die();
    }

    // Get date and time
    $adate = $data[$i][2];
    $date = strtotime($data[$i][2]);

    // Get rid of any other rows with the same sample name and a greater date
    $datasize1 = sizeof($data);
    for ($y=0; $y <= $datasize1; $y++) {
        
        if (isset($data[$y])) {
            
            if ($data[$y][0] == $sampleid) { 
            
                if (strtotime($data[$y][2]) > $date) {
                    unset($data[$y]);
                }
                elseif (strtotime($data[$y][2]) == $date) {
                     continue;            
                              
                }
                else
                {
                   $adate = $data[$y][2];
                   $date = strtotime($data[$y][2]);
                   unset($data[$y]);
                }            
            }
        }
    }

    // Find sample names with a decimal point in them, get the one that is ".1", and get rid of the rest.
    $datasize2 = sizeof($data);
    for ($x = 1; $x <= $datasize2; $x++) {    
        if (isset($data[$x])) {      
           $sampleid = $data[$x][0];    
            if (strpos($sampleid, ".")) {        
                $arrsn = explode(".",$sampleid);   
                if ($arrsn[1] > 1) {
                    unset($data[$x]);
                } 
            }
        }    
    }
    // If this record survived, add it to the new array
    if (isset($data[$i])) {
        array_push($finaldata, $data[$i]);    
    }
}

/*
?>
<pre>
<?php print_r($finaldata) ?>
</pre>
<?php
*/

// Loop through Ret and Area values and compare to Retention Time windows to determine compound
foreach ($finaldata as $arr) {    

    $sampleid = $arr[0];       
    $samplemass = $arr[1];
    $injectiondate = $arr[2];
    $ninjectiondate = strtotime($injectiondate);
    $guid = com_create_guid();
    $guid = substr($guid, 1, -1);
     
    $sql = "select max(RunID) as LastRunID from datapoints";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
            $currid = $row["LastRunID"];
        }
    }                
    
    $runid = $currid + 1;
        
    //if (strlen($arr[2]) < 1 || !is_numeric($arr[2])) {
    //    continue;
    //}
    
    $vals = array();
    
    $i = 4;
    $arrsize1 = sizeof($arr);
    for ($i = 4; $i <= $arrsize1; $i = $i + 2) { 
        
        if (!isset($arr[$i])) {
            continue;
        }
    
        if (strlen($arr[$i]) > 0 && is_numeric($arr[$i])) {
            $y=$i-1;
            //array_push($vals, array($arr[$i], $arr[$y], $sampleid, $samplemass, $injectiondate, $ninjectiondate, $guid));
                      
            $sql = "insert into datapoints (RunID, SampleID, InstrumentName, RetTime, PeakArea) values (:runid, :sampleid, :instrumentname, :rettime, :peakarea)";
            $stmt = $dbconn->prepare($sql);
            $stmt->execute(array(':runid'=>$runid, ':sampleid'=>$sampleid, ':instrumentname'=>$instrumentname, ':rettime'=>$arr[$i], ':peakarea'=>$arr[$y]));
            
             array_push($vals, array($arr[$i], $arr[$y], $sampleid, $samplemass, $injectiondate, $ninjectiondate, $guid, $runid));
            

   
        }        
    }   
            
    $totaldata[$sampleid] = getcompounds($vals); 
    
}

/*
?>
<pre>
<?php print_r($totaldata) ?>
</pre>
<?php
*/


$homoarr = array();

getjavascript();

foreach ($totaldata as $key=>$val) {
    
    // Loop through the results, get information, then write out the HTML for each sample
    
    if (!isset($val)) {
        continue;
    }
    
    if (!isset($val[0][4])) {
        continue;
    }
  
/*  
?>
<pre>
<?php print_r($val) ?>
</pre>
<?php
*/  
        
        $sampleid = $key;
        $samplemass = $val[0][3]; 
        $injectiondate = $val[0][4];
        $ninjectiondate = $val[0][5];
        $guid = $val[0][6];
        
        if (strpos($sampleid, "-")) {        
            $homoarr = explode("-", $sampleid); 
            $sampleidfordata = $homoarr[0];               
        } 
        else
        {
            $sampleidfordata = $sampleid;
        } 
      
        $sql = "select * from tblsamples where sample_id = :sampleid";
        $stmt = $dbconn->prepare($sql);
        if ($stmt->execute(array(':sampleid'=>$sampleidfordata))) {
            while ($row = $stmt->fetch()) {
                $sampletype = $row["product_type"];      
                $wetmass = $row["wet_mass"];
                $drymass = $row["dry_mass"];
                $packageamount = $row["package_amount"];                   
            }           
        }    
                
        // tblconstants call
        $dilution = 1; // default value
        $sql = "Select cvalue from tblconstants where cname = 'dilution' and ckey = '$sampletype'";
        $stmt = $dbconn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $dilution = $row["cvalue"];               
            }           
        }        

        $thca = "";
        $thc = "";
        $cbda = "";
        $cbd = "";
        $cbca = "";
        $cbc = "";
        $cbga = "";
        $cbg = "";
        $cbna = "";                       
        $cbn = "";
        $thcva = "";                       
        $thcv = "";                        
        $cbdva = "";                        
        $cbdv = "";                       
        $cbdla = "";
        $cbla = "";
        $tocopherol = "";
        
        $thcaret = "";
        $thcret = "";
        $cbdaret = "";
        $cbdret = "";
        $cbcaret = "";
        $cbcret = "";
        $cbgaret = "";
        $cbgret = "";
        $cbnaret = "";                       
        $cbnret = "";
        $thcvaret = "";                       
        $thcvret = "";                        
        $cbdvaret = "";                        
        $cbdvret = "";                       
        $cbdlaret = "";
        $cblaret = "";
        $tocopherolret = "";
    
    foreach ($val as $key1=>$val1) {        
         
        $compound = $val1[0];
        $area = $val1[1];
        $rettime = $val1[7];
        
         if ($compound == "THCA") {
            $thca = $area;
            $thcaret = $rettime;
        }
        if ($compound == "THC") {
            $thc = $area;
            $thcret = $rettime;
        }
        if ($compound == "CBDA") {
            $cbda = $area;
            $cbdaret = $rettime;
        }
        if ($compound == "CBD") {
            $cbd = $area;
            $cbdret = $rettime;
        }
        if ($compound == "CBCA") {
            $cbca = $area;
            $cbcaret = $rettime;
        }
        if ($compound == "CBC") {
            $cbc = $area;
            $cbcret = $rettime;
        }
        if ($compound == "CBGA") {
            $cbga = $area;
            $cbgaret = $rettime;
        }
        if ($compound == "CBG") {
            $cbg = $area;
            $cbgret = $rettime;
        }
        if ($compound == "CBNA") {
            $cbna = $area;
            $cbnaret = $rettime;
        }
        if ($compound == "CBN") {
            $cbn = $area;
            $cbnret = $rettime;
        }
        if ($compound == "THCVA") {
            $thcva = $area;
            $thcvaret = $rettime;
        }
        if ($compound == "THCV") {
            $thcv = $area;
            $thcvret = $rettime;
        }
        if ($compound == "CBDVA") {
            $cbdva = $area;
            $cbdvaret = $rettime;
        }
        if ($compound == "CBDV") {
            $cbdv = $area;
            $cbdvret = $rettime;
        }
        if ($compound == "CBDLA") {
            $cbdla = $area;
            $cbdlaret = $rettime;
        }
        if ($compound == "CBLA") {
            $cbla = $area;
            $cblaret = $rettime;
        }
        if ($compound == "Tocopherol") {
            $tocopherol = $area;
            $tocopherol_peak_area = $area;
            $tocopherolret = $rettime;
        }           
    }
    

// Clear and create array for HTML output
    
$arrcalc = array();
$arrret = array();

$arrcalc["guid"] = $guid;
$arrcalc["sampleid"] = $sampleid;
$arrcalc["dilution"] = $dilution;
$arrcalc["samplemass"] = $samplemass;
$arrcalc["injectiondate"] = $injectiondate;
$arrcalc["ninjectiondate"] = $ninjectiondate;
$arrcalc["sampletype"] = $sampletype;
$arrcalc["packageamount"] = $packageamount;
$arrcalc["rettime"] = $rettime;
$arrcalc["THC"] = $thc;
$arrcalc["THCA"] = $thca;
$arrcalc["CBDA"] = $cbda;
$arrcalc["CBD"] = $cbd;
$arrcalc["CBC"] = $cbc;
$arrcalc["CBCA"] = $cbca;
$arrcalc["CBGA"] = $cbga;
$arrcalc["CBG"] = $cbg;
$arrcalc["CBNA"] = $cbna;
$arrcalc["CBN"] = $cbn;
$arrcalc["THCVA"] = $thcva;
$arrcalc["THCV"] = $thcv;
$arrcalc["CBDVA"] = $cbdva;
$arrcalc["CBDV"] = $cbdv;
$arrcalc["CBDLA"] = $cbdla;
$arrcalc["CBLA"] = $cbla;
$arrcalc["Tocopherol"] = $tocopherol;

$arrret["THC"] = $thcret;
$arrret["THCA"] = $thcaret;
$arrret["CBDA"] = $cbdaret;
$arrret["CBD"] = $cbdret;
$arrret["CBC"] = $cbcret;
$arrret["CBCA"] = $cbcaret;
$arrret["CBGA"] = $cbgaret;
$arrret["CBG"] = $cbgret;
$arrret["CBNA"] = $cbnaret;
$arrret["CBN"] = $cbnret;
$arrret["THCVA"] = $thcvaret;
$arrret["THCV"] = $thcvret;
$arrret["CBDVA"] = $cbdvaret;
$arrret["CBDV"] = $cbdvret;
$arrret["CBDLA"] = $cbdlaret;
$arrret["CBLA"] = $cblaret;
$arrret["Tocopherol"] = $tocopherolret;

$sname = make_url_friendly($sampleid);


/*
?>
<pre>
<?php print_r($arrret) ?>
</pre>
<?php
*/



?>
<div id="applydiv">

<div id="cannalims_cover"></div>

<div>

<?php 
echo "<span style=\"font-weight:bold;font-size:120%;margin:10px 10px 2em 10px;\" class=\"$guid\">Sample ID: $key</span>"; 

$rtstr = "<span style=\"float:right;margin:0 2em 5px 0;\" class=\"$guid\"><label for=\"$key" . "_reporttype\">Report Type:</label>
<select id=\"$key" . "_reporttype\">
    <option value=\"Standard\">Standard</option>
    <option value=\"Infused I\">Infused I</option>";
    
    if (strpos($key, "-")) {
        $rtstr .= "<option value=\"Infused II\" selected>Infused II</option></select></span>"; 
    }
    else
    {
        $rtstr .= "<option value=\"Infused II\">Infused II</option></select></span>";        
    }
    
    echo $rtstr;
        
    ?>

<div style="clear:both"></div>

<div id="<?php echo $guid ?>" class="rowparent <?php echo $guid ?>" style="margin-bottom:2em;">


                   
<img src="images/close.png" class="closebtn" onclick="removesamplefromresults('<?php echo $guid ?>')" />

<?php


echo(makeformula($arrcalc, $arrret));

?>

</div>

</div>

<?php 


}

//troubleshooting("parsecsv_cannabinoids.php", "After Write HTML Line 441 SampleID: $sampleid");

echo "<style>

.rowparent {
    background-color: #F1F1F1;
    border: 1px solid lightgrey;
}

.formula {
    width: 100%;    
}

.formula input {
    width: 5em;
    border: none;
    text-align: left;
    padding: 2px 5px 2px 5px;
}

.formula table {
    width: 100%;
}

.formulalabel {
    /*width: 4em;*/
    text-align: left;
}

.formulalabel1 {
    width: 4em;
    text-align: left;
}

.formularesult {
    width: 6em;
    text-align: right !important;
}

.closebtn {
    float: right;
    margin: -15px -10px 0 0;
}

.closebtn:hover {
    cursor: pointer;
    cursor: hand;
}

.successdiv {
    position: fixed;
    top: 50%;
    left: 50%;
    width: 200px;
    min-height: 200px;
    margin-top: -100px; /* Half the height */
    margin-left: -100px; /* Half the width  */
    z-index: 10000000; 
    font-size: 40px;    
}

.centeralign tr td label {
    text-align: center !important;
}

#addcompounddiv {
    display: none;
    position: fixed;
    top: 20%;
    left: 50%;
    width: 350px;
    min-height: 80px;
    margin-top: -100px; /* Half the height */
    margin-left: -200px; /* Half the width  */
    z-index: 10000000;
    background-color: white;
    border: solid green 1px;
     -webkit-border-radius: 15px;
    -moz-border-radius: 15px;
    border-radius: 15px;
    overflow: hidden;
    padding: 1em;
}

#cannalims_cover {
    background-color: black;
    width: 100%;
    height: 100%;
    display: none;
    position: fixed;
    z-index: 5000; 
}


</style>";
           
             

function makeformula($arr, $arrret) {  
    
    global $dbconn;
    global $wetmass;
    global $drymass;
    global $instrumentname;
        
    $dilution = $arr["dilution"];
    $samplemass = $arr["samplemass"];
    $packageamount = $arr["packageamount"];      
    $sampleid = $arr["sampleid"];
    $sampletype = $arr["sampletype"];
    $guid = $arr["guid"];
    $injectiondate = $arr["injectiondate"];
    $ninjectiondate = $arr["ninjectiondate"];
   
    $ret = "";
    $pthc = 2300;
    
    // get PTHC from tblconstants
    $sql = "select cvalue from tblconstants where cname = 'pthc' and instrument = :instrumentname";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':instrumentname'=>$instrumentname))) {
        while ($row = $stmt->fetch()) {
            $pthc = $row["cvalue"];          
        }
    }
    
    //error_log("PTHC: " . $pthc);
    
    foreach (array_slice($arr, 9) as $key => $val) {     

    //Create the HTML markup for each sample
        
        // Get response factor for compound from tblconstants
        $sql = "select cvalue from tblconstants where cname = 'responsefactor' and ckey = '$key'";
        $stmt = $dbconn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $responsefactor = $row["cvalue"];
            }
        }
        $sname = make_url_friendly($sampleid);                
        $prefix = $key . $sname; // Unique ID for sample    

        $rettime = $arrret[$key];        
    
        if ($sampletype == "Concentrate") {             
            if (strlen($val) > 0) {               
                $ret .= "<script>$('#$prefix input').keyup(function(){calculatepercentage('$prefix', '$sampletype')});calculatepercentage('$prefix', '$sampletype')</script>
                <div id=\"$prefix\" class=\"formula\" data-guid=\"$guid\" data-compound=\"$key\" data-sampleid=\"$sampleid\" data-sampletype=\"$sampletype\" data-injectiondate=\"$injectiondate\" data-ninjectiondate=\"$ninjectiondate\" data-packageamount=\"$packageamount\">
                    <div style=\"padding:10px;\">
                        <table>                                                
                            <tr title=\"RT = Retention Time, PA = Peak Area, RF = Response Factor, PTHC = delta-9 THC @ 1000 ppm, Dil = Dilution, Mass = Sample Mass, % = Percentage \">                    
                                <td><label class=\"formulalabel1\">$key" . "</label></td> 
                                <td><label class=\"formulalabel\">RT</label><input type=\"text\" value=\"$rettime\" disabled></input></td>
                                <td><label class=\"formulalabel\">PA</label><input type=\"text\" value=\"$val\" id=\"$prefix" . "_area\"></input></td>
                                <td><label class=\"formulalabel\">RF</label><input type=\"text\" value=\"$responsefactor\" id=\"$prefix" . "_responsefactor\"></td>
                                <td><label class=\"formulalabel\">PTHC</label><input type=\"text\" value=\"$pthc\" id=\"$prefix" . "_pthc\"></input></td>
                                <td><label class=\"formulalabel\">Dil</label><input type=\"text\" value=\"$dilution\" id=\"$prefix" . "_dilution\"></input></td>
                                <td><label class=\"formulalabel\">Mass</label><input type=\"text\" value=\"$samplemass\" id=\"$prefix" . "_samplemass\"></input></td>
                                <td><label class=\"formulalabel\">PCT</label><input type=\"text\" class=\"formularesult\" id=\"$prefix" . "_percentage\"></input><label>%</label></td>
                                <td><button id=\"btn_$prefix\" class=\"btncommit\" data-prefix=\"$prefix\" style=\"margin-left:20px;\" onclick=\"commitchange('$prefix', 'false');\">Commit</button><button id=\"btn_delete_$prefix\" style=\"margin-left:1em;\" class=\"btncommit\" data-prefix=\"$prefix\" style=\"margin-left:20px;\" onclick=\"removecompound('$prefix');\">Delete</button></td>                            
                            </tr>                        
                        </table>
                    </div>
                </div>";                
             }             
        }        
        
        if ($sampletype == "Flower") {            
            if (strlen($val) > 0) {                
                
                // get weighboat from tblconstants
                $sql = "select cvalue from tblconstants where cname = 'weighboat'";
                $stmt = $dbconn->prepare($sql);
                if ($stmt->execute()) {
                    while ($row = $stmt->fetch()) {
                        $weighboat = $row["cvalue"];          
                    }
                }
                
                $ret .= "<script>$('#$prefix input').keyup(function(){calculatepercentage('$prefix', '$sampletype')});calculatepercentage('$prefix', '$sampletype')</script>                
                <div id=\"$prefix\" class=\"formula\" data-guid=\"$guid\" data-compound=\"$key\" data-sampleid=\"$sampleid\" data-sampletype=\"$sampletype\" data-injectiondate=\"$injectiondate\" data-ninjectiondate=\"$ninjectiondate\" data-packageamount=\"$packageamount\">
                    <div style=\"padding:10px;\">
                        <table class=\"centeralign\">
                            <tr title=\"Wet = Wet Mass, Dry = Dry Mass, Weighboat = Weighboat Mass, H20 = Water Content, Corrected = Corrected Mass, RT = Retention Time, PA = Peak Area, RF = Response Factor, PTHC = delta-9 THC @ 1000 ppm, Dil = Dilution, Mass = Sample Mass, % = Percentage \">                    
                                <td><label class=\"formulalabel1\">$key" . "</label></td>
                                <td><label class=\"formulalabel\">Wet</label><br /><input type=\"text\" class=\"wetmass\" value=\"$wetmass\" id=\"$prefix" . "_wetmass\" disabled></input></td>
                                <td><label class=\"formulalabel\">Dry</label><br /><input type=\"text\" class=\"drymass\" value=\"$drymass\" id=\"$prefix" . "_drymass\" disabled></input></td>
                                <td><label class=\"formulalabel\">Weighboat</label><br /><input type=\"text\" class=\"weighboat\" value=\"$weighboat\" id=\"$prefix" . "_weighboat\" disabled ></input></td>
                                <td><label class=\"formulalabel\">H2O</label><br /><input class=\"watercontent\" type=\"text\" id=\"$prefix" . "_watercontent\" disabled></input></td>
                                <td><label class=\"formulalabel\">Corrected</label><br /><input type=\"text\" class=\"correctedmass\" id=\"$prefix" . "_correctedmass\" class=\"correctedmass\" disabled></input></td>
                                <td><label class=\"formulalabel\">RT</label><br /><input type=\"text\" value=\"$rettime\" disabled></input></td>
                                <td><label class=\"formulalabel\">PA</label><br /><input type=\"text\" value=\"$val\" id=\"$prefix" . "_area\"></input></td>
                                <td><label class=\"formulalabel\">RF</label><br /><input type=\"text\" value=\"$responsefactor\" id=\"$prefix" . "_responsefactor\"></td>
                                <td><label class=\"formulalabel\">PTHC</label><br /><input type=\"text\" value=\"$pthc\" id=\"$prefix" . "_pthc\"></input></td>
                                <td><label class=\"formulalabel\">Dil</label><br /><input type=\"text\" value=\"$dilution\" id=\"$prefix" . "_dilution\"></input></td>
                                <td><label class=\"formulalabel\">Mass</label><br /><input type=\"text\" value=\"$samplemass\" id=\"$prefix" . "_samplemass\"></input></td>
                                <td><label class=\"formulalabel\">PCT (%)</label><br /><input type=\"text\" class=\"formularesult\" id=\"$prefix" . "_percentage\"></input>%</td>
                                <td><button id=\"btn_$prefix\" class=\"btncommit\" data-prefix=\"$prefix\" style=\"margin-left:20px;\" onclick=\"commitchange('$prefix', 'false');\">Commit</button><button id=\"btn_delete_$prefix\" style=\"margin-left:1em;\" class=\"btncommit\" data-prefix=\"$prefix\" style=\"margin-left:20px;\" onclick=\"removecompound('$prefix');\">Delete</button></td>                            
                            </tr>                        
                        </table>
                    </div>
                </div>";
            }
        }
        
         if ($sampletype == "Solid Edible") {
            if (strlen($val) > 0) {
                $ret .= "<script>$('#$prefix input').keyup(function(){calculatepercentage('$prefix', '$sampletype')});calculatepercentage('$prefix', '$sampletype')</script>
                <div id=\"$prefix\" class=\"formula\" data-guid=\"$guid\" data-compound=\"$key\" data-sampleid=\"$sampleid\" data-sampletype=\"$sampletype\" data-injectiondate=\"$injectiondate\" data-ninjectiondate=\"$ninjectiondate\" data-packageamount=\"$packageamount\">
                <div style=\"padding:10px;\">
                <table>
                    <tr title=\"RT = Retention Time, PA = Peak Area, RF = Response Factor, PTHC = delta-9 THC @ 1000 ppm, Dil = Dilution, Mass = Sample Mass, % = Percentage \">                    
                        <td><label class=\"formulalabel1\">$key" . "</label></td> 
                        <td><label class=\"formulalabel\">RT</label><input type=\"text\" value=\"$rettime\" disabled></input></td>
                        <td><label class=\"formulalabel\">PA</label><input type=\"text\" value=\"$val\" id=\"$prefix" . "_area\"></input></td>
                        <td><label class=\"formulalabel\">RF</label><input type=\"text\" value=\"$responsefactor\" id=\"$prefix" . "_responsefactor\"></td>
                        <td><label class=\"formulalabel\">PTHC</label><input type=\"text\" value=\"$pthc\" id=\"$prefix" . "_pthc\"></input></td>
                        <td><label class=\"formulalabel\">Dil</label><input type=\"text\" value=\"$dilution\" id=\"$prefix" . "_dilution\"></input></td>
                        <td><label class=\"formulalabel\">Mass</label><input type=\"text\" value=\"$samplemass\" id=\"$prefix" . "_samplemass\"></input></td>
                        <td><label class=\"formulalabel\">PCT</label><input type=\"text\" class=\"formularesult\" id=\"$prefix" . "_percentage\"></input>%</td>
                        <td><button id=\"btn_$prefix\" class=\"btncommit\" data-prefix=\"$prefix\" style=\"margin-left:20px;\" onclick=\"commitchange('$prefix', 'false');\">Commit</button><button id=\"btn_delete_$prefix\" style=\"margin-left:1em;\" class=\"btncommit\" data-prefix=\"$prefix\" style=\"margin-left:20px;\" onclick=\"removecompound('$prefix');\">Delete</button></td>                                               
                    </tr>                        
                </table>
                </div>
                </div>";             
             }
        }

        if ($sampletype == "Liquid Edible") {
            if (strlen($val) > 0) {         
                $ret .= "<script>$('#$prefix input').keyup(function(){calculatepercentage('$prefix', '$sampletype')});calculatepercentage('$prefix', '$sampletype')</script>
                <div id=\"$prefix\" class=\"formula\" data-guid=\"$guid\" data-compound=\"$key\" data-sampleid=\"$sampleid\" data-sampletype=\"$sampletype\" data-injectiondate=\"$injectiondate\" data-ninjectiondate=\"$ninjectiondate\" data-packageamount=\"$packageamount\">
                <div style=\"padding:10px;\">
                <table>              
                    <tr title=\"RT = Retention Time, PA = Peak Area, RF = Response Factor, PTHC = delta-9 THC @ 1000 ppm, Dil = Dilution, Mass = Sample Mass, % = Percentage \">                    
                        <td><label class=\"formulalabel1\">$key" . "</label></td> 
                        <td><label class=\"formulalabel\">RT</label><input type=\"text\" value=\"$rettime\" disabled></input></td>
                        <td><label class=\"formulalabel\">PA</label><input type=\"text\" value=\"$val\" id=\"$prefix" . "_area\"></input></td>
                        <td><label class=\"formulalabel\">RF</label><input type=\"text\" value=\"$responsefactor\" id=\"$prefix" . "_responsefactor\"></td>
                        <td><label class=\"formulalabel\">PTHC</label><input type=\"text\" value=\"$pthc\" id=\"$prefix" . "_pthc\"></input></td>
                        <td><label class=\"formulalabel\">Dil</label><input type=\"text\" value=\"$dilution\" id=\"$prefix" . "_dilution\"></input></td>
                        <td><label class=\"formulalabel\">Mass</label><input type=\"text\" value=\"$samplemass\" id=\"$prefix" . "_samplemass\"></input></td>
                        <td><label class=\"formulalabel\">PCT</label><input type=\"text\" class=\"formularesult\" id=\"$prefix" . "_percentage\"></input>%</td>
                        <td><button id=\"btn_$prefix\" class=\"btncommit\" data-prefix=\"$prefix\" style=\"margin-left:20px;\" onclick=\"commitchange('$prefix', 'false');\">Commit</button><button id=\"btn_delete_$prefix\" style=\"margin-left:1em;\" class=\"btncommit\" data-prefix=\"$prefix\" style=\"margin-left:20px;\" onclick=\"removecompound('$prefix');\">Delete</button></td>                            
                    </tr>                        
                </table>
                </div>
                </div>";
            }
        }            
    }        
    return $ret;    
}

function getcompounds($vals) {
    
    /*
    ?>
    <pre>
    <?php print_r($vals) ?>
    </pre>
    <?php 
    */
    
    global $instrumentname;
    global $dbconn;
    
    $foundcompounds = array();
    $compounds = array();
    
    $valsize = sizeof($vals);
    
    for ($i = 0; $i <= $valsize; $i++) { 

        if (!isset($vals[$i])) {
            continue;
        }
        
        $ret = $vals[$i][0];
        $area = $vals[$i][1];    
        $sampleid = $vals[$i][2];
        $samplemass = $vals[$i][3];
        $injectiondate = $vals[$i][4];
        $ninjectiondate = $vals[$i][5];
        $guid = $vals[$i][6];
        $runid = $vals[$i][7];        
        
        //troubleshooting("parsecsv_cannabinoids.php", "Before Stored Proc Line 725");
        
        $sql = "CALL calcCompoundsProc($runid, 'cannabinoids');";
        $q = $dbconn->query($sql);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        
         while ($r = $q->fetch()):
        
            array_push($foundcompounds, array($r["Compound"], $r["PeakArea"], $sampleid, $samplemass, $injectiondate, $ninjectiondate, $guid, $r["RetTime"])); 
        
         endwhile;
         
         $q->closeCursor();
         
         //troubleshooting("parsecsv_cannabinoids.php", "After Stored Proc Line 739");
       
         return $foundcompounds;
 
    }

}


function getjavascript() {
    
    global $instrumentname;
?>
<script>

    var controlbuttons = '<div><button style="margin-top:1.25em;" class="bcbutton" id="btncommitall">Commit All</button><button style="margin-top:1.25em;display:none;" class="bcbutton" id="btnapply">Approve</button></div>';

    $("#controlbuttons").html(controlbuttons);
        
    $("#rtinstrument").val("<?php echo $instrumentname ?>");
    $("#rtcompoundtypes").val("cannabinoids");
    rtinstrument();
    $("#retentiontimewindows").hide();


    function calculatepercentage(prefix, sampletype) {
    
    // Do the calculations based on sample type
    
    var area = document.getElementById(prefix+'_area').value;
    var responsefactor = document.getElementById(prefix+'_responsefactor').value;
    var pthc = document.getElementById(prefix+'_pthc').value;
    var dilution = document.getElementById(prefix+'_dilution').value;
    
    if (sampletype == "Solid Edible" || sampletype == "Concentrate") {
        var samplemass = document.getElementById(prefix+'_samplemass').value;
    }
       
    if (sampletype == "Concentrate") {  
        if (area > 0) {
            percentage = ((((area / 1000) / (responsefactor * pthc)) * dilution) / samplemass) * 100;
            //percentage = (((area / (responsefactor * pthc)) * dilution) / samplemass) * 100;
        }
    }
    
    if (sampletype == "Flower") { 
        if (area > 0) {
            var samplemass = document.getElementById(prefix+'_samplemass').value;
            var wetmass = document.getElementById(prefix+'_wetmass').value;
            var drymass = document.getElementById(prefix+'_drymass').value;
            var weighboat = document.getElementById(prefix+'_weighboat').value;
            var watercontent = (wetmass-drymass)/(wetmass-weighboat);
            document.getElementById(prefix+'_watercontent').value = watercontent;
            var correctedmass = samplemass - (samplemass * watercontent);
            document.getElementById(prefix+'_correctedmass').value = correctedmass;
            percentage = ((((area / 1000) / (responsefactor * pthc)) * dilution) / correctedmass) * 100;
            //percentage = (((area / (responsefactor * pthc)) * dilution) / correctedmass) * 100;
        }
    }
    
    if (sampletype == "Solid Edible") { 
        if (area > 0) {
            percentage = ((area / (responsefactor * pthc)) * dilution) / samplemass;
        }
    }
    
    if (sampletype == "Liquid Edible") { 
        if (area > 0) {
            percentage = (area / (responsefactor * pthc)) * dilution;
        }
    }
       
    //document.getElementById(prefix+'_percentage').value = round(percentage, 2);
    document.getElementById(prefix+'_percentage').value = percentage;

}

function round(value, exp) {
    if (typeof exp === 'undefined' || +exp === 0)
    return Math.round(value);

    value = +value;
    exp  = +exp;

    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
    return NaN;

    // Shift
    value = value.toString().split('e');
    value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

    // Shift back
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
}

function commitchange(prefix, all) {
    
    var btntext = $('#btn_' + prefix).text();
    
    if (btntext == 'Commit' || all == 'true') {
        $('#' + prefix).find('input').attr('disabled',true);
        $('#' + prefix).find('input').css('background-color', '#F1F1F1');
        $('#btn_' + prefix).text('Change');
    }
    else
    {
        $('#' + prefix).find('input').attr('disabled',false);
        $('#' + prefix).find('input').css('background-color', 'white');
        $('#btn_' + prefix).text('Commit');
    }
    
    var foundone = 0;         
    $(".btncommit").each(function() {
        if ($(this).text() == "Commit") {
           foundone = foundone + 1; 
        }
    });
    
    if (foundone > 0) {
        $("#btnapply").hide();
        $("#btncommitall").show(); 
    }
    else
    {
        $("#btncommitall").hide();
        $("#btnapply").show(); 
    }
    
   
}

function removesamplefromresults(prefix) {       
    $('.' + prefix).remove(); 
    
}

function showapplybutton(all) {
    commitchange('', 'true');
}

function showaddcompound(guid) {
   $("#cannalims_cover").fadeTo(500, 0.5).css('display', 'block');
   $("#addcompounddivguid").val(guid);
   $("#addcompounddiv").fadeIn(200);
   
  
}

function closeaddcompound() {
    $("#cannalims_cover").fadeOut(100);        
    $("#addcompounddiv").fadeOut(250);
    $("#addcompounddiv input").val("");
}

function addcompoundapply() {
    
    var guid = $("#addcompounddivguid").val();
    alert(guid);
    
    closeaddcompound();
}

function removecompound(prefix) {
    
    var x = confirm("Really remove this compound?");
    
    if (x) {    
        $("#" + prefix).remove();
    };
    
}
 
$(document).ready(function() {
    
    $(".draggable").draggable();
    
    $("#btnaddcompoundcancel").click(function() {
       closeaddcompound();
    });
  
    $("#btnaddcompoundapply").click(function() {
        addcompoundapply();
    });
    
    $("#btncommitall").click(function() {
        $(".btncommit").each(function() {
            var prefix = $(this).data("prefix");
            commitchange(prefix, 'true');
        });
        commitchange('', 'true');            
    });
    
     // Select the full text in an input when clicked on
   $('input').on('focus', function (e) {
        $(this)
            .one('mouseup', function () {
                $(this).select();
                return false;
            })
            .select();
    });
    
    $("#btnapply").click(function() {
        waitingDialog.show();
        var result = "";
        var arr = {};
        var finalarr = {};
        var counter = 0;         
        $(".formula").each(function() {                              
            counter = counter + 1;
            var id = $(this).prop("id");
            var guid = $(this).data("guid");
            arr["guid"] = $(this).data("guid");
            arr["compoundname"] = $(this).data("compound");
            arr["sampleid"] = $(this).data("sampleid");
            arr["reporttype"] = $("#"+$(this).data("sampleid")+"_reporttype").val(); 
            arr["sampletype"] = $(this).data("sampletype");
            arr["packageamount"] = $(this).data("packageamount");
            arr["injectiondate"] = $(this).data("injectiondate");
            arr["ninjectiondate"] = $(this).data("ninjectiondate");
            arr["percentage"] = $("#"+id+"_percentage").val();
            arr["samplemass"] = $("#"+id+"_samplemass").val();
            arr["area"] = $("#"+id+"_area").val();
            arr["responsefactor"] = $("#"+id+"_responsefactor").val();
            arr["dilution"] = $("#"+id+"_dilution").val();
            arr["pthc"] = $("#"+id+"_pthc").val();
            arr["wetmass"] = $("#"+id+"_wetmass").val();
            arr["drymass"] = $("#"+id+"_drymass").val();
            arr["weighboat"] = $("#"+id+"_weighboat").val();
            arr["watercontent"] = "";
            if (arr["sampletype"] == "Flower") {
                arr["watercontent"] = $("#"+id+"_watercontent").val();
            }
            arr["correctedmass"] = $("#"+id+"_correctedmass").val();
            arr["instrumentname"] = "<?php echo $instrumentname ?>"; 
            arr["testtype"] = "Cannabinoids";
            arr["tocopherol_peak_area"] = "";
                      
            if ($(this).data("compound") == "Tocopherol") {               
                arr["tocopherol_peak_area"] = $("#"+id+"_area").val();
            }            
            finalarr[id] = arr;
            arr = {};
            
        });            
        
           //console.log(JSON.stringify(finalarr));
           
           
           
           $.post("commitdata_cannabinoids.php", {finalarr: JSON.stringify(finalarr)}, 
            function(data) {                
                if (data.length > 0) {
                    window.top.location.href = 'logout.php';
                    return false;
                }                
                $("#testtype").val("");
                showuploadbutton();
                setTimeout(function() {
                $("#results").html("<div style=\"padding-left:10em;\"><h1>Success!</h1></div>"); 
                $("#btnapply").hide();
                $("#btncommitall").hide(); 
                waitingDialog.hide();
                }, 0);                    
            });
        });
    });
    
    
</script>
<?php 
}
?>

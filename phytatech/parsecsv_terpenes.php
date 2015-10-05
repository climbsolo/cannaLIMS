<?php 

include "includes.php";

if (!logincheck()) {
    header('Location: logout.php');
    die();
}

ob_start();

// Get the uploaded .csv file
$filename = $_POST["filename"];

// Each row of the csv file becomes an array. Create an array of these arrays.
$foundcompounds = array();
$instrumentname = $_POST["instrumentname"];

if ($instrumentname == "1050") {
    $instrumentname = "HPLC 1050";
}
if ($instrumentname == "1100") {
    $instrumentname = "HPLC 1100";
}
if ($instrumentname == "5890") {
    $instrumentname = "GC 5890";
}
if ($instrumentname == "6890") {
    $instrumentname = "GC 6890";
}


$csvFile = file('uploads/' . $_SESSION["luid"] . '/' . $filename);
$data = [];

// Get csv rows
foreach ($csvFile as $line) {
    $data[] = str_getcsv($line);
}

// Get rid of header row
unset($data[0]);

$finaldata = array();

$totaldata = array();

// Get rid of duplicate sample names, keep least recent (first one) 
$datasize1 = sizeof($data);
for ($i = 1; $i <= $datasize1; $i++) {  

    // Get rid of any empty rows    
    if (!isset($data[$i])) {
        continue;
    }

    // Grab the sample name, get rid of  the row if the sample name is blank or is not numeric
    $samplename = $data[$i][0];
    if ((strlen($samplename) < 1) || (!is_numeric($samplename))) {
        unset($data[$i]);
        continue;
    }

    // Get date and time
    $adate = $data[$i][2];
    $date = strtotime($data[$i][2]);

    // Get rid of any other rows with the same sample name and a greater date
    $datasize2 = sizeof($data);
    for ($y=0; $y <= $datasize2; $y++) {
        
        if (isset($data[$y])) {
            
            if ($data[$y][0] == $samplename) { 
            
            //echo "Date: " . strtotime($data[$y][2]);
            
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
    $datasize3 = sizeof($data);
    for ($x = 1; $x <= sizeof($data); $x++) {    
        $samplename = $data[$x][0];    
        if (strpos($samplename, ".")) {        
            $arrsn = explode(".",$samplename);   
            if ($arrsn[1] > 1) {
                unset($data[$x]);
            } 
        }
    }    
    
    // If this record survived, add it to the new array
    if (isset($data[$i])) {
        array_push($finaldata, $data[$i]);    
    }
}

/*
// Make  sure that each sample has had a Cannabinoids test already (terpenes calculations require the THC and THCA values for the sample). If ANY of the samples in the .csv does not have a THC and THC record, bail on the whole thing and tell the user why.
$arrnothc = array();
foreach ($finaldata as $arr) {    

    $samplename = $arr[0];   
    $x = $dbconn->query("select count(*) from tblcannabinoids where sampleid = '$samplename'")->fetchColumn();

    if ($x < 1) {
        array_push($arrnothc, $samplename);
    }
    
}

if( !empty( $arrnothc ) ) {
    
    foreach ($arrnothc as $sid) {
        $str .= $sid . "<br />";
    }
    
    echo "<p><span style=\"font-weight:bold;\">ERROR:</span> The following samples have no THC or THCA values in the system. Terpene calculation requires that these Cannabinoids be tested first.</p><p>Please either upload Cannabinoid test results for these samples, or remove them from the Terpenes .csv import file.</p><p>The import process has been aborted.</p><p><span style=\"font-weight:bold;\">Problem Samples:</span><br />$str</p>";
    
    die();
    
}

*/

/*
?>
<pre>
<?php print_r($finaldata) ?>
</pre>
<?php 
*/


// Loop through Ret(retention time) and Area values and compare to Retention Time windows to determine compound
foreach ($finaldata as $arr) {    

    $samplename = $arr[0]; 
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
            //array_push($vals, array($arr[$i], $arr[$y], $samplename, $injectiondate, $ninjectiondate, $guid));
            $sql = "insert into datapoints (RunID, SampleID, InstrumentName, RetTime, PeakArea) values (:runid, :sampleid, :instrumentname, :rettime, :peakarea)";
            $stmt = $dbconn->prepare($sql);
            $stmt->execute(array(':runid'=>$runid, ':sampleid'=>$samplename, ':instrumentname'=>$instrumentname, ':rettime'=>$arr[$i], ':peakarea'=>$arr[$y]));
            
             array_push($vals, array($arr[$i], $arr[$y], $samplename, $injectiondate, $ninjectiondate, $guid, $runid, $samplemass));
        }        
    }   
            
    $totaldata[$samplename] = getcompounds($vals);  
    
}

/*
?>
<pre>
<?php print_r($totaldata) ?>
</pre>
<?php 
*/

getjavascript();

foreach ($totaldata as $key=>$val) {
    
    if (!isset($key)) {
        continue;
    }
    
    // Loop through the results, get information, then write out the HTML for each sample
    
        $guid = com_create_guid();
        $guid = substr($guid, 1, -1);
        $samplename = $key;    
        $injectiondate = $val[0][3];
        $ninjectiondate = $val[0][4];
        $samplemass = $val[0][7];   

        $sql = "select product_type from tblsamples where sample_id = :sampleid";
        $stmt = $dbconn->prepare($sql);
        if ($stmt->execute(array(':sampleid'=>$samplename))) {
            while ($row = $stmt->fetch()) {
                $sampletype = $row["product_type"];                 
            }           
        }           
              
        
        $x = $dbconn->query("select count(*) from tblsamples where sample_id = '$samplename' and (active <> 'false' or active = '' or active is null)")->fetchColumn();
        if ($x < 1) {
        ob_end_clean();
            echo "The Sample ID \"" .  $samplename . "\" in the import file does not exist in the system.</p><p>Please check that all of the Sample IDs are correct in the import file, and if so, please ensure that those Sample IDs exist in the system.</p><p>The import process has been aborted.</p>";
            break;
            die();
        }
                         
        $alphapinene = "";
        $betapinene = "";
        $myrcene = "";
        $limonene = "";
        $terpinolene = "";
        $linalool = "";
        $alphaterpineol = "";
        $betacaryophyllene = "";
        $humulene = "";
        $cisocimene = "";
        $terpthc = "";
    
    foreach ($val as $key1=>$val1) {        
         
        $compound = $val1[0];
        $area = $val1[1];       
        
         if ($compound == "Alpha-Pinene") {
            $alphapinene = $area;
        }
        if ($compound == "Beta-Pinene") {
            $betapinene = $area;
        }
        if ($compound == "Myrcene") {
            $myrcene = $area;
        }
        if ($compound == "Limonene") {
            $limonene = $area;
        }
        if ($compound == "Terpinolene") {
            $terpinolene = $area;
        }
        if ($compound == "Linalool") {
            $linalool = $area;
        }
        if ($compound == "Alpha-Terpineol") {
            $alphaterpineol = $area;
        }
        if ($compound == "Beta-Caryophyllene") {
            $betacaryophyllene = $area;
        }
        if ($compound == "Humulene") {
            $humulene = $area;
        }
        if ($compound == "Cis-Ocimene") {
            $cisocimene = $area;
        }
        if ($compound == "TerpTHC") {
            $terpthc = $area;
        }
    }
    

// Clear and create array for HTML output
    
$thc = "";
$thca = "";

// Get the THC and THCA values for the sample
$sql = "select thc, thca from tblcannabinoids where sampleid = '$samplename' order by ndatetime desc limit 1";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {
        $thc = $row["thc"];
        $thca = $row["thca"];          
    }           
} 

if (is_null($thc)) {
  $thc = .001;  
}

if (!is_null($thc)) {
    if (strlen($thc) < 1) {
        $thc = .001;
    }        
}

if (is_null($thca)) {
  $thca = .001;  
}

if (!is_null($thca)) {
    if (strlen($thca) < 1) {
        $thca = .001;
    }        
}

$arrcalc = array();

$arrcalc["thc"] = $thc;
$arrcalc["thca"] = $thca; 

$arrcalc["guid"] = $guid;
$arrcalc["samplename"] = $samplename;
$arrcalc["samplemass"] = $samplemass;
$arrcalc["injectiondate"] = $injectiondate;
$arrcalc["ninjectiondate"] = $ninjectiondate;
$arrcalc["sampletype"] = $sampletype;
$arrcalc["terpthc"] = $terpthc;
$arrcalc["Alpha-Pinene"] = $alphapinene;
$arrcalc["Beta-Pinene"] = $betapinene;
$arrcalc["Myrcene"] = $myrcene;
$arrcalc["Limonene"] = $limonene;
$arrcalc["Terpinolene"] = $terpinolene;
$arrcalc["Linalool"] = $linalool;
$arrcalc["Alpha-Terpineol"] = $alphaterpineol;
$arrcalc["Beta-Caryophyllene"] = $betacaryophyllene;
$arrcalc["Humulene"] = $humulene;
$arrcalc["Cis-Ocimene"] = $cisocimene;


/*
?>
<pre>
<?php print_r($arrcalc) ?>
</pre>
<?php
*/


?>
<div id="applydiv">

<?php echo "<span style=\"font-weight:bold;font-size:120%;margin:10px 10px 2em 10px;\" class=\"$guid\">Sample Name: $key</span>"; ?>
<div id="<?php echo $guid ?>" class="rowparent <?php echo $guid ?>" style="margin-bottom:2em;">
<img src="images/close.png" class="closebtn" onclick="removesamplefromresults('<?php echo $guid ?>')" />

<?php


echo(makeformula($arrcalc));

?>

</div>

</div>

<?php 


}

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
    text-align: center;
    padding: 2px 5px 2px 5px;
}

.formula table {
    width: 100%;
}

.formulalabel {
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

</style>";
           
             

function makeformula($arr) {
    
    global $dbconn;
    global $wetmass;
    global $drymass;
    global $weighboat;
    global $watercontent;
    global $correctedmass;
    global $instrumentname;
     
    $samplename = $arr["samplename"];
    $samplemass = $arr["samplemass"];
    $sampletype = $arr["sampletype"];
    $guid = $arr["guid"];
    $thc = $arr["thc"];
    $thca = $arr["thca"];
    $injectiondate = $arr["injectiondate"];
    $ninjectiondate = $arr["ninjectiondate"];
    $ret = "";
    $terpthc = $arr["terpthc"];
      
    foreach (array_slice($arr, 8) as $key => $val) {   

    //Create the HTML markup for each sample
        
        // Get response factor for compound from tblconstants
        $responsefactor = 1;
        $sql = "select cvalue from tblconstants where cname = 'responsefactor' and ckey = '$key'";
        $stmt = $dbconn->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                $responsefactor = $row["cvalue"];
            }
        }
        $sname = make_url_friendly($samplename);                
        $prefix = $key . $sname; // Unique ID for sample       
     
     

       if (strlen($val) > 0) {
            $ret .= "<script>$('#$prefix input').keyup(function(){calculatepercentage('$prefix', '$sampletype')});calculatepercentage('$prefix')</script>                
            <div id=\"$prefix\" class=\"formula\" data-guid=\"$guid\" data-compound=\"$key\" data-samplename=\"$samplename\" data-samplemass=\"$samplemass\" data-sampletype=\"$sampletype\" data-injectiondate=\"$injectiondate\" data-ninjectiondate=\"$ninjectiondate\">
                <div style=\"padding:10px;\">
                    <table class=\"centeralign\">
                        <tr title=\"PA = Peak Area, RF = Response Factor, csvTHC = peak area of thc on gc at ~60.7 minutes, % = Percentage \">                    
                            <td><label class=\"formulalabel\">$key" . "</label></td>                            
                            <td><label class=\"formulalabel\">PA</label><br /><input type=\"text\" value=\"$val\" id=\"$prefix" . "_area\"></input></td>
                            <td><label class=\"formulalabel\">RF</label><br /><input type=\"text\" value=\"$responsefactor\" id=\"$prefix" . "_responsefactor\"></td>
                            <td><label class=\"formulalabel\">csvTHC</label><br /><input type=\"text\" value=\"$terpthc\" id=\"$prefix" . "_terpthc\"></input></td>
                            <td><label class=\"formulalabel\">THCA</label><br /><input type=\"text\" value=\"$thca\" id=\"$prefix" . "_thca\"></input></td>
                            <td><label class=\"formulalabel\">THC</label><br /><input type=\"text\" value=\"$thc\" id=\"$prefix" . "_thc\"></input></td>
                            <td><label class=\"formulalabel\"> PCT (%)</label><br /><input type=\"text\" style=\"text-align:right;\" class=\"formularesult\" id=\"$prefix" . "_percentage\"></input>%</td>
                            <td><button id=\"btn_$prefix\" class=\"btncommit\" data-prefix=\"$prefix\" style=\"margin-left:20px;\" onclick=\"commitchange('$prefix', 'false');\">Commit</button><button id=\"btn_delete_$prefix\" style=\"margin-left:1em;\" class=\"btncommit\" data-prefix=\"$prefix\" style=\"margin-left:20px;\" onclick=\"removecompound('$prefix');\">Delete</button></label></td>                            
                        </tr>                        
                    </table>
                </div>
            </div>";
        }                 
    }        
    return $ret;    
}

function getcompounds($vals) {
    
    $foundcompounds = array();
    $compounds = array();
    $sizeofvals = sizeof($vals);
    
    global $dbconn;
    
    for ($i = 0; $i <= $sizeofvals; $i++) { 

        if (!isset($vals[$i])) {
            continue;
        }
        
        $ret = $vals[$i][0];
        $area = $vals[$i][1];    
        $samplename = $vals[$i][2];
        $injectiondate = $vals[$i][3];
        $ninjectiondate = $vals[$i][4];
        $guid = $vals[$i][5];
        $runid = $vals[$i][6];
        $samplemass = $vals[$i][7];
        
        // Check Retention Time ($ret) against Retention Windows to determine compounds
        
        $sql = "CALL calcCompoundsProc($runid, 'terpenes');";
        $q = $dbconn->query($sql);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        
        while ($r = $q->fetch()){

            array_push($foundcompounds, array($r["Compound"], $r["PeakArea"], $samplename, $injectiondate, $ninjectiondate, $guid, $r["RetTime"], $samplemass)); 

        }
        $q->closeCursor();
             
    }   

return $foundcompounds;   
}

function getjavascript() {
    
    global $instrumentname;
    
?>
<script>

var controlbuttons = '<div><button class="bcbutton" id="btncommitall" style="margin-top:1.25em;">Commit All</button><button style="margin-top:1.25em;display:none;" class="bcbutton" id="btnapply"> Approve </button></div>';

$("#controlbuttons").html(controlbuttons);

$("#rtinstrument").val("<?php echo $instrumentname ?>");
$("#rtcompoundtypes").val("terpenes");
rtinstrument();
$("#retentiontimewindows").hide();

    function calculatepercentage(prefix) {
    
    // Do the calculations based on sample type
    
    var area = document.getElementById(prefix+'_area').value;
    var responsefactor = document.getElementById(prefix+'_responsefactor').value;
    var terpthc = document.getElementById(prefix+'_terpthc').value;
    var thca = document.getElementById(prefix+'_thca').value;
    var thc = document.getElementById(prefix+'_thc').value;
    var percentage = "";
   
    percentage = (((parseFloat(area)) / parseFloat(responsefactor)) / parseFloat(terpthc)) * ((parseFloat(thca) * 0.88) + parseFloat(thc));       
    percentage = roundToTwo(percentage);
      
    document.getElementById(prefix+'_percentage').value = percentage; //round(percentage, 2);

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

function roundToTwo(value) {
    return(Math.round(value * 100) / 100);
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

function removecompound(prefix) {
    var x = confirm("Really remove this compound?");
    
    if (x) {    
        $("#" + prefix).remove();
    };    
}
 
$(document).ready(function() {
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
            arr["sampleid"] = $(this).data("samplename");
            arr["sampletype"] = $(this).data("sampletype");
            arr["samplemass"] = $(this).data("samplemass");
            arr["injectiondate"] = $(this).data("injectiondate");
            arr["ninjectiondate"] = $(this).data("ninjectiondate");
            arr["percentage"] = $("#"+id+"_percentage").val();           
            arr["terpthc"] = $("#"+id+"_terpthc").val();           
            arr["instrumentname"] = "<?php echo $instrumentname ?>";                               
            finalarr[id] = arr;
            arr = {}; 
        });            
          // console.log(JSON.stringify(finalarr));
           
           $.post("commitdata_terpenes.php", {finalarr: JSON.stringify(finalarr)}, 
            function(data) { 
                          
                $("#testtype").val("");
                showuploadbutton();
                waitingDialog.hide();
                setTimeout(function() {
                $("#results").html("<div style=\"padding-left:10em;\"><h1>Success!</h1></div>");                
                $("#btnapply").hide();
                $("#btncommitall").hide(); 
               
                }, 0);                    
            });
        });
    });
</script>
<?php 
}
?>

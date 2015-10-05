<?php 

include "includes.php";

if (!logincheck()) {
    header('Location: logout.php');
    die();
}

ob_start();

// Get the uploaded .csv file
$filename = $_POST["filename"];
$instrumentname = $_POST["instrumentname"];

if ($instrumentname == "5890") {
    $instrumentname = "GC 5890";
}
if ($instrumentname == "6890") {
    $instrumentname = "GC 6890";
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
<?php print_r($data); ?>
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
    $samplename = $data[$i][0];
    if ((strlen($samplename) < 1) || (!is_numeric($samplename))) {
        unset($data[$i]);
        continue;
    }
    
    $x = $dbconn->query("select count(*) from tblsamples where sample_id = '$samplename' and (active <> 'false' or active = '' or active is null)")->fetchColumn();
    if ($x < 1) {
    ob_end_clean();
        echo "The Sample ID \"" .  $samplename . "\" in the import file does not exist in the system.</p><p>Please check that all of the Sample IDs are correct in the import file, and if so, please ensure that those Sample IDs exist in the system.</p><p>The import process has been aborted.</p>";
        break;
        die();
    }


    // Get date and time
    $adate = $data[$i][2];
    $date = strtotime($data[$i][2]);

    // Get rid of any other rows with the same sample name and a greater date
    $datasize2 = sizeof($data);
    for ($y=0; $y <= $datasize2; $y++) {
        
        if (isset($data[$y])) {
            
            if ($data[$y][0] == $samplename) { 
            
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
    for ($x = 1; $x <= $datasize3; $x++) {
        if (isset($data[$x][0])) {
            $samplename = $data[$x][0];    
            if (strpos($samplename, ".")) {        
                $arrsn = explode(".", $samplename);   
                if (end($arrsn) > 1) {
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
            $sql = "insert into datapoints (RunID, SampleID, InstrumentName, RetTime, PeakArea) values (:runid, :sampleid, :instrumentname, :rettime, :peakarea)";
            $stmt = $dbconn->prepare($sql);
            $stmt->execute(array(':runid'=>$runid, ':sampleid'=>$samplename, ':instrumentname'=>$instrumentname, ':rettime'=>$arr[$i], ':peakarea'=>$arr[$y]));
            
            array_push($vals, array($arr[$i], $arr[$y], $samplename, $samplemass, $injectiondate, $ninjectiondate, $guid, $runid));
        }        
    }


/*
?>
<pre>
<?php print_r($vals) ?>
</pre>
<?php
*/
        
    $totaldata[$samplename] = getcompoundvalues($vals);
    
}

/*
?>
<pre>
<?php print_r($totaldata) ?>
</pre>
<?php
*/


// Write the Javascript functions to the DOM and start the HTML output
getjavascript();
?>

<div id="manageretentiontimes"></div>

<div id="applydiv">
<?php

/*
?>
<pre>
<?php print_r($totaldata) ?>
</pre>
<?php
*/

foreach ($totaldata as $key=>$val) {
    
    // Loop through the results, get information, then write out the HTML for each sample
    
        $guid = com_create_guid();
        $guid = substr($guid, 1, -1);
        $samplename = $key;
        $injectiondate = $val[0][4];
        $ninjectiondate = $val[0][5];
        $guid = $val[0][6];
        $samplemass = $val[0][7];
      
        $sampletype = "Concentrate";      
                    
        $butane = "";
        $isobutane = "";
        $dichloromethane = "";
        $hexane = "";
        $benzene = "";
        $heptane = "";
        $toluene = "";
        $xylene = "";
        
        $butaneret = "";
        $isobutaneret = "";
        $dichloromethaneret = "";
        $hexaneret = "";
        $benzeneret = "";
        $heptaneret = "";
        $tolueneret = "";
        $xyleneret = "";
       
    foreach ($val as $key1=>$val1) {        
         
        $compound = $val1[0];
        $area = $val1[1];
        $slope = $val1[3];
        $rettime = $val1[8];
        
        if ($compound == "Butane") {
            $butane = $area . "_" . $slope;
            $butaneret = $rettime;            
        }
        if ($compound == "Isobutane") {
            $isobutane = $area . "_" . $slope;
            $isobutaneret = $rettime;            
        }
        if ($compound == "DiChloromethane") {
            $dichloromethane = $area . "_1";
            $dichloromethane_peak_area = $area;
            $dichloromethaneret = $rettime;
        }
        if ($compound == "Hexane") {
            $hexane = $area . "_" . $slope;
            $hexaneret = $rettime;
        }
        if ($compound == "Benzene") {
            $benzene = $area . "_" . $slope;
            $benzeneret = $rettime;
        }
        if ($compound == "Heptane") {
            $heptane = $area . "_" . $slope;
            $heptaneret = $rettime;
        }
        if ($compound == "Toluene") {
            $toluene = $area . "_" . $slope;
            $tolueneret = $rettime;
        }
        if ($compound == "Xylene") {
            $xylene = $area . "_" . $slope;
            $xyleneret = $rettime;
        }
    }        

// Clear and create array for HTML output
    
$arrcalc = array();
$arrret = array();

$arrcalc["guid"] = $guid;
$arrcalc["samplename"] = $samplename;
$arrcalc["injectiondate"] = $injectiondate;
$arrcalc["ninjectiondate"] = $ninjectiondate;
$arrcalc["samplemass"] = $samplemass;
$arrcalc["Butane"] = $butane;
$arrcalc["Isobutane"] = $isobutane;
$arrcalc["Hexane"] = $hexane;
$arrcalc["Benzene"] = $benzene;
$arrcalc["Heptane"] = $heptane;
$arrcalc["Toluene"] = $toluene;
$arrcalc["Xylene"] = $xylene;
$arrcalc["DiChloromethane"] = $dichloromethane;

$arrret["Isobutane"] = $isobutaneret;
$arrret["Butane"] = $butaneret;
$arrret["Hexane"] = $hexaneret;
$arrret["Benzene"] = $benzeneret;
$arrret["Heptane"] = $heptaneret;
$arrret["Toluene"] = $tolueneret;
$arrret["Xylene"] = $xyleneret;
$arrret["DiChloromethane"] = $dichloromethaneret;


$html = makeformula($arrcalc, $arrret);

if (strlen($html) > 0) {
?>

<div>

<?php echo "<span style=\"font-weight:bold;font-size:120%;margin:10px 10px 2em 10px;\" class=\"$guid\">Sample Name: $key</span>"; ?>
<div id="<?php echo $guid ?>" class="rowparent <?php echo $guid ?>" style="margin-bottom:2em;">
<img src="images/close.png" class="closebtn" onclick="removesamplefromresults('<?php echo $guid ?>')" />

<?php

echo $html;

?>

</div>

</div>

<?php 

}

}

echo "<style>

.rowparent {
    background-color: #F1F1F1;
    border: 1px solid lightgrey;
}

.formula {
    width: 100%;
    min-width: 620px;    
}

.formula input {
    width: 5em;
    border: none;
    text-align: left;
    padding: 2px 5px 2px 5px;
}

.formula table {
    width: 100%;
    min-width: 600px; 
}

.formulalabel {
    width: 4em;
    text-align: left;
 }

.formulalabel1 {
    text-align: right;
    margin-right: 1em;
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
           
             

function makeformula($arr, $arrret) {
    
     /*
     ?>
    <pre>
    <?php print_r($arrret) ?>
    </pre>
    <?php
    */
    
    global $dbconn;
        
    $samplename = $arr["samplename"];
    $samplemass = $arr["samplemass"];
    $guid = $arr["guid"];
    $injectiondate = $arr["injectiondate"];
    $ninjectiondate = $arr["ninjectiondate"];
    $sampletype = "Concentrate";
        
/*
?>
<pre>
<?php print_r($arr) ?>
</pre>
<?php
*/
    
    $ret = "";   
    foreach (array_slice($arr, 5) as $key => $val) { 

    //Create the HTML markup for each sample
       
        $sname = make_url_friendly($samplename);                
        $prefix = $key . $sname; // Unique ID for sample

        
        if (strlen($val) > 0) { 
            $arrval = array();
             
            $arrval = explode("_", $val);

            $area = $arrval[0];
            $slope = $arrval[1];
            $rettime = $arrret[$key];         
                   
            $ret .= "<script>$('#$prefix input').keyup(function(){calculatepercentage('$prefix', '$sampletype')});calculatepercentage('$prefix', '$sampletype')</script>
            <div id=\"$prefix\" class=\"formula\" data-guid=\"$guid\" data-compound=\"$key\" data-samplename=\"$samplename\" data-sampletype=\"$sampletype\" data-injectiondate=\"$injectiondate\" data-ninjectiondate=\"$ninjectiondate\">
                <div style=\"padding:10px;\">
                    <table>
                        <tr title=\"RT = Retention Time, PA = Peak Area, Slope = Slope\">                    
                            <td style=\"width:12em;\"><label class=\"formulalabel\">$key" . "</label></td> 
                            <td><label class=\"formulalabel\">RT</label><input type=\"text\" value=\"$rettime\" disabled></input></td>
                            <td><label class=\"formulalabel1\">PA:</label><input type=\"text\" value=\"$area\" id=\"$prefix" . "_area\"></input></td>
                            <td><label class=\"formulalabel1\">Slope:</label><input type=\"text\" value=\"$slope\" id=\"$prefix" . "_slope\"></td>
                            <td><label class=\"formulalabel1\">Mass:</label><input type=\"text\" value=\"$samplemass\" id=\"$prefix" . "_samplemass\"></td>
                            <td><input type=\"text\" class=\"formularesult\" id=\"$prefix" . "_percentage\"></input>ppm</td>
                            <td><button id=\"btn_$prefix\" class=\"btncommit\" data-prefix=\"$prefix\" style=\"margin-left:20px;\" onclick=\"commitchange('$prefix', 'false');\">Commit</button><button id=\"btn_delete_$prefix\" style=\"margin-left:1em;\" class=\"btncommit\" data-prefix=\"$prefix\" style=\"margin-left:20px;\" onclick=\"removecompound('$prefix');\">Delete</button></td>                            
                        </tr>                        
                    </table>
                </div>
            </div>";                
        }             
    } 
  
    return $ret; 
}


function getcompoundvalues($vals) {
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
    $arrret = array();
    $arr = [];
    $arr2 = array();
    $sizeofvals = sizeof($vals);
    
    for ($i = 0; $i <= $sizeofvals; $i++) { 

        if (!isset($vals[$i])) {
            continue;
        }
        
        $retval = $vals[$i][0];
        $area = $vals[$i][1];    
        $samplename = $vals[$i][2];
        $samplemass = $vals[$i][3];
        $injectiondate = $vals[$i][4];
        $ninjectiondate = $vals[$i][5];
        $guid = $vals[$i][6];
        $runid = $vals[$i][7];
        
        $ret = "";
        $slopeval = "";
        
        $arrret = explode(".", $retval);
        
        //troubleshooting("parsecsv_residual_solvents.php", "Before Stored Proc Line 487 RunID: $runid - SampleID: $samplename");
        
        $sql = "CALL calcCompoundsProc($runid, 'residualsolvents');";
        $q = $dbconn->query($sql);
        $q->setFetchMode(PDO::FETCH_ASSOC);
         
        /*
        ?>
         <pre>
         Return from Stored Proc
         <?php print_r($q->fetch()) ?>
         </pre>
         <?php 
         */

        $arr = array();         
         while ($r = $q->fetch()){ 
         
            $compound = $r["Compound"];
            //if (strlen($compound > 0)) {
                $arr[$r["SampleID"]][$compound]["area"] = $r["PeakArea"];
                $arr[$r["SampleID"]][$compound]["ret"] = $r["RetTime"]; 
            //}            
         };
         
        $q->closeCursor();
        
        //troubleshooting("parsecsv_residual_solvents.php", "After Stored Proc Line 514 RunID: $runid - SampleID: $samplename");
      
        $arr1 = array();
        
        //troubleshooting("parsecsv_residual_solvents.php", "Before Get Slope Line 518 RunID: $runid - SampleID: $samplename");
        
        /*
        ?>
        <pre>
        <?php print_r($arr) ?>
        </pre>        
        <?php 
        */
       
        foreach ($arr as $key=>$val) {
            $sampleid = $key;
            foreach ($val as $key1=>$val1) {        
                $compound = $key1;
                $area = $val1["area"];
                $ret = $val1["ret"];
                $slopeval = getslope("$instrumentname", "$compound");
                $arr1[$sampleid . $compound] = array($compound, $area, $samplename, $slopeval, $injectiondate, $ninjectiondate, $guid, $samplemass, $ret);
                         
            }
        }
        
        array_push($arr2, $arr1);
        
        //troubleshooting("parsecsv_residual_solvents.php", "After Get Slope Line 532 RunID: $runid - SampleID: $samplename");
    }
    
    foreach ($arr2 as $key1=>$val1) {    
        foreach ($val1 as $key=>$val) {
            array_push($foundcompounds, $val);
        } 
    }
  
    
return $foundcompounds;   
}

function getslope($instrument, $compound) {
    
    global $dbconn;
    $slope = "";

    $sql = "select cvalue from tblconstants where cname = 'slope' and instrument = '$instrument' and ckey = '$compound'";   
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) { 
           $slope = $row["cvalue"];
        }
    }
    
    $stmt->closeCursor();
    
    return $slope;    
}

function getjavascript() {
    
     global $instrumentname;
     
?>
<script>

var controlbuttons = '<div><button class="bcbutton" id="btncommitall" style="margin-top:1.25em;">Commit All</button><button style="margin-top:1.25em;display:none;" class="bcbutton" id="btnapply"> Approve </button></div>';

$("#controlbuttons").html(controlbuttons);

$("#rtinstrument").val("<?php echo $instrumentname ?>");
$("#rtcompoundtypes").val("residualsolvents");
rtinstrument();
$("#retentiontimewindows").hide();

    function calculatepercentage(prefix, sampletype) {
    
    // Do the calculations based on sample type
    
    var area = document.getElementById(prefix+'_area').value;
    var slope = document.getElementById(prefix+'_slope').value;
    var samplemass = document.getElementById(prefix+'_samplemass').value;
       
    //if (area > 0) {
        percentage = (area / slope) / samplemass;
    //}  
       
    document.getElementById(prefix+'_percentage').value = round(percentage, 2);

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

function removecompound(prefix) {
    
    var x = confirm("Really remove this compound?");
    
    if (x) {    
        $("#" + prefix).remove();
    };
    
}

function showapplybutton(all) {
    commitchange('', 'true');
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
            arr["samplemass"] = $("#"+id+"_samplemass").val();
            arr["injectiondate"] = $(this).data("injectiondate");
            arr["ninjectiondate"] = $(this).data("ninjectiondate");
            arr["percentage"] = $("#"+id+"_percentage").val();
            arr["area"] = $("#"+id+"_area").val();           
            arr["instrumentname"] = "<?php echo $instrumentname ?>"; 
            //if ($(this).data("compound") == "DiChloromethane") {               
                arr["dichloromethane_peak_area"] = $("#"+id+"_area").val();
            //} 
            finalarr[id] = arr;
            arr = {}; 
        });            
           
           //console.log(JSON.stringify(finalarr))
           
           $.post("commitdata_residualsolvents.php", {finalarr: JSON.stringify(finalarr)}, 
            function(data) {
                if (data.length > 0) {
                    window.top.location.href = 'logout.php';
                    return false;
                }             
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
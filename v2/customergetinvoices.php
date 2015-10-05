<?php 

include "includes.php";

$strc = "";
$strrs = "";
$strt = "";

$cluid = "";

if (isset($_SESSION["cluid"])) {
    $cluid = $_SESSION["cluid"];
}

if (strlen($cluid) < 1) {
    die();
}

$currentcount = 0;

if (isset($_POST["currentcount"])) {
    $currentcount = $_POST["currentcount"] + 0;
}
$nextcount = $currentcount + 50;

$ccounter = 0;
$rscounter = 0;
$tcounter = 0;

$sql = "select distinct quickbooks_invoice_id from tblsamples where sent_to_quickbooks = 1 and (active is null or active = '' or active <> 'false') and (license_number = :cluid) limit $currentcount, $nextcount";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute(array(':cluid'=>$cluid))) {
    while ($row = $stmt->fetch()) { 
        $quickbooks_invoice_id = $row["quickbooks_invoice_id"];
        //$quickbooks_invoice_id = '181';
                
        $sql1 = "select min(ndate_test_completion_workflow) as min, max(ndate_test_completion_workflow) as max from tblsamples where quickbooks_invoice_id='$quickbooks_invoice_id'";
        $stmt1 = $dbconn->prepare($sql1);
        if ($stmt1->execute()) {
            while ($row1 = $stmt1->fetch()) { 
            
            $min = date('m/d/Y', $row1["min"]);
            $max = date('m/d/Y', $row1["max"]);
            
            }
        } 
        
        $guid = $uniqueID;

        $strc .= "</div><div><table class=\"cptable\"><tr><td><a href=\"showinvoice.php?id=$quickbooks_invoice_id\" target=\"_blank\">Invoice for Samples: $min through $max</a><br /><a href=\"javascript:void(0)\" onclick=\"showsampledetails('$guid', '$quickbooks_invoice_id')\" style=\"font-size:80%;font-style:italic;margin-left:2em;\">Details...</a></td></tr></table><div id=\"$guid\"><br /></div></div>";  

        $ccounter = $ccounter + 1;          
    }
}

if ($ccounter == 0) {
    $finalstr = "done###@@CANNALIMSSPLITTER@@###";
}
else
{
    
$finalstr = $nextcount . "###@@CANNALIMSSPLITTER@@###<style>.cptable tr td { padding:.5em; text-align: left !important; } .cptable { width:100%; } </style><div class=\"row\">$strc</div>";
}


echo $finalstr;


?>
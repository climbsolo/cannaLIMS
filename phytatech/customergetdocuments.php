<?php 

include "includes.php";

$strc = "";
$strrs = "";
$strt = "";
$cluid = "";

if (isset($_SESSION["cluid"])) {
    $cluid = $_SESSION["cluid"];
}

$searchby = $_POST["searchby"];
$searchfor = $_POST["searchfor"];
$currentcount = $_POST["currentcount"] + 0;
$nextcount = $currentcount + 100;

$sqlstr = " and $searchby like '%$searchfor%' ";

if ($searchby == "all" || strlen($searchby) < 1) {
    $sqlstr = "";
}
if ($searchby == "adatecheckedin") {
    $ndatecheckedin = strtotime($searchfor);
    
    $sqlstr = " and ndatecheckedin = '$ndatecheckedin' ";
}

$sqlstr .= " order by ndatecheckedin desc limit $currentcount, $nextcount ";

$ccounter = 0;
$rscounter = 0;
$tcounter = 0;

$sql = "select * from tblreports where licensenumber = '$cluid' and filename like '%Cannabinoids%' and (approved is not null and approved <> '') $sqlstr";

$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) { 
        $ccounter = $ccounter + 1;
        $ncheckindate = $row["ndatecheckedin"];        
        $checkindate= date("m/d/Y", $ncheckindate);        
        $productname = $row["productname"];
        $sampleid = $row["sampleid"];
        $id = $row["id"];
        if ($ccounter > 0) {
            $strc .= "<table class=\"cptable\"><tr><td style=\"width:8em;\">$checkindate</td><td><a href=\"showreport.php?id=$id\" target=\"_blank\">Cannabinoids_$productname" . "_" . "$sampleid</a></td></tr></table>";
        }       
    }
}
    
$sql = "select * from tblreports where licensenumber = '$cluid' and filename like '%Terpenes%' and (approved is not null and approved <> '')  $sqlstr";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) { 
        $tcounter = $tcounter + 1;    
        $ncheckindate = $row["ndatecheckedin"];        
        $checkindate= date("m/d/Y", $ncheckindate);
        $productname = $row["productname"];
        $sampleid = $row["sampleid"];
        $id = $row["id"];        
        if ($tcounter > 0) {
            $strt .= "<table class=\"cptable\"><tr><td style=\"width:8em;\">$checkindate</td><td><a href=\"showreport.php?id=$id\" target=\"_blank\">Terpenes_$productname" . "_" . "$sampleid</a></td></tr></table>";
        }
    }
}

$sql = "select * from tblreports where licensenumber = '$cluid' and filename like '%ResidualSolvents%' and (approved is not null and approved <> '')  $sqlstr";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) { 
        $rscounter = $rscounter + 1;    
        $ncheckindate = $row["ndatecheckedin"];        
        $checkindate= date("m/d/Y", $ncheckindate);
        $productname = $row["productname"]; 
        $sampleid = $row["sampleid"]; 
        $id = $row["id"];
        if ($rscounter > 0) {
        $strrs .= "<table class=\"cptable\"><tr><td style=\"width:8em;\">$checkindate</td><td><a href=\"showreport.php?id=$id\" target=\"_blank\">ResidualSolvents_$productname" . "_" . "$sampleid</a></td></tr></table>";
        }
        
    }
}   
    

if ($currentcount < 1) {
    $strc = "<h3 style=\"text-align:center;\">Cannabinoids (Potency)</h3>$strc";
    $strt = "<h3 style=\"text-align:center;\">Terpenes</h3>$strt";
    $strrs = "<h3 style=\"text-align:center;\">Residual Solvents</h3>$strrs";
}
    
    
if ($ccounter + $rscounter + $tcounter == 0) {
    $finalstr = "done###@@CANNALIMSSPLITTER@@###";
}
else
{
    
$finalstr = $nextcount . "###@@CANNALIMSSPLITTER@@###<style>.cptable tr td { padding:.5em; text-align: left !important; } .cptable { width:100%; } </style><div class=\"row\" style=\"font-weight:bold;text-align:right;font-size:110%;\">License Number: $cluid</div><div class=\"col-lg-4\">$strc</div><div class=\"col-lg-4\">$strrs</div><div class=\"col-lg-4\">$strt</div></div>";
}


echo $finalstr;


?>
<?php 

include "includes.php";


if (!isset($_SESSION["cluid"])) {
    die();
}

$cluid = $_SESSION["cluid"];

if (strlen($cluid) < 1) {
    die();    
}

if (!isset($_GET["qbid"])) {
    die();
}

$qbid = $_GET["qbid"];

$total = 0;
$ret = "";

$sql = "select * from vw_testsbyclientall where quickbooks_invoice_id=:qbid order by sample_id";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute(array(':qbid'=>$qbid))) {
    while ($row = $stmt->fetch()) { 
    
        $ret .= "<tr><td>" . $row["sample_id"] . "</td><td>" . $row["date_report_approval_workflow"] . "</td><td>" . $row["productdescription"] . "</td><td>$" . $row["price"] . "</td></tr>";        
        $total = ($total+0) + ($row["price"]+0);    
    
    }
}

$ret = "<hr /><table class=\"tablesorter zebra\" id=\"pendingsamples\" style=\"width:100%;\"><thead><tr><th>Sample ID</th><th>Test Date<th>Test Performed</th><th>Test Price</th></thead>" . $ret . "<td></td><td></td><td></td><td><span style=\"font-weight:bold;\">Total: $" . $total . "</span></td></table><hr /><br />";

echo $ret;

?>
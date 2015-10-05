<?php 



include "includes.php";

if (!isset($_SESSION["cluid"])) {
    die();
}

$cluid = $_SESSION["cluid"];

if (strlen($cluid) < 1) {
    die();    
}

$ret = "";
$total = 0;

$sql = "select * from vw_testsbyclientall where license_number=:cluid and sent_to_quickbooks=0 order by sample_id";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute(array(':cluid'=>$cluid))) {
    while ($row = $stmt->fetch()) {    
        $ret .= "<tr><td>" . $row["sample_id"] . "</td><td>" . $row["date_test_completion_workflow"] . "</td><td>" . $row["productdescription"] . "</td><td>$" . $row["price"] . "</td></tr>";
        
        $total = ($total+0) + ($row["price"]+0);
    }
}

showfooter(); 

$ret = "<hr /><table class=\"tablesorter zebra\" id=\"pendingsamples\" style=\"width:100%;\"><thead><tr><th>Sample ID</th><th>Test Date<th>Test Performed</th><th>Test Price</th></thead>" . $ret . "<td></td><td></td><td></td><td><span style=\"font-weight:bold;\">Total: $" . $total . "</span></td></table><hr /><br />";

echo $ret;

?>
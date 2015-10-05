<?php 

include "includes.php";

$startdate = "";
if (isset($_GET["startdate"])) {
    $startdate = $_GET["startdate"];
}

$enddate = "";
if (isset($_GET["enddate"])) {
    $enddate = $_GET["enddate"];
};

$sql = "select * from vw_selectinvoicetestsrun where (`Sent to Quickbooks` >= STR_TO_DATE('$startdate', '%m/%d/%Y') and `Sent to Quickbooks` <= STR_TO_DATE('$enddate', '%m/%d/%Y'))";

$arr = array();

$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       
        array_push($arr, $row);
       
    }
}

if (!isset($arr[0])) {
    echo "No Results Found";
    die();
}

$th = "";
$str = "";
$tempstr = "";
$tablestr = "";

foreach ($arr[0] as $key=>$val) {
    $th .= "<th>" . $key . "</th>";
}


$tablestr = "<table id=\"invoicing_details_results_table\" class=\"tablesorter\"><thead>" . $th . "</thead>";

foreach ($arr as $key=>$val) {
    foreach ($val as $key1=>$val1) {
        $tempstr .= "<td>$val1</td>";
    }
    
    $str .= "<tr>$tempstr</tr>";
    $tempstr = "";
}

echo $tablestr .= "<tbody>$str</tbody></table>";

?>
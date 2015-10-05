<?php 

include "includes.php";

logincheck();

$qtype = $_GET["qtype"];

$str = "";
$searchby = "";
$searchfor = "";
$completed = "";
$sqlstr = "";
$arr = array();
$arr1 = array();
$page = "";
$limit = "";

if ($qtype == "samplelist") {
    
    if (isset($_GET["sb"])) {
        $searchby = $_GET["sb"];
    }
    
    if (isset($_GET["sf"])) {
        $searchfor = $_GET["sf"]; 
    }    
    $sqlstr = " and $searchby like '%$searchfor%' ";

    if ($searchby == "all" || strlen($searchby) < 1) {
        $sqlstr = "";
    }
    
    $teststoperform = "";
    
    if (isset($_GET["completed"])) {
        $completed = $_GET["completed"];
    }
    
    if ($completed == 'true') {
        $sqlstr .= " and completed = 'true' ";
    }
    else
    {
       $sqlstr .= " and (completed is null or completed = '' or completed <> 'true') "; 
    }
    
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    }
    
    if (isset($_GET["limit"])) {
        $limit = $_GET["limit"];
    }
    
    $rows = json_decode(getpagination('tblsamples', 'sample_id desc', "$sqlstr", "$qtype", $page, 'managesamples.php', $limit), true);
    
    
    if (isset($rows["error"])) {
        
        $arr1["error"] = $rows["error"];
        $str = json_encode($arr1);    
        echo $str;
        die();
    }
        
    $pagination = $rows["pagination"];
    $p_pagination = $rows["p_pagination"];
    $limithtml = $rows["limithtml"];
    
    foreach($rows["data"] as $row){
     
            $id = $row["sample_id"]; 
            $date_accepted = $row["date_accepted"];            
            $client_id = $row["client_id"];
            
            $business_name = "";
            $sql1 = "select business_name from tblclients where client_id = :client_id";
            $stmt1 = $dbconn->prepare($sql1);
            if ($stmt1->execute(array(':client_id'=>$client_id))) {
                while($row1 = $stmt1->fetch()) {
                    $business_name = $row1["business_name"];
                }
            }            
            
            $arr[$id]["date_accepted"] = $row["date_accepted"];
            $arr[$id]["license_number"] = $business_name . " " . $row["license_number"];
            $arr[$id]["product_name"] = $row["product_name"];
            $arr[$id]["package_amount"] = $row["package_amount"];
            
            if (strlen($arr[$id]["package_amount"]) < 1) {
                $arr[$id]["package_amount"] = 1;
            }            
            
            $arr[$id]["sample_mass"] = $row["sample_mass"];            
            $arr[$id]["wet_mass"] = $row["wet_mass"];
            $arr[$id]["product_type"] = $row["product_type"];
            
            $arrteststoperform = []; 
            $tests = "";            

            $sql2 = "select * from tblsamplesproductxref inner join tblproducts on tblsamplesproductxref.product_id = tblproducts.id where tblsamplesproductxref.sample_id = '$id' ";
            $stmt2 = $dbconn->prepare($sql2);
            if ($stmt2->execute()) {
                while($row2 = $stmt2->fetch()) {
                    $productdescription = $row2["productdescription"];
                    $dom_element_id = $row2["dom_element_id"];
                    $arrteststoperform[$dom_element_id] = $productdescription;
                }
            }         
            
            $teststoperform = json_encode($arrteststoperform);
            
            if(strlen($teststoperform) > 0) {  
                $arr1 = json_decode($teststoperform, true);
                foreach ($arr1 as $key1=>$val1) {
                    $tests .= $val1 . ", ";
                }
            }
           
            
            $arr[$id]["tests_to_perform"] = rtrim($tests, ", ");

            
            $arr[$id]["used_mass"] = (($row["sub_sample_mass_cannabinoids"] + 0) + ($row["sub_sample_mass_residual_solvents"] + 0) + ($row["sub_sample_mass_terpenes"] + 0)); 

            $used_mass =  $arr[$id]["used_mass"];
            $package_amount = $row["package_amount"] + 0; 
            $arr[$id]["unused_mass"] = (($arr[$id]["sample_mass"] + 0) - $arr[$id]["used_mass"]); 

            if ( strpos(strtolower($row["product_type"]), "edible") > -1) 
            {
                if ($package_amount == 0) {
                    $package_amount = 1;
                }
                                 
                $unused_mass = ($package_amount - $used_mass); 
                $unused_mass = $unused_mass / $package_amount;
                $arr[$id]["unused_mass"] = round($unused_mass * 100, 2) . "%";
                $arr[$id]["used_mass"] = round(((1 - $unused_mass) * 100), 2) . "%";
           
           }
            if (strtolower($row["product_type"]) == "flower") 
            {
                $weighboat = 0;
                $sql3 = "select cvalue from tblconstants where cname = 'weighboat'";
                $stmt3 = $dbconn->prepare($sql3);
                if ($stmt3->execute()) {
                    while($row3 = $stmt3->fetch()) {
                        $weighboat = $row3["cvalue"] + 0;
                    }
                }
                $arr[$id]["used_mass"] = ((($arr[$id]["wet_mass"] + 0) - $weighboat) - $arr[$id]["used_mass"]);                
                $arr[$id]["unused_mass"] = $arr[$id]["sample_mass"] - $arr[$id]["used_mass"];                
            }
           
            
            $arr[$id]["metrc_number"] = $row["metrc_number"];           
            $arr[$id]["date_test_completion_workflow"] = formatdate($row["date_test_completion_workflow"]);
            $arr[$id]["date_report_generation_workflow"] = formatdate($row["date_report_generation_workflow"]);  
            $arr[$id]["date_report_approval_workflow"] = formatdate($row["date_report_approval_workflow"]);            
            $arr[$id]["date_data_input_into_metrc_workflow"] = formatdate($row["date_data_input_into_metrc_workflow"]);  
            
            $reportslist = "";
            
            $sql2 = "select id, filename, filepath from tblreports where sampleid = '$id' and (active is null or active = '' or active <> 'false')";
            $stmt2 = $dbconn->prepare($sql2);
            if ($stmt2->execute()) {
                while($row2 = $stmt2->fetch()) {
                    $report_id = $row2["id"];
                    $filename = $row2["filename"];
                    
                    //$reportslist .= "<a href=\"" . $row2["filepath"] . "\" target=\"_blank\">" . $row2["filename"] . "</a><br />";
                    
                    $reportslist .=  "<a href=\"showreport_internal.php?id=$report_id\" target=\"_blank\">$filename</a><br />";
                    
                }
            }

            $arr[$id]["reportslist"] = $reportslist;          
        }

    
    $str = "";
       
    foreach ($arr as $key=>$val) {
        
        $str .= "<tr onclick=\"showinmanagesamples('" . $key . "')\">
        <td>
        <span>$key</span>
        </td>
        <td>
        <span title=\"" . $val["date_accepted"] . "\">" . $val["date_accepted"] . "</span>
        </td>
        <td>
        <span title=\"" . $val["license_number"] . "\">" . $val["license_number"] . "</span>
        </td>
        <td>
        <span title=\"" . $val["product_name"] . "\">" . $val["product_name"] . "</span>
        </td>
        <td>
        <span title=\"" .  $val["tests_to_perform"] . "\">" . $val["tests_to_perform"] .  "</span>
        </td>
        <td>
        <span title=\"" .  $val["used_mass"] . "\">" . $val["used_mass"] .  "</span>
        </td>
        <td>
        <span title=\"" .  $val["unused_mass"] . "\">" . $val["unused_mass"] .  "</span>
        </td>       
        <td>
        <span title=\"" . $val["metrc_number"] . "\">" . $val["metrc_number"] . "</span>
        </td>        
        <td onclick=\"event.stopPropagation();\">
        <input type=\"text\" id=\"date_test_completion_workflow\" title=\"" .  $val["date_test_completion_workflow"] . "\" value=\"" .  $val["date_test_completion_workflow"] . "\" style=\"background:transparent;border:none;padding:0;width:100%;\" class=\"datepicker\" onblur=\"updateworkflow($key, $(this));\"></input>
        <span title=\"" .  $val["date_test_completion_workflow"] . "\" >" . $val["date_test_completion_workflow"] .  "</span>
        </td>        
        <td onclick=\"event.stopPropagation();\">
        <input type=\"text\" id=\"date_report_generation_workflow\" title=\"" .  $val["date_report_generation_workflow"] . "\" value=\"" .  $val["date_report_generation_workflow"] . "\" style=\"background:transparent;border:none;padding:0;width:100%;\" class=\"datepicker\" onblur=\"updateworkflow($key, $(this))\"></input>
        <span title=\"" .  $val["date_report_generation_workflow"] . "\">" . $val["date_report_generation_workflow"] . "</span>
        </td>
        <td onclick=\"event.stopPropagation();\">
        <input type=\"text\" id=\"date_report_approval_workflow\" title=\"" .  $val["date_report_approval_workflow"] . "\" value=\"" .  $val["date_report_approval_workflow"] . "\" style=\"background:transparent;border:none;padding:0;width:100%;\" class=\"datepicker\" onblur=\"updateworkflow($key, $(this))\"></input>
        <span style=\"display:none;\">" . $val["date_report_approval_workflow"] . "</span>
        </td>
        <td onclick=\"event.stopPropagation();\">
        <input type=\"text\" id=\"date_data_input_into_metrc_workflow\" value=\"" .  $val["date_data_input_into_metrc_workflow"] . "\"  title=\"" .  $val["date_data_input_into_metrc_workflow"] . "\" style=\"background:transparent;border:none;padding:0;width:100%;\" class=\"datepicker\" onblur=\"updateworkflow($key, $(this))\"></input>
        <span style=\"display:none;\">" . $val["date_data_input_into_metrc_workflow"] . "</span>
        </td>
        <td onclick=\"event.stopPropagation();\">" . $val["reportslist"] . "</td>
        </tr>
       ";
    
    }
    
    $arr1["tds"] = $str;
    $arr1["pagination"] = $pagination;
    $arr1["p_pagination"] = $p_pagination;
    $arr1["limithtml"] = $limithtml;
    
    $str = json_encode($arr1);
    
    echo $str;
    

    
    die();
        
}

if ($qtype == "updatesample") {
    
$tval = $_POST["tval"];
$tcol = $_POST["tcol"];
$sample_id = $_POST["sample_id"];

$date = formatdate($tval);
$ndate = strtotime($date);

$sql = "update tblsamples set $tcol = :date, n" . $tcol . " = :ndate where sample_id = :sample_id";
$stmt = $dbconn->prepare($sql);
$stmt->execute(array(':date'=>$date, ':ndate'=>$ndate, ':sample_id'=>$sample_id));

if ($tcol == "date_report_approval_workflow") {
$sql = "update tblreports set approved = :ndate where sampleid = :sample_id";
$stmt = $dbconn->prepare($sql);
$stmt->execute(array(':ndate'=>$ndate, ':sample_id'=>$sample_id));
}

$sql = "select * from tblsamples where sample_id = :sample_id";
$stmt = $dbconn->prepare($sql);
$stmt->execute(array(':sample_id'=>$sample_id));
$row = $stmt->fetch(PDO::FETCH_ASSOC);          
               
foreach ($row as $key=>$val) {
    
    if (strpos($key, '_workflow') !== false) {
        if ($key[0] != "n") {
            $arr1[$key] = $val;
        }
    }   
}

//echo json_encode($arr1);

$completed = 'true';
    foreach ($arr1 as $key=>$val) {
        if (strlen($val) < 1) {
            $completed = null;
            $sql = "update tblsamples set completed = null where sample_id = :sample_id";
            $stmt = $dbconn->prepare($sql);
            $stmt->execute(array(':sample_id'=>$sample_id));  
            }      
        
        if ($key == 'date_report_approval_workflow') {            
            $sql = "update tblreports set approved = '$tval' where sampleid = :sample_id";
            $stmt = $dbconn->prepare($sql);
            $stmt->execute(array(':sample_id'=>$sample_id));            
            
        }
        
    }
        
    if ($completed == "true") {
        $sql = "update tblsamples set completed = 'true' where sample_id = :sample_id";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':sample_id'=>$sample_id));    
    }
    
    if (strlen($tval) > 0) {                
           logaction($tcol, $tval, $sample_id, 'tblsamples', '');
    }
    
die();

}

function formatdate($date) {
    
    if (strlen($date) < 1) {
        return;
    }
    //return date("m d Y", strtotime($date));
    return date("m/d/Y", strtotime($date));
}

?>
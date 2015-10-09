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
    
     if (isset($_GET["flower"])) {
        $flower = $_GET["flower"];
    }
    
    if ($flower == 'true') {
        $sqlstr .= " and product_type = 'Flower' ";
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
            $client_id = $row["client_id"];
            
            $business_name = "";
            $sql1 = "select business_name from tblclients where client_id = :client_id";
            $stmt1 = $dbconn->prepare($sql1);
            if ($stmt1->execute(array(':client_id'=>$client_id))) {
                while($row1 = $stmt1->fetch()) {
                    $business_name = $row1["business_name"];
                }
            }            
            
            $arr[$id]["license_number"] = $business_name . " " . $row["license_number"];
            $arr[$id]["product_name"] = $row["product_name"];
            $arr[$id]["product_type"] = $row["product_type"];
            $arr[$id]["batch_id"] = $row["batch_id"];
            $arr[$id]["metrc_number"] = $row["metrc_number"];
            $arr[$id]["rush_order"] = $row["rush_order"];
            $arr[$id]["tocopherol_peak_area"] = $row["tocopherol_peak_area"]; 
            $arr[$id]["dichloromethane_peak_area"] = $row["dichloromethane_peak_area"]; 
            $arr[$id]["wet_mass"] = $row["wet_mass"]; 
            $arr[$id]["dry_mass"] = $row["dry_mass"];   
              
           
            /*
            $teststoperform = $row["tests_to_perform"];
            $tests = ""; 
            */ 

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
            
            $tests = "";
            $arrteststoperform = [];
            $teststoperform = array();
            
        }

    
    $str = "";
       
    foreach ($arr as $key=>$val) {
        
        $str .= "<tr onclick=\"editsample('" . $key . "')\">
        <td>
        <span class=\"record_id\">$key</span>
        </td>
        <td>
        <span title=\"" . $val["license_number"] . "\">" . $val["license_number"] . "</span>
        </td>
        <td>
        <span title=\"" . $val["product_name"] . "\">" . $val["product_name"] . "</span>
        </td>
        <td>
        <span title=\"" . $val["product_type"] . "\">" . $val["product_type"] . "</span>
        </td>
        <td>       
        <span title=\"" . $val["batch_id"] . "\">" . $val["batch_id"] . "</span>
        </td>
        <td>
        <span title=\"" . $val["metrc_number"] . "\">" . $val["metrc_number"] . "</span>
        </td>
        <td>
        <span title=\"" . $val["tests_to_perform"] . "\" readonly>" . $val["tests_to_perform"] .  "</span>
        </td>
        <td>
        <span title=\"" . $val["rush_order"] . "\">" . $val["rush_order"] . "</span>
        </td>";
        
        //if ($completed == 'true') {
            
        $str .= "<td>
        <span title=\"" . $val["tocopherol_peak_area"] . "\">" . $val["tocopherol_peak_area"] . "</span>
        </td>
        <td>
        <span title=\"" . $val["dichloromethane_peak_area"] . "\">" . $val["dichloromethane_peak_area"] . "</span>
        </td>";
        
        //}
        
        //if ($flower == 'true') {
            
        $str .= "<td onclick=\"event.preventDefault();event.stopPropagation();\">
        <input type=\"text\" style=\"max-width:4em;\" onblur=\"updateflowermass('wet_mass', $key, $(this).val())\" value=\"" . $val["wet_mass"] . "\"></input><span style=\"display:none;\" title=\"" .  $val["wet_mass"] . "\"></span>
        </td>
        <td onclick=\"event.preventDefault();event.stopPropagation();\">
         <input type=\"text\" style=\"max-width:4em;\" onblur=\"updateflowermass('dry_mass', $key, $(this).val())\" value=\"" . $val["dry_mass"] . "\"></input><span style=\"display:none;\" title=\"" .  $val["dry_mass"] . "\"></span>
        </td>";
        
       // }
        
        $str .= "</tr>";
    
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
    
    // Note: Convention over Configuration here... the key names for the elements in the object passed from the AJAX call correspond to the column names in the database.
    
    //$arr = json_decode($_GET["arr"]);
    
    $json = $_GET["json"];        
    $arr = json_decode($json);
    
    //echo $arr;    
    
    $sample_id = $arr->sample_id;
    $metrc_number = $arr->metrc_number;
    
    if (strlen($metrc_number) > 0) {
    
    $x = $dbconn->query("select count(*) from tblsamples where metrc_number = '$metrc_number' and sample_id <> '$sample_id' and (active <> 'false' or active = '' or active is null)")->fetchColumn();
    if ($x > 0) {
        $sql1 ="select sample_id from tblsamples where metrc_number = '$metrc_number' and sample_id <> '$sample_id' and (active <> 'false' or active = '' or active is null)";
        $stmt1 = $dbconn->prepare($sql1);
        if ($stmt1->execute()) {
            while($row1 = $stmt1->fetch()) {
                $msid = $row1["sample_id"];
            }
        }   
        echo "Sorry, that METRC Number is Already in use by Sample ID: $msid.<br />";
        die();
    }
    }
    
    unset($arr->sample_id);
    unset($arr->business_name);
    
    $tests_to_perform = $arr->tests_to_perform;
    
    $arrteststoperform = json_decode($tests_to_perform);
    
    
    $sql = "delete from tblsamplesproductxref where sample_id = '$sample_id'";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
    
    foreach($arrteststoperform as $key=>$val) {
        
        $sql = "select id from tblproducts where productdescription = '$val'";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute();
        while($row = $stmt->fetch()) { 
        
            $dom_element_id = $key;       
            $product_id = $row['id'];
            
            $sql2 = "insert into tblsamplesproductxref (sample_id, product_id, dom_element_id) values ('$sample_id', '$product_id', '$dom_element_id')";
            $stmt2 = $dbconn->prepare($sql2);
            $stmt2->execute();
                       

        }    
    }  
    
    unset($arr->tests_to_perform);
           
    foreach ($arr as $key=>$val) {

        if ((strpos($key, '_workflow') !== false) || ($key == 'date_accepted')) {            
          
            $arr1[$key] = $val;
                   
            
            $ndatecolname = "n" . $key;
            
            $ndate = strtotime($val);
            
            $sql = "update tblsamples set $ndatecolname = :ndate where sample_id = :sample_id";
            $stmt = $dbconn->prepare($sql);
            $stmt->execute(array(':ndate'=>$ndate, ':sample_id'=>$sample_id));
        }
        
              
        $sql = "update tblsamples set $key = :value where sample_id = :sample_id";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':value'=>$val, ':sample_id'=>$sample_id));
        
        if ($key == 'date_accepted' && strlen($val) > 0) {
            logaction($key, $val, $sample_id, 'tblsamples', '');
        }
    }
    
   
    $completed = 'true';
    foreach ($arr1 as $key=>$val) {
        if (strlen($val) < 1) {
            $completed = null;
        }
       
        if (strpos($key, '_workflow') !== false) {
            if (substr($key, 0, 5) == "date_") { 
                if (strlen($val) > 0) {                                  
                    logaction($key, $val, $sample_id, 'tblsamples', '');
                }
            }
        }
        
        if ($key == 'date_report_approval_workflow') {
            $sql = "update tblreports set approved = '$val' where sampleid = :sample_id";
            $stmt = $dbconn->prepare($sql);
            $stmt->execute(array(':sample_id'=>$sample_id)); 
            
        }
    } 
        
    if ($completed == "true") {
        $sql = "update tblsamples set completed = 'true' where sample_id = :sample_id";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':sample_id'=>$sample_id));    
    }
    else
    {
        $sql = "update tblsamples set completed = null where sample_id = :sample_id";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':sample_id'=>$sample_id)); 
    }        
    
    
    $sample_count = $_GET["samplecount"];
    
    if ($sample_count > 0) {
        
        $client_id = $arr->client_id;
        $license_number = $arr->license_number;
        $date_accepted = $arr->date_accepted;
        $ndate_accepted = strtotime($date_accepted);
        
        $manifest_id = $arr->manifest_id;
        $rush_order = $arr->rush_order;
        
        for ($x = 2; $x <= $sample_count; $x++) {         
            
            $sql = "insert into tblsamples (client_id, license_number, date_accepted, ndate_accepted, manifest_id, rush_order) values (:client_id, :license_number, :date_accepted, :ndate_accepted, :manifest_id, :rush_order)";            
            $stmt = $dbconn->prepare($sql);
            $stmt->execute(array(':client_id'=>$client_id, ':license_number'=>$license_number, ':date_accepted'=>$date_accepted, ':ndate_accepted'=>$ndate_accepted, ':manifest_id'=>$manifest_id, ':rush_order'=>$rush_order));  
            $sample_id = $dbconn->lastInsertId();

            logaction('date_accepted', $aDateGlobal, $sample_id, 'tblsamples', '');            
                     
        }
        
    }
    
unset($_SESSION["showmanagesamplesorworkflowsampleid"]);
 
echo "success"; 

die();

}    
    
if ($qtype == "newsample") {
        
    $sql = "Select first_name, last_name, email from tblusers where user_id = :luid";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':luid'=>$_SESSION["luid"]))) {
        while($row = $stmt->fetch()) {          
            $created_by = $row["first_name"] . " " . $row["last_name"] . " <" . $row["email"] . ">";            
        }
    }
    
    $sql = "insert into tblsamples (created_by, date_created) values (:created_by, :date_created)";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':created_by'=>$created_by, ':date_created'=>$aDateTimeGlobal));
    $sample_id = $dbconn->lastInsertId();
    $arr["sample_id"] = $sample_id;
   
    echo json_encode($arr);
    
}

if ($qtype == "deletesample") {
    $sample_id = $_GET["sample_id"];
    $sql = "update tblsamples set active = 'false' where sample_id = :sample_id";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':sample_id'=>$sample_id));
    
    $sql = "update tblchangehistory set active = 'false' where sample_id = :sample_id";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':sample_id'=>$sample_id));
    die();
}

if ($qtype == "getsampledata") {
    
    $sample_id = $_GET["sample_id"];
    $arr = array();
    $total_sub_sample_mass = 0;
    $sql = "select * from tblsamples where sample_id = :sample_id";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':sample_id'=>$sample_id))) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);            
        foreach ($row as $key=>$val) {
            if (strpos($key, "sub_sample_mass") > -1 ) {
               $arrsubsamplemass[$key] = $val;
               continue;
            }
            else{
                $arr[$key] = $val;   
            }            
        }
    }
    
    $reports_html = "";
    $sql = "select id, filename, filepath from tblreports where sampleid = :sample_id";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':sample_id'=>$sample_id))) {
        while($row = $stmt->fetch()) {          
            //$filepath = $row["filepath"];
            $filename = $row["filename"];            
            //$reports_html .=  "<div style=\"padding:.5em;\"><a href=\"$filepath\" target=\"_blank\">$filename</a></div>";
            
            $report_id = $row["id"]; 
            $reports_html .=  "<div style=\"padding:.5em;\"><a href=\"showreport_internal.php?id=$report_id\" target=\"_blank\">$filename</a></div>";
        }
    }
    
    $arr["managesample_reports_div"] = $reports_html;
    
    $arrt1 = [];
    
    $sql2 = "select *, tblsamplesproductxref.dom_element_id as dom_element_id from tblsamplesproductxref inner join tblproducts on tblsamplesproductxref.product_id = tblproducts.id where tblsamplesproductxref.sample_id = :sample_id";
    $stmt2 = $dbconn->prepare($sql2);
    if ($stmt2->execute(array(':sample_id'=>$sample_id))) {
        while($row2 = $stmt2->fetch()) {  
            $arrt1[$row2["dom_element_id"]] = $row2["productdescription"];            
        }
    }
    
   
   
   $arr["tests_to_perform"] = json_encode($arrt1);
   
    $arr["used_mass"] = (($arrsubsamplemass["sub_sample_mass_cannabinoids"] + 0) + ($arrsubsamplemass["sub_sample_mass_residual_solvents"] + 0) + ($arrsubsamplemass["sub_sample_mass_terpenes"] + 0)); 

    $used_mass = $arr["used_mass"];
    $package_amount = $arr["package_amount"] + 0;        

            if ( strpos(strtolower($arr["product_type"]), "edible") > -1) 
            {
                if ($package_amount == 0) {
                    $package_amount = 1;
                }
                $unused_mass = ($package_amount - $used_mass); 
                $unused_mass = $unused_mass / $package_amount;
                $arr["unused_mass"] = round($unused_mass * 100, 2) . "%";
                $arr["used_mass"] = round(((1 - $unused_mass) * 100), 2) . "%";
           
           }
            elseif (strtolower($arr["product_type"]) == "flower") 
            {
                $weighboat = 0;
                $sql = "select cvalue from tblconstants where cname = 'weighboat'";
                $stmt = $dbconn->prepare($sql);    
                if ($stmt->execute()) {
                    while($row = $stmt->fetch()) {
                        $weighboat = $row["cvalue"] + 0;
                    }
                }               
                $arr["used_mass"] = ((($arr["wet_mass"] + 0) - $weighboat) - $used_mass);                
                $arr["unused_mass"] = $arr["sample_mass"] - $arr["used_mass"];   
            }
            else
            {
                $arr["unused_mass"] = (($arr["sample_mass"] + 0) - $arr["used_mass"]); 
            }
   
   
    
    echo json_encode($arr);
   
    die();
}

if ($qtype == "clientdropdown") {
        
    $arr = array();
    
    $sample_id = $_GET["sample_id"];  
    
    if (strlen($sample_id) > 0) {
        
        $sql1 = "select client_id from tblsamples where sample_id = '$sample_id'";
               
        $stmt1 = $dbconn->prepare($sql1);
        if ($stmt1->execute()) {
            while($row1 = $stmt1->fetch()) {                
                if (strlen($row1["client_id"]) > 0) {
                    $sql2 = "select tblclients.business_name as business_name, tblclients.client_id as client_id from tblclients inner join tblsamples on tblclients.client_id=tblsamples.client_id where tblsamples.sample_id = '$sample_id'";
                    $stmt2 = $dbconn->prepare($sql2);
                    if ($stmt2->execute()) {
                        while($row2 = $stmt2->fetch()) {
                            $arr[$row2["client_id"]] = $row2["business_name"];           
                        }
                    } 
                }
            }
        }                
    }
    
    if (empty($arr)) {
        $sql = "select business_name, client_id from tblclients where active is null or active = '' or active <> 'false' order by business_name asc";  
        $stmt = $dbconn->prepare($sql);
        if ($stmt->execute()) {
            while($row = $stmt->fetch()) {
                $arr[$row["client_id"]] = $row["business_name"];           
            }
        }
    }        
    
    
    
  /*
    $arr2 = [];
     
    foreach ($arr as $key => $row)
    {
        $arr2[$key] = $row;
    }
    array_multisort($arr2, SORT_ASC, $arr);
    */
    
    echo json_encode($arr); 
        
    die();
    
}

if ($qtype == "licensedropdown") {
    
    $arr = array();
    
    $client_id = $_GET["client_id"];
    
    $sql = "select id, license_number from tbllicenses where (active is null or active = '' or active <> 'false') and client_id = '$client_id' order by license_number asc";   
   
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while($row = $stmt->fetch()) {
            $arr[$row["license_number"]] = $row["license_number"];           
        }
    }    
    echo json_encode($arr); 
        
    die();
    
}

if ($qtype == "producttypelist") {
    
    $arr = array();
    
    $sql = "select display_text from tbldropdowns where type = 'producttype' order by display_text asc";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while($row = $stmt->fetch()) {
            
            $arr[$row["display_text"]] = $row["display_text"];           
        }
    }   
    
    echo json_encode($arr); 
        
    die();    
    
}

if ($qtype == "linkimage") {
    
    $filepath = "uploads/" . $_GET["sample_id"] . "/" .  $_GET["filename"];
    
    $sql = "update tblsamples set sample_image_path = :filepath";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':filepath'=>$filepath));
    
}

if ($qtype == "navigate") {
    
    $sample_id = $_GET["sample_id"];
    $direction = $_GET["direction"];
    $completed = $_GET["completed"];
    $str = "";
    $completedsql = "";
    
    if ($completed == "true") {
        $completedsql = "completed = 'true'";        
    }
    
    if ($completed == "false") {
        $completedsql = "completed is null or completed = '' or completed <> 'true'";        
    }
    
    if ($direction == "next") {    
        $sql = "select sample_id from tblsamples where sample_id = (select min(sample_id) from tblsamples where sample_id > :sample_id and (active is null or active = '' or active <> 'false') and ($completedsql))";
    }

    if ($direction == "previous") {
        $sql = "select sample_id from tblsamples where sample_id = (select max(sample_id) from tblsamples where sample_id < :sample_id and (active is null or active = '' or active <> 'false') and ($completedsql))";
    }
    
    //echo $sql;
    
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':sample_id'=>$sample_id))) {
        while($row = $stmt->fetch()) {        
            $str = $row["sample_id"];
        }
    }            
    
    echo $str;
    
}


if ($qtype == 'updateflowermass') {
    
    $wetordry = $_GET["wetordry"];
    $sampleid = $_GET["sampleid"];
    $val = $_GET["val"];
    
    $sql = "update tblsamples set $wetordry = :val where sample_id = :sampleid";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array('val'=>$val, ':sampleid'=>$sampleid));
    
};


// This one's weird... on the Workflow frame, if you click a sample, it takes you to the Edit Sample view on the Manage Samples frame. This sets the variable that lets the system know which frame to show when clicking done. 
if ($qtype == 'setmanagesamplesorworkflow') {
    
    $sampleid = "";
    if (isset($_GET["sampleid"])) {
    $sampleid = $_GET["sampleid"];
        if (strlen($sampleid) > 0) {
            $_SESSION["showmanagesamplesorworkflowsampleid"] = $sampleid;
        }
        else
        {
            unset($_SESSION["showmanagesamplesorworkflowsampleid"]);
        } 
    }        
}


?>
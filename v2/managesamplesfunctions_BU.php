<?php 

include "includes.php";

$qtype = $_GET["qtype"];

$str = "";
$arr = array();
$arr1 = array();

if ($qtype == "samplelist") {
    
    $searchby = $_GET["sb"];
    $searchfor = $_GET["sf"];    
    $sqlstr = " and $searchby like '%$searchfor%' ";

    if ($searchby == "all" || strlen($searchby) < 1) {
        $sqlstr = "";
    }
    
    $teststoperform = "";
    
    $sql = "select * from tblsamples where (active is null or active = '' or active <> 'false') $sqlstr order by sample_id desc";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while($row = $stmt->fetch()) {          
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
            
              
            $teststoperform = $row["tests_to_perform"];
              
            $arr1 = json_decode($teststoperform, true);   

            $tests = "";            
            
            foreach ($arr1 as $key1=>$val1) {
                $tests .= $val1 . ", ";
            }
           
            
            $arr[$id]["tests_to_perform"] = rtrim($tests, ", ");
            
        }
    }
    
    $str = "";
       
    foreach ($arr as $key=>$val) {
        
        $str .= "<tr onclick=\"editsample('" . $key . "')\">
        <td>
        <span>$key</span>
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
        <span title=\"" .  $val["tests_to_perform"] . "\" readonly>" . $val["tests_to_perform"] .  "</span>
        </td>
        <td>
        <span title=\"" .  $val["rush_order"] . "\">" . $val["rush_order"] . "</span>
        </td>
        </tr>";
    
    }
    
    $arr1["tds"] = $str;

    $str = json_encode($arr1);
    
    echo $str;
        
}

if ($qtype == "updatesample") {
    
    // Note: Convention over Configuration here... the key names for the elements in the object passed from the AJAX call correspond to the column names in the database.
    
    //$arr = json_decode($_GET["arr"]);
    
    $json = $_GET["json"];
        
    $arr = json_decode($json);
    
    //echo $arr;
    
    $sample_id = $arr->sample_id;
    
    unset($arr->sample_id);
    unset($arr->business_name);
       
    foreach ($arr as $key=>$val) {        
        $sql = "update tblsamples set $key = :value where sample_id = :sample_id";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':value'=>$val, ':sample_id'=>$sample_id));
       
    }      
die();

}    
    
if ($qtype == "newsample") {
    
    $sql = "insert into tblsamples (notes) value (null)";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
    $sample_id = $dbconn->lastInsertId();
    $arr["sample_id"] = $sample_id;
    echo json_encode($arr);
    
}

if ($qtype == "deletesample") {
    $sample_id = $_GET["sample_id"];
    $sql = "update tblsamples set active = 'false' where sample_id = :sample_id";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':sample_id'=>$sample_id));
    die();
}

if ($qtype == "getsampledata") {
    
    $sample_id = $_GET["sample_id"];
    $arr = array();
    
    $sql = "select * from tblsamples where sample_id = :sample_id";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':sample_id'=>$sample_id))) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);            
        foreach ($row as $key=>$val) {
            $arr[$key] = $val;            
        }
    }
    
    echo json_encode($arr);
   
    die();
}

if ($qtype == "clientdropdown") {
        
    $arr = array();
    
    $sample_id = $_GET["sample_id"];
        
    $sql = "select business_name, client_id from tblclients where active is null or active = '' or active <> 'false' order by business_name asc";  
    
    if (strlen($sample_id) > 0) {
        
        $sql1 = "select client_id from tblsamples where sample_id = $sample_id";
        $stmt1 = $dbconn->prepare($sql1);
        if ($stmt1->execute()) {
            while($row1 = $stmt1->fetch()) {                
                if (strlen($row1["client_id"]) > 0) {
                    $sql = "select tblclients.business_name, tblclients.client_id from tblclients inner join tblsamples on tblclients.client_id=tblsamples.client_id where tblsamples.sample_id = '$sample_id'";
                }
            }
        }                
    }
   
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while($row = $stmt->fetch()) {
            $arr[$row["client_id"]] = $row["business_name"];           
        }
    } 

    
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

?>
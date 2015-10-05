<?php 

include "includes.php";

logincheck();

$qtype = $_GET["qtype"];

$str = "";
$arr = array();
$arr1 = array();

if ($qtype == "clientlist") {
    
    $searchby = $_GET["sb"];
    $searchfor = $_GET["sf"];    
    $sqlstr = " and $searchby like '%$searchfor%' ";

    if ($searchby == "all" || strlen($searchby) < 1) {
        $sqlstr = "";
    }
        
    $sql = "select * from tblclients where (active is null or active = '' or active <> 'false') $sqlstr order by business_name asc";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
            $id = $row["client_id"];
            $arr[$id]["business_name"] = $row["business_name"];
            $arr[$id]["business_address1"] = $row["business_address1"];
            $arr[$id]["business_address2"] = $row["business_address2"];
            $arr[$id]["business_city"] = $row["business_city"];
            $arr[$id]["business_state"] = $row["business_state"];
            $arr[$id]["business_zip"] = $row["business_zip"];            
            $business_address = $row["business_address1"];
            if (strlen($row["business_address2"] > 0)) {
                $business_address .= " " . $row["business_address2"];
            }
            $business_address .= "&nbsp;" . $row["business_city"] . " " . $row["business_state"] . " " .  $row["business_zip"]; 
            $arr[$id]["business_address"] = $business_address;
            $arr[$id]["email"] = $row["email"];
            $arr[$id]["business_phone"] = $row["business_phone"];
        }
    }
    
         
    foreach ($arr as $clientid=>$client) {
        
        $cls = "cls" . time() .  rand(10000000, 99999999);
        $options = "";
        
        $str .= "<tr onclick=\"editclient('$clientid')\" >               
        <td>
        <span>" . $client["business_name"] . "</span>
        </td>        
        <td>
        <span>" . $client["business_address"] . "</span>
        </td>
        <td>
        <span>" . $client["email"] . "</span>
        </td>
        <td>
        <span>" . $client["business_phone"] . "</span>
        </td>
        </tr>";
    }
    
    $arr["tds"] = $str;

    $str = json_encode($arr);
    
    echo $str;
    
    die();
        
}

if ($qtype == "updateclient") {
    
    // Note: Convention over Configuration here... the key names for the elements in the object passed from the AJAX call correspond to the column names in the database.
    
    //$arr = json_decode($_GET["arr"]);
    
    $json = $_GET["json"];
    
    $arr = json_decode($json);
    
    //echo $arr;
    
    $client_id = $arr->client_id;
    
    unset($arr->client_id);
   
    foreach ($arr as $key=>$val) {
        $sql = "update tblclients set $key = :value where client_id = :client_id";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':value'=>$val, ':client_id'=>$client_id));
       
    }

  
    die();    
}

if ($qtype == "newclient") {   
        $arr = array();
        
        $sql = "Select first_name, last_name, email from tblusers where user_id = :luid";
        $stmt = $dbconn->prepare($sql);
        if ($stmt->execute(array(':luid'=>$_SESSION["luid"]))) {
            while($row = $stmt->fetch()) {          
                $created_by = $row["first_name"] . " " . $row["last_name"] . " <" . $row["email"] . ">";            
            }
        }
    
        $sql = "insert into tblclients (created_by, date_created) values (:created_by, :date_created)";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':created_by'=>$created_by, ':date_created'=>$aDateTimeGlobal));
        $client_id = $dbconn->lastInsertId();
        $arr["client_id"] = $client_id;
        echo json_encode($arr);

}


if ($qtype == "deleteclient") {
    $client_id = $_GET["client_id"];
    $sql = "update tblclients set active = 'false' where client_id = :client_id";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':client_id'=>$client_id));
    $sql = "update tbllicenses set active = 'false' where client_id = :client_id";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':client_id'=>$client_id));
    die();
}

if ($qtype == "getclientdata") {
    $client_id = $_GET["client_id"];
    $arr = array();
    $sql = "select * from tblclients where client_id = :client_id";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':client_id'=>$client_id))) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);            
        foreach ($row as $key=>$val) {
            $arr[$key] = $val;            
        }
    }
    
    echo json_encode($arr);
   
    die();
    
  
}

if ($qtype == "getlicensedata") {
    
    $client_id = $_GET["client_id"];
    $arr = array();
    
    $sql = "select license_type, license_number, date_expiration, cannabinoids_price_per_test, residualsolvents_price_per_test, terpenes_price_per_test from tbllicenses where client_id = :client_id";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':client_id'=>$client_id))) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);            
        foreach ($row as $key=>$val) {
            $arr[$key] = $val;            
        }
    }
    
    echo json_encode($arr);
   
    die();
}

?>
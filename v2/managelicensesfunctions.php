<?php 

include "includes.php";

logincheck();

$qtype = $_GET["qtype"];

if ($qtype == "getlicenses") {
    
    $arr = array();

    $client_id = $_GET["client_id"];
    $str = "";

    $sql = "select * from tbllicenses where client_id = :client_id and (active is null or active = '' or active <> 'false')";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':client_id'=>$client_id))) {
        while ($row = $stmt->fetch()) {          
            $license_number = $row["license_number"];
            $license_type = $row["license_type"];
            $expiration_date = $row["date_expiration"];
            
            $str .= 
            "         
                <tr onclick=\"showlicense('$license_number')\">
                    <td>
                        <span title=\"$license_number\">$license_number</span>                
                    </td>
                    <td>
                        <span title=\"$license_type\">$license_type</span>                
                    </td>
                    <td>
                        <span title=\"$expiration_date\">$expiration_date</span>                
                    </td>
                    <td><button onclick=\"deletelicense('$license_number');\" style=\"height:2em:\">Del</button>
                </tr>";            
            
        }
    } 

    $arr["tds"] = $str;

    echo json_encode($arr);

}

if ($qtype == "addlicense") {
        
    $json = $_GET["json"];
       
    $arr = json_decode($json, true);
    
    $tests = $arr[0];    
    
    $client_id = $arr[1]['val'];
    $license_number = $arr[3]['val'];
    $license_type = $arr[2]['val'];
    $date_expiration = $arr[4]['val'];        
    $hide_total_potential_cannabinoids_on_reports = $arr[5]['val'];
    $license_image_path = $arr[6]['val'];
     
    $sql = "select count(*) from tbllicenses where license_number = '$license_number' and client_id <> '$client_id' and (active is null or active = '' or active <> 'false')";
    $x = $dbconn->query($sql)->fetchColumn();
        
    if ($x > 0) {        
        $sql = "select tblclients.business_name as business_name from tblclients inner join tbllicenses ON tblclients.client_id=tbllicenses.client_id where tbllicenses.license_number = :license_number and (tbllicenses.active is null or tbllicenses.active = '' or tbllicenses.active <> 'false')";
         $stmt = $dbconn->prepare($sql);
        if ($stmt->execute(array(':license_number'=>$license_number))) {
            while ($row = $stmt->fetch()) {
                $business_name = $row["business_name"];
            }
        }
        echo "This License Number is already assigned to $business_name.";       
        die();
    }  
    
    

    $sql = "select count(*) from tbllicenses where license_number = '$license_number'";
    $x = $dbconn->query($sql)->fetchColumn();
        
    if ($x > 0) {
        $sql = "update tbllicenses set license_type = :license_type, date_expiration = :date_expiration, hide_total_potential_cannabinoids_on_reports = :hide_total_potential_cannabinoids_on_reports, license_image_path = :license_image_path where license_number = :license_number";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':license_number'=>$license_number, ':license_type'=>$license_type, ':date_expiration'=>$date_expiration, ':hide_total_potential_cannabinoids_on_reports'=>$hide_total_potential_cannabinoids_on_reports, ':license_image_path'=>$license_image_path));
        
        $sql = "select id from tbllicenses where license_number = :license_number";
        $stmt = $dbconn->prepare($sql);
        if ($stmt->execute(array(':license_number'=>$license_number))) {
            $row = $stmt->fetch();            
            $license_id = $row["id"];
        }      
    }
    else
    {   
        $sql = "Select first_name, last_name, email from tblusers where user_id = :luid";
        $stmt = $dbconn->prepare($sql);
        if ($stmt->execute(array(':luid'=>$_SESSION["luid"]))) {
            while($row = $stmt->fetch()) {          
                $created_by = $row["first_name"] . " " . $row["last_name"] . " <" . $row["email"] . ">";            
            }
        }
        $sql = "insert into tbllicenses (license_number, license_type, date_expiration, hide_total_potential_cannabinoids_on_reports, client_id, license_image_path, created_by, date_created) values (:license_number, :license_type, :date_expiration, :hide_total_potential_cannabinoids_on_reports, :client_id, :license_image_path, :created_by, :date_created)";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':license_number'=>$license_number, ':license_type'=>$license_type, ':date_expiration'=>$date_expiration, ':hide_total_potential_cannabinoids_on_reports'=>$hide_total_potential_cannabinoids_on_reports, ':client_id'=>$client_id, ':license_image_path'=>$license_image_path, ':created_by'=>$created_by, ':date_created'=>$aDateTimeGlobal));
        
        $license_id = $dbconn->lastInsertId();    
    }    
         
    $sql = "delete from tbllicenseproductpricexref where license_id = :license_id";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':license_id'=>$license_id));
    
    foreach($tests as $test) {
        
        foreach($test as $key1=>$val1) {
     
            $product_id = $val1['product_id'];
            $price = $val1['price'];          
           
            $sql = "insert into tbllicenseproductpricexref (product_id, price, license_id) values (:product_id, :price, :license_id)";
            $stmt = $dbconn->prepare($sql);
            $stmt->execute(array(':product_id'=>$product_id, ':price'=>$price, ':license_id'=>$license_id));           
        }    
    }
        
    die();   
    
}

if ($qtype == "deletelicense") {
    $license_number = $_GET["license_number"];    
    $sql = "update tbllicenses set active = 'false' where license_number = :license_number";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':license_number'=>$license_number));
    
}

if ($qtype == "editlicense") {
    
    $license_number = "";
    if (isset($_GET["license_number"])) {
        $license_number = $_GET["license_number"];
    }
    $arr = array();
    $product_id = "";
    $license_id = "";
    
    if (strlen($license_number) > 0) {
    
    $sql = "select * from tbllicenses where license_number = :license_number";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':license_number'=>$license_number))) {
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
        foreach ($row as $key=>$val) {
            $arr[$key] = $val;
          
        }
    }   
    
    $license_id = $arr["id"];
    
    }
    
    $strtests = "";
    
    $sql = "select * from tblproducts order by product_order";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) { 
        
            $dom_id = $row["dom_element_id"];
            $desc = $row["productdescription"];
            $product_id = $row["id"];

            $strtests .="<label for=\"$dom_id\">$desc Cost Per Test</label>
            <input type=\"number\" skip=\"any\" id=\"$dom_id\" data-product_id=\"$product_id\" data-license_id=\"$license_id\" class=\"form-control pricingdata\"></input>
            <br />";            
           
        }
    }  
    
    $arr['strtests'] = $strtests; 
    $arr['license_id'] = $license_id;    
    
    echo json_encode($arr);
    
    die();
}

if ($qtype == 'getprices') {
    
    $license_id = $_GET["license_id"];
    $arrret = [];
    
    $sql = "select product_id, price from tbllicenseproductpricexref where license_id = :license_id";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':license_id'=>$license_id))) {
         while($row = $stmt->fetch()) { 
                  
            $product_id = $row["product_id"];
            $price = $row["price"];
            
            $arrret[$product_id] = $price;
                     
            
         }
        
    }
    
    echo json_encode($arrret);

    
}

?>
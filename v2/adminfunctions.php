<?php 

include "includes.php";

logincheck();

$calltype = "";

if (isset($_GET["calltype"])) {
    $calltype = $_GET["calltype"];
}

if (isset($_GET["searchfor"])) {
    $searchfor = $_GET["searchfor"];
}

if (isset($_GET["value"])) {
    $value = $_GET["value"];
}

if (isset($_GET["instrument"])) {
    $instrument = $_GET["instrument"];
}

$arr = array();
$arr2 = array();
      
if ($calltype == "allrfcompounds") {
    $sql = "select * from tblconstants where cname = 'responsefactor' order by ckey asc";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
            $compounds = $row["ckey"];
            $value = $row["cvalue"];
            $arr["val"] = "$compounds";
            $arr["text"] = "$compounds";
            $arr["value"] = "$value";                
            array_push($arr2, $arr);  
        }
        echo json_encode($arr2);
    }          
            
    die();
}

if ($calltype == "rfcompounds") {   
    $sql = "select * from tblconstants where cname = 'responsefactor' and ckey = '$searchfor'";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
            $value = $row["cvalue"];
            $arr["value"] = "$value";
        }
        echo json_encode($arr);
    }
    die();
}

if ($calltype == "updaterfcompound") {
    
    $sql = "delete from tblconstants where cname = 'responsefactor' and ckey = '$searchfor'";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
    
    $sql = "insert into tblconstants (cname, cvalue, ckey) values ('responsefactor', '$value', '$searchfor')";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
}
if ($calltype == "deleterfcompound") {
    
    $sql = "delete from tblconstants where cname = 'responsefactor' and ckey = '$searchfor'";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
}

if ($calltype == "getpthc") {   
    if (!isset($_GET["instrument"])) {
    die();
}
else
{  
    $instrument = $_GET["instrument"];
    $sql = "select * from tblconstants where cname = 'pthc' and instrument = :instrument";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':instrument'=>$instrument))) {
        while ($row = $stmt->fetch()) {
            $value = $row["cvalue"];
            $arr["value"] = "$value";
        }
        echo json_encode($arr);
    }
    die();
}
}

if ($calltype == "updatepthc") {
    
if (!isset($_GET["instrument"])) {
    die();
}
else
{  
    $instrument = $_GET["instrument"];
    $value = $_GET["value"];
    $sql = "delete  from tblconstants where cname = 'pthc' and instrument = :instrument";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':instrument'=>$instrument));
    
    $sql = "insert into tblconstants (cname, ckey, cvalue, instrument) value ('pthc', 'pthc', '$value', '$instrument')";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':value'=>$value, ':instrument'=>$instrument));
    
    die();
}
}

if ($calltype == "allslopecompounds") {
    $sql = "select * from tblconstants where cname = 'slope' and instrument = '$instrument' order by ckey asc";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
            $compounds = $row["ckey"];
            $value = $row["cvalue"];
            $arr["val"] = "$compounds";
            $arr["text"] = "$compounds";
            $arr["value"] = "$value";                
            array_push($arr2, $arr);  
        }
        echo json_encode($arr2);
    }          
            
    die();
}
if ($calltype == "slopecompounds") { 
    $sql = "select * from tblconstants where cname = 'slope' and instrument = '$instrument' and ckey = '$searchfor'";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
            $value = $row["cvalue"];
            $arr["value"] = "$value";
        }
        echo json_encode($arr);
    }
    die();
}

if ($calltype == "updateslopecompound") {
    
    $sql = "delete from tblconstants where cname = 'slope' and instrument = '$instrument' and ckey = '$searchfor'";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
    
    $sql = "insert into tblconstants (cname, cvalue, ckey, instrument) values ('slope', '$value', '$searchfor', '$instrument')";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
}
if ($calltype == "deleteslopecompound") {    
    $sql = "delete from tblconstants where cname = 'slope' and instrument = '$instrument' and ckey = '$searchfor'";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
}

if ($calltype == "getdilutionvalue") { 
    $sql = "select * from tblconstants where cname = 'dilution' and ckey = '$searchfor'";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
            $value = $row["cvalue"];
            $arr["value"] = "$value";
        }
        echo json_encode($arr);
    }
    die();
}
if ($calltype == "updatedilution") {
    
    $sql = "delete from tblconstants where cname = 'dilution' and ckey = '$searchfor'";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
    
    $sql = "insert into tblconstants (cname, cvalue, ckey) values ('dilution', '$value', '$searchfor')";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
}

if ($calltype == "weighboat") {   
    $sql = "select * from tblconstants where cname = 'weighboat' and ckey = 'weighboat'";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
            $value = $row["cvalue"];
            $arr["value"] = "$value";
        }
        echo json_encode($arr);
    }
    die();
}

if ($calltype == "updateweighboat") {
    
    $sql = "delete  from tblconstants where cname = 'weighboat'";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
    
    $sql = "insert into tblconstants (cname, ckey, cvalue) value ('weighboat', 'weighboat', '$value')";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
}

if ($calltype == "getusers") {
    $arr = array();
    $sql = "select * from tblusers where (active is null or active = '' or active <> 'false') order by first_name, last_name asc";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
            $user_name = $row["user_name"];
            $userid = $row["user_id"];
            $arr["$userid"]["user_name"] = $user_name;
        }        
    }
    
    echo json_encode($arr);
}

if ($calltype == "getuserdata") {
    
    $arr = array();
    
    $user_id = $_GET["user_id"];
    
    $sql = "select * from tblusers where user_id = :user_id";
     $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':user_id'=>$user_id))) {
        while ($row = $stmt->fetch()) {
            $first_name = $row["first_name"];
            $last_name = $row["last_name"];
            $email = $row["email"];
            $role = $row["role"];
            $password = $row["password"];             
            $arr["userfirstname"] = DeXSS($first_name);
            $arr["userlastname"] = DeXSS($last_name);
            $arr["useremail"] = DeXSS($email);
            $arr["userrole"] = $role;
            $arr["userpassword"] = $password;
        }       
    }    
    
    echo json_encode($arr);
    
    die();
}

if ($calltype == "updateuser") {
        
    $arr = array();
    
    $user_id = $_GET["user_id"];
    $user_name = $_GET["user_name"];
    $first_name = $_GET["first_name"];
    $last_name = $_GET["last_name"];
    $email = $_GET["email"];
    $role = $_GET["role"];
    $pw = $_GET["password"];
    $password = "";
    
    if (strlen($pw) > 0) {    
        $password = md5($pw . $salt);
    }
        
    $x = $dbconn->query("select count(*) from tblusers where email = '$email' and user_id <> '$user_id' and (active <> 'false' or active = '' or active is null)")->fetchColumn();
    if ($x > 0) {
        echo "Sorry, that Email Address is Already In Use.";
        die();
    }
    
    if (strlen($user_id) < 1) {
        $sql = "insert into tblusers (_placeholder) value ('')";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute();
        $user_id = $dbconn->lastInsertId();
    }
    
    $sql = "update tblusers set user_name = :user_name, first_name = :first_name, last_name = :last_name, email = :email, role = :role where user_id = :user_id";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':user_id'=>$user_id, ':user_name'=>$user_name, ':first_name'=>$first_name, ':last_name'=>$last_name, ':email'=>$email, ':role'=>$role));  

    if (strlen($password) > 0) {
        $sql = "update tblusers set password = :password where user_id = :user_id";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':password'=>$password, ':user_id'=>$user_id)); 
    }
    
    echo $user_id;
    
    die();
}

if ($calltype == "deleteuser") {
    
    $user_id = $_GET["user_id"];
    
    $sql = "update tblusers set active = 'false' where user_id = :user_id";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':user_id'=>$user_id));    
}

if ($calltype == "reportsviews") {
     
    $reports_html = "";
    $searchby = $_GET["searchby"];
    $searchfor = $_GET["searchfor"];
    
    $sql = "select sample_id, date_cannabinoids_report_view, date_residualsolvents_report_view, date_terpenes_report_view from tblsamples where $searchby like ? and ((date_cannabinoids_report_view is not null and date_cannabinoids_report_view <> '') or (date_residualsolvents_report_view is not null and date_residualsolvents_report_view <> '') or (date_cannabinoids_report_view is null or date_cannabinoids_report_view <> '')) and (active = '' or active is null or active <> 'false') order by sample_id desc limit 1000"; //group by sample_id";
    
    $stmt = $dbconn->prepare($sql);    
     if ($stmt->execute(array("%$searchfor%"))) {
        while ($row = $stmt->fetch()) {
            
            $sample_id = $row["sample_id"];
            $nc = 0;
            $c = $row["date_cannabinoids_report_view"];
            if (strlen($c) > 0) {
                $nc = strtotime($c);
            }
            $nr = 0;
            $r = $row["date_residualsolvents_report_view"];
            if (strlen($r) > 0) {
                $nr = strtotime($r);
            } 
            $nt = 0;
            $t = $row["date_cannabinoids_report_view"];
            if (strlen($t) > 0) {
                $nt = strtotime($t);
            } 
            
            if (($nc + $nr + $nt) > 0) {            
                $reports_html .= "<tr><td>" . $row["sample_id"] . "</td><td>$c</td><td>$r</td><td>$t</td></tr>";
            }           
        }
    }
   echo $reports_html;
  
    
}


//$calltype = "samplelist";

if ($calltype == "samplelist") {
    
    $searchby = "all";
    $searchfor = "";
    
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
       
    $page = 2;   
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    }
    
    $limit = 100;
    if (isset($_GET["limit"])) {
        $limit = $_GET["limit"];
    }
    
    //$sqlstr = " and sample_id = '205147' ";
    //$page = 1;
    //$limit = 4690;
    
    
    $rows = json_decode(getpagination('tblsamples', 'sample_id desc', "$sqlstr", "$calltype", $page, 'admin.php', $limit), true);
        
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
            //$record_id = $row["id"]; 

            $arr2 = []; 
            $arr3 = array();           

            $sql3 = "select product_name, storage_location, client_id, sub_sample_mass_cannabinoids, sub_sample_mass_residual_solvents, sub_sample_mass_terpenes, package_amount, product_type, sample_mass, wet_mass from tblsamples where sample_id = '$id'";
            $stmt3 = $dbconn->prepare($sql3);    
            if ($stmt3->execute()) {
                while ($row3 = $stmt3->fetch()) {
                    $arr[$id]["product_name"] = $row3["product_name"];
                    $arr[$id]["storage_location"] = $row3["storage_location"];
                    $client_id = $row3["client_id"];
                    $arr[$id]["used_mass"] = (($row3["sub_sample_mass_cannabinoids"] + 0) + ($row3["sub_sample_mass_residual_solvents"] + 0) + ($row3["sub_sample_mass_terpenes"] + 0));
                    $package_amount = $row3["package_amount"] + 0; 
                    $product_type = $row3["product_type"];
                    $sample_mass = $row3["sample_mass"];
                    $wet_mass = $row3["wet_mass"];
                }
            } 
            $used_mass =  $arr[$id]["used_mass"];
            
            if ( strpos(strtolower($product_type), "edible") > -1) 
            {
                if ($package_amount == 0) {
                    $package_amount = 1;
                }
                                 
                $unused_mass = ($package_amount - $used_mass); 
                $unused_mass = $unused_mass / $package_amount;
                $arr[$id]["unused_mass"] = round($unused_mass * 100, 2) . "%";
                $arr[$id]["used_mass"] = round(((1 - $unused_mass) * 100), 2) . "%";
                               
           }
            elseif (strtolower($product_type) == "flower") 
            {
                $weighboat = 0;
                $sql = "select cvalue from tblconstants where cname = 'weighboat'";
                $stmt = $dbconn->prepare($sql);    
                if ($stmt->execute()) {
                    while($row = $stmt->fetch()) {
                        $weighboat = $row["cvalue"] + 0;
                    }
                }               
                $arr[$id]["used_mass"] = ((($wet_mass + 0) - $weighboat) - $used_mass);                
                $arr[$id]["unused_mass"] = $sample_mass - $arr[$id]["used_mass"];         
            }
            else
            {
                $arr[$id]["unused_mass"] = (($sample_mass + 0) - $arr[$id]["used_mass"]); 
            }  

            $sql1 = "select * from tblchangehistory where sample_id = '$id'";
                                   
            $stmt1 = $dbconn->prepare($sql1);    
            if ($stmt1->execute()) {            
                while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
               
                $arr2[$row1['sample_id']] = $row1; 

                array_push($arr3, $arr2);
                
                $arr2 = [];

                }           
                
                foreach ($arr3 as $key3=>$val3) {                   
                    
                    foreach($val3 as $key4=>$val4) {
                        
                        $ccsample_id = $val4['sample_id'];
                        $field_name = $val4['field_name'];
                        $value = $val4['value'];

                        $user_id = $val4["user_id"];
                        $sql2 = "select first_name, last_name from tblusers where user_id = '$user_id'";
                        $stmt2 = $dbconn->prepare($sql2);    
                        if ($stmt2->execute()) {            
                            $row2= $stmt2->fetch();                            
                            $fullname = $row2["first_name"] . " " . $row2["last_name"];
                        }
                
                        $arr[$ccsample_id][$field_name] = $value . "<br />" . $fullname;
                                                                       
                    }

                    $val3 = [];                    
                    
                }
                
                $arr3 = [];
                
            }      
                       
        }
        
        
    $str = "";
       
    foreach ($arr as $key=>$val) {
        
        if (!isset($val["product_name"])) {
            $val["product_name"] = "";
        }

        if (!isset($val["storage_location"])) {
            $val["storage_location"] = "";
        }
        
        if (!isset($val["used_mass"])) {
            $val["used_mass"] = "";
        }
        
        if (!isset($val["unused_mass"])) {
            $val["unused_mass"] = "";
        }
        
        if (!isset($val["date_accepted"])){
            $val["date_accepted"] = "";
        }
        
        if (!isset($val["date_test_completion_workflow"])) {
            $val["date_test_completion_workflow"] = "";
        }
        
        if (!isset($val["date_report_approval_workflow"])) {
            $val["date_report_approval_workflow"] = "";
        }
        
        if (!isset($val["date_report_generation_workflow"])) {
            $val["date_report_generation_workflow"] = "";
        }
        
        if (!isset($val["date_data_input_into_metrc_workflow"])) {
            $val["date_data_input_into_metrc_workflow"] = "";
        }
               
        
        $str .= "<tr onclick=\"editsample('" . $key . "')\">
        <td>
        <span class=\"record_id\">$key</span>
        </td>        
        <td>
        <span title=\"" . $val["product_name"] . "\">" . $val["product_name"] . "</span>
        </td>        
        <td>       
        <span title=\"" . $val["storage_location"] . "\">" . $val["storage_location"] . "</span>
        </td>
        <td>
        <span title=\"" . $val["used_mass"] . "\">" . $val["used_mass"] . "</span>
        </td>
        <td>
        <span title=\"" . $val["unused_mass"] . "\">" . $val["unused_mass"] . "</span>
        </td>
        <td>
        <span title=\"" .  $val["date_accepted"] . "\">" . $val["date_accepted"] .  "</span>
        </td>
        <td>
        <span title=\"" .  $val["date_test_completion_workflow"] . "\">" . $val["date_test_completion_workflow"] . "</span>
        </td>
        <td>
        <span title=\"" .  $val["date_report_generation_workflow"] . "\">" . $val["date_report_generation_workflow"] . "</span>
        </td>
        <td>
        <span title=\"" .  $val["date_report_approval_workflow"] . "\">" . $val["date_report_approval_workflow"] . "</span>
        </td>
        <td>
        <span title=\"" .  $val["date_data_input_into_metrc_workflow"] . "\">" . $val["date_data_input_into_metrc_workflow"] . "</span>
        </td>
        </tr>";
    }
    
    $arr1["tds"] = $str;
    $arr1["pagination"] = $pagination;
    $arr1["p_pagination"] = $p_pagination;
    $arr1["limithtml"] = $limithtml;
    
    $str = json_encode($arr1);
    
    echo $str;
        
}

if ($calltype == "getcustomerportaldropdown") {

    $sql = "select tbllicenses.license_number as license_number, tblclients.business_name as business_name from tbllicenses inner join tblclients on tbllicenses.client_id = tblclients.client_id order by tblclients.business_name asc";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
            $license_number = $row["license_number"];            
            $business_name = $row["business_name"];
            $arr["text"] = "$business_name $license_number";
            $arr["val"] = "$license_number";                
            array_push($arr2, $arr);  
        }
        echo json_encode($arr2);
    }          
            
    die();
   
}

if ($calltype == "adminportallogin") {
    
    if (isset($_GET["license_number"])) {
        $_SESSION["cluid"] = $_GET["license_number"];
    }   
    
}


?>
<?php 

include "includes.php";


$password = md5('Cannasys1' . $salt);

$sql = "update tbllicenses set password = '$password'";
$stmt = $dbconn->prepare($sql);
//$stmt->execute();


//die();

$arrbusinessnames1 = array("Canna", "MJ ", "Vita", "Compassionate ", "Green ", "Ganja ", "Kine", "Recreational", "Market");
$arrbusinessnames2 = array("Finest", "Joint", "Providers", "Care", "Medicines", "Alternatives", "Merchants", "Buds");
$arrbcheck = array();
$sql = "select * from tblclients";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while($row = $stmt->fetch()) {
        
        $client_id = $row["client_id"];
        
        $business_name = $arrbusinessnames1[array_rand($arrbusinessnames1)] . " " . $arrbusinessnames2[array_rand($arrbusinessnames2)];
       if (recursive($business_name, $arrbcheck)) {
        $sql1 = "update tblclients set business_name = '$business_name' where client_id = '$client_id'";
        $stmt1 = $dbconn->prepare($sql1);
       // $stmt1->execute(); 
       
       array_push($business_name, $arrbcheck);
       
       echo $business_name;
       
       }
       

    //echo "<br />" . $sql1 . "<br />";       
      
    }
}


function recursive($business_name, $arr) {
    
      if (!in_array($business_name, $arr)) {
          return;          
      }
      else
      {
         recursive($business_name, $arr);
      }
    
} 

die();


$arrproductnames1 = array("Canna", "King", "Grape", "Purple", "Durbin", "Sour", "Kine", "Cherry", "Colorado");
$arrproductnames2 = array("Kush", "Diesel", "Bud", "Poison", "Joker", "Dap", "Vape", "Trim");

$sql = "select * from tblsamples";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while($row = $stmt->fetch()) {
        $sample_id = $row["sample_id"];
        $product_name = $arrproductnames1[array_rand($arrproductnames1)] . " " . $arrproductnames2[array_rand($arrproductnames2)];
        $sql1 = "update tblsamples set product_name = '$product_name' where sample_id = '$sample_id'";
        $stmt1 = $dbconn->prepare($sql1);
       // $stmt1->execute(); 

    echo "<br />" . $sql1 . "<br />";       
      
    }
} 
   
   die();

$arrnames = array("Mike Jones", "Jon Smith", "Chad Jones", "Brandon Wellington", "Michael Jeffries", "Gina Torres" , "Fred Anderson", "Mary Smith", "Joe Johnson");
$sql = "select * from tblusers";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while($row = $stmt->fetch()) {
        $user_id = $row["user_id"];
        $user_name = $arrnames[array_rand($arrnames)];
        
        echo $user_name;
        
        $arrname1 = explode(" ", $user_name);
        $firstname = $arrname1[0];
        $lastname = $arrname1[1];
        
        $uname = strtolower($firstname[0] . strtolower($lastname));
        
        $sql1 = "update tblusers set first_name = '$firstname', last_name = '$lastname', user_name = '$uname' where user_id = '$user_id'";
        $stmt1 = $dbconn->prepare($sql1);
       //$stmt1->execute(); 

    echo "<br />" . $sql1 . "<br />";       
      
    }
}

die();
$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
$result = '';

$sql = "select * from tblusers";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while($row = $stmt->fetch()) {
        
    $user_id = $row["user_id"];
    
    $i = 0;
    for ($i = 0; $i < 8; $i++) {
        $result .= $characters[mt_rand(0, 35)];
    }
    
    $sql1 = "update tblusers set email = '$result" . "@gmail.com' where user_id = '$user_id'";
    $stmt1 = $dbconn->prepare($sql1);
    //$stmt1->execute();
    
    echo "<br />" . $sql1 . "<br />";
    
    $result = "";
        
    }
}


        
die();

$csvFile = file("41 addresses.txt");

$data = [];

// Get csv rows
foreach ($csvFile as $line) {
    $data[] = str_getcsv($line);
}

$arr2 = array();

foreach ($data as $str) {    
    $address = $str[0];
    $arr = explode("::", $address);
    $address1 = $arr[0];
    
    $arr1 = explode(";", $arr[1]);
    
    $city = $arr1[0];    
    $state = $arr1[1];
    $zip = $arr1[2];
    
    $arr = array("$address1", "$city", "$state", "$zip");
    
    array_push($arr2, $arr);
    
   
    
}

$counter = 0;

$sql = "select * from tblclients";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while($row = $stmt->fetch()) {
        $client_id = $row["client_id"];
        $sql1 = "update tblclients set business_address1 = :a1, business_city = :c, business_state = :s, business_zip = :z where client_id = :client_id";
        $stmt1 = $dbconn->prepare($sql1);
        //$stmt1->execute(array(':a1'=>$arr2[$counter][0], ':c'=>$arr2[$counter][1], ':s'=>$arr2[$counter][2], ':z'=>$arr2[$counter][3], ':client_id'=>$client_id));  
        $counter = $counter + 1; 

 echo "<br />" . $sql1 . "<br />";        
       
    }
}

$arrlicense = array();

$arrmetrc = array();

$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
$result = '';


$sql = "select * from tblclients";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while($row = $stmt->fetch()) {
        
    $client_id = $row["client_id"];
    
    $i = 0;
    for ($i = 0; $i < 8; $i++) {
        $result .= $characters[mt_rand(0, 35)];
    }
    
    $sql1 = "update tblclients set email = '$result" . "@gmail.com' where client_id = '$client_id'";
    $stmt1 = $dbconn->prepare($sql1);
    //$stmt1->execute();
    
    echo "<br />" . $sql1 . "<br />";
    
    $result = "";
        
    }
}



$characters = '0123456789RM';
$result = '';

$sql = "select * from tblsamples";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while($row = $stmt->fetch()) {
        
    $sample_id = $row["sample_id"];
    
    $i = 0;
    for ($i = 0; $i < 12; $i++) {
        $result .= $characters[mt_rand(0, 12)];
    }
    
    $sql1 = "update tblsamples set metrc_number = '$result' where sample_id = '$sample_id'";
    $stmt1 = $dbconn->prepare($sql1);
    //$stmt1->execute();
    
    echo "<br />" . $sql1 . "<br />";
    
    $result = "";
        
   
    }
}

$sql = "select * from tbllicenses";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while($row = $stmt->fetch()) {
        
    $license_id = $row["id"];
    $old_license_number = $row["license_number"];
    
    $i = 0;
    for ($i = 0; $i < 3; $i++) {
        $result1 .= $characters[mt_rand(0, 12)];
    }
    
    $i = 0;
    for ($i = 0; $i < 6; $i++) {
        $result2 .= $characters[mt_rand(0, 12)];
        
        $newlicensenumber = $result1 . "-" . $result2;
    }
    
    $sql1 = "update tbllicenses set license_number = '$newlicensenumber' where license_number = '$old_license_number'";   
    $stmt1 = $dbconn->prepare($sql1);   
    //$stmt1->execute();
    echo "<br />" . $sql1 . "<br />";
    
    $sql1 = "update tblsamples set license_number = '$newlicensenumber' where license_number = '$old_license_number'";   
    $stmt1 = $dbconn->prepare($sql1);
    //$stmt1->execute();
    
    echo $sql1 . "<br />";
     $sql1 = "update tblreports set licensenumber = '$newlicensenumber' where licensenumber = '$old_license_number'";   
    $stmt1 = $dbconn->prepare($sql1);
    //$stmt1->execute();
    echo $sql1 . "<br />";
    
    $result1 = "";
    $result2 = "";
            
   
    }
}


?>
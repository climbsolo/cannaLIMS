<?php

include "includes.php";




$sql = "select tests_to_perform, sample_id from tblsamples where sample_id > '206303'";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {
       
       $json = $row["tests_to_perform"];
       $sample_id = $row["sample_id"];
       $product_ids = array();
       
      $arr = json_decode($json);
      
      foreach ($arr as $key=>$val) {
          
          $dom_element_id = $key;        
          
          $sql1 = "select id from tblproducts where productdescription = '$val'";
          
          //echo $val . "<br />";
          $stmt1 = $dbconn->prepare($sql1);
            if ($stmt1->execute()) {
                while ($row1 = $stmt1->fetch()) {
                    
                    array_push($product_ids, $row1["id"]);
                    
                }
            }
            
            foreach ($product_ids as $product_id) {
                $sql2 = "insert into tblsamplesproductxref (sample_id, product_id, dom_element_id) values ('$sample_id', '$product_id', '$dom_element_id')";
                
                 echo $sql2 . "<br />";
                 
                $stmt2 = $dbconn->prepare($sql2);
               // $stmt2->execute();
                
                
               
                
                
            }
            
            $product_ids = array();
            
            
          
      }
       
       
       
    }
}  

    


?>


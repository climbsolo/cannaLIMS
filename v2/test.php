<?php

include "includes.php";

echo phpinfo();


/*


 $sql2 = "select * from tblsamplesproductxref inner join tblproducts on tblsamplesproductxref.product_id = tblproducts.id";
            $stmt2 = $dbconn->prepare($sql2);
            if ($stmt2->execute()) {
                while($row2 = $stmt2->fetch()) {
                    $productdescription = $row2["productdescription"];
                    
                    echo "$productdescription<br />";
                }
            }    



            */
/*

$sql = "select * from tblcannabinoids limit 1 UNION ALL select * from tblresidualsolvents limit 1 UNION ALL select * from tblterpenes limit 1";

$stmt = $dbconn->prepare($sql);

if ($stmt->execute()) {
   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   ?>
   <pre>
   <?php print_r($rows) ?>
   </pre>
   <?php
}
*/

?>
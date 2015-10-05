<?php

include "includes.php";


$sql2 = "select sample_id, ndate_report_approval_workflow, date_report_approval_workflow from tblsamples where ndate_report_approval_workflow like '144374052031446800%'";
$stmt2 = $dbconn->prepare($sql2);
if ($stmt2->execute()) {
    while($row2 = $stmt2->fetch()) {
        $ndate_report_approval_workflow = $row2["ndate_report_approval_workflow"];
        $date_report_approval_workflow = $row2["date_report_approval_workflow"];
        $sample_id = $row2["sample_id"];
        echo "sample_id: $sample_id - $date_report_approval_workflow ($ndate_report_approval_workflow)<br />"; 
        
    }
}


die();


//echo phpinfo();



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
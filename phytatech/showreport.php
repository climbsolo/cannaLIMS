<?php 

include "includes.php";

$cluid = "";

if (isset($_SESSION["cluid"])) {
    $cluid = $_SESSION["cluid"];
}

if (strlen($cluid) < 1) {
    header('Location: customerportal.php');
    die();
}

$id = $_GET["id"];

$sql = "select * from tblreports where id = '$id' and licensenumber = '$cluid'";

$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {  
        $filepath = $row["filepath"];
        $filename = $row["filename"];
        $guid = $row["guid"] . ".pdf";
        $sample_id = $row["sampleid"];
        $arr = explode("-", $filename);        
        $reporttype = str_replace(".pdf", "", $arr[1]);       
    }    

 showfooter();   
 
 $filepath .= "?cb=" . $uniqueID;

?>


<script>
    
    $.post("postcustomervisit.php", {sampleid: "<?php echo $sample_id ?>", reporttype: "<?php echo $reporttype ?>"});

</script>

  <!--<iframe id="frame" src="<?php echo $guid ?>" style="width:100%;height:100%;border:none;padding-bottom:10em;"></iframe>-->

<object data="<?php echo $filepath ?>" type="application/pdf" width="100%" height="100%">
 
  <p>It appears you don't have a PDF plugin for this browser.
  No biggie... you can <a href="<?php echo $filepath ?>">click here to
  download the PDF file.</a></p>
  
</object>    

<?php 


}
else
{
    
   die(); 
    
}

?>

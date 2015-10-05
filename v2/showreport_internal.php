<?php 

include "includes.php";

$luid = "";

if (isset($_SESSION["luid"])) {
    $luid = $_SESSION["luid"];
}

if (strlen($luid) < 1) {
    header('Location: customerportal.php');
    die();
}

$id = $_GET["id"];

$sql = "select * from tblreports where id = '$id'";

$stmt = $dbconn->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {  
        $filepath = $row["filepath"];
        $filename = $row["filename"];       
        $sample_id = $row["sampleid"];
        $arr = explode("-", $filename);        
        $reporttype = str_replace(".pdf", "", $arr[1]);       
    }    

  $filepath .= "?cb=" . $uniqueID;


?>

<head>
<title><?php echo $filename ?></title>
<meta name="description" content="Awesome Description Here">

<object data="<?php echo $filepath ?>" type="application/pdf" width="100%" height="100%">
 
  <p>It appears you don't have a PDF plugin for this browser.
  No biggie... you can <a href="<?php echo $filepath ?>">click here to
  download the PDF file.</a></p>
  
</object>  

<?php 

 showfooter();
}
else
{
    
   die(); 
    
}

?>

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

$x = $dbconn->query("select count(*) from tblsamples where license_number = '$cluid' and quickbooks_invoice_id = '$id' and (active <> 'false' or active = '' or active is null)")->fetchColumn();
    if ($x < 1) {
        die();
    }

$url = "http://qboapi.cannalims.com/api/invoice/GetInvoicePDF2/$qbo_appname/$id?cb=$uniqueID";

 $ch = curl_init();
    
    curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    curl_setopt($ch,CURLOPT_MAXREDIRS,50);
    if(substr($url,0,8)=='https://'){
        // The following ensures SSL always works. A little detail:
        // SSL does two things at once:
        //  1. it encrypts communication
        //  2. it ensures the target party is who it claims to be.
        // In short, if the following code is allowed, CURL won't check if the 
        // certificate is known and valid, however, it still encrypts communication.
        curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    }
    curl_setopt($ch,CURLOPT_URL, $url);    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
	
    $result = curl_exec($ch);
    curl_close($ch); 
    
    $url = str_replace('"', "", $result);

    $filepath = "http://qboapi.cannalims.com$url";    


?>

<object data="<?php echo $filepath ?>" type="application/pdf" width="100%" height="100%">
 
  <p>It appears you don't have a PDF plugin for this browser.
  No biggie... you can <a href="<?php echo $filepath ?>">click here to
  download the PDF file.</a></p>
  
</object>    
<?php 

include "includes.php";

if (!logincheck()) {
   die("logout"); 
}

if (!empty($_POST["finalarr"])) {
   $finalarr = $_POST["finalarr"];
}
else
{
    //die();
}

$testtype= "Residual Solvents";
$dbtesttype = "residualsolvents";

$finalarr = json_decode($finalarr, true);

$finalarrcompounds = array();
$reportsarr = array();
$arrsampleids = array();

list($usec, $sec) = explode(' ', microtime());
$usec = str_replace("0.", "", $usec);
$ndatetime = date($sec) . $usec;

$adatetime = $aDateTimeGlobal;
 
$insertsampleid = ""; 
 
foreach ($finalarr as $arr) {

$guid = $arr["guid"];
$instrumentname = $arr["instrumentname"];
$sampleid = $arr["sampleid"];
$samplemass = $arr["samplemass"];
$compoundname = $arr["compoundname"];
$sampletype = "Concentrate";
$injectiondate = $arr["injectiondate"];
$ninjectiondate = $arr["ninjectiondate"];
$percentage = $arr["percentage"];
$area = $arr["area"];
$dichloromethane_peak_area = $arr["dichloromethane_peak_area"];

    //echo $sampleid;
 if ($sampleid != $insertsampleid) {
        $insertsampleid = $sampleid;
        
        $sql = "update tblresidualsolvents set dup = 'true' where sampleid = '$sampleid'";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute();

        $sql = "insert into tblresidualsolvents (sampleid, adatetime, ndatetime, sampletype, instrumentname, injectiondate, ninjectiondate, subsamplemass) values ('$sampleid', '$adatetime', '$ndatetime', '$sampletype', '$instrumentname', '$injectiondate', '$ninjectiondate', '$samplemass')";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute();
 }

$sql = "update tblresidualsolvents set $compoundname = '$percentage' where sampleid = '$sampleid' and ndatetime = '$ndatetime'";
$stmt = $dbconn->prepare($sql);
$stmt->execute();

$sql = "update tblsamples set dichloromethane_peak_area = '$dichloromethane_peak_area' where sample_id = '$sampleid'";
$stmt = $dbconn->prepare($sql);
$stmt->execute();

$percentage = $percentage . " ppm"; //s / 100;

$arrsampleids["$sampleid"]=array("sampleid"=>$sampleid, "testtype"=>$testtype, "injectiondate"=>$injectiondate, "subsamplemass"=>$samplemass);

$finalarrcompounds[$sampleid]["$compoundname"]=$percentage;

}


foreach($arrsampleids as $key=>$val) {
    
    $sampleid = $val["sampleid"];
    $testtype = $val["testtype"];
    $injectiondate = $val["injectiondate"];
    $postedsubsamplemass = $val["subsamplemass"];
    
    $arr = explode(",", $injectiondate);
    $tvinjectiondate = $arr[0];
    $arr = explode("-", $tvinjectiondate);
    $day = $arr[0];
    if (strlen($day) < 2) { 
        $day = "0" . $day;
    }
    $month = $arr[1];
    $month = date("m", strtotime($month));
    $year = $arr[2];
    $tvinjectiondate = $month . "/" . $day . "/20" . $year;
   
    $arrupdate = array();
    
    $arrupdate["date_test_completion_workflow"] = $tvinjectiondate;
    $arrupdate["ndate_test_completion_workflow"] = strtotime(date($tvinjectiondate));
    $arrupdate["date_report_generation_workflow"] = $aDateGlobal;
    $arrupdate["ndate_report_generation_workflow"] = $nDateGlobal;
    $arrupdate["sub_sample_mass_residual_solvents"] = $postedsubsamplemass;
    
    foreach ($arrupdate as $key1=>$val1) {
        $sql = "update tblsamples set $key1 = :val1 where sample_id=:sample_id";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':val1'=>$val1, ':sample_id'=>$sampleid));
       
    }
    
    linkreport($sampleid, $testtype, $instrumentname, $dbtesttype);
}
  

function linkreport($sampleid, $testtype, $instrumentname, $dbtesttype) {
    
    global $homeurl;
    
    $url = $homeurl . "/makereport.php";
      
    $fields_string = "sampleid=$sampleid&testtype=$testtype&instrumentname=$instrumentname&dbtesttype=$dbtesttype";
    
    $strCookie = 'PHPSESSID=' . $_COOKIE['PHPSESSID'] . '; path=/';

    session_write_close();
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
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

    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    curl_setopt( $ch, CURLOPT_COOKIE, $strCookie ); 
	curl_setopt($ch,CURLOPT_POST, strlen($fields_string));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    
}


?>
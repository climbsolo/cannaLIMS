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
    die();
}

$testtype= "Terpenes";
$dbtesttype = "terpenes";

//$finalarr = '{"Alpha-Pinene3163":{"guid":"0770C46E-306F-4FE1-B2E8-2E5ED7FD613F","compoundname":"Alpha-Pinene","sampleid":3163,"sampletype":"Flower","injectiondate":0,"ninjectiondate":"","percentage":"0.2906241182398388","terpthc":"458","instrumentname":"HPLC 1100"},"Beta-Pinene3163":{"guid":"0770C46E-306F-4FE1-B2E8-2E5ED7FD613F","compoundname":"Beta-Pinene","sampleid":3163,"sampletype":"Flower","injectiondate":0,"ninjectiondate":"","percentage":"0.1937494121598925","terpthc":"458","instrumentname":"HPLC 1100"},"Myrcene3163":{"guid":"0770C46E-306F-4FE1-B2E8-2E5ED7FD613F","compoundname":"Myrcene","sampleid":3163,"sampletype":"Flower","injectiondate":0,"ninjectiondate":"","percentage":"0.22604098085320792","terpthc":"458","instrumentname":"HPLC 1100"},"Terpinolene3163":{"guid":"0770C46E-306F-4FE1-B2E8-2E5ED7FD613F","compoundname":"Terpinolene","sampleid":3163,"sampletype":"Flower","injectiondate":0,"ninjectiondate":"","percentage":"0.387498824319785","terpthc":"458","instrumentname":"HPLC 1100"},"Limonene3165":{"guid":"D74CAEF2-C883-4255-9AE3-FE6130E73662","compoundname":"Limonene","sampleid":3165,"sampletype":"Flower","injectiondate":0,"ninjectiondate":"","percentage":"0.3174908991127778","terpthc":"841","instrumentname":"HPLC 1100"},"Linalool3165":{"guid":"D74CAEF2-C883-4255-9AE3-FE6130E73662","compoundname":"Linalool","sampleid":3165,"sampletype":"Flower","injectiondate":0,"ninjectiondate":"","percentage":"0.14940748193542483","terpthc":"841","instrumentname":"HPLC 1100"},"Beta-Caryophyllene3165":{"guid":"D74CAEF2-C883-4255-9AE3-FE6130E73662","compoundname":"Beta-Caryophyllene","sampleid":3165,"sampletype":"Flower","injectiondate":0,"ninjectiondate":"","percentage":"0.29881496387084966","terpthc":"841","instrumentname":"HPLC 1100"}}';

$finalarr = json_decode($finalarr, true);

$finalarrcompounds = array();
$reportsarr = array();
$arrsampleids = array();

$insertsampleid = "";

list($usec, $sec) = explode(' ', microtime());
$usec = str_replace("0.", "", $usec);
$ndatetime = date($sec) . $usec;

$adatetime = $aDateTimeGlobal;
 
foreach ($finalarr as $arr) {
//$area = "";
$guid = $arr["guid"];
$instrumentname = $arr["instrumentname"];
$sampleid = $arr["sampleid"];
$samplemass = $arr["samplemass"];
$compoundname = $arr["compoundname"];
$sampletype =  $arr["sampletype"];
$injectiondate = $arr["injectiondate"];
$ninjectiondate = $arr["ninjectiondate"];
$percentage = $arr["percentage"];
//$area = $arr["area"];

if ($sampleid != $insertsampleid) {
    //echo $sampleid;
    $insertsampleid = $sampleid;
   
    $sql = "insert into tblterpenes (sampleid, adatetime, ndatetime, sampletype, instrumentname, injectiondate, ninjectiondate) values ('$sampleid', '$adatetime', '$ndatetime', '$sampletype', '$instrumentname', '$injectiondate', '$ninjectiondate')";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
}

$sqlcompoundname = preg_replace("/[^A-Za-z ]/", '', $compoundname);

$sql = "update tblterpenes set $sqlcompoundname = '$percentage' where sampleid = '$sampleid' and ndatetime = '$ndatetime'";
$stmt = $dbconn->prepare($sql);
$stmt->execute();

$percentage = $percentage . "%"; //s / 100;

$arrsampleids["$sampleid"]=array("sampleid"=>$sampleid, "testtype"=>$testtype, "injectiondate"=>$injectiondate, "subsamplemass"=>$samplemass);


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
    $arrupdate["sub_sample_mass_terpenes"] = $postedsubsamplemass;
    
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
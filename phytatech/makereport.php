<?php 

include "includes.php"; 

$sampleid = $_POST["sampleid"];
$testtype = DeXSS($_POST["testtype"]);
$dbtesttype = $_POST["dbtesttype"];
$instrumentname = DeXSS($_POST["instrumentname"]);
$moisturecontent = "";
if (isset($_POST["moisturecontent"])) {
    $moisturecontent = $_POST["moisturecontent"];
}
if (strlen($moisturecontent) > 0) {
    $moisturecontent = "<label>Moisture: </label><span>" . round($moisturecontent, 2) . "</span><br />";
}

$sql = "select * from tblsamples where sample_id = :sample_id";

$stmt = $dbconn->prepare($sql);
if ($stmt->execute(array(':sample_id'=>$sampleid))) {   
    while ($row = $stmt->fetch()) { 
        $injectiondate = $row["date_test_completion_workflow"];        
        
        logaction("date_test_completion_workflow", $injectiondate, $sampleid, 'tblsamples', $testtype);
        
        
        logaction("date_report_generation_workflow", $aDateGlobal, $sampleid, 'tblsamples', $testtype);       
        
        $samplenotes = DeXSS($row["notes"]);
        $checkindate = $row["date_accepted"];
        $client_id = $row["client_id"];
        $sql1 = "select business_name from tblclients where client_id = :client_id";
        $stmt1 = $dbconn->prepare($sql1);
        if ($stmt1->execute(array(':client_id'=>$client_id))) {   
            while ($row1 = $stmt1->fetch()) { 
                $businessname = $row1["business_name"];        
            }    
        }
        $licensenumber = $row["license_number"];
        $hidetpc = "";
        $sql2 = "select hide_total_potential_cannabinoids_on_reports from tbllicenses where license_number = :license_number";
        $stmt2 = $dbconn->prepare($sql2);
        if ($stmt2->execute(array(':license_number'=>$licensenumber))) {   
            while ($row2 = $stmt2->fetch()) { 
                $hidetpc = $row2["hide_total_potential_cannabinoids_on_reports"];        
            }    
        }
        
        $metrcnumber = $row["metrc_number"];        
        $dateaccepted = $row["date_accepted"];
        $batchid = $row["batch_id"];
        $productname = $row["product_name"];
        $packageamount = $row["package_amount"];
        
    }
}


$reporttype = "Standard";
if (isset($_POST["reporttype"])) {
    $reporttype = $_POST["reporttype"];
}

$message2 = "";
$cname = "";
$hidetpcstr = "";


if (strlen($hidetpc) < 1) {    
    $message2 = '<p ><span class="sup">2 </span> The sum of acidic and neutral values does not equal total potential content of a compound.<br /><span style="font-style:italic;font-size:80%;">To account for incomplete conversion of acidic to neutral compounds, the acidic value is reduced by a standard formula i.e., (THC-acid x 0.88) + delta9-THC = Total Potential THC</span>';
}



$message = "<div style=\"width:68%;float:left;border-top:2px solid lightgray;font-size:.7em;\">
<p><span class=\"sup\">1 </span> Product samples are tested as sold to patients, with no adjustment for water content.</p>
$message2    
<p>
<span class=\"sup\">* </span> Ingredients found in trace quantities, below physiologically significant levels, are not reported.
</p>
</div>";



if ($testtype == "Cannabinoids") {
    
    $rtesttype = "Cannabinoids";
    $reporttitle = "Cannabinoids";
      
            $thca = 0;
            $rthca = "NR*";
            $thc = 0;
            $rthc = "NR*";
            $thc_tpc = 0;
            $rthc_tpc = "NR*";
            $cbda = 0;
            $rcbda = "NR*";
            $cbd = 0;
            $rcbd = "NR*";
            $cbd_tpc = 0;
            $rcbd_tpc = "NR*";
            $cbca = 0;
            $rcbca = "NR*";
            $cbc = 0;
            $rcbc = "NR*";
            $cbc_tpc = 0;
            $rcbc_tpc = "NR*";
            $cbga = 0;
            $rcbga = "NR*";
            $cbg = 0;
            $rcbg = "NR*";
            $cbg_tpc = 0;
            $rcbg_tpc = "NR*";
            $cbna = 0;
            $rcbna = "NR*";
            $cbn = 0;
            $rcbn = "NR*";
            $cbn_tpc = 0;
            $rcbn_tpc = "NR*";
            $thcva = 0;
            $rthcva = "NR*";
            $thcv = 0;
            $rthcv = "NR*";
            $thcv_tpc = 0;
            $rthcv_tpc = "NR*";
            $cbdva = 0;
            $rcbdva = "NR*";
            $cbdv = 0;
            $rcbdv = "NR*";
            $cbdv_tpc = 0;
            $rcbdv_tpc = "NR*";
            $cbla = 0;
            $rcbla = "NR*";
            $cbla_tpc = 0;
            $rcbla_tpc = "NR*";
            $sd = "";
            $rsd = "";
          
    $sql = "select * from tblcannabinoids where sampleid = '$sampleid' and (dup <> 'true' or dup = '' or dup is null) order by ndatetime desc limit 1";
    $stmt = $dbconn->prepare($sql);
    
    if ($stmt->execute()) {
       
        while ($row = $stmt->fetch()) { 
            $thca = 0;
            $rthca = "NR*";
            $thc = 0;
            $rthc = "NR*";
            $thc_tpc = 0;
            $thc_cr = 0;
            $rthc_tpc = "NR*";
            $rthc_cr = "NR*";
            $cbda = 0;
            $rcbda = "NR*";
            $cbd = 0;
            $rcbd = "NR*";
            $cbd_tpc = 0;
            $rcbd_tpc = "NR*";
            $cbd_cr = 0;
            $rcbd_cr = "NR*";
            $cbca = 0;
            $rcbca = "NR*";
            $cbc = 0;
            $rcbc = "NR*";
            $cbc_tpc = 0;
            $rcbc_tpc = "NR*";
            $cbc_cr = 0;
            $rcbc_cr = "NR*";
            $cbga = 0;
            $rcbga = "NR*";
            $cbg = 0;
            $rcbg = "NR*";
            $cbg_tpc = 0;
            $rcbg_tpc = "NR*";
            $cbg_cr = 0;
            $rcbg_cr = "NR*";
            $cbna = 0;
            $rcbna = "NR*";
            $cbn = 0;
            $rcbn = "NR*";
            $cbn_tpc = 0;
            $rcbn_tpc = "NR*";
            $cbn_cr = 0;
            $rcbn_cr = "NR*";
            $thcva = 0;
            $rthcva = "NR*";
            $thcv = 0;
            $rthcv = "NR*";
            $thcv_tpc = 0;
            $rthcv_tpc = "NR*";
            $thcv_cr = 0;
            $rthcv_cr = "NR*";
            $cbdva = 0;
            $rcbdva = "NR*";
            $cbdv = 0;
            $rcbdv = "NR*";
            $cbdv_tpc = 0;
            $rcbdv_tpc = "NR*";
            $cbdv_cr = 0;
            $rcbdv_cr = "NR*";
            $cbla_tpc = 0;
            $rcbla_tpc = "NR*";
            $cbla_cr = 0;
            $rcbla_cr = "NR*";
            $sd = "";
            $rsd = "";
           
            $thca = $row["thca"];
            if (is_numeric($thca)) {
                $rthca = round($thca, 2) . "%";
            }
            else
            {
                $thca = 0;
            } 
            
            $thc = $row["thc"];
            if (is_numeric($thc)) {
                $rthc = round($thc, 2) . "%";
            }
            else
            {
                $thc = 0;
            } 
            
            //$test = $test . " - $thc";
      
            $thc_tpc = round(($thca * .88) + $thc, 2);                      
            if (is_numeric($thc_tpc)) {
                if ($thc_tpc > 0){
                    $rthc_tpc = $thc_tpc . "%";
                }
            }
            
            if (($thc + $thca) > 0) {            
                $thc_cr = round($thc / ($thc + $thca), 2) * 100;
                if (is_numeric($thc_cr)) {
                    if ($thc_cr > 0){
                        $rthc_cr = $thc_cr . "%";
                    }
                }
            }
          
            $cbda = $row["cbda"];
            if (is_numeric($cbda)) {
                $rcbda = round($cbda, 2) . "%";
            }
             else
            {
                $cbda = 0;
            } 
                        
            $cbd = $row["cbd"];
            if (is_numeric($cbd)) {
                $rcbd = round($cbd, 2) . "%";
            }
             else
            {
                $cbd = 0;
            }                     
            $cbd_tpc = round(($cbda * .88) + $cbd, 2);
            if (is_numeric($cbd_tpc)) {
                if ($cbd_tpc > 0){
                    $rcbd_tpc = $cbd_tpc . "%";
                }
            }
            
            if (($cbd + $cbda) > 0) {
                $cbd_cr = round($cbd / ($cbd + $cbda), 2) * 100;
                if (is_numeric($cbd_cr)) {
                    if ($cbd_cr > 0){
                        $rcbd_cr = $cbd_cr . "%";
                    }
                }
            }
            
            $cbca = $row["cbca"];
            if (is_numeric($cbca)) {
                $rcbca = round($cbca, 2) . "%";
            }
             else
            {
                $cbca = 0;
            } 
            $cbc = $row["cbc"];
            if (is_numeric($cbc)) {
                $rcbc = round($cbc, 2) . "%";
            }
            else
            {
                $cbc = 0;
            }             
            $cbc_tpc = round(($cbca * .88) + $cbc, 2);
            if (is_numeric($cbc_tpc)) {
                if ($cbc_tpc > 0){
                    $rcbc_tpc = $cbc_tpc . "%";
                }
            }
            
            if (($cbc + $cbca) > 0) {
                $cbc_cr = round($cbc / ($cbc + $cbca), 2) * 100;
                if (is_numeric($cbc_cr)) {
                    if ($cbc_cr > 0){
                        $rcbc_cr = $cbc_cr . "%";
                    }
                }
            }
            
            $cbga = $row["cbga"];
            if (is_numeric($cbga)) {
                $rcbga = round($cbga, 2) . "%";
            }
            else
            {
                $cbga = 0;
            } 
            $cbg = $row["cbg"];
            if (is_numeric($cbg)) {
                $rcbg = round($cbg, 2) . "%";
            }
            else
            {
                $cbg = 0;
            }             
            $cbg_tpc = round(($cbga * .88) + $cbg, 2);
            if (is_numeric($cbg_tpc)) {
                if ($cbg_tpc > 0){
                    $rcbg_tpc = $cbg_tpc . "%";
                }
            }
            
            if (($cbg + $cbga) > 0) {
                $cbg_cr = round($cbg / ($cbg + $cbga), 2) * 100;
                if (is_numeric($cbg_cr)) {
                    if ($cbg_cr > 0){
                        $rcbg_cr = $cbg_cr . "%";
                    }
                }
            }
            
            $cbna = $row["cbna"];
            if (is_numeric($cbna)) {
                $rcbna = round($cbna, 2) . "%";
            }
             else
            {
                $cbna = 0;
            } 
            $cbn = $row["cbn"];
            if (is_numeric($cbn)) {
                $rcbn = round($cbn, 2) . "%";
            }
            else
            {
                $cbn = 0;
            }             
            $cbn_tpc = round(($cbna * .88) + $cbn, 2);
            if (is_numeric($cbn_tpc)) {
                if ($cbn_tpc > 0){
                    $rcbn_tpc = $cbn_tpc . "%";
                }
            }

            if (($cbn + $cbna) > 0) {
            $cbn_cr = round($cbn / ($cbn + $cbna), 2) * 100;
                if (is_numeric($cbn_cr)) {
                    if ($cbn_cr > 0){
                        $rcbn_cr = $cbn_cr . "%";
                    }
                }
            }                
            
            $thcva = $row["thcva"];
            if (is_numeric($thcva)) {
                $rthcva = round($thcva, 2) . "%";
            }
             else
            {
                $thcva = 0;
            } 
            $thcv = $row["thcv"];
            if (is_numeric($thcv)) {
                $rthcv = round($thcv, 2) . "%";
            }
            else
            {
                $thcv = 0;
            }             
            $thcv_tpc = round(($thcva * .88) + $thcv, 2);            
            if (is_numeric($thcv_tpc)) {
                if ($thcv_tpc > 0){
                    $rthcv_tpc = $thcv_tpc . "%";
                }   
            }

            if (($thcv + $thcva) > 0) {
                $thcv_cr = round($thcv / ($thcv + $thcva), 2) * 100;
                if (is_numeric($thcv_cr)) {
                    if ($thcv_cr > 0){
                        $rthcv_cr = $thcv_cr . "%";
                    }
                }
            }
            
            $cbdva = $row["cbdva"];
            if (is_numeric($cbdva)) {
                $rcbdva = round($cbdva, 2) . "%";
            }
             else
            {
                $cbdva = 0;
            } 
            $cbdv = $row["cbdv"];
            if (is_numeric($cbdv)) {
                $rcbdv = round($cbdv, 2) . "%";
            }
            else
            {
                $cbdv = 0;
            }             
            $cbdv_tpc = round(($cbdva * .88) + $cbdv, 2);
            if (is_numeric($cbdv_tpc)) {
                if ($cbdv_tpc > 0){
                    $rcbdv_tpc = $cbdv_tpc . "%";
                }
            }
            
            if (($cbdv + $cbdva) > 0) {
                $cbdv_cr = round($cbdv / ($cbdv + $cbdva), 2) * 100;
                if (is_numeric($cbdv_cr)) {
                    if ($cbdv_cr > 0){
                        $rcbdv_cr = $cbdv_cr . "%";
                    }
                }
            }
            
            $cbla = $row["cbla"];
            if (is_numeric($cbla)) {
                $rcbla = round($cbla, 2) . "%";
            }
            else
            {
                $cbla = 0;
            } 
            
            //$test = $test . " - $cbla";
      
            
            $sd = $row["thc_standard_deviation"];
            $rsd = $row["thc_relative_standard_deviation"];
            
            if (strlen($sd) > 0) {
                
                $subsamplecount = $dbconn->query("select count(*) from tblcannabinoids where sampleid like '$sampleid" . "-%' and (dup <> 'true' or dup = '' or dup is null)")->fetchColumn();
                
                if ($rsd < 15) {                
                    $notes = "n = $subsamplecount, stdev = $sd, %RSD = $rsd% Sample Passes Homogeneity";
                }
                else
                {
                    $notes = "n = $subsamplecount, stdev = $sd, %RSD = $rsd% Sample Does NOT Pass Homogeneity";
                }                
             
            $samplenotes = $notes . "<br /><br />" . $samplenotes;
             
            }            
        }
    } 

    if ($reporttype == "Standard") {  
    
    if (strlen($hidetpc) > 1) { 
    
    $content = "<div id=\"results1\">
    <table style=\"font-size:.7em;background-color:#DCDDDE;padding:.5em;margin-bottom:1em;width:100%;\">
        <tr>
            <td style=\"width:50%;\">ACIDIC COMPOUND</td>
            <td style=\"width:50%;\">NEUTRAL COMPOUND</td>
                  
        </tr>
    </table>   
        <table id=\"resultstable\">            
            <tr>             
                <td><div style=\"width:1.2em;height:1.5em;background-color:#FFBC8F;margin-right:5px;float:left;\"></div>CBDA</td><td><span id=\"cbda\">$rcbda</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#F79552;margin-right:5px;float:left;\"></div>CBD</td><td><span id=\"cbd\">$rcbd</span></td>
 
            </tr>
            <tr>             
                <td><div style=\"width:1.2em;height:1.5em;background-color:#FA8F7F;margin-right:5px;float:left;\"></div>THCA</td><td><span id=\"thca\">$rthca</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#F04E37;margin-right:5px;float:left;\"></div>THC</td><td><span id=\"thc\">$rthc</span></td>                
            </tr>
            <tr>          
                <td><div style=\"width:1.2em;height:1.5em;background-color:#FFD8C9;margin-right:5px;float:left;\"></div>CBCA</td><td><span id=\"cbca\">$rcbca</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#FAB49B;margin-right:5px;float:left;\"></div>CBC</td><td><span id=\"cbd\">$rcbc</span></td>               
            </tr>
            <tr>              
                <td><div style=\"width:1.2em;height:1.5em;background-color:#DFF08D;margin-right:5px;float:left;\"></div>CBGA</td><td><span id=\"cbga\">$rcbga</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#C8DC69;margin-right:5px;float:left;\"></div>CBG</td><td><span id=\"cbg\">$rcbg</span></td>               
            </tr>
            <tr>             
                <td><div style=\"width:1.2em;height:1.5em;background-color:#57DE9D;margin-right:5px;float:left;\"></div>CBNA</td><td><span id=\"cbna\">$rcbna</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#2BB673;margin-right:5px;float:left;\"></div>CBN</td><td><span id=\"cbn\">$rcbn</span></td>                
            </tr>
            <tr>             
                <td><div style=\"width:1.2em;height:1.5em;background-color:#61C3F2;margin-right:5px;float:left;\"></div>THCVA</td><td><span id=\"thcva\">$rthcva</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#1D9AD6;margin-right:5px;float:left;\"></div>THCV</td><td><span id=\"thcv\">$rthcv</span></td>                
            </tr>
            <tr>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#C3E8F7;margin-right:5px;float:left;\"></div>CBDVA</td><td><span id=\"cbdva\">$rcbdva</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#91D9F8;margin-right:5px;float:left;\"></div>CBDV</td><td><span id=\"cbdv\">$rcbdv</span></td>               
            </tr>
        
        </table>
    
    </div>";

    }   
    else
    {    
    
    $content = "<div id=\"results1\">
    <table style=\"font-size:.7em;background-color:#DCDDDE;padding:.5em;margin-bottom:1em;width:100%;\">
        <tr>
            <td style=\"width:32%;\">ACIDIC<br />COMPOUND</td>
            <td style=\"width:32%;\">NEUTRAL<br />COMPOUND</td>
            <td style=\"width:32%;text-align:right;\"><div style=\"font-weight:bold;text-align:left;\"><span>TOTAL POTENTIAL CANNABINOIDS<span class=\"sup\">2</span></span></div></td>
        </tr>
    </table>   
        <table id=\"resultstable\">            
            <tr>
                <td>CBDA</td><td><span id=\"cbda\">$rcbda</span></td>
                <td>CBD</td><td><span id=\"cbd\">$rcbd</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#F79552;margin-right:5px;float:left;\"></div><span>CBD</span></td><td><span id=\"cbd_tpc\">$rcbd_tpc</span></td>
            </tr>
            <tr>
                <td>THCA</td><td><span id=\"thca\">$rthca</span></td>
                <td>THC</td><td><span id=\"thc\">$rthc</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#F04E37;margin-right:5px;float:left;\"></div><span>THC</span></td><td><span id=\"thc_tpc\">$rthc_tpc</span></td>
            </tr>
            <tr>
                <td>CBCA</td><td><span id=\"cbca\">$rcbca</span></td>
                <td>CBC</td><td><span id=\"cbd\">$rcbc</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#FAB49B;margin-right:5px;float:left;\"></div><span>CBC</span></td><td><span id=\"cbc_tpc\">$rcbc_tpc</span></td>
            </tr>
            <tr>
                <td>CBGA</td><td><span id=\"cbga\">$rcbga</span></td>
                <td>CBG</td><td><span id=\"cbg\">$rcbg</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#C8DC69;margin-right:5px;float:left;\"></div><span>CBG</span></td><td><span id=\"cbg_tpc\">$rcbg_tpc</span></td>
            </tr>
            <tr>
                <td>CBNA</td><td><span id=\"cbna\">$rcbna</span></td>
                <td>CBN</td><td><span id=\"cbn\">$rcbn</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#2BB673;margin-right:5px;float:left;\"></div><span>CBN</span></td><td><span id=\"cbn_tpc\">$rcbn_tpc</span></td>
            </tr>
            <tr>
                <td>THCVA</td><td><span id=\"thcva\">$rthcva</span></td>
                <td>THCV</td><td><span id=\"thcv\">$rthcv</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#1D9AD6;margin-right:5px;float:left;\"></div><span>THCV</span></td><td><span id=\"thcv_tpc\">$rthcv_tpc</span></td>
            </tr>
            <tr>
                <td>CBDVA</td><td><span id=\"cbdva\">$rcbdva</span></td>
                <td>CBDV</td><td><span id=\"cbdv\">$rcbdv</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#91D9F8;margin-right:5px;float:left;\"></div><span>CBDV</span></td><td><span id=\"cbdv_tpc\">$rcbdv_tpc</span></td>
            </tr>
        
        </table>
    
    </div>";
    
    }
    
    }
    
    if ($reporttype == "Infused I" || $reporttype == "Infused II") {
        
    $rtesttype = "Cannabinoids";
    $reporttitle = str_replace(' ', '', $reporttype);
    $reporttitle = $reporttype;
    
    $im2 = "";
    $mg = "mg\ml";
    
    if ($reporttype == "Infused I") {       
       $im2 = "<p><span class=\"sup\">* </span> 14 ml equals approximately 1 tablespoon.";
        $thc = $thc / $packageamount;        
        $thca = $thca / $packageamount;       
        $cbc = $cbc / $packageamount;       
        $cbca = $cbca / $packageamount;        
        $cbd = $cbd / $packageamount;        
        $cbda = $cbda / $packageamount;        
        $cbg = $cbg / $packageamount;        
        $cbga = $cbga / $packageamount;        
        $cbn = $cbn / $packageamount;        
        $cbna = $cbna / $packageamount;       
        $thcv = $thcv / $packageamount;        
        $thcva = $thcva / $packageamount;
        $cbdv = $cbdv / $packageamount;
        $cbdva = $cbdva / $packageamount;       
    }
    
    if ($reporttype == "Infused II") {
        $mg = "mg\unit";                     
    }
    
    
    
    if ($thc > 0) {
        $rthc  = round($thc, 2);
    }
    if ($thca > 0 ) {
        $rthca = round($thca, 2);
    }
    if ($cbc > 0 ) {
        $rcbc = round($cbc, 2);
    }
    if ($cbca > 0 ) {
        $rcbca = round($cbca, 2);
    }
    if ($cbd > 0) {
        $rcbd = round($cbd, 2);
    }
    if ($cbda > 0) {
        $rcbda = round($cbda, 2);
    }
    if ($cbg >0) {
        $rcbg = round($cbg, 2);
    }
    if ($cbga > 0 ) {
        $rcbga = round($cbga, 2);
    }
    if ($cbn > 0) {
        $rcbn =  round($cbn, 2);
    }
    if ($cbna > 0) {
        $rcbna = round($cbna, 2);
    }
    if ($thcv > 0) {
        $rthcv = round($thcv, 2);
    }
    if ($thcva > 0) {
        $rthcva = round($thcva, 2);
    }        
    if ($cbdv > 0) {
        $rcbdv = round($cbdv, 2);
    }
    if ($cbdva > 0) {
        $rcbdva = round($cbdva, 2);
    }
    
    $message = "<div style=\"width:68%;float:left;border-top:2px solid lightgray;font-size:.7em;\">
    <p><span class=\"sup\">* </span> None Reported (NR) because the compound exists at or below the detection limit of the method.</p>  
    $im2
    <p><span class=\"sup\">1 </span> Conversion ratio indicates the amount of neutral cannabinoid compared directly to its acidic counterpart. A number close to 1 means a high neutral content. A number close to 0 indicates a high acidic cannabinoid content.</p>
    </div>";
    
        
    $content = "<div id=\"results1\">
    <table style=\"font-size:.7em;background-color:#DCDDDE;padding:.5em;margin-bottom:1em;width:100%;\">
        <tr>
            <td style=\"width:32%;\">ACIDIC<br />COMPOUND $mg</td>
            <td style=\"width:32%;\">NEUTRAL<br />COMPOUND $mg</td>
            <td style=\"width:32%;text-align:right;\"><div style=\"font-weight:bold;text-align:left;\"><span>Conversion Ratio<span class=\"sup\">1</span></span></div></td>
        </tr>
    </table>   
        <table id=\"resultstable\">            
            <tr>
                <td>CBDA</td><td><span id=\"cbda\">$rcbda</span></td>
                <td>CBD</td><td><span id=\"cbd\">$rcbd</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#F79552;margin-right:5px;float:left;\"></div></td><td><span id=\"cbd_cr\">$rcbd_cr</span></td>
            </tr>
            <tr>
                <td>THCA</td><td><span id=\"thca\">$rthca</span></td>
                <td>THC</td><td><span id=\"thc\">$rthc</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#F04E37;margin-right:5px;float:left;\"></div></td><td><span id=\"thc_cr\">$rthc_cr</span></td>
            </tr>
            <tr>
                <td>CBCA</td><td><span id=\"cbca\">$rcbca</span></td>
                <td>CBC</td><td><span id=\"cbd\">$rcbc</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#FAB49B;margin-right:5px;float:left;\"></div></td><td><span id=\"cbc_cr\">$rcbc_cr</span></td>
            </tr>
            <tr>
                <td>CBGA</td><td><span id=\"cbga\">$rcbga</span></td>
                <td>CBG</td><td><span id=\"cbg\">$rcbg</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#C8DC69;margin-right:5px;float:left;\"></div></td><td><span id=\"cbg_cr\">$rcbg_cr</span></td>
            </tr>
            <tr>
                <td>CBNA</td><td><span id=\"cbna\">$rcbna</span></td>
                <td>CBN</td><td><span id=\"cbn\">$rcbn</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#2BB673;margin-right:5px;float:left;\"></div></td><td><span id=\"cbn_cr\">$rcbn_cr</span></td>
            </tr>
            <tr>
                <td>THCVA</td><td><span id=\"thcva\">$rthcva</span></td>
                <td>THCV</td><td><span id=\"thcv\">$rthcv</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#1D9AD6;margin-right:5px;float:left;\"></div></td><td><span id=\"thcv_cr\">$rthcv_cr</span></td>
            </tr>
            <tr>
                <td>CBDVA</td><td><span id=\"cbdva\">$rcbdva</span></td>
                <td>CBDV</td><td><span id=\"cbdv\">$rcbdv</span></td>
                <td><div style=\"width:1.2em;height:1.5em;background-color:#91D9F8;margin-right:5px;float:left;\"></div></td><td><span id=\"cbdv_cr\">$rcbdv_cr</span></td>
            </tr>
        
        </table>
    
    </div>";

    }  

 $content .= "
    <div id=\"results2\">
         <div id=\"chart_div\"></div>
    </div>
    
    <div style=\"clear:both;\" class=\"separator\"></div>
    
    <br />
    ";    
    
}
    
//--------------------------------- Residual Solvents ---------------------------------
    
if ($testtype == "Residual Solvents") {

    $rtesttype = "ResidualSolvents";
    $reporttitle = "Residual Solvents";
    
    $message = "<div style=\"width:68%;float:left;border-top:2px solid lightgray;font-size:.7em;\"><p><span class=\"sup\">* </span> None Reported (NR) because the compound exists at or below the detection limit of the method.</p>
      <p><span class=\"sup\">* </span> None Detected (ND) because the compound was not detected.</p></div>";

    $sql = "select * from tblresidualsolvents where sampleid = '$sampleid' order by ndatetime desc limit 1";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
            $isobutane = 0;
            $risobutane = "ND*";
            $isobutanepf ="green";
            $butane = 0;
            $rbutane = "ND*";
            $butanepf = "green";
            $benzene = 0;
            $rbenzene = "ND*";
            $benzenepf = "green";
            $heptane = 0;
            $rheptane = "ND*";
            $heptanepf = "green";
            $hexane = 0;
            $rhexane = "ND*";
            $hexanepf = "green";
            $toluene = 0;
            $rtoluene= "ND*";
            $toluenepf = "green";
            $xylene = 0;
            $rxylene = "ND*";
            $xylenepf = "green";
                
            $butane = $row["butane"];
            if (is_numeric($butane)) {
            if ($butane >= .5) {    
                if ($butane >=.5 && $butane < 1)
                {
                    $rbutane = "NR*";
                }
                else
                {                
                    $rbutane = round($butane, 2) . "ppm";
                }
                if ($butane >= 800) {
                    $butanepf = "red";
                }
                }
            }            
            $isobutane = $row["isobutane"];
            if (is_numeric($isobutane)) {
                if ($isobutane >=.5) {
                if ($isobutane >=.5 && $isobutane < 1)
                {
                    $risobutane = "NR*";
                }
                else
                {  
                    $risobutane = round($isobutane, 2) . "ppm";
                }   
                if ($isobutane >= 800) {
                    $isobutanepf = "red";
                }
                }
            } 
            $benzene = $row["benzene"];
            if (is_numeric($benzene)) {
                if ($benzene >=.5) {
                if ($benzene >=.5 && $benzene < 1)
                {
                    $rbenzene = "NR*";
                }
                else
                {  
                    $rbenzene = round($benzene, 2) . "ppm";
                }
                if ($benzene >= 1) {
                    $benzenepf = "red";
                }
                }
            } 
            $heptane = $row["heptane"];
            if (is_numeric($heptane)) {
                if ($heptane >=.5) {
                if ($heptane >=.5 && $heptane < 1)
                {
                    $rheptane = "NR*";
                }
                else
                {  
                    $rheptane = round($heptane, 2) . "ppm";
                }
                if ($heptane >= 500) {
                    $heptanepf = "red";
                }
                }
            }
            $hexane = $row["hexane"];
            if (is_numeric($hexane)) {
                if ($hexane >=.5) {
                if ($hexane >=.5 && $hexane < 1)
                {
                    $rhexane = "NR*";
                }
                else
                {  
                    $rhexane = round($hexane, 2) . "ppm";
                }
                if ($hexane >= 10) {
                    $hexanepf = "red";
                }
                }
            }
            $toluene = $row["toluene"];
            if (is_numeric($toluene)) {
                if ($toluene >=.5) {
                if ($toluene >=.5 && $toluene < 1)
                {
                    $rtoluene = "NR*";
                }
                else
                {  
                    $rtoluene = round($toluene, 2) . "ppm";
                }
                if ($toluene >= 1) {
                    $toluenepf = "red";
                }
                }
            }
            $xylene = $row["xylene"];
            if (is_numeric($xylene)) {
                if ($xylene >=.5) {
                if ($xylene >=.5 && $xylene < 1)
                {
                    $rxylene = "NR*";
                }
                else
                {  
                    $rxylene = round($xylene, 2) . "ppm";
                }
                if ($xylene >= 1) {
                    $xylenepf = "red";
                }
                }
            }
            
            $content = "<div id=\"results1\">
                <table id=\"resultstable\">
                    <tr style=\"font-size:.7em;background-color:#DCDDDE;padding:.5em;margin-bottom:1em;\">
                        <div style=\"font-weight:bold;\">RESIDUAL SOLVENTS<br />parts per million (ppm)</div>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:$butanepf;margin-right:5px;float:left;\"></div>Butane</td>
                        <td><span id=\"cbc_tpc\">$rbutane</span></td>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:$isobutanepf;margin-right:5px;float:left;\"></div>Isobutane</td>
                        <td><span id=\"cbc_tpc\">$risobutane</span></td>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:$benzenepf;margin-right:5px;float:left;\"></div>Benzene</td>
                        <td><span id=\"cbc_tpc\">$rbenzene</span></td>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:$heptanepf;margin-right:5px;float:left;\"></div>Heptane</td>
                        <td><span id=\"cbc_tpc\">$rheptane</span></td>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:$hexanepf;margin-right:5px;float:left;\"></div>Hexane</td>
                        <td><span id=\"cbc_tpc\">$rhexane</span></td>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:$toluenepf;margin-right:5px;float:left;\"></div>Toluene</td>
                        <td><span id=\"cbc_tpc\">$rtoluene</span></td>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:$xylenepf;margin-right:5px;float:left;\"></div>Xylene</td>
                        <td><span id=\"cbc_tpc\">$rxylene</span></td>
                    </tr>
                    
                </table>
            
            </div>
            
            <div id=\"results2\" style=\"display:none;\">
                 <div id=\"chart_div\"></div>
            </div>
            
            <div style=\"clear:both;\" class=\"separator\"></div>
            
            <br />";            
               
        }
    }
}

//--------------------------------- Terpenes ---------------------------------
    
if ($testtype == "Terpenes") {

    $rtesttype = "Terpenes";
    $reporttitle = "Terpenes";
    
    
    
    //$message2 = '<p><span class="sup">2 </span> Common terpenes; not including all terpenoid compounds found in <span style="font-style:italic;">Cannabis</span>';
    
    $message = "<div style=\"width:68%;float:left;border-top:2px solid lightgray;font-size:.7em;\"><p><span class=\"sup\">* </span> None Reported (NR) because the compound exists at or below the detection limit of the method.</p></div>";

    $sql = "select * from tblterpenes where sampleid = '$sampleid' order by ndatetime desc limit 1";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) { 
            $alphapinene = 0;
            $ralphapinene = "NR*";
            $betapinene = 0;
            $rbetapinene = "NR*";
            $myrcene = 0;
            $rmyrcene = "NR*";
            $limonene = 0;
            $rlimonene = "NR*";
            $terpinolene = 0;
            $rterpinolene = "NR*";
            $linalool = 0;
            $rlinalool = "NR*";
            $alphaterpineol = 0;
            $ralphaterpineol = "NR*";
            $betacaryophyllene = 0;
            $rbetacaryophyllene = "NR*";
            $humulene = 0;
            $rhumulene = "NR*";
            $cisocimene  = 0;
            $rcisocimene = "NR*";
                
            $alphapinene = $row["alphapinene"];
            if (is_numeric($alphapinene)) {
                $ralphapinene = round($alphapinene, 2) . "%";
            }            
            $betapinene = $row["betapinene"];
            if (is_numeric($betapinene)) {
                $rbetapinene = round($betapinene, 2) . "%";
            } 
            $myrcene = $row["myrcene"];
            if (is_numeric($myrcene)) {
                $rmyrcene = round($myrcene, 2) . "%";
            }
            $limonene = $row["limonene"];
            if (is_numeric($limonene)) {
                $rlimonene = round($limonene, 2) . "%";
            }
            $terpinolene = $row["terpinolene"];
            if (is_numeric($terpinolene)) {
                $rterpinolene = round($terpinolene, 2) . "%";
            }
            $linalool = $row["linalool"];
            if (is_numeric($linalool)) {
                $rlinalool = round($linalool, 2) . "%";
            }
            $alphaterpineol = $row["alphaterpineol"];
            if (is_numeric($alphaterpineol)) {
                $ralphaterpineol = round($alphaterpineol, 2) . "%";
            }
            $betacaryophyllene = $row["betacaryophyllene"];
            if (is_numeric($betacaryophyllene)) {
                $rbetacaryophyllene = round($betacaryophyllene, 2) . "%";
            }
            $humulene = $row["humulene"];
            if (is_numeric($humulene)) {
                $rhumulene = round($humulene, 2) . "%";
            }
            $cisocimene = $row["cisocimene"];
            if (is_numeric($cisocimene)) {
                $cisocimene = round($cisocimene, 2) . "%";
            }
            
            $content = "<div id=\"results1\">
                <table id=\"resultstable\">
                    <tr style=\"font-size:.7em;background-color:#DCDDDE;padding:.5em;margin-bottom:1em;\">
                        <div><span style=\"font-weight:bold;\">TERPENES</span><br />(percentage (%))</div>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:#9EB169;margin-right:5px;float:left;\"></div>&alpha;-pinene</td>
                        <td><span id=\"cbc_tpc\">$ralphapinene</span></td>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:#D3BD2A;margin-right:5px;float:left;\"></div>&beta;-pinene</td>
                        <td><span id=\"cbc_tpc\">$rbetapinene</span></td>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:#FFC709;margin-right:5px;float:left;\"></div>Myrcene</td>
                        <td><span id=\"cbc_tpc\">$rmyrcene</span></td>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:#FFF460;margin-right:5px;float:left;\"></div>Limonene</td>
                        <td><span id=\"cbc_tpc\">$rlimonene</span></td>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:#DDD7D5;margin-right:5px;float:left;\"></div>Terpinolene</td>
                        <td><span id=\"cbc_tpc\">$rterpinolene</span></td>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:#BBB7DC;margin-right:5px;float:left;\"></div>Linalool</td>
                        <td><span id=\"cbc_tpc\">$rlinalool</span></td>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:#B5A2C3;margin-right:5px;float:left;\"></div>&alpha;-terpineol</td>
                        <td><span id=\"cbc_tpc\">$ralphaterpineol </span></td>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:#9E76B4;margin-right:5px;float:left;\"></div>&beta;-caryophyllene</td>
                        <td><span id=\"cbc_tpc\">$rbetacaryophyllene</span></td>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:#6E2B8D;margin-right:5px;float:left;\"></div>Humulene</td>
                        <td><span id=\"cbc_tpc\">$rhumulene</span></td>
                    </tr>
                    <tr>                        
                        <td><div style=\"width:1.2em;height:1.5em;background-color:#2E3091;margin-right:5px;float:left;\"></div>Cis-ocimene</td>
                        <td><span id=\"cbc_tpc\">$rcisocimene</span></td>
                    </tr>                    
                
                </table>
            
            </div>
            
            <div id=\"results2\">
                 <div id=\"chart_div\"></div>
            </div>
            
            <div style=\"clear:both;\" class=\"separator\"></div>
            
            <br />";            
               
        }
    }
}
/*
$arr = explode("-", $dateaccepted);
$day = $arr[1];
if (strlen($day) < 2) {
    $day = "0" . $day;
}
$month = $arr[0];
$year = $arr[2];

$month = getMonthByNumber($month);

$dateaccepted = $month . " " . $day . ", " . $year;
*/

$arr = explode(",", $injectiondate);
$injectiondate = $arr[0];
/*
$arr = explode("-", $injectiondate);
$day = $arr[0];
if (strlen($day) < 2) { 
    $day = "0" . $day;
}
$month = $arr[1];
$year = $arr[2];
$injectiondate = $month . " " . $day . ", 20" . $year;
*/

$datereported = date("m/d/Y");


$str = fopen("reports/standard.html", "r");
$str = fread($str, filesize("reports/standard.html"));

$str = str_replace ("{CUSTOMER}", $businessname, $str);
$str = str_replace ("{SAMPLEID}", $sampleid, $str);
$str = str_replace ("{BATCHNUMBER}", $batchid, $str);
$str = str_replace ("{METRCNUMBER}", $metrcnumber, $str);
$str = str_replace ("{DATERECEIVED}", $dateaccepted, $str);
$str = str_replace ("{DATEOFLABTEST}", $injectiondate, $str);
$str = str_replace ("{DATEREPORTED}", $datereported, $str);
$str = str_replace ("{REPORTTYPE}", strtoupper($reporttitle), $str);
$str = str_replace ("{PIECHART}", piechart($testtype), $str);
$str = str_replace ("{CONTENT}", $content, $str);
$str = str_replace ("{ADDRESS}", $labaddress, $str);
$str = str_replace ("{PHONE}", $labphone, $str);
$str = str_replace ("{SAMPLENOTES}", $samplenotes, $str);
$str = str_replace ("{MESSAGE}", $message, $str);
$str = str_replace ("{PRODUCTNAME}", $productname, $str);
$str = str_replace ("{LICENSE}", $licensenumber, $str);
$str = str_replace ("{INSTRUMENTNAME}", $instrumentname, $str);
$str = str_replace ("{MOISTURECONTENT}", $moisturecontent, $str);


//$test = round(($cbga * .88) + $cbg, 2);
$test = "";
$str = str_replace ("{TEST}", $test, $str);

$guid = com_create_guid();
$guid = str_replace ( '{' , '', $guid);
$guid = str_replace ( '}' , '', $guid); 


file_put_contents("reports/temp/$sampleid-$rtesttype" . ".html", $str);
//file_put_contents("reports/pdf/$sampleid-$rtesttype" . ".html", $str);

$str = "wkhtmltopdf -s Letter -T 0 -L 0 -B 0 -R 0 --zoom 1.33 --load-error-handling ignore reports/temp/$sampleid-$rtesttype" . ".html reports/pdf/$guid" . ".pdf";
//--javascript-delay 1500

exec($str);

unlink("reports/temp/$sampleid-$rtesttype" . ".html");

$ncheckindate = strtotime(date($checkindate));


$sql = "delete from tblreports where filename = :filename";
$stmt = $dbconn->prepare($sql);
$stmt->execute(array(':filename'=>"$sampleid-$rtesttype" . ".pdf"));


$sql = "insert into tblreports (filename, filepath, adatecreated, ndatecreated, adatecheckedin, ndatecheckedin, businessname, licensenumber, batchid, productname, sampleid, metrcnumber, testtype, guid) values (:filename, :filepath, :adatecreated, :ndatecreated, :adatecheckedin, :ndatecheckedin, :businessname, :licensenumber, :batchid, :productname, :sampleid, :metrcnumber, :dbtesttype, :guid)";
$stmt = $dbconn->prepare($sql);
$stmt->execute(array(':filename'=>"$sampleid-$rtesttype" . ".pdf", ':filepath'=>"reports/pdf/$guid" . ".pdf", ':adatecreated'=>$aDateGlobal, ':ndatecreated'=>$nDateGlobal, ':adatecheckedin'=>$checkindate, ':ndatecheckedin'=>$ncheckindate, ':businessname'=>$businessname, ':licensenumber'=>$licensenumber, ':batchid'=>$batchid, ':productname'=>$productname, ':sampleid'=>$sampleid, ':metrcnumber'=>$metrcnumber, ':dbtesttype'=>$dbtesttype, ':guid'=>$guid));


function piechart($testtype) {
    
    global $hidetpc;
    global $reporttype;
    
    $datapoints = "dataPoints: [ ";
    
    if ($testtype == "Cannabinoids") {    
        global $thca, $rthca, $thc, $rthc, $thc_tpc, $rthc_tpc, $thc_cr, $rthc_cr, $cbda, $rcbda, $cbd, $rcbd, $cbd_tpc, $rcbd_tpc, $cbd_cr, $rcbd_cr, $cbca, $rcbca, $cbc, $rcbc, $cbc_tpc, $rcbc_tpc, $cbc_cr, $rcbc_cr, $cbga, $rcbga, $cbg, $rcbg, $cbg_tpc, $rcbg_tpc, $cbg_cr, $rcbg_cr, $cbna, $rcbna, $cbn, $rcbn, $cbn_tpc, $rcbn_tpc, $cbn_cr, $rcbn_cr, $thcva, $rthcva, $thcv, $rthcv, $thcv_tpc, $rthcv_tpc, $thcv_cr, $rthcv_cr, $cbdva, $rcbdva, $cbdv, $rcbdv, $cbdv_tpc, $rcbdv_tpc, $cbdv_cr, $rcbdv_cr;      
        
        if ($reporttype == "Standard") {
        
            if (strlen($hidetpc) > 1) {
             
                if ($rcbda != "NR*") {
                    $datapoints .= "{ y: " . round($cbda, 2). ", indexLabel: \"CBDA: $rcbda\", color: \"#FFBC8F\" },";
                }         
                if ($rcbd != "NR*") {
                    $datapoints .= "{ y: " . round($cbd, 2). ", indexLabel: \"CBD: $rcbd\", color: \"#F79552\" },";
                }
                
                if ($rthca != "NR*") {
                    $datapoints .= "{ y: " . round($thca, 2) . ", indexLabel: \"THCA: $rthca\", color: \"#FA8F7F\" },";
                }
                if ($rthc != "NR*") {
                    $datapoints .= "{ y: " . round($thc, 2) . ", indexLabel: \"THC: $rthc\", color: \"#F04E37\" },";
                }

                if ($rcbca != "NR*") {
                    $datapoints .= "{ y: " . round($cbca, 2) . ", indexLabel: \"CBCA: $rcbca\", color: \"#FFD8C9\" },";
                }        
                if ($rcbc != "NR*") {
                    $datapoints .= "{ y: " . round($cbc, 2) . ", indexLabel: \"CBC: $rcbc\", color: \"#FAB49B\" },";
                }
                
                if ($rcbga != "NR*") {
                    $datapoints .= "{ y: " . round($cbga, 2) . ", indexLabel: \"CBGA: $rcbga\", color: \"#DFF08D\" },";
                }
                if ($rcbg != "NR*") {
                    $datapoints .= "{ y: " . round($cbg, 2) . ", indexLabel: \"CBG: $rcbg\", color: \"#C8DC69\" },";
                }
                
                if ($rcbna != "NR*") {
                    $datapoints .= "{ y: " . round($cbna, 2) . ", indexLabel: \"CBNA: $rcbna\", color: \"#57DE9D\" },";
                }
                if ($rcbn != "NR*") {
                    $datapoints .= "{ y: " . round($cbn, 2) . ", indexLabel: \"CBN: $rcbn\", color: \"#2BB673\" },";
                }
                
                if ($rthcva != "NR*") {
                    $datapoints .= "{ y: " . round($thcva, 2) . ", indexLabel: \"THCVA: $rthcva\", color: \"#61C3F2\" },";
                }
                if ($rthcv != "NR*") {
                    $datapoints .= "{ y: " . round($thcv, 2) . ", indexLabel: \"THCV: $rthcv\", color: \"#1D9AD6\" },";
                }
                
                if ($rcbdva != "NR*") {
                    $datapoints .= "{ y: " . round($cbdva, 2) . ", indexLabel: \"CBDVA: $rcbdva\", color: \"#C3E8F7\" },";
                }
                if ($rcbdv != "NR*") {
                    $datapoints .= "{ y: " . round($cbdv, 2) . ", indexLabel: \"CBDV: $rcbdv\", color: \"#91D9F8\" },";
                }
                    
            }        
            else
            {       
                
                if ($rcbd_tpc != "NR*") {
                    $datapoints .= "{ y: " . round($cbd_tpc, 2) . ", indexLabel: \"CBD: $rcbd_tpc\", color: \"#F79552\" },";
                }
                if ($rthc_tpc != "NR*") {
                    $datapoints .= "{ y: " . round($thc_tpc, 2) . ", indexLabel: \"THC: $rthc_tpc\", color: \"#F04E37\" },";
                }
                if ($rcbc_tpc != "NR*") {
                    $datapoints .= "{ y: " . round($cbc_tpc, 2) . ", indexLabel: \"CBC: $rcbc_tpc\", color: \"#FAB49B\" },";
                }
                if ($rcbg_tpc != "NR*") {
                    $datapoints .= "{ y: " . round($cbg_tpc, 2) . ", indexLabel: \"CBG: $rcbg_tpc\", color: \"#C8DC69\" },";
                }
                if ($rcbn_tpc != "NR*") {
                    $datapoints .= "{ y: " . round($cbn_tpc, 2) . ", indexLabel: \"CBN: $rcbn_tpc\", color: \"#2BB673\" },";
                }
                if ($rthcv_tpc != "NR*") {
                    $datapoints .= "{ y: " . round($thcv_tpc, 2) . ", indexLabel: \"THCV: $rthcv_tpc\", color: \"#1D9AD6\" },";
                }
                if ($rcbdv_tpc != "NR*") {
                    $datapoints .= "{ y: " . round($cbdv_tpc, 2) . ", indexLabel: \"CBDV: $rcbdv_tpc\", color: \"#91D9F8\" },";
                }
            }
        }
    
        if ($reporttype == "Infused I" || $reporttype == "Infused II") {
                    
            if ($rcbd_cr != "NR*") {
                $datapoints .= "{ y: " . round($cbd_cr, 2) . ", indexLabel: \"CBD/CBDA: $rcbd_cr\", color: \"#F79552\" },";
            }
            if ($rthc_cr != "NR*") {
                $datapoints .= "{ y: " . round($thc_cr, 2) . ", indexLabel: \"THC: $rthc_cr\", color: \"#F04E37\" },";
            }
            if ($rcbc_cr != "NR*") {
                $datapoints .= "{ y: " . round($cbc_cr, 2) . ", indexLabel: \"CBC: $rcbc_cr\", color: \"#FAB49B\" },";
            }
            if ($rcbg_cr != "NR*") {
                $datapoints .= "{ y: " . round($cbg_cr, 2) . ", indexLabel: \"CBG: $rcbg_cr\", color: \"#C8DC69\" },";
            }
            if ($rcbn_cr != "NR*") {
                $datapoints .= "{ y: " . round($cbn_cr, 2) . ", indexLabel: \"CBN: $rcbn_cr\", color: \"#2BB673\" },";
            }
            if ($rthcv_cr != "NR*") {
                $datapoints .= "{ y: " . round($thcv_cr, 2) . ", indexLabel: \"THCV: $rthcv_cr\", color: \"#1D9AD6\" },";
            }
            if ($rcbdv_cr != "NR*") {
                $datapoints .= "{ y: " . round($cbdv_cr, 2) . ", indexLabel: \"CBDV: $rcbdv_cr\", color: \"#91D9F8\" },";
            }
        }        
    }
    
    if ($testtype == "Residual Solvents") {
        global $butane, $hexane, $benzene, $heptane, $toluene, $xylene, $rbutane, $rhexane, $rbenzene, $rheptane, $rtoluene, $rxylene;
               
        if ($rbutane != "NR*") {
            $datapoints .= "{ y: " . round($butane, 2). ", indexLabel: \"Butane: $rbutane\", color: \"#F04E37\" },";
        }
        if ($rhexane != "NR*") {
            $datapoints .= "{ y: " . round($hexane, 2). ", indexLabel: \"Hexane: $rhexane\", color: \"#C8DC69\" },";
        }        
        if ($rbenzene != "NR*") {
            $datapoints .= "{ y: " . round($benzene, 2). ", indexLabel: \"Benzene: $rbenzene\", color: \"#F79552\" },";
        }
        if ($rheptane != "NR*") {
            $datapoints .= "{ y: " . round($heptane, 2). ", indexLabel: \"Heptane: $rheptane\", color: \"#FAB49B\" },";
        }
        if ($rtoluene != "NR*") {
            $datapoints .= "{ y: " . round($toluene, 2). ", indexLabel: \"Toluene: $rtoluene\", color: \"#2BB673\" },";
        }
        if ($rxylene != "NR*") {
            $datapoints .= "{ y: " . round($xylene, 2). ", indexLabel: \"Xylene: $rxylene\", color: \"#1D9AD6\" },";
        }        
    }
    
    if ($testtype == "Terpenes") {
        global $alphapinene, $betapinene, $myrcene, $limonene, $terpinolene, $linalool, $alphaterpineol, $betacaryophyllene, $humulene, $cisocimene, $ralphapinene, $rbetapinene, $rmyrcene, $rlimonene, $rterpinolene, $rlinalool, $ralphaterpineol, $rbetacaryophyllene, $rhumulene, $rcisocimene;
            
        if ($ralphapinene != "NR*") {
            $datapoints .= "{ y: " . round($alphapinene, 2). ", indexLabel: \"a-pinene: $ralphapinene\", color: \"#9EB169\" },";
        }
        if ($rbetapinene != "NR*") {
            $datapoints .= "{ y: " . round($betapinene, 2). ", indexLabel: \"B-pinene: $rbetapinene\", color: \"#D3BD2A\" },";
        }
        if ($rmyrcene != "NR*") {
            $datapoints .= "{ y: " . round($myrcene, 2). ", indexLabel: \"Myrcene: $rmyrcene\", color: \"#FFC709\" },";
        }
        if ($rlimonene != "NR*") {
            $datapoints .= "{ y: " . round($limonene, 2). ", indexLabel: \"Limonene: $rlimonene\", color: \"#FFF460\" },";
        }
        if ($rterpinolene != "NR*") {
            $datapoints .= "{ y: " . round($terpinolene, 2). ", indexLabel: \"Terpinolene: $rterpinolene\", color: \"#DDD7D5\" },";
        }
        if ($rlinalool != "NR*") {
            $datapoints .= "{ y: " . round($linalool, 2). ", indexLabel: \"Linalool: $rlinalool\", color: \"#BBB7DC\" },";
        }
        if ($ralphaterpineol != "NR*") {
            $datapoints .= "{ y: " . round($alphaterpineol, 2). ", indexLabel: \"a-terpineol: $ralphaterpineol\", color: \"#B5A2C3\" },";
        }
        if ($rbetacaryophyllene != "NR*") {
            $datapoints .= "{ y: " . round($betacaryophyllene, 2). ", indexLabel: \"B-caryophyllene: $rbetacaryophyllene\", color: \"#9E76B4\" },";
        }
        if ($rhumulene != "NR*") {
            $datapoints .= "{ y: " . round($humulene, 2). ", indexLabel: \"Humulene: $rhumulene\", color: \"#6E2B8D\" },";
        }
        if ($rcisocimene != "NR*") {
            $datapoints .= "{ y: " . round($cisocimene, 2). ", indexLabel: \"Cis-ocimene: $rcisocimene\", color: \"#6E2B8D\" },";
        }       
    }
    
    $datapoints = rtrim($datapoints, ",");
        
    $datapoints .= " ]";

$str = "
<script>

window.onload = function () {
    var chart = new CanvasJS.Chart(\"chart_div\",
    {
        data: [
        {
            type: \"pie\",
            showInLegend: false,
            legendText: \"{indexLabel}\",
            indexLabelPlacement: \"inside\",
            indexLabelFontColor: \"black\",
            indexLabelFontFamily: \"courier\",
            indexLabelFontSize: 12,
           $datapoints
        }
        ]
    });
    chart.render();
}
</script>
";

return $str;
}



function getMonthByNumber($monthStr) {
$m = trim($monthStr);
switch ($m) {
    case "1":
        $m = "Jan";
        break;
    case "2":
        $m = "Feb";
        break;
    case "3":
        $m = "mar";
        break;
    case "4":
        $m = "Apr";
        break;
    case "5":
        $m = "May";
        break;
    case "6":
        $m = "Jun";
        break;
    case "7":
        $m = "Jul";
        break;
    case "8":
        $m = "Aug";
        break;
    case "9":
        $m = "Sep";
        break;
    case "10":
        $m = "Oct";
        break;
    case "11":
        $m = "Nov";
        break;
    case "12":
        $m = "Dec";
        break;
    default:
        break;
}
return $m;
}

?>

<?php 

include "includes.php";

logincheck();

$arr = array();
$arr2 = array();
$instrument = "";
$instrumentname = "";
$compoundname = "";
$compoundtype = "";
$mintime = "";
$maxtime = "";
$x = "";

if (isset($_GET["del"])) {
    $compoundname = $_GET["c"];
    $instrumentname = $_GET["i"];
    $sql = "update compounds set archived = 'true' where Instrument = :instrumentname and Compound = :compoundname";    
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':instrumentname'=>$instrumentname, ':compoundname'=>$compoundname));   
    die();
}

if (isset($_GET["add"])) {
    $compoundname = $_GET["c"];
    $instrumentname = $_GET["i"];
    $compoundtype = $_GET["ct"];
    $mintime = $_GET["min"];
    $maxtime = $_GET["max"];
    $x = $dbconn->query("select count(*) from compounds where Instrument = '$instrumentname' and compound = '$compoundname' and (archived <> 'true' or archived = '' or archived is null)")->fetchColumn();
    if ($x > 0) {
        $sql = "update compounds set MinRetWindow = :mintime, MaxRetWindow = :maxtime where Instrument = :instrumentname and Compound = :compoundname and (archived <> 'true' or archived = '' or archived is null)";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':mintime'=>$mintime, ':maxtime'=>$maxtime, ':instrumentname'=>$instrumentname, ':compoundname'=>$compoundname));
    }
    else
    {
    
    $sql = "insert into compounds (Compound, CompoundType, Instrument, MinRetWindow, MaxRetWindow) values (:compoundname, :compoundtype, :instrumentname, :mintime, :maxtime)";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':compoundname'=>$compoundname, ':compoundtype'=>$compoundtype, ':instrumentname'=>$instrumentname, ':mintime'=>$mintime, ':maxtime'=>$maxtime));
    }
   
    
die();
}

if (isset($_GET["ct"]) && !isset($_GET["min"])) {
    $instrumentname = $_GET["i"];
    $compoundtype = $_GET["ct"];
    $sql = "select Compound from compounds where Instrument = '$instrumentname' and CompoundType = '$compoundtype' and (archived <> 'true' or archived = '' or archived is null) order by MinRetWindow";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
            array_push($arr, $row["Compound"]);
        }
        
    }
    echo json_encode($arr);
    die();
}

if (isset($_GET["gt"])) {
    
    $compoundname = $_GET["c"];
    $instrument = $_GET["i"];
    
    $sql = "select * from compounds where Instrument = '$instrument' and Compound = '$compoundname' and (archived <> 'true' or archived = '' or archived is null)";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch()) {
            $mintime = $row["MinRetWindow"];
            $maxtime = $row["MaxRetWindow"];            
            $arr["rtmintime"]=$mintime;
            $arr["rtmaxtime"]=$maxtime;
        }       
    } 
    
echo json_encode($arr); 
die();
} 

if (isset($_GET["ctv"])) {
        
    $instrument = $_GET["i"];
    $compoundtype = $_GET["ctv"];

    $sql = "select * from compounds where Instrument = :instrument and CompoundType = :compoundtype and (archived <> 'true' or archived = '' or archived is null) order by MinRetWindow";
    $stmt = $dbconn->prepare($sql);
    if ($stmt->execute(array(':instrument'=>$instrument, ':compoundtype'=>$compoundtype))) {
        while ($row = $stmt->fetch()) {
            $compoundname = $row["Compound"];          
            array_push($arr, $compoundname);
        };      
    }    
    echo json_encode($arr);
    die();
}

if (isset($_GET["u"])) {  
    $min = $_GET["min"];
    $max = $_GET["max"];
    $instrumentname = $_GET["i"];
    $compoundname = $_GET["c"];
        
    $sql = "update compounds set MinRetWindow = :min, MaxRetWindow = :max where Instrument = :instrumentname and Compound = :compoundname and (archived <> 'true' or archived = '' or archived is null)";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute(array(':min'=>$min, ':max'=>$max, ':instrumentname'=>$instrumentname, ':compoundname'=>$compoundname));

    die();    
}

?>
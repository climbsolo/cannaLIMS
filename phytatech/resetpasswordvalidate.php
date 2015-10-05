<?php

include "includes.php";

$cannacashguid = "";
$resetpasswordid = "";
if (isset($_POST["resetpasswordid"])) {
    $resetpasswordid = $_POST["resetpasswordid"];
}

$newpw = "";
if (isset($_POST["pw"])) {
    $newpw = $_POST["pw"];
}

if (strlen($newpw) < 6) {
    echo "Password must be at least 6 characters in length.";
    die();
}

$password = md5($newpw . $salt);

$sql = "Select * from tblusers where resetpasswordid = :resetpasswordid"; // Need to add the timeout check against the UNIX timestamp

$stmt = $dbconn->prepare($sql);
if($stmt->execute(array(':resetpasswordid'=>$resetpasswordid))) {
    while ($row = $stmt->fetch()) {
        $user_id = $row["user_id"];
        //$userrole = $row["userrole"];
        $email = $row["email"];
        $first_name = $row["first_name"];
        $last_name = $row["last_name"];
        $fullname = $first_name . " " . $last_name;
    }
}

if (strlen($user_id) < 1) {
    echo "Sorry, this request cannot be fulfilled. Please check that this is not an old request.";
    die();
}

$sql2 = "update tblusers set password = :password where user_id = :user_id";
$stmt2 = $dbconn->prepare($sql2);
$stmt2->execute(array(':password'=>$password, ':user_id'=>$user_id));
                
$sql3 = "update tblusers set resetpasswordid = '' where user_id = :user_id";    
$stmt3 = $dbconn->prepare($sql3);
$stmt3->execute(array(':user_id'=>$user_id));

$_SESSION["luid"] = $user_id;
//$_SESSION["luserrole"] = $userrole;
$_SESSION["lemail"] = $email;
$_SESSION["lfullname"] = $fullname;


echo "success";

?>
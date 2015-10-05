<?php 

include "includes.php";

$str = "failure";

$username = $_POST["username"];
$pw = $_POST["password"];
$url = $_POST["url"];

$password = md5($pw . $salt);

$sql = "select user_id, role from tblusers where user_name = :username and password = :password";
$stmt = $dbconn->prepare($sql);
if ($stmt->execute(array(':username'=>$username, ':password'=>$password))) {
    while ($row = $stmt->fetch()) { 
        $_SESSION["luid"] = $row["user_id"];
        $_SESSION["role"] = $row["role"];
        $_SESSION["url"] = $url;
        $now = time();
        $_SESSION['discard_after'] = $now + $session_timeout;
        $str = "success";
    }
}

echo $str;


?>
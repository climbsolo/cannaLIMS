<?php

session_start(); 

$url = "";

if (isset($_SESSION["cluid"])) {
    $url = "customerlogout.php";
}

if (isset($_SESSION["url"])) {
    $url = $_SESSION["url"];
}


session_unset();     
session_destroy();


if (strlen($url) < 1) {
    header('Location: ./');
    die();
}

echo "<script>window.top.location.href = '$url'</script>";


?>
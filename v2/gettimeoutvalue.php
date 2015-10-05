<?php 

session_start();

$discard_after = time();

if (isset($_SESSION["discard_after"])) {
    $discard_after = $_SESSION["discard_after"];
}


echo $discard_after;

?>
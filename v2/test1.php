<?php 

include "includes.php";

$_SESSION["test"] = "before";
$_SESSION["test1"] = "before1";

echo $_SESSION["test"] . "<br />";

session_write_close();

for ($x = 0; $x <= 2; $x++) {
   sleep(2);

} 

session_start();

$_SESSION["test"] = "after";
echo $_SESSION["test"] . "<br />";

?>
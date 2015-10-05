<?php

$str = "wkhtmltopdf -s Letter -T 0 -L 0 -B 0 -R 0 --zoom 1.33 --load-error-handling ignore c:\\!web\\cannalims\\v2\\reports\\temp\\172-Terpenes.html  c:\\!web\\cannalims\\v2\\reports\\temp\\172-Terpenes.pdf";

$r =  system($str, $retval);

//$r = shell_exec($str);

//$last_line = system('dir', $retval);

//echo $retval;


echo ("<br />R: " . $retval);


?>

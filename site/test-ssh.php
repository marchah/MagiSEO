
<h1>Test SSH</h1>
<?php
if (!function_exists("ssh2_connect"))
	echo "marche pas";
else
	echo "marche";
	$session=ssh2_connect('192.168.80.6'); 
var_dump($session);
?>
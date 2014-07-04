<?php
include('Net/SSH2.php');
include('Crypt/RSA.php');

 $ssh = new Net_SSH2('37.187.16.124');
 if (!$ssh->login('marcha', '9zB46LmU5c')) {
 exit('Login Failed');
 }

 echo $ssh->exec('pwd');
echo $ssh->exec('ls -la');
?>
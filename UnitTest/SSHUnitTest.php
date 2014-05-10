<h1>BEGIN SSH Unit Test</h1>
<?php
if (!set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/phpseclib'))
	exit('############### set_include_path() failed ###################<br />');
	
require_once('../site/PHPseclib/Net/SSH2.php');


set_error_handler('errorHandler', E_USER_NOTICE);

$ipServerSSH = '192.168.234.136';
$user = 'marcha';
$login = 'totoauzoo';
$pathFileSSHConf = '/etc/ssh/sshd_config';
$pathFileSSHConfSave = '/etc/ssh/sshd_config_save';


echo "############### SSH CONNECTION TO $ipServerSSH START ###################<br />";
$ssh = new Net_SSH2($ipServerSSH);
echo "############### SSH CONNECTION TO $ipServerSSH OK ###################<br />";
echo "<br />############### SSH AUTHENTIFICATION TO $ipServerSSH START ###################<br />";
if (!$ssh->login($user, $login))
    exit("############### SSH AUTHENTIFICATION TO $ipServerSSH FAILED ###################<br />");
echo "############### SSH AUTHENTIFICATION TO $ipServerSSH OK ###################<br />";
echo "<br />############### SSH TEST BASIC COMMANDE TO $ipServerSSH START ###################<br />";
echo 'pwd: ' . $ssh->exec('pwd') . '<br />';
echo 'ls: ' . $ssh->exec('ls') . '<br />';
echo 'cd ..; ls -a: ' . $ssh->exec('cd ..; ls -a') . '<br />';
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";
echo "############### SSH TEST BASIC COMMANDE TO $ipServerSSH OK ###################<br />";
/*echo "<br />############### CONFIGURE OS START ###################<br />";
echo "#### INSTALL SUDO START ###<br />";
$ssh->write("su -c \"apt-get install -y sudo\"\n");
$ssh->setTimeout(10);
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";
echo "#### INSTALL SUDO OK ###<br />";
echo "############### CONFIGURE OS OK ###################<br />";
*/
echo "<br />############### SECURE SSH START ###################<br />";
echo "#### COPY FILE CONFIG SSH START ###<br />";
$ssh->write("su -c \"cp $pathFileSSHConf $pathFileSSHConfSave\"\n");
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";
echo 'ls /etc/ssh/sshd_config*: ' . $ssh->exec('ls /etc/ssh/sshd_config*') . '<br />';
echo "#### COPY FILE CONFIG SSH OK ###<br />";
echo "<br />############### SECURE SSH OK ###################<br />";


echo "<br />############### PUT ORIGINAL SSH CONF START ###################<br />";
echo "#### PUT ORIGINAL FILE CONFIG SSH START ###<br />";
$ssh->write("su -c \"mv $pathFileSSHConfSave $pathFileSSHConf\"\n");
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";
echo 'ls /etc/ssh/sshd_config*: ' . $ssh->exec('ls /etc/ssh/sshd_config*') . ' <b>BUG save file ssh config often still written but in fact it has been deleted !!!!</b><br />';
echo 'ls /etc/ssh/sshd_config*: ' . $ssh->exec('ls /etc/ssh/sshd_config*') . ' <b>Now it isn\'t, why???<b/><br />';
echo "#### PUT ORIGINAL FILE CONFIG SSH OK ###<br />";
echo "<br />############### PUT ORIGINAL SSH CONF OK ###################<br />";



/*echo "<br />############### UNCONFIGURE OS START ###################<br />";
echo "<br />#### REMOVE SUDO START ###<br />";
$ssh->write("su -c \"apt-get remove -y sudo\"\n");
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";
echo "#### REMOVE SUDO OK ###<br />";*/
//echo $ssh->read('/.*@.*[$|#]|.*[P|p]assword.*/', NET_SSH2_READ_REGEX) . "<br />";
//echo 'su -c "apt-get install -y sudo": ' . $ssh->exec('su -c "apt-get install -y sudo"') . "<br />";
//echo "############### UNCONFIGURE OS OK ###################<br />";

function errorHandler($errno, $errstr, $errfile, $errline) {
	echo "PHPseclib internal error: "
    . "errno=". $errno .", "
	. "errstr=". $errstr .", "
	. "errfile". $errfile .", "
	. "errline". $errline .", ";
}

?>
<h1>END SSH Unit Test</h1>
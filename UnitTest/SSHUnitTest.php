<h1>BEGIN SSH Unit Test</h1>
<?php
if (!set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/phpseclib'))
	exit('############### set_include_path() failed ###################<br />');
	
require_once('../site/PHPseclib/Net/SSH2.php');
require_once('../site/PHPseclib/Net/SFTP.php');


set_error_handler('errorHandler', E_USER_NOTICE);

$ipServerSSH = '192.168.234.136';
$user = 'marcha';
$login = 'totoauzoo';
$pathFileSSHConf = '/etc/ssh/sshd_config';
$pathFileSSHConfSave = '/etc/ssh/sshd_config_save';

$PATH_DIR_CONFIG_SSH= "/etc/ssh";
$PATH_FILE_CONFIG_SSH= $PATH_DIR_CONFIG_SSH . "/sshd_config";
$PATH_FILE_CONFIG_SSH_SAVE= $PATH_FILE_CONFIG_SSH . ".save";
$PATH_DIR_KEY_SSH="~/.ssh";
$PATH_FILE_PRIVATE_KEY_SSH= $PATH_DIR_KEY_SSH . "/id_server";
$PATH_FILE_PUBLIC_KEY_SSH= $PATH_FILE_PRIVATE_KEY_SSH . ".pub";
$SSH_PORT="9876";
$SSH_PROTOCOL="33333";
$LOGIN_GRACE_TIME="20";
$MAX_AUTH_TRIES="1";
$MAX_STARTUPS="2";
$GROUP_SSH_ALLOW="sshusers";

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

echo "<br />############### SECURE SSH START ###################<br />";
echo "#### COPY FILE CONFIG SSH START ###<br />";
$ssh->write("su -c \"cp $PATH_FILE_CONFIG_SSH $PATH_FILE_CONFIG_SSH_SAVE\"\n");
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";
echo 'ls '. $PATH_FILE_CONFIG_SSH .'*: ' . $ssh->exec('ls '. $PATH_FILE_CONFIG_SSH .'*') . '<br />';
echo "#### COPY FILE CONFIG SSH OK ###<br />";
echo "<br />#### GENERATE SSH KEY START ###<br />";
$ssh->exec('mkdir '. $PATH_DIR_KEY_SSH);
$ssh->exec('chmod 0700 '. $PATH_DIR_KEY_SSH);
$ssh->exec("ssh-keygen -t dsa -f ". $PATH_FILE_PRIVATE_KEY_SSH ." -N ''");
if (!file_put_contents('../site/SSHKeys/id_server_'. $ipServerSSH, $ssh->exec("cat ". $PATH_FILE_PRIVATE_KEY_SSH)))
	exit('############### DOWNLOAD KEY SSH FILE FAILED ###################<br />');
//$ssh->exec('rm -f '. $PATH_FILE_PUBLIC_KEY_SSH);
$ssh->exec('chmod 0600 '. $PATH_DIR_KEY_SSH .'/*');
echo "#### GENERATE SSH KEY OK ###<br />";
echo "<br />#### CONFIGURE SSH START ###<br />";
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";
$ssh->write("su -c \"/usr/sbin/addgroup $GROUP_SSH_ALLOW\"\n");
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";
$ssh->write("su -c \"/usr/sbin/usermod -a -G $GROUP_SSH_ALLOW $user\"\n");
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";

//$ssh->write("su -c \"sed -i 's/^Port [0-9][0-9]*$/Port $SSH_PORT/' $PATH_FILE_CONFIG_SSH\"\n");

$ssh->write("su -c \"sed -i 's/^Port [0-9][0-9]*$/Port $SSH_PORT/' $PATH_FILE_CONFIG_SSH; "
			//."sed -i 's/HostKey /etc/ssh/ssh_host_dsa_key / / ;"
			."sed -i 's/^Protocol [0-9][0-9]*$/Protocol $SSH_PROTOCOL/' $PATH_FILE_CONFIG_SSH; "
			."sed -i 's/^PermitRootLogin [a-zA-Z-][a-zA-Z-]*$/PermitRootLogin no/' $PATH_FILE_CONFIG_SSH; "
			."sed -i 's/^LoginGraceTime [0-9][0-9]*$/LoginGraceTime $LOGIN_GRACE_TIME/' $PATH_FILE_CONFIG_SSH; "
			."sed -i 's/^MaxAuthTries [0-9][0-9]*$/MaxAuthTries $MAX_AUTH_TRIES/' $PATH_FILE_CONFIG_SSH; "
			."sed -i 's/^RSAAuthentication [a-zA-Z][a-zA-Z]*$/RSAAuthentication no/' $PATH_FILE_CONFIG_SSH; "
			."sed -i 's/^UsePAM [a-zA-Z][a-zA-Z]*$/UsePAM no/' $PATH_FILE_CONFIG_SSH; "
			."sed -i 's/^KerberosAuthentication [a-zA-Z][a-zA-Z]*$/KerberosAuthentication no/' $PATH_FILE_CONFIG_SSH; "
			."sed -i 's/^GSSAPIAuthentication [a-zA-Z][a-zA-Z]*$/GSSAPIAuthentication no/' $PATH_FILE_CONFIG_SSH; "
			/*."sed -i 's/^PasswordAuthentication [a-zA-Z][a-zA-Z]*$/PasswordAuthentication no/' $PATH_FILE_CONFIG_SSH; "*/
			."sed -i 's/^#MaxStartups [0-9:][0-9:]*$/MaxStartups $MAX_STARTUPS/' $PATH_FILE_CONFIG_SSH; "
			."sed -i 's/^MaxStartups [0-9:][0-9:]*$/MaxStartups $MAX_STARTUPS/' $PATH_FILE_CONFIG_SSH;\"\n");
			//." echo \"AllowGroups $GROUP_SSH_ALLOW\" >> $PATH_FILE_CONFIG_SSH;\"\n");

echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";
echo $ssh->exec("diff $PATH_FILE_CONFIG_SSH $PATH_FILE_CONFIG_SSH_SAVE") . "<br />";

//ssh-keygen -H -f ~/.ssh/known_hosts	ON S'EN FOUT
//rm ~/.ssh/known_hosts.old				ON S'EN FOUT

$ssh->write("su -c \"/etc/init.d/ssh restart\"\n");
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";
echo "#### CONFIGURE SSH OK ###<br />";
echo "<br />############### SECURE SSH OK ###################<br />";

echo "<br />############### DISECURE SSH START ###################<br />";
echo "#### DISCONFIGURE SSH START ###<br />";
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";
$ssh->write("su -c \"/usr/sbin/delgroup $GROUP_SSH_ALLOW\"\n");
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";
$ssh->write("su -c \"/etc/init.d/ssh restart\"\n");
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";
echo "#### DISCONFIGURE SSH OK ###<br />";
echo "<br />#### REMOVE SSH KEY START ###<br />";
$ssh->write("rm -rf $PATH_DIR_KEY_SSH \n");
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";
echo "#### REMOVE SSH KEY OK ###<br />";
echo "<br />#### PUT ORIGINAL FILE CONFIG SSH START ###<br />";
//echo $ssh->write("ls -la\n") . "<br />";
//echo "su -c \"mv $PATH_FILE_CONFIG_SSH_SAVE $PATH_FILE_CONFIG_SSH\"\n" . "<br />";
//return ;
$ssh->write("su -c \"mv $PATH_FILE_CONFIG_SSH_SAVE $PATH_FILE_CONFIG_SSH\"\n");
return ;
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";

echo 'ls '. $PATH_FILE_CONFIG_SSH .'*: ' . $ssh->exec('ls '. $PATH_FILE_CONFIG_SSH .'*') . ' <b>BUG save file ssh config often still written but in fact it has been deleted !!!!</b><br />';
echo 'ls '. $PATH_FILE_CONFIG_SSH .'*: ' . $ssh->exec('ls '. $PATH_FILE_CONFIG_SSH .'*') . ' <b>Now it isn\'t, why???<b/><br />';
echo "#### PUT ORIGINAL FILE CONFIG SSH OK ###<br />";
echo "<br />############### DISECURE SSH OK ###################<br />";



/*echo "<br />############### CONFIGURE OS START ###################<br />";
echo "#### INSTALL SUDO START ###<br />";
$ssh->write("su -c \"apt-get install -y sudo\"\n");
$ssh->setTimeout(10);
echo $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX) ."<br />";
echo "#### INSTALL SUDO OK ###<br />";
echo "############### CONFIGURE OS OK ###################<br />";
*/


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
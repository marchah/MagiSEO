#!/bin/bash

su -c "aptitude remove -y apache2 php5 php5-mysql libapache2-mod-php5 virtualBox";
su -c "aptitude autoremove -y";

su -c "/usr/sbin/delgroup sshusers";
su -c "rm -rf ~/.ssh";
su -c "mv /etc/ssh/sshd_config.save /etc/ssh/sshd_config";
su -c "/etc/init.d/ssh restart";

rm -r ScriptServer;
rm ScriptServerSFTP.tar;
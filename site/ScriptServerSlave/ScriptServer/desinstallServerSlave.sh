#!/bin/bash

su -c "apt-get remove -y apache2 php5 php5-mysql libapache2-mod-php5 virtualBox";
su -c "apt-get autoremove -y";

su -c "/usr/sbin/delgroup sshusers";
su -c "rm -rf ~/.ssh";
su -c "mv /etc/ssh/sshd_config.save /etc/ssh/sshd_config";
su -c "/etc/init.d/ssh restart";
su -c "service ssh restart";

rm -r ScriptServer;

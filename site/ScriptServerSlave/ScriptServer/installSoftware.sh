#!/bin/bash

su -c "apt-get install -y aptitude";
su -c "aptitude install -y apache2 php5 php5-mysql libapache2-mod-php5";
su -c "/etc/init.d/apache2 restart";
su -c "aptitude install -y virtualBox";
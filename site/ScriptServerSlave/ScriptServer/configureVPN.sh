#!/bin/bash


su -c "apt-get install -y openvpn";
su -c "nohup openvpn --config ./ScriptServer/client.ovpn > log.txt &";

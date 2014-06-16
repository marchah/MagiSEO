#!/bin/bash

VBoxManage createvm --name testvm --register;
VBoxManage modifyvm "testvm" --memory 1024 --acpi on --boot1 dvd --nic1 bridged --bridgeadapter1 eth0 --ostype Ubuntu
#vboxmanage modifyvm "testvm" --memory 768 #--vram 64 --acpi on --boot1 dvd -nic1
VBoxManage createvdi --filename ~/VirtualBox\ VMs/testvm/testvm-disk01.vdi --size 10000 
VBoxManage storagectl "testvm" --name "IDE Controller" --add ide
VBoxManage storageattach "testvm" --storagectl "IDE Controller" --port 0 --device 0 --type hdd --medium ~/VirtualBox\ VMs/testvm/testvm-disk01.vdi
VBoxManage storageattach "testvm" --storagectl "IDE Controller" --port 1 --device 0 --type dvddrive --medium /iso/ubuntu-12.04.1-server-i386.iso

VBoxHeadless --startvm "testvm" -e "TCP/Ports=4444" & #--vrde off &

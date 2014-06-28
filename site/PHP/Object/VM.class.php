<?php
@session_start();
class VM {
    private $_id;
    private $_idServer;
    private $_ip;
    private $_name;
    private $_RAM;
    private $_HDD;
    private $_state;
    private $_serverIP;
        
    public function __construct($id, $idServer, $ip, $name, $RAM, $HDD, $state) {
        $this->_id = $id;
        $this->_idServer = $idServer;
        $this->_ip = $ip;
        $this->_name = $name;
        $this->_RAM = $RAM;
        $this->_HDD = $HDD;
        $this->_state = $state;
    }
    
    public function getId()         {return $this->_id;}
    public function getIdServer()   {return $this->_idServer;}
    public function getIp()         {return $this->_ip;}
    public function getName()       {return $this->_name;}
    public function getRAM()        {return $this->_RAM;}
    public function getHDD()        {return $this->_HDD;}
    public function getState()      {return $this->_state;}
    public function getServerIP() {return $this->_serverIP;}

    public function setId($id)              {$this->_id = $id;}
    public function setIdServer($idServer)  {$this->_idServer = $idServer;}
    public function setIp($ip)              {$this->_ip = $ip;}
    public function setName($name)          {$this->_name = $name;}
    public function setRAM($RAM)            {$this->_RAM = $RAM;}
    public function setHDD($HDD)            {$this->_HDD = $HDD;}
    public function setState($state)        {$this->_state = $state;}
    public function setServerIP($serverIP) {$this->_serverIP = $serverIP;}
}
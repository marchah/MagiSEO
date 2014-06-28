<?php
@session_start();
class Server {

  private $_id;
  private $_IPV4;
 /* private $_name;
  private $_state;
  private $_isOn;*/
  private $_diskMaxSize;
  private $_diskCurrentSize;
  private $_nbMaxProc;
  private $_nbCurrentProc;
  private $_flashMax;
  private $_flashMaxCurrent;
  private $_username;
  private $_password;
  private $_keySSHPath;

    function __toString() {
        return "Server{id=$this->_id, IPV4=$this->_IPV4}";
    }

    public function __construct($data) {
	$this->_id = $data['id'];
	$this->_IPV4 = $data['IPV4'];
	/*$this->_name = $data['name'];
	$this->_state = $data['state'];
	$this->_isOn = $data['on_off'];*/
	$this->_diskMaxSize = $data['disk_max_size'];
	$this->_diskCurrentSize = $data['disk_current_size'];
	$this->_nbMaxProc = $data['nb_max_proc'];
	$this->_nbCurrentProc = $data['nb_current_proc'];
	$this->_flashMax = $data['flash_max_size'];
	$this->_flashMaxCurrent = $data['flash_current_size'];
	$this->_username = $data['username'];
	$this->_password = $data['password'];
        $this->_keySSHPath = $data['keysshpath'];
  }

    public function setIPV4($IPV4){$this->_IPV4 = $IPV4;}
    public function getIPV4(){return $this->_IPV4;}
    public function setDiskCurrentSize($diskCurrentSize) {$this->_diskCurrentSize = $diskCurrentSize;}
    public function getDiskCurrentSize() {return $this->_diskCurrentSize;}
    public function setFlashMaxSize($flashMaxSize) {$this->_flashMax = $flashMaxSize;}
    public function getFlashMaxSize() {return $this->_flashMax;}
    public function setDiskMaxSize($diskMaxSize) {$this->_diskMaxSize = $diskMaxSize;}
    public function getDiskMaxSize() {return $this->_diskMaxSize;}
    public function setFlashCurrentSize($flashCurrentSize) {$this->_flashMaxCurrent = $flashCurrentSize;}
    public function getFlashCurrentSize() {return $this->_flashMaxCurrent;}
    /*public function setIsOn($isOn) {$this->_isOn = $isOn;}
    public function getIsOn() {return $this->_isOn;}*/
    public function setId($id) {$this->_id = $id;}
    public function getId() {return $this->_id;}
    /*public function setName($name) {$this->_name = $name;}
    public function getName() {return $this->_name;}*/
    public function setNbCurrentProc($nbCurrentProc) {$this->_nbCurrentProc = $nbCurrentProc;}
    public function getNbCurrentProc() {return $this->_nbCurrentProc;}
    public function setNbMaxProc($nbMaxProc) {$this->_nbMaxProc = $nbMaxProc;}
    public function getNbMaxProc() {return $this->_nbMaxProc;}
    /*public function setState($state) {$this->_state = $state;}
    public function getState() {return $this->_state;}*/
    public function setUsername($username) {$this->_username = $username;}
    public function getUsername() {return $this->_username;}
    public function setPassword($password) {$this->_password = $password;}
    public function getPassword() {return $this->_password;}
    public function getKeySSHPath() {return $this->_keySSHPath;}
    public function setKeySSHPath($keySSHPath) {$this->_keySSHPath = $keySSHPath;}
}

?>
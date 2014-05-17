<?php

class User {
    private $_id;
    private $_login;
    private $_password;
    private $_firstName;
    private $_lastName;
    private $_email;
    private $_avatarPath;
    private $_dateLastConnection;
    
    
    public function __construct($id, $login, $password, $firstName, $lastName, $email, $avatarPath, $dateLastConnection) {
        $this->_id = $id;
        $this->_login = $login;
        $this->_password = $password;
        $this->_firstName = $firstName;
        $this->_lastName = $lastName;
        $this->_email = $email;
        $this->_avatarPath = $avatarPath;
        $this->_dateLastConnection = $dateLastConnection;
    }
    
    public function getId()                 {return $this->_id;}
    public function getLogin()              {return $this->_login;}
    public function getPassword()           {return $this->_password;}
    public function getFirstName()          {return $this->_firstName;}
    public function getLastName()           {return $this->_lastName;}
    public function getEmail()              {return $this->_email;}
    public function getAvatarPath()         {return $this->_avatarPath;}
    public function getDateLastConnection() {return $this->_dateLastConnection;}

    public function setId($id)                                  {$this->_id = $id;}
    public function setLogin($login)                            {$this->_login = $login;}
    public function setPassword($password)                      {$this->_password = $password;}
    public function setFirstName($firstName)                    {$this->_firstName = $firstName;}
    public function setLastName($lastName)                      {$this->_lastName = $lastName;}
    public function setEmail($email)                            {$this->_email = $email;}
    public function setAvatarPath($avatarPath)                  {$this->_avatarPath = $avatarPath;}
    public function setDateLastConnection($dateLastConnection)  {$this->_dateLastConnection = $dateLastConnection;}
}

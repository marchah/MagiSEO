<?php
@session_start();
class Report {

	private $_id;
	private $_idUser;
        private $_userLogin;
	private $_title;
	private $_description;
	private $_type;
        private $_typeName;
	private $_date;

	public function __construct($id, $idUser, $userLogin, $title, $description, $type, $typeName, $date) {
            $this->_id = $id;
            $this->_idUser = $idUser;
            $this->_userLogin = $userLogin;
            $this->_title = $title;
            $this->_description = $description;
            $this->_type = $type;
            $this->_typeName = $typeName;
            $this->_date = $date;
	}

        public function getId()             {return $this->_id;}
        public function getIdUser()         {return $this->_idUser;}
        public function getuserLogin()      {return $this->_userLogin;}
        public function getTitle()          {return $this->_title;}
        public function getDescription()    {return $this->_description;}
        public function getType()           {return $this->_type;}
        public function getTypeName()       {return $this->_typeName;}
        public function getDate()           {return $this->_date;}

        public function setId($id)                      {$this->_id = $id;}
        public function setIdUser($idUser)              {$this->_idUser = $idUser;}
        public function setuserLogin($userLogin)        {$this->_userLogin = userLogin;}
        public function setTitle($title)                {$this->_title = $title;}
        public function setDescription($description)    {$this->_description = $description;}
        public function setType($type)                  {$this->_type = $type;}
        public function setTypeName($typeName)          {$this->_typeName = $typeName;}
        public function setDate($date)                  {$this->_date = $date;}
        
    public function __toString() {
        return "Report {id= $this->_id, idUser=$this->_idUser, userLogin=$this->_userLogin, title=$this->_title, description=$this->_description, type=$this->_type, date=$this->_date}";
    }
}

?>
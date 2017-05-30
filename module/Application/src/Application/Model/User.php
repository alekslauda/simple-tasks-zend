<?php
namespace Application\Model;

class User
{
    public $id;
    public $name;
    public $email;
    public $password;
    
    function exchangeArray($data)
    {
        $this->id =(isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        
        if (isset($data["password"]))
        {
            $this->setPassword($data["password"]);
        }
    }
    
    public function setPassword($password)
    {
        $this->password = md5($password);
    }
    
    public function getId() {
        return $this->id;
    }
   
    public function getName() {
        return $this->name;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function getPassword($masked = false) {
        if($masked) {           
            return substr($this->password, 0, 3).str_repeat("*",6);
        }
        return $this->password;
    }
}



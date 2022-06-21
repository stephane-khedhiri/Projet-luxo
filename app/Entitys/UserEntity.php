<?php

namespace App\Entitys;

use Exception;

class UserEntity
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $first_name;
    /**
     * @var string
     */
    public $mail;
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    protected $date_at;
    /**
     * @var string
     */
    public $password;
    /**
     * @var array
     */
    protected $annoncements;


    public function __construct()
    {
        $this->annoncements = [];
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param int $id
     * @return self
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->first_name;

    }
    /**
     * @param string $firstName
     * @return self
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }
    /**
     * @param string $mail
     * @return self
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * @param string $username
     * @return self
     */
    public function setUsername($username){
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $format
     * @return string
     */
    public function getDateAt(string $format = 'Y-m-d')
    {

        return date_create($this->date_at)->format($format);
    }
    /**
     * @param string $date
     * @return self
     */
    public function setDateAT(string $date)
    {
        $this->date_at= $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * @param string $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    /**
     * @return string
     */
    public function getPasswordHash()
    {
        return password_hash($this->password,PASSWORD_DEFAULT);
    }

    /**
     * @return array
     */
    public function getAnnoncements() :array
    {
        return $this->annoncements;
    }
    /**
     * @param array $annoncements
     * @return $this
     */
    public function AddAnnoncements(array $annoncements) :self{

        for($i=0; $i < count($annoncements);$i++){
            $this->annoncements[$i] =  $annoncements[$i];
        }
        return $this;
    }
}
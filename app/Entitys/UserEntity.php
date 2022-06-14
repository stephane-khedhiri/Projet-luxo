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

    private const REGEX = [
        'text' => [
            'regex' => "/^(?=.{4,20}$)(?![_.-])(?!.*[_.-]{2})[a-zA-Z_-]+([^._-])$/i",
            'message' => [
                'require' => 'le champ %s est requier',
                'length' => 'le champ %s doit contenir plus 3 caractère !',
                'invalide' => 'le champ %s ne doit pas contenir des chiffre',
            ]
        ],
        'password' => [
            'regex'=> "/^(?=(?:.*[A-Z]){2,})(?=(?:.*[a-z]){2,})(?=(?:.*\d){2,})(?=(?:.*[!@#$%^&*()\-_=+{};:,<.>]){2,})(?!.*(.)\1{2})([A-Za-z0-9!@#$%^&*()\-_=+{};:,<.>]{12,20})$/i",
            'message' => [
                'require' => 'le champ mot de passe est requier',
                'invalide' => "le champ doit contenir :<br>
                    le mot de passe contient au moins 2 lettres majuscules!<br>
                    le mot de passe comporte au moins 2 lettres minuscules!<br>
                    le mot de passe comporte au moins 2 chiffres (0-9)!<br>
                    le mot de passe contient au moins 2 caractères spéciaux, du groupe !@#$%^&*()-_=+{};:,<.><br>
                    le mot de passe est composé de 12 à 20 caractères"
            ]
        ]
    ];

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
        if ($name == ""){
            throw  new Exception(sprintf(self::REGEX['text']['message']['require'], 'nom'));
        }elseif (strlen($name) < 4){
            throw  new Exception(sprintf(self::REGEX['text']['message']['length'], 'nom'));
        }elseif (!preg_match(self::REGEX['text']['regex'], $name)){
            throw new Exception(sprintf(self::REGEX['text']['message']['invalide'],'nom'));
        }
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
        if ($firstName == "") {
            throw  new Exception(sprintf(self::REGEX['text']['message']['require'],'prénom'));
        }elseif (strlen($firstName) < 4){
            throw  new Exception(sprintf(self::REGEX['text']['message']['length'], 'prénom'));
        }elseif (!preg_match(self::REGEX['text']['regex'], $firstName)){
            throw new Exception(sprintf(self::REGEX['text']['message']['invalide'], 'prénom'));
        }
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
        if ($mail == ""){
            throw  new Exception(sprintf(self::REGEX['text']['require'], 'email'));
        }elseif(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
            throw new Exception(sprintf(self::REGEX['text']['message']['invalide'], 'email'));
        }
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
        if($username ==""){
            throw new Exception(sprintf(self::REGEX['text']['message']['require'], 'utilisateur'));
        }elseif (strlen($username) < 4){
            throw new Exception(sprintf(self::REGEX['text']['message']['length'], 'utilisateur'));
        }elseif (!preg_match(self::REGEX['text']['regex'], $username)){
            throw new Exception(sprintf(self::REGEX['text']['message']['invalide'], 'utilisateur'));
        }
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
        if($password == ""){
            throw new Exception(self::REGEX['password']['message']['require']);
        }elseif(!preg_match(self::REGEX['password']['regex'], $password)){
            throw new Exception(self::REGEX['password']['message']['invalide']);
        }
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
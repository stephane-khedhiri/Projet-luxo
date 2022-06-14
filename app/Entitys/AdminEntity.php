<?php

namespace App\Entitys;

class AdminEntity {

    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;

    public function getUsername():?string{
        return $this->username;
    }

    public function getPassword():?string{
        return $this->password;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username):self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password):self
    {
        $this->password = $password;
        return $this;
    }
}
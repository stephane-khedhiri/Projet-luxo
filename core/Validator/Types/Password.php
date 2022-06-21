<?php

namespace Core\Validator\Types;

use Exception;

class Password
{
    /**
     * @var string
     */
    static protected $message = "%s n'est pas mot de passe valide";


    /**
     * check la valeur si c'est un email
     * @param string $value
     * @return void
     * @throws Exception
     */
    static public function Password(string $value , $key) {
        if(!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,16}$/', $value)){
            throw new Exception(sprintf(self::$message, $value));
        }
    }

}
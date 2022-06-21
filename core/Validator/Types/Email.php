<?php

namespace Core\Validator\Types;

use \Core\Validator\Types\InterfaceType;
use \Exception;

class Email
{
    /**
     * @var string
     */
    static protected $message = "%s n'est pas un email valide";


    /**
     * check la valeur si c'est un email
     * @param string $value
     * @return void
     * @throws Exception
     */
    static public function Email(string $value = '', $key) {
        if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
            throw new Exception(sprintf(self::$message, $value));
        }
    }
}
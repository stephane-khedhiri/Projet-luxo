<?php

namespace Core\Validator\Types;

use Exception;

class Lenght
{
    /**
     * @var string
     */
    static protected $message = "%s doit contenir entre %s et %s caractère !";


    /**
     * @param string $value
     * @param integer $min
     * @param integer $max
     * @return void
     * @throws Exception
     */
    static public function Lenght(string $value = '', string $key,int $min, int $max = 20){
        if (strlen($value) < $min || strlen($value > $max)){
            throw new Exception(sprintf(self::$message, $value, $min,$max));
        }
    }
}
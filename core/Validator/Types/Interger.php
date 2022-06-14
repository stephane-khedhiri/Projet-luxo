<?php

namespace Core\Validator\Types;

use Exception;

class Interger
{
    /**
     * @var string
     */
    static protected $message = "%s n'est pas un %s valide";


    /**
     * @param string $value
     * @param string $key
     * @return void
     * @throws Exception
     */
    static public function Interger(string $value, string $key){
        if (!preg_match("/[0-9]/gm", $value)){
            throw new Exception(sprintf(self::$message, $value, $key));
        }
    }
}
<?php

namespace Core\Validator\Types;

use Exception;

class Text
{
    static public $message = " %s n'est pas un %s valide";

    /**
     * @param string $value
     * @param string $key
     * @return void
     * @throws Exception
     */
    static public function Text(string $value, string $key){
        if(!preg_match("/[a-zA-Z_-]+([^._-])/", $value)){
            throw new Exception(sprintf(self::$message, $value, $key));
        }
    }
}
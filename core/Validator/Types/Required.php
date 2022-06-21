<?php

namespace Core\Validator\Types;

use Exception;

class Required
{
    /**
     * @var string
     */
    static protected $message = "le champ %s est obligatoire";

    /**
     * @param string $value
     * @param string $key
     * @return void
     * @throws Exception
     */
    static public function Required(string $value , string $key)
    {
        if(strlen($value)<= 0){
            throw new Exception(sprintf(self::$message, $key));
        }

    }
}
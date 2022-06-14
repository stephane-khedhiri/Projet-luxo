<?php

namespace Core\Validator\Types;

use Exception;

class Required
{
    static public $message = "le champ %s est obligatoire";

    static public function Required($value, $key)
    {
        if($value === ''){
            throw new Exception(sprintf(self::$message, $key));
        }
    }
}
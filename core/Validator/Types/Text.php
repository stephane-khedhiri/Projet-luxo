<?php

namespace Core\Validator\Types;

use Exception;

class Text implements InterfaceType
{
    public $message = " %s n'est pas un %s valide";
    public function Text($value, $key){
        if(!preg_match("/[a-zA-Z_-]+([^._-])/gm", $value)){
            throw new Exception(sprintf($this->message, $value, $key));
        }
    }

    public function Required($value)
    {
        if(empty($value)){
            throw new Exception();
        }
    }

    public function Length($value, $min, $max)
    {
        // TODO: Implement Length() method.
    }
}
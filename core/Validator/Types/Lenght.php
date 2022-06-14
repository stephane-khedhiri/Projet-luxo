<?php

namespace Core\Validator\Types;

use Exception;

class Lenght
{
    /**
     * @var string
     */
    protected $message = "%s doit contenir entre %s et %s caractère !";

    /**
     * @param $value
     * @param $min
     * @param $max
     * @return void
     * @throws Exception
     */
    public function lenght($value, $min, $max){
        if (strlen($value) < $min || strlen($value) > $max){
            throw new Exception(sprintf($this->message, $value, $min,$max));
        }
    }
}
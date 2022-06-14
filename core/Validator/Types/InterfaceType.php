<?php

namespace Core\Validator\Types;

interface InterfaceType
{
    public function Required($value);
    public function Length($value, $min, $max);
}
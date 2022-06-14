<?php

namespace Core\Validator;



class validator
{

    /**
     * @var array
     */
    public $validators = [];

    /**
     * @param array $inputs
     * @param array $rules
     * @return Validation
     */
    public function make(array $inputs, array $rules){
        $this->registerRules();
        $validation = new Validation($this, $inputs, $rules);
        return $validation;
    }


    protected  function registerRules(){
        $baseRules = [
            'Email' => \Core\Validator\Types\Email::class,
            'Text' => \Core\Validator\Types\Text::class,
            'Required' => \Core\Validator\Types\Required::class,
            'Lenght' => \Core\Validator\Types\Lenght::class,
            'Interger' => \Core\Validator\Types\Interger::class,
        ];
        foreach ($baseRules as $key => $rule){
            $this->validators[$key] = $rule;
        }
    }


}
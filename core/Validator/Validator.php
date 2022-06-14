<?php

namespace Core\Validator;



class validator
{

    public $validators = [];

    /**
     * @param $inputs
     * @param $rules
     * @return Validation
     */
    public function make($inputs, $rules){
        $this->registerRules();
        $validation = new Validation($this, $inputs, $rules);
        return $validation;
    }

    protected  function registerRules(){
        $baseRules = [
            'Email' => \Core\Validator\Types\Email::class,
            'Text' => \Core\Validator\Types\Text::class,
            'Required' => \Core\Validator\Types\Required::class,

        ];
        foreach ($baseRules as $key => $rule){
            $this->validators[$key] = $rule;
        }
    }


}
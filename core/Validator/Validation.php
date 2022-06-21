<?php

namespace Core\Validator;



use App\Entitys\AdminEntity;
use App\Entitys\AnnoncementEntity;
use App\Entitys\UserEntity;
use Exception;

class Validation
{
    /**
     * @var mixed
     */
    protected $validator;
    /**
     * @var array
     */
    protected $inputs = [];
    /**
     * @var array
     */
    private $rules = [];

    protected $errors=[];


    /**
     * @param validator $validator
     * @param array $inputs
     * @param array $rules
     */
    public function __construct(validator $validator, array $inputs, array $rules)
    {
        $this->validator = $validator;
        $this->inputs = $inputs;
        $this->rules = $rules;

    }

    /**
     * @return void
     * @throws Exception
     */
    public function validate (){
        foreach ($this->rules as $key => $value){

            $this->resoleveRule($value, $key,$this->getValue($key));
        }
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getRule($key){
        return $this->rules[ucfirst($key)];
    }


    /**
     * @param $rules
     * @param $key
     * @param string $value
     * @return void
     */
    protected function resoleveRule($rules, $key,string $value = ''){
        if (is_string($rules)){
            $rules = explode('|', $rules);
        }
        foreach ($rules as $i => $rule){
            $params = [];
            list($rulename, $params) = $this->parseRule($rule, $value, $key);
            if($rulename){
                try{
                call_user_func_array([$this->validator->validators[$rulename],$rulename ], $params);
                }catch (Exception $e){
                    if (!isset($this->errors[$e->getTrace()[2]['args'][1]])){
                        $this->errors[$e->getTrace()[2]['args'][1]] = ['key'=> $e->getTrace()[2]['args'][1],'message'=>$e->getMessage()];
                    }else{
                        if(!in_array($this->errors[$e->getTrace()[2]['args'][1]], $this->getErrors())){
                            $this->errors[$e->getTrace()[2]['args'][1]] = ['key'=> $e->getTrace()[2]['args'][1],'message'=>$e->getMessage()];
                        }
                    }
                }
            }
        }
    }

    /**
     * @param string $rule
     * @param string $value
     * @param string $key
     * @return array
     */
    protected function parseRule(string $rule, string $value, string $key):array
    {

        $params = [];
        $exp = explode(',', $rule);
        $rulename = ucfirst($exp[0]);
        if(isset($exp[1])){
            $arg = explode(':', $exp[1]);
           $params = array_merge(['value'=>$value, 'key' => $key], $arg);


        }else{
            $params = ['value' => $value, 'key' => $key];
        }
        return [$rulename, $params];
    }

    /**
     * @param string $key
     * @return mixed
     */
    private function getValue(string $key){
        if(isset($this->inputs[$key])){
            return $this->inputs[$key];
        }
        return '';
    }

    /**
     * recupere les donnes du formulaire à objet du type entity passe en paramétre
     * @param UserEntity|AnnoncementEntity|AdminEntity $entity
     * @return UserEntity | AnnoncementEntity | AdminEntity
     */
    public function getData($entity){
        $class = new $entity();
        foreach($this->inputs as $key => $value){
            $method = 'set'. ucfirst($key);
            if(method_exists($class, $method)){
                $class->$method(htmlspecialchars($this->inputs[$key]));
            }
        }
        return $class;
    }
    public function getErrors(){
        return $this->errors;
    }

}
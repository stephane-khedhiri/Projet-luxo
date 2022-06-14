<?php
namespace Core\HTML\Form;

use App\Entitys\AdminEntity;
use App\Entitys\AnnoncementEntity;
use App\Entitys\UserEntity;
use Exception;

/**
 * Class Form
 * Permet de générer un formulaire rapidement et simplement et convertir les donnes d'un formulaire a un objet type Entity
 */
class Form{

    /**
     * @var array Données utilisées par le formulaire
     */
    protected $data;


    public $labels;
    /**
     * @var string Tag utilisé pour entourer les champs
     */
    public $surround = 'div class="group-form"';

    /**
     * @param array $data Données utilisées par le formulaire
     */
    public function __construct($data = array()){
        $this->data = $data;
        $this->labels = [];
    }

    /**
     * @param $html string Code HTML à entourer
     * @return string
     */
    protected function surround($html){
        return "<{$this->surround}>{$html}</{$this->surround}>";
    }

    /**
     * @param $index string Index de la valeur à récupérer
     * @return string
     */
    protected function getValue($index){
        if(is_object($this->data)){
            return $this->data->$index;
        }
        return isset($this->data[$index]) ? $this->data[$index] : null;
    }

    /**
     * @param $name string
     * @param $label string
     * @param array $options
     * @return string
     */
    public function input($name, $label, $options = []){
        $type = isset($options['type']) ? $options['type'] : 'text';
        $label = '<label for = '.$name.'>' . $label . '</label>';
        if ($type === 'textarea') {
            $input = '<textarea name="' . $name . '">' . $this->getValue($name) . '</textarea>';
        } else {
            $input = '<input type="' . $type . '" name="' . $name . '" value="' . $this->getValue($name) .'">';
        }
        return $this->surround($label . $input);
    }
    public function option(string $name,$label,array $values)
    {
        $option = '';
        foreach ($values as $key => $value) {
            $option = $option . '<option value="' . $key . '"' . ($this->getValue($name) == $key ? 'selected' : '') . '>' . ucfirst($value) . '</option>';
        }
        $select = '<label for="' . $name . '">' . $label . '</label>
        <select name="' . $name . '">' . $option . '</select>';

        return $this->surround($select);
    }

    /**
     * @return string
     */
    public function submit(){
        return $this->surround('<button type="submit">Envoyer</button>');
    }

}
<?php

namespace App\Console\Command;

use App\Models\AdminModel as ModelAdmin;

class Admin
{
    private $ModelAdmin;

    public function __construct(array $options = null)
    {
        $this->ModelAdmin = new ModelAdmin();

    }
    public function run(array $datas)
    {
        $options = [ 'method'=>$datas[0],'username'=> $datas[1], 'password'=> $datas[2]];
        extract($options);

        if(method_exists($this->ModelAdmin, ucfirst($method))){
            try{
                $this->ModelAdmin->$method($username, $password);
                print 'nouveau administrateur' . PHP_EOL;
            }catch (\Exception $e){
                print 'Erreur : '. $e->getMessage();
            }
        }

    }
}
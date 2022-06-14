<?php

namespace App\Console\Command;

use App\Models\VisitorModel;
use App\Console\Command\Faker;

class visitors
{
    private $ModelVisitors;

    private $faker;

    public function __construct()
    {
        $this->ModelVisitors =new VisitorModel();
        $this->faker = new Faker();
    }
    public function run(array $datas)
    {
        $options = [ 'method'=>$datas[0],'count'=> (int)$datas[1]];
        extract($options);
        if(method_exists($this->ModelVisitors, ucfirst($method))){
            try{
                for($i = 0; $i< $count; $i++){
                    $date = $this->faker->generateDate();
                    $ip=$this->faker->generateIp();
                    $this->ModelVisitors->$method($ip, $date);
                }
            }catch (\Exception $e){
                print 'Erreur : '. $e->getMessage();
            }
        }

    }
}
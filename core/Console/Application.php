<?php

namespace Core\Console;

use Core\Console\Command\Admin;

class Application
{
    public $argv;

    public function __construct($argv){
        $this->argv = $argv;
    }

    public function run()
    {

        $name = "App\Console\Command\\".ucfirst($this->argv[0]);

        if (class_exists($name)) {
           $application = new $name();
           $application->run(array_slice($this->argv, 1));
        } else {
            print 'Commande n\'existe pas je vous invite d\'utiliseur help';
        }

    }
}
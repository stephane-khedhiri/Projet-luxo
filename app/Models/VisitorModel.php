<?php

namespace App\Models;

use Core\Database;
use Core\Models;

class VisitorModel extends Models
{

    protected $table = 'compter';
    // récupéré les visiteur par son ip
    public function init(){
        $query = $this->query("SELECT * FROM visitors WHERE ip = ?", [$_SERVER['REMOTE_ADDR']], true);
        if(!$query){
            $this->query('INSERT INTO visitors(ip, date) VALUES (?,?)', [$_SERVER['REMOTE_ADDR'], date('Y-m-d')]);
        }
    }
    public function add($ip, $date){
        $query =$this->db->prepare("INSERT INTO visitors(ip, date) VALUES (?,?)");
        $query->execute([$ip,$date]);
    }

    public function getVuesByDate($year = null): array{
        if($year){
            $query = $this->query("SELECT DATE_FORMAT(visitors.date, '%Y.%m') as date, count(visitors.ip) as vues
                FROM visitors WHERE DATE_FORMAT(visitors.date, '%Y') = ?
                GROUP BY DATE_FORMAT(visitors.date, '%Y.%m') DESC ;", [$year]);
        }else {
            $query = $this->db->prepare("SELECT DATE_FORMAT(visitors.date, '%Y.%m') as date, count(visitors.ip) as vues
                FROM visitors
                GROUP BY DATE_FORMAT(visitors.date, '%Y.%m') DESC 
                LIMIT 5;");
        }
        $statis = [];
        foreach($query as $data => $value){
            $statis = array_merge($statis,[$value->date=> (int)$value->vues]);
        }
        return $statis;
    }

    public function getVueUser($year = null): array{
        if($year){
            $query = $this->query("SELECT COUNT(id) as vues, DATE_FORMAT(Users.date_at, '%Y.%m') as date
                            FROM Users WHERE DATE_FORMAT(Users.date_at, '%Y') = ?
                            GROUP BY DATE_FORMAT(Users.date_at, '%Y.%m') DESC;", [$year]);

        }else{
        $query = $this->query("SELECT COUNT(id) as vues, DATE_FORMAT(Users.date_at, '%Y.%m') as date
                            FROM Users 
                            GROUP BY DATE_FORMAT(Users.date_at, '%Y.%m') DESC
                            LIMIT 5;");
        }
        $statis = [];
        foreach($query as $data => $value){
            $statis = array_merge($statis,[$value->date=> (int)$value->vues]);
        }
        return $statis;

    }

}
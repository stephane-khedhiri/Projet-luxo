<?php

namespace App\Console\Command;



use App\Models\UserModel as ModelUsers;
use App\Models\AnnoncementModel as ModelAnnoncements;
use App\Models\ImageModel as ModelImages;
use App\Entity\Users as EntityUser;
use App\Entity\AnnoncementEntity as EntityAnnoncement;
use App\Entity\Image as EntityImage;
use App\Controllers\FILE\Upload;
use DateTime;


class Faker
{
    protected $ModelUsers;
    protected $ModelAnnoncements;
    protected $ModelImages;
    protected $urls = [
        "https://cdn.pixabay.com/photo/2015/10/20/18/57/furniture-998265__480.jpg",
        "https://cdn.pixabay.com/photo/2017/03/22/17/39/kitchen-2165756__340.jpg",
        "https://cdn.pixabay.com/photo/2016/04/18/08/50/kitchen-1336160_960_720.jpg",
        "https://cdn.pixabay.com/photo/2016/04/18/08/50/kitchen-1336160_960_720.jpg",
        "https://cdn.pixabay.com/photo/2016/11/30/08/48/bedroom-1872196_960_720.jpg",

        ];

    public function __construct()
    {
        $this->ModelUsers = new ModelUsers();
        $this->ModelAnnoncements = new ModelAnnoncements();
        $this->ModelImages = new ModelImages();
        $this->users = [];
        $this->annoncements= [];
        $this->images = [];
    }

    public function run ($datas){

        $options = ['countUser' => $datas[0], 'countAnnoncement'=> $datas[1]];
        extract($options);
        $this->fakerUser($countUser);
        $this->fakerAnnoncement($countAnnoncement);
        $this->fakerImages();
        $result= '';
        if(!count($this->users)<=0){
            foreach ($this->users as $user){
                $idUser = $this->ModelUsers->CreateUser($user);
                $user->setId($idUser);
                foreach ($this->annoncements as $annoncement){



                    $annoncement->addImages($this->images);
                    $annoncement->setUser($user->getId());
                    foreach ($this->images as $image){
                        $dir_upload = ROOT .'publuc/image/' ;

                        $upload = new Upload($dir_upload, null,$annoncement->getTypeToString().'/'.$annoncement->getId());
                        $upload->scanDir();
                        $upload->uploadByUrl($this->urls[rand(0, 4)]);

                        $image->setName($upload->getName());
                        $image->setPath($upload->getPath());


                    }

                    $this->ModelAnnoncements->Add($annoncement);

                }
                $result .= 'mail : '.$user->getMail(). ' password : '.$user->getPassword() .PHP_EOL;
            }

            print $result;
        }
    }

    private function fakerUser($count){

        for ($u =0; $u < $count; $u++){
            $date = $this->generateDate();
            $birth = $this->generateBirth();
            $user = new EntityUser();
            $slug = rand(1,999);
            $user->setName('Users'. $slug);
            $user->setFirstName('Users'. $slug);
            $user->setBirth($birth);
            $user->setDateAt($date);
            $user->setMail('Users'.$slug.'@gmail.com');
            $user->setPassword('Users'. $slug);
            $this->users[$u] = $user;
        }
    }

    private function fakerAnnoncement($count){

            foreach ($this->users as $user){
                for ($a = 0; $a < $count; $a++){
                    $annoncement= new EntityAnnoncement();
                    $postal = rand(75001, 75020);
                    $categorie = rand(1,2);
                    $prix = $categorie == 1? rand(500, 1200):rand(150000, 250000);
                    $type = rand(1, 3);
                    $floor = rand(0,15);
                    $surface = rand(35,150);
                    $room = rand(1,4);
                    $slug = rand(0,999);
                    $a++;
                    $annoncement->setTitle('titre'.$slug);
                    $annoncement->setDescription('description'.$slug);
                    $annoncement->setCity('paris');
                    $annoncement->setZip($postal);
                    $annoncement->setPrice($prix);
                    $annoncement->setDate(date("d.m.Y"));
                    $annoncement->setDateAt(date("d.m.Y"));
                    $annoncement->setCategory($categorie);
                    $annoncement->setType($type);
                    $annoncement->setFloor($floor);
                    $annoncement->setSurface($surface);
                    $annoncement->setRoom($room);

                    $a--;
                    $this->annoncements[$a] = $annoncement;
                }
            }
    }

    private function fakerImages(){
        foreach ($this->annoncements as $annoncement){

            for ($i = 0; $i< 3; $i++ ){

                $image = new EntityImage();

                $this->images[$i] = $image;
            }
        }
    }

    public function generateBirth(){
        $d = rand(1,30);
        $m = rand(1,12);
        $y= rand(1994,2002);
        if($d<10){
            $d = 0 . $d;
        }
        if ($m<10){
            $m = 0 . $m;
        }
        return (string) $y. '-' .$m . '-' .$d;
    }

    public function generateDate()
    {
        $d = rand(1,30);
        $m = rand(1,12);
        $y= rand(date('Y')-5,date('Y'));
        if($d<10){
            $d = 0 . $d;
        }
        if ($m<10){
            $m = 0 . $m;
        }
        return (string) $y. '-' .$m . '-' .$d;
    }

    public function generateIp(){
        return rand(100,999) . '.' .rand(100,999).'.'.rand(0,9). '.'.rand(0,9);
    }
}
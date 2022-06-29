<?php
namespace App\Models;

use Core\Database;
use App\Entitys\AnnoncementEntity;
use App\Entitys\ImageEntity;
use Core\Models;
use Exception;
use \PDO;


class AnnoncementModel extends Models{


    protected $table = 'annoncements';
    // récupéré une annonce avec un id de l'annonce

    public function getAnnoncementById(int $id):AnnoncementModel{
        return $this->query("SELECT 
                            a.id, a.title, a.description, a.city, a.zip, a.price,
                            a.date, a.category, a.type, a.floor, a.surface, a.room,
                            i.name, i.path
                            user_id AS Users,
                            date_at AS dateAt
                            FROM annoncements AS a
                            JOIN annoncements_images AS ai ON ai.annoncement_id = a.id
                            JOIN images AS i ON i.id = ai.image_id
                            WHERE a.id = ?;
        ");

    }
    // récupéré des annonce avec l'id de createur
    public function getAnnoncementByUserId(int $id) :array{
        $query = $this->prepare("SELECT * FROM annoncements WHERE annoncements.Users = ?");
        $query->execute([$id]);
        $annoncements = $query->fetchAll(PDO::FETCH_CLASS, AnnoncementModel::class);
        foreach ($annoncements as $annoncement){
        $images = $this->images->getImagesByIdAnooncement($annoncement->getId());
        $annoncement->setImages($images);
        }
        return  $annoncements;
    }

    /**

     * @param bool $all
     * @param array|null $wheres
     * @param int|null $limit
     * @param string|null $order
     * @param int|null $currentPage
     * @return array | AnnoncementEntity
     */
    public function getAnnoncements(array $wheres = null, int $limit = null, string $order = null, bool $all = false ,int $currentPage = null){
        $where= "";
        if (is_array($wheres)){
            $param = [];
            foreach ($wheres as $sql => $attribute){
                $where .= $sql.' = ?';
                $param []= $attribute;

            }
        }else{
            $wheres ='';
            $param = null;
        }

        if(!is_null($order)){
            $order = " ORDER BY  $order";
        }else{
            $order='';
        }
        if(!is_null($limit)){
            $limits= " LIMIT $limit";
            if(!is_null($currentPage)){
                $offset = $limit * ($currentPage-1);
                $offset = " OFFSET $offset";
            }else{
                $offset ='';
            }
        }else{
            $limit ='';
        }


        $annoncements = $this->query("SELECT annoncements.category, annoncements.title, annoncements.date_at, annoncements.id, annoncements.city, annoncements.zip,
                        annoncements.description, annoncements.price
                        FROM annoncements WHERE $where $order $limits $offset
        ", $param,$all, AnnoncementEntity::class);
        if ($annoncements){

            foreach ($annoncements as $annoncement){
                $images = $this->query("SELECT i.id, i.name, i.path FROM images as i
                                INNER JOIN annoncements_images as ai ON ai.image_id = i.id
                                WHERE ai.annoncement_id = ?",
                [$annoncement->getId()], false , ImageEntity::class);
                $annoncement->AddImages($images);

            }
        }

        return $annoncements;
    }
    // récupéré les huit derniere annonce avec la categorie
    public function getAnnoncementsFormCategorie(int $categorie) {
        $query = $this->prepare("SELECT  i.name, a.titre, a.description, a.ville, a.postal, a.prix,  i.path  FROM annoncements AS a 
                    LEFT JOIN images AS i ON a.id = i.annoncementId 
                    GROUP BY annoncements.id
                    WHERE a.categorie = ?");
        $query->execute([$categorie]);
        $rows = $query->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, AnnoncementModel::class);


        return $rows;


        /*
        $query = $this->Bd->prepare("SELECT id,titre, description, ville, postal, prix, date,categorie, type, Users FROM annoncements WHERE categorie = ? ORDER BY date DESC LIMIT 8;");
        $query->execute([$categorie]);
        $annoncements = $query->fetchAll(PDO::FETCH_CLASS, AnnoncementController::class);
        foreach($annoncements as  $annoncements){
            $images =$this->images->getImagesByIdAnooncement($annoncements->getId());
            $annoncements->AddImages($images);
        }
        return $annoncements;
        */
    }
    // récupéré tout les annonces par categorie avec la pagination
    public function getAnnoncementsFormCategorieAll(int $categorie, int $currentPage, int $limit):array{

        $offset = $limit * ($currentPage -1);
        $query = $this->query("SELECT id, titre, description, ville, postal, prix, date,category, type, Users FROM annoncements WHERE category = $categorie LIMIT $limit OFFSET $offset;");
        $annoncements = $query->fetchAll(PDO::FETCH_CLASS, AnnoncementModel::class);
        var_dump($query->errorInfo());


        return $annoncements;
    }
    // récupéré le nombre total des annonces

    /**
     * @param int $categorie
     * @return int
     */
    public function getCountAnnoncement(array $wheres = null){
        $where = "";
        if(is_array($wheres)){
            foreach ($wheres as $sql => $attribute){
                $where .= $sql.' = ? ';
                $param []= $attribute;
            }
        }


        $count = $this->query("SELECT COUNT(id) as count FROM annoncements WHERE $where", $param,true);

        return (int)$count->count;
    }
    // supprimé une annonce avec l'id de l'annonce
    public function deleted(int $id, $userid){
        $query = $this->query("DELETE FROM annoncements WHERE id = ? AND user_id LIMIT 1", [$id, $userid]);
        if(!$query){
            throw new Exception("error est survenu veuillez ressayer!");
        }
    }
    // modifier une annonce avec l'id de l'annonce
    public function updateAnnoncementById(AnnoncementModel $annoncement): bool{
        $query = $this->prepare('UPDATE annoncements SET titre=?, description=?, ville= ?, postal=?, prix=?, date=?, categorie=?, type=? WHERE id=?');
        $result = $query->execute([
            $annoncement->getTitre(), $annoncement->getDescription(), $annoncement->getVille(), $annoncement->getPostal(),
            $annoncement->getPrix(), $annoncement->getDate(), $annoncement->getCategorie(), $annoncement->getType(), $annoncement->getId()
            ]);
        return $result;
    }
    // requepere les annonce avec l'id du createur
    public function getAnnoncementByUser(int $id):array{
        $query =$this->prepare('SELECT id,titre, description, ville, postal, prix, date,categorie, type, Users FROM annoncements WHERE Users = ? ORDER By date DESC');
        $query-> execute([$id]);
        $annoncements = $query->fetchAll(PDO::FETCH_CLASS, AnnoncementModel::class);
        foreach($annoncements as  $annoncement){
            $images =$this->images->getImagesByIdAnooncement((int)$annoncement->getId());
            $annoncement->AddImages($images);
        }
        return $annoncements;
    }
    // Ajouter une annonce
    public function getAnnoncement($id, $userid){
        $annoncement = $this->query("SELECT annoncements.category, annoncements.title, annoncements.date_at, annoncements.id, annoncements.city, annoncements.zip,
                        annoncements.description, annoncements.price, annoncements.user_id FROM annoncements WHERE id = ? AND user_id = ?",
        [$id, $userid],true, AnnoncementEntity::class);
        if ($annoncement){

                $images = $this->query("SELECT i.id, i.name, i.path FROM images as i
                                INNER JOIN annoncements_images as ai ON ai.image_id = i.id
                                WHERE ai.annoncement_id = ?",
                    [$annoncement->getId()], false , ImageEntity::class);
                $annoncement->AddImages($images);

        }
        return $annoncement;
    }
    public function Add(AnnoncementEntity $annoncement){

        $result = $this->query( "
                    INSERT INTO annoncements(title, description, city, price, date,date_at,
                    category, type, floor,surface,room,user_id)
                    VALUES(?,?,?,?,?,?,?,?,?,?,?,?)  ;",
            [
                $annoncement->getTitle(),
                $annoncement->getDescription(),
                $annoncement->getCity(),
                $annoncement->getPrice(),
                $annoncement->getDate(),
                $annoncement->getDateAt(),
                $annoncement->getCategory(),
                $annoncement->getType(),
                $annoncement->getFloor() ,
                $annoncement->getSurface(),
                $annoncement->getRoom(),
                $annoncement->getUser()
            ]);
        $annoncement->setId((int)$this->db->lastInsertId());
        foreach ($annoncement->getImages() as $image){
             $result = $this->query("INSERT INTO images(name, path)value(?,?)",
                 [$image->getName(), $image->getPath()]
             );

             $image->setId((int)$this->db->lastInsertId('images'));
             $this->query("INSERT INTO annoncements_images(annoncement_id,image_id)VALUES(?,?)",
                 [$annoncement->getId(), $image->getId()]
             );
        }
        return $result;
    }
   
}
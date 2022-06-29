<?php

namespace App\Entitys;

use App\Entitys\ImageEntity as EntityImage;
use Cassandra\Date;
use Exception;


class AnnoncementEntity
{
    public const TYPES = ['aucune selection', 'studio', 'appartement','maison'];
    
    public const CATEGORYS  = ['aucune selection','location', 'achat'];
    public const CITYS = ['aucune selection', 'paris', 'marseille', 'toulouse', 'lille'];
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $description;
    /**
     * @var string
     */
    protected $city;
    /**
     * @var int
     */
    protected $zip;
    /**
     * @var int
     */
    protected $price;
    /**
     * @var string $date
     */
    protected $date;
    /**
     * @var string $date_at
     */
    protected $date_at;
    /**
     * @var int $category
     */
    protected $category;
    /**
     * @var EntityImage $images
     */
    protected $images;
    /**
     * @var int
     */
    protected $type;
    /**
     * @var int
     */
    protected $floor;
    /**
     * @var int
     */
    protected $surface;
    /**
     * @var int
     */
    protected $room;
    /**
     * @var Users
     */
    protected $user;

    /**
     * @var ImageEntity
     */
    protected $image;


    public function __construct()
    {

        $this->images = [];
    }


    public function getId() :?int
    {
        return $this->id;
    }
    public function setId(string $id) :self
    {
        $this->id = $id;
        return $this;
    }
    
    
    public function getTitle() :?string
    {
        return $this->title;
    }
    
    public function setTitle(string $title) :self
    {
        $this->title = $title;
        
        return $this;
    }
    
    
    public function getDescription() :?string
    {
        return $this->description;
    }
    
    public function setDescription(string $description) :self
    {
        $this->description = $description;
        
        return $this;
    }
    
    
    public function getCity() :?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return self
     * @throws Exception
     */
    public function setCity(string $city) :self
    {
        $this->city = $city;
        return $this;
    }
    
    


    public function getCategory() :?int
    {
        return $this->category;
    }
    public function getCategoryToString() :?string
    {
        return self::CATEGORYS[$this->category];
    }

    /**
     * @return int|null
     */
    public function getPrice() :?int
    {

        return $this->price;
    
    }
    
    public function setPrice(string $price) :self
    {

        $this->price = $price;
        
        return $this;
    }


    public function getDate() :?string
    {
        return $this->date;
    }

    public function getDateFormatFR() :?string
    {
        return date_format($this->date, 'd-m-Y');
    }
    
    public function setDate(string $date) :self
    {
        $this->date = $date;
        
        return $this;
    }
    
    



    /**
     * @param string $category
     * @return int
     */
     static public function getCategoryByKey($category){
        foreach (self::CATEGORYS as $key => $value){
            if ($value == $category){
                return $key;
            }
        }
    }

    public function setCategory(int $category): self
    {
        $this->category = $category;
        
        return $this;
    }
    
    
    public function getType() :?int
    {
        return $this->type;
    }
    public function getTypeToString() :?string
    {
        return self::TYPES[$this->type];
    }

    public function setType(int $type) :self
    {
        $this->type = $type;
        
        return $this;
    }


    /**
     * @return array|null
     */
    public function getImages() :?array
    {
        return $this->images;
    }

    /**
     * @param array|imageEntity $images
     * @return $this
     */
    public function AddImages( $images) :self
    {

        if(is_object($images)){

            $this->images[] = clone $images;
        }else{
            foreach ($images as $image){
                $this->images[] = clone $image;
            }
        }

        return $this;
    }


    public function getUser() :int
    {
        return $this->user;
    }
    
    public function setUser(int $user) :self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getFloor(): ?int
    {
        return $this->floor;
    }

    /**
     * @param int $floor
     * @return self
     */
    public function setFloor(int $floor): self
    {
        $this->floor = $floor;
        return $this;
    }

    /**
     * @return int
     */
    public function getSurface(): ?int
    {
        return $this->surface;
    }

    /**
     * @param int $surface
     * @return self
     */
    public function setSurface(int $surface): self
    {
        $this->surface = $surface;
        return $this;
    }

    /**
     * @return int
     */
    public function getRoom(): ?int
    {
        return $this->room;
    }

    /**
     * @param int $room
     * @return self
     */
    public function setRoom(int $room): self
    {
        $this->room = $room;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateAt(): string
    {

        return date('Y-m-d');
    }

    /**
     * @param string $date_at
     * @return self
     */
    public function setDateAt(string $date_at): self
    {
        $this->date_at = $date_at;
        return $this;
    }
}
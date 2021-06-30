<?php
namespace Wishlist\entity;


class WishlistEntity
{
    private $id;
    private $uid;
    private $id_item;
    private $createdAt;
    private $status;
    private $tabla;


    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"]) ? $data["id"] : null);
        $this->uid = (isset($data["uid"]) ? $data["uid"] : null);
        $this->id_item = (isset($data["id_item"]) ? $data["id_item"] : null);
        $this->createdAt = (isset($data["createdAt"]) ? $data["createdAt"] : null);
        $this->status = (isset($data["status"]) ? $data["status"] : null);
        $this->tabla = (isset($data["tabla"]) ? $data["tabla"] : null);

    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array();
    }

    public function id($id = null){ if($id != null){ $this->id=$id; }else{ return $this->id; } }

    public function uid($uid = null){ if($uid != null){ $this->uid=$uid; }else{ return $this->uid; } }

    public function id_item($id_item = null){ if($id_item != null){ $this->id_item=$id_item; }else{ return $this->id_item; } }

    public function createdAt($createdAt = null){ if($createdAt != null){ $this->createdAt=$createdAt; }else{ return $this->createdAt; } }

    public function status($status = null){ if($status !== null){ $this->status=$status; }else{ return $this->status; } }
    
    public function tabla($tabla = null){ if($tabla !== null){ $this->tabla=$tabla; }else{ return $this->tabla; } }
}
?>

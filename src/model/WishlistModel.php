<?php
namespace Wishlist\model;

class WishlistModel  extends \Franky\Database\Mysql\objectOperations
{
    private $campos;
    private $campo_item;
    private $campo_item_id;
    private $tabla_item;
    private $busca;
    private $rango;
    
    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('wishlist');
    }

    function setCampos($campos)
    {
        $this->campos = $campos;
    }

    function setCampoItem($item)
    {
        $this->campo_item = $item;
    }
    function setCampoItemId($item_id)
    {
        $this->campo_item_id = $item_id;
    }

    function setTablaItem($tabla)
    {
        $this->tabla_item = $tabla;
    }
    function setBusca($busca)
    {
        $this->busca = $busca;
    }
    function setRango($rango)
    {
        $this->rango = $rango;
    }

    function getData($data = array())
    {
       
        $data = $this->optimizeEntity($data);
        $campos = ["wishlist.id",
            "wishlist.uid",
            "wishlist.createdAt",
            "wishlist.tabla",
            "wishlist.status",
            "wishlist.id_item"
        ];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("wishlist.".$k,$v,'=');
        }

        return $this->getColeccion($campos);

    }
    function getFullData($data = array())
    {
       
        $data = $this->optimizeEntity($data);
        $campos = ["wishlist.id",
            "wishlist.uid",
            "wishlist.createdAt",
            "wishlist.tabla",
            "wishlist.status",
            "wishlist.id_item",
            $this->tabla_item.'.'.$this->campo_item.' as item'
        ];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("wishlist.".$k,$v,'=');
        }
      
        if(!empty($this->busca))
        {
              $this->where()->addAnd($this->tabla_item.'.'.$this->campo_item,"%$this->busca%",'like');
        }
        if(!empty($this->rango))
        {
              $this->where()->concat('AND (');
              $this->where()->addAnd('wishlist.createdAt',$this->rango[0].' 00:00:00','>=');
              $this->where()->addAnd('wishlist.createdAt',$this->rango[1].' 23:59:59','<=');
              $this->where()->concat(')');
        }

        $this->from()->addInner($this->tabla_item,"wishlist.id_item",$this->tabla_item.".".$this->campo_item_id);
        $this->from()->addInner("users","users.id","wishlist.uid");


        return $this->getColeccion($campos);

    }

    private function optimizeEntity($array)
    {
        foreach ($array as $k => $v )
        {
            if (!isset($v)) {
                unset($array[$k]);
            }
        }
        return $array;
    }

    public function save($data)
    {
        $data = $this->optimizeEntity($data);


    	if (isset($data['id']))
    	{
            $this->where()->addAnd('id',$data['id'],'=');

            return $this->editarRegistro($data);
    	}
    	else {

            return $this->guardarRegistro($data);
    	}

    }

    public function delete($data)
    {

      $data = $this->optimizeEntity($data);
      foreach($data as $k => $v)
      {
          $this->where()->addAnd("wishlist.".$k,$v,'=');
      }

      return $this->eliminarRegistro($data);
    }
}
?>

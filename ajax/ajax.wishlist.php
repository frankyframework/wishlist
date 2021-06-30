<?php

function wishlist_addWishlist($id,$tabla,$status)
{
    $WishlistModel = new Wishlist\model\WishlistModel;
    $WishlistEntity = new Wishlist\entity\WishlistEntity;
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MySession;
    global $MyRequest;
    $respuesta = null;

    $WishlistEntity->id_item($Tokenizer->decode($id));
 
    $WishlistEntity->tabla($tabla);
    if(!$MySession->LoggedIn())
    {
        $WishlistEntity->status($status);
        $MySession->SetVar('wishlist_eventos_pendientes',['wishlist' => $WishlistEntity->getArrayCopy()]);

        $respuesta[] = array("message" => "login","path" => $MyRequest->url(LOGIN)."?callback=".urlencode($MyRequest->getReferer()));
    }
    else {
        if($status == 1)
        {
          $WishlistEntity->status($status);
            
          $WishlistEntity->uid($MySession->GetVar('id'));

          if($WishlistModel->getData($WishlistEntity->getArrayCopy()) != REGISTRO_SUCCESS)
          {
              $WishlistEntity->createdAt(date('Y-m-d H:i:s'));
              $result = $WishlistModel->save($WishlistEntity->getArrayCopy());
          }

        }
        else{

          $WishlistEntity->uid($MySession->GetVar('id'));
          $result = $WishlistModel->delete($WishlistEntity->getArrayCopy());
        }


    }

    return $respuesta;
}

function wishlist_EliminarWhislist($id,$status)
{
    global $MySession;
    $WishlistModel = new Wishlist\model\WishlistModel;
    $WishlistEntity = new Wishlist\entity\WishlistEntity;
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyMessageAlert;
    $respuesta = null;
    if($MySession->LoggedIn())
    {
        $WishlistEntity->id($Tokenizer->decode($id));
        if($WishlistModel->delete($WishlistEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {


        }
        else
        {
                    $respuesta[] = array("message" => $MyMessageAlert->Message(($status == 1 ? "activar" : "eliminar")."_generico_error"));
        }
    }
    else
    {
         $respuesta[] = array("message" => $MyMessageAlert->Message("sin_privilegios"));
    }

    return $respuesta;
}


function wishlist_getWishlist($tabla)
{

    $WishlistModel = new Wishlist\model\WishlistModel;
    $WishlistEntity = new Wishlist\entity\WishlistEntity;
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MySession;

    $respuesta = null;


    if($MySession->LoggedIn())
    {

         $WishlistEntity->status(1);
         $WishlistEntity->tabla($tabla);
         $WishlistEntity->uid($MySession->GetVar('id'));
         $WishlistModel->setTampag(1000);
         if($WishlistModel->getData($WishlistEntity->getArrayCopy()) == REGISTRO_SUCCESS)
         {
            $respuesta = [];
              while($registro = $WishlistModel->getRows())
              {
                  $respuesta[] = $Tokenizer->token('wishlist',$registro['id_item']);
              }
         }
       }

    return $respuesta;
}

$MyAjax->register("wishlist_addWishlist");
$MyAjax->register("wishlist_EliminarWhislist");
$MyAjax->register("wishlist_getWishlist");

?>
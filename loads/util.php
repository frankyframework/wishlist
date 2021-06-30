<?php

function _wishlist($txt)
{
    return dgettext("wishlist",$txt);
}



function wishlist_completarTareas()
{
    global $MySession;

    $eventos_pendientes = $MySession->GetVar('wishlist_eventos_pendientes');


    if(isset($eventos_pendientes['wishlist']))
    {
        $WishlistModel = new Wishlist\model\WishlistModel;
        $WishlistEntity = new Wishlist\entity\WishlistEntity($eventos_pendientes['wishlist']);

        if($WishlistEntity->status() == 1)
        {
          $WishlistEntity->createdAt(date('Y-m-d H:i:s'));
          $WishlistEntity->uid($MySession->GetVar('id'));
          $result = $WishlistModel->save($WishlistEntity->getArrayCopy());
        }
        else{
          $WishlistEntity->uid($MySession->GetVar('id'));
          $WishlistModel->delete($WishlistEntity->getArrayCopy());
        }

    }

    $MySession->UnsetVar('wishlist_eventos_pendientes');

}


?>
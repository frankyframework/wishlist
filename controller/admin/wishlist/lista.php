<?php
use Wishlist\model\WishlistModel;
use Wishlist\entity\WishlistEntity;
use Franky\Haxor\Tokenizer;


if ($MyRequest->isAjax()) {
    $callback	= $MyRequest->getRequest('callback');
    $filters = $MyRequest->getRequest('filters');
    $dataPost = json_decode(stripslashes($filters),true);
    $dataPost = $dataPost['rules'];
    $request = [];
    foreach($dataPost as $data) {
        $request[$data['field']] = $MyRequest->Sanitizacion($data['data']);
    }

    $rango = [];

    if(isset($request['createdAt']) && !empty($request['createdAt']))
    {
        $rango = [$request['createdAt'],$request['createdAt']];
    }

    $WishlistModel = new WishlistModel();
    $WishlistEntity = new WishlistEntity($request);
    $Tokenizer = new Tokenizer();
    $WishlistModel->setRango($rango);
    $sortInput  = (!empty($MyRequest->getRequest('sidx',"wishlist.createdAt")) ? : "wishlist.createdAt");

    $WishlistEntity->tabla($tabla);
    $WishlistModel->setCampoItem($campo_item);
    $WishlistModel->setTablaItem($tabla);
    $WishlistModel->setCampoItemId($campo_item_id);
    if (isset($campo_item_urlkey) && !empty($campo_item_urlkey) ) {
        $WishlistModel->setCampoItemUrl($campo_item_urlkey);
    }     
    if (isset($campo_item_image) && !empty($campo_item_image) ) {
        $WishlistModel->setCampoItemImage($campo_item_image);
    }  

    $WishlistModel->setPage($MyRequest->getRequest('page',1));
    $WishlistModel->setTampag($MyRequest->getRequest('rows',12));
    $WishlistModel->setOrdensql($sortInput." ".$MyRequest->getRequest('sord',"ASC"));


    $WishlistEntity->uid($MySession->GetVar('id'));

    $result	 = $WishlistModel->getFullData($WishlistEntity->getArrayCopy());

    $dataRows = ["rows" => [], "total" => ceil($WishlistModel->getTotal() / $MyRequest->getRequest('rows',12)), "page" => (int)$MyRequest->getRequest('page',1),"records" => $WishlistModel->getTotal()];

    if($WishlistModel->getTotal() > 0)
    {

        while($registro = $WishlistModel->getRows())
        {
            $registro = array_filter($registro, function($llave) {
                    return !is_numeric($llave);
            }, ARRAY_FILTER_USE_KEY);

            if (isset($campo_item_urlkey) && !empty($campo_item_urlkey) && isset($urlView)) {
                $registro["item"] = '<a href="'.str_replace("{campo_item_urlkey}",$registro["item_url_key"],$urlView).'" target="_blank">'.$registro["item"]."</a>" ;
            }    
            if (isset($campo_item_image) && !empty($campo_item_image) && isset($pathImage)) {
            
                $registro["item_image"] = str_replace("{campo_item_image}",$registro["item_image"],$pathImage);
                $registro["item_image"] = str_replace("{campo_item_id}",$registro["id_item"],$registro["item_image"]);
                if(!empty($registro["item_image"]) && file_exists($registro["item_image"]))
                {
                    $registro['item_image'] = makeHTMLImg(imageResize($registro["item_image"],(isset($thumbW) ? $thumbW : 100),(isset($thumbH) ? $thumbH : 100), true),100,100,strip_tags($registro["item"]));
                }
            }     

            $dataRows['rows'][] = array_merge($registro,array(
            "id" => $Tokenizer->token("whishist", $registro["id"]),
            "createdAt"         => getFechaUI($registro["createdAt"]),
            "status"  =>  "desactivar"
            ));


        }

    }
    header('Content-Type: application/json; charset=utf-8');
    echo $callback . '(' . json_encode($dataRows). ');';
    die;
} else {
    $MyMetatag->setJs("/public/plugins/jqGrid/js/jquery.jqGrid.js");
    $MyMetatag->setJs("/public/plugins/jqGrid/js/i18n/grid.locale-$lang_root.js");
    $MyMetatag->setCSS("/public/plugins/jqGrid/css/ui.jqgrid.css");
}

?>

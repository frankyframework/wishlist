<?php
use Franky\Core\ObserverManager;
$ObserverManager = new ObserverManager;
include 'lca.php';
include 'util.php';
bindtextdomain("wishlist", PROJECT_DIR ."/modulos/wishlist/locale");


if (function_exists('bind_textdomain_codeset')) 
{
    bind_textdomain_codeset("wishlist", 'UTF-8');
}

$ObserverManager->addObserver('login_user','wishlist_completarTareas');

$MyMetatag->setJs("/modulos/wishlist/web/js/ajax.wishlist.js");
?>
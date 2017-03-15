<?php

session_start();
require "libs/Smarty.class.php";    // Smarty
include 'php/php_fast_cache.php';   // Cache
include 'class/Autoloader.php';     // autoloader

Autoloader::register();

define('MAIN_PATH', getcwd());
define("UPLOAD_DIR", "img/");

$connexion = Connexion::getInstance('127.0.0.1', 'u725582773_root', 'toor47', 'u725582773_test;charset=utf8');
//$connexion = Connexion::getInstance('localhost', 'root', 'toor', 'test');
$manager = CaveManager::getInstance($connexion->getPDO());

require 'php/loginout.php';

require 'php/crud.php';

/************************************* initialisation des variables de vue ************************************/

$first     = $ListNames[0]["id"];               // 1er enregistrement
$nb_rec    = count($ListNames);                 // nb d'elements dans la base
$last      = $ListNames[$nb_rec - 1]["id"];     // dernier enregistrement
$nb_elt    = 10;                                // nb enregistrements par pages
$nb_pages  = ceil($nb_rec / $nb_elt);           // calcul du nb pages

$page      = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
$bottleGet = filter_input(INPUT_GET, 'bottle', FILTER_SANITIZE_NUMBER_INT);
$bottle    = isset($bottleIdCreated) ? $bottleIdCreated : $bottleGet;
$directionGet = filter_input(INPUT_GET, 'direction', FILTER_SANITIZE_STRING);
$direction = isset($directionCreated) ? $directionCreated : $directionGet;
$optNames = array_column($ListNames, 'name', 'id'); // on récupère les colonnes 'name' et 'id' de $ListNames (pour la recherche)

$LaBase   = array();            // tableau associatif id => ligne (une bouteille et toutes ses infos) *******
$LesIndex = array();            // tableau associant les index aux id
foreach ($ListNames as $index => $line) {
  $line[] = $index;
  $LaBase[$line["id"]] =  $line;
  $LesIndex[] = $line["id"];  // ****************************
}
$bottle = ($bottle > 0) ? $bottle : $first;         // quelle bouteille afficher si smartphone
$page   = ($page > 0) && ($page < $nb_pages) ? $page : floor($LaBase[$bottle][0] / $nb_elt);  // quelle page afficher

if (!(isset($direction))) { // ****************************** si grand écran ****************
    $elements = array();
    $i        = 0;
    $nb_eltPage = $nb_elt;      // elts de derniere page <= $nb_elt
    if($page == ($nb_pages-1)) {$nb_eltPage = (1 - $nb_pages) * $nb_elt + $nb_rec;}
    while ($i < $nb_eltPage) {
        $elem = array_merge($ListNames[$page * $nb_elt + $i], array('ordre' => ($nb_elt - $i))); // pour le z-index des images
        $elements[] = $elem; // tableau de (nb_eltPage) bouteilles
        $i++;
    }
} elseif ($direction == 'left') { // *********************    si smartphone  ***************
    if ($bottle != $first) {
        $bottle    = $LesIndex[$LaBase[$bottle][0]-1];
    }
    $elements[] = $LaBase[$bottle];
    $direction = NULL;
} elseif ($direction == 'right') {
    if ($bottle != $last) {
        $bottle    = $LesIndex[$LaBase[$bottle][0]+1];
    }
    $elements[] = $LaBase[$bottle];
    $direction = NULL;
} else {
    $elements[] = $LaBase[$bottle];
    $direction = NULL;
}
$_SESSION['page'] = $page;

$smarty = new Smarty();                   // nouvel objet smarty et recup des variables php dans smarty
$smarty->setTemplateDir('./template');
$smarty->assign('nb_rec', $nb_rec);       // nombre de lignes dans mycave
$smarty->assign('nb_pages', $nb_pages);   // nombre de pages
$smarty->assign('page', $page);           // page courrente
$smarty->assign('elts', $elements);       // les enregistrements de mycave
$smarty->assign('bottle', $bottle);       // bouteille en cours
$smarty->assign('session', $_SESSION);
$smarty->assign('myOptions', $optNames);  // la liste des bouteilles

$smarty->display('index.tpl');            // appelle la page principale
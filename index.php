<?php
// On enregistre notre autoload.
function chargerClasse($classname)  // <-------------------------------------------------------
{
  require 'php/'.$classname.'.php';
}
spl_autoload_register('chargerClasse');// <-------------------------------------------------------

session_start();
include("php/php_fast_cache.php");

require 'php/connect.php';          // Connection à la bd
require("libs/Smarty.class.php");   // Smarty

if(isset($_POST['create'])){        // <-------------------------------------------------------
    $bottle_update = $_POST;
    $bouteille = new Bottle($bottle_update);
    $manager->add($bouteille);
}                                   // <-------------------------------------------------------

define('MAIN_PATH', getcwd());

phpFastCache::$storage = "auto";
$ListNames = phpFastCache::get("products_page");   //****** mise en cache *************************
if($ListNames == null || !isset($_GET['cache'])) {
    $req       = $bdd->query("SELECT * FROM mycave ORDER BY id");   // récupère toute la base
    $ListNames = $req->fetchAll(PDO::FETCH_ASSOC);
    phpFastCache::set("products_page",$ListNames,600);
    $_GET['cache'] = NULL;                        //*****************************************
}

$manager = new CaveManager($db);    // <-------------------------------------------------------

$first     = $ListNames[0]["id"];               // 1er enregistrement
$nb_rec    = count($ListNames);                 // nb d'elements dans la base
$last      = $ListNames[$nb_rec - 1]["id"];     // dernier enregistrement
$nb_elt    = 10;                                // nb enregistrements par pages
$nb_pages  = ceil($nb_rec / $nb_elt);           // calcul du nb pages

$page      = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
$bottle    = filter_input(INPUT_GET, 'bottle', FILTER_SANITIZE_NUMBER_INT);
$direction = filter_input(INPUT_GET, 'direction', FILTER_SANITIZE_STRING);

$page   = ($page > 0) ? $page : 0;          // quelle page afficher
$bottle = ($bottle > 0) ? $bottle : $first; // ou quelle bouteille si smartphone

$optNames = array_column($ListNames, 'name', 'id'); // on récupère les colonnes 'name' et 'id' de $ListNames (pour smartphone)

$LaBase   = array();           // tableau associatif id => ligne (une bouteille et toutes ses infos) *******
$LesIndex = array();         // tableau associant les indexes aux id
foreach ($ListNames as $index => $line) {
  $line[] = $index;
  $LaBase[$line["id"]] =  $line;
  $LesIndex[] = $line["id"];  // ****************************
}

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
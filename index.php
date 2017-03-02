<?php
// On enregistre notre autoload.
session_start();
require "libs/Smarty.class.php";    // Smarty
include 'php/php_fast_cache.php';   // Cache

function chargerClasse($classname)  
{
  require 'php/'.$classname.'.php';
}
spl_autoload_register('chargerClasse');  // autoloader

define('MAIN_PATH', getcwd());
define("UPLOAD_DIR", "img/");

$connexion = new Connexion('localhost', 'root', 'root', 'test');
$bdd = $connexion->getPDO();
$manager = new CaveManager($bdd);

if(isset($_POST['loggingin'])){   
    $userService = new UserService($bdd, $_POST['login'], $_POST['pswd']);// <------------------- LOGIN 
    if ($user_id = $userService->login()) {
        //echo 'Logged in as user id: '.$user_id;
        $userData = $userService->getUser();
        $_SESSION['id'] = $userData['id'];
        $_SESSION['pseudo'] = $userData['login'];
        unset($_POST);
    } else {
        echo 'Invalid login';
    }
}
if(isset($_GET['logout'])){                       //  <------------------------------------------  LOGOUT
    // Suppression des variables de session et de la session
    $page = $_SESSION['page'];
    $_SESSION = array();
    session_destroy();
    unset($_GET);
}
if(isset($_SESSION['id']) AND isset($_SESSION['pseudo'])){
    if(isset($_POST['create'])){                // <----------------------------------------------- CREATE
        if (!empty($_FILES["picture"])) {
            $pictureF = $_FILES["picture"];

            if ($pictureF["error"] !== UPLOAD_ERR_OK) {
                echo "<p>An error occurred.</p>";
                exit;
            }

            // verify the file type
            $fileType = exif_imagetype($_FILES["picture"]["tmp_name"]);
            $allowed = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
            if (!in_array($fileType, $allowed)) {
                echo "<p>File type is not permitted.</p>";
                exit;
            }

            // ensure a safe filename
            $namePic = preg_replace("/[^A-Z0-9._-]/i", "_", $pictureF["name"]);

            // don't overwrite an existing file
            $i = 0;
            $parts = pathinfo($namePic);
            while (file_exists(UPLOAD_DIR . $namePic)) {
                $i++;
                $namePic = $parts["filename"] . "-" . $i . "." . $parts["extension"];
            }

            // preserve file from temporary directory
            $success = move_uploaded_file($pictureF["tmp_name"],
                UPLOAD_DIR . $namePic);
            if (!$success) { 
                echo "<p>Unable to save file.</p>";
                exit;
            }

            // set proper permissions on the new file
            chmod(UPLOAD_DIR . $namePic, 0644);
        }
        $bottle_create = $_POST;
        $bottle_create['picture'] = $namePic;
        $bouteille = new Bottle($bottle_create);
        $manager->add($bouteille);
        unset($_POST);
        $cache = 'off';
    }                                   // <------------------------------------------------------- DELETE
    if(isset($_POST['delete'])){ 
        $bouteille = new Bottle(['id'=>$_POST['Del_id']]);
        $manager->delete($bouteille);
        unset($_POST);
        $cache = 'off';
    }
    if(isset($_POST['update'])){         // <------------------------------------------------------- UPDATE
        if (!empty($_FILES["picture-file"]["name"])) {
            $pictureF = $_FILES["picture-file"];

            if ($pictureF["error"] !== UPLOAD_ERR_OK) {
                echo "<p>An error occurred.</p>";
                exit;
            }

            // verify the file type
            $fileType = exif_imagetype($_FILES["picture-file"]["tmp_name"]);
            $allowed = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
            if (!in_array($fileType, $allowed)) {
                echo "<p>File type is not permitted.</p>";
                exit;
            }

            // ensure a safe filename
            $namePic = preg_replace("/[^A-Z0-9._-]/i", "_", $pictureF["name"]);

            // don't overwrite an existing file
            // preserve file from temporary directory
            if(!file_exists(UPLOAD_DIR . $namePic)){
                $success = move_uploaded_file($pictureF["tmp_name"],
                    UPLOAD_DIR . $namePic);
                if (!$success) { 
                    echo "<p>Unable to save file.</p>";
                    exit;
                }
            }

            // set proper permissions on the new file
            chmod(UPLOAD_DIR . $namePic, 0644);
        }

        $picture=(isset($namePic))?$namePic:$_POST['picture'];
        $bottle_update = $_POST;
        $bottle_update['picture'] = $picture;
        $bouteille = new Bottle($bottle_update);
        $manager->update($bouteille);
        unset($_POST);
        $cache = 'off';
    }
}
phpFastCache::$storage = "auto";
$ListNames = phpFastCache::get("products_page");   // mise en cache 
if(is_null($ListNames) || isset($cache)) {
    $ListNames = $manager->getList();// <------------------------------------------------------- READ
    phpFastCache::set("products_page",$ListNames,0);
    unset($cache);
}

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

$LaBase   = array();            // tableau associatif id => ligne (une bouteille et toutes ses infos) *******
$LesIndex = array();            // tableau associant les index aux id
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
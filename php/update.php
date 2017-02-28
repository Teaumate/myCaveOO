<?php
session_start();
require 'connect.php';

define("UPLOAD_DIR", "../img/");

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
$grapes = filter_input(INPUT_POST, 'grapes', FILTER_SANITIZE_STRING);
$country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
$region = filter_input(INPUT_POST, 'region', FILTER_SANITIZE_STRING);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
$picture = filter_input(INPUT_POST, 'picture', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

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

$picture=($namePic!=NULL)?$namePic:$picture;

if (isset($_SESSION['id']) AND isset($_SESSION['pseudo'])){
    $req = $bdd->prepare("UPDATE `mycave` SET `name`=?,`year`=?,`grapes`=?,`country`=?,`region`=?,`description`=?,`picture`=? WHERE id=?");
    $req->execute(array($name, $year, $grapes, $country, $region, $description, $picture, $id));
    $msg='Enregistrement modifié';
    header('Location: ../index.php?msg='.$msg);
}
header('Location: ../index.php?page='.$_SESSION['page'].'&cache=cache');
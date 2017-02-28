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

if (isset($_SESSION['id']) AND isset($_SESSION['pseudo'])){
    $req = $bdd->prepare("INSERT INTO mycave( name, year, grapes, country, region, description, picture) VALUES (?,?,?,?,?,?,?)");
    $req->execute(array($name, $year, $grapes, $country, $region, $description, $namePic));
    $msg='Enregistrement ajouté';
    header('Location: ../index.php?msg='.$msg);
}
header('Location: ../index.php?page='.$_SESSION['page'].'&cache=cache');
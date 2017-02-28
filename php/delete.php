<?php
session_start();
require 'connect.php';
$id = filter_input(INPUT_POST, 'Del_rec', FILTER_SANITIZE_NUMBER_INT);

if (isset($_SESSION['id']) AND isset($_SESSION['pseudo'])){
    $req = $bdd->prepare('DELETE FROM mycave WHERE id=?');
    $req->execute(array($id));
    $msg='Enregistrement supprimé';
    header('Location: ../index.php?msg='.$msg);
}
header('Location: ../index.php?page='.$_SESSION['page'].'&cache=cache');
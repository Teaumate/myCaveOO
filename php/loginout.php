<?php
if(isset($_POST['loggingin'])){   
    $userService = new UserService($connexion->getPDO(), $_POST['login'], $_POST['pswd']);// <------------------- LOGIN 
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
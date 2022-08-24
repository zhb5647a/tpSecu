<?php
session_start();
// choix du menu
$role = $_SESSION['role'];
if ( $role === "administrateur") {
    include './view/view_header_admin.php';
}elseif ($role === "utilisateur") {
    include './view/view_header_utilisateur.php';
}else {
    include './view/view_header.php';
}

    //Analyse de l'URL avec parse_url() et retourne ses composants
    $url = parse_url($_SERVER['REQUEST_URI']);
    //test soit l'url a une route sinon on renvoi à la racine
    $path = isset($url['path']) ? $url['path'] : '/';


    switch($path){
        case $path === "/exoConnexion/addUser":
            include './controller/ctrl_add_user.php';
            break;
        case $path === "/exoConnexion/evenement":
            include './controller/ctrl_see_evenement.php';
            break;

        case $path === "/exoConnexion/connexion":
            include './controller/ctrl_connexion.php';
            break;

        case $path === "/exoConnexion/activation":
            include './controller/ctrl_active_account.php';
            break;
    }

?>
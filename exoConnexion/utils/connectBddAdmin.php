<?php
    //fichier de connexion à la BDD
    $bdd = new PDO('mysql:host=localhost;dbname=tpsecu', 'admin1','1234',
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
?>

<?php
include './utils/connectBddUser.php';
include './utils/funciton.php';

include './model/model_user.php';
include './manager/manager_user.php';

include './view/view_connexion.php';

$message= "";

//test si on à cliqué sur le bouton connexion
if(isset($_POST['connexion'])){
    //test si les champs sont remplis
    if($_POST['mail_util'] !="" AND $_POST['pwd_util'] !=""){
        $mail = cleanInput($_POST['mail_util']);
        $pwd = cleanInput($_POST['pwd_util']);
        //instance d'un nouvel objet
        $util = new ManagerUser(null,null,$mail,null,null);
        $verif = $util->getUtilByMail($bdd);
        $valide_util = $verif[0] ["valide_util"];
            if($valide_util == 1){
            //test si test est différent de vide
            if(!empty($verif)){
                //récupére le hash
                $hash = $verif[0]['pwd_util'];
                 //vérifie si le mot de passe correspond
                 $password = password_verify($pwd, $hash);
                 if($password){
                     //créer les super globales SESSION
                     $_SESSION['connected'] = true;
                     $_SESSION['id'] = $verif[0]['id_util'];
                     $_SESSION['name'] = $verif[0]['name_util'];
                     $_SESSION['first'] = $verif[0]['first_name_util'];
                     $_SESSION['mail'] = $verif[0]['mail_util'];
                     $_SESSION['role'] = $verif[0]['id_role'];
                     //message connecté
                     $message ="Connecté";
                 }
                }
                 else{
                    //message les informations sont incorrectes
                    $message =" les informations sont incorrectes";
                }   

            }else {
                $message = "compte non activé";
            }
    }
    //test si les champs sont vides
    else{
        $message = "Veuillez compléter les champs du formulaire";
    }
}
echo $message;
?>
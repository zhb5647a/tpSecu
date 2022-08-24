<?php
include './model/model_user.php';
include './manager/manager_user.php';

include './view/view_add_user.php';

include './utils/connectBddUser.php';
include './utils/funciton.php';
// importation identifiant
include './id_smtp.php';

$message="";

if (!empty($_POST['bouton'])) {
    if (isset($_POST['name_util']) && !empty($_POST['name_util'])&&
    isset($_POST['first_name_util']) && !empty($_POST['first_name_util'])&&
    isset($_POST['mail_util']) && !empty($_POST['mail_util'])&&
    isset($_POST['pwd_util']) && !empty($_POST['pwd_util'])&&
    isset($_POST['confirm_pwd']) && !empty($_POST['confirm_pwd'])) {
        if ($_POST['confirm_pwd'] == $_POST['confirm_pwd']) {

            $mdp =cleanInput($_POST['pwd_util']);
            $name =cleanInput($_POST['name_util']);
            $firstName=cleanInput($_POST['first_name_util']);
            $mail =cleanInput($_POST['mail_util']);

            $hash = password_hash($mdp,PASSWORD_DEFAULT);
            $user = new ManagerUser($name,$firstName,$mail,$hash,1);
            var_dump($user);

            // $user->getUtilByMail($bdd);
            if (empty($user->getUtilByMail($bdd))) {


                $token = md5($mail);
                $user->setTokenUtil($token);
                $user->setIdRole(1);
                //enregistrement
                $user->addUser($bdd);

                $subject = 'hello';
                $emailMessage = utf8_decode("<h1>bienvenue à toi ".$name."</h1> <p>merci de ton inscription!</p>
                <a href='http://localhost/exoConnexion/activation?id=$token'>clique sur le lien pour valider ton inscrition</a>");
                $user->sendMail($user->getMailUtil(),$subject,$emailMessage,$loginSmtp,$mdpSmtp);
                
            }else{
                // probleme compte dejà pris
                $message="erreur lors de la siasie erreur 2";
            }
        }else{
            // probleme mdp non identique
            $message="erreur lors de la siasie erreur 1";
        }
    }else{
        $message="merci de remplir le formulaire";
    }
}else{
    $message="cliquer sur le bouton";
}

echo $message;





?>
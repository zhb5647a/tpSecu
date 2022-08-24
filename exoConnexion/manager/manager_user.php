<?php
// importation classe pour email
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//////////////////////////////////
Class ManagerUser extends Utilisateur{

    // methods
    public function addUser($bdd):void{
        try {
            $name_util = $this->getNameUtil();
            $first_name_util = $this->getFirstNameUtil();
            $mail_util = $this->getMailUtil();
            $pwd_util = $this->getPwdUtil();
            $id_role = $this->getIdRole();
            $valide_util = $this->getValideUtil();
            $token_util = $this->getTokenUtil();
            $req = $bdd->prepare('INSERT INTO utilisateur(name_util,first_name_util,
            mail_util,pwd_util,id_role,valide_util,token_util)
            VALUES(?,?,?,?,?,?,?)');

            $req->bindparam(1,$name_util,PDO::PARAM_STR);
            $req->bindparam(2, $first_name_util,PDO::PARAM_STR);
            $req->bindparam(3,$mail_util,PDO::PARAM_STR);
            $req->bindparam(4,$pwd_util,PDO::PARAM_STR);
            $req->bindparam(5,$id_role,PDO::PARAM_INT);
            $req->bindparam(6,$valide_util,PDO::PARAM_STR);
            $req->bindparam(7,$token_util,PDO::PARAM_STR);
            $req->execute();

        } catch (Exception $e) {
            die('Erreur :' .$e->getMessage());
        }

    }

   //récuper un utilisateur avec son mail
   public function getUtilByMail(object $bdd):?array{
    try{
        $mail = $this->getMailUtil();
        //préparation de la requête
        $req = $bdd->prepare('SELECT id_util, name_util, 
        first_name_util, mail_util, pwd_util, id_role, valide_util, token_util 
        FROM utilisateur WHERE mail_util = ?');
        //affectation des paramètres
        $req->bindparam(1,$mail, PDO::PARAM_STR);
        $req->execute();
        $data = $req->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    //exception
    catch(Exception $e)
    {
        //affichage d'une exception en cas d’erreur
        die('Erreur : '.$e->getMessage());
    }
}
    //fonction active un compte
    public function activateUtil(object $bdd):void{
        try{
            $token = $this->getTokenUtil();
            //préparation de la requête
            $req = $bdd->prepare('UPDATE utilisateur SET valide_util = 1 
            WHERE token_util = ?');
            //affectation des paramètres
            $req->bindparam(1,$token, PDO::PARAM_STR);
            $req->execute();
        }
        //exception
        catch(Exception $e)
        {
            //affichage d'une exception en cas d’erreur
            die('Erreur : '.$e->getMessage());
        }
    }
    //récuper un utilisateur avec son token
    public function getUtilByToken(object $bdd):?array{
        try{
            $token = $this->getTokenUtil();
            //préparation de la requête
            $req = $bdd->prepare('SELECT id_util, name_util, 
            first_name_util, mail_util, pwd_util, id_role, valide_util, token_util 
            FROM utilisateur WHERE token_util = ?');
            //affectation des paramètres
            $req->bindparam(1,$token, PDO::PARAM_STR);
            $req->execute();
            $data = $req->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }
        //exception
        catch(Exception $e)
        {
            //affichage d'une exception en cas d’erreur
            die('Erreur : '.$e->getMessage());
        }
        return $data;
    }
     // fonciton pour envoyer un email
     public function sendMail(?string $userMail,?string $subject,?string $emailMessage,?string $loginSmtp,?string $mdpSmtp){
        require './vendor/autoload.php';
            
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0; //SMTP::DEBUG_SERVER mettre afficher tout les messages /2 pour une trace / 0 pour désactiver les messages //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.office365.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $loginSmtp;                     //SMTP username
            $mail->Password   = $mdpSmtp;                               //SMTP password
            $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($loginSmtp, utf8_decode('Clémence'));
            $mail->addAddress($userMail);     //Add a recipient
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments POUR LES PJ
            // $mail->addAttachment('./asset/image/licorneSeriously.png', 'new.jpg');    //Optional name
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $emailMessage;
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients'; pour mettre du string

            $mail->send();
            echo 'Le message à était envoyé';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }        
}

?>
<?php
include_once("BaseDeDonne.php");

$errorLogin ="";

function validateLogin($user,$password)
{
    //Préparation de la requette qui valide le login envoyé
    if($sqlLoginCheck = $this->bdd->prepare("SELECT * FROM usagers WHERE NomUsager=? and MotDePasse=?"))
    {
        //Lie les paramêtre de nom d'usager et de mot de passe à la requette
        $sqlLoginCheck->bindParam(1,$user, PDO::PARAM_STR);
        $sqlLoginCheck->bindParam(2,$password, PDO::PARAM_STR);
        //Execution de la requette
        $sqlLoginCheck->execute();

        //Regarde s'il y a aumoin une ligne de retourné si oui retourne true sinon false et ferme le curseur
        if($row = $sqlLoginCheck->fetch())
        {
            $sqlLoginCheck->closeCursor();
            return true;
        }
        else
        {
            $sqlLoginCheck->closeCursor();
            return false;
        }

    }
    else
    {
        die("Erreur : MYSQL statement n'a pas pu être préparé");
    }
}


if(isset($_POST['Connecter']))
{
    if(empty($_POST['username']) && empty($_POST['password']))
    {
        $errorLogin = 'Les deux champs ne peuvent être vide';
    }
    else
    {
        if(validateLogin($_POST['username'],$_POST['password']))
        {

        }
    }
}



echo "<form action='' method='post' accept-charset='UTF-8'>
                                <div class='input-group'>
                                  Nom d'usager : <input id='username' type='text' class='form-control' name='username' value='' placeholder='Nom d&#39;usager'>
                                </div>

                                <div class='input-group'>
                                  Mot de passe : <input id='password' type='password' class='form-control' name='password' value='' placeholder='Mot de passe'>
                                </div>

                                <button type='submit' name='Connecter'  class='btn btn-default'>Se connecter</button>";

if($errorLogin!="")
{
    echo "<div>
           $errorLogin
          </div>";
}
echo  "</form>";
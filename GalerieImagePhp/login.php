<?php

session_start();
$errorLogin ="";
//blaba
function validateLogin($user,$password)
{
    $Fichier = "Authentification.txt";
    $var = $user . ":" . $password;
    if($AUTHENTIFICATION = file_get_contents($Fichier))
    {
        $existe = substr_count($AUTHENTIFICATION, $var);
    }
    if($existe == 0)
    {
        return false;
    }
    return true;
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
            // save username dans variable session LoggedIn
            $SESSION['LoggedIn'] = $_POST['username'];
            // redirect sur Index
            header("Location: Index.php");
        }
        else
        {
            $errorLogin = "Authentification non reussie";
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
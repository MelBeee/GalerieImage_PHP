<?php
/**
 * Created by PhpStorm.
 * User: 201356187
 * Date: 2015-11-24
 * Time: 10:23
 */
echo "MODIFIER SON PROFIL";

$_SESSION['LoggedIn'] = "MelBeee";
$errorLogin = "";

function VerifyOldPassword($password)
{
    $Fichier = "Authentification.txt";
    $var = $_SESSION['LoggedIn'] . ":" . $password;
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

function WriteInFile($password, $oldpassword)
{
    $Fichier = "Authentification.txt";
    $var = $_SESSION['LoggedIn'] . ":" . $password;
    $oldvar = $_SESSION['LoggedIn'] . ":" . $oldpassword;

    if($AUTHENTIFICATION = file_get_contents($Fichier))
    {
        $AUTHENTIFICATION = str_replace($oldvar, "", $AUTHENTIFICATION);
        file_put_contents($Fichier, $AUTHENTIFICATION);
    }
    if($handle = fopen($Fichier, 'a'))
    {
        fwrite($handle, $var . "\n");
    }
}

if(isset($_POST['ModifierPassword']))
{
    if(empty($_POST['NewPassword']) && empty($_POST['OldPassword']) && empty($_POST['VerifyPassword']))
    {
        $errorLogin = 'Tous les champs doivent etre remplis';
    }
    else
    {
        if($_POST['NewPassword'] == $_POST['VerifyPassword'])
        {
            if(VerifyOldPassword($_POST['OldPassword']))
            {
                WriteInFile($_POST['NewPassword'], $_POST['OldPassword']);
            }
            else
            {
                $errorLogin = 'Ancien mot de passe incorrecte';
            }
        }
        else
        {
            $errorLogin = 'La confirmation du mot de passe est incorrecte';
        }
    }
}
else if(isset($_POST['RetourIndex']))
{
    header("Location: Index.php");
}

echo "<form action='' method='POST' >
        <label for='OldPassword'>Mot de passe courant :</label>
        <input maxlength='20' type='password' name='OldPassword'></br>
        <label for='NewPassword'>Nouveau mot de passe :</label>
        <input maxlength='20' type='password' name='NewPassword'></br>
        <label for='VerifyPassword'>Confirmer mot de passe :</label>
        <input maxlength='20' type='password' name='VerifyPassword'></br>
        <input type='submit' name='ModifierPassword'></br>
        <input type='submit' value='Retour' name='RetourIndex'></br>";

if($errorLogin!="")
{
    echo "<div>
           $errorLogin
          </div>";
}
echo  "</form>";
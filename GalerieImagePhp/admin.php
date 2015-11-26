<?php
/**
 * Created by PhpStorm.
 * User: 201356187
 * Date: 2015-11-24
 * Time: 10:23
 */
$_SESSION['LoggedIn'] = 'admin';
if($_SESSION['LoggedIn'] != 'admin')
{
    header("Location: Index.php");
}

echo "GESTION DES UTILISATEURS";


if(isset($_POST['Supprimer']))
{
    $Fichier = "Authentification.txt";
    $user = $_POST['Supprimer'];

    if($AUTHENTIFICATION = file_get_contents($Fichier))
    {
        $AUTHENTIFICATION = str_replace($user, "", $AUTHENTIFICATION);

        file_put_contents($Fichier, $AUTHENTIFICATION);
    }
}
else if(isset($_POST['RetourIndex']))
{
    header("Location: Index.php");
}


echo "<form action='' method='POST' >";
$handle = fopen("Authentification.txt", 'r');
if($handle)
{
    while(($line = fgets($handle)) !== false)
    {
        if(substr_count($line, ':') > 0 && substr_count($line, 'admin') <= 0)
        {
            $user = substr($line, 0, strpos($line,':'));
            echo "<button type='submit' value='$line' name='Supprimer'>Supprimer</button>";
            echo $user . "<br>";
        }
    }
}

echo "  <input type='submit' value='Retour' name='RetourIndex'></br>";
echo  "</form>";
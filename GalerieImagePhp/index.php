<!DOCTYPE html>
<html>
<?php
session_start();
if(isset($SESSION['LoggedIn']))
{

$extension="";
if(isset($_POST['GestionImageSubmit']))
{
    if(isset($_FILES['ImageToUpload']['name']))
    {
        $extension = pathinfo($_FILES["ImageToUpload"]["name"], PATHINFO_EXTENSION);
        if($extension == "jpg" || $extension == "png" || $extension == "gif" || $extension == "jpeg" ||  $extension == "JPG" || $extension == "PNG" || $extension == "GIF" || $extension == "JPEG")
        {
            $uploadfile = "Image/".uniqid("DepotImage").".".$extension;
            if(move_uploaded_file($_FILES['ImageToUpload']['tmp_name'], $uploadfile))
            {
                header("Location: Index.php");
            }
        }
    }
}

$fichiers = scandir("image");

echo "<form action='Gestimage.php' method='POST' enctype='multipart/form-data' >";
if ($fichiers !== FALSE) {
    // on parcourt le tableau
    for ($i = 0; $i < sizeof($fichiers); $i++) {
        $nomFichier = $fichiers[$i];
        // on évite l'affichage des fichiers cachés
        if ($nomFichier[0] != ".") {
            echo "<button name='ImageClicker' value='image/$nomFichier' type='submit'><img style='max-height:150px; max-width: 200px; height:auto; width:auto; display:block;' src='image/$nomFichier'></button>";
        }
    }
} else {
    die("Erreur: repertoire invalide");
};

echo "</form>";



echo "<form action='' method='POST' enctype='multipart/form-data'>
    <label for='ImageToUpload'>Votre image a upload</label>
    <input type='file' name='ImageToUpload' id='ImageToUpload'>
    <input type='submit' name='GestionImageSubmit'>
</form>

</html>";
}
else
{
    include_once('login.php');
}
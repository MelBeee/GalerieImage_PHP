<!DOCTYPE html>
<html>
<head>
    <style>
        .HeaderShit
        {
            color:grey;
            font-size:25px;
        }
    </style>
</head>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("gestimage.php");
if(isset($_SESSION['LoggedIn']))
{
    $extension="";

    if(isset($_POST['GestionImageSubmit']))
    {
        if(isset($_FILES['ImageToUpload']['name']))
        {
            $extension = pathinfo($_FILES["ImageToUpload"]["name"], PATHINFO_EXTENSION);
            if($extension == "jpg" || $extension == "png" || $extension == "gif" || $extension == "jpeg" ||  $extension == "JPG" || $extension == "PNG" || $extension == "GIF" || $extension == "JPEG")
            {

                $uploadfile = "image/".uniqid($_SESSION['LoggedIn']."(".$_POST['TitreImage']."_".date('j-m-y')."+").".".$extension;
                if(move_uploaded_file($_FILES['ImageToUpload']['tmp_name'], $uploadfile))
                {
                    header("Location: index.php");
                }
            }
        }
    }

    $fichiers = scandir("image");

    echo "<a href='profile.php' class='HeaderShit'>Usager: ".$_SESSION['LoggedIn']."</a>  ";
    if($_SESSION['LoggedIn'] == "admin")
    {
        echo "<a href='admin.php' class='HeaderShit' style>ADMIN_PAGE</a>" ;
    }
    echo "<form action='gestimage.php' method='POST' enctype='multipart/form-data' >";

    if ($fichiers !== FALSE) {
        // on parcourt le tableau
        echo "<table><tr>";
        for ($i = 0; $i < sizeof($fichiers); $i++) {
            echo "<td>";
            $nomFichier = $fichiers[$i];
            $NomUsager = substr($nomFichier, 0 ,strpos($nomFichier,"("));
            $FileTitre = getStringBetween($nomFichier,"(","_");
            $Date = getStringBetween($nomFichier,"_","+");
            $NbCommentaire = 0;
            if($Commentaire = file_get_contents($Fichier))
            {
                $NbCommentaire = substr_count($Commentaire,$nomFichier);
            }
            // on évite l'affichage des fichiers cachés
            if ($nomFichier[0] != ".") {
                echo "<div>";
                echo "<button name='ImageClicker' value='image/$nomFichier' type='submit'><img style='max-height:150px; max-width: 200px; height:auto; width:auto; display:block;' src='image/$nomFichier'></button>";
                echo "<br>";
                echo "Usager :".$NomUsager;
                echo "<br>";
                echo "Titre :".$FileTitre;
                echo "<br>";
                echo "Date :". $Date;
                echo "<br>";
                echo "Nombre de commentaire :".$NbCommentaire;
                echo "</div>";
            }
            echo "</td>";
        }
        echo "</tr></table>";
    } else {
        die("Erreur: repertoire invalide");
    };

    echo "</form>";



    echo "<form action='' method='POST' enctype='multipart/form-data'>
        <label for='TitreImage'>Titre de l'image :</label>
        <input type='text' name='TitreImage' id='TitreImage'>
        <label for='ImageToUpload'>Votre image a upload :</label>
        <input type='file' name='ImageToUpload' id='ImageToUpload'>
        <input type='submit' name='GestionImageSubmit'>
    </form>

    </html>";
}
else
{
    header("Location: login.php");
}
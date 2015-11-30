<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['LoggedIn'] = "MelBeee";

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
    else if (isset($_POST['PageIndex']))
    {
        header("Location: index.php");
    }
    else if (isset($_POST['PageProfil']))
    {
        header("Location: profil.php");
    }
    else if (isset($_POST['PageAdmin']))
    {
        header("Location: admin.php");
    }
    else if (isset($_POST['PageConnecter']))
    {
        header("Location: login.php");
    }

    $fichiers = scandir("image");
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<title>Login</title>";
    echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css\">\n
                 <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css\">\n
                 <link rel=\"stylesheet\" href=\"//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css\" media=\"screen\">
                 <script src=\"//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js\"></script>
                 <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js\"></script>";
    echo "</head>";
    echo "<body  style=\"background-color:#A4D36B\">";


    echo "<div class=\"navbar navbar-inverse navbar-fixed-top\">\n
                <div class=\"container\">\n
                        <div class=\"navbar-header\">\n
                            <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\".navbar-collapse\"></button>
                            <p style=\"color:white; font-size:30px;\"> Galerie d'Image </p>
                        </div>\n
                    <div class=\"navbar-collapse collapse\">\n
                        <ul class=\"nav navbar-nav\">\n
                        <li><a type='submit' name='PageIndex'>Index</a></li>\n
                        <li><a type='submit' name='PageProfil'>Profil</a></li>\n
                        <li><a type='submit' name='PageAdmin'>Admin</a></li>\n
                        <li><a type='submit' name='PageConnecter'>Deconnection</a></li>
                        </ul>
                    </div>
                </div>
            </div>";


    if ($fichiers !== FALSE)
    {

        echo "<div class='container' style='position:absolute; top:20%;'>";
        echo "<form action='gestimage.php' method='POST' enctype='multipart/form-data'>
	         <div class='row>
		     <div class='list-group gallery'>";
        for($i = 0; $i < sizeof($fichiers); $i++) {
            $nomFichier = $fichiers[$i];
            $NomUsager = substr($nomFichier, 0 ,strpos($nomFichier,"("));
            $FileTitre = getStringBetween($nomFichier,"(","_");
            $Date = getStringBetween($nomFichier,"_","+");
            $NbCommentaire = 0;
            if($Commentaire = file_get_contents($Fichier))
            {
                $NbCommentaire = substr_count($Commentaire,$nomFichier);
            }
            if($nomFichier[0] != ".")
            {
                echo "<div class='col-sm-4 col-xs-6 col-md-3 col-lg-3'>
               <a class='thumbnail fancybox'  rel='ligthbox'  name='ImageClicker' value='image/$nomFichier' type='submit'>
                    <img class='img-responsive' style='max-height:150px; max-width: 200px; height:auto; width:auto; display:block;'   src='image/$nomFichier' />
                    <div class='text-center'>
                        <small class='text-muted'><b>$FileTitre</b></small><br>
                        <small class='text-muted'>Usager : $NomUsager</small><br>
                        <small class='text-muted'>Date : $Date</small><br>
                        <small class='text-muted'>Nombre de commentaires : $NbCommentaire</small>
                    </div>
                </a>
                </div> ";
            }
        }
        echo "</div>
            </div>
            </form>
            </div> ";
    }
    else
    {
        die("Erreur: repertoire invalide");
    };

    echo "<form action='' method='POST' enctype='multipart/form-data'>
        <label for='TitreImage'>Titre de l'image :</label>
        <input type='text' name='TitreImage' id='TitreImage'>
        <label for='ImageToUpload'>Votre image a upload :</label>
        <input type='file' name='ImageToUpload' id='ImageToUpload'>
        <input type='submit' name='GestionImageSubmit'>
    </form>";

    echo "  <div class='navbar navbar-inverse navbar-fixed-bottom'>
            <div class='container'>
                <div class='navbar-header'>";
    if(isset($_SESSION['LoggedIn']))
    {
        echo "<p><h5 style='color:white;'>Connecte en tant que ".$_SESSION['LoggedIn']."</h5></p>";
    }
    echo "<p><h8 style='color:white;'>Application fait par Melissa Boucher et Charlie Laplante</h8></p>";
    echo "          </div>
            </div>
        </div>";
    echo "</body>";
    echo "</html>";
}
else
{
    header("Location: login.php");
}
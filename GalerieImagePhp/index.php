<?php

//--- SESSION ET REDIRECTION ---\\

//Si la variable session n'existe pas on la crée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//Si l'usager n'est pas connecté on le redirige vers  la page de connection 
if (!isset($_SESSION['LoggedIn'])) {
    header("Location: login.php");
}
//Inclut la page gestion image
include_once("gestimage.php");

//--- VARIABLES ---\\

// tableau contenant tout les photos televerser sur le site 
$ArrayPhoto = array();
// contient le message d'erreur a afficher
$errorLogin = "";

//--- POST ET GET ---\\

    $extension="";
    //Si le Post a été envoyé par le button de soumission d'image
    if(isset($_POST['GestionImageSubmit']))
    {
        //Check si le TitreImage a été envoyé
        if(isset($_POST['TitreImage']) && $_POST['TitreImage'] != "")
        {
            //Si le input file Image a été envoyé
            if(isset($_FILES['ImageToUpload']['name']))
            {
                //Get l'extension du fichier
                $extension = pathinfo($_FILES["ImageToUpload"]["name"], PATHINFO_EXTENSION);
                if($extension == "jpg" || $extension == "png" || $extension == "gif" || $extension == "jpeg" ||  $extension == "JPG" || $extension == "PNG" || $extension == "GIF" || $extension == "JPEG")
                {
                    //Set le timezone pour que la fonction date retourne les bonnes valeurs
                    date_default_timezone_set("America/New_York");
                    //Ajoute un unique id a la photo pour son enregistrement
                    $uploadfile = uniqid("").".".$extension;
                    //Ouvre le fichier Photo.txt
                    if($Handle = fopen("Photo.txt",'a'))
                    {
                        //Écrit dans le fichier photo.txt le nom de la nouvelle photo sauvegarder
                        fwrite($Handle,$_SESSION['LoggedIn']. "/" . $_POST['TitreImage'] . "~" . date('j M Y, G:i:s') . "_" . $uploadfile . "¯" . "\n" );
                    }
                        //Copie le file choisis dans le dossier image
                    if(move_uploaded_file($_FILES['ImageToUpload']['tmp_name'], "image/".$uploadfile))
                    {
					// redirige a index
                        header("Location: index.php");
                    }
                }
            }
            else
            {
                $errorLogin = "Il faut choisir une image";
            }
        }
        else
        {
            $errorLogin = "Il faut un titre a l'image";
        }
    }
	
	//--- AFFICHAGE HTML ---\\
	
    //Echo le début de la page d'index
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<title>Index</title>";
    echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css\">\n
                 <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css\">
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
                        <li><a  href='index.php' >Index</a></li>\n
                        <li><a  href='profil.php' >Profil</a></li>\n";
                        //Si la valeur de la variable session LoggedIn vaut admin alors on ajoute l'onglet admin
                        if($_SESSION['LoggedIn'] == "admin")
                        {
                            echo "<li><a  href='admin.php' >Admin</a></li>\n";
                        }
                        //déconnection envoit un get a la page login pour qu'elle supprime les coockies et sessions
                        echo "<li><a  href='login.php?deconnecter=true' name='Logout'>Deconnection</a></li>
                        </ul>
                    </div>
                </div>
            </div>";
    echo "<div class='container' style='margin-top:5%; margin-bottom: 5%'>";

	// variable contenant le nom de fichier 
    $directory = "image";
    //Check si le dossier est vide (2 parce qu'il y a tjrs 2 fichiers cachers dans les dossiers)
    $is_empty = (bool) (count(scandir($directory)) == 2);
	// si il n'est pas vide, on affiche les images
    if (!$is_empty)
    {
        echo "<div class='row'>
              <form action='gestimage.php' method='GET' enctype='multipart/form-data'>
		      <div class='list-group gallery'>";
        //Ouvre le fichier photo.txt Pour le lire
        $handle = fopen("Photo.txt", 'r');
        if($handle)
        {
            //Lis les informations et les sauvegardes dans un tableau
            while(($line = fgets($handle)) !== false)
            {
                $Array[] = $line;
            }
            //Ferme le dossier
            fclose($handle);
        }
        //Si le tableau n'est pas vide
        if(!empty($Array))
        {
            //Lis ce que contient le tableau en partant de la fin
            for($i = count($Array)-1 ; $i >= 0 ; $i--)
            {
                if(strlen($Array[$i]) > 5)
                {
                     //Décortique la ligne dans le tableau pour trouver les informations nécessaires lors de l'affichage
                    $line = $Array[$i];
                    $User = substr($line, 0, strpos($line, '/'));
                    $Titre = getStringBetween($line, '/', '~');
                    $Date = getStringBetween($line, '~', '_');
                    $Guid = getStringBetween($line, '_', '¯');
                    $NbCommentaire = 0;
                    //Check le nombre de ligne dans le fichier qui sauvegarde les commentaires (Savoir combien il y de commentaire)
					$Fichier = "Commentaire.txt";
                    if($Commentaire = file_get_contents($Fichier))
                    {
                        $NbCommentaire = substr_count($Commentaire,$Guid);
                    }
                    //Fait apparaitre photo et information
                    echo "<div class='col-sm-4 col-xs-6 col-md-3 col-lg-3'>
                  <a class='thumbnail fancybox'  rel='ligthbox' href='gestimage.php?image=image/$Guid' name='ImageClicker' type='submit'>
                     <img class='img-responsive' style='max-height:150px; max-width: 200px; height:auto; width:auto; display:block;'   src='image/$Guid' />
                     <div class='text-center'>
                        <small class='text-muted'><b>$Titre</b></small><br>
                        <small class='text-muted'>$User</small><br>
                        <small class='text-muted'>$Date</small><br>
                        <small class='text-muted'>$NbCommentaire commentaires</small>
                     </div>
                  </a>
               </div> ";
                }
            }
        }

        echo "</div>
            </form>
            </div>
             ";
    }
    echo "<div class='row'>
    	<div class='col-md-4 col-md-offset-4'>
    		<div class='panel panel-default'>
			  	<div class='panel-heading'>
			    	<h3 class='panel-title'>Televerser une image</h3>
			 	</div>
			  	<div class='panel-body'>
			    	<form accept-charset='UTF-8' enctype='multipart/form-data' method='POST' role='form'>
                    <fieldset>
			    	  	<div class='form-group'>
			    		    <input class='form-control' maxlength='20' placeholder='Titre de l&#39;image' name='TitreImage' type='text'>
			    		</div>
			    		<div class='form-group'>
                            <input type='file' name='ImageToUpload' id='ImageToUpload'>
			    		</div>
			    		<input class='btn btn-lg btn-success btn-block' name='GestionImageSubmit' type='submit' value='Valider'>
                     </fieldset>
			      	</form>";
                    //Si la variable d'erreur est vide on ne la montre  pas
                    if($errorLogin!='')
                    {
                        echo "<div> $errorLogin </div>";
                    }
                    echo "</div>
			</div>
		</div>
    </div></div>";

    echo "  <div class='navbar navbar-inverse navbar-fixed-bottom'>
            <div class='container'>
                <div class='navbar-header'>";
    //Si la variable session n'est pas vide alors on montre qui est connecter
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

<?php

//--- SESSION ET REDIRECTION ---\\

//Si la variable session n'existe pas on la crée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//Si l'usager n'est pas logged in on le renvoit à login
if (!isset($_SESSION['LoggedIn'])) {
    header("Location: login.php");
}

//--- VARIABLES ---\\

// variable contenant le nom d'un fichier a ouvrir 
$Fichier = "";
// tableau qui va contenir les commentaires 
$Array = array();
// variable qui va contennir le nom du proprietaire de l'image
$ProprioImage = "";
// variable qui va contenir une erreur a affichier 
$errorLogin = "";

//--- FONCTIONS ---\\

//Fonction qui retourne la string qui se retrouve entre 2 characteres
function getStringBetween($str, $from, $to)
{
    $sub = substr($str, strpos($str, $from) + strlen($from), strlen($str));
    return substr($sub, 0, strpos($sub, $to));
}

//trouve le propriétaire de l'image en analysant le nom de l'image
function getProprioImage()
{
	// variable contenant le nom du proprietaire
    $ProprioImage = "rien-";
	// variable contenant le nom du fichier 
    $Fichier = "Photo.txt";
	// ouverture du fichier 
    if ($PHOTO = file_get_contents($Fichier)) {
        $handle = fopen($Fichier , 'r');
        if ($handle) {
		// on remplis le tableau avec chaque ligne du fichier texte
            while (($line = fgets($handle)) !== false) {
                $Array[] = $line;
            }
            fclose($handle);
        }
    }
	// trouve le nom du proprietaire pour savoir si on peut supprimer ou non l'image 
    $Trouver = false;
	// tant qu'on a pas fait tout les lignes et qu'on a pas trouver le proprietaire
    for ($i = 0; $i < count($Array) && !$Trouver; $i++) {
		// si le proprietaire trouver est le meme que LoggedIn, c'est qu'on peut supprimer
        if (!$Trouver && $_SESSION['LoggedIn'] == substr($Array[$i], 0, strpos($Array[$i], '/')) &&  $_SESSION['ImageCommentaire'] == "image/".getStringBetween($Array[$i], '_', '¯')) {
            $ProprioImage = substr($Array[$i], 0, strpos($Array[$i], '/'));
            $Trouver = true;
        }
    }
    return $ProprioImage;
}

//Analyse les commentaires pour les afficher en ordre
function ProccessComment()
{
// ouvre le fichier commentaire 
    $handle = fopen("Commentaire.txt", "r");
    if ($handle) {
	// on cherche dans chaque ligne 
        while (($line = fgets($handle)) !== false) {
		// si le nom de l'image est dans la ligne on get la ligne dans le tableau
            if (strpos($line, $_SESSION['ImageCommentaire']) !== false) {
                $Array[] = getStringBetween($line, "*", "~");
            }
        }
        fclose($handle);
    }
    //Si le tableau n'est pas vide alors on affiche son contenue avec style et grâce
    if (!empty($Array)) {
	// pour chaque ligne dans le tableau, on affiche son information
        for ($i = count($Array) - 1; $i >= 0; $i--) {
		// get le nom utilisateur dans la chaine 
            $user = substr($Array[$i], 0, strpos($Array[$i], '_'));
		// get le commentaire dans la chaine
            $comment = getStringBetween($Array[$i], "_", "/");
		// get la date dans la chaine 
            $date = getStringBetween($Array[$i], "/", "¯");
		// affiche le tout 
            echo "
                <hr data-brackets-id='12673'>
                <ul data-brackets-id='12674' id='sortable' class='list-unstyled ui-sortable'>
                    <strong class='pull-left primary-font'>$user</strong>
                    <small class='pull-right text-muted'>
                    <span class='glyphicon glyphicon-time'></span>$date</small>
                    </br>
                    <li class='ui-state-default'>$comment</li>
                    </br>
                </ul>
            ";
        }
    }
}

//--- POST ET GET ---\\

//Si le post vient du boutton Supprimer
if (isset($_POST['SupprimerImage'])) {
    //Ouvre le fichier photo est supprime la ligne de l'image
    $Fichier = "Photo.txt";
    $Trouver = false;
    $LaPhoto = "";

    $handle = fopen("Photo.txt", 'r');
    if($handle)
    {
        $Image = substr($_SESSION['ImageCommentaire'],6, strlen($_SESSION['ImageCommentaire'])-6);
        //Lis les informations et les sauvegardes dans un tableau
        while(($line = fgets($handle)) !== false && !$Trouver)
        {
            if(strpos($line, $Image) !== false)
            {
                echo $line;
                $LaPhoto = $line;
                $Trouver = true;
            }
        }
        //Ferme le fichier
        fclose($handle);
    }

    if($Trouver)
    {
        //Supprime l'image
        unlink($_POST['ImageSupp']);
        // ouvre le fichier
        if ($AUTHENTIFICATION = file_get_contents($Fichier)) {
            // remplace la ligne par du vide
            $AUTHENTIFICATION = str_replace($LaPhoto, "", $AUTHENTIFICATION);

            file_put_contents($Fichier, $AUTHENTIFICATION);
        }

        //Renvoit à la page de la galerie
        header("Location: index.php");
    }
}
//Si le post vient d'envoyer
if (isset($_POST['EnvoyerCommentaire'])) {
    //Si le commentaire n'est pas vide
    if ($_POST['LeCommentaire'] != "") {
	$Fichier = "Commentaire.txt";
        //On écrit le commentaire dans le file  avec le nom d'usager et la date d'écriture
        if ($Handle = fopen($Fichier, 'a')) {
            fwrite($Handle, "*" . $_SESSION['LoggedIn'] . "_" . $_POST['LeCommentaire'] . "/" . date('j M Y, G:i:s') . "¯" . "~" . $_SESSION['ImageCommentaire'] . "\n");
        }
    }
}
//S'il y a un get d'image qui vient de la page index alors on set l'image a afficher pour celle que l'Usager a choisis
if (isset($_GET['image'])) {
    $_SESSION['ImageCommentaire'] = $_GET['image'];
    //Fait apparaitre les forms
    gestImageMain();
}

//--- AFFICHAGE HTML ---\\

function gestImageMain()
{
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<title>Image</title>";
    echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css\">\n
                 <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css\">\n
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
                                    <li><a  href='profil.php'>Profil</a></li>\n";
                                    if($_SESSION['LoggedIn'] == "admin")
                                    {
                                        echo "<li><a  href='admin.php' >Admin</a></li>\n";
                                    }
                                    echo "<li><a  href='login.php?deconnecter=true' name='Logout'>Deconnection</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>";

    echo "<div class='container' style='margin-top:5%; margin-bottom:5%;'>";
    echo "<div class='row'>";
    echo "<div class='col-md-4 col-md-offset-4'>";
    echo "<img style='max-height:600px; max-width: 800px; height:auto; width:auto; display:block;' src=" . $_SESSION['ImageCommentaire'] . " >";
    echo "</div> </div>";
    echo "<div class='row'>";
    echo "<div class='col-md-4 col-md-offset-4'>";
    echo "<form method='POST' enctype='multipart/form-data'>";
    if (GetProprioImage() == $_SESSION['LoggedIn'] || $_SESSION['LoggedIn'] == "admin") {
        echo "<input class='btn btn-lg btn-success btn-block' name='SupprimerImage' type='submit' value='Supprimer'>
                   <input type='hidden' name='ImageSupp' value=", $_SESSION['ImageCommentaire'] . ">";
    }
    echo "</form>";
    echo "</div> </div>";

    echo "<div class='row'>";
    echo "<div class='col-md-offset-4'>";
    echo "
<form method='POST' enctype='multipart/form-data'>
<div class='col-lg-8 col-sm-12 text-center'>
    <div class='well'>
        <h4>Commentaires de la photo</h4>
        <div class='input-group'>
            <input type='text' maxlength='150' name='LeCommentaire' class='form-control input-sm chat-input' placeholder='Ecrivez votre commentaire ici...' />
            <span class='input-group-btn'>
                <button type='submit' name='EnvoyerCommentaire' class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-comment'></span> Commenter</button>
            </span>
        </div>";
    ProccessComment();
    echo "</div>
    </div>
</form>";
    echo "</div> </div>";

    echo "  <div class='navbar navbar-inverse navbar-fixed-bottom'>
            <div class='container'>
            <div class='navbar-header'>";
    if (isset($_SESSION['LoggedIn'])) {
        echo "<p><h5 style='color:white;'>Connecte en tant que " . $_SESSION['LoggedIn'] . "</h5></p>";
    }
    echo "<p><h8 style='color:white;'>Application fait par Melissa Boucher et Charlie Laplante</h8></p>";
    echo "          </div>
            </div>
        </div>";
    echo "</body>";
    echo "</html>";
}



<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$Fichier = "Commentaire.txt";
$Array = array();
$ProprioImage = "";
$errorLogin = "";
//Si le post vient du boutton Supprimer
if (isset($_POST['SupprimerImage'])) {
    //Supprime l'image
    unlink($_POST['ImageSupp']);
    //Ouvre le fichier photo est supprime la ligne de l'image
    $Fichier = "Photo.txt";
    $substring = substr($_SESSION['ImageCommentaire'], strpos($_SESSION['ImageCommentaire'], '/'), sizeof($_SESSION['ImageCommentaire']) - 6);
    if ($PHOTO = file_get_contents($Fichier)) {
        $PHOTO = str_replace($substring, "", $PHOTO);

        file_put_contents($Fichier, $PHOTO);
    }
    //Renvoit à la page de la galerie
    header("Location: Index.php");
}
//Si le post vient d'envoyer
if (isset($_POST['EnvoyerCommentaire'])) {
    //Si le commentaire n'est pas vide
    if ($_POST['LeCommentaire'] != "") {
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
//Fonction qui retourne la string qui se retrouve entre 2 characters
function getStringBetween($str, $from, $to)
{
    $sub = substr($str, strpos($str, $from) + strlen($from), strlen($str));
    return substr($sub, 0, strpos($sub, $to));
}
//trouve le propriétaire de l'image en analysant le nom de l'image
function getProprioImage()
{
    $ProprioImage = "rien-";
    $Fichier = "Photo.txt";
    if ($PHOTO = file_get_contents($Fichier)) {
        $handle = fopen("Photo.txt", 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $Array[] = $line;
            }
            fclose($handle);
        }
    }
    $Trouver = false;
    for ($i = 0; $i < count($Array) && !$Trouver; $i++) {
        if (!$Trouver && $_SESSION['LoggedIn'] == substr($Array[$i], 0, strpos($Array[$i], '/')) &&  $_SESSION['ImageCommentaire'] == "image/".getStringBetween($Array[$i], '_', '¯')) {
            $ProprioImage = substr($Array[$i], 0, strpos($Array[$i], '/'));
            $Trouver = true;
        }
    }

    return $ProprioImage;
}


//Analise les commentaire pour les mettres dans ordre d'écriture
function ProccessComment()
{
    $handle = fopen("Commentaire.txt", "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            if (strpos($line, $_SESSION['ImageCommentaire']) !== false) {

                $Array[] = getStringBetween($line, "*", "~");
            }
        }

        fclose($handle);
    }
    //Si le tableau n'est pas vide alors on affiche son contenue avec style et grâce
    if (!empty($Array)) {
        for ($i = count($Array) - 1; $i >= 0; $i--) {
            $user = substr($Array[$i], 0, strpos($Array[$i], '_'));
            $comment = getStringBetween($Array[$i], "_", "/");
            $date = getStringBetween($Array[$i], "/", "¯");

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
//Fonction qui fait apparaitre la page avec tout les forms
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

    //////////////////////////////////////////////////////////////////////////////
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
    //////////////////////////////////////////////////////////////////////////////

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



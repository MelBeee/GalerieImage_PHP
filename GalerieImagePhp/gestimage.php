<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$Fichier = "Commentaire.txt";
$Array = array();
$ProprioImage = "";
$errorLogin = "";

if (isset($_POST['SupprimerImage'])) {
    unlink($_POST['ImageSupp']);
    $Fichier = "Photo.txt";
    $substring = substr($_SESSION['ImageCommentaire'], strpos($_SESSION['ImageCommentaire'], '/'), sizeof($_SESSION['ImageCommentaire']) - 6);
    if ($PHOTO = file_get_contents($Fichier)) {
        $PHOTO = str_replace($substring, "", $PHOTO);

        file_put_contents($Fichier, $PHOTO);
    }

    header("Location: Index.php");
}

if (isset($_POST['EnvoyerCommentaire'])) {
    if ($Handle = fopen($Fichier, 'a')) {
        fwrite($Handle, "*" . $_SESSION['LoggedIn'] . "_" . $_POST['LeCommentaire'] . "/" . date('j M Y, G:i:s') . "¯" . "~" . $_SESSION['ImageCommentaire'] . "\n");
    }
}

if (isset($_GET['image'])) {
    $_SESSION['ImageCommentaire'] = $_GET['image'];
    gestImageMain();
}

function getProprioImage()
{
    $ProprioImage = "";
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
        $substring = substr($_SESSION['ImageCommentaire'], strpos($_SESSION['ImageCommentaire'], '/'), sizeof($_SESSION['ImageCommentaire']) - 6);
        if (substr_count($Array[$i], $substring) > 0 && !$Trouver) {
            $ProprioImage = substr($Array[$i], 0, strpos($Array[$i], '/'));
            $Trouver = true;
        }
    }

    return $ProprioImage;
}

function getStringBetween($str, $from, $to)
{
    $sub = substr($str, strpos($str, $from) + strlen($from), strlen($str));
    return substr($sub, 0, strpos($sub, $to));
}

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

function gestImageMain()
{
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<title>Login</title>";
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
                                    <li><a  href='profil.php'>Profil</a></li>\n
                                    <li><a  href='admin.php' >Admin</a></li>\n
                                    <li><a  href='login.php' >Deconnection</a></li>
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



<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['LoggedIn'] != 'admin') {
    header("Location: Index.php");
}

if (isset($_POST['Supprimer'])) {
    $Fichier = "Authentification.txt";
    $user = $_POST['Supprimer'];

    if ($AUTHENTIFICATION = file_get_contents($Fichier)) {
        $AUTHENTIFICATION = str_replace($user, "", $AUTHENTIFICATION);

        file_put_contents($Fichier, $AUTHENTIFICATION);
    }
}

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Login</title>";
echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css\">\n
                 <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css\">\n
                 <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js\"></script>";
echo "</head>";
echo "<body  style=\"background-color:#A4D36B\">";
echo " <div class=\"navbar navbar-inverse navbar-fixed-top\">\n
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

echo "<form action='' method='POST' >
<div class='container' style='margin-top:5%; margin-bottom:5%;'>
    <div class='row'>
    	<div class='col-md-4 col-md-offset-4'>
    		<div class='panel panel-default'>
			  	<div class='panel-heading'>
			    	<h3 class='panel-title'>Supprimer un usager</h3>
			 	</div>
			  	<div class='panel-body'>

                    <fieldset>";
$handle = fopen("Authentification.txt", 'r');
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        if (substr_count($line, ':') > 0 && substr_count($line, 'admin') <= 0) {
            $user = substr($line, 0, strpos($line, ':'));
            echo "<button class='btn btn-lg btn-success btn-block' name='Supprimer' type='submit' value='$line'>Supprimer $user</button>";
            echo "<br>";
        }
    }
}
echo " </fieldset>
			    </div>
			</div>
		</div>
	</div>
</div>";
$handleLog = fopen('LogFile.txt', 'r');

if($handleLog)
{
    while (($lineLog = fgets($handleLog)) !== false) {
            $Array[] = $lineLog;
    }
    if (!empty($Array)) {
        for ($i = count($Array) - 1; $i >= count($Array) - 10 ; $i--) {
            echo $Array[$i].'<br>';
        }

    }


}
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
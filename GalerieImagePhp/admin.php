<?php

//--- SESSION ET REDIRECTION ---\\

//Si la variable session n'existe pas on la crée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//Si l'usager n'est pas connecté en tant qu'admin alors on le renvoit à index
if ($_SESSION['LoggedIn'] != 'admin') {
    header("Location: index.php");
}

//--- FONCTIONS ---\\

//Fonction qui retourne la string qui se retrouve entre 2 characteres
function getStringBetween($str, $from, $to)
{
    $sub = substr($str, strpos($str, $from) + strlen($from), strlen($str));
    return substr($sub, 0, strpos($sub, $to));
}

// Affiche tout les usagers qui existe pour permettre a l'admin de les supprimer
function AfficherUsers()
{
	// ouvre le fichier authentification
	$handle = fopen("Authentification.txt", 'r');
	if ($handle) {
		// pour chaque ligne on affiche le nom et on crée un bouton
		while (($line = fgets($handle)) !== false) {
			if (substr_count($line, ':') > 0 && substr_count($line, 'admin') <= 0) {
				$user = substr($line, 0, strpos($line, ':'));
				echo "<button class='btn btn-lg btn-success btn-block' name='Supprimer' type='submit' value='$line'>Supprimer $user</button>";
				echo "<br>";
			}
		}
	}
}

//--- POST ET GET ---\\

// Si on post le supprimer
if (isset($_POST['Supprimer'])) {
// fichier texte a ouvrir 
    $Fichier = "Authentification.txt";
// get le nom que l'admin a clicker pour le supprimer
    $user = $_POST['Supprimer'];
// ouvre le fichier 
    if ($AUTHENTIFICATION = file_get_contents($Fichier)) {
	// remplace la ligne par du vide 
        $AUTHENTIFICATION = str_replace($user, "", $AUTHENTIFICATION);
		
        file_put_contents($Fichier, $AUTHENTIFICATION);
    }
}

//--- AFFICHAGE HTML ---\\

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Admin</title>";
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

echo "<form action='' method='POST' >
<div class='container' style='margin-top:5%; margin-bottom:5%;'>
    <div class='row'>
    	<div class='col-md-4 col-md-offset-4'>
    		<div class='panel panel-default'>
			  	<div class='panel-heading'>
			    	<h3 class='panel-title'>Supprimer un usager</h3>
			 	</div>
			  	<div class='panel-body'  style='overflow:scroll; height:350px;'>

                    <fieldset>";
AfficherUsers();
echo " </fieldset>
			    </div>
			</div>
		</div>
	</div>";

echo "
<div class='row'>
    	<div class='col-md-4 col-md-offset-4'>
    		<div class='panel panel-default'>
			  	<div class='panel-heading'>";
$handleLog = fopen('LogFile.txt', 'r');
if($handleLog)
{
    echo "
    <div class='well'>
    <table class='table'>
      <thead>
        <tr>
          <th>#</th>
          <th>Utilisateur</th>
          <th>Date</th>
          <th>Adresse IP</th>
        </tr>
      </thead>
      <tbody>";
    while (($lineLog = fgets($handleLog)) !== false) {
        $Array[] = $lineLog;
    }
    if (!empty($Array)) {
        $cpt = 1;
        for ($i = count($Array) - 1; $i >= count($Array) - 10 ; $i--) {
            if($i >= 0)
            {
                $username= substr($Array[$i], 0, strpos($Array[$i], ':'));
                $date =  getStringBetween($Array[$i], ':', '/');
                $ipadress = getStringBetween($Array[$i], '/', '-');
                echo "
            <tr>
              <td>$cpt</td>
              <td>$username</td>
              <td>$date</td>
              <td>$ipadress</td>
            </tr>";
                $cpt++;
            }
        }
    }
    echo "</tbody>
</table>
</div>";
}
echo "        </div>
            </div>
        </div>
    </div>
</div>";

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
<?php

//--- SESSION ET REDIRECTION ---\\

// si la session n'est pas starter, on la start
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//Si l'usager n'est pas connecté on le redirige vers  la page de connection 
if (!isset($_SESSION['LoggedIn'])) {
    header("Location: login.php");
}

//--- VARIABLES ---\\

// variable contenant les messages d'erreur a afficher a l'utilisateur
$errorLogin = "";

//--- FONCTIONS ---\\

//Vérifie si l'ancien mot de passe entré par l'usager est bon
function VerifyOldPassword($password)
{
	// variable contenant le nom du fichier 
    $Fichier = "Authentification.txt";
	// variable contenant la ligne qui devrait etre dans le fichier 
	// nomutilisateur.motdepasse
    $var = $_SESSION['LoggedIn'] . ":" . $password;
	// on ouvre le fichier texte 
    if ($AUTHENTIFICATION = file_get_contents($Fichier)) {
	// on compte le nombre de ligne contenant la variable $var 
        $existe = substr_count($AUTHENTIFICATION, $var);
    }
	// s'il il y a aucune ligne de retourner ca veut dire qu'il a entré un mauvais mot de passe 
	// on retourne donc false 
    if ($existe == 0) {
        return false;
    }
	// sinon on retourne true parce qu'il a entrer le bon mot de passe 
    return true;
}

//Écrire dans le file le nouveau mot de passe
function WriteInFile($password, $oldpassword)
{
	// variable contenant le fichier a ouvrir 
    $Fichier = "Authentification.txt";
	// la ligne qu'on veut ecrire 
    $var = $_SESSION['LoggedIn'] . ":" . $password;
	// la ligne qu'on veut remplacer 
    $oldvar = $_SESSION['LoggedIn'] . ":" . $oldpassword;

	// on ouvre le fichier 
    if ($AUTHENTIFICATION = file_get_contents($Fichier)) {
		// on remplace la ligne par la nouvelle ligne 
        $AUTHENTIFICATION = str_replace($oldvar, $var, $AUTHENTIFICATION);
		// on remet le tout dans le fichier 
        file_put_contents($Fichier, $AUTHENTIFICATION);
    }
}

//--- POST ET GET ---\\ 

//Si le post est envoyé par le bouton de modification de mot de passe
if (isset($_POST['ModifierPassword'])) {
    //Si les inputs sont vides
    if (empty($_POST['NewPassword']) && empty($_POST['OldPassword']) && empty($_POST['VerifyPassword'])) {
        $errorLogin = 'Tous les champs doivent etre remplis';
    } else {
        //Si le nouveau mot de passe est identique au vieux mot de passe
        if (strcmp( $_POST['NewPassword'], $_POST['VerifyPassword']) === 0){
            if (VerifyOldPassword($_POST['OldPassword'])) {
				
                WriteInFile($_POST['NewPassword'], $_POST['OldPassword']);
            } else {
                $errorLogin = 'Ancien mot de passe incorrecte';
            }
        } else {
            $errorLogin = 'La confirmation du mot de passe est incorrecte';
        }
    }
}

//Si le post rester connecter est envoyé
if(isset($_POST['ResterConnecter']))
{
    //Si le checkbox connecté est cocher
    if(isset($_POST['Connected']))
    {
		// on creer un cookie avec la variable loggedin qui sera detruit dans 24h 
        setcookie("Connected", $_SESSION['LoggedIn'], time()+86400 , "/");
    }
}
//Si le coockie Connected n'est pas vide
if(isset($_COOKIE['Connected']))
{
    $_SESSION['LoggedIn'] = $_COOKIE['Connected'];
    header("Location: Index.php");
}

//--- AFFICHAGE HTML ---\\

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Profil</title>";
echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css\">\n
                 <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css\">\n
                 <link rel='stylesheet' href='CheckedBox.css'>
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
                //Si l'usager est connecté en tant qu'admin alors on rajoute l'onglet admin
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
<div class='container' style='margin-top:5%; margin-bottom: 5%'>
    <div class='row'>
    	<div class='col-md-4 col-md-offset-4'>
    		<div class='panel panel-default'>
			  	<div class='panel-heading'>
			    	<h3 class='panel-title'>Changer le mot de passe</h3>
			 	</div>
			  	<div class='panel-body'>
			    	<form accept-charset='UTF-8' role='form'>
                    <fieldset>
			    	  	<div class='form-group'>
			    		    <input class='form-control' maxlength='20'  placeholder='Mot de passe courant' name='OldPassword' type='password'>
			    		</div>
			    		<div class='form-group'>
			    			<input class='form-control' maxlength='20'  placeholder='Nouveau Mot de passe' name='NewPassword' type='password'>
			    		</div>
			    		<div class='form-group'>
			    			<input class='form-control' maxlength='20'  placeholder='Confirmation Mot de passe' name='VerifyPassword' type='password'>
			    		</div>
			    		<input class='btn btn-lg btn-success btn-block' name='ModifierPassword' type='submit' value='Valider'>";
                    if ($errorLogin != '') {
                        echo "<div> $errorLogin </div>";
                    }
                    echo "</fieldset>
			      	</form>
			    </div>
			</div>
		</div>
	</div>
	<br>
	<div class='row'>
        <div class='col-md-4 col-md-offset-4'>
            <div class='panel panel-default'>
                <div class='panel-heading'>
                    <h3 class='panel-title'>Rester connecter pendant 24 heures</h3>
                </div>
                <div class='panel-body'>
                    <div class='funkyradio'>
                        <div class='funkyradio-success'>
                            <input type='checkbox' name='Connected' id='checkbox3' checked/>
                            <label for='checkbox3'>Rester Connecte</label>
                        </div>
                    </div>
                    <input class='btn btn-lg btn-success btn-block' name='ResterConnecter' type='submit' value='Valider'>
                </div>
            </div>
        </div>
	</div>
</div>";


echo "  <div class='navbar navbar-inverse navbar-fixed-bottom'>
            <div class='container'>
                <div class='navbar-header'>";
//Si l'usager est logged in alors on fait écrit en tant que qui
if (isset($_SESSION['LoggedIn'])) {
    echo "<p><h5 style='color:white;'>Connecte en tant que " . $_SESSION['LoggedIn'] . "</h5></p>";
}
echo "<p><h8 style='color:white;'>Application fait par Melissa Boucher et Charlie Laplante</h8></p>";
echo "          </div>
            </div>
        </div>";
echo "</body>";
echo "</html>";
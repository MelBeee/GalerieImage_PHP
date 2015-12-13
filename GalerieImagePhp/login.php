<?php

//--- SESSION ET REDIRECTION ---\\

// si la session n'est pas starter, on la start
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Si la variable session existe déja on redirect tout de suite à la galerie d'image
if(isset($_SESSION['LoggedIn']))
{
    header("Location: index.php");
}

//Set le timezone pour que la fonction date retourne les bonnes valeurs
date_default_timezone_set("America/New_York");

//--- VARIABLES ---\\

//Variable d'erreur
$errorLogin = "";

//--- FONCTIONS ---\\

//Fonction qui valide le login
function validateLogin($user, $password)
{
	// variable contenant le nom du fichier a ouvrir
    $Fichier = "Authentification.txt";
	// variable contenant la chaine de caractere a verifier dans le fichier 
	// nomutilisateur.motdepasse
    $var = $user . ":" . $password;
	// ouvre le fichier 
    if ($AUTHENTIFICATION = file_get_contents($Fichier)) {
		// on compte le nombre de ligne qui contient la chaine de caractere
        $existe = substr_count($AUTHENTIFICATION, $var);
    }
	// s'il y en a pas, on retourne false pour pas se connecter 
    if ($existe == 0) {
        return false;
    }
	// sinon on retourne true pour se connecter 
    return true;
}

//Fonction qui écrit dans le file qui garde en mémoire lorsqu'un usager se connecte
function WriteInLog($Username,$Date,$Ip)
{
	// variable contenant le nom du fichier a ouvrir
    $Fichier = "LogFile.txt";
	// constitution de la chaine de caracteres a ecrire dans le fichier 
    $var = $Username.":".$Date."/".$Ip."-";
	// on ouvre le fichier
    if ($handle = fopen($Fichier, 'a')) {
	// on ajoute la chiane de caracteres 
        fwrite($handle, $var . "\n");
    }
}

//Si la page a été envoyé par le button déconnecter alors on détruit le cookie et la session
if(isset($_GET['deconnecter']))
{
    unset($_COOKIE['Connected']);
    setcookie('Connected', '', time() - 3600, '/');
    if (isset($_SESSION['LoggedIn'])) {
        session_unset($_SESSION['LoggedIn']);
        session_destroy();
        header("Location: login.php");
    }
}

//--- POST ET GET ---\\

//Si le post Connecter est envoyé
if (isset($_POST['Connecter'])) {
	// on verifie si l'utilsiateur a écrit des choses dans les textbox 
    if (empty($_POST['username']) && empty($_POST['password'])) {
        $errorLogin = 'Les deux champs ne peuvent etre vide';
    } else {
		// on verifie s'il a ecrit les bons identifiants 
        if (validateLogin($_POST['username'], $_POST['password'])) {
            // enregistre la valeur de username dans la variable session LoggedIn
            $_SESSION['LoggedIn'] = $_POST['username'];
			// on ecrit dans le log 
            WriteInLog($_POST['username'],date('j M Y, G:i:s'),$_SERVER['REMOTE_ADDR']);
            // redirect sur Index
            header("Location: index.php");
        } else {
            $errorLogin = "Authentification non reussie";
        }
    }
}

//--- AFFICHAGE HTML ---\\

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Login</title>";
echo "<meta charset='UTF-8'>";
echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css\">\n
                 <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css\">\n
                 <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js\"></script>";
echo "</head>";
echo "<body  style=\"background-color:#A4D36B\">";
echo "
<div class=\"navbar navbar-inverse navbar-fixed-top\">\n
    <div class=\"container\">\n
            <div class=\"navbar-header\">\n
                <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\".navbar-collapse\"></button>
                <p style=\"color:white; font-size:30px;\"> Galerie d'Image </p>
            </div>\n
        <div class=\"navbar-collapse collapse\">\n
            <ul class=\"nav navbar-nav\">\n
                <li><a  href='index.php' >Index</a></li>\n
                <li><a  href='login.php' >Connexion</a></li>
            </ul>
        </div>
    </div>
</div>
<div class='container' style='margin-top:5%; margin-bottom: 5%'>
    <div class='row'>
    	<div class='col-md-4 col-md-offset-4'>
    		<div class='panel panel-default'>
			  	<div class='panel-heading'>
			    	<h3 class='panel-title'>Connexion</h3>
			 	</div>
			  	<div class='panel-body'>
			    	<form accept-charset='UTF-8' method='post' role='form'>
                    <fieldset>
			    	  	<div class='form-group'>
			    		    <input class='form-control' id='username' placeholder='Nom utilisateur' name='username' type='text'>
			    		</div>
			    		<div class='form-group'>
			    			<input class='form-control' id='password' placeholder='Mot de Passe' name='password' type='password'>
			    		</div>
			    		<input class='btn btn-lg btn-success btn-block' name='Connecter' id='Connecter' type='submit' value='Se connecter'>";
//Si la variable n'est pas vide on echo la variable d'erreur
if ($errorLogin != '') {
    echo "<div> $errorLogin </div>";
}
echo "</fieldset>
			      	</form>
			    </div>
			</div>
		</div>
	</div>
</div>";
//Echo le form le connexion
echo "</form>";
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
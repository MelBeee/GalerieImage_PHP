<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set("America/New_York");

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

if (isset($_SESSION['LoggedIn'])) {
    session_unset($_SESSION['LoggedIn']);
    session_destroy();
    header("Location: login.php");
}
if(isset($_GET['deconnecter']))
{
    unset($_COOKIE['Connected']);
    setcookie('Connected', '', time() - 3600, '/');
}
if(isset($_COOKIE['Connected']))
{
    $_SESSION['LoggedIn'] = $_COOKIE['Connected'];
    header("Location: Index.php");
}

$errorLogin = "";
function validateLogin($user, $password)
{
    $Fichier = "Authentification.txt";
    $var = $user . ":" . $password;
    if ($AUTHENTIFICATION = file_get_contents($Fichier)) {
        $existe = substr_count($AUTHENTIFICATION, $var);
    }
    if ($existe == 0) {
        return false;
    }
    return true;
}

function WriteInLog($Username,$Date,$Ip)
{
    $Fichier = "LogFile.txt";
    $var = $Username.":".$Date.":".$Ip;

    if ($handle = fopen($Fichier, 'a')) {
        fwrite($handle, $var . "\n");
    }
}

if (isset($_POST['Connecter'])) {
    if (empty($_POST['username']) && empty($_POST['password'])) {
        $errorLogin = 'Les deux champs ne peuvent etre vide';
    } else {
        if (validateLogin($_POST['username'], $_POST['password'])) {
            // save username dans variable session LoggedIn
            $_SESSION['LoggedIn'] = $_POST['username'];
            if(isset($_POST['Connected']))
            {
                setcookie("Connected", $_POST['username'], time()+86400 , "/");
            }
            WriteInLog($_POST['username'],date('j M Y, G:i:s'),$_SERVER['REMOTE_ADDR']);

            // redirect sur Index
            header("Location: Index.php");
        } else {
            $errorLogin = "Authentification non reussie";
        }
    }
}

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
                <li><a  href='profil.php' >Profil</a></li>\n
                <li><a  href='admin.php' >Admin</a></li>\n
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
if ($errorLogin != '') {
    echo "<div> $errorLogin </div>";
}
echo "<input type='checkbox' name='Connected' value='Connected'> Rester Connecté";
echo "</fieldset>
			      	</form>
			    </div>
			</div>
		</div>
	</div>
</div>";


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
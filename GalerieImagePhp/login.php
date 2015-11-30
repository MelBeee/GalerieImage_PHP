<?php

    session_start();

			echo "<!DOCTYPE html>";
            echo "<html>";
            echo "<head>";
            echo "<title>Login</title>";
            echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css\">\n
                 <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css\">\n
                 <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js\"></script>";
            echo "</head>";
			echo "<body  style=\"background-color:#A4D36B\">";

if(isset($_SESSION['LoggedIn']))
{
    session_unset($_SESSION['LoggedIn']);
    session_destroy();
    header("Location: login.php");
}

$errorLogin ="";
function validateLogin($user,$password)
{
    $Fichier = "Authentification.txt";
    $var = $user . ":" . $password;
    if($AUTHENTIFICATION = file_get_contents($Fichier))
    {
        $existe = substr_count($AUTHENTIFICATION, $var);
    }
    if($existe == 0)
    {
        return false;
    }
    return true;
}
if(isset($_POST['Connecter']))
{
    if(empty($_POST['username']) && empty($_POST['password']))
    {
        $errorLogin = 'Les deux champs ne peuvent être vide';
    }
    else
    {
        if(validateLogin($_POST['username'],$_POST['password']))
        {
            // save username dans variable session LoggedIn
            $_SESSION['LoggedIn'] = $_POST['username'];
            // redirect sur Index
            header("Location: Index.php");
        }
        else
        {
            $errorLogin = "Authentification non reussie";
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
echo "<form action='' method='post' accept-charset='UTF-8'>
<div class=\"navbar navbar-inverse navbar-fixed-top\">\n
    <div class=\"container\">\n
            <div class=\"navbar-header\">\n
                <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\".navbar-collapse\"></button>
                <p style=\"color:white; font-size:30px;\"> Galerie d'Image </p>
            </div>\n
        <div class=\"navbar-collapse collapse\">\n
            <ul class=\"nav navbar-nav\">\n
            <li><a type='submit' name='PageIndex'>  Index  </a></li>\n
            <li><a type='submit' name='PageProfil'>  Profil  </a></li>\n
            <li><a type='submit' name='PageAdmin'>  Admin  </a></li>\n
            <li><a type='submit' name='PageConnecter'>Connexion</a></li>
            </ul>
        </div>
    </div>
</div>
<div class='container' style='position:absolute; top:20%;'>
    <div class='row'>
    	<div class='col-md-4 col-md-offset-4'>
    		<div class='panel panel-default'>
			  	<div class='panel-heading'>
			    	<h3 class='panel-title'>Connexion</h3>
			 	</div>
			  	<div class='panel-body'>
			    	<form accept-charset='UTF-8' role='form'>
                    <fieldset>
			    	  	<div class='form-group'>
			    		    <input class='form-control' id='username' placeholder='Nom utilisateur' name='username' type='text'>
			    		</div>
			    		<div class='form-group'>
			    			<input class='form-control' id='password' placeholder='Mot de Passe' name='password' type='password'>
			    		</div>
			    		<input class='btn btn-lg btn-success btn-block' name='Connecter' id='Connecter' type='submit' value='Se connecter'>
			    	</fieldset>
			      	</form>
			    </div>
			</div>
		</div>
	</div>
</div>";

if($errorLogin!='')
{
    echo "<div> $errorLogin </div>";
}

echo "</form>";
echo "  <div class='navbar navbar-inverse navbar-fixed-bottom'>
            <div class='container'>
                <div class='navbar-header'>";
                    if(isset($_SESSION['LoggedIn']))
                    {
                    echo "<p><h5 style='color:white;'>Connecte en tant que".$_SESSION['LoggedIn']."</h5></p>";
                    }
                    echo "<p><h8 style='color:white;'>Application fait par Melissa Boucher et Charlie Laplante</h8></p>";
echo "          </div>
            </div>
        </div>";
echo "</body>";
echo "</html>";
<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$errorLogin = "";

function VerifyOldPassword($password)
{
    $Fichier = "Authentification.txt";
    $var = $_SESSION['LoggedIn'] . ":" . $password;
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

function WriteInFile($password, $oldpassword)
{
    $Fichier = "Authentification.txt";
    $var = $_SESSION['LoggedIn'] . ":" . $password;
    $oldvar = $_SESSION['LoggedIn'] . ":" . $oldpassword;

    if($AUTHENTIFICATION = file_get_contents($Fichier))
    {
        $AUTHENTIFICATION = str_replace($oldvar, "", $AUTHENTIFICATION);

        file_put_contents($Fichier, $AUTHENTIFICATION);
    }
    if($handle = fopen($Fichier, 'a'))
    {
        fwrite($handle, $var . "\n");
    }
}

if(isset($_POST['ModifierPassword']))
{
    if(empty($_POST['NewPassword']) && empty($_POST['OldPassword']) && empty($_POST['VerifyPassword']))
    {
        $errorLogin = 'Tous les champs doivent etre remplis';
    }
    else
    {
        if($_POST['NewPassword'] == $_POST['VerifyPassword'])
        {
            if(VerifyOldPassword($_POST['OldPassword']))
            {
                WriteInFile($_POST['NewPassword'], $_POST['OldPassword']);
            }
            else
            {
                $errorLogin = 'Ancien mot de passe incorrecte';
            }
        }
        else
        {
            $errorLogin = 'La confirmation du mot de passe est incorrecte';
        }
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
                        if($errorLogin!='')
                        {
                            echo "<div> $errorLogin </div>";
                        }
                        echo "</fieldset>
			      	</form>
			    </div>
			</div>
		</div>
	</div>
</div>";



echo "  <div class='navbar navbar-inverse navbar-fixed-bottom'>
            <div class='container'>
                <div class='navbar-header'>";
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
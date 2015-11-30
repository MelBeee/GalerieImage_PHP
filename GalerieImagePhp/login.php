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
			echo "<body  style=\"background-color:#777777\">";

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
                <p style=\"color:white; font-size:30px;\"> Reseau Admission </p>
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
<div class='container'>
	<div class='row'>
		<div class='col-md-offset-5 col-md-3'>
            <div class='form-login'>
				<h4>Connection</h4>
				<input type='text' id='username' class='form-control input-sm chat-input' name='username' value='' placeholder='Nom d&#39;usager' />
				</br>
				<input type='password' id='password' class='form-control input-sm chat-input' name='password' value='' placeholder='Mot de passe'' />
				</br>
				<div class='wrapper'>
					<span class='group-btn'>
						<div class='col-sm-6 col-sm-offset-3'>
							<input type='submit' name='Connecter' id='Connecter' class='form-control btn btn-login' value='Se Connecter'>
						</div>
					</span>
				</div>
            </div>
        </div>

</div>";

if($errorLogin!='')
{
    echo "<div> $errorLogin </div>";
}

echo "</form>";
echo "</body>";
echo "</html>";
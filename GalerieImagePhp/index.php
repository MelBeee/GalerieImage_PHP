<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("gestimage.php");

$ArrayPhoto = array();
$errorLogin = "";

function trierdirectory($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess');

    $files = array();
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}

if(isset($_SESSION['LoggedIn']))
{
    $extension="";

    if(isset($_POST['GestionImageSubmit']))
    {
        if(isset($_POST['TitreImage']) && $_POST['TitreImage'] != "")
        {
            if(isset($_FILES['ImageToUpload']['name']))
            {
                $extension = pathinfo($_FILES["ImageToUpload"]["name"], PATHINFO_EXTENSION);
                if($extension == "jpg" || $extension == "png" || $extension == "gif" || $extension == "jpeg" ||  $extension == "JPG" || $extension == "PNG" || $extension == "GIF" || $extension == "JPEG")
                {
                    date_default_timezone_set("America/New_York");
                    $uploadfile = uniqid("").".".$extension;

                    if($Handle = fopen("Photo.txt",'a'))
                    {
                        fwrite($Handle,$_SESSION['LoggedIn']. "/" . $_POST['TitreImage'] . "~" . date('j M Y, G:i:s') . "_" . $uploadfile . "¯" . "\n" );
                    }

                    if(move_uploaded_file($_FILES['ImageToUpload']['tmp_name'], "image/".$uploadfile))
                    {
                        header("Location: index.php");
                    }
                }
            }
            else
            {
                $errorLogin = "Il faut choisir une image";
            }
        }
        else
        {
            $errorLogin = "Il faut un titre a l'image";
        }
    }
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<title>Login</title>";
    echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css\">\n
                 <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css\">
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
                        <li><a  href='profil.php' >Profil</a></li>\n
                        <li><a  href='admin.php' >Admin</a></li>\n
                        <li><a  href='login.php?deconnecter=true' name='Logout'>Deconnection</a></li>
                        </ul>
                    </div>
                </div>
            </div>";
    echo "<div class='container' style='margin-top:5%; margin-bottom: 5%'>";

    $directory = "image";
    $is_empty = (bool) (count(scandir($directory)) == 2);
    if (!$is_empty)
    {

        echo "<div class='row'>
              <form action='gestimage.php' method='GET' enctype='multipart/form-data'>
		      <div class='list-group gallery'>";

        $handle = fopen("Photo.txt", 'r');
        if($handle)
        {
            while(($line = fgets($handle)) !== false)
            {
                $Array[] = $line;
            }
            fclose($handle);
        }
        if(!empty($Array))
        {
            for($i = count($Array)-1 ; $i >= 0 ; $i--)
            {
                if($Array[$i] != "")
                {
                    $line = $Array[$i];
                    $User = substr($line, 0, strpos($line, '/'));
                    $Titre = getStringBetween($line, '/', '~');
                    $Date = getStringBetween($line, '~', '_');
                    $Guid = getStringBetween($line, '_', '¯');
                    $NbCommentaire = 0;
                    if($Commentaire = file_get_contents($Fichier))
                    {
                        $NbCommentaire = substr_count($Commentaire,$Guid);
                    }
                    echo "<div class='col-sm-4 col-xs-6 col-md-3 col-lg-3'>
                  <a class='thumbnail fancybox'  rel='ligthbox' href='gestimage.php?image=image/$Guid' name='ImageClicker' type='submit'>
                     <img class='img-responsive' style='max-height:150px; max-width: 200px; height:auto; width:auto; display:block;'   src='image/$Guid' />
                     <div class='text-center'>
                        <small class='text-muted'><b>$Titre</b></small><br>
                        <small class='text-muted'>$User</small><br>
                        <small class='text-muted'>$Date</small><br>
                        <small class='text-muted'>$NbCommentaire commentaires</small>
                     </div>
                  </a>
               </div> ";
                }
            }
        }

        echo "</div>
            </form>
            </div>
             ";
    }

    echo "<div class='row'>
    	<div class='col-md-4 col-md-offset-4'>
    		<div class='panel panel-default'>
			  	<div class='panel-heading'>
			    	<h3 class='panel-title'>Televerser une image</h3>
			 	</div>
			  	<div class='panel-body'>
			    	<form accept-charset='UTF-8' enctype='multipart/form-data' method='POST' role='form'>
                    <fieldset>
			    	  	<div class='form-group'>
			    		    <input class='form-control' maxlength='20' placeholder='Titre de l&#39;image' name='TitreImage' type='text'>
			    		</div>
			    		<div class='form-group'>
                            <input type='file' name='ImageToUpload' id='ImageToUpload'>
			    		</div>
			    		<input class='btn btn-lg btn-success btn-block' name='GestionImageSubmit' type='submit' value='Valider'>
                     </fieldset>
			      	</form>";
                    if($errorLogin!='')
                    {
                        echo "<div> $errorLogin </div>";
                    }
                    echo "</div>
			</div>
		</div>
    </div></div>";

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
}
else
{
    header("Location: login.php");
}
 <?php
     if (session_status() == PHP_SESSION_NONE) {
         session_start();
     }
     $Fichier ="Commentaire.txt";
     $Array = array();
    $ProprioImage = "";

 if(isset($_POST['SupprimerImage']))
 {
     unlink($_POST['ImageSupp']);
     header("Location: Index.php");
 }

 if(isset($_POST['EnvoyerCommentaire']))
 {
     if($Handle = fopen($Fichier,'a'))
     {
         fwrite($Handle, "*" . $_SESSION['LoggedIn']."_".$_POST['LeCommentaire']."/".date('j M Y, G:i:s'). "¯" . "~" . $_SESSION['ImageCommentaire']."\n");
     }
 }

 if(isset($_GET['image']))
 {
     $_SESSION['ImageCommentaire'] = $_GET['image'];
     gestImageMain();
 }

    function getProprioImage()
    {
        $ProprioImage = "";
        $Fichier = "Photo.txt";
        if($PHOTO = file_get_contents($Fichier))
        {
            $handle = fopen("Photo.txt", 'r');
            if($handle)
            {
                while(($line = fgets($handle)) !== false)
                {
                    $Array[] = $line;
                }
                fclose($handle);
            }
        }
        for($i = 0; $i < count($Array) ; $i++)
        {
            $substring = substr($_SESSION['ImageCommentaire'], strpos($_SESSION['ImageCommentaire'], '/'), sizeof($_SESSION['ImageCommentaire'])-6);
            if(substr_count($Array[$i], $substring) >= 0)
            {
                $ProprioImage = substr($Array[$i], 0, strpos($Array[$i], '/'));
            }
        }

        return $ProprioImage;
    }

     function getStringBetween($str,$from,$to)
     {
         $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
         return substr($sub,0,strpos($sub,$to));
     }

     function ProccessComment()
     {
         echo "<div class='row'>
                    <div class='col-md-12'>
                        <h2 class='page-header'>Commentaire</h2>
                        <section class='comment-list'>";
         $handle = fopen("Commentaire.txt", "r");
         if ($handle) {
             while (($line = fgets($handle)) !== false) {
                 if (strpos($line, $_SESSION['ImageCommentaire']) !== false) {

                     $Array[] = getStringBetween($line, "*", "~");
                 }
             }

             fclose($handle);
         }
         if (!empty($Array))
         {
             for($i = count($Array)-1 ; $i >= 0 ; $i--)
             {
                 $user = substr($Array[$i], 0, strpos($Array[$i], '_'));
                 $comment = getStringBetween($Array[$i], "_", "/");
                 $date = getStringBetween($Array[$i], "/", "¯");

                 echo "<article class='row'>
                        <div class='col-md-10 col-sm-10'>
                          <div class='panel panel-default'>
                            <div class='panel-body'>
                              <header class='text-left'>
                                <div class='comment-user'><i class='fa fa-user'></i>$user</div>
                                <time><i class='fa fa-clock-o'></i>$date</time>
                              </header>
                              <div class='comment-post'>
                                <p>
                                 $comment
                                </p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </article>";
             }
         }
             echo "    </section>
                    </div>
            </div>";
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

         echo "<img style='max-height:600px; max-width: 800px; height:auto; width:auto; display:block;' src=" . $_SESSION['ImageCommentaire'] . " >";

         echo "<form method='POST' enctype='multipart/form-data'>";
         if(GetProprioImage() == $_SESSION['LoggedIn'] || $_SESSION['LoggedIn'] == "admin")
         {
             echo "<input class='btn btn-lg btn-success btn-block' name='SupprimerImage' type='submit' value='Supprimer'>
                   <input type='hidden' name='ImageSupp' value=",$_SESSION['ImageCommentaire'].">";
         }
         echo "</form>";

         echo "<form method='POST' enctype='multipart/form-data'>
            <input type='text' maxlength='150' name='LeCommentaire'>
            <input type='submit' value='envoyer' name='EnvoyerCommentaire'>";

         echo "<br>";

         ProccessComment();

        echo "</div>";
         //////////////////////////////////////////////////////////////////////////////

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



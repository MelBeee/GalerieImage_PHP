 <?php
     if (session_status() == PHP_SESSION_NONE) {
         session_start();
     }
     $Fichier ="Commentaire.txt";
     $Array = array();

     function getStringBetween($str,$from,$to)
     {
         $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
         return substr($sub,0,strpos($sub,$to));
     }

     function ProccessComment()
     {

         $handle = fopen("Commentaire.txt", "r");
         if ($handle)
         {
             while (($line = fgets($handle)) !== false)
             {
                 if(strpos($line,$_SESSION['ImageCommentaire']) !== false)
                 {

                     $Array[] = getStringBetween($line,"*","\n");
                 }
             }

             fclose($handle);
         }
         if(!empty($Array))
         {
             for($i = count($Array)-1 ; $i >= 0 ; $i--)
             {
                 echo $Array[$i];
                 echo "<br>";
             }
         }

     }

     function gestImageMain()
     {
         echo "<img src=".$_SESSION['ImageCommentaire']." style='max-height:600px; max-width: 800px; height:auto; width:auto; display:block;' >";

         echo "<form method='POST' enctype='multipart/form-data'>";
         if(substr_count($_SESSION['ImageCommentaire'],$_SESSION['LoggedIn']) == 1 || $_SESSION['LoggedIn'] == "admin")
         {
             echo "<input type='submit' value='Supprimer' name='SupprimerImage'>
                                  <input type='hidden' name='ImageSupp' value=",$_SESSION['ImageCommentaire'].">";
         }
         echo "</form>";

         echo "<form method='POST' enctype='multipart/form-data'>
            <input type='text' name='LeCommentaire'>
            <input type='submit' value='envoyer' name='EnvoyerCommentaire'>";

         echo "<br>";

         ProccessComment();

         echo "<a href='Index.php'>RETOURNER A LA PAGE DE DEBUT</a>";
     }

    if(isset($_POST['SupprimerImage']))
    {
        unlink($_POST['ImageSupp']);
        header("Location: Index.php");
    }

    if(isset($_POST['EnvoyerCommentaire']))
    {
        if($Handle = fopen($Fichier,'a'))
        {
            fwrite($Handle,$_SESSION['ImageCommentaire']."*".$_SESSION['LoggedIn']."_".$_POST['LeCommentaire']."_".date('j-m-y')."\n");
            gestImageMain();
        }
    }

    if(isset($_POST['ImageClicker']))
    {
        $_SESSION['ImageCommentaire'] = $_POST['ImageClicker'];
        gestImageMain();
    }




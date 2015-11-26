 <?php
/**
 * Created by PhpStorm.
 * User: 201356187
 * Date: 2015-11-24
 * Time: 10:23
 */
 if(isset($_POST['SupprimerImage'])) {
     unlink($_POST['ImageSupp']);
     header("Location: Index.php");
 }

if(isset($_POST['ImageClicker']))
     {
         $Image = $_POST['ImageClicker'];

         echo "<img src='$Image' style='max-height:600px; max-width: 800px; height:auto; width:auto; display:block;' >";

    echo "<form method='POST' enctype='multipart/form-data'>";
      if(substr_count($Image,$_SESSION['LoggedIn']) == 1 || $_SESSION['LoggedIn']=="admin")
               {
                   echo "<input type='submit' value='Supprimer' name='SupprimerImage'>
           <input type='hidden' name='ImageSupp' value='$Image'>";
       }
      echo "</form>";
  echo "<a href='Index.php'>RETOURNER A LA PAGE DE DEBUT</a>";

}
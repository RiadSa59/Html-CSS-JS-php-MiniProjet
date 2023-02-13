<?php
require_once('lib/auth.php');
$ident = $_SESSION['ident']; 
?>
<!DOCTYPE html>                                        
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">                              
  <head>                                                                                         
    <meta charset="UTF-8"/>                         
    <title>Page à accès contrôlé</title>
    <script src="getByInterest.js"></script>
    <script src="stillAlive.js"></script>
    <style>
      
      .hidden { display : none;}
      section {
        width : 500px;
        background-color : rgb(240,240,240);
        padding : 5px;
        margin-bottom : 15px;
        border-radius : 3px;
      }
      section>h3 {
        margin : 0;
        margin-bottom : 5px;
      }
      #foundUsers {
        position : relative;
      }
      #foundUsers>.closeCommand {
        position : absolute;
        right:0; top:0;
        padding : 0px 3px;
        border-radius : 3px;
        background-color : lightyellow;
        cursor : pointer;
      }
      
    </style>
  </head>
<body>
<h1>
  
<?php echo "Bienvenue ". $ident->getPrenom() . " " .$ident->getNom();
?>
</h1>
<p><a href="formuUpdateUser.php">Modifier mon profil</a></p>

<section>
  <h3>Recherche par sujet d'intérêt</h3>
  <form name="byInterest">
    <input type="text" name="interest" required="required" />
    <button name="search" value="search">Rechercher</button>
  </form>
  <div id="foundUsers" class="hidden">
    <h4></h4>
    <div class="result"></div>
    <div class="closeCommand" title="close">✖︎</div>
  </div>
</section>
<section><h3>Utilisateurs actifs :</h3>
<div id="vivants"></div>
</section>
<a href="logout.php">Se déconnecter</a>
</body>
</html>

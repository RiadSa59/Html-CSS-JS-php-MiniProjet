<?php
/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */
require_once('lib/auth.php');
$personne = $_SESSION['ident'];
?>
<!DOCTYPE HTML>
<html>
    <head>
        <!-- Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr -->
        <meta charset="UTF-8" />
        <title>Mon compte</title>
        <!--

        
        
        Ces scripts permettent de traiter en "ajax" (XMLHttpRequest) les formulaires
        
        ATTENTION : utiliser au maximum UN SEUL de ces 2 scripts
        
        Le premier script ne traite que du premier formulaire :
        
        <script src="formuUpdateUser.js"></script>
        
        Celui-ci traite de manière générique les 3 formulaires:
        
        <script src="ajaxifyForms.js"></script>
        
        -->
                
    </head>
    <body>
        <h1>
            Mise à jour des données personnelles de
            <span id="nom"><?php echo $personne->getNom();?></span>
            <span id="prenom"><?php echo $personne->getPrenom();?></span>
        </h1>
        <h2>Informations générales</h2>
        <div>
        <form action="updateUser.php" method="POST" id="update">        
            <label for="login">Mot de passe</label><input type="password" name="password" value="" placeholder="Nouveau mot de passe"/>
            <label for="nom">Nom</label><input type="text" name="nom" value="<?php echo $personne->getNom(); ?>" />
            <label for="prenom">Prénom</label><input type="text" name="prenom" value="<?php echo $personne->getPrenom(); ?>"/>
            <button name="valid" value="ok" type="submit">Valider</button>
        </form>
        </div>
        <h2>Centres d'intérêt</h2>
        <div>
        <form action="updateInterest.php" method="POST" id="interests">        
            <label for="list">Liste :</label><input type="text" name="list"
            value="<?php echo $bd->getInterests($personne->getLogin());?>" />
            <button type="submit">Valider</button>
            
        </form>
        </div>
        </div>
        <h2>Avatar</h2>
        <div>
           <p><img class="avatar" src="sendAvatar.php" alt="avatar" id="avatar"/></p> 
        <form action="loadAvatar.php" method="post" enctype="multipart/form-data" id="avatarForm">
            <!--<input type="hidden" name="MAX_FILE_SIZE" value="5000000" /> -->
            <label for="">Liste :</label><input type="file" name="fichier-avatar" />
            <button type="submit">Valider</button>
        </form>
        </div>
        <footer><a href="index.php">Page d'accueil</a></footer>
    </body>
</html>
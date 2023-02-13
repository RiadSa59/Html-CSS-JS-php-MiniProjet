<?php
require("fichierJSON.class.php");
require("toolsForm.php");

$html = "<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml'>

    <head>
    
        <title>Filter V'Lille</title>
        <meta charset='UTF-8' />
        <link rel=\"stylesheet\" href=\"filter.css\" type=\"text/css\"/>
    </head>

    <body>
        <form action='test.php'>
          <fieldset>
              <legend>Communes</legend>
                ";
$JSON = new fichierJSON("https://opendata.lillemetropole.fr/api/records/1.0/search/?dataset=vlille-realtime&rows=250&timezone=Europe/Paris");
$htmlCommunes = listCommune($JSON,$list_commune);
$html .= $htmlCommunes[0];
$html .= "
          </fieldset>

          <fieldset>
              <legend>Les stations</legend>
                ";
$html .= listStation($htmlCommunes[1]);
$html .= "
                <p id='info'> </p>
          </fieldset>
          
          <fieldset>
            <legend>CB</legend>
            ".filtreCB()."
          </fieldset>
          
          <fieldset>
            <legend>Vélo Disponible</legend>
                ".filtreVelo(true)."
          </fieldset>
          
          <fieldset>
            <legend>Espace Disponible</legend>
                ".filtreVelo(false)."
          </fieldset>

          <button type=\"submit\" name=\"filtre\" value=\"Filter\">Filtrer</button>
      </form>
    </body>
</html>";

echo $html;
//file_put_contents("filterVlille.html",$html);
//header('Location: filterVlille.html');


 ?>

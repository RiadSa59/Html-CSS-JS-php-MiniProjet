<?php
require("fichierJSON.class.php");
require("toolsProjetVLille.php");

$html = "<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml'>
    <head>
        <title>Projet V'Lille</title>
        <meta charset='UTF-8' />
        <link rel='stylesheet' href='projet.css' type='text/css' />
        <link rel='stylesheet' href='https://unpkg.com/leaflet@1.0.3/dist/leaflet.css' />
		<script src='https://unpkg.com/leaflet@1.0.3/dist/leaflet.js'></script>
		<script src='scriptCarteVLille.js'></script>
    </head

    <body>

    
    
    
    <header>
        <p id='entete'>
            <a href='test.php'>Accueil</a>   <a href='formFilter.php'>Filtre</a>
        </p>
    </header>
    <nav id='tableauVLille'>";


			$url = "https://opendata.lillemetropole.fr/api/records/1.0/search/?dataset=vlille-realtime&rows=250&timezone=Europe/Paris";
            $url = update($url);
			$stations = new fichierJSON($url);
			$html .= $stations->toTable()."
	</nav>
	
	<section id= 'GrandeCarte'>
		    <div id='carteVLille'></div>
	
	<section id='infoComplete'>
	<div id='miniCarte'></div>
	
    
		<p id='Informations'></p>
	</section>
	</section>
	
	</div>

    </body>
</html>";

file_put_contents("VLille.html",$html);
header("Location:VLille.html");

?>

<?php

class fichierJSON{

  private $stations;

  

  public function __construct($url){
    $configContext = array(
          'http' => array(
                  'proxy' => 'tcp://cache.univ-lille1.fr:3128',
                  'request_fulluri' => true
          )
    );
    stream_context_set_default($configContext);

    $fp = file_get_contents($url);

    $this->stations = json_decode($fp,true)['records'];

  }


  public function getEtat($nbStation){
    return $this->stations[$nbStation]['fields']['etat'];
  }

  public function getCommune($nbStation){
    return $this->stations[$nbStation]['fields']['commune'];
  }

  public function getEtatConnexion($nbStation){
    return $this->stations[$nbStation]['fields']['etatConnexion'];
  }

  public function getNbVeloDispo($nbStation){
    return $this->stations[$nbStation]['fields']['nbVelosDispo'];
  }

  public function getNbPlaceDispo($nbStation){
    return $this->stations[$nbStation]['fields']['nbPlacesDispo'];
  }

  public function getNameStation($nbStation){
    return $this->stations[$nbStation]['fields']['nom'];
  }

  public function getLibelle($nbStation){
    return $this->stations[$nbStation]['fields']['libelle'];
  }

  public function getGeoX($nbStation){
    return $this->stations[$nbStation]['fields']['geo'][0];
  }

  public function getGeoY($nbStation){
    return $this->stations[$nbStation]['fields']['geo'][1];
  }

  public function getAdresse($nbStation){
    return $this->stations[$nbStation]['fields']['adresse'];
  }


  public function getType($nbStation){
    return $this->stations[$nbStation]['fields']['type'];
  }

  public function getGeometry($nbStation){
    return $this->stations[$nbStation]['fields']['geometry'][0];
  }


  private function headTable(){
	$headTable = "\t\t\t\t\t<thead>\n\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t";
	$headTable .= "<th>NameStation</th>\n\t\t\t\t\t\t\t";
	$headTable .= "<th>Commune</th>\n\t\t\t\t\t\t\t";
	$headTable .= "<th>Vélo disponibles</th>\n\t\t\t\t\t\t\t";
	$headTable .= "<th>Place disponibles</th>\n\t\t\t\t\t\t";
	$headTable .= "</tr>\n\n";
	return $headTable;
 }

 private function dataStation($name,$index){
	$data = " data-lat='{$this->getGeoX($index)}' ";
	$data .= "data-lon='{$this->getGeoY($index)}' ";
	$data .= "data-nameStation=\"{$name}\" ";
	$data .= "data-etat='{$this->getEtat($index)}' ";
	$data .= "data-connexion='{$this->getEtatConnexion($index)}' ";
	$data .= "data-commune=\"{$this->getCommune($index)}\" ";
	$data .= "data-adresse=\"{$this->getAdresse($index)}\" ";
	$data .= "data-typeCB='{$this->getType($index)}' ";
	$data .= "data-velodispo='{$this->getNbVeloDispo($index)}' ";
	$data .= "data-espace='{$this->getNbPlaceDispo($index)}' ";
	$data .= "data-libelle='{$this->getLibelle($index)}' ";
	return $data;
 }

  private function bodyTable(){
	  $bodyTable = "\t\t\t\t\t<tbody>\n";
	  $index = 0;
	  while($this->stations[$index] !== null){
		  $name = $this->getNameStation($index);
		  $name = explode(" (CB)",$name)[0];
		  $nameStation = substr($name,strpos($name," ",0)+1);

		  $bodyTable .= "\t\t\t\t\t\t<tr {$this->dataStation($nameStation,$index)}>\n";
		  $bodyTable .= "\t\t\t\t\t\t\t<td>{$nameStation}</td>\n";
		  $bodyTable .= "\t\t\t\t\t\t\t<td>{$this->getCommune($index)}</td>\n";
		  $bodyTable .= "\t\t\t\t\t\t\t<td>{$this->getNbVeloDispo($index)}</td>\n";
		  $bodyTable .= "\t\t\t\t\t\t\t<td>{$this->getNbPlaceDispo($index)}</td>\n";
		  $bodyTable .= "\t\t\t\t\t\t</tr>\n\n";
		  $index++;
	  }
	  $bodyTable .= "\t\t\t\t\t</tbody>\n\n";
	  return $bodyTable;
  }


  public function toTable(){
	  $HTML = "\t\t<table id=tableJSON>\n";
	  $HTML .= $this->headTable();
	  $HTML .= $this->bodyTable();
	  $HTML .= "\t\t\t</table>\n";
	  return $HTML;

}
}
?>

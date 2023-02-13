<?php
  require("station.class.php");

   function createTable($list,$JSON){
     $length = count($list);
     for($index = 0;$index<$length;$index++){
       $key[] = $list[$index];
     }
     $list_commune = array_fill_keys($key,array());
     ksort($list_commune);

     $nbStation = 0;
     $commune = $JSON->getCommune($nbStation);
     while($commune !== null){
       $TPE = $JSON->getType($nbStation);
       $adresse = $JSON->getAdresse($nbStation);
       $libelle = $JSON->getLibelle($nbStation);
       $nameStation = $JSON->getNameStation($nbStation);
       $nameStation = explode(" (CB)",$nameStation)[0];
       $nameStation = substr($nameStation,strpos($nameStation," ",0)+1);

       $list_commune[$commune][] = new station($TPE,$libelle,$nameStation,$adresse);


       
       $nbStation++;
       $commune = $JSON->getCommune($nbStation);
     }
       return $list_commune;
   }


   function compareElem($a,$b){
     if($a === $b){
       return TRUE;
     }
     return FALSE;
   }

  function compareList($one,$list){
    $length = count($list);
    for($index = 0;$index<$length;$index++){
      if(compareElem($list[$index],$one)){
        return FALSE;
      }
    }
    return TRUE;
  }


  function listCommune($JSON){
    $html = "<select multiple='multiple' size=5 name='commune[]' id='filterCommunes'>\n\t\t\t\t\t\t<option name='communes' value='communes' selected='selected'>communes</option>\n\t\t\t\t\t";
    $nbStation = 0;
    $commune = $JSON->getCommune($nbStation);
    $listCommune = array();
    while( $commune !== null){
      if(compareList($commune,$listCommune)){
        $listCommune[] = $commune;
        $html .= "\t<option name=\"{$commune}\">{$commune}</option>\n\t\t\t\t\t";
      }
      $nbStation++;
      $commune = $JSON->getCommune($nbStation);
    }
    $html .= "</select>\n\n";
    $list_commune = createTable($listCommune,$JSON);
    return array($html,$list_commune);
  }


  function listStation($list){
    $html = "<select name='station' id='filterStations'>\n\t\t\t\t\t\t\t<option name='stations' selected='selected'>Stations</option>\n\t\t\t\t\t\t";

    foreach($list as $commune => $stations){
      $html .= "\t<optgroup label=\"{$commune}\" name=\"{$commune}\">\n\t\t\t\t\t\t";
      foreach($stations as $station){
        $html .= "\t<option name=\"{$station->getName()}\" value=\"{$station->getLibelle()}\" >{$station->getName()}</option>\n\t\t\t\t\t\t";
      }
      $html .= "</optgroup>\n\t\t\t\t\t";
    }
    $html .= "</select>\n\n";

    return $html;

  }

  function filtreCB(){
      $html = "<p id='filtreCB'>
                        <input type='radio' name='cb' id='oui' value='AVEC' /><label for='oui'>oui</label>    <input type='radio' name='cb' id='non' value='SANS' /><label for='non'>non</label>  <input type='radio' name='cb' id='NoImportant' value='noFiltre' checked/><label for='NoImportant'>Avec et Sans</label> 
               </p>";
      return $html;
  }

  function filtreVelo($dispo){

      $name = 'inputNumberVeloDispo';
      $id = 'VSpaceVDispo';
      $minMax = "VminMax";
      $label = "Entrer le nombre de vélos disponibles souhaité:";
      if($dispo){
          $name = 'inputVSpace';
          $id = 'VSpace';
          $minMax = 'VminMaxminMax';
          $label = "Entrer le nombre d'emplacements disponibles souhaité: ";
      }
      $html = "<p id='filtreVDispo'>
                        <label for='{$id}'>{$label}</label>
                        <input type='number' size='2' name='{$name}' id='{$id}' value='0' />
                        <input type='radio' name='{$minMax}' id='superieur' value='>' /><label for='superieur'>supérieur</label>    <input type='radio' name='{$minMax}' id='inferieur' value='<' checked/><label for='inferieur'>inférieur</label>
              </p>";

      return $html;
  }

 ?>

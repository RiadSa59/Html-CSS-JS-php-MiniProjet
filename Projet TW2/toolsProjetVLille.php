<?php
function update($url){
    if(isset($_GET["filtre"])){
        if(isset($_GET["commune"])){
            foreach($_GET["commune"] as $commune){
                if($commune !== "communes")
                $url .= "&refine.commune={$commune}";
            }
        }
        if($_GET["station"] !== "Stations"){
            $url .= "&q=libelle:{$_GET["station"]}";
        }

        if($_GET["cb"] != "noFiltre"){
            $url .= "&refine.type={$_GET["cb"]} TPE";
        }

        if($_GET["inputVSpace"] !== '0'){
            $url .= "&q=nbVelosDispo{$_GET["minMax"]}{$_GET["inputVSpace"]}";
        }

        if($_GET["inputNumberVeloDispo"] !== '0') {
            $url .= "&q=nbPlacesDispo{$_GET["VminMax"]}{$_GET["inputNumberVeloDispo"]}";
        }
    }


    
    return $url;
}
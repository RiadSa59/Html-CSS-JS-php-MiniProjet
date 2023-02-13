/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */
   window.addEventListener("load",function(){
      setInterval(jeSuisVivant,10000); // à lancer toutes les 10s
      jeSuisVivant();  // une première exécution, pour obtenir l'info immédiatement
    }
   );
   
   function jeSuisVivant(){ 
      var req = new XMLHttpRequest();
      req.open("get","je_suis_vivant.php"); 
      req.responseType = "json";       // on attend une réponse JSON
      req.addEventListener("load", afficheVivants); // à déclencher lors de la reponse HTTP
      //req.addEventListener("error",requestFailed.bind(req,this)); // à déclencher en cas d'échec HTTP
      req.send(null);                // envoi de la requête HTTP
   }
   
   
 
   function afficheVivants(ev){
      var reponse = this.response;
      if (reponse instanceof Array) {
         document.getElementById("vivants").textContent = reponse.join(" ");
      }
   }
 
/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */
   window.addEventListener("load",function(){
      document.forms.byInterest.addEventListener("submit", search);
      document.querySelector("#foundUsers>div.closeCommand").addEventListener("click",closeDiv);
     }
   );
   
   function search(ev){
      var form = this;
      
      ev.preventDefault();
      var req = new XMLHttpRequest();
      req.open("post","serviceGetByInterest.php"); 
      req.responseType = "json";       // on attend une réponse JSON
      req.addEventListener("load", displayFound); // à déclencher lors de la reponse HTTP
      //req.addEventListener("error",requestFailed.bind(req,this)); // à déclencher en cas d'échec HTTP
      req.send(new FormData(form));                // envoi de la requête HTTP
      document.querySelector("#foundUsers").classList.remove("hidden");
      document.querySelector("#foundUsers>h4").textContent = "Utilisateurs intéressés par " + form.interest.value;
      document.querySelector("#foundUsers>div.result").textContent = "(en attente du résultat)";
    }
   
 
   function displayFound(ev){
      var reponse = this.response;
      if (reponse instanceof Array) {
         if (reponse.length>0){
            document.querySelector("#foundUsers>div").textContent = reponse.join(" ");
         } else {
            document.querySelector("#foundUsers>div.result").textContent = "(aucun)";
         }
      }
   }
   
   function closeDiv(ev){
      this.parentNode.classList.add("hidden");
   }
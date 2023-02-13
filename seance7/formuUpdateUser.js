/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */

/* Ce script "ajaxifie" le formulaire update : lors de la validation, ses données sont envoyées par unes requête XMLHttpRequest
 * à un service web (qui renvoie son résultat en json)
 * et non plus par l'action par défaut du navigateur.
 *
 * Un message est affiché pour indiquer le résultat de la validation (réalisée ou en échec)
 *
 * Le message du dernier résultat est effacé si l'utilisateur modifie de nouveau un champ de saisie du formulaire
 *
 */

/*

 * NB : ce script ne traite que le formulaire update, pas les autres.
 */

 
 /*
  * Au démarrage ...
  */
   window.addEventListener("load", function(){
      var form = document.forms.update;
      form.addEventListener("submit",formUpdateSender);
      for (var field of form)
        field.addEventListener('change',clearMessage);
   }
   );

   /*
    * Gestionnaire de l'évènement submit du formulaire update
    */
   function formUpdateSender(ev){ 
      ev.preventDefault();            // empêcher l'action par défaut (envoi ordinaire du formulaire)
      var service = "serviceUpdateUser.php";
      var postdata = new FormData(this);  // données du formulaire
      var req = new XMLHttpRequest();
      req.open("POST", service);  
      req.responseType = "json";          // on attend une réponse JSON
      req.addEventListener("load", requestUpdateSucceded); // à déclencher lors de la reponse HTTP
      req.addEventListener("error",requestUpdateFailed);   // à déclencher en cas d'échec HTTP
      // récupération des données et envoi :
      req.send(postdata);                // envoi de la requête HTTP
      disableForm(this, true);        // désactivation du formulaire (en attendant le résultat de la requête)
   }
   
   /*
    * À exécuter en cas d'échec HTTP
    */
   function requestUpdateFailed(ev){  // this est l'objet XMMLHttpRequest qui a déclenché la requête
      var form = document.forms.update;
      addMessage(form,"Erreur : la requête a échoué", true);
      disableForm(form, false); // réactivation du formulaire      
   }

   /*
    * À exécuter en cas de réponse HTTP normale
    */
   function requestUpdateSucceded(ev){ // this est l'objet XMMLHttpRequest qui a déclenché la requête
      var form = document.forms.update;
      var reponse = this.response;
      if ( !reponse || !reponse.status || reponse.status !="done"){ // reponse incorrecte ou status != "done"
         addMessage(form,"Erreur : la mise à jour a échoué", true);
       } else {            // le service a envoyé une réponse positive
         addMessage(form,"mis à jour : " + reponse.date, false);
         refreshDisplay(reponse.identite);
      }
      disableForm(form, false); // réactivation du formulaire
   }
   
   /*
    * désactivation/réactivation d'un formulaire
    */
   function disableForm(form, setDisable){
      for (var field of form)
        field.disabled = setDisable;
   }
   
   /*
    * ajout d'un message dans un formulaire
    */
   function addMessage(form, message, isError){
      var zone = document.createElement('p');
      zone.textContent = message;
      zone.classList.add("message");
      if (isError){
          zone.classList.add("error");
      }
      form.appendChild(zone);
   }
   
   /*
    * effacement des messages insérés dans le formulaire
    */
   function clearMessage(){ // gestionnaire de "change" sur un champ de saisie
         // this est un champ de saisie, donc this.form est son formulaire.
       var zones = this.form.querySelectorAll(".message");
       for (var z of zones)
           z.remove();
   }
   /*
    * mise à jour du message d'accueil.
    * identite est un objet (nom, prenom ...)
    */
   function refreshDisplay(identite){ // mise à jour du message d'accueil
       document.getElementById("nom").textContent = identite.nom;
       document.getElementById("prenom").textContent = identite.prenom;
   }

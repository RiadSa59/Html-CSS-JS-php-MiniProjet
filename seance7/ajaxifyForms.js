/*
 * Creative Commons  CC-BY-NC 2.0  (cc) Bruno.Bogaert [at] univ-lille1.fr 
 */

/* Dans cet exemple, on "ajaxifie" les 3 formulaires de la page par la même procédure
 * Deux technos spécifiques sont employées afin de faciliter la conception 'générique' :
 *  - la méthode bind() qui permet de fabriquer une fonction liée
 *  - le déclenchement par programme d'un évènement personnalisé (voir CustomEvent sur le site MDN)
 *
 *   
 *
 * Lors de la validation, les données du formulaire sont envoyées par unes requête XMLHttpRequest
 * à un service web (qui renvoie son résultat en json)
 * et non plus par l'action par défaut du navigateur.
 *
 * Un message est affiché pour indiquer le résultat de la validation (réalisée ou en échec)
 *
 * Le message du dernier résultat est effacé si l'utilisateur modifie de nouveau un champ de saisie du formulaire
 *
 *          ------ NB : en plus de la version précédente ----
 * En  cas de succès, un évènement personnalisé baptisé "received" est déclenché sur le formulaire concerné.
 * on a choisi de faire "transporter" par cet évènement la réponse reçue du service web
 * Ce mécanisme permet, tout en profitant de la factorisation du code de gestion de la requête,
 * de déclencher facilement des traitements complémentaires propres à chaque formulaire, si nécessaire
 *
 */


/*
 * Au démarrage...
 */
window.addEventListener("load",function(){
      ajaxForm("update");   // ajaxification du formulaire update
      document.forms.update.addEventListener("received", profileChanged); // action compl. pour le formulaire update, en cas de succès (mise à jour du titre)
      ajaxForm("interests"); // ajaxification du formulaire interests. Pas d'action compl. pour ce formulaire
      ajaxForm("avatarForm"); // ajaxification du formulaire avatar.
      document.forms.avatarForm.addEventListener("received", refreshAvatar); // action compl. (mise à jour de l'image courante)
   }
   );

   /*
    *  Ajaxification d'un formulaire, initialisation :
    *  mise en place des gestionnaires d'évènements
    */
   function ajaxForm(name){
      var form = document.forms[name];
      form.addEventListener("submit",formSender);
      for (var field of form)
        field.addEventListener('change',clearMessage);   
   }
   
  /*
    * Le nom du service qui sera invoqué par XmlHttpRequest est obtenu à partir de celui de l'action par défaut, en ajoutant
    *    le préfixe 'service', en notation camelcase
    * exemple 'machin.php' --> 'serviceMachin.php'
    *
    * la méthode utilisée pour XmlHttpRequest sera la même que celle prévue pour l'action par défaut
    * 
    */
   function formSender(ev){ // gestionnaire de l'évènement submit d'un formulaire. this est donc un formulaire
      var form = this;
      ev.preventDefault();            // empêcher l'action par défaut
      var url = new URL(form.action); // URL de l'action par défaut
      url = addServicePrefix(url);    // nouvelle URL (voir fonctions utilitaires)
      var data = new FormData(this);  // données du formulaire
      var postData = null;            // ce qu'il faudra envoyer en POST (null par défaut)
      if (form.method.toUpperCase()=="GET"){
         url.search =  formDataToQueryString(data); // ajout à l'url d'une query string  contenant les données du formulaire
      } else { // POST
         postData = data; 
      }
      var req = new XMLHttpRequest();
      req.open(form.method, url.href); // requête à envoyer, par la méthode prévue, mais à la nouvelle URL
      req.responseType = "json";       // on attend une réponse JSON
      // req.timeout=1000;               
      req.addEventListener("load", requestSucceded.bind(req, form)); // à déclencher lors de la reponse HTTP
      req.addEventListener("error",requestFailed.bind(req, form)); // à déclencher en cas d'échec HTTP
         // bind :  pour ces 2 gestionnaires, leur 'this' sera le premier agument du bind, c'est à dire req (l'objet XMLHttpRequest)
         //    et leur premier argument sera le 2ème argument du bind : c'est à dire form, le formulaire
      // récupération des données et envoi :
      req.send(postData);                // envoi de la requête HTTP
      disableForm(this, true);       // désactivation du formulaire
   }
   
   /*
    * À exécuter en cas d'échec HTTP
    * grâce au bind,  form désigne le formulaire concerné par la requête et this désigne l'objet XMLHttpRequest
    */
   function requestFailed(form, ev){
      addMessage(form,"Erreur : la requête a échoué", true);
      disableForm(form, false); // réactivation du formulaire      
   }

   /*
    * À exécuter en cas de réponse HTTP
    * grâce au bind,  form désigne le formulaire concerné par la requête et this désigne l'objet XMLHttpRequest
    */
   function requestSucceded(form, ev){
      var reponse = this.response;
      if ( !reponse || !reponse.status || reponse.status !="done"){ // reponse incorrecte ou status != "done"
         addMessage(form,"Erreur : la mise à jour a échoué", true);
       } else {            // le service a envoyé une réponse positive
         addMessage(form,"mis à jour : " + reponse.date, false);
            // declenchement d'un évènement personnalisé baptisé 'received' sur le formulaire.
            // On lui associe dans 'detail' la reponse reçue 
         form.dispatchEvent(new CustomEvent("received",{"detail": reponse})); 
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
   
//============= Mise à jour de l'affichage =========
   /*
    * mise à jour du message d'accueil.
    * identite est un objet (nom, prenom ...)
    */
   function refreshDisplay(identite){ // mise à jour du message d'accueil
       document.getElementById("nom").textContent = identite.nom;
       document.getElementById("prenom").textContent = identite.prenom;
   }
   
   /*
    * Gestionnaire de l'évènement "received" sur le formulaire
    */
   function profileChanged(ev){
      // ev est une instance du CustomEvent déclenché par requestSucceded
      // ev.detail contient la valeur passée par requestSucceded, c'est à dire la réponse à la requête de mise à jour
      refreshDisplay(ev.detail.identite);
   }
   

//============  AVATAR ===============
   /*
    * Envoi une requête pour récupérer l'avatar
    *
    * NB : cette méthode pourrait être appelée directement
    * La fonction d'initialisation, l'a aussi utilisée comme gestionnaire de l'évènement "received" sur le formulaire avatar.
    */
   function refreshAvatar(){
      var r = new XMLHttpRequest();
      r.open("GET","sendavatar.php");
      r.responseType="blob";  // la réponse doit être mise dans un Blob
      r.addEventListener("load",receiveAvatar);
      r.send(null);
    }
    /*
     * Mise à jour de l'image dans le document
     */
    function receiveAvatar(ev){
      // this.response est un Blob contenant l'image reçue
      var url = URL.createObjectURL(this.response);  // fabrication de l'URL interne désignant ce Blob
      document.getElementById("avatar").src = url;   // l'élément <img> affiche maintenant la nouvelle image
    }
    
//=============== Divers utilitaires ============
   /*
    *   Met les données d'un formulaire ou d'une Map sous forme de query string
    *    (inadapté si le formulaire contient un fichier : à envoyer en POST)
    *    
    *   fd : FormData instance (or Map instance)
    *   returns : query string (without initial '?')
   */
   function formDataToQueryString (fd){
      return Array.from(fd).map(function(p){return encodeURIComponent(p[0])+'='+encodeURIComponent(p[1]);}).join('&');
   }
   
   /*
    * Fabrique une nouvelle URL en ajoutant "service" au nom de base, en notation camelcase
    */
   function addServicePrefix(url){  // url est une instance de l'api URL (== window.URL)
      var parts = url.pathname.split('/');
      basename = parts[parts.length-1];
      parts[parts.length-1] =  "service" + basename[0].toUpperCase() + basename.substring(1);
      url.pathname = parts.join('/');
      return url;
   }
   
 

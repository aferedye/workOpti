
/* Fonction à améliorer/découper ... 
        - Conversion des données AJAX en JSON
        - Calcul du nombre de page
        - Création div avec une classe dynamique et affichage du nombre de pages
    */
   function nbrPage(sliceVar, json) {
    const obj = JSON.parse(json);
    let sliceResult = Math.ceil(sliceVar / 10)+1;
    let htmlContent = '<div class="numPage'+sliceVar+' white">'+sliceResult+'</div>';
    let paginationContainer = document.getElementById('paginationContainer');
  
    paginationContainer.insertAdjacentHTML('afterBegin', htmlContent);
    

    // Création de toute les pages 
    afficherPage(sliceVar, obj);
  
 
    let paginationNumber = document.querySelector('.numPage'+sliceVar+'');
    let backgroundNumber = document.querySelectorAll('.white');

    // Ajout d'un listener sur les numéros de pages pour afficher la page demandé [1,2,3,...].
    paginationNumber.addEventListener('click', function() {
    
        let result = document.querySelectorAll('.resultBloc'+sliceVar);
        let rest = document.querySelectorAll('.hidden');

        // Boucle dynamique qui affiche les donnés de la page demandé et met en surbrillance le numéro de page
        for(let a = 0; a < result.length; a++) {
            let res = result[a];
            res.style.display = "block";
            /*paginationNumber.classList.add("blue");
            paginationNumber.classList.remove("white");*/
        }
  
        // Boucle qui récupère la classe des élément de la liste / Découpe de la classe pour récupérer la valeur dynamique
        for(let b = 0; b < rest.length; b++) {
                let re = rest[b];
                let text = re.className;
                let r = text.substr(17, 18);
              
                // Si la class dynamique n'est pas celle demandé on la masque
                if (r != sliceVar) {
                    re.style.display = "none";
                }
        }
    })};

/* Fonction qui intégre dynamiquement les résultat de la bdd via la rêquete AJAX en HTML.
    - Découpe de l'objet en bloc de 10 en lui assignant une class dynamique pour le rendu.
*/
function afficherPage(sliceVar,obj) {
    let table = document.getElementById('societyList');
    
   
    obj.splice(sliceVar, 10).forEach(function (obj) {

        let route = "/panel/society/details";
        let htmlContent = '<div class="hidden resultBloc'+sliceVar+'"><form method="post" action="' + route +
            '"><ul class="society-list"><li id="societyName" class="list--first">' + obj[
                'Name'] + '</li><li class="list--first"><a href="' + obj['Site'] +
            '" id="societySite">' + obj['Site'] +
            '</a></li><input type="hidden" name="iddevis" value="' + obj['id'] +
            '"><li class="list--last"><input type="submit" value="Détails" class="btn btn--list"></li></ul></form></div>';
        table.insertAdjacentHTML('afterBegin', htmlContent);
    })  
}

// Fonction qui renvoie le nombre d'objet dans le tableau.
function ObjectLength_Modern(object) {
    return Object.keys(object).length;
}

function onLoad(event) {
    blockEntreprise.style.display = "none";
    load.style.display = "flex";
};

function onSucces(event) {
    if (this.readyState === 4 && this.status === 200) {
        setTimeout(function () {
            load.style.display = "none";
            succes.style.display = "flex";
        }, 1000);
    } else {
        setTimeout(function () {
            load.style.display = "none";
            error.style.display = "block";
        }, 1000);
    }
};

function onLoadContact(event) {
    blockContact.style.display = "none";
    load.style.display = "flex";
};

function onSuccesContact(event) {
    if (this.readyState === 4 && this.status === 200) {
        setTimeout(function () {
            let text = document.querySelector('.succestext');

            text.innerHTML = "Le contact est bien enregister dans la base de données.";
            load.style.display = "none";
            succes.style.display = "flex";
        }, 1000);
    } else {
        setTimeout(function () {
            load.style.display = "none";
            error.style.display = "block";
        }, 1000);
    }
};
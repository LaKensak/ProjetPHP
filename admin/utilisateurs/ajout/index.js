"use strict";

import {
    retournerVersApresConfirmation,
    donneesValides,
    configurerFormulaire,
    afficherSucces,
    afficherErreur, viderLesChamps
} from "../../../composant/fonction.js";

/*global data */


// tableau stockant l'ordre d'arrivée des pilotes
let classement = [];


// récupération des éléments de l'interface
let idGrandPrix = document.getElementById('idGrandPrix');
let btnEnregistrer = document.getElementById('btnEnregistrer');
let idPilote = document.getElementById('idPilote');
let leClassement = document.getElementById('leClassement');

// tableau des numéros de pilotes
let lesPilotes =  data.lesPilotes.map((element) => parseInt(element.id));

// alimenter la zone de liste des grands prix
for (const gp of data.lesCubes) {
    idGrandPrix.appendChild(new Option(gp.name, gp.id));
}


// configuration du formulaire
configurerFormulaire(true);

// Les gestionnaires d'événements

btnEnregistrer.onclick = () => {
    leClassement.nextElementSibling.innerText = "";
    classement = [];
    if (donneesValides()) {
        // récupération des numéros de pilotes dans l'ordre d'arrivée
        for (const element of leClassement.value.split(',')) {
            let numPilote = parseInt(element);
            // le pilote n'est pas dans la liste des pilotes
            if (!lesPilotes.includes(numPilote)) {
                leClassement.nextElementSibling.innerText = `Le pilote ${numPilote} n'existe pas dans la liste des pilotes`;
                return;
            } else if (classement.includes(numPilote)) {
                leClassement.nextElementSibling.innerText = `Le pilote ${numPilote} est présent deux fois dans le classement`;
                return;
            }
            classement.push(numPilote);
        }
        enregistrer();
    }
};

function enregistrer() {
    $.ajax({
        url: "ajax/ajouter.php",
        method: 'POST',
        dataType: "json",
        data: {
            idGrandPrix: idGrandPrix.value,
            classement : JSON.stringify(classement),
            idPilote : idPilote.value
        },
        success: () => {
            viderLesChamps();
            afficherSucces("Opération réalisée avec succès");

            // suppression du Grand Prix sélectionné dans la liste
            idGrandPrix.remove(idGrandPrix.selectedIndex);
            // s'il n'y a plus de Grand Prix, on retourne à l'accueil
            if (idGrandPrix.length === 0) {
                retournerVersApresConfirmation("Saisie enregistrée. Tous les classements ont été enregistrés ", '/admin');
            } else {
                // effacer les champs
                leClassement.value = "";
                idPilote.value = "0";
                afficherSucces("Saisie enregistrée, il reste des classements à saisir.");
            }
        },
        error: reponse => {
            afficherErreur('Une erreur imprévue est survenue');
            console.log(reponse.responseText);
        }
    });
}

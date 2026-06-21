// === Filtres AJAX des covoiturages ===
// Recupere les elements de filtre et la zone d'affichage des resultats
const filtreEco = document.getElementById('filtre-eco');
const filtrePrix = document.getElementById('filtre-prix');
const filtrePlaces = document.getElementById('filtre-places');
const resultats = document.getElementById('resultats');

// Fonction qui interroge l'API et met a jour l'affichage (sans recharger la page)
async function chargerCovoiturages() {
    // On construit les parametres d'URL selon les filtres actifs
    const params = new URLSearchParams();
    if (filtreEco.checked) params.append('eco', '1');
    if (filtrePrix.value) params.append('prix_max', filtrePrix.value);
    if (filtrePlaces.value) params.append('places_min', filtrePlaces.value);

    // Appel AJAX a l'API (fetch) qui renvoie du JSON
    const reponse = await fetch('/api/covoiturages?' + params.toString());
    const covoiturages = await reponse.json();

    // On vide la zone de resultats avant de la reconstruire
    resultats.innerHTML = '';

    // Cas ou aucun trajet ne correspond aux filtres
    if (covoiturages.length === 0) {
        resultats.innerHTML = '<p>Aucun covoiturage pour ces criteres.</p>';
        return;
    }

    // On construit le HTML pour chaque trajet recu
    covoiturages.forEach(function (trajet) {
        // Badge ecologique uniquement si le vehicule est electrique
        const eco = trajet.vehicule_energie === 'electrique' ? ' 🌿 Ecologique' : '';

        const li = document.createElement('li');
        li.innerHTML =
            '<strong>' + trajet.ville_depart + ' → ' + trajet.ville_arrivee + '</strong><br>' +
            'Depart : ' + trajet.depart + '<br>' +
            'Prix : ' + trajet.prix + ' credits<br>' +
            'Places : ' + trajet.nb_places + '<br>' +
            'Chauffeur : ' + trajet.chauffeur + '<br>' +
            'Vehicule : ' + trajet.vehicule_marque + ' ' + trajet.vehicule_modele + eco + '<br>' +
            // Lien vers la page detaillee de ce trajet
            '<a href="/covoiturage/' + trajet.id_covoiturage + '">Voir le detail</a>';

        resultats.appendChild(li);
    });
}

// On declenche le rechargement a chaque modification d'un filtre
filtreEco.addEventListener('change', chargerCovoiturages);
filtrePrix.addEventListener('input', chargerCovoiturages);
filtrePlaces.addEventListener('input', chargerCovoiturages);

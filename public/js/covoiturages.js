// === Filtres AJAX des covoiturages ===
const filtreEco = document.getElementById('filtre-eco');
const filtrePrix = document.getElementById('filtre-prix');
const filtrePlaces = document.getElementById('filtre-places');
const resultats = document.getElementById('resultats');

async function chargerCovoiturages() {
    const params = new URLSearchParams();
    if (filtreEco.checked) params.append('eco', '1');
    if (filtrePrix.value) params.append('prix_max', filtrePrix.value);
    if (filtrePlaces.value) params.append('places_min', filtrePlaces.value);

    const reponse = await fetch('/api/covoiturages?' + params.toString());
    const covoiturages = await reponse.json();

    resultats.innerHTML = '';

    if (covoiturages.length === 0) {
        resultats.innerHTML = '<p class="aucun-resultat">Aucun covoiturage pour ces criteres.</p>';
        return;
    }

    covoiturages.forEach(function (trajet) {
        // Badge ecologique uniquement si electrique
        const badgeEco = trajet.vehicule_energie === 'electrique'
            ? '<span class="badge-eco">🌿 Electrique</span>'
            : '';

        // Initiale du chauffeur pour l'avatar
        const initiale = trajet.chauffeur.charAt(0).toUpperCase();

        const li = document.createElement('li');
        li.className = 'trajet-carte-liste';
        li.innerHTML =
            '<div class="trajet-infos">' +
                '<p class="trajet-route">' +
                    '<span class="point-depart"></span>' +
                    '<strong>' + trajet.ville_depart + '</strong>' +
                    '<span class="fleche">→</span>' +
                    '<strong>' + trajet.ville_arrivee + '</strong>' +
                    badgeEco +
                '</p>' +
                '<p class="trajet-chauffeur">' +
                    '<span class="avatar-mini">' + initiale + '</span>' +
                    trajet.chauffeur + ' · ' +
                    trajet.vehicule_marque + ' ' + trajet.vehicule_modele + ' · ' +
                    trajet.nb_places + ' places' +
                '</p>' +
                '<p class="trajet-heure">' + trajet.depart + '</p>' +
            '</div>' +
            '<div class="trajet-action">' +
                '<span class="trajet-prix">' + trajet.prix + ' <small>credits</small></span>' +
                '<a href="/covoiturage/' + trajet.id_covoiturage + '" class="btn-voir">Voir</a>' +
            '</div>';

        resultats.appendChild(li);
    });
}

filtreEco.addEventListener('change', chargerCovoiturages);
filtrePrix.addEventListener('input', chargerCovoiturages);
filtrePlaces.addEventListener('input', chargerCovoiturages);
<!-- Page d'accueil : banniere avec recherche integree -->
<section class="banniere">
    
    <p class="banniere-accent">🌿 Le covoiturage responsable</p>
    <h1 class="banniere-titre">Roulez malin, roulez vert</h1>
    <p class="banniere-soustitre">Trouvez un trajet près de chez vous en 30 secondes.</p>
    

    <!-- Recherche rapide : envoie vers la page covoiturages (methode GET) -->
    <form action="/covoiturages" method="get" class="banniere-recherche" role="search">
        <input type="text" name="depart" placeholder="Ville de depart" aria-label="Ville de depart">
        <input type="text" name="arrivee" placeholder="Ville d'arrivee" aria-label="Ville d'arrivee">
        <button type="submit">Rechercher</button>
    </form>

    <!-- Chiffres-cles -->
    <div class="banniere-chiffres">
        <div class="chiffre">
            <span class="chiffre-valeur">20</span>
            <span class="chiffre-label">credits offerts</span>
        </div>
        <div class="chiffre">
            <span class="chiffre-valeur">100%</span>
            <span class="chiffre-label">trajets verifies</span>
        </div>
        <div class="chiffre">
            <span class="chiffre-valeur">0 CO&#8322;</span>
            <span class="chiffre-label">en covoiturage electrique</span>
        </div>
    </div>
</section>

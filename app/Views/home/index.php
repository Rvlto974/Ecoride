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

    <p class="banniere-preuve">⭐⭐⭐⭐⭐ 4.8/5 · plus de 200 conducteurs notés</p>

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

<!-- Comment ca marche -->
<section class="hiw">
    <h2 class="hiw-titre">Comment ça marche ?</h2>
    <div class="hiw-etapes">
        <div class="hiw-etape">
            <div class="hiw-icone">🔍</div>
            <h3 class="hiw-etape-titre">1. Cherchez</h3>
            <p class="hiw-etape-texte">Indiquez depart et arrivee, trouvez le trajet ideal en un instant.</p>
        </div>
        <div class="hiw-etape">
            <div class="hiw-icone">👤</div>
            <h3 class="hiw-etape-titre">2. Reservez</h3>
            <p class="hiw-etape-texte">Choisissez un conducteur note et reservez avec vos credits.</p>
        </div>
        <div class="hiw-etape">
            <div class="hiw-icone">🚗</div>
            <h3 class="hiw-etape-titre">3. Voyagez</h3>
            <p class="hiw-etape-texte">Partagez la route, reduisez vos couts et votre empreinte carbone.</p>
        </div>
    </div>
</section>
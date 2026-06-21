<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class CovoiturageModel extends Model
{
    // Recupere tous les covoiturages disponibles (avec infos chauffeur + vehicule)
    public function findAll(): array
    {
        // Jointures : covoiturage -> utilisateur (chauffeur), vehicule, marque
        $sql = 'SELECT c.*,
                       u.pseudo AS chauffeur,
                       v.modele AS vehicule_modele,
                       v.energie AS vehicule_energie,
                       m.libelle AS vehicule_marque
                FROM covoiturage c
                JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                JOIN vehicule v ON c.id_vehicule = v.id_vehicule
                JOIN marque m ON v.id_marque = m.id_marque
                WHERE c.nb_places > 0
                ORDER BY c.depart ASC';

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Recherche les covoiturages selon depart, arrivee et date
    public function search(string $depart, string $arrivee, string $date): array
    {
        // Memes jointures que findAll, mais avec des filtres (requete preparee)
        $sql = 'SELECT c.*,
                       u.pseudo AS chauffeur,
                       v.modele AS vehicule_modele,
                       v.energie AS vehicule_energie,
                       m.libelle AS vehicule_marque
                FROM covoiturage c
                JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                JOIN vehicule v ON c.id_vehicule = v.id_vehicule
                JOIN marque m ON v.id_marque = m.id_marque
                WHERE c.nb_places > 0
                  AND c.ville_depart LIKE :depart
                  AND c.ville_arrivee LIKE :arrivee
                  AND DATE(c.depart) = :date
                ORDER BY c.depart ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            // Le % permet une recherche partielle (ex : "lyon" trouve "Lyon")
            'depart'  => '%' . $depart . '%',
            'arrivee' => '%' . $arrivee . '%',
            'date'    => $date,
        ]);

        return $stmt->fetchAll();
    }

    // Recherche avec filtres avances (pour l'AJAX)
    // $filtres est un tableau : eco, prix_max, places_min
    public function searchFiltered(array $filtres): array
    {
        // On part de la requete de base avec les jointures
        $sql = 'SELECT c.*,
                       u.pseudo AS chauffeur,
                       v.modele AS vehicule_modele,
                       v.energie AS vehicule_energie,
                       m.libelle AS vehicule_marque
                FROM covoiturage c
                JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                JOIN vehicule v ON c.id_vehicule = v.id_vehicule
                JOIN marque m ON v.id_marque = m.id_marque
                WHERE c.nb_places > 0';

        $params = [];

        // Filtre ecologique : seulement les vehicules electriques
        if (!empty($filtres['eco'])) {
            $sql .= ' AND v.energie = :energie';
            $params['energie'] = 'electrique';
        }

        // Filtre prix maximum
        if (!empty($filtres['prix_max'])) {
            $sql .= ' AND c.prix <= :prix_max';
            $params['prix_max'] = (int) $filtres['prix_max'];
        }

        // Filtre nombre de places minimum
        if (!empty($filtres['places_min'])) {
            $sql .= ' AND c.nb_places >= :places_min';
            $params['places_min'] = (int) $filtres['places_min'];
        }

        $sql .= ' ORDER BY c.depart ASC';

        // Requete preparee avec les filtres actifs
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Recupere UN covoiturage precis par son id (pour la vue detaillee)
    public function findById(int $id): ?array
    {
        // Memes jointures, mais on filtre sur l'id du covoiturage
        $sql = 'SELECT c.*,
                       u.pseudo AS chauffeur,
                       v.modele AS vehicule_modele,
                       v.energie AS vehicule_energie,
                       v.couleur AS vehicule_couleur,
                       v.nb_places AS vehicule_nb_places,
                       m.libelle AS vehicule_marque
                FROM covoiturage c
                JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                JOIN vehicule v ON c.id_vehicule = v.id_vehicule
                JOIN marque m ON v.id_marque = m.id_marque
                WHERE c.id_covoiturage = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $covoiturage = $stmt->fetch();

        // null si aucun trajet trouve (id inexistant)
        return $covoiturage ?: null;
    }

    // Inscrit un passager a un covoiturage (transaction : tout ou rien)
    // Retourne un tableau : succes (bool), message (string), et credits si succes
    public function participer(int $idCovoiturage, int $idUtilisateur): array
    {
        // On recupere le trajet pour verifier places + prix
        $covoiturage = $this->findById($idCovoiturage);
        if ($covoiturage === null) {
            return ['succes' => false, 'message' => 'Ce covoiturage n existe pas.'];
        }

        // Verification : reste-t-il des places ?
        if ($covoiturage['nb_places'] <= 0) {
            return ['succes' => false, 'message' => 'Il n y a plus de places disponibles.'];
        }

        // On recupere les credits de l'utilisateur
        $sql = 'SELECT credits FROM utilisateur WHERE id_utilisateur = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idUtilisateur]);
        $user = $stmt->fetch();

        // Verification : assez de credits ?
        if ($user['credits'] < $covoiturage['prix']) {
            return ['succes' => false, 'message' => 'Vous n avez pas assez de credits.'];
        }

        // Verification : ne participe-t-il pas deja ?
        $sql = 'SELECT COUNT(*) AS total FROM participation
                WHERE id_utilisateur = :user AND id_covoiturage = :covoit';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user' => $idUtilisateur, 'covoit' => $idCovoiturage]);
        if ($stmt->fetch()['total'] > 0) {
            return ['succes' => false, 'message' => 'Vous participez deja a ce trajet.'];
        }

        // ===== TRANSACTION : les 3 operations doivent reussir ensemble =====
        try {
            $this->db->beginTransaction();

            // 1. Debiter les credits du passager
            $sql = 'UPDATE utilisateur SET credits = credits - :prix WHERE id_utilisateur = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['prix' => $covoiturage['prix'], 'id' => $idUtilisateur]);

            // 2. Enregistrer la participation
            $sql = 'INSERT INTO participation (id_utilisateur, id_covoiturage, credits_utilises, statut, validation)
                    VALUES (:user, :covoit, :prix, :statut, :validation)';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'user' => $idUtilisateur,
                'covoit' => $idCovoiturage,
                'prix' => $covoiturage['prix'],
                'statut' => 'confirme',
                'validation' => 'en_attente',
            ]);

            // 3. Decrementer le nombre de places du trajet
            $sql = 'UPDATE covoiturage SET nb_places = nb_places - 1 WHERE id_covoiturage = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $idCovoiturage]);

            // Tout a reussi -> on valide la transaction
            $this->db->commit();

            // On renvoie aussi le nouveau solde de credits (prix deduit)
            return [
                'succes' => true,
                'message' => 'Participation confirmee !',
                'credits' => $user['credits'] - $covoiturage['prix'],
            ];

        } catch (\Exception $e) {
            // Une operation a echoue -> on annule tout
            $this->db->rollBack();
            return ['succes' => false, 'message' => 'Une erreur est survenue, veuillez reessayer.'];
        }
    }
    // Recupere les covoiturages auxquels un utilisateur participe (son historique)
    public function findParticipations(int $idUtilisateur): array
    {
        // On joint participation -> covoiturage -> chauffeur/vehicule/marque
        // pour afficher les details de chaque trajet reserve
        $sql = 'SELECT c.*,
                       u.pseudo AS chauffeur,
                       v.modele AS vehicule_modele,
                       v.energie AS vehicule_energie,
                       m.libelle AS vehicule_marque,
                       p.credits_utilises,
                       p.statut AS participation_statut,
                       p.date_confirmation
                FROM participation p
                JOIN covoiturage c ON p.id_covoiturage = c.id_covoiturage
                JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                JOIN vehicule v ON c.id_vehicule = v.id_vehicule
                JOIN marque m ON v.id_marque = m.id_marque
                WHERE p.id_utilisateur = :id
                ORDER BY p.date_confirmation DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idUtilisateur]);
        return $stmt->fetchAll();
    }
}

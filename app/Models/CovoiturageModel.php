<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class CovoiturageModel extends Model
{
    // Recupere tous les covoiturages disponibles (avec infos chauffeur + vehicule)
    public function findAll(): array
    {
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
            'depart'  => '%' . $depart . '%',
            'arrivee' => '%' . $arrivee . '%',
            'date'    => $date,
        ]);

        return $stmt->fetchAll();
    }

    // Recherche avec filtres avances (pour l'AJAX) : eco, prix_max, places_min
    public function searchFiltered(array $filtres): array
    {
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

        if (!empty($filtres['eco'])) {
            $sql .= ' AND v.energie = :energie';
            $params['energie'] = 'electrique';
        }
        if (!empty($filtres['prix_max'])) {
            $sql .= ' AND c.prix <= :prix_max';
            $params['prix_max'] = (int) $filtres['prix_max'];
        }
        if (!empty($filtres['places_min'])) {
            $sql .= ' AND c.nb_places >= :places_min';
            $params['places_min'] = (int) $filtres['places_min'];
        }

        $sql .= ' ORDER BY c.depart ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Recupere UN covoiturage precis par son id (pour la vue detaillee)
    public function findById(int $id): ?array
    {
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

        return $covoiturage ?: null;
    }

    // Inscrit un passager a un covoiturage (transaction : tout ou rien)
    public function participer(int $idCovoiturage, int $idUtilisateur): array
    {
        $covoiturage = $this->findById($idCovoiturage);
        if ($covoiturage === null) {
            return ['succes' => false, 'message' => 'Ce covoiturage n existe pas.'];
        }

        if ($covoiturage['nb_places'] <= 0) {
            return ['succes' => false, 'message' => 'Il n y a plus de places disponibles.'];
        }

        $sql = 'SELECT credits FROM utilisateur WHERE id_utilisateur = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idUtilisateur]);
        $user = $stmt->fetch();

        if ($user['credits'] < $covoiturage['prix']) {
            return ['succes' => false, 'message' => 'Vous n avez pas assez de credits.'];
        }

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

            $this->db->commit();

            return [
                'succes' => true,
                'message' => 'Participation confirmee !',
                'credits' => $user['credits'] - $covoiturage['prix'],
            ];

        } catch (\Exception $e) {
            $this->db->rollBack();
            return ['succes' => false, 'message' => 'Une erreur est survenue, veuillez reessayer.'];
        }
    }

    // Recupere les covoiturages auxquels un utilisateur participe (son historique)
    public function findParticipations(int $idUtilisateur): array
    {
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

    // Cree un nouveau covoiturage propose par un chauffeur
    public function creer(array $data, int $idUtilisateur): bool
    {
        $sql = 'INSERT INTO covoiturage
                (ville_depart, ville_arrivee, depart, arrivee, prix, nb_places, statut, id_vehicule, id_utilisateur)
                VALUES
                (:depart_ville, :arrivee_ville, :depart, :arrivee, :prix, :places, :statut, :vehicule, :user)';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'depart_ville'  => $data['ville_depart'],
            'arrivee_ville' => $data['ville_arrivee'],
            'depart'        => $data['depart'],
            'arrivee'       => $data['arrivee'],
            'prix'          => (int) $data['prix'],
            'places'        => (int) $data['nb_places'],
            'statut'        => 'en_attente',
            'vehicule'      => (int) $data['id_vehicule'],
            'user'          => $idUtilisateur,
        ]);
    }
}

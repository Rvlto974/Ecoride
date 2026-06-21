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
}
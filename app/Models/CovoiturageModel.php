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
}
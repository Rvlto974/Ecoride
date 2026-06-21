<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class VehiculeModel extends Model
{
    // Recupere tous les vehicules d'un utilisateur (pour le menu deroulant)
    public function findByUser(int $idUtilisateur): array
    {
        $sql = 'SELECT v.*, m.libelle AS marque
                FROM vehicule v
                JOIN marque m ON v.id_marque = m.id_marque
                WHERE v.id_utilisateur = :id
                ORDER BY v.modele ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idUtilisateur]);
        return $stmt->fetchAll();
    }

    // Recupere toutes les marques (pour le menu deroulant du formulaire vehicule)
    public function findAllMarques(): array
    {
        $sql = 'SELECT * FROM marque ORDER BY libelle ASC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Cree un nouveau vehicule rattache a l'utilisateur
    public function create(array $data, int $idUtilisateur): bool
    {
        $sql = 'INSERT INTO vehicule
                (immatriculation, date_premiere_immat, modele, couleur, nb_places, energie, id_marque, id_utilisateur)
                VALUES
                (:immat, :date_immat, :modele, :couleur, :nb_places, :energie, :marque, :user)';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'immat'      => $data['immatriculation'],
            'date_immat' => $data['date_premiere_immat'],
            'modele'     => $data['modele'],
            'couleur'    => $data['couleur'],
            'nb_places'  => (int) $data['nb_places'],
            'energie'    => $data['energie'],
            'marque'     => (int) $data['id_marque'],
            'user'       => $idUtilisateur,
        ]);
    }
}
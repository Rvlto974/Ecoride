<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class StatsModel extends Model
{
    // Compte le nombre total d'utilisateurs
    public function compterUtilisateurs(): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM utilisateur';
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetch()['total'];
    }

    // Compte le nombre total de covoiturages proposes
    public function compterCovoiturages(): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM covoiturage';
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetch()['total'];
    }

    // Compte le nombre total de participations (reservations)
    public function compterParticipations(): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM participation';
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetch()['total'];
    }

    // Calcule le total des credits en circulation (somme des credits utilisateurs)
    public function totalCredits(): int
    {
        $sql = 'SELECT SUM(credits) AS total FROM utilisateur';
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetch()['total'];
    }

    // Calcule le nombre de covoiturages par jour (pour un futur graphique)
    public function covoituragesParJour(): array
    {
        // On groupe les covoiturages par date de depart
        $sql = 'SELECT DATE(depart) AS jour, COUNT(*) AS total
                FROM covoiturage
                GROUP BY DATE(depart)
                ORDER BY jour ASC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
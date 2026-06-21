<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class UserModel extends Model
{
    // Recherche un utilisateur par son email
    public function findByEmail(string $email): ?array
    {
        $sql = 'SELECT * FROM utilisateur WHERE email = :email';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    // Verifie si un email est deja utilise
    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    // Cree un compte utilisateur classique (inscription)
    public function create(string $pseudo, string $email, string $motDePasse): bool
    {
        $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO utilisateur (pseudo, email, mot_de_passe, credits, role, est_passager)
                VALUES (:pseudo, :email, :mdp, 20, :role, 1)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'pseudo' => $pseudo,
            'email'  => $email,
            'mdp'    => $hash,
            'role'   => 'utilisateur',
        ]);
    }

    // ===== Methodes pour l'administration =====

    // Recupere tous les comptes (pour la gestion admin)
    public function findAll(): array
    {
        $sql = 'SELECT id_utilisateur, pseudo, email, role, statut, credits
                FROM utilisateur
                ORDER BY id_utilisateur ASC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Cree un compte employe (role "employe")
    public function creerEmploye(string $pseudo, string $email, string $motDePasse): bool
    {
        $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO utilisateur (pseudo, email, mot_de_passe, credits, role, est_passager)
                VALUES (:pseudo, :email, :mdp, 0, :role, 0)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'pseudo' => $pseudo,
            'email'  => $email,
            'mdp'    => $hash,
            'role'   => 'employe',
        ]);
    }

    // Change le statut d'un compte (actif <-> suspendu)
    public function changerStatut(int $id, string $nouveauStatut): bool
    {
        $sql = 'UPDATE utilisateur SET statut = :statut WHERE id_utilisateur = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'statut' => $nouveauStatut,
            'id'     => $id,
        ]);
    }

    // Recupere un compte par son id
    public function findById(int $id): ?array
    {
        $sql = 'SELECT * FROM utilisateur WHERE id_utilisateur = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }
}

<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class UserModel extends Model
{
    public function findByEmail(string $email): ?array
    {
        $sql = 'SELECT * FROM utilisateur WHERE email = :email';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    public function create(string $pseudo, string $email, string $motDePasse): bool
    {
        $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO utilisateur (pseudo, email, mot_de_passe, credits, role, est_passager) VALUES (:pseudo, :email, :mdp, 20, :role, 1)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'pseudo' => $pseudo,
            'email'  => $email,
            'mdp'    => $hash,
            'role'   => 'utilisateur',
        ]);
    }
}

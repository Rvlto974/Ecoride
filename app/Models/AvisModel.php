<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Mongo;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

class AvisModel
{
    // Recupere la collection "avis" de la base MongoDB
    private function getCollection()
    {
        return Mongo::getDatabase()->selectCollection('avis');
    }

    // Enregistre un nouvel avis dans MongoDB (statut "en_attente" par defaut)
    public function creer(array $data): bool
    {
        $collection = $this->getCollection();

        $resultat = $collection->insertOne([
            'id_passager'      => $data['id_passager'],
            'pseudo_passager'  => $data['pseudo_passager'],
            'id_chauffeur'     => $data['id_chauffeur'],
            'pseudo_chauffeur' => $data['pseudo_chauffeur'],
            'note'             => (int) $data['note'],
            'commentaire'      => $data['commentaire'],
            'statut'           => 'en_attente',
            'date'             => new UTCDateTime(),
        ]);

        return $resultat->getInsertedCount() === 1;
    }

    // Recupere tous les avis VALIDES d'un chauffeur (pour affichage public)
    public function findValidesByChauffeur(int $idChauffeur): array
    {
        $collection = $this->getCollection();
        $curseur = $collection->find([
            'id_chauffeur' => $idChauffeur,
            'statut' => 'valide',
        ]);
        return $curseur->toArray();
    }

    // Calcule la note moyenne d'un chauffeur (sur ses avis valides)
    // Retourne null s'il n'a aucun avis valide
    public function moyenneChauffeur(int $idChauffeur): ?float
    {
        $avis = $this->findValidesByChauffeur($idChauffeur);

        // Aucun avis -> pas de moyenne
        if (empty($avis)) {
            return null;
        }

        // On additionne toutes les notes
        $total = 0;
        foreach ($avis as $unAvis) {
            $total += $unAvis['note'];
        }

        // Moyenne arrondie a 1 decimale
        return round($total / count($avis), 1);
    }

    // Recupere tous les avis EN ATTENTE (pour la moderation employe)
    public function findEnAttente(): array
    {
        $collection = $this->getCollection();
        $curseur = $collection->find(['statut' => 'en_attente']);
        return $curseur->toArray();
    }

    // Change le statut d'un avis (valide ou refuse) - pour la moderation
    public function changerStatut(string $id, string $nouveauStatut): bool
    {
        $collection = $this->getCollection();
        $resultat = $collection->updateOne(
            ['_id' => new ObjectId($id)],
            ['$set' => ['statut' => $nouveauStatut]]
        );
        return $resultat->getModifiedCount() === 1;
    }
}

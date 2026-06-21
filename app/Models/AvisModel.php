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

        // En NoSQL, on insere un "document" (equivalent d'une ligne, mais flexible)
        $resultat = $collection->insertOne([
            'id_passager'      => $data['id_passager'],
            'pseudo_passager'  => $data['pseudo_passager'],
            'id_chauffeur'     => $data['id_chauffeur'],
            'pseudo_chauffeur' => $data['pseudo_chauffeur'],
            'note'             => (int) $data['note'],
            'commentaire'      => $data['commentaire'],
            'statut'           => 'en_attente', // pour la moderation par l'employe
            'date'             => new UTCDateTime(), // date et heure actuelles
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

    // Recupere tous les avis EN ATTENTE (pour la moderation employe)
    public function findEnAttente(): array
    {
        $collection = $this->getCollection();
        $curseur = $collection->find(['statut' => 'en_attente']);
        return $curseur->toArray();
    }

    // Change le statut d'un avis (valide ou refuse) - pour la moderation
    // $id est l'identifiant Mongo (_id) sous forme de chaine
    public function changerStatut(string $id, string $nouveauStatut): bool
    {
        $collection = $this->getCollection();

        // updateOne : on cible le document par son _id et on modifie son statut
        $resultat = $collection->updateOne(
            ['_id' => new ObjectId($id)],          // filtre : quel document
            ['$set' => ['statut' => $nouveauStatut]] // modification a appliquer
        );

        // getModifiedCount = 1 si un document a bien ete modifie
        return $resultat->getModifiedCount() === 1;
    }
}

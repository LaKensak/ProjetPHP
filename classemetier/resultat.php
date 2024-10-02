<?php

// table resultat(idGrandPrix, idPilote, place, point)

class Resultat
{

    /**
     * Retourne true si le résultat du grand prix passé en paramètre est saisi
     * @param int $idGrandPrix
     * @return bool
     */
    public static function estSaisi($idGrandPrix): bool
    {
        $sql = <<<EOD
            Select count(*)
            FROM resultat
            WHERE idGrandPrix = :idGrandPrix;
EOD;
        $select = new Select();
        return $select->getValue($sql, ['idGrandPrix' => $idGrandPrix]) > 0;
    }


    /**
     * Retourne le classement du grand prix passé en paramètre
     * @param int $idGrandPrix
     * @return array
     */
    public static function getClassement($idGrandPrix): array
    {
        $sql = <<<EOD
            SELECT place as Place, concat(pilote.nom, ' ', prenom) as Pilote, ecurie.nom as ecurie, resultat.point as Point 
            FROM resultat, pilote, ecurie
            Where resultat.idPilote = pilote.id
              and pilote.idEcurie = ecurie.id
            and idGrandPrix = :idGrandPrix 
            order by Place;
EOD;

        $select = new Select();
        return $select->getRows($sql, ['idGrandPrix' => $idGrandPrix]);
    }

    public static function supprimer($idGrandPrix)
    {
        $sql = <<<EOD
            delete from resultat
            where idGrandPrix = :idGrandPrix;
            update grandprix set idPilote = null where id = :idGrandPrix;
EOD;
        $db = Database::getInstance();
        $curseur = $db->prepare($sql);
        $curseur->bindParam('idGrandPrix', $idGrandPrix);
        try {
            $curseur->execute();
        } catch (Exception $e) {
            Erreur::envoyerReponse($e->getMessage());
        }
    }

    public static function ajouter($idGrandPrix, $idPilote, $place, $point)
    {
        $sql = <<<EOD
            INSERT INTO resultat (idGrandPrix, idPilote, place, point)
            VALUES (:idGrandPrix, :idPilote, :place, :point);
EOD;
        $db = Database::getInstance();
        $curseur = $db->prepare($sql);
        $curseur->bindParam('idGrandPrix', $idGrandPrix);
        $curseur->bindParam('idPilote', $idPilote);
        $curseur->bindParam('place', $place);
        $curseur->bindParam('point', $point);
        try {
            $curseur->execute();
        } catch (Exception $e) {
            Erreur::envoyerReponse($e->getMessage());
        }
    }




}
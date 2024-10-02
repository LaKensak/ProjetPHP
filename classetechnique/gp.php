<?php

class gp extends Table
{

    public static function getNomEcurie($id)
    {
        $sql = "SELECT nom FROM ecurie WHERE id = :id";
        $select = new Select();
        $ligne = $select->getRow($sql, ['id' => $id]);
        return $ligne ? $ligne['nom'] : null;
    }

    public static function getNomPilote($id)
    {
        $sql = "Select nom,photo from pilote where id = :id";
        $select = new Select();
        $ligne = $select->getRow($sql, ['id' => $id]);
        return $ligne ? $ligne['nom'] : null;
    }


    public static function getClassement($id)
    {
        $sql = "SELECT DATE_FORMAT(date,'%d/%m/%Y') as dateFr, grandprix.nom,  SUM(resultat.point) as point, grandprix.idPays
                FROM resultat
                JOIN grandprix ON resultat.idGrandprix = grandprix.id
                JOIN pilote ON resultat.idPilote = pilote.id
                WHERE idEcurie = :id
                GROUP BY grandprix.date, grandprix.nom";
        $select = new Select();
        return json_encode($select->getRows($sql, ['id' => $id]));
    }

    public static function getClassementGP()
    {
        $sql = <<<EOD
        SELECT ecurie.id,
                   ecurie.nom,
                   idPays,
                   pays.nom as nomPays,
                   (SELECT SUM(point)
                    FROM classementpilote
                    WHERE idEcurie = ecurie.id
                    GROUP BY idEcurie) AS point,
                   (SELECT COUNT(*) + 1
                    FROM (SELECT SUM(point) AS total_points
                          FROM classementpilote
                          GROUP BY idEcurie) AS p
                    WHERE total_points > point) AS place
            FROM ecurie
            JOIN pays ON ecurie.idPays = pays.id
            ORDER BY point DESC;
EOD;
        $select = new Select();
        return $select->getRows($sql);
    }

    public static function getCalendrier(): array
    {
        $sql = <<<EOD
Select id, date_format(date,'%d/%m') as dateFr, nom, circuit, imgCircuit, idPays,
          if(exists(select 1  from resultat where idGrandPrix = id), 1 , 0) as nb 
    from grandprix 
    order by date 
EOD;
        $select = new Select();
        return $select->getRows($sql);
    }

    public static function ClassementDP()
    {
        $sql = <<<EOD
        SELECT
            pilote.nom,
            SUM(resultat.point) AS points,
            (
                SELECT COUNT(*) + 1
                FROM (
                    SELECT SUM(point) AS points
                    FROM classementpilote
                    GROUP BY id
                ) AS p
                WHERE points > SUM(resultat.point)
            ) AS place,
            GROUP_CONCAT(
                CONCAT(IF(resultat.point = 0, '-/', CONCAT(resultat.point, '/')), resultat.place) ORDER BY grandprix.date SEPARATOR '  '
            ) AS PointParGP,
            GROUP_CONCAT(DISTINCT grandprix.idPays) AS pays_participes
        FROM
            ecurie
        JOIN
            pays ON ecurie.idPays = pays.id
        JOIN
            pilote ON ecurie.id = pilote.idEcurie
        JOIN
            resultat ON pilote.id = resultat.idPilote
        JOIN
            grandprix ON resultat.idGrandprix = grandprix.id
        GROUP BY
            pilote.id
        ORDER BY
            points DESC;
EOD;
        $select = new Select();
        return $select->getRows($sql);
    }

    public static function getClassementEcurie()
    {
        $sql = <<<EOD
        SELECT
            ecurie.nom AS nomEcurie,
            SUM(resultat.point) AS points,
            (
                SELECT COUNT(*) + 1
                FROM (
                    SELECT SUM(point) AS points
                    FROM classementecurie
                    GROUP BY id
                ) AS e
                WHERE points > SUM(resultat.point)
            ) AS place,
            GROUP_CONCAT(
                CONCAT(IF(resultat.point = 0, '-/', CONCAT(resultat.point, '/')), resultat.place) ORDER BY grandprix.date SEPARATOR '  '
            ) AS PointParGP,
            GROUP_CONCAT(DISTINCT grandprix.idPays) AS pays_participes
        FROM
            ecurie
        JOIN
            pays ON ecurie.idPays = pays.id
        JOIN
            pilote ON ecurie.id = pilote.idEcurie
        JOIN
            resultat ON pilote.id = resultat.idPilote
        JOIN
            grandprix ON resultat.idGrandprix = grandprix.id
        GROUP BY
            ecurie.id
        ORDER BY
            points DESC;

EOD;
        $select = new Select();
        return $select->getRows($sql);
    }

    public static function getClassementPilote()
    {
        $sql = <<<EOD
           SELECT
            pilote.id,
            pilote.nom as piloteNom,
            pilote.idPays,
            pays.nom AS nomPays,
            e.nom AS nomEcurie,
            (SELECT SUM(point)
             FROM classementpilote
             WHERE classementpilote.id = pilote.id
             GROUP BY classementpilote.id) AS point,
            (SELECT COUNT(*) + 1
             FROM (SELECT SUM(point) AS total_points
                   FROM classementpilote
                   GROUP BY id) AS p
             WHERE total_points > point) AS place
        FROM
            pilote
        JOIN
            ecurie e ON pilote.idEcurie = e.id
        JOIN
            pays ON pilote.idPays = pays.id
        ORDER BY
            point DESC;

EOD;
        $select = new Select();
        return $select->getRows($sql);
    }

    public static function getClassementE($id) {
        $sql = <<<EOD
            SELECT DATE_FORMAT(date,'%d/%m/%Y') as dateFr, grandprix.nom,  SUM(resultat.point) as point, grandprix.idPays
            FROM resultat
            JOIN grandprix ON resultat.idGrandprix = grandprix.id
            JOIN pilote ON resultat.idPilote = pilote.id
            WHERE idEcurie = :id
            GROUP BY grandprix.date, grandprix.nom
EOD;
        $select = new Select();
        return $select->getRows($sql, ['id' => $id]);
    }
    public static function getClassementP($id) {
        $sql = <<<EOD
             SELECT
            date_format(date,'%d/%m') as dateFr,
            gp.nom AS nomGP,
            p.nom AS Pilote,  -- Choisissez MIN() ou MAX() pour choisir un pilote arbitrairement
            p.idEcurie AS Écurie,  -- Choisissez MIN() ou MAX() pour choisir une écurie arbitrairement
            r.place AS Place,
            r.point AS Points
        FROM
            resultat r
        JOIN
            grandprix gp ON r.idGrandPrix = gp.id
        JOIN
            pilote p ON r.idPilote = p.id
        WHERE
            p.id = :id
        GROUP BY
            gp.nom;
EOD;
        $select = new Select();
        return $select->getRows($sql, ['id' => $id]);
    }

    public static function getPilotesEcurie($id) {
        $sql = <<<EOD
            SELECT logo,p.id as Pays,imgVoiture,pilote.nom as nomPilote, pilote.prenom,photo,pilote.id as idPilote,pilote.idPays as paysPilote
            FROM ecurie
            JOIN pays p ON ecurie.idPays = p.id
            JOIN pilote ON ecurie.id = pilote.idEcurie
            WHERE ecurie.id = :id
EOD;
        $select = new Select();
        $lignesPilotes = $select->getRows($sql, ['id' => $id]);

        if (count($lignesPilotes) < 2) {
            Erreur::envoyerReponse("Il n'y a pas suffisamment de pilotes dans cette écurie", 'system');
        }

        $data = [
            'pilote1' => $lignesPilotes[0],
            'pilote2' => $lignesPilotes[1]
        ];

        return $data;
    }
    public static function getPilotes($id)
    {
        $sql = <<<EOD
        SELECT photo, pays.id as Pays,e.nom as ecuriePilote,pilote.id as numPilote, pays.nom as nomPays, pilote.prenom as prenom
        FROM pilote
        join pays on pilote.idPays = pays.id
        join ecurie e on pilote.idEcurie = e.id
        WHERE pilote.id = :id
EOD;
        $select = new Select();
        $ligne = $select->getRow($sql, ['id' => $id]);
        return $ligne ? $ligne : null;
    }

}

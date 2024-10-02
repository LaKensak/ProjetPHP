<?php

// table pays(id, nom)

class Pays
{

    /**
     * Retourne l'ensemble des grands prix ordonnés chronologiquement
     * Champs à retourner : id, date au format jj/mm/aaaa (dateFr), nom et circuit
     * @return array
     */
    public static function getListe(): array
    {
        $sql = <<<EOD
       SELECT id, nom
       FROM pays
       ORDER BY nom;
EOD;
        $select = new Select();
        return $select->getRows($sql);
    }

    public static function getLesId(): array
    {
        $sql = <<<EOD
       Select id
        From pays
        order by nom;   
EOD;
        $select = new Select();
        return $select->getRows($sql);
    }


}
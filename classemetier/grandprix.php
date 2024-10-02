<?php

// table grandprix(id, date, nom, circuit, idPays, idPilote)

class GrandPrix extends Table
{

    public function __construct()
    {
        parent::__construct('grandprix');

        // date
        $input = new inputDate();
        $input->Require = true;
        $annee = date("Y");
        $input->Max = "$annee-12-31";
        $annee--;
        $input->Min = "$annee-01-01";
        $this->columns['date'] = $input;

        // nom
        $input = new inputText();
        $input->Require = true;
        $input->EnMajuscule = false;
        $input->SupprimerAccent = false;
        $input->SupprimerEspaceSuperflu = true;
        $input->Pattern ="^[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]([ '\-]?[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])*$";
        $input->MaxLength = 50;
        $this->columns['nom'] = $input;

        // circuit
        $input = new inputText();
        $input->Require = true;
        $input->EnMajuscule = false;
        $input->SupprimerAccent = false;
        $input->SupprimerEspaceSuperflu = true;
        $input->Pattern ="^[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]([ '\-]?[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])*$";
        $input->MaxLength = 50;
        $this->columns['circuit'] = $input;

        // idPays
        $input = new InputList();
        $input->Require = true;
        // il faut récupérer chaque id de pays
        $lesLignes = Pays::getLesId();
        // stockage des id dans un tableau
        foreach ($lesLignes as $ligne) {
            $input->Values[] = $ligne['id'];
        }
        $this->columns['idPays'] = $input;

    }


    /**
     * Retourne l'ensemble des grands prix ordonnés chronologiquement
     * @return array
     */
    public static function getAll(): array
    {
        $sql = <<<EOD
            SELECT id, date_format(date, '%d/%m/%Y') as dateFr, nom, circuit, date, idPays, idPilote,
                   if(exists(select 1 from resultat where resultat.idGrandPrix = id), 0, 1) as deleteOk
            FROM grandprix
            ORDER BY date;
EOD;
        $select = new Select();
        return $select->getRows($sql);
    }


    public static function getSansResultat(): array
    {
        $sql = <<<EOD
               SELECT id, nom, date_format(date, '%d/%m/%Y') as dateFr 
               FROM grandprix 
               Where id not in (select idGrandPrix from resultat)
               order by date;
EOD;
        $select = new Select();
        return $select->getRows($sql);
    }

    public static function getAvecResultat(): array
    {
        $sql = <<<EOD
                select id, nom, date_format(date, '%d/%m/%y') as dateFr 
                from grandprix 
                where id in (select idgrandprix from resultat)
                order by date;
EOD;
        $select = new Select();
        return $select->getRows($sql);
    }

    public static function setIdPilote($idGrandPrix, $idPilote)
    {
        $sql = <<<EOD
            update grandprix
            set idPilote = :idPilote
            where id = :idGrandPrix;
EOD;
        $db = Database::getInstance();
        $curseur = $db->prepare($sql);
        $curseur->bindParam('idPilote', $idPilote);
        $curseur->bindParam('idGrandPrix', $idGrandPrix);
        try {
            $curseur->execute();
        } catch (PDOException $e) {
            Erreur::envoyerReponse($e->getMessage());
        }
    }



}
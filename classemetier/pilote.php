<?php

// table grandprix(id, date, nom, circuit, idPays, idPilote)

class Pilote extends Table
{

    public function __construct()
    {
        parent::__construct('pilote');

        // nom
        $input = new inputText();
        $input->Require = true;
        $input->EnMajuscule = false;
        $input->SupprimerAccent = false;
        $input->SupprimerEspaceSuperflu = true;
        $input->Pattern ="^[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]([ '\-]?[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])*$";
        $input->MaxLength = 30;
        $this->columns['nom'] = $input;

        // prénom
        $input = new inputText();
        $input->Require = true;
        $input->EnMajuscule = false;
        $input->SupprimerAccent = false;
        $input->SupprimerEspaceSuperflu = true;
        $input->Pattern ="^[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]([ '\-]?[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])*$";
        $input->MaxLength = 20;
        $this->columns['prenom'] = $input;

        // numPilote
        $input = new InputList();
        $input->Require = true;
        $input->Values[] = [1, 2, 3, 4];
        $this->columns['numPilote'] = $input;

        // idEcurie
        $input = new InputList();
        $input->Require = true;
        $lesLignes = Ecurie::getLesId();
        // stockage des id dans un tableau
        foreach ($lesLignes as $ligne) {
            $input->Values[] = $ligne['id'];
        }
        $this->columns['idEcurie'] = $input;

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
     * Retourne l'ensemble des pilotes
     * @return array
     */
    public static function getAll(): array
    {
        $sql = <<<EOD
        SELECT id,names;
        FROM cubes
        order by 
EOD;
        $select = new Select();
        return $select->getRows($sql);
    }


    public static function getPhoto(): array
    {
        $sql = <<<EOD
        SELECT id, photo
        FROM pilote
        ORDER BY nom
EOD;
        $select = new Select();
        return $select->getRows($sql);
    }


    /**
     * Retourne lle numéro et le nom des pilotes
     * @return array
     */
    public static function getListe(): array
    {
        $sql = <<<EOD
            select id, concat(nom, ' ', prenom) as nom
            from pilote 
            order by id;
EOD;
        $select = new Select();
        return $select->getRows($sql);
    }
}
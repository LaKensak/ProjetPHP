<?php

// table grandprix(id, date, nom, circuit, idPays, idPilote)

class Ecurie extends Table
{

    public function __construct()
    {
        parent::__construct('ecurie');

        // nom
        $input = new inputText();
        $input->Require = true;
        $input->EnMajuscule = false;
        $input->SupprimerAccent = false;
        $input->SupprimerEspaceSuperflu = true;
        $input->Pattern ="^[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]([ '\-]?[A-Za-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])*$";
        $input->MaxLength = 45;
        $this->columns['nom'] = $input;

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
        SELECT id, nom, idPays,
               NOT EXISTS(SELECT 1 FROM pilote WHERE idEcurie = ecurie.id) AS deleteOk
        FROM ecurie
        ORDER BY nom;
EOD;
        $select = new Select();
        return $select->getRows($sql);
    }


    /**
     * Retourne le numéro et le nom des pilotes
     * @return array
     */
    public static function getListe(): array
    {
        $sql = <<<EOD
            select id, nom
            from ecurie
            order by nom;
EOD;
        $select = new Select();
        return $select->getRows($sql);
    }


    public static function getLesId(): array
    {
        $sql = <<<EOD
       Select id
        From ecurie
        order by nom;   
EOD;
        $select = new Select();
        return $select->getRows($sql);
    }
}
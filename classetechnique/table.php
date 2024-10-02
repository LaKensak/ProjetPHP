<?php

/**
 * Classe Table : représente une table SQL
 * Cette classe est une classe abstraite donc non instanciable.
 * Elle met en facteur tous les attributs et toutes les méthodes communes aux classes dérivées
 * @Author : Guy Verghote
 * @Date : 27/04/2024
 * @version : 2024.4
 */
abstract class Table
{
    // objet PDO pour réaliser l'ensemble des requêtes sur la table $tableName de la base de donnée $database
    private $db;

    // nom de la table : sera défini dans la classe dérivée
    private $tableName;

    // nom de la colonne de clé primaire par défaut id (sera défini dans la classe dérivée si la clé primaire n'est pas id)
    protected $idName = 'id';

    // Colonnes composant la structure de la table (à l'exeption de l'identifiant)
    // Tableau associatif - clé : nom de la colonne, valeur : un objet Input (contient la valeur et les règles de validations)
    // sera défini dans la classe dérivée
    protected $columns;

    // Objet InputList contenant les colonnes modifiables en mode colonne
    // sera défini dans la classe dérivée
    protected $listOfColumns;

    //  tableau d'objet Erreur alimenté par les méthodes de gestion
    private $lesErreurs = [];

    // propriété statique contenant la valeur de l'identifiant de la dernière ligne insérée lorsque l'identifiant est un champ auto-incrémenté
    // alimenté par la méthode lastInsertId de la classe PDO qui retourne une chaîne ou faux
    private $lastInsertId = false;

    /**
     * Filtre le message d'erreur pour en connaitre l'origine (déclencheur ou erreur imprévue)
     * Alimente le tableau $lesErreurs en conséquence
     * @param string $message
     * @return void
     */

    private function envoyerErreur($message)
    {
        // recherche de la présence d'un # qui signale un message provenant d'un déclencheur
        $filteredMessage = strstr($message, '#');
        if ($filteredMessage) {
            $this->lesErreurs['global'] = substr($filteredMessage, 1);
        } else {
            $this->lesErreurs['system'] = $message;
        }
    }

    /*
    -------------------------------------------------------------------------------------------------------------------
    Le constructeur
    --------------------------------------------------------------------------------------------------------------------
    */

    /**
     * Constructeur
     * @param string $nomTable nom de la table gérée
     */
    protected function __construct( $nomTable)
    {
        $this->tableName = $nomTable;
        $this->columns = [];
        $this->listOfColumns = new InputList();
        $this->db = Database::getInstance();
    }

    /*
    -------------------------------------------------------------------------------------------------------------------
    Les accesseurs sur les attributs des objets
    --------------------------------------------------------------------------------------------------------------------
    */

    /**
     * Retourne l'objet Input associé à la clé $colonne du tableau columns
     * @param string $colonne Nom de la colonne de la table
     * @return Input
     */
    public function getColonne( $colonne)
    {
        return $this->columns[$colonne];
    }

    // accesseur en lecture sur le tableau des erreurs
    public function getLesErreurs()
    {
        return $this->lesErreurs;
    }

    // accesseur en lecture sur la dernière valeur générée d'un champ auto-increment
    public function getLastInsertId()
    {
        return $this->lastInsertId;
    }

    /*
   -------------------------------------------------------------------------------------------------------------------
   Les méthodes privées permettant la génération dynamique des requêtes insert et update
   --------------------------------------------------------------------------------------------------------------------
   */

      /**
     * Alimente les paramètres de la requête et l'exécute
     * En cas d'erreur, l'erreur est conservé dans l'attribut statique lesErreurs
     * @param  string $sql Requête SQL paramétrée de type insert ou update à exécuter
     * @return bool
     */
    private function prepareAndExecute( $sql)
    {
        $curseur = $this->db->prepare($sql);

        // alimentation des paramètres de la requête
        foreach ($this->columns as $cle => $input) {
            if ($input->Value === null) {
                continue;
            }
            $curseur->bindValue($cle, $input->Value);
        }
        try {
            $curseur->execute();
            return true;
        } catch (Exception $e) {
            $this->envoyerErreur($e->getMessage());
            return false;
        }
    }

    /*
    -------------------------------------------------------------------------------------------------------------------
    Les méthodes publiques sur les objets : utilisable dans les classes dérivées
    --------------------------------------------------------------------------------------------------------------------
    */

    /**
     * Vérifie que toutes les données à transmettre sont bien transimses
     * Alimente la valeur des objets Input composant la table à partir des données transmises dans le tableau $_POST
     * Contrôle que tous les objets Input obligatoires ont bien une valeur
     * @return bool
     */
    public function donneesTransmises()
    {
        // Alimente les objets Input à l'aide du tableau $_POST
        foreach ($_POST as $cle => $valeur) {
            $valeur = trim($valeur);
            if ($valeur !== '' && isset($this->columns[$cle])) {
                $this->columns[$cle]->Value = $valeur;
            }
        }

        // Vérification que toutes les colonnes (input) obligatoires sont bien renseignées
        $ok = true;
        foreach ($this->columns as $cle => $input) {
            // s'il s'agit d'un fichier, on vérifie qu'il a bien été transmis
            if ($input instanceof InputFile) {
                if ($input->Require && !$input->fichierTransmis()) {
                    $this->lesErreurs[$cle] = "Veuillez renseigner ce champ.";
                    $ok = false;
                }
                // s'il s'agit d'un champ de type input, on vérifie qu'il a bien été renseigné s'il est obligatoire
            } elseif ($input->Require && $input->Value === null) {
                $this->lesErreurs[$cle] = "Veuillez renseigner ce champ.";
                $ok = false;
            }
        }
        return $ok;
    }

    /**
     * Contrôle la valeur attribuée à chaque colonne à partir des règles de validation associées à chaque colonne
     * @return bool
     */
    public function checkAll()
    {
        // Parcourt chaque objet Input et appelle sa méthode checkValidity pour vérifier la conformité de sa valeur
        // en cas de non-conformité, le message d'erreur est conservé dans le tableau des erreurs
        $correct = true;
        foreach ($this->columns as $cle => $input) {
            if (!$input->checkValidity()) {
                $this->lesErreurs[$cle] = $input->getValidationMessage();
                $correct = false;
            }
        }
        return $correct;
    }

    /**
     * Ajoute un enregistrement dans une table et éventuellement le fichier associé
     * @return bool
     */
    public function insert()
    {
        // génération de la requête insert
        $set = "";
        foreach ($this->columns as $cle => $input) {
            // une colonne dont la valeur de l'objet input associé est renseignée doit recevoir cette valeur
            if ($input->Value !== null) {
                $set .= "$cle = :$cle, ";
                // une colonne acceptant la valeur null reçoit cette valeur si l'objet input associé n'a pas été renseigné
            }
        }
        // suppression de la dernière virgule
        $set = substr($set, 0, -2);

        $sql = "insert into $this->tableName set $set";

        if (!$this->prepareAndExecute($sql)) {
            return false;
        } else {
            // si on trouve une colonne fichier de type InputFile, un fichier est transmis, il est copié
            if (isset($this->columns['fichier']) && $this->columns['fichier'] instanceof InputFile) {
                $this->columns['fichier']->copy();
            }
            // on renseigne la valeur de l'attribut lastInsertID au cas où (on ne peut pas savoir si l'identifiant est un compteur)
            $this->lastInsertId = $this->db->lastInsertId();
            return true;
        }
    }

    /**
     * Supprime l'enregistrement dont la valeur de la clé primaire est passé en paramètre
     * Si l'enregistrement est associé à un fichier le fichier sera aussi supprimé
     * La valeur de la clé primaire est préalablement contrôlée
     * en cas d'erreur le message est conservé dans l'attribut statique erreur
     * @param int|string $id valeur de la clé primaire (entier ou chaine de caractères)
     * @return bool
     */
    public function delete($id)
    {
        // vérification de l'id et récupération éventuelle du nom du fichier associé à l'enregistrement
        if (isset($this->columns['fichier'])) {
            $sql = <<<EOD
                select fichier from  $this->tableName
                where $this->idName = :id;
EOD;
        } else {
            $sql = <<<EOD
                Select 1 from  $this->tableName
                where $this->idName = :id;
EOD;
        }
        $curseur = $this->db->prepare($sql);
        $curseur->bindValue('id', $id);
        $curseur->execute();
        $ligne = $curseur->fetch(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        if (!$ligne) {
            $this->lesErreurs['global'] = "Enregistrement inexistant.";
            return false;
        }

        // suppression
        $sql = <<<EOD
            delete from  $this->tableName
            where $this->idName = :id;
EOD;

        $curseur = $this->db->prepare($sql);
        $curseur->bindValue('id', $id);
        try {
            $curseur->execute();
        } catch (Exception $e) {
            $this->envoyerErreur($e->getMessage());
            return false;
        }
        // si une colonne fichier existe et qu'elle est associée à un objet InputFile, il faut supprimer ce fichier
        if (isset($ligne['fichier']) && $this->columns['fichier'] instanceof InputFile) {
            $this->columns['fichier']->Value = $ligne['fichier'];
            $this->columns['fichier']->del();
        }
        return true;
    }

    /**
     *  Modifie un enregistrement dans une table
     * @param int|string $id valeur de la clé primaire
     * @param array $lesValeurs tableau associatif des nouvelles valeurs
     * @return bool
     */
    public function update($id, $lesValeurs)
    {
        // Construction de la clause SET
        $set = "";
        $newId = null;
        if (isset($lesValeurs['newId'])) {
            $newId = $lesValeurs['newId'];
            unset($lesValeurs['newId']);
        }

        foreach ($lesValeurs as $cle => $valeur) {
            $set .= "$cle = :$cle, ";
        }
        $set = rtrim($set, ', '); // Supprimer la virgule et l'espace à la fin

        try {
            // Début de la transaction
            $this->db->beginTransaction();

            // Mise à jour des autres colonnes
            $sql = "UPDATE $this->tableName SET $set WHERE $this->idName = :id";
            $curseur = $this->db->prepare($sql);

            // Liaison des paramètres nommés aux valeurs
            foreach ($lesValeurs as $cle => $valeur) {
                $curseur->bindValue(":$cle", $valeur);
            }
            $curseur->bindValue(':id', $id);
            $curseur->execute();

            // Mise à jour de l'ID si nécessaire
            if ($newId) {
                $sql = "UPDATE $this->tableName SET $this->idName = :newId WHERE $this->idName = :id";
                $curseur = $this->db->prepare($sql);
                $curseur->bindValue(':newId', $newId);
                $curseur->bindValue(':id', $id);
                $curseur->execute();
            }

            // Validation de la transaction
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Annulation de la transaction en cas d'erreur
            $this->db->rollBack();
            $this->lesErreurs['global'] = "Erreur lors de la mise à jour de l'enregistrement : " . $e->getMessage();
            return false;
        }
    }





    /**
     * Modifie la valeur d'une colonne d'un enregistrement
     * @param string $colonne Nom de la colonne à modifier
     * @param string|int $valeur Nouvelle valeur de la colonne
     * @param string|int $id Valeur de la clé primaire
     * @return bool
     */
    public function modifierColonne($colonne, $valeur, $id)
    {
        // contrôle sur la colonne : La colonne doit faire partie des colonnes modifiables de la table
        $this->listOfColumns->Value = $colonne;
        if (!$this->listOfColumns->checkValidity()) {
            $this->lesErreurs['global'] = "Ce champ n'est pas modifiable.";
            return false;
        }

        // contrôle de l'identifiant
        $sql = <<<EOD
	        SELECT 1
            FROM  $this->tableName
            where $this->idName = :id;
EOD;
        $curseur = $this->db->prepare($sql);
        $curseur->bindValue('id', $id);
        $curseur->execute();
        $ligne = $curseur->fetch(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        if (!$ligne) {
            $this->lesErreurs['global'] = "L'enregistrement à modifier n'existe pas.";
            return false;
        }

        // contrôle de la valeur à l'aide de l'objet input associé dans la classe
        $input = $this->columns[$colonne];
        $input->Value = $valeur;
        if (!$input->checkValidity()) {
            $this->lesErreurs['global'] = "La valeur pour la colonne $colonne n'est pas acceptée : " . $input->getValidationMessage();
            return false;
        }
        // modification dans la base
        // réalisation de la modification

        $sql = <<<EOD
            update  $this->tableName
             set $colonne= :valeur
             where $this->idName = :id;
EOD;
        $curseur = $this->db->prepare($sql);
        $curseur->bindValue('valeur', $valeur);
        $curseur->bindValue('id', $id);
        try {
            $curseur->execute();
            return true;
        } catch (Exception $e) {
            $this->envoyerErreur($e->getMessage());
            return false;
        }
    }

    /**
     * Modifie la valeur d'une colonne d'un enregistrement
     * @param string $colonne Nom de la colonne à modifier
     * @param string|int $valeur Nouvelle valeur de la colonne
     * @param string|int $id Valeur de la clé primaire
     * @return bool
     */
    public function setNull($colonne, $id)
    {
        // contrôle de la colonne
        if (! isset($this->columns[$colonne])) {
            $this->lesErreurs['global'] = "Requête invalide.";
            return false;
        }

        // contrôle de l'identifiant
        $sql = <<<EOD
	        SELECT 1
            FROM  $this->tableName
            where $this->idName = :id;
EOD;
        $curseur = $this->db->prepare($sql);
        $curseur->bindValue('id', $id);
        $curseur->execute();
        $ligne = $curseur->fetch(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        if (!$ligne) {
            $this->lesErreurs['global'] = "L'enregistrement à modifier n'existe pas.";
            return false;
        }

        // modification dans la base
        // réalisation de la modification

        $sql = <<<EOD
            update  $this->tableName
             set $colonne= null
             where $this->idName = :id;
EOD;
        $curseur = $this->db->prepare($sql);
        $curseur->bindValue('id', $id);
        try {
            $curseur->execute();
            return true;
        } catch (Exception $e) {
            $this->envoyerErreur($e->getMessage());
            return false;
        }
    }
}
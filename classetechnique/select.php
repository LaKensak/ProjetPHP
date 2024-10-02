<?php

/**
 * Classe permettant de gérer toutes les requêtes de consultation de la base de données
 *
 * @Author : Guy Verghote
 * @Version : 2024.5
 * @Date : 11/05/2024
 */
class Select
{

    //  attribut privé pour stocker l'objet Dbo assurant la connexion à la base de données
    private $db; // stocke l'adresse de l'unique objet instanciable

    /*
    -------------------------------------------------------------------------------------------------------------------
    Le constructeur
    --------------------------------------------------------------------------------------------------------------------
    */

    /**
     * Constructeur d'un objet Select
	 * Inialise l'attribut privé $db (objet PDO) en appelant la méthode getInstance de la classe Database
     */
    function __construct()
    {
        $this->db = Database::getInstance();
    }

    /*
    -------------------------------------------------------------------------------------------------------------------
    Les méthodes applicables sur un objet de la classe Select
    --------------------------------------------------------------------------------------------------------------------
    */

    /**
     * Retourne dans un tableau numérique, le résultat d'une requête SQL retournant plusieurs lignnes
     *  chaque ligne étant un tableau associatif clé = nomcolonne et valeur = valeur de la colonne
     * @param string $sql requête Sql
     * @param array $lesParametres tableau associatif clé = nomcolonne et valeur = valeur transmise
     * @return array
     */
    function getRows( $sql, array $lesParametres = [])
    {
        if ($lesParametres === []) {
            $curseur = $this->db->query($sql);
        } else {
            $curseur = $this->db->prepare($sql);
            foreach ($lesParametres as $cle => $valeur) {
                $curseur->bindValue($cle, $valeur);
            }
            $curseur->execute();
        }
        // fecthAll retourne un tableau vide si aucun enregistrement trouvé ou en cas d'erreur
        $lesLignes = $curseur->fetchAll(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        return $lesLignes;
    }

    /**
     * Retourne dans un tableau associatif, le résultat d'une requête retournant une seule ligne
     * @param string $sql requête Sql
     * @param array $lesParametres tableau associatif clé = nomcolonne et valeur = valeur transmise
     * @return array
     */
    function getRow( $sql, array $lesParametres = [])
    {
        if ($lesParametres === []) {
            $curseur = $this->db->query($sql);
        } else {
            $curseur = $this->db->prepare($sql);
            foreach ($lesParametres as $cle => $valeur) {
                $curseur->bindValue($cle, $valeur);
            }
            $curseur->execute();
        }
        // fecthAll retourne un tableau vide si aucun enregistrement trouvé ou en cas d'erreur
        $ligne = $curseur->fetch(PDO::FETCH_ASSOC);
        $curseur->closeCursor();
        return $ligne;
    }

    /**
     * Retourne dans une variable, le résultat d'une requête retournant une seule valeur
     * @param string $sql requête Sql
     * @param array $lesParametres tableau associatif clé = nomcolonne et valeur = valeur transmise
     * @return mixed la valeur retournée par la requête
     */
    function getValue($sql, array $lesParametres = [])
    {
        if ($lesParametres === []) {
            $curseur = $this->db->query($sql);
        } else {
            $curseur = $this->db->prepare($sql);
            foreach ($lesParametres as $cle => $valeur) {
                $curseur->bindValue($cle, $valeur);
            }
            $curseur->execute();
        }
        // fecthAll retourne un tableau vide si aucun enregistrement trouvé ou en cas d'erreur
        $valeur = $curseur->fetchColumn();
        $curseur->closeCursor();
        return $valeur;
    }
}
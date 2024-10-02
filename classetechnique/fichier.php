<?php

/**
 * Classe Fichier : ensemble de méthodes statiques concernant les opérations sur des fichiers et des répertoires
 *
 * @Author : Guy Verghote
 * @Version 2024.3
 * @Date : 28/04/2024
 */

class Fichier
{
    /**
     * Supprime le fichier dont le nom comprenant le chemin est passé en paramètre
     * @param string $fichier Nom du fichier à supprimer
     * @return bool
     */

    static public function supprimerFichier(string $fichier): bool
    {
        $ok = false;
        if (file_exists($fichier)) {
            $ok = unlink($fichier);
        }
        return $ok;
    }

    /**
     * retourne le type mime du fichier ou 'fichier inexistant'
     * @param string $fichier Nom du fichier à supprimer
     * @return string  $result message de retour
     */

    static public function typeMime(string $fichier): string
    {
        if (!file_exists($fichier)) {
            return 'fichier inexistant';
        }
        $infos = new finfo(FILEINFO_MIME);
        $type = $infos->file($fichier);
        return substr($type, 0, strpos($type, ';'));
    }

    /**
     * Retourne dans un tableau les fichiers d'un répertoire
     * l'extension des fichiers doit correspondre aux extensions passées en paramètre
     * @param string $rep Nom du répertoire
     * @param string $order critère de tri a pour croissant et d pour décroissant
     * @return array tableau des fichiers récupérés
     */
    static public function getAll(string $rep, string $order = "a"): array
    {
        $liste = array();
        $lesFichiers = scandir($rep);
        foreach ($lesFichiers as $fichier) {
            if ($fichier != "." && $fichier != "..") {
                $liste[] = $fichier;
            }
        }
        if (strtolower($order) === "a") {
            asort($liste);
        } else {
            arsort($liste);
        }
        return $liste;
    }

    /**
     * Retourne dans un tableau les fichiers d'un répertoire
     * l'extension des fichiers doit correspondre aux extensions passées en paramètre
     * @param string $rep Nom du répertoire
     * @param array $lesExtensions tableau des extensions cherchées
     * @param string $order critère de tri a pour croissant et d pour décroissant
     * @return array tableau des fichiers récupérés
     */
    static public function getLesFichiers(string $rep, array $lesExtensions, string $order = "a"): array
    {
        $liste = array();
        $lesFichiers = scandir($rep);
        foreach ($lesFichiers as $fichier) {
            if ($fichier != "." && $fichier != "..") {
                $extension = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
                if (in_array($extension, $lesExtensions)) {
                    $liste[] = $fichier;
                }
            }
        }

        natcasesort($liste);
        if (strtolower($order) !== "a") {
            $liste = array_reverse($liste);
        }
        // problème le tableau n'est plus numérique mais associatif
        return array_values($liste); // Réindexer le tableau
    }


    /**
     * Recherche et renvoi de la valeur d'une clé dans un fichier ini
     * Fichier ini : [section] composée de ligne de type cle = valeur
     * @param string $nomCle nom de la clé
     * @param string $nomSection nom de la section
     * @param string $nomFichier nom du fichier avec le chemin d'accès
     * @return array contenant deux clés
     *            'codeRetour' : -1 fichier inexistant 0, clé inexistante, 1 clé trouvée
     *            'valeur' : valeur de la clé cherchée
     */
    static public function getValeurIni(string $nomFichier, string $nomSection, string $nomCle): array
    {
        $reponse = array();
        $reponse['codeRetour'] = -1; // fichier inexistant
        $reponse['valeur'] = "";
        if (file_exists($nomFichier)) {
            $fichier = file($nomFichier);
            $i = 0;
            $nbLigne = count($fichier);
            $trouve = false;
            while ($i < $nbLigne && !$trouve) {
                $ligne = trim($fichier[$i]);
                // recherche de la section : commence par [ et se termine par ]
                // $section  est un tableau qui contient en premier élement la ligne et en second la valeur capturée ()
                if (preg_match("#^\[(.*)\]$#i", $ligne, $section)) {
                    if (strtolower($section[1]) == strtolower($nomSection)) {
                        $trouve = true;
                    }
                }
                $i++;
            }
            if ($trouve) {
                $reponse['codeRetour'] = 0; // clé inexistante
                $trouve = false;
                // recherche de la clé dans la section
                $fin = false;
                while ($i < $nbLigne && !$fin && !$trouve) {
                    $ligne = trim($fichier[$i]);
                    // si on trouve un section $fin passe à vrai
                    if (preg_match("#^\[.*\]$#i", $ligne)) {
                        $fin = true;
                    } else {
                        // détection d'une ligne clé/valeur
                        if (preg_match("#^(.+)=(.+)$#", $ligne, $couple)) {
                            $cle = trim($couple[1]); // attention respect de la casse
                            if ($cle == $nomCle) {
                                $reponse['valeur'] = trim($couple[2]);
                                $reponse['codeRetour'] = 1;
                                $trouve = true;

                            }
                        }
                    }
                    $i++;
                }
            }
        }
        return $reponse;
    }

    /**
     * Extraction d'un fichier de configuration des lignes de la forme clé = valeur
     *
     * @param string $nomFichier nom du fichier avec le chemin d'accès
     * @return array un tableau associatif associant à chaque clé sa valeur
     */
    static public function getLesParametres(string $nomFichier): array
    {
        $lesParametres = array();
        if (file_exists($nomFichier)) {
            $lesLignes = file($nomFichier);
            $i = 0;
            $nbLigne = count($lesLignes);
            while ($i < $nbLigne) {
                $ligne = trim($lesLignes[$i]);
                // détection d'une ligne clé/valeur
                if (preg_match("/^(.+)=(.+)$/", $ligne, $couple)) {
                    $cle = trim($couple[1]);  // attention respect de la casse
                    $valeur = trim($couple[2]);
                    $lesParametres[$cle] = $valeur;
                }
                $i++;
            }
        }
        return $lesParametres;
    }


    static public function getFichierLePlusRecent(string $rep, array $lesExtensions): string
    {
        $fichierRecent = null;
        $date = 0;
        if (is_dir($rep)) {
            $lesFichiers = scandir($rep);
            // Parcours de chaque fichier dans le répertoire
            foreach ($lesFichiers as $fichier) {
                // Ignorer les fichiers spéciaux '.' et '..'
                if ($fichier == '.' || $fichier == '..') {
                    continue;
                }
                $cheminFichier = $rep . DIRECTORY_SEPARATOR . $fichier;
                $extension = pathinfo($fichier, PATHINFO_EXTENSION);
                if (is_file($cheminFichier) && in_array(strtolower($extension), $lesExtensions)) {
                    $dateFichier = filemtime($cheminFichier);
                    if ($dateFichier > $date) {
                        $date = $dateFichier;
                        $fichierRecent = $fichier;
                    }
                }
            }
        }
        return $fichierRecent;
    }
}

<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">

    <title>Tableau de bord</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .dashboard-container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px 30px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            color: #333;
        }
        p {
            color: #555;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <nav class="bg-blue-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="..">
                            <span class="navbar-brand text-white">Administration</span>
                        </a>
                    </div>

                    <!-- Dropdown Résultat -->
                    <div class="relative ml-40">
                        <button id="dropdownResultatButton" class="text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center" type="button">
                            Gestion des utilisateurs
                            <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <!-- Dropdown menu Résultat -->
                        <div id="dropdownResultat" class="hidden absolute z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600 right-0">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownResultatButton">
                                <li>
                                    <a href="utilisateurs/ajout/ajout.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Ajouter</a>
                                </li>
                                <li>
                                    <a href="utilisateurs/suppression/suppression.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Supprimer</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </nav>
    <a href="../index.php">Retourner à l'accueil</a>
    <h2>Bienvenue sur votre tableau de bord</h2>
    <p>Vous pouvez maintenant accéder à toutes les fonctionnalités réservées à nos utilisateurs inscrits.</p>
    <script>
        // Fonction pour afficher ou cacher le menu déroulant Résultat
        document.getElementById('dropdownResultatButton').addEventListener('click', function() {
            const dropdownResultat = document.getElementById('dropdownResultat');
            dropdownResultat.classList.toggle('hidden');
        });

        // Fonction pour afficher ou cacher le menu déroulant Grand Prix
        document.getElementById('dropdownGrandPrixButton').addEventListener('click', function() {
            const dropdownGrandPrix = document.getElementById('dropdownGrandPrix');
            dropdownGrandPrix.classList.toggle('hidden');
        });

        // Fonction pour afficher ou cacher le menu déroulant Pilote
        document.getElementById('dropdownPiloteButton').addEventListener('click', function() {
            const dropdownPilote = document.getElementById('dropdownPilote');
            dropdownPilote.classList.toggle('hidden');
        });

        // Fonction pour afficher ou cacher le menu déroulant Ecurie
        document.getElementById('dropdownEcurieButton').addEventListener('click', function() {
            const dropdownEcurie = document.getElementById('dropdownEcurie');
            dropdownEcurie.classList.toggle('hidden');
        });
    </script>
    <a href="logout.php">Se déconnecter</a>
</div>


</body>
</html>

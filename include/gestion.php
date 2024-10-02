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

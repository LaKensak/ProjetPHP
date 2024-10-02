<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" integrity="sha512-Ez0cGzNzHR1tYAv56860NLspgUGuQw16GiOOp/I2LuTmpSK9xDXlgJz3XN4cnpXWDmkNBKXR/VDMTCnAaEooxA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
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

<div class="border p-3 mt-3 " style="width: 45rem; margin:auto">
    <div class="input-group col-md-6 mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="idGrandPrix">Sélectionnez le Grand Prix </label>
        </div>
        <select class="form-select" id="idGrandPrix">
        </select>
    </div>
    <div class="input-group col-md-6 mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="idPilote">Sélectionnez le pilote ayant obtenu le point bonus </label>
        </div>
        <select class="form-select" id="idPilote">
            <option value="0">Aucun</option>
        </select>
    </div>

    <label for="leClassement" class="col-form-label obligatoire">
        Saisir les numéros des pilotes dans l'ordre d'arrivée en les séparant par une virgule (sans autre caractère)
    </label>
    <input id="leClassement"
           type="text"
           class="form-control"
           required
           maxlength='60'
           pattern="^(?:([1-9]|[1-9][0-9]),){9,19}([1-9]|[1-9][0-9])$"
           autocomplete="off">
    <div class="mt-3 text-center">
        <button id='btnEnregistrer' class="bouton btn btn-sm btn-success">Enregistrer le classement</button>
    </div>
</div>
<script src="index.js"></script>
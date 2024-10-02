<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUBE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"/>
    <link rel="stylesheet" href="../assets/index-CTDMGqk2.css">
    <script src="../assets/index-Dr3JfuGf.js" defer></script>
    <script src="../assets/hls-sFmj3BG-.js" defer></script>
    <script src="https://unpkg.com/@ungap/custom-elements-builtin"></script>
    <script type="module" src="../x-frame-bypass.js"></script>
</head>
<body>
<header class="fixed top-0 left-0 right-0 z-50 bg-black/90" id="#home">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center py-5 mx-auto c-space">
            <a href="/" class="text-neutral-400 font-bold text-xl hover:text-white transition-colors">
                Cube
            </a>
            <button id="toggleButton" class="text-neutral-400 hover:text-white focus:outline-none sm:hidden flex"
                    aria-label="Toggle menu">
                <img src="../assets/menu.svg" alt="toggle" class="w-6 h-6" id="toggleIcon"/>
            </button>

            <nav class="sm:flex hidden" id="desktopNav">
            </nav>
        </div>
    </div>
    <div id="mobileNav" class="nav-sidebar max-h-0 bg-black text-white overflow-hidden transition-all duration-300">
        <nav class="p-5"></nav>
    </div>
</header>

<script>
    const userId = <?php echo json_encode(isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null); ?>; // User ID for JS
    const username = <?php echo json_encode(isset($_SESSION['username']) ? $_SESSION['username'] : null); ?>; // Username for JS

    const navLinks = [
        {id: 1, name: '', href: ''},
        {id: 2, name: 'Cubes', href: 'cube.php'},
        {id: 3, name: 'Chronomètre', href: 'chrono.php'},
        {id: 4, name: 'Solution', href: 'solution.php'},
        {id: 5, name: 'Présentation', href: 'presentation.php'},
        {
            id: 6,
            name: userId ? `Bienvenue, ${username}` : 'Connexion',
            href: userId ? '../admin/dashboard.php' : '../admin/login.php'
        }, // Update href if connected
    ];

    function createNavItems() {
        const ul = document.createElement('ul');
        ul.className = 'nav-ul flex flex-col space-y-2';

        navLinks.forEach(({id, name, href}) => {
            const li = document.createElement('li');
            li.className = 'nav-li';
            const a = document.createElement('a');
            a.href = href;
            a.className = 'nav-li_a text-neutral-400 hover:text-white transition-colors';
            a.textContent = name;
            li.appendChild(a);
            ul.appendChild(li);
        });

        return ul;
    }

    // NavBar
    const desktopNav = document.getElementById('desktopNav');
    const mobileNav = document.getElementById('mobileNav').querySelector('nav');

    desktopNav.appendChild(createNavItems());
    mobileNav.appendChild(createNavItems());

    const toggleButton = document.getElementById('toggleButton');
    const mobileNavSidebar = document.getElementById('mobileNav');
    const toggleIcon = document.getElementById('toggleIcon');
    let estOuvert = false;

    toggleButton.addEventListener('click', () => {
        estOuvert = !estOuvert;
        mobileNavSidebar.style.maxHeight = estOuvert ? '300px' : '0';
        toggleIcon.src = estOuvert ? 'assets/close.svg' : 'assets/menu.svg';
    });
</script>

</body>
</html>

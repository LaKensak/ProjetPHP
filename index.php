<?php
session_start();
include("admin/config.php"); // Assurez-vous que la connexion à la base de données est incluse

// Variable pour stocker le message de bienvenue
$userId = null;
$username = null;

if (isset($_SESSION['user_id'])) {
    // L'utilisateur est connecté via session
    $userId = $_SESSION['user_id'];
    $username = $_SESSION['username']; // Assurez-vous que le nom d'utilisateur est stocké dans la session
} elseif (isset($_COOKIE['auth_token'])) {
    // Vérifier le token comme expliqué précédemment
    $token = $_COOKIE['auth_token'];

    $sql = "SELECT * FROM users WHERE token = :token";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username']; // Stocker le nom d'utilisateur
        $userId = $user['id'];
        $username = $user['username'];
    } else {
        setcookie('auth_token', '', time() - 3600, "/", "", true, true);
    }
}

// Création du message de bienvenue
$welcomeMessage = $userId ? "Bienvenue, " . htmlspecialchars($username) : "Connexion";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUBE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"/>
    <link rel="stylesheet" href="/assets/index-CTDMGqk2.css">
    <script src="assets/index-Dr3JfuGf.js" defer></script>
    <script src="assets/hls-sFmj3BG-.js" defer></script>
    <script src="https://unpkg.com/@ungap/custom-elements-builtin"></script>
    <script type="module" src="x-frame-bypass.js"></script>
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
                <img src="assets/menu.svg" alt="toggle" class="w-6 h-6" id="toggleIcon"/>
            </button>

            <nav class="sm:flex hidden" id="desktopNav">
            </nav>
        </div>
    </div>
    <div id="mobileNav" class="nav-sidebar max-h-0 bg-black text-white overflow-hidden transition-all duration-300">
        <nav class="p-5"></nav>
    </div>
</header>

<section id="section">
    <iframe is="x-frame-bypass" src="https://www.worldcubeassociation.org/"
            style="width: 100%; height: 100vh; border: none;">
    </iframe>
</section>

<script>
    const userId = <?php echo json_encode($userId); ?>; // ID de l'utilisateur pour JS
    const username = <?php echo json_encode($username); ?>; // Nom de l'utilisateur pour JS

    const navLinks = [
        { id: 1, name: '', href: '' },
        { id: 2, name: 'Cubes', href: 'comps/cube.php' },
        { id: 3, name: 'Solution', href: 'solution' },
        { id: 4, name: 'Présentation', href: 'presentation' },
        { id: 5, name: userId ? `Bienvenue, ${username}` : 'Connexion', href: userId ? 'admin/dashboard.php' : 'admin/login.php' }, // Mettre à jour le href si connecté
    ];

    function createNavItems() {
        const ul = document.createElement('ul');
        ul.className = 'nav-ul flex flex-col space-y-2';

        navLinks.forEach(({ id, name, href }) => {
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

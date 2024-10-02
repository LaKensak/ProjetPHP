<?php
session_start();
include("../admin/config.php");


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

if (isset($_POST['chrono']) && isset($_POST['id'])) {
    $chrono = $_POST['chrono'];
    $userId = $_POST['id'];

    $sql = "UPDATE users SET chrono = :chrono WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['chrono' => $chrono, 'id' => $userId]); // Passer l'ID utilisateur ici

    echo json_encode(['success' => true, 'user_id' => $userId]);
    exit;
}

include "cube&chrono.php";

$cubeId = $_GET['id'] ?? null; // Récupère l'ID du cube
if ($cubeId === null) {
    echo "Cube ID manquant !";
    exit;
}
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
</head>
<body>
<header class="fixed top-0 left-0 right-0 z-50 bg-black/90">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center py-5 mx-auto c-space">
            <a href="/" class="text-neutral-400 font-bold text-xl hover:text-white transition-colors">Chronomètre</a>
            <button id="toggleButton" class="text-neutral-400 hover:text-white focus:outline-none sm:hidden flex"
                    aria-label="Toggle menu">
                <img src="../assets/menu.svg" alt="toggle" class="w-6 h-6" id="toggleIcon"/>
            </button>
            <nav class="sm:flex hidden" id="desktopNav"></nav>
        </div>
    </div>
    <div id="mobileNav" class="nav-sidebar max-h-0 bg-black text-white overflow-hidden transition-all duration-300">
        <nav class="p-5"></nav>
    </div>
</header>

<section class="flex justify-center items-center h-screen">
    <div class="container flex flex-col justify-center items-center">
        <h2 class="text-white text-3xl font-bold">Chronomètre</h2>
        <br>
        <div id="chrono" style="color: white; display: inline-block; font-size: 20px;">00:00:00.000</div>
        <div style="display: inline-block; color: white; padding: 10px">
            <button onclick="SetStart()"> ▶</button>
            <button onclick="SetStop()"> ❚❚</button>
            <button onclick="Reset()"> ⟳</button>
            <button onclick="getChronoInfo()" class="text-white font-bold py-1 px-2 rounded">Envoyer</button>
        </div>
    </div>
    <script type="text/javascript">
        let seconds = 0;
        let milliseconds = 0;
        let timer = null;
        const para = document.getElementById("chrono");

        function updateChrono() {
            milliseconds += 10;

            if (milliseconds >= 1000) {
                milliseconds = 0;
                seconds++;
            }

            const min = Math.floor(seconds / 60);
            const sec = seconds % 60;

            para.innerHTML =
                "00:" +
                (min < 10 ? "0" + min : min) + ":" +
                (sec < 10 ? "0" + sec : sec) + "." +
                (milliseconds < 100 ? (milliseconds < 10 ? "00" + milliseconds : "0" + milliseconds) : milliseconds);
        }

        const SetStart = () => {
            if (!timer) {
                timer = setInterval(updateChrono, 10);
            }
        }

        const SetStop = () => {
            clearInterval(timer);
            timer = null;
        }

        const Reset = () => {
            clearInterval(timer);
            timer = null;
            seconds = 0;
            milliseconds = 0;
            para.innerHTML = "00:00:00.000";
        }


        function getCubeId() {
            const urlParams = new URLSearchParams(window.location.search);
            const cubeId = urlParams.get('id');
            return cubeId;
        }

        const getChronoInfo = () => {
            const min = Math.floor(seconds / 60);
            const sec = seconds % 60;

            const info = {
                minutes: min,
                seconds: sec,
                milliseconds: milliseconds,
                format: `00:${min < 10 ? "0" + min : min}:${sec < 10 ? "0" + sec : sec}.${milliseconds < 100 ? (milliseconds < 10 ? "00" + milliseconds : "0" + milliseconds) : milliseconds}`
            };

            console.log(info); // Affiche les informations dans la console

            const chronoData = info.format;
            const cubeId = getCubeId(); // Utilise la fonction pour obtenir l'ID du cube
            const userId = <?php echo json_encode(isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null); ?>;

            if (userId && cubeId) {
                fetch('chrono.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        'chrono': chronoData, // Envoyer les informations du chrono
                        'cubeId': cubeId, // Envoyer l'ID du cube
                        'userId': userId // Envoyer l'ID de l'utilisateur
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Le chrono a été enregistré avec succès pour l\'utilisateur et le cube: ' + getCubeId());
                            console.log('Le chrono a été enregistré avec succès pour l\'utilisateur ID:', <?php echo json_encode(isset($_SESSION['username']) ? $_SESSION['username'] : null); ?>, 'et nom du cube:', getCubeId());
                        } else {
                            console.error('Erreur de mise à jour du chrono pour l\'utilisateur ID:', <?php echo json_encode(isset($_SESSION['username']) ? $_SESSION['username'] : null); ?>);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de l\'enregistrement des données:', error);
                    });
            } else {
                alert('Vous n\'êtes pas connecté ou l\'ID du cube est manquant.');
                console.error('L\'utilisateur n\'est pas connecté ou l\'ID du cube est manquant.');
            }

            return info;
        }
    </script>
</section>
<script>
    const userId = <?php echo json_encode(isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null); ?>; // User ID for JS
    const username = <?php echo json_encode(isset($_SESSION['username']) ? $_SESSION['username'] : null); ?>; // Username for JS

    const navLinks = [
        {id: 1, name: '', href: ''},
        {id: 2, name: 'Cubes', href: 'cube.php'},
        {id: 3, name: 'Chronomètre', href: 'chrono.php'},
        {id: 4, name: 'Solution', href: 'solution'},
        {id: 5, name: 'Présentation', href: 'presentation'},
        {
            id: 6,
            name: userId ? `Bienvenue, ${username}` : 'Connexion',
            href: userId ? '../admin/dashboard.php' : '../admin/login.php'
        },
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

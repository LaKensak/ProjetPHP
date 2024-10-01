const navLinks = [
    { id: 1, name: '', href: '' },
    { id: 2, name: 'Cubes', href: 'comps/cube.php' },
    { id: 3, name: 'Chronomètre', href: 'comps/chrono.php' },
    { id: 4, name: 'Solution', href: 'solution' },
    { id: 5, name: 'Présentation', href: 'presentation' },
    { id: 6, name: 'Connexion', href: 'admin/login.php' },
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

// Récupération d'une liste de compétitions via l'API de la WCA
const section = document.getElementById('section');

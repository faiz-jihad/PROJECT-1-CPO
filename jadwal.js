document.addEventListener('DOMContentLoaded', function () {
    // Sidebar
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const closeButton = document.getElementById('close-button');
    const menuButton = document.getElementById('menu-button');

    function showSidebar() {
        if (sidebar && overlay) {
            sidebar.classList.add('open');
            overlay.classList.add('show');
        }
    }

    function closeSidebar() {
        if (sidebar && overlay) {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        }
    }

    if (menuButton) {
        menuButton.addEventListener('click', showSidebar);
    }
    if (closeButton) {
        closeButton.addEventListener('click', closeSidebar);
    }
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    // Sub Menu
    const subMenu = document.getElementById("subMenu");
    const profilePic = document.querySelector(".profile-pic");

    function toggleMenu() {
        if (subMenu) {
            subMenu.classList.toggle("open-menu");
        }
    }

    if (profilePic) {
        profilePic.addEventListener("click", function (event) {
            event.stopPropagation(); // Mencegah event dari bubble ke document
            toggleMenu();
        });
    }

    // Klik di luar menu akan menutupnya
    document.addEventListener("click", function (event) {
        if (subMenu && !subMenu.contains(event.target) && !profilePic.contains(event.target)) {
            subMenu.classList.remove("open-menu");
        }
    });
});

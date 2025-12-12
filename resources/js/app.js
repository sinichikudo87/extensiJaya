import './bootstrap';
import Alpine from 'alpinejs'
import '@fortawesome/fontawesome-free/css/all.min.css';

window.Alpine = Alpine
Alpine.start()

document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleSidebar");
    const closeBtn = document.getElementById("closeSidebar");

    if (!sidebar) return;

    // Toggle sidebar
    if (toggleBtn) {
        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("-translate-x-full");
        });
    }

    // Close button
    if (closeBtn) {
        closeBtn.addEventListener("click", () => {
            sidebar.classList.add("-translate-x-full");
        });
    }

    // Klik di luar sidebar untuk menutup
    document.addEventListener("click", (e) => {
        if (
            !sidebar.contains(e.target) &&
            !toggleBtn.contains(e.target)
        ) {
            sidebar.classList.add("-translate-x-full");
        }
    });
});

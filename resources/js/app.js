import "./bootstrap";

import Alpine from "alpinejs";
import axios from "axios";
import Swal from "sweetalert2";

import "@fortawesome/fontawesome-free/css/all.min.css";
import "flatpickr/dist/flatpickr.min.css";

import Chart from "chart.js/auto";

// =========================
// GLOBAL LIBRARY
// =========================
window.Alpine = Alpine;
window.axios = axios;
window.Swal = Swal;
window.Chart = Chart;

// =========================
// HELPER GLOBAL
// =========================
window.formatJuta = function (value) {
    if (value === null || value === undefined || value === '') return 'Rp 0';

    const number =
        typeof value === 'string'
            ? parseFloat(value.replace(/[^\d.-]/g, '')) || 0
            : value;

    return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.floor(number));
};

// Alpine helper & Notifications
document.addEventListener('alpine:init', () => {
    Alpine.store('nav', {
        activeTitle: 'Dashboard',
        setTitle(title) {
            this.activeTitle = title;
        }
    });
    
    Alpine.data('helpers', () => ({
        formatJuta: window.formatJuta
    }));

    Alpine.data('notificationComponent', () => ({
        notifOpen: false,
        notifications: [],
        unread: 0,
        notificationSound: new Audio('/sounds/notification.mp3'),

        init() {
            fetch('/notifications')
                .then(res => res.json())
                .then(data => {
                    this.notifications = data;
                    this.unread = data.filter(n => n.is_read == 0).length;
                });

            window.Echo.channel('notifications')
                .listen('.new.notification', (e) => {
                    this.notifications.unshift({
                        id: Date.now() + Math.random(),
                        title: e.title,
                        message: e.message,
                        cost: e.cost,
                        notif_time: e.time,
                        is_read: 0
                    });

                    this.unread++;

                    this.notificationSound.play().catch(err => {
                        console.log("Autoplay dicegah browser, butuh klik user sekali.");
                    });

                    if (this.notifications.length > 10) {
                        this.notifications.pop();
                    }
                    tampilkanNotifikasiKeUI(e); // Perbaikan: kirim data event tunggal ke UI jika diperlukan
                });
        },

        toggleNotif() {
            this.notifOpen = !this.notifOpen;
            if (this.notificationSound.paused) {
                this.notificationSound.play().then(() => {
                    this.notificationSound.pause();
                    this.notificationSound.currentTime = 0;
                }).catch(e => console.log("Audio unlock failed:", e));
            }

            if (this.notifOpen) {
                this.unread = 0;
            }
        }
    }));
});

// =========================
// START ALPINE
// =========================
Alpine.start();

// =========================
// MODULE IMPORTS
// =========================
import "./dashboards/index.js";
import "./admin/company/index.js";
import "./admin/employee/index.js";
import "./admin/financial/index.js";
import "./admin/chart-color-settings/index.js";
import "./admin/project-entry/index.js";
import "./admin/project-update/index.js";
import "./admin/project-category/index.js";
import "./admin/report/monitoring.js";
import { sendChat } from "./ChatLive/index.js";

window.sendChat = sendChat;

// =========================
// SIDEBAR CONTROL
// =========================
document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleSidebar");
    const mobileToggle = document.getElementById("mobileToggleSidebar");
    const submenuToggles = document.querySelectorAll(".submenu-toggle");

    // Desktop toggle
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("collapsed");

            if (sidebar.classList.contains("collapsed")) {
                document
                    .querySelectorAll(".submenu")
                    .forEach((sm) => sm.classList.remove("active"));
            }
        });
    }

    // Mobile toggle
    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener("click", () => {
            sidebar.classList.toggle("absolute");
            sidebar.classList.toggle("z-50");
            sidebar.classList.toggle("-translate-x-full");
        });
    }

    // Submenu toggle
    submenuToggles.forEach((btn) => {
        btn.addEventListener("click", () => {
            const wrapper = btn.closest(".submenu-wrapper");
            const submenu = wrapper?.querySelector(".submenu");

            if (submenu) {
                submenu.classList.toggle("active");
            }
        });
    });
});

// =========================
// MODAL HELPER
// =========================
function toggleModal(id, show) {
    const el = document.getElementById(id);
    if (!el) return;

    el.classList.toggle("hidden", !show);
    el.classList.toggle("flex", show);
}

// =========================
// PWA LOGIC
// =========================
let deferredPrompt = null;

const btn = document.getElementById('installBtn');
const text = document.getElementById('installText');
const icon = document.getElementById('installIcon');

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js');
}

// ===== PWA: INIT =====
document.addEventListener('DOMContentLoaded', () => {
    if (!btn) return;

    const isIOS = () => {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    };
    
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches
        || window.navigator.standalone === true;

    // 1. Jika aplikasi sudah terinstal / dibuka dalam mode aplikasi
    if (isStandalone) {
        setInstalledState();
        return;
    }

    // 2. Jika perangkat adalah iOS (Safari)
    if (isIOS()) {
        if (text) text.textContent = "Instal Aplikasi";
        if (icon) {
            icon.classList.remove('fa-android');
            icon.classList.add('fa-share-square'); 
        }
        
        showButton();

        // override aksi klik khusus iOS untuk memandu pengguna secara manual
        btn.style.cursor = 'pointer';
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            Swal.fire({
                title: 'Cara Instal di iOS',
                html: '1. Ketuk tombol <b>Share</b> (ikon kotak dengan panah ke atas) di bagian bawah Safari.<br>' +
                     '2. Gulir ke bawah dan pilih menu <b>Add to Home Screen</b> (Tambahkan ke Layar Utama).',
                icon: 'info',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#3085d6'
            });
        });
    }
});

// ===== PWA EVENT: READY TO INSTALL (Hanya terpicu di Android/Chromium) =====
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    showButton();
});

// ===== PWA EVENT: CLICK INSTALL (Hanya berjalan di Android/Chromium) =====
btn?.addEventListener('click', async () => {
    // Abaikan jika ini perangkat iOS (sudah dihandle oleh SweetAlert di atas)
    const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    if (isIOS) return;

    if (!deferredPrompt) return;

    setInstallingState();

    try {
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;

        if (outcome === 'accepted') {
            setInstalledState();
        } else {
            resetState();
        }
    } catch (err) {
        console.error('Install error:', err);
        resetState();
    }

    deferredPrompt = null;
});

// ===== PWA EVENT: SUCCESS INSTALLED =====
window.addEventListener('appinstalled', () => {
    setInstalledState();
});

// ===== PWA: STATE HANDLERS =====
function showButton() {
    if (!btn) return;
    btn.classList.remove('hidden');
    btn.classList.add('flex');
    btn.dataset.state = 'ready';
}

function setInstallingState() {
    if (!btn) return;
    btn.dataset.state = 'installing';
    if (text) text.textContent = 'Installing...';
    if (icon) icon.classList.add('animate-spin');
}

function setInstalledState() {
    if (!btn) return;
    btn.dataset.state = 'installed';

    if (text) text.textContent = 'Installed';
    if (icon) {
        icon.classList.remove('fa-android', 'fa-share-square', 'animate-spin');
        icon.classList.add('fa-check', 'text-cyan-400');
    }

    btn.disabled = true;
    btn.classList.add('opacity-60', 'cursor-not-allowed');
    setTimeout(() => {
        btn.style.setProperty('display', 'none', 'important');
    }, 2500);
}

function resetState() {
    if (!btn) return;
    btn.dataset.state = 'ready';
    if (text) text.textContent = 'Install';
    if (icon) icon.classList.remove('animate-spin');
}

function tampilkanNotifikasiKeUI(data) { 
    alert(`Notif: ${data.title}\n${data.message}\nBiaya: ${data.cost}`);
}
/**
 * sidebar.js
 * ──────────
 * Sidebar dropdown — Vanilla JS (no jQuery).
 * Dipanggil via onclick="toggleSidebarGroup(this)" pada tombol trigger.
 *
 * CATATAN: File ini di-bundle oleh Vite sebagai ES Module (package.json: "type":"module").
 * Fungsi dalam ES Module bersifat privat terhadap modul — TIDAK otomatis tersedia di window.
 * Karena onclick="..." pada HTML mencari nama fungsi di window (global scope),
 * fungsi ini harus di-assign secara eksplisit ke window agar dapat dipanggil.
 */

/**
 * Toggle expand/collapse grup sidebar.
 * Dipanggil via onclick pada tombol trigger.
 *
 * @param {HTMLElement} trigger - Elemen <button> yang diklik
 */
function toggleSidebarGroup(trigger) {
    const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
    const menuId     = trigger.getAttribute('aria-controls');
    const menu       = document.getElementById(menuId);

    if (!menu) return;

    if (isExpanded) {
        // ── Tutup ──
        trigger.setAttribute('aria-expanded', 'false');
        trigger.classList.remove('group-active');
        menu.classList.remove('submenu-open');
    } else {
        // ── Buka ──
        trigger.setAttribute('aria-expanded', 'true');
        trigger.classList.add('group-active');
        menu.classList.add('submenu-open');
    }
}

// Expose ke global scope agar onclick="toggleSidebarGroup(this)" di HTML dapat menemukannya.
// Diperlukan karena Vite mem-bundle file ini sebagai ES Module (scope terisolasi).
window.toggleSidebarGroup = toggleSidebarGroup;


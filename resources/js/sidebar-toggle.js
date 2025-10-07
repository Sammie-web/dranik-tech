// Custom JS for toggling sidebar on mobile
window.toggleSidebar = function() {
    const sidebar = document.getElementById('mobile-sidebar');
    if (sidebar) {
        sidebar.classList.toggle('hidden');
    }
};

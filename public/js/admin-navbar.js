document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("adminSidebar");
    const toggleBtn = document.getElementById("sidebarToggle");

    toggleBtn.addEventListener("click", () => {
        sidebar.classList.toggle("collapsed");
    });
});

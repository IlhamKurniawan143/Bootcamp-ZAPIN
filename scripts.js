// scripts.js

// Sidebar toggle function
function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var wrapper = document.querySelector('.wrapper');
    
    // Toggle the 'open' class on the sidebar
    sidebar.classList.toggle('open');
    
    // Toggle the 'shifted' class on the wrapper to move the content
    wrapper.classList.toggle('shifted');
    
    // Save the sidebar state in localStorage
    var isOpen = sidebar.classList.contains('open');
    localStorage.setItem('sidebarOpen', isOpen);
    localStorage.removeItem('sidebarOpen');
}

// Function to check the sidebar state on page load
function checkSidebarState() {
    var sidebar = document.getElementById('sidebar');
    var wrapper = document.querySelector('.wrapper');
    
    // Retrieve the sidebar state from localStorage
    var sidebarOpen = localStorage.getItem('sidebarOpen') === 'true';
    
    if (sidebarOpen) {
        sidebar.classList.add('open');
        wrapper.classList.add('shifted');
    }
}

// Check the sidebar state when the page loads
document.addEventListener('DOMContentLoaded', checkSidebarState);

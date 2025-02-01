document.addEventListener('DOMContentLoaded', function () {
    // Profile menu toggle
    const profileIcon = document.querySelector('.profile-icon');
    const profileMenu = document.querySelector('.profile-menu');

    profileIcon.addEventListener('click', function (e) {
        e.stopPropagation();
        profileMenu.classList.toggle('active');
    });

    // Close profile menu when clicking outside
    document.addEventListener('click', function (e) {
        if (!profileMenu.contains(e.target) && !profileIcon.contains(e.target)) {
            profileMenu.classList.remove('active');
        }
    });

    // Mobile navigation menu toggle (if needed)
    const navMenu = document.querySelector('.navbar');
    const navToggle = document.querySelector('.navigation-menu');

    if (navToggle) {
        navToggle.addEventListener('click', function () {
            navMenu.classList.toggle('active');
        });
    }
}); 
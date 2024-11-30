
window.onload = function() {
    const user = JSON.parse(window.localStorage.getItem('user'));

    if (user) {
        // Display welcome message
        const welcomeMessage = document.getElementById('welcomeMessage');
        welcomeMessage.textContent = `Welcome, ${user.name || user.identifier}!`;  

        // Display user email
        const userEmail = document.getElementById('userEmail');
        userEmail.textContent = `Email: ${user.email || 'N/A'}`;
    } else {
        // If no user data in localStorage, redirect to login page
        window.location.href = '/LogIn/login.html';
    }
};
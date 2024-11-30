function validateLoginForm(event) {
    event.preventDefault();

    const identifier = document.getElementById('identifier').value.trim();
    const password = document.getElementById('password').value.trim();

    const identifierError = document.getElementById('identifierError');
    const passwordError = document.getElementById('passwordError');

    identifierError.textContent = '';
    passwordError.textContent = '';

    if (identifier === '') {
        identifierError.textContent = 'Username or Email is required';
        return;
    } else if (!isValidEmail(identifier) && !isValidUsername(identifier)) {
        identifierError.textContent = 'Invalid Username or Email';
        return;
    }

    if (password === '') {
        passwordError.textContent = 'Password is required';
        return;
    }

}

function isValidEmail(input) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(input);
}


function isValidUsername(input) {
    const usernameRegex = /^[a-zA-Z0-9_.-]+$/; 
    return usernameRegex.test(input);
}


// Function to initialize Google Sign-In
function onLoad() {
    google.accounts.id.initialize({
        client_id: '1083477586589-dkq0g5879mouf85r7senerroiobi33d1.apps.googleusercontent.com',  // Use your actual Google OAuth2 client ID here
        callback: handleCredentialResponse
    });

    // Render the Google Sign-In button
    google.accounts.id.renderButton(
        document.getElementById("googleSignInDiv"),
        {
            theme: "outline", // Choose button style
            size: "large", // Choose button size
            text: "Sign in with Google" // Custom button text
        }
    );
}

// This is the callback function that will handle the response after successful login
function handleCredentialResponse(response) {
    const responsePayload = decodeJwtResponse(response.credential);
    console.log("ID Token: ", responsePayload);
    
    const userName = responsePayload.name;
    const userEmail = responsePayload.email;
    
    // Store the user information in localStorage or wherever necessary
    window.localStorage.setItem('user', JSON.stringify({
        name: userName,
        email: userEmail
    }));

    // Redirect to the profile page after successful login
    window.location.href = getRedirectURL();
}

// Decode JWT (JSON Web Token) response
function decodeJwtResponse(token) {
    const base64Url = token.split('.')[1];
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
    return JSON.parse(jsonPayload);
}

// Helper function to get the redirect URL based on the environment
function getRedirectURL() {
    if (window.location.hostname === '127.0.0.1') {
        // Local development
        return 'http://127.0.0.1:5500/HomePage/Profile/profile.html';
    } else {
        // Production (GitHub Pages)
        return 'https://erinnasalihu.github.io/WebEngineeringProject/HomePage/Profile/profile.html';
    }
}

// Initialize on page load
window.onload = function() {
    onLoad();
};


document.getElementById('loginForm').addEventListener('submit', validateLoginForm);

const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');
const passwordEyeIcon = document.getElementById('passwordEyeIcon');


togglePassword.addEventListener('click', function () {
     const currentType = passwordInput.getAttribute('type');
    
    const newType = currentType === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', newType);

    
    passwordEyeIcon.classList.toggle('fa-eye');
    passwordEyeIcon.classList.toggle('fa-eye-slash');
});



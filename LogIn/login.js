

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



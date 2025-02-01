document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const passwordEyeIcon = document.getElementById('passwordEyeIcon');

    // Toggle password visibility
    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle eye icon
        passwordEyeIcon.className = type === 'password' ? 'fa fa-eye' : 'fa fa-eye-slash';
    });

    // Handle form submission
    loginForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const email = document.getElementById('identifier').value;
        const password = document.getElementById('password').value;

        try {
            const response = await fetch('LogIn/login_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            });

            const data = await response.json();

            if (data.success) {
                // Show success message
                alert(data.message);
                // Redirect to home page
                window.location.href = data.redirect;
            } else {
                // Show error message
                alert(data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    });
});

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



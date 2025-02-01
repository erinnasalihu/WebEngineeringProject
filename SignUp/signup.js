document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('signupForm');
    const errorElements = {
        username: document.getElementById('usernameError'),
        email: document.getElementById('emailError'),
        password: document.getElementById('passwordError'),
        confirmPassword: document.getElementById('confirmPasswordError')
    };

    // Clear error messages
    function clearErrors() {
        Object.values(errorElements).forEach(element => {
            element.textContent = '';
        });
    }

    // Validate form
    function validateForm(formData) {
        clearErrors();
        let isValid = true;

        // Username validation
        if (!formData.username) {
            errorElements.username.textContent = 'Emri i përdoruesit është i detyrueshëm';
            isValid = false;
        }

        // Email validation
        if (!formData.email) {
            errorElements.email.textContent = 'Emaili është i detyrueshëm';
            isValid = false;
        } else if (!isValidEmail(formData.email)) {
            errorElements.email.textContent = 'Formati i emailit është i pavlefshëm';
            isValid = false;
        }

        // Password validation
        if (!formData.password) {
            errorElements.password.textContent = 'Fjalëkalimi është i detyrueshëm';
            isValid = false;
        } else if (formData.password.length < 8) {
            errorElements.password.textContent = 'Fjalëkalimi duhet të ketë të paktën 8 karaktere';
            isValid = false;
        }

        // Confirm password validation
        if (!formData.confirmPassword) {
            errorElements.confirmPassword.textContent = 'Konfirmimi i fjalëkalimit është i detyrueshëm';
            isValid = false;
        } else if (formData.confirmPassword !== formData.password) {
            errorElements.confirmPassword.textContent = 'Fjalëkalimet nuk përputhen';
            isValid = false;
        }

        return isValid;
    }

    // Handle form submission
    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const formData = {
            username: form.querySelector('#username').value.trim(),
            email: form.querySelector('#email').value.trim(),
            password: form.querySelector('#password').value.trim(),
            confirmPassword: form.querySelector('#confirm-password').value.trim()
        };

        if (validateForm(formData)) {
            try {
                const response = await fetch('signup_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        username: formData.username,
                        email: formData.email,
                        password: formData.password
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Properly decode the message
                    const decodedMessage = decodeURIComponent(JSON.parse(JSON.stringify(data.message)));
                    alert(decodedMessage);
                    window.location.href = '/LogIn/index.php';
                } else {
                    const decodedMessage = decodeURIComponent(JSON.parse(JSON.stringify(data.message)));
                    alert(decodedMessage);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ndodhi një gabim gjatë regjistrimit');
            }
        }
    });

    // Email validation helper
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Password visibility toggle
    function setupPasswordToggle(inputId, toggleId, eyeIconId) {
        const input = document.getElementById(inputId);
        const toggle = document.getElementById(toggleId);
        const eyeIcon = document.getElementById(eyeIconId);

        if (toggle && input && eyeIcon) {
            toggle.addEventListener('click', function () {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                eyeIcon.classList.toggle('fa-eye');
                eyeIcon.classList.toggle('fa-eye-slash');
            });
        }
    }

    // Setup password toggles
    setupPasswordToggle('password', 'togglePassword', 'passwordEyeIcon');
    setupPasswordToggle('confirm-password', 'toggleConfirmPassword', 'confirmPasswordEyeIcon');
});
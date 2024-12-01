import { initializeApp } from "firebase/app";
import { getAuth, GoogleAuthProvider, signInWithPopup } from "firebase/auth";


const firebaseConfig = {
  apiKey: "AIzaSyDxlSpdPDZAT1r9dPgz_LvBftwRMam7i7Q",
  authDomain: "the-olive-kitchen.firebaseapp.com",
  projectId: "the-olive-kitchen",
  storageBucket: "the-olive-kitchen.firebasestorage.app",
  messagingSenderId: "979701422960",
  appId: "1:979701422960:web:2fc744b671a2afe66e0db5"
};


const app = initializeApp(firebaseConfig);


const auth = getAuth(app);
const provider = new GoogleAuthProvider();

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




document.getElementById('googleSignInBtn').addEventListener('click', () => {
    signInWithPopup(auth, provider)
      .then((result) => {
       
        const user = result.user;
        console.log('User Info:', {
          name: user.displayName,
          email: user.email
        });
  
        
        localStorage.setItem('user', JSON.stringify({
          name: user.displayName,
          email: user.email
        }));
  
      
        window.location.href = '../Profile/profile.html'; 
      })
      .catch((error) => {
        console.error('Error during sign-in:', error.message);
      });
  });

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



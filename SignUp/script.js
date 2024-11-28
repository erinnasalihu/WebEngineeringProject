function validateSignupForm(event){
    event.preventDefault();
    const username=document.getElementById('username').value.trim();
    const email=document.getElementById('email').value.trim();
    const password=document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirmPassword').value.trim();
    
    const usernameError=document.getElementById('usernameError');
    const emailError=document.getElementById('emailError');
    const passwordError=document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');
   
    usernameError.textContent='';
    emailError.textContent='';
    passwordError.textContent='';
    confirmPasswordError.textContent = '';



    if(username===''){
        usernameError.textContent='Username is Required';
        return;
    }

    if(email===''){
        emailError.textContent='Email is Required';
        return;
    }else if(!isValidEmail(email)){
        emailError.textContent='Invalid email format';
        return;
    }

    if(password===''){
        passwordError.textContent='Password is Required';
        return;
    }else if( password.length<8 ){
        passwordError.textContent='Password must be 8 characters'
        return;
    }
    if (confirmPassword === '') {
        confirmPasswordError.textContent = 'Confirm Password is Required';
        return;
    } else if (confirmPassword !== password) {
        confirmPasswordError.textContent = 'Passwords do not match';
        return;
    }

}

function isValidEmail(email){
    const emailRegex=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
    
   
}
document.getElementById('signupForm').addEventListener('submit', validateSignupForm);
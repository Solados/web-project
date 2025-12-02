const container = document.querySelector('.container');
const registerBtn = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.login-btn');

registerBtn.addEventListener('click', () => {
    container.classList.add('active');
})

loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
})

// Handle login form submission via AJAX
function handleLoginSubmit(event) {
    event.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const errorDiv = document.getElementById('loginError');
    
    // Clear previous error
    errorDiv.style.display = 'none';
    errorDiv.textContent = '';
    
    // Send login request via AJAX
    fetch('login_check.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect on successful login
            window.location.href = '/index.html';
        } else {
            // Display error message under login button
            errorDiv.textContent = data.message;
            errorDiv.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Login error:', error);
        errorDiv.textContent = 'An error occurred. Please try again.';
        errorDiv.style.display = 'block';
    });
    
    return false;
}
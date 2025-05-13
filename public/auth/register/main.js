const container = document.querySelector('.container');
const registerBtn = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.login-btn');

// Set initial state to show the register form
container.classList.add('active');

loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
});

registerBtn.addEventListener('click', () => {
    container.classList.add('active');
});


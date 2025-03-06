const themeSwitch = document.getElementById('theme-switch');
const body = document.body;

themeSwitch.addEventListener('click', () => {
  body.classList.toggle('darkmode');
});

function logout() {
    window.location.href = "login.html";  // Adjust the path to your actual login page
}

localStorage.setItem('ordinances', JSON.stringify(ordinances));

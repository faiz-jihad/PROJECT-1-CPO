// see password
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const icon = this;
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bxs-lock-alt');
        icon.classList.add('bxs-lock-open-alt');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('bxs-lock-open-alt');
        icon.classList.add('bxs-lock-alt');
    }
});

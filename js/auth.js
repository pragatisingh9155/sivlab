document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ username, password })
                });

                const result = await response.json();
                if (result.success) {
                    localStorage.setItem('user', JSON.stringify(result.user));
                    window.location.href = 'dashboard.html';
                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error('Login error:', error);
                alert('Login failed. Please try again.');
            }
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('new-username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }

            try {
                const response = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ username, email, password })
                });

                const result = await response.json();
                if (result.success) {
                    localStorage.setItem('user', JSON.stringify(result.user));
                    window.location.href = 'dashboard.html';
                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error('Registration error:', error);
                alert('Registration failed. Please try again.');
            }
        });
    }

    // Progress Tracking
    function updateProgressDisplay() {
        const user = JSON.parse(localStorage.getItem('user'));
        if (user && user.progress) {
            const progressElements = document.querySelectorAll('.lab-progress');
            progressElements.forEach(el => {
                const labId = el.dataset.labId;
                const progress = user.progress[labId] || 0;
                el.style.width = `${progress}%`;
                el.textContent = `${progress}% Complete`;
            });
        }
    }

    // Call this function when dashboard loads
    updateProgressDisplay();
});

function trackLabProgress(labId, progress) {
    const user = JSON.parse(localStorage.getItem('user'));
    if (user) {
        if (!user.progress) user.progress = {};
        user.progress[labId] = progress;
        localStorage.setItem('user', JSON.stringify(user));
    }
}
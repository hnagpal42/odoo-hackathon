// Add this to handle form submission
document.querySelector('form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const response = await fetch('api/auth.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        })
    });

    const data = await response.json();
    if (data.success) {
        window.location.href = 'dashboard.php';
    } else {
        alert(data.error || 'Login failed');
    }
});
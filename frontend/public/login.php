<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Skill Swap Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card bg-secondary shadow">
                <div class="card-body p-4">
                    <h3 class="card-title text-center mb-4">Login</h3>
                    <form method="POST" action="login_process.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                        <div class="text-end mt-2">
                            <a href="#" class="text-light text-decoration-underline small">Forgot username/password?</a>
                        </div>
                    </form>
                </div>
            </div>
            <p class="text-center text-muted mt-3 small">Don’t have an account? <a href="register.php" class="text-info">Register here</a></p>
        </div>
    </div>
</div>

<script>
    document.querySelector('form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const response = await fetch('api/auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.href = 'dashboard.php';
        } else {
            alert(result.message || 'Login failed');
        }
    });
</script>

</body>
</html>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

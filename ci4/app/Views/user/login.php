<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Portal Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100vh; display: flex; align-items: center; }
        .login-card { width: 100%; max-width: 400px; padding: 2rem; border-radius: 20px; border: none; background: #fff; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .form-control { border-radius: 10px; padding: 12px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="login-card">
            <h3 class="text-center fw-bold mb-4">Sign In</h3>
            
            <?php if(session()->getFlashdata('flash_msg')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('flash_msg'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="mb-3">
                    <label class="form-label small text-muted fw-bold">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@email.com" required>
                </div>
                <div class="mb-4">
                    <label class="form-label small text-muted fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">Login to Account</button>
            </form>
            <p class="text-center mt-4 text-muted small">© 2026 - Universitas Pelita Bangsa</p>
        </div>
    </div>
</body>
</html>
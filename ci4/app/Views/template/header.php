<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .navbar { box-shadow: 0 2px 4px rgba(0,0,0,.08); }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,.05); }
        .btn-primary { border-radius: 8px; padding: 8px 20px; font-weight: 500; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Admin Portal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('/'); ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="<?= base_url('/admin/artikel'); ?>">Dashboard</a></li>
                </ul>
                <div class="d-flex">
                    <a href="<?= base_url('/user/logout'); ?>" class="btn btn-light btn-sm fw-bold px-3">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container pb-5">
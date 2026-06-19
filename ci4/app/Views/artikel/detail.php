<?= $this->include('template/header'); ?>

<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h1 class="fw-bold mb-2"><?= $artikel['judul']; ?></h1>
        
        <div class="mb-4">
            <span class="badge bg-primary fs-6"><?= $artikel['nama_kategori']; ?></span>
        </div>

        <?php if(!empty($artikel['gambar'])): ?>
            <img src="<?= base_url('/gambar/' . $artikel['gambar']);?>" alt="<?= $artikel['judul']; ?>" class="img-fluid rounded mb-4" style="max-height: 400px; object-fit: cover;">
        <?php endif; ?>
        
        <div class="fs-5" style="line-height: 1.8;">
            <?= $artikel['isi']; ?>
        </div>
        
        <div class="mt-5">
            <a href="<?= base_url('/admin/artikel'); ?>" class="btn btn-outline-secondary">&larr; Kembali ke Daftar Artikel</a>
        </div>
    </div>
</div>

<?= $this->include('template/footer'); ?>
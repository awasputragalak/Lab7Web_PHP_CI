<?= $this->include('template/header'); ?>

<div class="row">
    <div class="col-md-8">
        <h2 class="fw-bold mb-4"><?= $title; ?></h2>
        <?php if($artikel): foreach($artikel as $row): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="fw-bold h4"><a href="<?= base_url('/artikel/' . $row['slug']);?>" class="text-decoration-none text-dark"><?= $row['judul']; ?></a></h3>
                    <div class="mb-2">
                        <a href="<?= base_url('/artikel/kategori/' . (url_title($row['nama_kategori'], '-', true))); ?>" class="badge bg-primary text-decoration-none">
                            <?= $row['nama_kategori']; ?>
                        </a>
                    </div>
                    <p class="text-muted"><?= substr($row['isi'], 0, 150); ?>...</p>
                    <a href="<?= base_url('/artikel/' . $row['slug']);?>" class="btn btn-sm btn-outline-primary">Baca Selengkapnya &rarr;</a>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div class="alert alert-info">Belum ada artikel.</div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">Daftar Kategori</div>
            <ul class="list-group list-group-flush">
                <?php foreach($kategori as $k): ?>
                    <li class="list-group-item">
                        <a href="<?= base_url('/artikel/kategori/' . $k['slug_kategori']); ?>" class="text-decoration-none text-dark d-flex justify-content-between align-items-center">
                            <?= $k['nama_kategori']; ?>
                            <span class="badge bg-secondary rounded-pill">View</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<?= $this->include('template/footer'); ?>
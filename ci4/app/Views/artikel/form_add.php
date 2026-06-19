<?= $this->include('template/header'); ?>

<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        <div class="card shadow-sm p-4 mb-5 border-0 rounded-4">
            <?php $title = $title ?? 'Tambah Artikel'; ?>
            <h3 class="fw-bold mb-4 text-center"><?= $title; ?></h3>
            
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="judul" class="form-label fw-bold text-muted">Judul Artikel</label>
                    <input type="text" name="judul" id="judul" class="form-control form-control-lg bg-light" placeholder="Masukkan judul yang menarik..." required>
                </div>
                
                <div class="mb-3">
                    <label for="id_kategori" class="form-label fw-bold text-muted">Kategori</label>
                    <select name="id_kategori" id="id_kategori" class="form-select bg-light" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php if (!empty($kategori)): ?>
                            <?php foreach ($kategori as $k): ?>
                                <option value="<?= $k['id_kategori']; ?>"><?= $k['nama_kategori']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label fw-bold text-muted">Cover Artikel (Gambar)</label>
                    <input type="file" name="gambar" id="gambar" class="form-control bg-light" accept="image/*">
                </div>
                
                <div class="mb-4">
                    <label for="isi" class="form-label fw-bold text-muted">Isi Artikel</label>
                    <textarea name="isi" id="isi" cols="30" rows="10" class="form-control bg-light" placeholder="Tuliskan isi artikel lu di sini..." required></textarea>
                </div>
                
                <div class="d-flex justify-content-between pt-2">
                    <a href="<?= base_url('/admin/artikel'); ?>" class="btn btn-outline-secondary px-4 fw-bold">Kembali</a>
                    <button type="submit" class="btn btn-primary px-5 fw-bold">Kirim Artikel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('template/footer'); ?>
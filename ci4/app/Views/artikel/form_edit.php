<?= $this->include('template/header'); ?>
<?php $artikel = $artikel ?? []; ?>
<?php $kategori = $kategori ?? []; ?>

<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        <div class="card shadow-sm p-4 mb-5 border-0 rounded-4">
            <h3 class="fw-bold mb-4 text-center"><?= $title ?? 'Edit Artikel'; ?></h3>
            
            <!-- WAJIB: Tambahkan enctype dan ubah method jadi post -->
            <form action="" method="post" enctype="multipart/form-data">
                
                <!-- INPUT HIDDEN: Buat nyimpen nama gambar lama biar nggak ilang -->
                <input type="hidden" name="gambarLama" value="<?= $artikel['gambar'] ?? ''; ?>">

                <div class="mb-3">
                    <label for="judul" class="form-label fw-bold text-muted">Judul Artikel</label>
                    <input type="text" name="judul" id="judul" value="<?= $artikel['judul'] ?? ''; ?>" class="form-control form-control-lg bg-light" required>
                </div>
                
                <div class="mb-3">
                    <label for="id_kategori" class="form-label fw-bold text-muted">Kategori</label>
                    <select name="id_kategori" id="id_kategori" class="form-select bg-light" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k['id_kategori']; ?>" <?= ($k['id_kategori'] == ($artikel['id_kategori'] ?? '')) ? 'selected' : ''; ?> >
                                <?= $k['nama_kategori']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- KOLOM UPLOAD GAMBAR DI HALAMAN EDIT -->
                <div class="mb-3">
                    <label for="gambar" class="form-label fw-bold text-muted">Cover Artikel (Gambar Baru)</label>
                    <input type="file" name="gambar" id="gambar" class="form-control bg-light" accept="image/*">
                    
                    <!-- Info kecil buat ngasih tau gambar saat ini -->
                    <?php if(!empty($artikel['gambar'])): ?>
                        <small class="text-primary mt-2 d-block">
                            <i class="bi bi-info-circle"></i> Gambar saat ini: <strong><?= $artikel['gambar']; ?></strong>. <br>
                            <em>(Biarkan kosong jika tidak ingin mengubah gambar)</em>
                        </small>
                    <?php endif; ?>
                </div>
                
                <div class="mb-4">
                    <label for="isi" class="form-label fw-bold text-muted">Isi Artikel</label>
                    <textarea name="isi" id="isi" cols="30" rows="10" class="form-control bg-light" required><?= $artikel['isi'] ?? ''; ?></textarea>
                </div>
                
                <div class="d-flex justify-content-between pt-2">
                    <a href="<?= base_url('/admin/artikel'); ?>" class="btn btn-outline-secondary px-4 fw-bold">Batal</a>
                    <button type="submit" class="btn btn-success px-5 fw-bold">Update Artikel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('template/footer'); ?>
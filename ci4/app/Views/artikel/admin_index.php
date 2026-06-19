<?= $this->include('template/header'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold m-0"><?= $title; ?></h2>
    <a href="<?= base_url('/admin/artikel/add'); ?>" class="btn btn-success fw-bold">+ Tambah Artikel</a>
</div>

<div class="card p-3 mb-4 shadow-sm border-0 bg-light">
    <form id="search-form" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="q" id="search-box" value="<?= $q; ?>" placeholder="Cari judul artikel..." class="form-control">
        </div>
        <div class="col-md-3">
            <select name="kategori_id" id="category-filter" class="form-select">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= $k['id_kategori']; ?>"><?= $k['nama_kategori']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="sort" id="sort-filter" class="form-select">
                <option value="terbaru">Terbaru Ditambahkan</option>
                <option value="judul_asc">Judul (A - Z)</option>
                <option value="judul_desc">Judul (Z - A)</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 fw-bold">Terapkan</button>
        </div>
    </form>
</div>

<div id="loading-indicator" class="text-center my-5" style="display: none;">
    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    <div class="mt-2 fw-bold text-muted">Mengambil data dari server...</div>
</div>

<div class="card overflow-hidden shadow-sm border-0" id="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="ps-4 py-3">Cover</th>
                    <th>Judul Artikel & Kategori</th>
                    <th class="text-center">Status</th>
                    <th class="text-center pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody id="article-container">
                </tbody>
        </table>
    </div>
</div>

<div id="pagination-container" class="mt-4 d-flex justify-content-center">
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    const articleContainer = $('#article-container');
    const paginationContainer = $('#pagination-container');
    const loadingIndicator = $('#loading-indicator');
    const tableCard = $('#table-card');
    
    // Fungsi Utama Tarik Data AJAX
    const fetchData = (url) => {
        // Tampilkan loading, sembunyikan tabel
        tableCard.hide();
        paginationContainer.hide();
        loadingIndicator.show();

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(data) {
                renderArticles(data.artikel);
                // Langsung masukkan HTML pagination dari controller
                paginationContainer.html(data.pager); 
                
                // Sembunyikan loading, tampilkan tabel
                loadingIndicator.hide();
                tableCard.show();
                paginationContainer.show();
            }
        });
    };

    // Fungsi Render Baris Tabel
    const renderArticles = (articles) => {
        let html = '';
        if (articles.length > 0) {
            articles.forEach(article => {
                let imgHtml = article.gambar ? 
                    `<img src="<?= base_url('gambar/') ?>${article.gambar}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">` : 
                    `<div class="bg-secondary rounded d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 60px; height: 60px;"><small>No Img</small></div>`;
                
                let statusIcon = article.status == 1 ? '✅' : '⏳';

                html += `
                <tr>
                    <td class="ps-4">${imgHtml}</td>
                    <td>
                        <div class="fw-bold text-dark fs-6">${article.judul}</div>
                        <span class="badge bg-info text-dark mt-1">${article.nama_kategori}</span>
                    </td>
                    <td class="text-center">${statusIcon}</td>
                    <td class="text-center pe-4">
                        <a class="btn btn-sm btn-outline-primary" href="/admin/artikel/edit/${article.id}">Ubah</a>
                        <a class="btn btn-sm btn-outline-danger" href="/admin/artikel/delete/${article.id}" onclick="return confirm('Yakin mau menghapus artikel ini?');">Hapus</a>
                    </td>
                </tr>`;
            });
        } else {
            html = `<tr><td colspan="4" class="text-center py-5 text-muted">Data artikel tidak ditemukan.</td></tr>`;
        }
        articleContainer.html(html);
    };

    // Cegah Reload saat klik tombol Terapkan/Cari
    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        triggerSearch();
    });

    // Otomatis cari saat Dropdown Kategori atau Sorting diubah
    $('#category-filter, #sort-filter').on('change', function() {
        triggerSearch();
    });

    function triggerSearch() {
        const q = $('#search-box').val();
        const kategori_id = $('#category-filter').val();
        const sort = $('#sort-filter').val();
        // Buat URL dengan parameter pencarian dan sorting
        fetchData(`<?= base_url('/admin/artikel') ?>?q=${q}&kategori_id=${kategori_id}&sort=${sort}`);
    }

    // Intercept klik tombol pagination bawaan CI4 biar pakai AJAX
    $(document).on('click', '.pagination a', function(e){
        e.preventDefault();
        let url = $(this).attr('href'); // Ambil link halamannya
        fetchData(url); // Proses via AJAX
    });

    // Panggilan pertama saat halaman dibuka
    fetchData('<?= base_url('/admin/artikel') ?>');
});
</script>

<?= $this->include('template/footer'); ?>
<?= $this->include('template/header'); ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Data Artikel (Versi AJAX)</h1>
        <!-- Tombol untuk memunculkan Modal Tambah -->
        <button type="button" class="btn btn-success fw-bold" id="btnTambah">
            + Tambah Artikel AJAX
        </button>
    </div>

    <table class="table table-bordered table-hover shadow-sm" id="artikelTable">
        <thead class="table-dark text-center">
            <tr>
                <th>ID</th>
                <th>Judul Artikel</th>
                <th>ID Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data akan masuk ke sini otomatis -->
        </tbody>
    </table>
</div>

<!-- ================= MODAL BOOTSTRAP UNTUK FORM ================= -->
<div class="modal fade" id="artikelModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold" id="modalTitle">Form Artikel</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formArtikel">
        <div class="modal-body bg-light">
            <!-- ID disembunyikan di sini, buat nentuin ini Tambah atau Edit -->
            <input type="hidden" name="id" id="artikel_id">
            
            <div class="mb-3">
                <label class="form-label fw-bold">Judul Artikel</label>
                <input type="text" class="form-control" name="judul" id="judul" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">ID Kategori</label>
                <input type="number" class="form-control" name="id_kategori" id="id_kategori" placeholder="Contoh: 1, 2, atau 3..." required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Isi Artikel</label>
                <textarea class="form-control" name="isi" id="isi" rows="5" required></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary fw-bold" id="btnSimpan">Simpan Data</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- ================= END MODAL ================= -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    
    function showLoadingMessage() {
        $('#artikelTable tbody').html('<tr><td colspan="4" class="text-center py-4 text-muted">Sedang mengambil data... ⏳</td></tr>');
    }

    function loadData() {
        showLoadingMessage(); 
        $.ajax({
            url: "<?= base_url('ajax/getData') ?>",
            method: "GET",
            dataType: "json",
            success: function(data) {
                var tableBody = "";
                for (var i = 0; i < data.length; i++) {
                    var row = data[i];
                    tableBody += '<tr>';
                    tableBody += '<td class="text-center">' + row.id + '</td>';
                    tableBody += '<td>' + row.judul + '</td>';
                    tableBody += '<td class="text-center"><span class="badge bg-secondary">' + row.id_kategori + '</span></td>';
                    tableBody += '<td class="text-center">';
                    // Tambahan Tombol Edit di tabel
                    tableBody += '<a href="#" class="btn btn-sm btn-primary btn-edit me-2" data-id="' + row.id + '">Ubah</a>';
                    tableBody += '<a href="#" class="btn btn-sm btn-danger btn-delete" data-id="' + row.id + '">Hapus</a>';
                    tableBody += '</td>';
                    tableBody += '</tr>';
                }
                $('#artikelTable tbody').html(tableBody);
            }
        });
    }

    // Load data pas halaman pertama dibuka
    loadData();

    // =============== LOGIK TOMBOL TAMBAH ===============
    $('#btnTambah').click(function(){
        $('#formArtikel')[0].reset(); // Kosongin form
        $('#artikel_id').val('');     // Kosongin ID
        $('#modalTitle').text('Tambah Artikel Baru'); // Ubah judul modal
        $('#artikelModal').modal('show'); // Tampilkan pop-up modal
    });

    // =============== LOGIK TOMBOL EDIT ===============
    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        
        // Ambil data spesifik dari database tanpa reload
        $.ajax({
            url: "<?= base_url('ajax/getDetail/') ?>" + id,
            method: "GET",
            dataType: "json",
            success: function(data) {
                $('#modalTitle').text('Edit Artikel');
                
                // Isi otomatis form-nya pakai data dari server
                $('#artikel_id').val(data.id);
                $('#judul').val(data.judul);
                $('#id_kategori').val(data.id_kategori);
                $('#isi').val(data.isi);
                
                $('#artikelModal').modal('show'); // Tampilkan pop-up modal
            }
        });
    });

    // =============== LOGIK SUBMIT FORM (TAMBAH & EDIT) ===============
    $('#formArtikel').submit(function(e){
        e.preventDefault();
        var formData = $(this).serialize(); // Rangkum semua isian form jadi 1 paket

        $.ajax({
            url: "<?= base_url('ajax/save') ?>",
            method: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                $('#artikelModal').modal('hide'); // Tutup modal
                alert(response.pesan); // Tampilkan alert sukses
                loadData(); // Refresh tabel seketika tanpa reload halaman!
            }
        });
    });

    // =============== LOGIK TOMBOL HAPUS ===============
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var id = $(this).data('id');

        if (confirm('Yakin mau menghapus artikel ini? Data nggak bisa balik lho!')) {
            $.ajax({
                url: "<?= base_url('ajax/delete/') ?>" + id,
                method: "GET",
                success: function(data) {
                    loadData(); // Refresh tabel setelah hapus
                }
            });
        }
    });
});
</script>

<?= $this->include('template/footer'); ?>
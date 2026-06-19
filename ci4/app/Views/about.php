<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <h1><?= $title ?? 'Halaman About'; ?></h1>
    <hr>
    <p>Ini adalah halaman About yang sekarang sudah menggunakan View Layout baru!</p>
<?= $this->endSection() ?>
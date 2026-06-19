<div class="widget-box">
    <h3 class="title">Artikel Terkini</h3>
    <ul>
        <?php foreach ($artikel as $row): ?>
            <li>
                <a href="<?= base_url('/artikel/' . $row['slug']) ?>">
                    <?= $row['judul'] ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php $pager->setSurroundCount(2) ?>
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
    <?php if ($pager->hasPrevious()) : ?>
        <li class="page-item">
            <a href="<?= $pager->getFirst() ?>" aria-label="First" class="page-link">
                <span aria-hidden="true">&laquo; Awal</span>
            </a>
        </li>
        <li class="page-item">
            <a href="<?= $pager->getPrevious() ?>" aria-label="Previous" class="page-link">
                <span aria-hidden="true">&lsaquo; Mundur</span>
            </a>
        </li>
    <?php endif ?>

    <?php foreach ($pager->links() as $link) : ?>
        <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
            <a href="<?= $link['uri'] ?>" class="page-link">
                <?= $link['title'] ?>
            </a>
        </li>
    <?php endforeach ?>

    <?php if ($pager->hasNext()) : ?>
        <li class="page-item">
            <a href="<?= $pager->getNext() ?>" aria-label="Next" class="page-link">
                <span aria-hidden="true">Maju &rsaquo;</span>
            </a>
        </li>
        <li class="page-item">
            <a href="<?= $pager->getLast() ?>" aria-label="Last" class="page-link">
                <span aria-hidden="true">Akhir &raquo;</span>
            </a>
        </li>
    <?php endif ?>
    </ul>
</nav>
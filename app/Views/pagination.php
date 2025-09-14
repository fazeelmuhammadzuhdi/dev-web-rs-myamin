<?php

use CodeIgniter\Pager\PagerRenderer;


$pager->setSurroundCount(2);
?>


<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
    <ul class="pagination mt-5 pagination-circle pagination-3d justify-content-center">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>" class="page-link">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link" href="<?= $link['uri'] ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>" class="page-link">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>

        <?php endif ?>
    </ul>
</nav>
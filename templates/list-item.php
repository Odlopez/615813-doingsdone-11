<li class="main-navigation__list-item">
    <a class="main-navigation__list-item-link" href="#"><?= $project['name'] ?></a>

    <span class="main-navigation__list-item-count">
        <?= counts_category_in_tasks($tasks, $project['name']) ?>
    </span>
</li>
<li class="main-navigation__list-item">
    <a class="main-navigation__list-item-link" href="#"><?= htmlspecialchars($project) ?></a>
    <span class="main-navigation__list-item-count">
        <?= counts_category_in_tasks($tasks, $project) ?>
    </span>
</li>
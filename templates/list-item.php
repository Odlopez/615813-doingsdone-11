<li class="main-navigation__list-item <?= get_project_class_name($project, $project_id) ?>">
    <a class="main-navigation__list-item-link" href="/?project_id=<?= $project['id'] ?>"><?= $project['name'] ?></a>

    <span class="main-navigation__list-item-count">
        <?= counts_category_in_tasks($tasks, $project['name']) ?>
    </span>
</li>
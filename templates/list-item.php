<li class="main-navigation__list-item <?= get_project_class_name($project, $active_project_id) ?>">
    <a class="main-navigation__list-item-link"
       href="<?= get_list_item_link_href($project['id'], $show_complete_tasks) ?>"><?= $project['name'] ?>
    </a>

    <span class="main-navigation__list-item-count">
        <?= counts_category_in_tasks($tasks, $project['name']) ?>
    </span>
</li>
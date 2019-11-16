<tr class="tasks__item task <?= get_task_class_name($task) ?>">
    <td class="task__select">
        <label class="checkbox task__checkbox">
            <input class="checkbox__input visually-hidden task__checkbox"
                   type="checkbox" value="<?= htmlspecialchars($task['id']); ?>"
                <?= $task['is_done'] ? 'checked' : '' ?>>
            <span class="checkbox__text"><?= htmlspecialchars($task['name']) ?></span>
        </label>
    </td>

    <td class="task__file">
        <?php if (isset($task['file_name']) && isset($task['file_path'])) : ?>
            <a class="download-link"
               href="<?= $task['file_path'] ?>">
                <?= htmlspecialchars($task['file_name']) ?>
            </a>
        <?php endif ?>
    </td>

    <td class="task__date"><?= $task['deadline'] ? date("d.m.Y", strtotime($task['deadline'])) : '' ?></td>
    <td class="task__controls"></td>
</tr>
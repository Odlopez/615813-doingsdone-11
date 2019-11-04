<tr class="tasks__item task <?= get_task_class_name($task) ?>">
    <td class="task__select">
        <label class="checkbox task__checkbox">
            <input class="checkbox__input visually-hidden task__checkbox"
                   type="checkbox" value="<?= htmlspecialchars($task['id']); ?>"
                <?= $task['isDone'] ? 'checked' : '' ?>>
            <span class="checkbox__text"><?= htmlspecialchars($task['task']) ?></span>
        </label>
    </td>

    <td class="task__file">
        <a class="download-link" href="#">Home.psd</a>
    </td>

    <td class="task__date"><?= htmlspecialchars($task['date']) ?></td>
    <td class="task__controls"></td>
</tr>
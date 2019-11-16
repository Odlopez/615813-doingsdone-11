<?php
/**
 * Возвращает все проекты для заданных условий
 * @param $link mysqli Ресурс соединения
 * @param int $user_id массив значений для подстановки в sql-запрос
 * @return array результат запроса к БД в виде массива
 */
function getAllProjects($link, int $user_id): array
{
    $sql_projects = "SELECT p.name, p.id, COUNT(t.id) as tasks_count FROM projects p 
    LEFT JOIN tasks t ON t.project_id = p.id WHERE user_id = ? GROUP BY p.id";

    return get_db_result($link, $sql_projects, [$user_id]);
}

/**
 * Возвращает задачи для заданных условий
 * @param $link mysqli Ресурс соединения
 * @param int $user_id массив значений для подстановки в sql-запрос
 * @return array результат запроса к БД в виде массива
 */
function getTasks($link, int $user_id, array $options = []): array
{
    $sql_tasks = "SELECT *, t.name AS name, p.name AS project_name, p.id AS project_id FROM tasks t 
    JOIN projects p ON t.project_id = p.id WHERE user_id = ?";

    $data = [$user_id];

    if (isset($options['is_done']) && (int)$options['is_done'] === 0) {
        $sql_tasks = $sql_tasks . " AND t.is_done = ?";
        $data[] = (int)$options['is_done'];
    }

    if (isset($options['project_id']) && $options['project_id'] !== null) {
        $sql_tasks = $sql_tasks . "  AND p.id = ?";
        $data[] = (int)$options['project_id'];
    }

    return get_db_result($link, $sql_tasks, $data);
}

function setTasks($link, array $options)
{
    $sql_add_task = "INSERT INTO tasks (name, project_id, deadline, file_name, file_path)
        VALUES (?, ?, ?, ?, ?)";

    return get_db_result($link, $sql_add_task, $options);
}

/**
 * Возвращает адресс ссылки проекта, в зависимости переданных get-данных
 * @param string $progect_id айдишник проекта
 * @param int $show_completed идентефикатор, определяющий показывать ли выполненные задачи
 * @return string ссылка для .main-navigation__list-item-link
 */
function get_list_item_link_href(string $progect_id, int $show_completed = null): string
{
    $href = [];
    $href[] = '/index.php?project_id=' . $progect_id;

    if ($show_completed === 1) {
        $href[] = 'show_completed=' . $show_completed;
    }

    return implode('&', $href);
}

/**
 * Возвращает булевое значение - показатель срочности задачи
 * @param string $date дата выполнения задачи представленная в строковом виде
 * @return bool true - если задача срочная, false - если нет
 */
function checks_urgency_of_task(string $date): bool
{
    $urgency_interval_in_hours = 24;
    $urgency_task = false;
    $task_date = strtotime($date);
    $now_time = time();

    if (($task_date - $now_time) < 0) {
        return $urgency_task;
    }

    $urgency_task = ($task_date - $now_time) <= $urgency_interval_in_hours * 60 * 60;

    return $urgency_task;
}

/**
 * Возвращает имена классов для строки в таблице задач
 * @param array $task массив данных конкретной задачи
 * @return string скроку с дополнительными именами класса для строки .task-item
 */
function get_task_class_name(array $task): string
{
    $classes = [];

    if ($task['is_done']) {
        $classes[] = 'task--completed';
    }

    if (checks_urgency_of_task((string)($task['deadline'])) && !$task['is_done']) {
        $classes[] = 'task--important';
    }

    return implode(' ', $classes);
}

/**
 * Возвращает имена классов для ссылки проекта
 * @param array $project массив данных конкретной задачи
 * @param int $project_id айдишник целевого проекта
 * @return string скроку с дополнительными именами класса для строки .task-item
 */
function get_project_class_name(array $project, int $project_id = null): string
{
    $classes = [];

    if ($project_id !== null && $project['id'] === (int)$project_id) {
        $classes[] = 'main-navigation__list-item--active';
    }

    return implode(' ', $classes);
}

/**
 * Валидирует поле с названием задачи
 * @param string $task_name название задачи
 * @return bool
 */
function validate_task_name(string $task_name)
{
    if (strlen($task_name) === 0) {
        return 'Поле не должно быть пустым';
    } elseif (strlen($task_name) > 255) {
        return 'Название задачи не должно превышать 255 символов';
    }

    return false;
}

/**
 * Валидирует поле прогектов
 * @param array $projects массив с проектами доступными пользователю
 * @param int $project_id айдишник выбранного в поле проекта
 * @return bool
 */
function validate_project(array $projects, int $project_id)
{
    $is_not_correct_id = true;

    array_walk($projects, function ($item) use ($project_id, &$is_not_correct_id) {
        if (isset($item['id']) && $item['id'] == $project_id) {
            $is_not_correct_id  = false;
        }
    });

    return $is_not_correct_id ? 'Такого проекта не существует' : false;
}

/**
 * Валидирует поле с датой задачи
 * @param string $date_value значение поле даты задачи
 * @return bool
 */
function validate_date(string $date_value)
{
    if (strlen($date_value) > 0) {
        $now_date = date('Y-m-d', time());

        if (!is_date_valid($date_value)) {
            return 'Дата должна быть в формате \'ГГГГ-ММ-ДД\'';
        } elseif ($date_value < $now_date) {
            return 'Дата должна быть больше или равна текущей';
        }

        return false;
    }

    return false;
}

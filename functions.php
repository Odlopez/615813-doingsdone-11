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
 * @param int $is_done идентефикатор завершенных заданий
 * @param int $project_id имя проекту, по которому нужно фильтровать задачи
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

/**
 * Возвращает адресс ссылки проекта, в зависимости переданных get-данных
 * @param string $progect_id айдишник проекта
 * @return string ссылка для .main-navigation__list-item-link
 */
function get_list_item_link_href(string $progect_id, int $show_completed = null): string
{
    $href = [];
    $href[] = '?project_id=' . $progect_id;

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

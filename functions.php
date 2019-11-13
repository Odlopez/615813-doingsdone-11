<?php
/**
 * Возвращает все проекты для заданных условий
 * @param $link mysqli Ресурс соединения
 * @param int $user_id массив значений для подстановки в sql-запрос
 * @return array результат запроса к БД в виде массива
 */
function getAllProjects($link, int $user_id): array
{
    $sql_projects = "SELECT name, id FROM projects WHERE user_id = ?";

    return get_db_result($link, $sql_projects, [$user_id]);
}

/**
 * Возвращает задачи для заданных условий
 * @param $link mysqli Ресурс соединения
 * @param int $user_id массив значений для подстановки в sql-запрос
 * @param int $project_id имя проекту, по которому нужно фильтровать задачи
 * @return array результат запроса к БД в виде массива
 */
function getProjectTasks($link, int $user_id, int $project_id): array
{
    $sql_tasks = "SELECT *, t.name AS name, p.name AS project_name, p.id AS project_id FROM tasks t 
    JOIN projects p ON t.project_id = p.id WHERE user_id = ?";

    $data = [$user_id];

    if ($project_id !== 0) {
        $sql_tasks = $sql_tasks . "  AND p.id = ?";
        $data[] = $project_id;
    }

    return get_db_result($link, $sql_tasks, $data);
}

/**
 * Возвращает задачи для всех проектов определенного юзера
 * @param $link mysqli Ресурс соединения
 * @param int $user_id массив значений для подстановки в sql-запрос
 * @return array результат запроса к БД в виде массива
 */
function getAllTasks($link, int $user_id): array
{
    return getProjectTasks($link, $user_id, 0);
}

/**
 * Возвращает количество совпадающих по назанию категорий в переданном массиве проектов
 * @param array $tasks_list массив с задачами
 * @param string $parameter_value имя категории
 * @return int количество сопадающих категорий
 */
function counts_category_in_tasks(array $tasks_list, string $parameter_value): int
{
    return array_reduce($tasks_list, function ($carry, $item_task) use ($parameter_value) {
        $carry += $item_task['project_name'] === $parameter_value ? 1 : 0;

        return $carry;
    }, 0);
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
 * @param int $project_id айди выбранного проекта
 * @return string скроку с дополнительными именами класса для строки .task-item
 */
function get_project_class_name(array $project, int $project_id): string
{
    $classes = [];

    if ((int)$project['id'] === $project_id) {
        $classes[] = 'main-navigation__list-item--active';
    }

    return implode(' ', $classes);
}

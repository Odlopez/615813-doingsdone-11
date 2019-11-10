<?php
/**
 * Возвращает все проекты для заданных условий
 * @param $link mysqli Ресурс соединения
 * @param int $user_id массив значений для подстановки в sql-запрос
 * @return array результат запроса к БД в виде массива
 */
function getAllProjects($link, int $user_id): array
{
    $sql_projects = "SELECT name FROM projects WHERE user_id = ?";

    return get_db_result($link, $sql_projects, [$user_id]);
}

/**
 * Возвращает задачи для заданных условий
 * @param $link mysqli Ресурс соединения
 * @param int $user_id массив значений для подстановки в sql-запрос
 * @return array результат запроса к БД в виде массива
 */
function getAllTasks($link, int $user_id): array
{
    $sql_tasks = "SELECT *, t.name AS name, p.name AS project_name FROM tasks t 
    JOIN projects p ON t.project_id = p.id WHERE user_id = ?";

    return get_db_result($link, $sql_tasks, [$user_id]);
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

    if (checks_urgency_of_task(htmlspecialchars($task['deadline'])) && !$task['is_done']) {
        $classes[] = 'task--important';
    }

    return implode(' ', $classes);
}

<?php
/**
 * Возвращает все проекты для заданных условий
 * @param $link mysqli Ресурс соединения
 * @param array $data массив значений для подстановки в sql-запрос
 * @return array результат запроса к БД в виде массива
 */
function getAllProjects($link, $data = [])
{
    $sql_projects = "SELECT name FROM projects WHERE user_id = ?";

    return get_db_result($link, $sql_projects, $data);
}

/**
 * Возвращает задачи для заданных условий
 * @param $link mysqli Ресурс соединения
 * @param array $data массив значений для подстановки в sql-запрос
 * @return array результат запроса к БД в виде массива
 */
function getAllTasks($link, $data = [])
{
    $sql_tasks = <<<SQL
        SELECT t.name AS task_name, p.name AS project_name, t.deadline, t.is_done 
        FROM tasks t JOIN projects p ON t.project_id = p.id WHERE user_id = ?
SQL;

    return get_db_result($link, $sql_tasks, $data);
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

/**
 * @param $link mysqli Ресурс соединения
 * @param string $sql SQL запрос с плейсхолдерами вместо значений
 * @param array $data массив значений для подстановки в sql-запрос
 * @return array  результат запроса к БД в виде массива
 */
function get_db_result($link, $sql, $data = [])
{
    $result = [];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);

    if ($res) {
        $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    return $result;
}

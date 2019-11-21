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
 * @param array $options массив с дополнительными параметрами запроса
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
 * @param $link mysqli Ресурс соединения
 * @param array $options массив с данными по новой задаче
 */
function setTask($link, array $options)
{
    $sql_add_task = "INSERT INTO tasks (name, project_id, deadline, file_name, file_path)
        VALUES (?, ?, ?, ?, ?)";

    get_db_result($link, $sql_add_task, $options);
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

function get_link_href_given_show_completed(string $href, int $show_completed = null): string
{
    if ($show_completed === 1) {
        $href = $href . '?show_completed=' . $show_completed;
    }

    return $href;
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
 * @param string $input_name название задачи
 * @return string | bool
 */
function validate_task_name(string $input_name)
{
    if (strlen($input_name) === 0) {
        return 'Поле не должно быть пустым';
    } elseif (strlen($input_name) > 255) {
        return 'Название задачи не должно превышать 255 символов';
    }

    return false;
}

/**
 * Валидирует поле прогектов
 * @param array $projects массив с проектами доступными пользователю
 * @param int $project_id айдишник выбранного в поле проекта
 * @return string | bool
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
 * @return string | bool
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

/**
 * Валидирует поле email при регистрации
 * @param $link mysqli Ресурс соединения
 * @param string $email_value значение поля email
 * @return string | bool
 */
function validate_registration_email($link, string $email_value)
{
    if (strlen($email_value) === 0) {
        return 'Поле не должно быть пустым';
    } elseif (strlen($email_value) > 128) {
        return 'Название задачи не должно превышать 128 символов';
    } elseif (!filter_var($email_value, FILTER_VALIDATE_EMAIL)) {
        return 'Email должен быть корректным';
    }

    $sql_mail = "SELECT email FROM users WHERE email LIKE ?";

    $is_registered_email = !!get_db_result($link, $sql_mail, [$email_value]);

    if ($is_registered_email) {
        return 'Этот email уже зарегистрирован';
    }

    return false;
}

/**
 * Валидирует поле password при регистрации
 * @param string $password_value значения поля password
 * @return string | bool
 */
function validate_registration_password(string $password_value)
{
    if (strlen($password_value) === 0) {
        return 'Поле не должно быть пустым';
    } elseif (strlen($password_value) < 8) {
        return 'Пароль не должен быть короче 8 символов';
    }

    return false;
}

/**
 * Валидирует поле с именем пользователя
 * @param string $input_name название задачи
 * @return string | bool
 */
function validate_user_name(string $input_name)
{
    if (strlen($input_name) === 0) {
        return 'Поле не должно быть пустым';
    } elseif (strlen($input_name) > 140) {
        return 'Название задачи не должно превышать 140 символов';
    }

    return false;
}

/**
 * Возвращает hash пароля
 * @param string $password_value
 * @return string
 */
function get_password_hash(string $password_value): string
{
    return password_hash($password_value, PASSWORD_DEFAULT);
}

/**
 * @param $link mysqli Ресурс соединения
 * @param array $options массив с данными нового пользователя
 */
function setNewUser($link, array $options)
{
    $sql_add_task = "INSERT INTO users (name, email, password)
        VALUES (?, ?, ?)";

    get_db_result($link, $sql_add_task, $options);
}

/**
 * Валидирует поле email при авторизации
 * @param $link mysqli Ресурс соединения
 * @param string $email_value значение поля email
 * @return string | array
 */
function validate_authorization_email($link, string $email_value)
{
    if (strlen($email_value) === 0) {
        return 'Поле не должно быть пустым';
    } elseif (strlen($email_value) > 128) {
        return 'Название задачи не должно превышать 128 символов';
    } elseif (!filter_var($email_value, FILTER_VALIDATE_EMAIL)) {
        return 'Email должен быть корректным';
    }

    $sql_mail = "SELECT email FROM users WHERE email LIKE ?";

    $registered_email = get_db_result($link, $sql_mail, [$email_value]);

    if (!$registered_email) {
        return 'Данный email не зарегистрирован';
    }

    return $registered_email;
}

function validate_authorization_password($link, string $password_value, string $email_value)
{
    if (!validate_authorization_email($link, $email_value)) {
        return 'sdfsdf';
    }

    if (strlen($password_value) === 0) {
        return 'Поле не должно быть пустым';
    } elseif (strlen($password_value) < 8) {
        return 'Пароль не должен быть короче 8 символов';
    }

    $sql_password = "SELECT password FROM users WHERE email LIKE ?";

    $registered_password_date = get_db_result($link, $sql_password, [$email_value]);

    if (isset($registered_password_date[0]['password']) && !password_verify($password_value, $registered_password_date[0]['password'])) {
        return 'Пароль не соответствует введенному email';
    }

    return false;
}

function get_authorization_user_id($link, string $email_value)
{
    $sql_user = "SELECT id FROM users WHERE email LIKE ?";
    $user_data = get_db_result($link, $sql_user, [$email_value]);

    if (isset($user_data[0]) && isset($user_data[0]['id'])) {
        return (int)$user_data[0]['id'];
    }

    return null;
}

function get_authorization_user_name($link, string $email_value)
{
    $sql_user = "SELECT name FROM users WHERE email LIKE ?";
    $user_data = get_db_result($link, $sql_user, [$email_value]);

    if (isset($user_data[0]) && isset($user_data[0]['name'])) {
        return $user_data[0]['name'];
    }

    return null;
}

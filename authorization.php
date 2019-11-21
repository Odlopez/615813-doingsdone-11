<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'sql-connect.php';

if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$errors = [];
$user_post = [];

if (isset($_GET['show_completed'])) {
    $show_complete_tasks = (int)$_GET['show_completed'];
} else {
    $show_complete_tasks = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_post = $_POST;
    $result_email_validation = validate_authorization_email($con, $user_post['email'] ?? '');

    if (is_array($result_email_validation)) {
        $errors['password'] = validate_authorization_password($con, $user_post['password'] ?? '', $user_post['email']);
    } else {
        $errors['email'] = $result_email_validation;
        $errors['password'] = validate_authorization_password($con, $user_post['password'] ?? '', $user_post['email'] ?? '');
    }


    $errors = array_filter($errors);

    if (count($errors) === 0) {
        $user_id = get_authorization_user_id($con, $user_post['email']);
        $user_name = get_authorization_user_name($con, $user_post['email']);

        if ($user_id) {
            $_SESSION['user'] = $user_id;
            $_SESSION['user_name'] = $user_name;

            $location = 'Location: ' . get_link_href_given_show_completed('index.php', $show_complete_tasks);

            header($location);
        } else {
            exit("Ошибка подключения: не удалось получить данные пользователя");
        }
    }
}

$page_content = include_template('authorization-template.php', [
    'user_post' => $user_post,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'show_complete_tasks' => $show_complete_tasks,
    'title' => 'Дела в порядке',
]);

print($layout_content);

<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'sql-connect.php';

if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$errors = [];
$new_user = [];

if (isset($_GET['show_completed'])) {
    $show_complete_tasks = (int)$_GET['show_completed'];
} else {
    $show_complete_tasks = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_user = $_POST;
    $all_fields = ['email', 'password', 'name'];
    $rules = [
        'name' => function ($value) {
            return validate_user_name($value);
        },
        'email' => function ($value) use ($con) {
            return validate_registration_email($con, $value);
        },
        'password' => function ($value) {
            return validate_registration_password($value);
        },
    ];

    foreach ($all_fields as $key) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($new_user[$key] ?? '');
        }
    }

    $errors = array_filter($errors);

    if (count($errors) === 0) {
        $user_data = [
            $new_user['name'],
            $new_user['email'],
            get_password_hash($new_user['password'])
        ];

        setNewUser($con, $user_data);

        header('Location: authorization.php');
    }
}

$page_content = include_template('register-template.php', [
    'new_user' => $new_user,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'show_complete_tasks' => $show_complete_tasks,
    'title' => 'Дела в порядке'
]);

print($layout_content);

<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'sql-connect.php';

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
            return validate_input_name($value);
        },
        'email' => function ($value) use ($con) {
            return validate_registration_email($con, $value);
        },
        'password' => function ($value) {
            return validate_registration_password($value);
        },
    ];

    foreach ($new_user as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
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

        $location = 'Location: ' . get_link_href_given_show_completed('index.php', $show_complete_tasks);

        header($location);
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

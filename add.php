<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'sql-connect.php';

$projects = getAllProjects($con, $user_id);
$new_task = [];
$errors = [];

if (isset($_GET['show_completed'])) {
    $show_complete_tasks = (int)$_GET['show_completed'];
} else {
    $show_complete_tasks = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_task = $_POST;
    $all_fields = ['name', 'project', 'date'];
    $rules = [
        'name' => function ($value) {
            return validate_task_name($value);
        },
        'project' => function ($value) use ($projects) {
            return validate_project($projects, $value);
        },
        'date' => function ($value) {
            return validate_date($value);
        },
    ];

    foreach ($new_task as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
    }

    $errors = array_filter($errors);

    if (count($errors) === 0) {
        if (!empty($_FILES['file']['name'])) {
            $path = $_FILES['file']['name'];
            $tmp_name = $_FILES['file']['tmp_name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $new_file_path = 'uploads/' . $filename;

            move_uploaded_file($tmp_name, $new_file_path);
        }

        $task_data = [
            $new_task['name'],
            (int)$new_task['project'],
            $new_task['date'] === '' ? null : $new_task['date'],
            $path ?? null,
            $new_file_path ?? null
        ];

        setTask($con, $task_data);

        $location = 'Location: ' . get_link_href_given_show_completed('index.php', $show_complete_tasks);

        header($location);
    }
}

$page_content = include_template('add-template.php', [
    'projects' => $projects,
    'new_task' => $new_task,
    'show_complete_tasks' => $show_complete_tasks,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'show_complete_tasks' => $show_complete_tasks,
    'title' => 'Дела в порядке'
]);

print($layout_content);

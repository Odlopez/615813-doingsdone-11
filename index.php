<?php
require_once 'helpers.php';
require_once 'functions.php';

$con = mysqli_connect("localhost", "root", "", "615813-doingsdone-11");

if ($con === false) {
    exit("Ошибка подключения: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");

$show_complete_tasks = 0;
$user_id = 2;
$project_id = 0;
$projects = getAllProjects($con, $user_id);
$all_tasks = getAllTasks($con, $user_id);
$project_tasks = $all_tasks;

if (isset($_GET['project_id'])) {
    $project_id = (int)$_GET['project_id'];
    $project_tasks = getProjectTasks($con, $user_id, $project_id);
}

if (isset($_GET['show_completed'])) {
    $show_complete_tasks = (int)$_GET['show_completed'];
}

$page_content = include_template('main.php', [
    'projects' => $projects,
    'show_complete_tasks' => $show_complete_tasks,
    'all_tasks' => $all_tasks,
    'tasks' => $project_tasks,
    'project_id' => $project_id
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке'
]);

print($layout_content);

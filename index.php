<?php
require_once 'functions.php';
require_once 'helpers.php';

$con = mysqli_connect("localhost", "root", "", "615813-doingsdone-11");

if ($con === false) {
    exit("Ошибка подключения: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");


// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$user_id = 2;
$projects = getAllProjects($con, [$user_id]);
$tasks = getAllTasks($con, [$user_id]);

$page_content = include_template('main.php', [
    'projects' => $projects,
    'show_complete_tasks' => $show_complete_tasks,
    'tasks' => $tasks
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке'
]);

print($layout_content);

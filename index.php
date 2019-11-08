<?php
require_once 'functions.php';
require_once 'helpers.php';

$con = mysqli_connect("localhost", "root", "", "615813-doingsdone-11");

if ($con === false) {
    exit("Ошибка подключения: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");

$sql_projects = "SELECT name FROM projects WHERE user_id = ?";
$sql_tasks = "SELECT t.name AS task_name, p.name AS project_name, t.deadline, t.is_done FROM tasks t JOIN projects p ON t.project_id = p.id WHERE user_id = ?";


// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$user_id = 1;
$projects = get_db_result($con, $sql_projects, [$user_id]);
$tasks1 = get_db_result($con, $sql_tasks, [$user_id]);
$tasks = getAllTasks();

$page_content = include_template('main.php', [
    'projects' => $projects,
    'show_complete_tasks' => $show_complete_tasks,
    'tasks' => $tasks1
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке'
]);

print($layout_content);

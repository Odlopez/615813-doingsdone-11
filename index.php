<?php
require_once 'helpers.php';
require_once 'functions.php';
require_once 'sql-connect.php';

if (isset($_GET['show_completed'])) {
    $show_complete_tasks = (int)$_GET['show_completed'];
} else {
    $show_complete_tasks = 0;
}

if (isset($_SESSION['user'])) {
    $layout_name = 'layout.php';
    $projects = getAllProjects($con, $user_id);

    if (isset($_GET['project_id'])) {
        $active_project_id = $_GET['project_id'];
        $project_tasks = getTasks($con, $user_id, ['is_done' => $show_complete_tasks, 'project_id' => $active_project_id]);
    } else {
        $active_project_id = null;
        $project_tasks =  getTasks($con, $user_id, ['is_done' => $show_complete_tasks]);
    }

    $page_content = include_template('main.php', [
        'projects' => $projects,
        'show_complete_tasks' => $show_complete_tasks,
        'tasks' => $project_tasks,
        'active_project_id' => $active_project_id
    ]);

} else {
    $layout_name = 'guest-layout.php';
    $page_content = include_template('guest-template.php', []);
}

$layout_content = include_template($layout_name, [
    'content' => $page_content,
    'show_complete_tasks' => $show_complete_tasks,
    'title' => 'Дела в порядке',
]);

print($layout_content);

<?php
require_once 'helpers.php';
require_once 'functions.php';

$con = mysqli_connect("localhost", "root", "", "615813-doingsdone-11");

if ($con === false) {
    exit("Ошибка подключения: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");

$user_id = 2;
$projects = getAllProjects($con, $user_id);


$page_content = include_template('add-template.php', [
    'projects' => $projects,

]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке'
]);

print($layout_content);

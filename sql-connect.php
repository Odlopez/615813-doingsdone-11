<?php
$con = mysqli_connect("localhost", "root", "", "615813-doingsdone-11");

if ($con === false) {
    exit("Ошибка подключения: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");

session_start();

if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user'];
    $user_name = $_SESSION['user_name'];
}

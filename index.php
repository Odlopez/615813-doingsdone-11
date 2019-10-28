<?php
require_once 'functions.php';
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$projects = getAllProjects();
$tasks = getAllTasks();
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Дела в порядке</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/flatpickr.min.css">
</head>

<body>
<h1 class="visually-hidden">Дела в порядке</h1>

<div class="page-wrapper">
    <div class="container container--with-sidebar">
        <header class="main-header">
            <a href="/">
                <img src="img/logo.png" width="153" height="42" alt="Логотип Дела в порядке">
            </a>

            <div class="main-header__side">
                <a class="main-header__side-item button button--plus open-modal"
                   href="pages/form-task.html">Добавить задачу</a>

                <div class="main-header__side-item user-menu">
                    <div class="user-menu__data">
                        <p>Константин</p>

                        <a href="#">Выйти</a>
                    </div>
                </div>
            </div>
        </header>

        <div class="content">
            <section class="content__side">
                <h2 class="content__side-heading">Проекты</h2>

                <nav class="main-navigation">
                    <ul class="main-navigation__list">
                        <?php foreach ($projects as $projectName) : ?>
                            <li class="main-navigation__list-item">
                                <a class="main-navigation__list-item-link" href="#"><?= $projectName ?></a>
                                <span class="main-navigation__list-item-count">0</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <a class="button button--transparent button--plus content__side-button"
                   href="pages/form-project.html" target="project_add">Добавить проект</a>
            </section>

            <main class="content__main">
                <h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="index.php" method="post" autocomplete="off">
                    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

                    <input class="search-form__submit" type="submit" name="" value="Искать">
                </form>

                <div class="tasks-controls">
                    <nav class="tasks-switch">
                        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                        <a href="/" class="tasks-switch__item">Повестка дня</a>
                        <a href="/" class="tasks-switch__item">Завтра</a>
                        <a href="/" class="tasks-switch__item">Просроченные</a>
                    </nav>

                    <label class="checkbox">
                        <input class="checkbox__input visually-hidden show_completed"
                               type="checkbox" <?= ($show_complete_tasks === 1) ? 'checked' : '' ?>>
                        <span class="checkbox__text">Показывать выполненные</span>
                    </label>
                </div>

                <table class="tasks">
                    <?php foreach ($tasks as $taskItem) : ?>
                        <?php if ($show_complete_tasks === 0 && $taskItem['isDone']) {
                            continue;
                        } ?>
                        <tr class="tasks__item task <?= $taskItem['isDone'] ? 'task--completed' : '' ?>">
                            <td class="task__select">
                                <label class="checkbox task__checkbox">
                                    <input class="checkbox__input visually-hidden task__checkbox"
                                       type="checkbox" value="<?= $taskItem['id']; ?>"
                                       <?= $taskItem['isDone'] ? 'checked' : '' ?>>
                                    <span class="checkbox__text"><?= $taskItem['task'] ?></span>
                                </label>
                            </td>

                            <td class="task__file">
                                <a class="download-link" href="#">Home.psd</a>
                            </td>

                            <td class="task__date"><?= $taskItem['date'] ?></td>
                            <td class="task__controls"></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </main>
        </div>
    </div>
</div>

<footer class="main-footer">
    <div class="container">
        <div class="main-footer__copyright">
            <p>© 2019, «Дела в порядке»</p>

            <p>Веб-приложение для удобного ведения списка дел.</p>
        </div>

        <a class="main-footer__button button button--plus" href="pages/form-task.html">Добавить задачу</a>

        <div class="main-footer__social social">
            <span class="visually-hidden">Мы в соцсетях:</span>
            <a class="social__link social__link--facebook" href="#">
                <span class="visually-hidden">Facebook</span>
                <svg  width="27" height="27" >
                    <use xlink:href="img/sprite.svg#icon-facebook"></use>
                </svg>
            </a><span class="visually-hidden">
        ,</span>
            <a class="social__link social__link--twitter" href="#">
                <span class="visually-hidden">Twitter</span>
                <svg  width="27" height="27" >
                    <use xlink:href="img/sprite.svg#icon-twitter"></use>
                </svg>
            </a><span class="visually-hidden">
        ,</span>
            <a class="social__link social__link--instagram" href="#">
                <span class="visually-hidden">Instagram</span>
                <svg  width="27" height="27" >
                    <use xlink:href="img/sprite.svg#icon-instagram"></use>
                </svg>
            </a>
            <span class="visually-hidden">,</span>
            <a class="social__link social__link--vkontakte" href="#">
                <span class="visually-hidden">Вконтакте</span>
                <svg  width="27" height="27" >
                    <use xlink:href="img/sprite.svg#icon-vkontakte"></use>
                </svg>
            </a>
        </div>

        <div class="main-footer__developed-by">
            <span class="visually-hidden">Разработано:</span>

            <a href="https://htmlacademy.ru/intensive/php">
                <img src="img/htmlacademy.svg" alt="HTML Academy" width="118" height="40">
            </a>
        </div>
    </div>
</footer>

<script src="flatpickr.js"></script>
<script src="script.js"></script>
</body>
</html>

<?php
/** @var string $title Заголовок страницы */
?>
<!DOCTYPE html>
<html lang="ru">

<head>


    <meta charset="UTF-8">
    <title><?= $title ?></title>
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
                   href="/add.php">Добавить задачу</a>

                <div class="main-header__side-item user-menu">
                    <div class="user-menu__data">
                        <p>Константин</p>

                        <a href="#">Выйти</a>
                    </div>
                </div>
            </div>
        </header>

        <div class="content">
            <?= $content ?>
        </div>
    </div>
</div>

<footer class="main-footer">
    <div class="container">
        <div class="main-footer__copyright">
            <p>© 2019, «Дела в порядке»</p>

            <p>Веб-приложение для удобного ведения списка дел.</p>
        </div>

        <a class="main-footer__button button button--plus" href="/add.php">Добавить задачу</a>

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

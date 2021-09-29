<?php

/**
 * @var $post_types<array> - получаем из базы данных, типы постов
 * @var $posts<array> - получаем из базы данных, посты пользователей
 */
require_once("db.php");
require_once("functions.php");
require_once("helpers.php");
$is_auth = rand(0, 1);
$user_name = 'Андрей'; // укажите здесь ваше имя
date_default_timezone_set('Europe/Moscow');
$posts = normalizePosts($posts);
$main = include_template("main.php", [
    "post_types" => $post_types,
    "posts" => $posts,
]);
$pagePopular = include_template(
    "layout.php",
    [
        "title" => "readme: популярное",
        "is_auth" => $is_auth,
        "user_name" => $user_name,
        "main" => $main
    ]
);
print($pagePopular);

<?php

require_once("functions.php");
require_once("helpers.php");
$is_auth = rand(0, 1);
$user_name = 'Андрей'; // укажите здесь ваше имя
date_default_timezone_set('Europe/Moscow');
$posts = [
    [
        "title" => "Цитата",
        "type" => "post-quote",
        "contain" => "Мы в жизни любим только раз, а после ищем лишь похожих",
        "user_name" => "Лариса",
        "avatar" => "userpic-larisa-small.jpg",
    ],
    [
        "title" => "Игра престолов",
        "type" => "post-text",
        "contain" => "Американский драматический сериал в жанре фэнтези, является адаптацией цикла романов «Песнь Льда и Пламени». Создатели сериала — сценаристы Дэвид Беньофф и Дэн Вайс, съёмки ведутся в Северной Ирландии, Хорватии, Исландии, Испании, Марокко и на Мальте. Всего сериал включает 73 серии, объединённые в 8 сезонов. Премьера первого сезона в США состоялась на канале HBO 17 апреля 2011 года. Сериал отмечен множеством наград. Способствовал популяризации Джорджа Мартина как писателя и придуманного им мира.",
        "user_name" => "Владик",
        "avatar" => "userpic.jpg",
    ],
    [
        "title" => "Игра престолов",
        "type" => "post-text",
        "contain" => "Не могу дождаться начала финального сезона своего любимого сериала!",
        "user_name" => "Владик",
        "avatar" => "userpic.jpg",
    ],
    [
        "title" => "Наконец, обработал фотки!",
        "type" => "post-photo",
        "contain" => "rock-medium.jpg",
        "user_name" => "Виктор",
        "avatar" => "userpic-mark.jpg",
    ],
    [
        "title" => "Моя мечта",
        "type" => "post-photo",
        "contain" => "coast-medium.jpg",
        "user_name" => "Лариса",
        "avatar" => "userpic-larisa-small.jpg",
    ],
    [
        "title" => "Лучшие курсы",
        "type" => "post-link",
        "contain" => "www.htmlacademy.ru",
        "user_name" => "Владик",
        "avatar" => "userpic.jpg",
    ],
];
$main = include_template("main.php", [
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

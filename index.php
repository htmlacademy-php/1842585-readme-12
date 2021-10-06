<?php
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

$is_auth = rand(0, 1);
$user_name = 'Андрей';
date_default_timezone_set('Europe/Moscow');
$post_types = normalizePostTypes(fetchPostTypes());
$url = $_SERVER['REQUEST_URI'];
$url = explode('?', $url);
$url = $url[0];
$current_type_id = filter_input(INPUT_GET, 'type_id', FILTER_SANITIZE_SPECIAL_CHARS);
$main = include_template("main.php", [
    "post_types" => $post_types,
    "posts" => normalizePosts(fetchPopularPosts($current_type_id), $post_types),
    "current_type_id" => $current_type_id,
]);
$pagePopular = include_template(
    "layout.php",
    [
        "title" => "readme: популярное",
        "is_auth" => $is_auth,
        "user_name" => $user_name,
        "template" => $main
    ]
);
print($pagePopular);

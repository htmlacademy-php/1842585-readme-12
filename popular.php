<?php
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

$is_auth = rand(0, 1);
$user_name = 'Андрей';
date_default_timezone_set('Europe/Moscow');
$post_types = normalizePostTypes(fetchPostTypes());
$current_type_id = filter_input(INPUT_GET, 'type_id', FILTER_SANITIZE_SPECIAL_CHARS);
$popularPosts = $current_type_id ? fetchPopularPostsByType($current_type_id) : fetchPopularPosts();
$popular = include_template("popular.php", [
    "post_types" => $post_types,
    "posts" => normalizePosts($popularPosts, $post_types),
    "current_type_id" => $current_type_id,
]);
$pagePopular = include_template(
    "layout.php",
    [
        "title" => "readme: популярное",
        "is_auth" => $is_auth,
        "user_name" => $user_name,
        "template" => $popular,
        "template_class" => "page__main--popular",
        "type_id" => getFirstTypeId($post_types),
        "current_page" => "index",
    ]
);
print($pagePopular);

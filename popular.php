<?php
/**
 * @var $connect mysqli - подключение к базе данных
 */
session_start();
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

$user = normalizeUser(getUserAuthentication());
if (count($user) === 0) {
    redirectTo("/");
}

$current_type_id = filter_input(INPUT_GET, 'type_id', FILTER_SANITIZE_SPECIAL_CHARS);
$offset = (int) (filter_input(INPUT_GET, 'type_id', FILTER_SANITIZE_SPECIAL_CHARS) ?? 0);

date_default_timezone_set('Europe/Moscow');
$post_types = normalizePostTypes(fetchPostTypes($connect));
$users_likes = getUserLikes($connect, $user["id"]);

$limit = 6;
$popularPosts = $current_type_id ? fetchPopularPostsByType($connect, $current_type_id, $offset) : fetchPopularPosts($connect, $offset);

$popular = include_template("popular.php", [
    "post_types" => $post_types,
    "posts" => normalizePosts($popularPosts, $post_types, $users_likes),
    "prev_offset" => $offset - $limit,
    "next_offset" => $offset + $limit,
    "post_count" => getPostsCount($connect)["count"],
    "current_type_id" => $current_type_id,
]);
$pagePopular = include_template(
    "layout.php",
    [
        "title" => "readme: популярное",
        "user" => $user,
        "template" => $popular,
        "template_class" => "page__main--popular",
        "type_id" => getFirstTypeId($post_types),
        "current_page" => "popular",
        "search_text" => "",
    ]
);
print($pagePopular);

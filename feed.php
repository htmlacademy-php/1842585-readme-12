<?php
/**
 * @var $connect mysqli - подключение к базе данных
 */
session_start();
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

$user = getUserAuthentication();
if (count($user) === 0) {
    redirectTo("/");
}

date_default_timezone_set('Europe/Moscow');
$post_types = normalizePostTypes(fetchPostTypes($connect));
$users_likes = getUserLikes($connect, $user["id"]);
$current_type_id = filter_input(INPUT_GET, 'type_id', FILTER_SANITIZE_SPECIAL_CHARS);
$posts = $current_type_id ? fetchPostSubscribesByType($connect, $current_type_id, $user["id"]) : fetchPostSubscribes($connect, $user["id"]);
$feed = include_template("feed.php", [
    "post_types" => $post_types,
    "posts" => normalizePosts($posts, $post_types, $users_likes),
    "current_type_id" => $current_type_id,
]);
$pageFeed = include_template(
    "layout.php",
    [
        "title" => "readme: моя лента",
        "user" => $user,
        "template" => $feed,
        "template_class" => "page__main--popular",
        "type_id" => getFirstTypeId($post_types),
        "current_page" => "feed",
        "search_text" => "",
    ]
);
print($pageFeed);

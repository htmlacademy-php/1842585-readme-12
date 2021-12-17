<?php
/**
 * @var $connect mysqli - подключение к базе данных
 */
session_start();
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");
require_once("config.php");

$user = getUserAuthentication();
if (count($user) === 0) {
    redirectTo("/");
}

$current_type_id = filter_input(INPUT_GET, 'type_id', FILTER_SANITIZE_SPECIAL_CHARS);
$offset = (int) (filter_input(INPUT_GET, 'offset', FILTER_SANITIZE_SPECIAL_CHARS) ?? 0);
$sort_field = filter_input(INPUT_GET, 'sort_field', FILTER_SANITIZE_SPECIAL_CHARS) ?? "views_count";
$sort_direction = filter_input(INPUT_GET, 'sort_direction', FILTER_SANITIZE_SPECIAL_CHARS) ?? "DESC";

if (!in_array($sort_direction, ["DESC", "ASC"])) {
    $sort_direction = "DESC";
}

$next_sort_direction = $sort_direction === "DESC" ? "ASC" : "DESC";

if (!in_array($sort_field, ["views_count", "likes_count", "created_at"])) {
    $sort_field = "views_count";
}

$post_types = normalizePostTypes(fetchPostTypes($connect));
$users_likes = getUserLikes($connect, $user["id"]);

$limit = 6;
$popularPosts = $current_type_id
    ? fetchPopularPostsByType($connect, $current_type_id, $offset, $sort_field, $sort_direction) :
    fetchPopularPosts($connect, $offset, $sort_field, $sort_direction);

$current_type_params = $current_type_id ? "type_id=" . htmlspecialchars($current_type_id) . "&" : "";
$current_offset_params = $offset === 0 ? "" : "offset=" . htmlspecialchars($offset) . "&";
$current_sort_params = "sort_field=" .
    htmlspecialchars($sort_field) . "&sort_direction=" .
    htmlspecialchars($sort_direction);

$popular = include_template("popular.php", [
    "post_types" => $post_types,
    "posts" => normalizePosts($popularPosts, $post_types, $users_likes),
    "prev_offset" => $offset - $limit,
    "next_offset" => $offset + $limit,
    "post_count" => $current_type_id ?
        getPostsCountByType($connect, $current_type_id)["count"] :
        getPostsCount($connect)["count"],
    "current_type_id" => $current_type_id,
    "sort_field" => $sort_field,
    "sort_direction" => $sort_direction,
    "next_sort_direction" => $next_sort_direction,
    "offset" => $offset,
    "current_type_params" => $current_type_params,
    "current_offset_params" => $current_offset_params,
    "current_sort_params" => $current_sort_params,
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
        "unread_count" => getAllUnreadMessages($connect, $user["id"])["count"],
    ]
);
print($pagePopular);

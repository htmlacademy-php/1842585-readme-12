<?php
session_start();
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

$user = getUserAuthentication();
$search = trim($_GET["search"] ?? "");
if ($search === "" || count($user) === 0) {
    redirectTo("/");
}

$is_tag = substr($search, 0) === "#";

$post_types = normalizePostTypes(fetchPostTypes());
$posts = $is_tag ? searchPostsByHashtag($search) : searchPosts($search);

$search_template = include_template("search.php", [
    "search" => $search,
    "posts" => normalizePosts($posts, $post_types),
]);
$search_page = include_template(
    "layout.php",
    [
        "title" => "readme: страница результатов поиска",
        "user" => $user,
        "template" => $search_template,
        "template_class" => "page__main--search-results",
        "type_id" => getFirstTypeId($post_types),
        "current_page" => "search",
        "search_text" => $search,
    ]
);

print($search_page);

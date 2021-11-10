<?php
session_start();
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

$post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_SPECIAL_CHARS);
$post_types = normalizePostTypes(fetchPostTypes());
$post = normalizePost(fetchPostById($post_id), $post_types);

if (count($post) === 0) {
    header('HTTP/1.1 404 Not Found', true, 404);
    print("Ошибка 404, пост не найден.");
    exit();
}

$user = getUserAuthentication("/");

$post_template = include_template("post.php", [
    "post" => $post,
]);
$post_page = include_template(
    "layout.php",
    [
        "title" => "readme: публикация",
        "user" => $user,
        "template" => $post_template,
        "template_class" => "page__main--publication",
        "type_id" => getFirstTypeId($post_types),
        "current_page" => "post",
    ]
);
print($post_page);

<?php
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

$is_auth = rand(0, 1);
$user_name = 'Андрей';
$post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_SPECIAL_CHARS);
$post_types = normalizePostTypes(fetchPostTypes());
$post = normalizePost(fetchPostById($post_id), $post_types);

if (count($post) === 0) {
    header('HTTP/1.1 404 Not Found', true, 404);
    print("Ошибка 404, пост не найден.");
    exit();
}

$post_template = include_template("post.php", [
    "post" => $post,
]);
$post_page = include_template(
    "layout.php",
    [
        "title" => "readme: публикация",
        "is_auth" => $is_auth,
        "user_name" => $user_name,
        "template" => $post_template
    ]
);
print($post_page);

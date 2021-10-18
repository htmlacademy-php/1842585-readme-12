<?php
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $type_id = $_POST["type_id"];

    $required_empty_filed = [
        "heading" => "Заголовок",
        "cite-text" => "Текст цитаты",
        "post-text" => "Текст поста",
        "author" => "Автор",
        "link" => "Ссылка",
        "video-url" => "Ссылка youtube",
    ];

    foreach ($required_empty_filed as $field => $title) {
        $errors = addError($errors, checkFilling($field, $title), $field);
    }

} else {
    $type_id = filter_input(INPUT_GET, 'type_id', FILTER_SANITIZE_SPECIAL_CHARS);
}

$post_types = normalizePostTypes(fetchPostTypes());
$current_post_type = $post_types[array_search($type_id, array_column($post_types, "id"), true)];

if (!isset($type_id)) {
    header('HTTP/1.1 404 Not Found', true, 404);
    print("Ошибка 404, тип поста не найден.");
    exit();
}

$is_auth = rand(0, 1);
$user_name = 'Андрей';

$part_template = include_template("/parts/add/" . $current_post_type['icon_class'] . ".php", [
]);
$add_template = include_template("add.php", [
    "post_types" => $post_types,
    "type_id" => $type_id,
    "part_template" => $part_template,
    "current_type" => $current_post_type,
]);
$add_page = include_template(
    "layout.php",
    [
        "title" => "readme: добавление",
        "is_auth" => $is_auth,
        "user_name" => $user_name,
        "template" => $add_template,
        "template_class" => "page__main--publication",
        "type_id" => $type_id,
    ]
);
print($add_page);

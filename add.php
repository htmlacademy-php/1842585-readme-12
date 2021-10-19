<?php
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $type_id = $_POST["type_id"];
    $photo_link = '/uploads/';
    $uploads_dir = __DIR__ . $photo_link;
    $content = "";

    $required_empty_filed = [
        "heading" => "Заголовок",
        "cite-text" => "Текст цитаты",
        "post-text" => "Текст поста",
        "author" => "Автор",
    ];

    foreach ($required_empty_filed as $field => $title) {
        if (isset($_POST[$field])) {
            $errors = addError($errors, checkFilling($field, $title), $field);
        }
    }

    if (isset($_POST["userpic-file-photo"]) || isset($_POST["photo-url"])) {
        $field = "userpic-file-photo";
        $file = $_FILES[$field];

        if ($file["error"] === 0) {
            if (preg_match("/image\/(jpg|jpeg|png|gif)/i", $file["type"])) {
                $file_name = basename($file["name"]);
                $file_path = $uploads_dir . $file_name;
                move_uploaded_file($file["tmp_name"], $file_path);
                $content = $photo_link . $file_name;
            } else {
                $errors = addError($errors, "Неверный формат файла", $field);
            }
        } else if (isset($_POST["photo-url"])) {
            $photo_url = filter_var($_POST["photo-url"], FILTER_VALIDATE_URL);
            $photo_info = pathinfo($photo_url);
            if ($photo_url && preg_match("/(jpg|jpeg|png|gif)/i", $photo_info['extension'])) {
                $download_photo = file_get_contents($photo_url);
                 if ($download_photo) {
                     $file_name = basename($photo_info["basename"]);
                     $file_path = $uploads_dir . basename($photo_info["basename"]);
                     file_put_contents($file_path, $download_photo);
                     $content = $photo_link . $file_name;
                 } else {
                     $errors = addError($errors, "Не удалось получить изображение по ссылке", $field);
                 }
            } else {
                $errors = addError($errors, "Неверная ссылка на изображение", $field);
            }
        } else {
            $errors = addError($errors, "Не удалось загрузить изображение", $field);
        }

    }

    if (isset($_POST["link"])) {
        $field = "link";
        $link_url = filter_var($_POST[$field], FILTER_VALIDATE_URL);
        if ($link_url) {
            $content = $link_url;
        } else {
            $errors = addError($errors, "Неверная ссылка", $field);
        }
    }

    if (isset($_POST["video-url"])) {
        $field = "video-url";
        $video_url = filter_var($_POST[$field], FILTER_VALIDATE_URL);
        if ($video_url) {
            $result = check_youtube_url($video_url);
            if ($result === true) {
                $content = $video_url;
            } else {
                $errors = addError($errors, $result, $field);
            }
        } else {
            $errors = addError($errors, "Неверная ссылка на видео", $field);
        }
    }

    if (isset($_POST["tags"]) && $_POST["tags"] !== "") {
        $field = "tags";
        $tags = explode(" ", $_POST["tags"]);
        $errors = checkTags("/^#[A-Za-zА-Яа-яËё0-9]{1,19}$/", $tags, $errors);
    }

    if (count($errors) === 0) {

    } else if (isset($file_path)) {
        unlink($file_path);
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

$errors_template = include_template("/parts/add/errors.php", [
    "errors" => $errors,
]);
$part_template = include_template("/parts/add/" . $current_post_type['icon_class'] . ".php", [
    "errors" => $errors,
    "errors_template" => $errors_template,
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

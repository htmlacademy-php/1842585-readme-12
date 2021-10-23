<?php

require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_id = $_POST["type_id"];
    $uploads_dir = '/uploads/';
    $full_path = __DIR__ . $uploads_dir;
    $tags = [];
    $result = [
        "content" => "",
        "author" => "",
        "picture_url" => "",
        "video_url" => "",
        "website" => "",
    ];
    $post_fields = [
        1 => [
            "author" => "author",
            "content" => "cite-text",
        ],
        2 => [
            "website" => "link",
        ],
        3 => [
            "picture_file" => "userpic-file-photo",
            "picture_url" => "photo-url",
        ],
        4 => [
            "video-url" => "video-url",
        ],
        5 => [
            "content" => "post-text",
        ],
    ];
    $current_post_fields = $post_fields[(int) $type_id];
    $required_empty_filed = [
        "heading" => "Заголовок",
        "cite-text" => "Текст цитаты",
        "post-text" => "Текст поста",
        "author" => "Автор",
    ];

    $title = $_POST['heading'] ?? "";
    $errors = addError($errors, checkFilling("heading", $required_empty_filed["heading"]), "heading");

    foreach ($current_post_fields as $field => $web_name) {
        if ($field === "picture_file") {
            if ($_FILES[$web_name]["error"] !== 0) {
                continue;
            }

            $picture = $_FILES[$web_name];
            $errors = addError($errors, checkPictureType("/image\/(jpg|jpeg|png|gif)/i", $picture["type"]), $web_name);
            $file_path = getFilePath($full_path, $picture["name"]);
            $result["content"] = downloadContent($file_path, $uploads_dir, $picture["name"], $picture["tmp_name"], $errors);
        } else if ($field === "picture_url") {
            if ($result["content"] !== "") {
                continue;
            }

            $picture_url = filter_var($_POST[$web_name], FILTER_VALIDATE_URL);
            $photo_info = pathinfo($picture_url);
            $errors = addError($errors, checkPictureType("/(jpg|jpeg|png|gif)/i", $photo_info["extension"]), $web_name);
            $download_photo = file_get_contents($picture_url);
            $file_path = getFilePath($full_path, $photo_info["basename"]);
            $result["content"] = downloadContent($file_path, $uploads_dir, $photo_info["basename"], $download_photo, $errors);
            $result["picture_url"] = $picture_url;
        } else if ($field === "website") {
            $website = $_POST[$web_name];
            $errors = addError($errors, checkURL($website), $web_name);
            $result["content"] = getValidateURL($website, $errors);
            $result["website"] = $result["content"];
        } else if ($field === "video-url") {
            $video_url = $_POST[$web_name];
            $errors = addError($errors, checkYoutubeURL($video_url), $web_name);
            $result["content"] = getValidateURL($video_url, $errors);
            $result["video_url"] = $result["content"];
        } else {
            $errors = addError($errors, checkFilling($web_name, $required_empty_filed[$web_name]), $web_name);
            $result[$field] = $_POST[$web_name] ?? "";
        }
    }

    if (isset($_POST["tags"]) && $_POST["tags"] !== "") {
        $field = "tags";
        $tags = explode(" ", $_POST["tags"]);
        $errors = checkTags("/^#[A-Za-zА-Яа-яËё0-9]{1,19}$/", $tags, $errors);
    }

    if (count($errors) === 0) {
        $created_at = (new DateTime('NOW'))->format('Y-m-d-H-i-s');
        $result = addPost([$created_at, $title, $result["content"], $result["author"], $result["picture_url"], $result["video_url"], $result["website"], 1, $type_id]);

        foreach ($tags as $tag) {
            $current_tag = getTagByName($tag);
            $tag_id = count($current_tag) === 0 ? addNewTag([$tag]) : $current_tag["id"];
            addPostTag([(int) $result, (int) $tag_id]);
        }

        $new_url = $_SERVER['HTTP_ORIGIN'] . "/post.php?post_id=$result";
        header("Location: $new_url");
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

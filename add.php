<?php
/**
 * @var $connect mysqli - подключение к базе данных
 * @var $mailer
 */
session_start();
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");
require_once("config.php");
require_once("mailer.php");
require_once("mail-templates.php");

$user = getUserAuthentication();
if (count($user) === 0) {
    redirectTo("/");
}

$errors = [];
$result = [
    "title" => "",
    "content" => "",
    "author" => "",
    "picture_url" => "",
    "tmp_path" => "",
    "file_name" => "",
    "video_url" => "",
    "website" => "",
    "tags" => [],
    "errors" => [],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_id = $_POST["type_id"];
    $uploads_dir = '/uploads/';
    $full_path = __DIR__ . $uploads_dir;
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
            "video_url" => "video-url",
        ],
        5 => [
            "content" => "post-text",
        ],
    ];
    $current_post_fields = $post_fields[(int)$type_id];
    $required_empty_filed = [
        "heading" => "Заголовок",
        "cite-text" => "Текст цитаты",
        "post-text" => "Текст поста",
        "author" => "Автор",
    ];

    $result["title"] = $_POST['heading'] ?? "";
    $result["errors"] = addError(
        $result["errors"],
        checkFilling("heading", $required_empty_filed["heading"]),
        "heading"
    );
    $result["errors"] = addError(
        $result["errors"],
        checkLength($result["title"], $required_empty_filed["heading"], 200),
        "heading"
    );

    foreach ($current_post_fields as $field => $web_name) {
        $result = match ($field) {
            "picture_file" => addPictureFile($web_name, "content", $result, $uploads_dir),
            "picture_url" => addPictureURL($web_name, $result, $uploads_dir),
            "website" => addWebsite($web_name, $result, $field),
            "video_url" => addVideoURL($web_name, $result, $field),
            default => addTextContent($web_name, $result, $field, $required_empty_filed),
        };
    }

    $result = addTags("tags", $result);
    $errors = $result["errors"];

    if (count($errors) === 0) {
        $created_at = (new DateTime('NOW'))->format('Y-m-d-H-i-s');
        downloadFile($result["tmp_path"], $full_path, $result["file_name"]);
        downloadContent($result["picture_url"], $full_path, $result["file_name"]);
        $new_post_id = addPost(
            $connect,
            [
                $created_at,
                $result["title"],
                $result["content"],
                $result["author"] ?? $user["user_name"],
                $result["picture_url"],
                $result["video_url"],
                $result["website"],
                $user["id"],
                $type_id,
                null,
                null,
            ]
        );

        foreach ($result["tags"] as $tag) {
            $current_tag = getTagByName($connect, $tag);
            $tag_id = count($current_tag) === 0 ? addNewTag($connect, [$tag]) : $current_tag["id"];
            addPostTag($connect, [(int)$new_post_id, (int)$tag_id]);
        }

        $subscribers = getSubscribersByUserId($connect, $user["id"]);

        foreach ($subscribers as $subscriber) {
            sendEmail(
                $mailer,
                $user["email"],
                getPublicationSubject($user["user_name"]),
                $subscriber["email"],
                getPublicationTextTemplate($user["user_name"], $user["id"], $result["title"], $subscriber["login"])
            );
        }

        redirectTo("/post.php?post_id=$new_post_id");
    }
} else {
    $type_id = filter_input(INPUT_GET, 'type_id', FILTER_SANITIZE_SPECIAL_CHARS);
}

$post_types = normalizePostTypes(fetchPostTypes($connect));
$current_post_type = $post_types[array_search($type_id, array_column($post_types, "id"), true)];

if (!isset($type_id)) {
    header('HTTP/1.1 404 Not Found', true, 404);
    print("Ошибка 404, тип поста не найден.");
    exit();
}

$errors_template = include_template("/parts/add/errors.php", [
    "errors" => $errors,
]);
$part_template = include_template("/parts/add/" . $current_post_type['icon_class'] . ".php", [
    "result" => $result,
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
        "user" => $user,
        "template" => $add_template,
        "template_class" => "page__main--publication",
        "type_id" => $type_id,
        "current_page" => "add",
        "search_text" => "",
        "unread_count" => getAllUnreadMessages($connect, $user["id"])["count"],
    ]
);
print($add_page);

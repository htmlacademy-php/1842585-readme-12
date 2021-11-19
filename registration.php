<?php
/**
 * @var $connect mysqli - подключение к базе данных
 */
session_start();
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

if (count(getUserAuthentication()) > 0) {
    redirectTo("/");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $uploads_dir = '/uploads/';
    $full_path = __DIR__ . $uploads_dir;
    $result = [
        "email" => "",
        "login" => "",
        "password" => "",
        "avatar_path" => "",
        "errors" => [],
    ];
    $reg_fields = [
        "email" => "email",
        "login" => "login",
        "password" => "password",
        "password-repeat" => "password-repeat",
        "avatar_path" => "userpic-file",
    ];

    foreach ($reg_fields as $field => $web_name) {
        switch ($field) {
            case "email": {
                $result = addEmail($field, $web_name, $result);
                break;
            }
            case "login": {
                $result = addLogin($field, $web_name, $result);
                break;
            }
            case "password": {
                $result = addPassword($field, $web_name, $result);
                break;
            }
            case "password-repeat": {
                $result = addPasswordRepeat($web_name, $result);
                break;
            }
            case "avatar_path": {
                $result = addPictureFile($web_name, "avatar_path", $result, $uploads_dir);
                break;
            }
        }
    }

    $errors = $result["errors"];

    if (count($errors) === 0) {
        $registered_at = (new DateTime('NOW'))->format('Y-m-d-H-i-s');
        downloadFile($result["tmp_path"], $full_path, $result["file_name"]);
        $new_post_id = addUser(
            $connect,
            [
                $registered_at,
                $result["email"],
                $result["login"],
                $result["password"],
                $result["avatar_path"],
            ]
        );

        redirectTo("/");
    }
}

$errors_template = include_template("/parts/add/errors.php", [
    "errors" => $errors,
]);
$reg_page = include_template("registration.php", [
    "title" => "readme: регистрация",
    "is_auth" => false,
    "user_name" => "",
    "template_class" => "page__main--registration",
    "type_id" => getFirstTypeId(normalizePostTypes(fetchPostTypes($connect))),
    "current_page" => "registration",
    "errors" => $errors,
    "errors_template" => $errors_template,
]);
print($reg_page);

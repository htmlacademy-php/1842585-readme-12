<?php
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

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
                $result = addPictureFile($web_name, $result);
                break;
            }
        }
    }

    $result = downloadPictureFile("avatar_path", $full_path, $uploads_dir, "userpic-file", $result);
    $errors = $result["errors"];

    if (count($errors) === 0) {
        $registered_at = (new DateTime('NOW'))->format('Y-m-d-H-i-s');
        $new_post_id = addUser(
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
$reg_template = include_template("registration.php", [
    "errors" => $errors,
    "errors_template" => $errors_template,
]);
$reg_page = include_template(
    "layout.php",
    [
        "title" => "readme: регистрация",
        "is_auth" => false,
        "user_name" => "",
        "template" => $reg_template,
        "template_class" => "page__main--registration",
        "type_id" => getFirstTypeId(normalizePostTypes(fetchPostTypes())),
        "current_page" => "registration",
    ]
);
print($reg_page);

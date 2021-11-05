<?php
session_start();
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = getUserByLoginOrEmail($_POST['login']);

    if (count($user) === 0) {
        $errors["login"] = true;
    } else if (!password_verify($_POST['password'], $user["password"])) {
        $errors["password"] = true;
    } else {
        $_SESSION["user"] = [
            "id" => $user["id"],
            "registered_at" => $user["registered_at"],
            "email" => $user["email"],
            "login" => $user["login"],
            "avatar_path" => $user["avatar_path"],
        ];
    }
}

if (isset($_SESSION["user"])) {
    redirectTo("/feed.php");
    exit();
}

$main = include_template("main.php", [
    "errors" => $errors,
]);

print($main);

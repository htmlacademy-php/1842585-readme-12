<?php
/**
 * @var $connect mysqli - подключение к базе данных
 */
session_start();
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");
require_once("config.php");

$errors = [];
$login = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $user = getUserByLoginOrEmail($connect, $login);

    if (count($user) === 0) {
        $errors["login"] = true;
    } elseif (!password_verify($_POST['password'], $user["password"])) {
        $errors["password"] = true;
    } else {
        $_SESSION["user"] = normalizeUser($user);
    }
}

if (count(getUserAuthentication()) > 0) {
    redirectTo("/");
}

$main = include_template("main.php", [
    "login" => $login,
    "errors" => $errors,
]);

print($main);

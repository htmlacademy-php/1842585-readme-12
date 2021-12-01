<?php
/**
 * @var $connect mysqli - подключение к базе данных
 * @var $mailer
 */
session_start();
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");
require_once("mailer.php");

$user = getUserAuthentication();
if (count($user) === 0) {
    redirectTo("/");
}

$referer = "/";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author_id = $_REQUEST["author_id"] ?? "";
    $author = getUserById($connect, $author_id);
    $subscription = getSubscription($connect, $user["id"], $author_id);
    $referer = $_SERVER["HTTP_REFERER"];

    if ($author_id === $user["id"] || count($author) === 0) {
        redirectTo("/");
    }

    mysqli_begin_transaction($connect);

    $current_data = (new DateTime('NOW'))->format('Y-m-d-H-i-s');
    if (count($subscription) === 0) {
        $result = addSubscription(
            $connect,
            [
                $author_id,
                $user["id"],
                $current_data,
            ]
        );

        $message_id = addMessage(
            $connect,
            [
                $current_data,
                "подписался на вас",
                $user["id"],
                $author_id,
            ]
        );

        $emailText = "<h4>Здравствуйте, " . $author["login"] . "</h4>
        <p>На вас подписался новый пользователь " . $user["user_name"] . "</p>
        <p>Вот ссылка на его профиль: <a href=" . getProfileLink($user["id"]) . ">" . $user["user_name"] . "</a></p>";

        sendEmail($mailer, $user["email"], "У вас новый подписчик", $author["email"], $emailText);
    }
    else {
        $result = deleteSubscription($connect, $subscription["id"]);
        $message_id = 1;
    }

    if ($result && $message_id) {
        mysqli_commit($connect);
    }
    else {
        mysqli_rollback($connect);
    }
}

redirectTo($referer);

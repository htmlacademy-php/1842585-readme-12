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

        sendEmail(
            $mailer,
            $user["email"],
            getSubscriptionSubject(),
            $author["email"],
            getSubscriptionTextTemplate($author["login"], $user["user_name"], $user["id"])
        );
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

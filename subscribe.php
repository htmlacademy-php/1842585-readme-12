<?php
/**
 * @var $connect mysqli - подключение к базе данных
 */
session_start();
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

$user = normalizeUser(getUserAuthentication());
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
        $text_message = "подписался на вас";
    }
    else {
        $result = deleteSubscription($connect, $subscription["id"]);
        $text_message = "отписался от вас";
    }

    $message_id = addMessage(
        $connect,
        [
            $current_data,
            $text_message,
            $user["id"],
            $author_id,
        ]
    );

    if ($result && $message_id) {
        mysqli_commit($connect);
    }
    else {
        mysqli_rollback($connect);
    }
}

redirectTo($referer);

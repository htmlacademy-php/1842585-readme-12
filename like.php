<?php
/**
 * @var $connect mysqli - подключение к базе данных
 */
session_start();
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");
require_once("config.php");

$user = getUserAuthentication();
if (count($user) === 0) {
    redirectTo("/");
}

$referer = $_SERVER["HTTP_REFERER"];
$post_id = $_REQUEST["post_id"] ?? "";
$author_id = $_REQUEST["author_id"] ?? "";
$post = fetchPostById($connect, $post_id);
$like = getUserLike($connect, $user["id"], $post_id);

if ($author_id === "" || count($post) === 0) {
    redirectTo($referer);
}

$current_data = (new DateTime('NOW'))->format('Y-m-d-H-i-s');
if (count($like) === 0) {
    mysqli_begin_transaction($connect);

    $result = addLike(
        $connect,
        [
            $user["id"],
            $post_id,
            $current_data,
        ]
    );

    if ($user["id"] === $author_id) {
        $message_id = true;
    } else {
        $message_id = addMessage(
            $connect,
            [
                $current_data,
                "поставил вам лайк",
                $user["id"],
                $author_id,
            ]
        );
    }

    if ($result && $message_id) {
        mysqli_commit($connect);
    }
    else {
        mysqli_rollback($connect);
    }
}
else {
    $result = deleteLike($connect, $like["id"]);
}

redirectTo($referer);

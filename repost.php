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

$post_id = $_REQUEST["post_id"] ?? "";
$post = fetchPostById($connect, $post_id);

if (count($post) > 0) {
    $repostPost = getPostsRepost($connect, $user["id"], $post["user_id"], $post["id"]);
    if ($user["id"] === (string) $post["user_id"] || count($repostPost) > 0) {
        redirectTo($_SERVER["HTTP_REFERER"]);
    }

    $created_at = (new DateTime('NOW'))->format('Y-m-d-H-i-s');

    $new_post_id = addPost(
        $connect,
        [
            $created_at,
            $post["title"],
            $post["content"],
            $post["author"],
            $post["picture_url"],
            $post["video_url"],
            $post["website"],
            $user["id"],
            $post["type_id"],
            $post["id"],
            $post["user_id"],
        ]
    );
}

redirectTo("/");

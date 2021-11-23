<?php
/**
 * @var $connect mysqli - подключение к базе данных
 */
session_start();
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

$user = getUserAuthentication();
if (count($user) === 0) {
    redirectTo("/");
}

$post_types = normalizePostTypes(fetchPostTypes($connect));
$users_likes = getUserLikes($connect, $user["id"]);
$post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_SPECIAL_CHARS) ?? $_POST["post_id"];
if ($post_id !== "" && $_SERVER['REQUEST_METHOD'] === 'GET' && !in_array($post_id, $user["posts_viewed"], true)) {
    updatePostViews($connect, $post_id);
    $_SESSION['user']["posts_viewed"][] = $post_id;
}
$show_all_comments = (bool) filter_input(INPUT_GET, 'show_all_comments', FILTER_SANITIZE_SPECIAL_CHARS);
$post = normalizePost(fetchPostById($connect, $post_id), $post_types, $users_likes);
$errors = [];

if (count($post) === 0) {
    header('HTTP/1.1 404 Not Found', true, 404);
    print("Ошибка 404, пост не найден.");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = trim($_POST["comment"]);

    if ($comment !== "") {
        $id_comment = addComment(
            $connect,
            [
                (new DateTime('NOW'))->format('Y-m-d-H-i-s'),
                $comment,
                $user["id"],
                $post_id,
            ]
        );

        if ($id_comment) {
            redirectTo("/profile.php?author_id=" . $post["user_id"]);
        }
    } else {
        $errors[] = "Поле комментарий должно быть заполнено!";
    }
}

$author = normalizeUser(getUserById($connect, $post["user_id"]));
$hashtags = normalizeHashtags(getPostTags($connect, $post["id"]));

$user_profile = include_template("/parts/user/profile.php", [
    "user_id" => $author["id"],
    "avatar" => $author["avatar"],
    "user_name" => $author["user_name"],
    "registered_date" => $author["registered_date"],
    "date_title" => $author["date_title"],
    "time_ago" => $author["time_ago"],
    "template_class" => "post-details",
]);

$user_info = include_template("/parts/user/info.php", [
    "posts_count" => $author["posts_count"],
    "subscribers_count" => $author["subscribers_count"],
    "template_class" => "post-details",
    "author_id" => $author["id"],
    "is_subscribe" => count(getSubscription($connect, $user["id"], $author["id"])) > 0,
    "is_current_user" => $user["id"] === $author["id"],
]);

$post_template = include_template("post.php", [
    "post" => $post,
    "user" => $user,
    "user_profile" => $user_profile,
    "user_info" => $user_info,
    "comments" => $show_all_comments ? normalizeComments(getCommentsByPostId($connect, $post_id)) : normalizeComments(getTwoCommentsByPostId($connect, $post_id)),
    "show_all_comments" => $show_all_comments,
    "hashtags" => $hashtags,
    "errors" => $errors,
]);

$post_page = include_template(
    "layout.php",
    [
        "title" => "readme: публикация",
        "user" => $user,
        "template" => $post_template,
        "template_class" => "page__main--publication",
        "type_id" => getFirstTypeId($post_types),
        "current_page" => "post",
        "search_text" => "",
    ]
);
print($post_page);

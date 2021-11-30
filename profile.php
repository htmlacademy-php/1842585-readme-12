<?php
/**
 * @var $connect mysqli - подключение к базе данных
 */
session_start();
require_once("db.php");
require_once("helpers.php");
require_once("functions.php");

$user = getUserAuthentication();
$author_id = filter_input(INPUT_GET, 'author_id', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
if (!$author_id || count($user) === 0) {
    redirectTo("/");
}

$tab = (string) (filter_input(INPUT_GET, 'tab', FILTER_SANITIZE_SPECIAL_CHARS) ?? "posts");
$users_likes = getUserLikes($connect, $user["id"]);
$post_types = normalizePostTypes(fetchPostTypes($connect));
$hashtags = [];

switch($tab) {
    case "posts": {
        $content = normalizePosts(getPostsByUserId($connect, $author_id), $post_types, $users_likes);
        $hashtags = normalizeHashtags(getPostsTags($connect));
        break;
    }
    case "likes": {
        $content = normalizeLikes(getLikesByUserId($connect, $author_id), $post_types);
        break;
    }
    case "subscriptions": {
        $content = normalizeUsers(getSubscribersByUserId($connect, $author_id));
        break;
    }
    default: {
        $content = [];
    }
}

$tab_content = include_template("/parts/profile/$tab.php", [
    "content" => $content,
    "hashtags" => $hashtags,
    "current_user_id" => $user["id"],
    "current_user_subscriptions" => normalizeSubscriptions(getSubscriptionsByUserId($connect, $user["id"])),
]);

$user_info = include_template("/parts/user/info.php", [
    "posts_count" => getPostsCountByUserId($connect, $author_id)["count"],
    "subscribers_count" => getSubscribersCountByUserId($connect, $author_id)["count"],
    "template_class" => "profile",
    "author_id" => $author_id,
    "is_subscribe" => count(getSubscription($connect, $user["id"], $author_id)) > 0,
    "is_current_user" => $user["id"] === $author_id,
]);

$profile = include_template("profile.php", [
    "author" => normalizeUser(getUserById($connect, $author_id)),
    "tab" => $tab,
    "tab_content" => $tab_content,
    "user_info" => $user_info,
]);

$page_profile = include_template(
    "layout.php",
    [
        "title" => "readme: профиль",
        "user" => $user,
        "template" => $profile,
        "template_class" => "page__main--profile",
        "type_id" => getFirstTypeId($post_types),
        "current_page" => "profile",
        "search_text" => "",
        "unread_count" => getAllUnreadMessages($connect, $user["id"])["count"],
    ]
);

print($page_profile);

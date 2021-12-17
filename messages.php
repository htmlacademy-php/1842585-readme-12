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

$error = "";
$recipient_id = (string) (filter_input(INPUT_GET, 'recipient_id', FILTER_SANITIZE_SPECIAL_CHARS) ?? "");
if ($recipient_id !== "" && $recipient_id !== $user["id"] && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $text_message = trim($_POST["message"]);

    if ($text_message !== "") {
        $message_id = addMessage(
            $connect,
            [
                (new DateTime('NOW'))->format('Y-m-d-H-i-s'),
                $text_message,
                $user["id"],
                $recipient_id,
            ]
        );
    } else {
        $error = "Поле сообщение должно быть заполнено!";
    }
}

$post_types = normalizePostTypes(fetchPostTypes($connect));
$messages = normalizeMessages(getMessagesByRecipient($connect, $user["id"], $recipient_id));
$current_recipient = normalizeUser(getUserById($connect, $recipient_id));

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    foreach ($messages as $message) {
        if ($message["recipient_id"] === $user["id"]) {
            updateStatusMessage($connect, $message["id"]);
        }
    }
}

$messages_recipients = normalizeMessages(getLastMessagesEveryRecipient($connect, $user["id"]));

$messages_template = include_template("messages.php", [
    "current_recipient" => $current_recipient,
    "is_empty_messages" => count($messages) === 0,
    "messages" => $messages,
    "messages_recipients" => $messages_recipients,
    "current_user" => $user,
    "error" => $error,
    "recipient_id" => $recipient_id,
]);

$page_profile = include_template(
    "layout.php",
    [
        "title" => "readme: сообщения",
        "user" => $user,
        "template" => $messages_template,
        "template_class" => "page__main--messages",
        "type_id" => getFirstTypeId($post_types),
        "current_page" => "profile",
        "search_text" => "",
        "unread_count" => getAllUnreadMessages($connect, $user["id"])["count"],
    ]
);

print($page_profile);

<?php

use JetBrains\PhpStorm\Pure;

function getProfileLink(string $user_id): string
{
    return $_SERVER["HTTP_ORIGIN"] . "/profile.php?author_id=" . htmlspecialchars($user_id);
}

function getPublicationSubject($author): string
{
    return "Новая публикация от пользователя " . $author;
}

#[Pure] function getPublicationTextTemplate($author, $author_id, $title, $subscriber): string
{
    return "<h4>Здравствуйте, " . $subscriber . ".</h4>
        <p>Пользователь " . $author . " только что опубликовал новую запись " . $title . ".</p>
        <p>Посмотрите её на странице пользователя:
            <a href=" . getProfileLink($author_id) . ">" . $author . "</a>
        </p>";
}

function getSubscriptionSubject(): string
{
    return "У вас новый подписчик";
}

#[Pure] function getSubscriptionTextTemplate($author, $subscriber, $subscriber_id): string
{
    return "<h4>Здравствуйте, " . $author . "</h4>
        <p>На вас подписался новый пользователь " . $subscriber . "</p>
        <p>Вот ссылка на его профиль:
            <a href=" . getProfileLink($subscriber_id) . ">" . $subscriber . "</a>
        </p>";
}

<?php
/**
 * Шаблон информации о пользователе
 * @var $user_id string - лайки постов пользователя
 * @var $avatar string - путь до аватара пользователя
 * @var $user_name string - имя пользователя
 * @var $registered_date DateTime - время регистрации пользователя
 * @var $date_title string - время регистрации в читаемом формате
 * @var $time_ago string - время прошедшее со дня регистрации
 * @var $template_class string - класс для шаблона
 */

?>

<div class="<?= htmlspecialchars($template_class) ?>__user-info user__info">
    <div class="<?= htmlspecialchars($template_class) ?>__avatar user__avatar">
        <a class="user__avatar-link" href="/profile.php?author_id=<?= htmlspecialchars($user_id) ?>">
            <img
                class="<?= htmlspecialchars($template_class) ?>__picture user__picture"
                src="<?= htmlspecialchars($avatar) ?>"
                alt="Аватар пользователя"
            >
        </a>
    </div>
    <div class="<?= htmlspecialchars($template_class) ?>__name-wrapper user__name-wrapper">
        <a
            class="<?= htmlspecialchars($template_class) ?>__name user__name"
            href="/profile.php?author_id=<?= htmlspecialchars($user_id) ?>"
        >
            <span><?= htmlspecialchars($user_name) ?></span>
        </a>
        <time
            class="<?= htmlspecialchars($template_class) ?>__time user__additional"
            title="<?= htmlspecialchars($date_title) ?>"
            datetime="<?= htmlspecialchars($registered_date) ?>"
        >
            <?= htmlspecialchars($time_ago) ?>
        </time>
    </div>
</div>

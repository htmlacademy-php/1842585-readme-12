<?php

/**
 * Шаблон профиля пользователя
 * @var $author array - пользователь текущего профиля
 * @var $tab string - имя выбранной вкладки
 * @var $tab_content string - содержимое выбранной вкладки
 * @var $user_info string - шаблон информации о пользователе
 */

?>
<h1 class="visually-hidden">Профиль</h1>
<div class="profile profile--default">
    <div class="profile__user-wrapper">
        <div class="profile__user user container">
            <div class="profile__user-info user__info">
                <div class="profile__avatar user__avatar">
                    <img class="profile__picture user__picture" src="<?= htmlspecialchars($author["avatar"]) ?>"
                         alt="Аватар пользователя">
                </div>
                <div class="profile__name-wrapper user__name-wrapper">
                    <span class="profile__name user__name"><?= htmlspecialchars($author["user_name"]) ?></span>
                    <time class="profile__user-time user__time"
                          datetime="<?= htmlspecialchars($author["date_title"]) ?>"><?= htmlspecialchars(
                              $author["time_ago"]
                          ) ?></time>
                </div>
            </div>
            <?php print($user_info) ?>
        </div>
    </div>
    <div class="profile__tabs-wrapper tabs">
        <div class="container">
            <div class="profile__tabs filters">
                <b class="profile__tabs-caption filters__caption">Показать:</b>
                <ul class="profile__tabs-list filters__list tabs__list">
                    <li class="profile__tabs-item filters__item">
                        <a class="profile__tabs-link filters__button tabs__item <?= $tab === "posts" ? "filters__button--active tabs__item--active" : "" ?> button"
                           href="/profile.php?author_id=<?= htmlspecialchars($author["id"]) ?>">Посты</a>
                    </li>
                    <li class="profile__tabs-item filters__item">
                        <a class="profile__tabs-link filters__button tabs__item <?= $tab === "likes" ? "filters__button--active tabs__item--active" : "" ?> button"
                           href="/profile.php?author_id=<?= htmlspecialchars($author["id"]) ?>&tab=likes">Лайки</a>
                    </li>
                    <li class="profile__tabs-item filters__item">
                        <a class="profile__tabs-link filters__button tabs__item <?= $tab === "subscriptions" ? "filters__button--active tabs__item--active" : "" ?> button"
                           href="/profile.php?author_id=<?= htmlspecialchars($author["id"]) ?>&tab=subscriptions">Подписки</a>
                    </li>
                </ul>
            </div>
            <div class="profile__tab-content">
                <?php print($tab_content) ?>
            </div>
        </div>
    </div>
</div>

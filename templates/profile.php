<?php

/**
 * Шаблон профиля пользователя
 * @var $author array{id: string, email: string, user_name: string, avatar: string, registered_date: DateTime, time_ago: string, date_title: string} - пользователь текущего профиля
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
                    <img class="profile__picture user__picture" src="<?= htmlspecialchars($author["avatar"]) ?>" alt="Аватар пользователя">
                </div>
                <div class="profile__name-wrapper user__name-wrapper">
                    <span class="profile__name user__name"><?= htmlspecialchars($author["user_name"]) ?></span>
                    <time class="profile__user-time user__time" datetime="<?= htmlspecialchars($author["date_title"]) ?>"><?= htmlspecialchars($author["time_ago"]) ?></time>
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
                        <a class="profile__tabs-link filters__button tabs__item <?= $tab === "posts" ? "filters__button--active tabs__item--active" : "" ?> button" href="/profile.php?author_id=<?= htmlspecialchars($author["id"]) ?>">Посты</a>
                    </li>
                    <li class="profile__tabs-item filters__item">
                        <a class="profile__tabs-link filters__button tabs__item <?= $tab === "likes" ? "filters__button--active tabs__item--active" : "" ?> button" href="/profile.php?author_id=<?= htmlspecialchars($author["id"]) ?>&tab=likes">Лайки</a>
                    </li>
                    <li class="profile__tabs-item filters__item">
                        <a class="profile__tabs-link filters__button tabs__item <?= $tab === "subscriptions" ? "filters__button--active tabs__item--active" : "" ?> button" href="/profile.php?author_id=<?= htmlspecialchars($author["id"]) ?>&tab=subscriptions">Подписки</a>
                    </li>
                </ul>
            </div>
            <div class="profile__tab-content">
                <?php print($tab_content) ?>

                <section class="profile__subscriptions tabs__content">
                    <h2 class="visually-hidden">Подписки</h2>
                    <ul class="profile__subscriptions-list">
                        <li class="post-mini post-mini--photo post user">
                            <div class="post-mini__user-info user__info">
                                <div class="post-mini__avatar user__avatar">
                                    <a class="user__avatar-link" href="#">
                                        <img class="post-mini__picture user__picture" src="img/userpic-petro.jpg"
                                             alt="Аватар пользователя">
                                    </a>
                                </div>
                                <div class="post-mini__name-wrapper user__name-wrapper">
                                    <a class="post-mini__name user__name" href="#">
                                        <span>Петр Демин</span>
                                    </a>
                                    <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 лет на
                                        сайте
                                    </time>
                                </div>
                            </div>
                            <div class="post-mini__rating user__rating">
                                <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                    <span class="post-mini__rating-amount user__rating-amount">556</span>
                                    <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                </p>
                                <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                    <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                    <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                </p>
                            </div>
                            <div class="post-mini__user-buttons user__buttons">
                                <button
                                    class="post-mini__user-button user__button user__button--subscription button button--main"
                                    type="button">Подписаться
                                </button>
                            </div>
                        </li>
                        <li class="post-mini post-mini--photo post user">
                            <div class="post-mini__user-info user__info">
                                <div class="post-mini__avatar user__avatar">
                                    <a class="user__avatar-link" href="#">
                                        <img class="post-mini__picture user__picture" src="img/userpic-petro.jpg"
                                             alt="Аватар пользователя">
                                    </a>
                                </div>
                                <div class="post-mini__name-wrapper user__name-wrapper">
                                    <a class="post-mini__name user__name" href="#">
                                        <span>Петр Демин</span>
                                    </a>
                                    <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 лет на
                                        сайте
                                    </time>
                                </div>
                            </div>
                            <div class="post-mini__rating user__rating">
                                <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                    <span class="post-mini__rating-amount user__rating-amount">556</span>
                                    <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                </p>
                                <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                    <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                    <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                </p>
                            </div>
                            <div class="post-mini__user-buttons user__buttons">
                                <button
                                    class="post-mini__user-button user__button user__button--subscription button button--quartz"
                                    type="button">Отписаться
                                </button>
                            </div>
                        </li>
                        <li class="post-mini post-mini--photo post user">
                            <div class="post-mini__user-info user__info">
                                <div class="post-mini__avatar user__avatar">
                                    <a class="user__avatar-link" href="#">
                                        <img class="post-mini__picture user__picture" src="img/userpic-petro.jpg"
                                             alt="Аватар пользователя">
                                    </a>
                                </div>
                                <div class="post-mini__name-wrapper user__name-wrapper">
                                    <a class="post-mini__name user__name" href="#">
                                        <span>Петр Демин</span>
                                    </a>
                                    <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 лет на
                                        сайте
                                    </time>
                                </div>
                            </div>
                            <div class="post-mini__rating user__rating">
                                <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                    <span class="post-mini__rating-amount user__rating-amount">556</span>
                                    <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                </p>
                                <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                    <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                    <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                </p>
                            </div>
                            <div class="post-mini__user-buttons user__buttons">
                                <button
                                    class="post-mini__user-button user__button user__button--subscription button button--main"
                                    type="button">Подписаться
                                </button>
                            </div>
                        </li>
                        <li class="post-mini post-mini--photo post user">
                            <div class="post-mini__user-info user__info">
                                <div class="post-mini__avatar user__avatar">
                                    <a class="user__avatar-link" href="#">
                                        <img class="post-mini__picture user__picture" src="img/userpic-petro.jpg"
                                             alt="Аватар пользователя">
                                    </a>
                                </div>
                                <div class="post-mini__name-wrapper user__name-wrapper">
                                    <a class="post-mini__name user__name" href="#">
                                        <span>Петр Демин</span>
                                    </a>
                                    <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 лет на
                                        сайте
                                    </time>
                                </div>
                            </div>
                            <div class="post-mini__rating user__rating">
                                <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                    <span class="post-mini__rating-amount user__rating-amount">556</span>
                                    <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                </p>
                                <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                    <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                    <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                </p>
                            </div>
                            <div class="post-mini__user-buttons user__buttons">
                                <button
                                    class="post-mini__user-button user__button user__button--subscription button button--main"
                                    type="button">Подписаться
                                </button>
                            </div>
                        </li>
                    </ul>
                </section>
            </div>
        </div>
    </div>
</div>

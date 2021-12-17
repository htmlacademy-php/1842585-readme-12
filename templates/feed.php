<?php

/**
 * Шаблон главной страницы авторизованного пользователя
 * @var $post_types array<array{id: string, name: string, icon_class: string}> - массив типов постов
 * @var $posts array<array> - массив постов пользователей
 * @var $current_type_id string - идентификатор типа поста, если установлена сортировка
 */

?>
<div class="container">
    <h1 class="page__title page__title--feed">Моя лента</h1>
</div>
<div class="page__main-wrapper container">
    <section class="feed">
        <h2 class="visually-hidden">Лента</h2>
        <div class="feed__main-wrapper">
            <div class="feed__wrapper">
                <?php foreach ($posts as $index => $post) : ?>
                    <article class="feed__post post <?= htmlspecialchars($post["type"]) ?>">
                        <header class="post__header post__author">
                            <a class="post__author-link"
                               href="/profile.php?author_id=<?= htmlspecialchars($post["user_id"]) ?>" title="Автор">
                                <div class="post__avatar-wrapper">
                                    <img class="post__author-avatar"
                                         src="<?= htmlspecialchars($post["avatar"]) ?>"
                                         alt="Аватар пользователя" width="60" height="60">
                                </div>
                                <div class="post__info">
                                    <b class="post__author-name"><?= htmlspecialchars($post["user_name"]) ?></b>
                                    <span class="post__time"><?= htmlspecialchars($post["time_ago"]) ?></span>
                                </div>
                            </a>
                        </header>
                        <?php
                            $template_post = include_template("/parts/post/" . $post["type"] . ".php", [
                                "id" => $post["id"],
                                "title" => $post["title"],
                                "content" => $post["contain"],
                                "author" => $post["author"],
                                "is_details" => false,
                                "show_title" => true,
                                "is_video_control" => true,
                            ]);
                            print($template_post);
                        ?>
                        <footer class="post__footer post__indicators">
                            <?php
                                $template_indicators = include_template("/parts/post/indicators.php", [
                                    "post" => $post,
                                ]);
                                print($template_indicators);
                            ?>
                        </footer>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
        <ul class="feed__filters filters">
            <li class="feed__filters-item filters__item">
                <a class="filters__button <?= $current_type_id === null ? "filters__button--active" : "" ?>"
                   href="/feed.php">
                    <span>Все</span>
                </a>
            </li>
            <?php foreach ($post_types as $post_type) : ?>
                <li class="feed__filters-item filters__item">
                    <a class="filters__button filters__button--<?php
                    $active_class = $current_type_id === $post_type["id"] ? " filters__button--active" : "";
                    $post_class = htmlspecialchars($post_type["icon_class"]);
                    print($post_class . $active_class);
                    ?> button" href="?type_id=<?= $post_type["id"] ?>">
                        <span class="visually-hidden"><?= htmlspecialchars($post_type["name"]) ?></span>
                        <svg class="filters__icon" width="22" height="18">
                            <use xlink:href="#icon-filter-<?= htmlspecialchars($post_type["icon_class"]) ?>"></use>
                        </svg>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <aside class="promo">
        <article class="promo__block promo__block--barbershop">
            <h2 class="visually-hidden">Рекламный блок</h2>
            <p class="promo__text">
                Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
            </p>
            <a class="promo__link" href="#">
                Подробнее
            </a>
        </article>
        <article class="promo__block promo__block--technomart">
            <h2 class="visually-hidden">Рекламный блок</h2>
            <p class="promo__text">
                Товары будущего уже сегодня в онлайн-сторе Техномарт!
            </p>
            <a class="promo__link" href="#">
                Перейти в магазин
            </a>
        </article>
        <article class="promo__block">
            <h2 class="visually-hidden">Рекламный блок</h2>
            <p class="promo__text">
                Здесь<br> могла быть<br> ваша реклама
            </p>
            <a class="promo__link" href="#">
                Разместить
            </a>
        </article>
    </aside>
</div>

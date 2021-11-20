<?php

/**
 * Шаблон популярного контента
 * @var $post_types array<array{id: string, name: string, icon_class: string}> - массив типов постов
 * @var $posts array<array{id: string, title: string, type: string, contain: string, user_id: string, user_name: string, avatar: string, views_count:string, created_date:string, time_ago: string, date_title: string}> - массив постов пользователей
 * @var $current_type_id string - идентификатор типа поста, если установлена сортировка
 * @var $prev_offset
 * @var $next_offset
 * @var $post_count
 */

?>
<div class="container">
    <h1 class="page__title page__title--popular">Популярное</h1>
</div>
<div class="popular container">
    <div class="popular__filters-wrapper">
        <div class="popular__sorting sorting">
            <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
            <ul class="popular__sorting-list sorting__list">
                <li class="sorting__item sorting__item--popular">
                    <a class="sorting__link sorting__link--active" href="#">
                        <span>Популярность</span>
                        <svg class="sorting__icon" width="10" height="12">
                            <use xlink:href="#icon-sort"></use>
                        </svg>
                    </a>
                </li>
                <li class="sorting__item">
                    <a class="sorting__link" href="#">
                        <span>Лайки</span>
                        <svg class="sorting__icon" width="10" height="12">
                            <use xlink:href="#icon-sort"></use>
                        </svg>
                    </a>
                </li>
                <li class="sorting__item">
                    <a class="sorting__link" href="#">
                        <span>Дата</span>
                        <svg class="sorting__icon" width="10" height="12">
                            <use xlink:href="#icon-sort"></use>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
        <div class="popular__filters filters">
            <b class="popular__filters-caption filters__caption">Тип контента:</b>
            <ul class="popular__filters-list filters__list">
                <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                    <a class="filters__button filters__button--ellipse filters__button--all <?= $current_type_id === null ? "filters__button--active" : "" ?>"
                       href="popular.php">
                        <span>Все</span>
                    </a>
                </li>
                <?php foreach ($post_types as $post_type): ?>
                    <li class="popular__filters-item filters__item">
                        <a class="filters__button filters__button--<?php
                        $active_class = $current_type_id === $post_type["id"] ? " filters__button--active" : "";
                        $post_class = htmlspecialchars($post_type["icon_class"]);
                        print($post_class . $active_class);
                        ?> button"
                           href="?type_id=<?= $post_type["id"] ?>">
                            <span class="visually-hidden"><?= htmlspecialchars($post_type["name"]) ?></span>
                            <svg class="filters__icon" width="22" height="18">
                                <use xlink:href="#icon-filter-<?= htmlspecialchars($post_type["icon_class"]) ?>"></use>
                            </svg>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="popular__posts">
        <?php foreach ($posts as $index => $post): ?>
            <article class="popular__post post <?= htmlspecialchars($post["type"]) ?>">
                <header class="post__header">
                    <a href="/post.php?post_id=<?= htmlspecialchars($post["id"]) ?>">
                        <h2><?= htmlspecialchars($post["title"]) ?></h2>
                    </a>
                </header>
                <?php
                    $template_post = include_template("/parts/post/" . $post["type"] . ".php", [
                        "id" => $post["id"],
                        "title" => $post["title"],
                        "content" => $post["contain"],
                        "author" => $post["author"],
                        "is_details" => false,
                        "show_title" => false,
                        "is_video_control" => false,
                    ]);
                    print($template_post);
                ?>
                <footer class="post__footer">
                    <div class="post__author">
                        <a class="post__author-link" href="/profile.php?author_id=<?= htmlspecialchars($post["user_id"]) ?>" title="Автор">
                            <div class="post__avatar-wrapper">
                                <img class="post__author-avatar" src="<?= htmlspecialchars($post["avatar"]) ?>"
                                     alt="Аватар пользователя">
                            </div>
                            <div class="post__info">
                                <b class="post__author-name"><?= htmlspecialchars($post["user_name"]) ?></b>
                                <time class="post__time" title="<?= htmlspecialchars($post["date_title"]) ?>" datetime="<?= htmlspecialchars($post["created_date"]) ?>"><?= htmlspecialchars($post["time_ago"]) ?></time>
                            </div>
                        </a>
                    </div>
                    <div class="post__indicators">
                        <?php
                            $template_indicators = include_template("/parts/post/indicators.php", [
                                "post" => $post,
                            ]);
                            print($template_indicators);
                        ?>
                    </div>
                </footer>
            </article>
        <?php endforeach; ?>
    </div>
    <div class="popular__page-links">
        <a class="popular__page-link popular__page-link--prev button <?= $prev_offset < 0 ? "button--gray" : "button--green" ?>"
            <?= $prev_offset >= 0 ? "href=/popular.php?offset=" . htmlspecialchars($prev_offset) : "" ?>>Предыдущая страница</a>
        <a class="popular__page-link popular__page-link--next button <?= $next_offset >= $post_count ? "button--gray" : "button--green" ?>"
            <?= $next_offset < $post_count ? "href=/popular.php?offset=" . htmlspecialchars($next_offset) : "" ?>>Следующая страница</a>
    </div>
</div>

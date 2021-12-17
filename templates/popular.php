<?php

/**
 * Шаблон популярного контента
 * @var $post_types array<array{id: string, name: string, icon_class: string}> - массив типов постов
 * @var $posts array<array> - массив постов пользователей
 * @var $current_type_id string - идентификатор типа поста, если установлена сортировка
 * @var $prev_offset
 * @var $next_offset
 * @var $post_count
 * @var $sort_field
 * @var $sort_direction
 * @var $next_sort_direction
 * @var $offset
 * @var $current_type_params
 * @var $current_offset_params
 * @var $current_sort_params
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
                <li class="sorting__item <?= $sort_field === "views_count" ? "sorting__item--popular" : "" ?>">
                    <a class="sorting__link <?= $sort_direction === "ASC" ? "sorting__link--reverse" : "" ?>
                                            <?= $sort_field === "views_count" ? "sorting__link--active" : "" ?>"
                       href="<?= getSaveURL(
                           "/popular.php?",
                           $current_type_params,
                           $current_offset_params,
                           "sort_field=views_count&sort_direction=",
                           $next_sort_direction,
                       ) ?>">
                        <span>Популярность</span>
                        <svg class="sorting__icon" width="10" height="12">
                            <use xlink:href="#icon-sort"></use>
                        </svg>
                    </a>
                </li>
                <li class="sorting__item <?= $sort_field === "likes_count" ? "sorting__item--popular" : "" ?>">
                    <a class="sorting__link <?= $sort_direction === "ASC" ? "sorting__link--reverse" : "" ?>
                                            <?= $sort_field === "likes_count" ? "sorting__link--active" : "" ?>"
                       href="<?= getSaveURL(
                           "/popular.php?",
                           $current_type_params,
                           $current_offset_params,
                           "sort_field=likes_count&sort_direction=",
                           $next_sort_direction,
                       ) ?>">
                        <span>Лайки</span>
                        <svg class="sorting__icon" width="10" height="12">
                            <use xlink:href="#icon-sort"></use>
                        </svg>
                    </a>
                </li>
                <li class="sorting__item <?= $sort_field === "created_at" ? "sorting__item--popular" : "" ?>">
                    <a class="sorting__link <?= $sort_direction === "ASC" ? "sorting__link--reverse" : "" ?>
                                            <?= $sort_field === "created_at" ? "sorting__link--active" : "" ?>"
                       href="<?= getSaveURL(
                           "/popular.php?",
                           $current_type_params,
                           $current_offset_params,
                           "sort_field=created_at&sort_direction=",
                           $next_sort_direction
                       ) ?>">
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
                    <a class="filters__button filters__button--ellipse filters__button--all
                        <?= $current_type_id === null ? "filters__button--active" : "" ?>"
                       href="/popular.php?
                       <?= urlencode($current_offset_params) . urlencode($current_sort_params) ?>">
                        <span>Все</span>
                    </a>
                </li>
                <?php foreach ($post_types as $post_type) : ?>
                    <li class="popular__filters-item filters__item">
                        <a class="filters__button filters__button--<?php
                        $active_class = $current_type_id === $post_type["id"] ? " filters__button--active" : "";
                        $post_class = htmlspecialchars($post_type["icon_class"]);
                        print($post_class . $active_class);
                        ?> button"
                           href="/popular.php?type_id=<?= htmlspecialchars($post_type["id"]) ?>">
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
        <?php foreach ($posts as $index => $post) : ?>
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
                        <?php
                            $template_author_post = include_template("/parts/post/post-author.php", [
                                "post" => $post,
                            ]);
                            print($template_author_post);
                        ?>
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
        <a class="popular__page-link popular__page-link--prev button
            <?= $prev_offset < 0 ? "button--gray" : "button--green" ?>"
            <?= $prev_offset >= 0 ? getSaveURL(
                "href=/popular.php?",
                $current_type_params,
                "offset=",
                $prev_offset,
                "&",
                $current_sort_params,
            ) : "" ?>
        >
            Предыдущая страница
        </a>
        <a class="popular__page-link popular__page-link--next button
            <?= $next_offset >= $post_count ? "button--gray" : "button--green" ?>"
            <?= $next_offset < $post_count ? getSaveURL(
                "href=/popular.php?",
                $current_type_params,
                "offset=",
                $next_offset,
                "&",
                $current_sort_params,
            ) : "" ?>
        >
            Следующая страница
        </a>
    </div>
</div>

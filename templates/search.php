<?php
/**
 * Шаблон результатов поиска
 * @var $search string - строка поиска
 * @var $posts array{id: string, title: string, type: string, contain: string, user_name: string, avatar: string, views_count:string, created_date:string, time_ago: string, date_title: string} - пост пользователя
 */

?>

<h1 class="visually-hidden">Страница результатов поиска</h1>
<section class="search">
    <h2 class="visually-hidden">Результаты поиска</h2>
    <div class="search__query-wrapper">
        <div class="search__query container">
            <span>Вы искали:</span>
            <span class="search__query-text"><?= htmlspecialchars($search) ?></span>
        </div>
    </div>
    <div class="search__results-wrapper">
        <?php if (count($posts) === 0): ?>
            <div class="search__no-results container">
                <p class="search__no-results-info">К сожалению, ничего не найдено.</p>
                <p class="search__no-results-desc">
                    Попробуйте изменить поисковый запрос или просто зайти в раздел &laquo;Популярное&raquo;, там живет самый крутой контент.
                </p>
                <div class="search__links">
                    <a class="search__popular-link button button--main" href="/popular.php">Популярное</a>
                    <a class="search__back-link" href="/">Вернуться назад</a>
                </div>
            </div>
        <?php else: ?>
            <div class="container">
                <div class="search__content">
                    <?php foreach ($posts as $index => $post): ?>
                        <article class="search__post post <?= htmlspecialchars($post["type"]) ?>">
                            <header class="post__header post__author">
                                <?php
                                    $template_author_post = include_template("/parts/post/post-author.php", [
                                        "post" => $post,
                                    ]);
                                    print($template_author_post);
                                ?>
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
        <?php endif; ?>
    </div>
</section>

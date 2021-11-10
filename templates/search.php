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
                                <a class="post__author-link" href="#" title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <img class="post__author-avatar" src="<?= htmlspecialchars($post["avatar"]) ?>"
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
                                    "author" => $post["user_name"],
                                    "is_details" => false,
                                    "show_title" => true,
                                    "is_video_control" => true,
                                ]);
                                print($template_post);
                            ?>
                            <footer class="post__footer post__indicators">
                                <div class="post__buttons">
                                    <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                        <svg class="post__indicator-icon" width="20" height="17">
                                            <use xlink:href="#icon-heart"></use>
                                        </svg>
                                        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                             height="17">
                                            <use xlink:href="#icon-heart-active"></use>
                                        </svg>
                                        <span>250</span>
                                        <span class="visually-hidden">количество лайков</span>
                                    </a>
                                    <a class="post__indicator post__indicator--comments button" href="#"
                                       title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span>25</span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                </div>
                            </footer>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

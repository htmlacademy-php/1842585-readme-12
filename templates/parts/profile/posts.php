<?php
/**
 * Шаблон постов пользователя
 * @var $content array<array> - посты опубликованные пользователем профиля
 * @var $hashtags array - хэштеги постов
 */

?>
<section class="profile__posts tabs__content tabs__content--active">
    <h2 class="visually-hidden">Публикации</h2>
    <?php foreach ($content as $post) : ?>
        <article class="profile__post post <?= htmlspecialchars($post["type"]) ?>">
            <header class="post__header">
                <?php if ($post["user_id_origin"]) : ?>
                    <div class="post__author">
                        <?php
                            $template_author_post = include_template("/parts/post/post-author.php", [
                                "post" => $post,
                            ]);
                            print($template_author_post);
                        ?>
                    </div>
                <?php endif; ?>
                <a href="/post.php?post_id=<?= htmlspecialchars($post["id"]) ?>">
                    <h2><?= $post["title"] ?></h2>
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
                <div class="post__indicators">
                    <?php
                        $template_indicators = include_template("/parts/post/indicators.php", [
                            "post" => $post,
                        ]);
                        print($template_indicators);
                    ?>
                    <time
                        class="post__time"
                        datetime="<?= htmlspecialchars($post["created_date"]) ?>"
                    >
                        <?= htmlspecialchars($post["time_ago"]) ?>
                    </time>
                </div>
                <ul class="post__tags">
                    <?php
                        $posts_hashtags = array_filter($hashtags, function ($value) use ($post) {
                            return $value["post_id"] === $post["id"];
                        });

                    foreach ($posts_hashtags as $hashtag) :
                        ?>
                            <li>
                                <a href="/search.php?search=<?= urlencode($hashtag["name"]) ?>">
                            <?= htmlspecialchars($hashtag["name"]) ?>
                                </a>
                            </li>
                    <?php endforeach; ?>
                </ul>
            </footer>
            <div class="comments">
                <a class="comments__button button" href="/post.php?post_id=<?= htmlspecialchars($post["id"]) ?>">
                    Показать комментарии
                </a>
            </div>
        </article>
    <?php endforeach; ?>
</section>

<?php
/**
 * Шаблон постов пользователя
 * @var $content array<array{id: string, title: string, type: string, contain: string, user_name: string, avatar: string, views_count:string, created_date:string, time_ago: string, date_title: string}> - посты опубликованные пользователем профиля
 */

?>
<section class="profile__posts tabs__content tabs__content--active">
    <h2 class="visually-hidden">Публикации</h2>
    <?php foreach ($content as $post): ?>
        <article class="profile__post post <?= htmlspecialchars($post["type"]) ?>">
            <header class="post__header">
                <a href="/post.php?post_id=<?= htmlspecialchars($post["id"]) ?>">
                    <h2><?= $post["title"] ?></h2>
                </a>
            </header>
            <?php
                $template_post = include_template("/parts/post/" . $post["type"] . ".php", [
                    "id" => $post["id"],
                    "title" => $post["title"],
                    "content" => $post["contain"],
                    "author" => $post["user_name"],
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
                    <time class="post__time" datetime="<?= htmlspecialchars($post["created_date"]) ?>"><?= htmlspecialchars($post["time_ago"]) ?></time>
                </div>
                <ul class="post__tags">
                    <li><a href="#">#nature</a></li>
                    <li><a href="#">#globe</a></li>
                    <li><a href="#">#photooftheday</a></li>
                    <li><a href="#">#canon</a></li>
                    <li><a href="#">#landscape</a></li>
                    <li><a href="#">#щикарныйвид</a></li>
                </ul>
            </footer>
            <div class="comments">
                <a class="comments__button button" href="/post.php?post_id=<?= htmlspecialchars($post["id"]) ?>">Показать комментарии</a>
            </div>
        </article>
    <?php endforeach; ?>
</section>

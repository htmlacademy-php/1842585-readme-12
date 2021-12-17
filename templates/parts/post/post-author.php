<?php
/**
 * Шаблон автора поста
 * @var $post - данные поста
 */

?>
<a class="post__author-link"
   href="/profile.php?author_id=<?= $post["user_id_origin"]
       ? htmlspecialchars($post["user_id_origin"]) : htmlspecialchars($post["user_id"]) ?>" title="Автор">
    <div class="post__avatar-wrapper <?= $post["user_id_origin"] ? "post__avatar-wrapper--repost" : "" ?>">
        <img class="post__author-avatar"
             src="<?= $post["avatar_origin"]
                 ? htmlspecialchars($post["avatar_origin"]) : htmlspecialchars($post["avatar"]) ?>"
             alt="Аватар пользователя">
    </div>
    <div class="post__info">
        <b class="post__author-name">
            <?= $post["login_origin"]
                ? "Репост: " . htmlspecialchars($post["login_origin"]) : htmlspecialchars($post["user_name"]) ?>
        </b>
        <time class="post__time"
              title="<?= htmlspecialchars($post["date_title"]) ?>"
              datetime="<?= htmlspecialchars($post["created_date"]) ?>">
            <?= htmlspecialchars($post["time_ago"]) ?>
        </time>
    </div>
</a>

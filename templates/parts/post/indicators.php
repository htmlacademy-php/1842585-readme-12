<?php
/**
 * Шаблон подвала поста
 * @var $post - идентификатор поста
 */

?>

<div class="post__buttons">
    <a class="post__indicator post__indicator--likes
            <?= $post["is_liked"] ? "post__indicator--likes-active" : "" ?> button"
       href="/like.php?author_id=<?= htmlspecialchars($post["user_id"]) ?>&post_id=<?= htmlspecialchars(
           $post["id"]
       ) ?>" title="Лайк">
        <svg class="post__indicator-icon" width="20" height="17">
            <use xlink:href="#icon-heart"></use>
        </svg>
        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
             height="17">
            <use xlink:href="#icon-heart-active"></use>
        </svg>
        <span><?= htmlspecialchars($post["likes_count"]) ?></span>
        <span class="visually-hidden">количество лайков</span>
    </a>
    <a class="post__indicator post__indicator--comments button"
       href="/post.php?post_id=<?= htmlspecialchars($post["id"]) ?>"
       title="Комментарии">
        <svg class="post__indicator-icon" width="19" height="17">
            <use xlink:href="#icon-comment"></use>
        </svg>
        <span><?= htmlspecialchars($post["comments_count"]) ?></span>
        <span class="visually-hidden">количество комментариев</span>
    </a>
    <a class="post__indicator post__indicator--repost button"
       href="/repost.php?post_id=<?= htmlspecialchars($post["id"]) ?>" title="Репост">
        <svg class="post__indicator-icon" width="19" height="17">
            <use xlink:href="#icon-repost"></use>
        </svg>
        <span><?= htmlspecialchars($post["reposts_count"]) ?></span>
        <span class="visually-hidden">количество репостов</span>
    </a>
</div>

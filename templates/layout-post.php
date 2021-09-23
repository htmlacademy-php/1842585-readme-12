<?php

/**
 * Шаблон поста
 *
 * @var $post array{title: string, type: string, contain: string, user_name: string, avatar: string} - пост пользователя
 * @var $index int
 * @var $post_date array{create_date: date, alt_date: string, title: string} - дата создания поста и промежуток времени существования поста
 */

$post_date = getPostDate($index);

?>

<article class="popular__post post <?= $post["type"] ?>">
    <header class="post__header">
        <h2><?= htmlspecialchars($post["title"]) ?></h2>
    </header>
    <div class="post__main">
        <?php switch ($post["type"]) {
            case "post-quote":
                require("./templates/post/post_quote.php");
                break;
            case "post-link":
                require("./templates/post/post_link.php");
                break;
            case "post-photo":
                require("./templates/post/post_photo.php");
                break;
            case "post-video":
                require("./templates/post/post_video.php");
                break;
            case "post-text":
                require("./templates/post/post_text.php");
                break;
            default:
                die("Неизвестный тип поста.");
        } ?>
    </div>
    <footer class="post__footer">
        <div class="post__author">
            <a class="post__author-link" href="#" title="<?= $post_date["title"] ?>">
                <div class="post__avatar-wrapper">
                    <img class="post__author-avatar" src="img/<?= $post["avatar"] ?>"
                         alt="Аватар пользователя">
                </div>
                <div class="post__info">
                    <b class="post__author-name"><?= htmlspecialchars($post["user_name"]) ?></b>
                    <time class="post__time" datetime="<?= $post_date["created_date"] ?>"><?= htmlspecialchars(
                            $post_date["alt_date"]
                        ); ?></time>
                </div>
            </a>
        </div>
        <div class="post__indicators">
            <div class="post__buttons">
                <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                    <svg class="post__indicator-icon" width="20" height="17">
                        <use xlink:href="#icon-heart"></use>
                    </svg>
                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                         height="17">
                        <use xlink:href="#icon-heart-active"></use>
                    </svg>
                    <span>0</span>
                    <span class="visually-hidden">количество лайков</span>
                </a>
                <a class="post__indicator post__indicator--comments button" href="#"
                   title="Комментарии">
                    <svg class="post__indicator-icon" width="19" height="17">
                        <use xlink:href="#icon-comment"></use>
                    </svg>
                    <span>0</span>
                    <span class="visually-hidden">количество комментариев</span>
                </a>
            </div>
        </div>
    </footer>
</article>

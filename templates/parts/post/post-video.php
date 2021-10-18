<?php
/**
 * Шаблон видео-поста
 * @var $content - ссылка на видео
 * @var $title - описание видео
 * @var $is_details - страница для подробного просмотра поста
 */

if ($is_details):
    ?>

    <div class="post-details__image-wrapper post-photo__image-wrapper">
        <?= embed_youtube_video(htmlspecialchars($content)) ?>
    </div>
<?php else: ?>
    <div class="post-video__block">
        <div class="post-video__preview">
            <?= embed_youtube_cover(htmlspecialchars($content)) ?>
            <img src="img/coast-medium.jpg"
                 alt="Превью к видео <?= htmlspecialchars($title) ?>" width="360"
                 height="188">
        </div>
        <a href="post-details.html" class="post-video__play-big button">
            <svg class="post-video__play-big-icon" width="14" height="14">
                <use xlink:href="#icon-video-play-big"></use>
            </svg>
            <span class="visually-hidden">Запустить проигрыватель</span>
        </a>
    </div>
<?php endif ?>

<?php
/**
 * Шаблон превью поста с видео
 * @var $content string
 */

?>

<div class="post-mini__image-wrapper">
    <?= embed_youtube_cover(htmlspecialchars($content)) ?>
    <span class="post-mini__play-big">
        <svg class="post-mini__play-big-icon" width="12" height="13">
            <use xlink:href="#icon-video-play-big"></use>
        </svg>
    </span>
</div>
<span class="visually-hidden">Видео</span>

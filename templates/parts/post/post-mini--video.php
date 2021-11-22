<?php
/**
 * Шаблон превью поста с видео
 * @var $preview string
 */

?>

<div class="post-mini__image-wrapper">
    <img class="post-mini__image" src="<?= htmlspecialchars($preview) ?>" width="109" height="109" alt="Превью публикации">
    <span class="post-mini__play-big">
        <svg class="post-mini__play-big-icon" width="12" height="13">
            <use xlink:href="#icon-video-play-big"></use>
        </svg>
    </span>
</div>
<span class="visually-hidden">Видео</span>

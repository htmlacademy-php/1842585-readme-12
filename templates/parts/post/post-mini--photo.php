<?php
/**
 * Шаблон превью поста с изображением
 * @var $content string
 */

?>

<div class="post-mini__image-wrapper">
    <img class="post-mini__image"
         src="<?= htmlspecialchars($content) ?>"
         width="109"
         height="109"
         alt="Превью публикации"
    >
</div>
<span class="visually-hidden">Фото</span>

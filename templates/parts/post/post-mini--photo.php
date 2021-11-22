<?php
/**
 * Шаблон превью поста с изображением
 * @var $preview string
 */

?>

<div class="post-mini__image-wrapper">
    <img class="post-mini__image" src="<?= htmlspecialchars($preview) ?>" width="109" height="109" alt="Превью публикации">
</div>
<span class="visually-hidden">Фото</span>

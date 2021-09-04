<?php
/**
 * Шаблон для поста с изображением
 *
 * @var $post array
 *
 * Пример:
 * ["title" => "Моя мечта",
 * "type" => "post-photo",
 * "contain" => "coast-medium.jpg",
 * "user_name" => "Лариса",
 * "avatar" => "userpic-larisa-small.jpg"]
 */

?>
<div class="post-photo__image-wrapper">
    <img src="img/<?= $post["contain"] ?>" alt="<?= $post["title"] ?>" width="360"
         height="240">
</div>

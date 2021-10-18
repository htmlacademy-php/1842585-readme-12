<?php
/**
 * Шаблон поста-картинки
 * @var $title - описание картинки
 * @var $content - ссылка на картинку
 * @var $is_details - страница для подробного просмотра поста
 */

?>
<div class="post-photo__image-wrapper <?= $is_details ? "post-details__image-wrapper" : "" ?>">
    <img src="img/<?= htmlspecialchars($content) ?>" alt="<?= htmlspecialchars($title) ?>" width="760" height="507">
</div>

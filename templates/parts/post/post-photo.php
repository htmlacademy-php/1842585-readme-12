<?php
/**
 * Шаблон поста-картинки
 * @var $id - идентификатор поста
 * @var $title - описание картинки
 * @var $content - ссылка на картинку
 * @var $is_details - страница для подробного просмотра поста
 * @var $show_title - отображение заголовка
 */

?>
<div class="post__main">
    <?php if ($show_title) : ?>
        <h2><a href="/post.php?post_id=<?= htmlspecialchars($id) ?>"></a><?= htmlspecialchars($title) ?></h2>
    <?php endif; ?>
    <div class="post-photo__image-wrapper <?= $is_details ? "post-details__image-wrapper" : "" ?>">
        <img src="<?= htmlspecialchars($content) ?>" alt="<?= htmlspecialchars($title) ?>" width="760" height="507">
    </div>
</div>

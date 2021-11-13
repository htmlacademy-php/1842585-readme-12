<?php
/**
 * Шаблон текстового поста
 * @var $id - идентификатор поста
 * @var $title - заголовок поста
 * @var $content - текст поста
 * @var $show_title - отображение заголовка
 */

$postSettings = truncateContent($content);
?>
<div class="post__main">
    <?php if ($show_title): ?>
        <h2><a href="/post.php?post_id=<?= htmlspecialchars($id) ?>"></a><?= htmlspecialchars($title) ?></h2>
    <?php endif; ?>
    <p>
        <?= htmlspecialchars($postSettings["content"]) ?>
    </p>
    <?php if ($postSettings["truncated"]): ?>
        <a class="post-text__more-link">Читать далее</a>
    <?php endif ?>
</div>

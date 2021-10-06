<?php
/**
 * Шаблон текстового поста
 * @var $content - текст поста
 */

$postSettings = truncateContent($content);
?>
<div class="post__main">
    <p>
        <?= htmlspecialchars($postSettings["content"]) ?>
    </p>
    <?php if ($postSettings["truncated"]): ?>
        <a class="post-text__more-link">Читать далее</a>
    <?php endif ?>
</div>

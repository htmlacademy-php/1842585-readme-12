<?php
/**
 * Шаблон поста-цитаты
 * @var $content - текст цитаты
 * @var $author - автор цитаты
 */

?>
<div class="post__main">
    <blockquote>
        <p>
            <?= htmlspecialchars($content) ?>
        </p>
        <cite><?= htmlspecialchars($author) ?></cite>
    </blockquote>
</div>


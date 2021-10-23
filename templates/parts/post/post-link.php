<?php
/**
 * Шаблон поста со ссылкой
 * @var $content - ссылка на веб-сайт
 * @var $title - представление сайта
 */

?>
<div class="post__main">
    <div class="post-link__wrapper">
        <a class="post-link__external" href="<?= htmlspecialchars($content) ?>" title="Перейти по ссылке">
            <div class="post-link__info-wrapper">
                <div class="post-link__icon-wrapper">
                    <img src="https://www.google.com/s2/favicons?domain=<?= htmlspecialchars($content) ?>"
                         alt="<?= htmlspecialchars($title) ?>">
                </div>
                <div class="post-link__info">
                    <h3><?= htmlspecialchars($title) ?></h3>
                </div>
            </div>
        </a>
    </div>
</div>
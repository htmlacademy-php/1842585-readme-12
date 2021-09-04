<?php
/**
 * Шаблон поста-цитаты
 *
 * @var $post array
 *
 * Пример:
 * ["title" => "Цитата",
 * "type" => "post-quote",
 * "contain" => "Мы в жизни любим только раз, а после ищем лишь похожих",
 * "user_name" => "Лариса",
 * "avatar" => "userpic-larisa-small.jpg"]
 */

?>
<blockquote>
    <p>
        <?= htmlspecialchars($post["contain"]) ?>
    </p>
    <cite><?= htmlspecialchars($post["user_name"]) ?></cite>
</blockquote>

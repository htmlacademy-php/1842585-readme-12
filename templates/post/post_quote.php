<?php
/**
 * @var $post [
 * "title" => "Цитата",
 * "type" => "post-quote",
 * "contain" => "Мы в жизни любим только раз, а после ищем лишь похожих",
 * "user_name" => "Лариса",
 * "avatar" => "userpic-larisa-small.jpg",
 * ]
 */

?>
<blockquote>
    <p>
        <?= $post["contain"] ?>
    </p>
    <cite><?= $post["user_name"] ?></cite>
</blockquote>

<?php

/**
 * Шаблон для текстового поста
 *
 * @var $post array
 *
 * Пример:
 * ["title" => "Игра престолов",
 * "type" => "post-text",
 * "contain" => "Не могу дождаться начала финального сезона своего любимого сериала!",
 * "user_name" => "Владик",
 * "avatar" => "userpic.jpg"]
 */
$postSettings = truncateContent($post["contain"]);
?>
<p><?= htmlspecialchars($postSettings["content"]) ?></p>
<?php if ($postSettings["truncated"]): ?>
    <a class="post-text__more-link">Читать далее</a>
<?php endif; ?>

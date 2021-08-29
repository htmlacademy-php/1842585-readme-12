<?php

/**
 * @var $post [
 * "title" => "Игра престолов",
 * "type" => "post-text",
 * "contain" => "Не могу дождаться начала финального сезона своего любимого сериала!",
 * "user_name" => "Владик",
 * "avatar" => "userpic.jpg",
 * ]
 * @var $textWasOptimized bool
 */

?>
<p><?= optimizeContent($post["contain"]) ?></p>
<?php if ($textWasOptimized): ?>
<a class="post-text__more-link">Читать далее</a>
<?php endif; ?>

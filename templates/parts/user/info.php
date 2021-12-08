<?php
/**
 * Шаблон информации о пользователе
 * @var $posts_count - количество постов
 * @var $subscribers_count - количество подписчиков
 * @var $template_class - класс блока с рейтингом
 * @var $author_id - идентификатор просматриваемого пользователя
 * @var $is_subscribe - подписан ли авторизованный пользователя на просматриваемого пользователя
 * @var $is_current_user - авторизованный пользователя просматривает свой профиль
 */

?>

<div class="<?= htmlspecialchars($template_class) ?>__rating user__rating">
    <p class="<?= htmlspecialchars($template_class) ?>__rating-item user__rating-item user__rating-item--publications">
        <span class="<?= htmlspecialchars($template_class) ?>__rating-amount user__rating-amount"><?= $posts_count ?></span>
        <span class="<?= htmlspecialchars($template_class) ?>__rating-text user__rating-text">публикаций</span>
    </p>
    <p class="<?= htmlspecialchars($template_class) ?>-item user__rating-item user__rating-item--subscribers">
        <span class="<?= htmlspecialchars($template_class) ?>__rating-amount user__rating-amount"><?= $subscribers_count ?></span>
        <span class="<?= htmlspecialchars($template_class) ?>__rating-text user__rating-text">подписчиков</span>
    </p>
</div>

<?php if(!$is_current_user): ?>
    <form class="<?= htmlspecialchars($template_class) ?>__user-buttons user__buttons" method="post" action="/subscribe.php?author_id=<?= htmlspecialchars($author_id) ?>">
        <button class="<?= htmlspecialchars($template_class) ?>__user-button user__button user__button--subscription button button--main"><?= $is_subscribe ? "Отписаться" : "Подписаться" ?></button>
        <?php if ($is_subscribe): ?>
            <a class="<?= htmlspecialchars($template_class) ?>__user-button user__button user__button--writing button button--green" href="/messages.php?recipient_id=<?= htmlspecialchars($author_id) ?>">Сообщение</a>
        <?php endif; ?>
    </form>
<?php endif; ?>

<?php
/**
 * Шаблон информации о пользователе
 * @var $posts_count - количество постов
 * @var $subscribers_count - количество подписчиков
 * @var $rating_class - класс блока с рейтингом
 * @var $author_id - идентификатор просматриваемого пользователя
 * @var $is_subscribe - подписан ли авторизованный пользователя на просматриваемого пользователя
 * @var $form_class - класс формы подписки
 * @var $is_current_user - авторизованный пользователя просматривает свой профиль
 */

?>

<div class="<?= htmlspecialchars($rating_class) ?> user__rating">
    <p class="<?= htmlspecialchars($rating_class) ?>-item user__rating-item user__rating-item--publications">
        <span class="user__rating-amount"><?= $posts_count ?></span>
        <span class="<?= htmlspecialchars($rating_class) ?>-text user__rating-text">публикаций</span>
    </p>
    <p class="<?= htmlspecialchars($rating_class) ?>-item user__rating-item user__rating-item--subscribers">
        <span class="user__rating-amount"><?= $subscribers_count ?></span>
        <span class="<?= htmlspecialchars($rating_class) ?>-text user__rating-text">подписчиков</span>
    </p>
</div>

<?php if(!$is_current_user): ?>
    <form class="<?= htmlspecialchars($form_class) ?>-buttons user__buttons" method="post" action="/subscribe.php?author_id=<?= htmlspecialchars($author_id) ?>">
        <button class="<?= htmlspecialchars($form_class) ?>-button user__button user__button--subscription button button--main"><?= $is_subscribe ? "Отписаться" : "Подписаться" ?></button>
        <a class="<?= htmlspecialchars($form_class) ?>-button user__button user__button--writing button button--green" href="#">Сообщение</a>
    </form>
<?php endif; ?>

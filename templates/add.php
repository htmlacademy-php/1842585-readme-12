<?php
/**
 * Шаблон добавления поста
 * @var $post_types array<array{id: string, name: string, icon_class: string}> - массив типов постов
 * @var $posts array<array{id: string, title: string, type: string, contain: string, user_name: string, avatar: string, views_count:string, created_date:string, time_ago: string, date_title: string}> - массив постов пользователей
 * @var $type_id string - идентификатор типа поста, если установлена сортировка
 * @var $part_template html - шаблон для добавления поста
 * @var $current_type array{id: string, name: string, icon_class: string} - текущий тип поста
 */

?>
<div class="page__main-section">
    <div class="container">
        <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
    </div>
    <div class="adding-post container">
        <div class="adding-post__tabs-wrapper tabs">
            <div class="adding-post__tabs filters">
                <ul class="adding-post__tabs-list filters__list tabs__list">
                    <?php foreach ($post_types as $post_type): ?>
                        <li class="adding-post__tabs-item filters__item">
                            <a href="add.php?type_id=<?= htmlspecialchars($post_type['id']) ?>" class="adding-post__tabs-link filters__button filters__button--<?= htmlspecialchars($post_type['icon_class']) ?> tabs__item button <?= htmlspecialchars($post_type['id']) === $type_id ? "filters__button--active tabs__item--active" : "" ?>">
                                <svg class="filters__icon" width="22" height="18">
                                    <use xlink:href="#icon-filter-<?= htmlspecialchars($post_type['icon_class']) ?>"></use>
                                </svg>
                                <span><?= htmlspecialchars($post_type['name']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
            </div>
            <div class="adding-post__tab-content">
                <section class="adding-post__<?= htmlspecialchars($current_type["icon_class"]) ?> tabs__content tabs__content--active">
                    <h2 class="visually-hidden">Форма добавления ссылки</h2>
                    <form class="adding-post__form form" action="add.php?type_id=<?= htmlspecialchars($current_type['id']) ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="type_id" value="<?= htmlspecialchars($current_type["id"]) ?>">
                        <?php print($part_template) ?>
                        <div class="adding-post__buttons">
                            <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                            <a class="adding-post__close" href="#">Закрыть</a>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Шаблон поста со ссылкой
 * @var $content - ссылка на веб-сайт
 * @var $title - представление сайта
 * @var $errors - ошибки отправки формы
 * @var $errors_template - шаблон всех ошибок
 */

?>
<div class="form__text-inputs-wrapper">
    <div class="form__text-inputs">
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="link-heading">Заголовок <span class="form__input-required">*</span></label>
            <div class="form__input-section <?= isset($errors["heading"]) ? "form__input-section--error" : "" ?>">
                <input class="adding-post__input form__input" id="link-heading" type="text" name="heading" placeholder="Введите заголовок">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибки заполнения заголовка</h3>
                    <?php foreach ($errors["heading"] as $error): ?>
                        <p class="form__error-desc"><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="adding-post__textarea-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="post-link">Ссылка <span class="form__input-required">*</span></label>
            <div class="form__input-section <?= isset($errors["link"]) ? "form__input-section--error" : "" ?>">
                <input class="adding-post__input form__input" id="post-link" type="text" name="link">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибка заполнения ссылки</h3>
                    <?php foreach ($errors["link"] as $error): ?>
                        <p class="form__error-desc"><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="link-tags">Теги</label>
            <div class="form__input-section <?= isset($errors["tags"]) ? "form__input-section--error" : "" ?>">
                <input class="adding-post__input form__input" id="link-tags" type="text" name="tags" placeholder="Введите ссылку">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибки заполнения тегов</h3>
                    <?php foreach ($errors["tags"] as $error): ?>
                        <p class="form__error-desc"><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php print($errors_template); ?>
</div>


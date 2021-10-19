<?php
/**
 * Шаблон поста-цитаты
 * @var $content - текст цитаты
 * @var $author - автор цитаты
 * @var $errors - ошибки отправки формы
 * @var $errors_template - шаблон всех ошибок
 */

?>
<div class="form__text-inputs-wrapper">
    <div class="form__text-inputs">
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="quote-heading">Заголовок <span class="form__input-required">*</span></label>
            <div class="form__input-section <?= isset($errors["heading"]) ? "form__input-section--error" : "" ?>">
                <input class="adding-post__input form__input" id="quote-heading" type="text" name="heading" placeholder="Введите заголовок">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибки заполнения заголовка</h3>
                    <?php foreach ($errors["heading"] as $error): ?>
                        <p class="form__error-desc"><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="adding-post__input-wrapper form__textarea-wrapper">
            <label class="adding-post__label form__label" for="cite-text">Текст цитаты <span class="form__input-required">*</span></label>
            <div class="form__input-section <?= isset($errors["cite-text"]) ? "form__input-section--error" : "" ?>">
                <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input" name="cite-text" id="cite-text" placeholder="Текст цитаты"></textarea>
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибка заполнения текста цитаты</h3>
                    <?php foreach ($errors["cite-text"] as $error): ?>
                        <p class="form__error-desc"><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="adding-post__textarea-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="quote-author">Автор <span class="form__input-required">*</span></label>
            <div class="form__input-section <?= isset($errors["author"]) ? "form__input-section--error" : "" ?>">
                <input class="adding-post__input form__input" id="quote-author" type="text" name="author">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибки заполнения автора</h3>
                    <?php foreach ($errors["author"] as $error): ?>
                        <p class="form__error-desc"><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="cite-tags">Теги</label>
            <div class="form__input-section <?= isset($errors["tags"]) ? "form__input-section--error" : "" ?>">
                <input class="adding-post__input form__input" id="cite-tags" type="text" name="tags" placeholder="Введите теги">
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

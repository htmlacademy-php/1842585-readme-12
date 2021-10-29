<?php
/**
 * Шаблон поста-картинки
 * @var $title - описание картинки
 * @var $content - ссылка на картинку
 * @var $is_details - страница для подробного просмотра поста
 * @var $errors - ошибки отправки формы
 * @var $errors_template - шаблон всех ошибок
 */

?>
<div class="form__text-inputs-wrapper">
    <div class="form__text-inputs">
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="photo-heading">Заголовок <span class="form__input-required">*</span></label>
            <div class="form__input-section <?= isset($errors["heading"]) ? "form__input-section--error" : "" ?>">
                <input class="adding-post__input form__input" id="photo-heading" type="text" name="heading" placeholder="Введите заголовок">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибки заполнения заголовка</h3>
                    <?php foreach ($errors["heading"] as $error): ?>
                        <p class="form__error-desc"><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>
            <div class="form__input-section <?= isset($errors["photo-url"]) ? "form__input-section--error" : "" ?>">
                <input class="adding-post__input form__input" id="photo-url" type="text" name="photo-url" placeholder="Введите ссылку">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибки заполнения ссылки на изображение</h3>
                    <?php foreach ($errors["photo-url"] as $error): ?>
                        <p class="form__error-desc"><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="photo-tags">Теги</label>
            <div class="form__input-section <?= isset($errors["tags"]) ? "form__input-section--error" : "" ?>">
                <input class="adding-post__input form__input" id="photo-tags" type="text" name="tags" placeholder="Введите теги">
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
<div class="adding-post__input-file-container form__input-container form__input-container--file">
    <div class="adding-post__input-file-wrapper form__input-file-wrapper">
        <div class="adding-post__file-zone form__file-zone adding-post__file-zone--photo dropzone">
            <input class="adding-post__input-file form__input-file" id="userpic-file-photo" type="file" name="userpic-file-photo" title=" ">
            <div class="form__file-zone-text">
                <span>Перетащите фото сюда</span>
            </div>
        </div>
        <button class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button" type="button">
            <span>Выбрать фото</span>
            <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
                <use xlink:href="#icon-attach"></use>
            </svg>
        </button>
    </div>
    <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">
        <img class="preview" height="250" width="250" src="" alt="Превью изображения">
    </div>
</div>

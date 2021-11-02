<?php
/**
 * Шаблон текстового поста
 * @var $content - текст поста
 * @var $errors - ошибки отправки формы
 * @var $errors_template - шаблон всех ошибок
 */

?>
<div class="form__text-inputs-wrapper">
    <div class="form__text-inputs">
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="text-heading">Заголовок <span class="form__input-required">*</span></label>
            <div class="form__input-section <?= isset($errors["heading"]) ? "form__input-section--error" : "" ?>">
                <input class="adding-post__input form__input" id="text-heading" type="text" name="heading" placeholder="Введите заголовок">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибки заполнения заголовка</h3>
                    <?php foreach ($errors["heading"] as $error): ?>
                        <p class="form__error-desc"><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="adding-post__textarea-wrapper form__textarea-wrapper">
            <label class="adding-post__label form__label" for="post-text">Текст поста <span class="form__input-required">*</span></label>
            <div class="form__input-section <?= isset($errors["post-text"]) ? "form__input-section--error" : "" ?>">
                <textarea class="adding-post__textarea form__textarea form__input" id="post-text" name="post-text" placeholder="Введите текст публикации"></textarea>
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибки заполнения текста поста</h3>
                    <?php foreach ($errors["post-text"] as $error): ?>
                        <p class="form__error-desc"><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="post-tags">Теги</label>
            <div class="form__input-section <?= isset($errors["tags"]) ? "form__input-section--error" : "" ?>">
                <input class="adding-post__input form__input" id="post-tags" type="text" name="tags" placeholder="Введите теги">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибки заполнения тегов</h3>
                    <?php foreach ($errors["tags"] as $error): ?>
                        <p class="form__error-desc"><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php print($errors_template); ?>
</div>

<?php
/**
 * Шаблон видео-поста
 * @var $result - данные предыдущего заполнения
 * @var $errors - ошибки отправки формы
 * @var $errors_template - шаблон всех ошибок
 */

?>
<div class="form__text-inputs-wrapper">
    <div class="form__text-inputs">
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="video-heading">
                Заголовок <span class="form__input-required">*</span>
            </label>
            <div class="form__input-section <?= isset($errors["heading"]) ? "form__input-section--error" : "" ?>">
                <input
                    class="adding-post__input form__input"
                    id="video-heading"
                    type="text"
                    name="heading"
                    value="<?= htmlspecialchars($result["title"]) ?>"
                    placeholder="Введите заголовок"
                >
                <button class="form__error-button button" type="button">
                    !<span class="visually-hidden">Информация об ошибке</span>
                </button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибки заполнения заголовка</h3>
                    <?php if (isset($errors["heading"])) : ?>
                        <?php foreach ($errors["heading"] as $error) : ?>
                            <p class="form__error-desc"><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="video-url">
                Ссылка youtube <span class="form__input-required">*</span>
            </label>
            <div class="form__input-section <?= isset($errors["video-url"]) ? "form__input-section--error" : "" ?>">
                <input
                    class="adding-post__input form__input"
                    id="video-url"
                    type="text"
                    name="video-url"
                    value="<?= htmlspecialchars($result["video_url"]) ?>"
                    placeholder="Введите ссылку"
                >
                <button class="form__error-button button" type="button">
                    !<span class="visually-hidden">Информация об ошибке</span>
                </button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибки заполнения ссылки youtube</h3>
                    <?php if (isset($errors["video-url"])) : ?>
                        <?php foreach ($errors["video-url"] as $error) : ?>
                            <p class="form__error-desc"><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="video-tags">Теги</label>
            <div class="form__input-section <?= isset($errors["tags"]) ? "form__input-section--error" : "" ?>">
                <input
                    class="adding-post__input form__input"
                    id="video-tags"
                    type="text"
                    name="tags"
                    value="<?= htmlspecialchars(getTagsString($result["tags"])) ?>"
                    placeholder="Введите теги"
                >
                <button class="form__error-button button" type="button">
                    !<span class="visually-hidden">Информация об ошибке</span>
                </button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибки заполнения тегов</h3>
                    <?php if (isset($errors["tags"])) : ?>
                        <?php foreach ($errors["tags"] as $error) : ?>
                            <p class="form__error-desc"><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php print($errors_template); ?>
</div>

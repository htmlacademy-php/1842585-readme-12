<?php
/**
 * Шаблон ошибок
 * @var $errors - ошибки отправки формы
 */

if (count($errors) > 0) : ?>
    <div class="form__invalid-block">
        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
        <ul class="form__invalid-list">
            <?php foreach ($errors as $error_types) : ?>
                <?php foreach ($error_types as $error) : ?>
                    <li class="form__invalid-item"><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

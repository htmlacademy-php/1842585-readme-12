<?php
/**
 * Шаблон сообщений
 * @var $current_recipient
 * @var $is_empty_messages
 * @var $messages
 * @var $messages_recipients
 * @var $current_user
 * @var $error
 * @var $recipient_id
 */

?>
<h1 class="visually-hidden">Личные сообщения</h1>
<section class="messages tabs">
    <h2 class="visually-hidden">Сообщения</h2>
    <div class="messages__contacts">
        <ul class="messages__contacts-list tabs__list">
            <?php if ($is_empty_messages && count($current_recipient) > 0) : ?>
                <li class="messages__contacts-item">
                    <a class="messages__contacts-tab messages__contacts-tab--active tabs__item tabs__item--active"
                       href="/messages.php?recipient_id=<?= htmlspecialchars($current_recipient["id"])?>">
                        <div class="messages__avatar-wrapper">
                            <img
                                class="messages__avatar"
                                src="<?= htmlspecialchars($current_recipient["avatar"]) ?>"
                                alt="Аватар пользователя"
                            >
                        </div>
                        <div class="messages__info">
                            <span class="messages__contact-name">
                                <?= htmlspecialchars($current_recipient["user_name"]) ?>
                            </span>
                        </div>
                    </a>
                </li>
            <?php endif; ?>
            <?php foreach ($messages_recipients as $message_recipient) : ?>
                <li class="messages__contacts-item">
                    <a class="messages__contacts-tab tabs__item
                    <?= $message_recipient["recipient_id"] === $recipient_id ||
                    $message_recipient["user_id"] === $recipient_id
                        ? "messages__contacts-tab--active tabs__item--active" : "" ?>"
                       href="/messages.php?recipient_id=<?=
                        htmlspecialchars(
                            $message_recipient["recipient_id"] === $current_user["id"]
                            ? $message_recipient["user_id"] : $message_recipient["recipient_id"]
                                                        ) ?>">
                        <div class="messages__avatar-wrapper">
                            <img class="messages__avatar"
                                 src="<?= htmlspecialchars(
                                     $message_recipient["recipient_id"] === $current_user["id"]
                                     ? $message_recipient["avatar"] : $message_recipient["recipient_avatar"]
                                      ) ?>"
                                 alt="Аватар пользователя">
                            <?php if ($message_recipient["unread_count"]) : ?>
                                <i class="messages__indicator">
                                    <?= htmlspecialchars($message_recipient["unread_count"]) ?>
                                </i>
                            <?php endif; ?>
                        </div>
                        <div class="messages__info">
                            <span class="messages__contact-name">
                                <?= htmlspecialchars(
                                    $message_recipient["recipient_id"] === $current_user["id"]
                                    ? $message_recipient["user_name"] : $message_recipient["recipient_name"]
                                )
                                ?>
                            </span>
                            <div class="messages__preview">
                                <p class="messages__preview-text">
                                    <?= truncateContent(
                                        htmlspecialchars($message_recipient["content"]),
                                        10
                                    )["content"] ?>
                                </p>
                                <time class="messages__preview-time"
                                      title="<?= htmlspecialchars($message_recipient["date_title"]) ?>"
                                      datetime="<?= htmlspecialchars($message_recipient["created_date"]) ?>">
                                    <?= htmlspecialchars($message_recipient["time_ago"]) ?>
                                </time>
                            </div>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="messages__chat">
        <?php if ($recipient_id) : ?>
            <div class="messages__chat-wrapper">
                <?php if (count($messages) > 0) : ?>
                    <ul class="messages__list tabs__content tabs__content--active">
                        <?php foreach ($messages as $message) : ?>
                            <li class="messages__item
                            <?= $current_user["id"] === $message["user_id"] ? "messages__item--my" : "" ?>">
                                <div class="messages__info-wrapper">
                                    <div class="messages__item-avatar">
                                        <a class="messages__author-link"
                                           href="/profile.php?author_id=<?= htmlspecialchars($message["user_id"]) ?>">
                                            <img class="messages__avatar"
                                                 src="<?= htmlspecialchars($message["avatar"]) ?>"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="messages__item-info">
                                        <a class="messages__author"
                                           href="/profile.php?author_id=<?= htmlspecialchars($message["user_id"]) ?>">
                                            <?= $current_user["id"] === $message["user_id"]
                                                ? "Вы" : htmlspecialchars($message["user_name"]) ?>
                                        </a>
                                        <time class="messages__time"
                                              title="<?= htmlspecialchars($message["date_title"]) ?>"
                                              datetime="<?= htmlspecialchars($message["created_date"]) ?>">
                                            <?= htmlspecialchars($message["time_ago"]) ?>
                                        </time>
                                    </div>
                                </div>
                                <p class="messages__text">
                                    <?= htmlspecialchars($message["content"]) ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="comments">
                <form class="comments__form form"
                      action="/messages.php?recipient_id=<?= htmlspecialchars($recipient_id) ?>"
                      method="post">
                    <div class="comments__my-avatar">
                        <img class="comments__picture"
                             src="<?= htmlspecialchars($current_user["avatar"]) ?>"
                             alt="Аватар пользователя">
                    </div>
                    <div class="form__input-section <?= $error === "" ? "" : "form__input-section--error" ?>">
                        <textarea class="comments__textarea form__textarea form__input"
                                  name="message"
                                  placeholder="Ваше сообщение"></textarea>
                        <label class="visually-hidden">Ваше сообщение</label>
                        <button class="form__error-button button" type="button">!</button>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Ошибка валидации</h3>
                            <p class="form__error-desc"><?= htmlspecialchars($error) ?></p>
                        </div>
                    </div>
                    <button class="comments__submit button button--green" type="submit">Отправить</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>

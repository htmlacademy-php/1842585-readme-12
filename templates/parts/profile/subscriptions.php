<?php
/**
 * Шаблон подписок на автора
 * @var $content array<array{id: string, user_id: string, user_name: string, avatar: string, like_at: DateTime, post_id: string, post_type: string, post_content: string, post_title:string, time_ago:string, date_title: string}> - лайки постов пользователя
 * @var $current_user_id - идентификатор авторизованного пользователя
 * @var $current_user_subscriptions - подписки текущего пользователя
 */

?>

<div class="profile__tab-content">
    <section class="profile__subscriptions tabs__content tabs__content--active">
        <h2 class="visually-hidden">Подписки</h2>
        <ul class="profile__subscriptions-list">
            <?php foreach ($content as $user): ?>
                <li class="post-mini post-mini--photo post user">
                    <?php
                        $user_profile = include_template("/parts/user/profile.php", [
                            "user_id" => $user["id"],
                            "avatar" => $user["avatar"],
                            "user_name" => $user["user_name"],
                            "registered_date" => $user["registered_date"],
                            "date_title" => $user["date_title"],
                            "time_ago" => $user["time_ago"],
                            "template_class" => "post-mini",
                        ]);
                        print($user_profile);

                        $user_info = include_template("/parts/user/info.php", [
                            "posts_count" => $user["posts_count"],
                            "subscribers_count" => $user["subscribers_count"],
                            "template_class" => "post-mini",
                            "author_id" => $user["id"],
                            "is_subscribe" => in_array($user["id"], array_column($current_user_subscriptions, "author_id"), true),
                            "is_current_user" => $user["id"] === $current_user_id,
                        ]);
                        print($user_info);
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</div>

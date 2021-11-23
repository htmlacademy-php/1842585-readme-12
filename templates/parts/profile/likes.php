<?php
/**
 * Шаблон лайков постов пользователя
 * @var $content array<array{id: string, user_id: string, user_name: string, avatar: string, like_at: DateTime, post_id: string, post_type: string, post_content: string, post_title:string, time_ago:string, date_title: string}> - лайки постов пользователя
 */

?>
<section class="profile__likes tabs__content tabs__content--active">
    <h2 class="visually-hidden">Лайки</h2>
    <ul class="profile__likes-list">
        <?php foreach ($content as $like): ?>
            <li class="post-mini <?= htmlspecialchars($like["post_type"]) ?> post user">
                <div class="post-mini__user-info user__info">
                    <div class="post-mini__avatar user__avatar">
                        <a class="user__avatar-link" href="/profile.php?author_id=<?= htmlspecialchars($like["user_id"]) ?>">
                            <img class="post-mini__picture user__picture" src="<?= htmlspecialchars($like["avatar"]) ?>" alt="Аватар пользователя">
                        </a>
                    </div>
                    <div class="post-mini__name-wrapper user__name-wrapper">
                        <a class="post-mini__name user__name" href="/profile.php?author_id=<?= htmlspecialchars($like["user_id"]) ?>">
                            <span><?= htmlspecialchars($like["user_name"]) ?></span>
                        </a>
                        <div class="post-mini__action">
                            <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                            <time class="post-mini__time user__additional" datetime="<?= htmlspecialchars($like["date_title"]) ?>"><?= htmlspecialchars($like["time_ago"]) ?></time>
                        </div>
                    </div>
                </div>
                <div class="post-mini__preview">
                    <a class="post-mini__link" href="/post.php?post_id=<?= htmlspecialchars($like["post_id"]) ?>" title="Перейти на публикацию">
                        <?php
                            $post_preview = include_template("/parts/post/" . $like["post_type"] . ".php", [
                                "content" => $like["post_content"],
                            ]);
                            print($post_preview);
                        ?>
                    </a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

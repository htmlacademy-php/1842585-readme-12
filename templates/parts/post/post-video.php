<?php
/**
 * Шаблон видео-поста
 * @var $content - ссылка на видео
 * @var $title - описание видео
 * @var $is_details - страница для подробного просмотра поста
 * @var $is_video_control - добавление блока с управлением видео-плеера
 */

if ($is_details):
    ?>

    <div class="post-details__image-wrapper post-photo__image-wrapper">
        <?= embed_youtube_video(htmlspecialchars($content)) ?>
    </div>
<?php else: ?>
    <div class="post__main">
        <div class="post-video__block">
            <div class="post-video__preview">
                <?= embed_youtube_cover(htmlspecialchars($content)) ?>
            </div>

            <?php if ($is_video_control): ?>
                <div class="post-video__control">
                    <button class="post-video__play post-video__play--paused button button--video"
                            type="button"><span class="visually-hidden">Запустить видео</span></button>
                    <div class="post-video__scale-wrapper">
                        <div class="post-video__scale">
                            <div class="post-video__bar">
                                <div class="post-video__toggle"></div>
                            </div>
                        </div>
                    </div>
                    <button
                        class="post-video__fullscreen post-video__fullscreen--inactive button button--video"
                        type="button"><span class="visually-hidden">Полноэкранный режим</span></button>
                </div>
            <?php endif; ?>

            <a href="post-details.html" class="post-video__play-big button">
                <svg class="post-video__play-big-icon" width="14" height="14">
                    <use xlink:href="#icon-video-play-big"></use>
                </svg>
                <span class="visually-hidden">Запустить проигрыватель</span>
            </a>
        </div>
    </div>
<?php endif ?>

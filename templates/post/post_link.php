<?php
/**
 * @var $post [
 * "title" => "Лучшие курсы",
 * "type" => "post-link",
 * "contain" => "www.htmlacademy.ru",
 * "user_name" => "Владик",
 * "avatar" => "userpic.jpg",
 * ]
 */

?>
<div class="post-link__wrapper">
    <a class="post-link__external" href="<?= $post["contain"] ?>"
       title="Перейти по ссылке">
        <div class="post-link__info-wrapper">
            <div class="post-link__icon-wrapper">
                <img src="https://www.google.com/s2/favicons?domain=vitadental.ru"
                     alt="Иконка">
            </div>
            <div class="post-link__info">
                <h3><?= $post["title"] ?></h3>
            </div>
        </div>
        <span><?= $post["contain"] ?></span>
    </a>
</div>

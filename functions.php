<?php

/**
 * Обрезает переданный текст если количество символов превышает максимально допустимое.
 *
 * Примеры:
 * 1) truncateContent("Американский драматический сериал в жанре фэнтези, является адаптацией
 *                  цикла романов «Песнь Льда и Пламени». Создатели сериала — сценаристы Дэвид
 *                  Беньофф и Дэн Вайс, съёмки ведутся в Северной Ирландии, Хорватии, Исландии,
 *                  Испании, Марокко и на Мальте. Всего сериал включает 73 серии, объединённые
 *                  в 8 сезонов. Премьера первого сезона в США состоялась на канале HBO 17 апреля
 *                  2011 года. Сериал отмечен множеством наград. Способствовал популяризации Джорджа
 *                  Мартина как писателя и придуманного им мира.", 400);
 * result = ["content" => "Американский драматический сериал в жанре фэнтези, является адаптацией цикла
 *                         романов «Песнь Льда и Пламени». Создатели сериала — сценаристы Дэвид Беньофф
 *                         и Дэн Вайс, съёмки ведутся в Северной Ирландии, Хорватии, Исландии, Испании,
 *                         Марокко и на Мальте. Всего сериал включает 73 серии, объединённые в 8 сезонов.
 *                         Премьера первого сезона в США состоялась на канале HBO 17 апреля 2011 года.
 *                         Сериал отмечен...",
 *         "truncated" => true]
 *
 * 2) truncateContent("Не могу дождаться начала финального сезона своего любимого сериала!");
 * result = ["content" => "Не могу дождаться начала финального сезона своего любимого сериала!",
 *         "truncated" => false]
 *
 * @param string $content - текст который необходимо обработать.
 * @param int $maxLength - допустимое количество символов в тексте.
 * @return array [
 *  content => string - обработанный текст
 *  truncated => bool - true если текст был обрезан]
 */
function truncateContent(string $content, int $maxLength = 300): array
{
    $resultLength = 0;
    $resultArray = [];
    $contentArray = explode(" ", $content);
    foreach ($contentArray as $contentPart) {
        $resultLength += mb_strlen($contentPart);
        $resultArray[] = $contentPart;
        if ($resultLength >= $maxLength) {
            break;
        }
        $resultLength++;
    }
    $truncated = count($contentArray) !== count($resultArray);
    $result = $truncated ? implode(" ", $resultArray) . "..." : implode(" ", $resultArray);

    return [
        "content" => $result,
        "truncated" => $truncated,
    ];
}

/**
 * Возвращает описание длительности существования поста.
 *
 * Примеры:
 * 1) getTimeAgo(new DateTime("2021-09-23 15:34:40"));
 * result = "19 минут назад"
 *
 * @param DateTime $created_date - дата создания поста.
 * @return string "19 минут назад"
 */
function getTimeAgo(DateTime $created_date): string
{
    $current_date = date_create();
    $diff = date_diff($current_date, $created_date);

    if ($diff->y > 0) { // больше года
        $date_count = $diff->y;
        $format = ['год', 'года', 'лет'];
    } elseif ($diff->days > 35) { // больше 5 недель
        $date_count = $diff->m;
        $format = ['месяц', 'месяца', 'месяцев'];
    } elseif ($diff->days > 7) { // больше 7 дней
        $date_count = ceil($diff->days / 7);
        $format = ['неделя', 'недели', 'недель'];
    } elseif ($diff->d > 0) { // больше 24 часов
        $date_count = $diff->d;
        $format = ['день', 'дня', 'дней'];
    } elseif ($diff->h > 0) { // больше часа
        $date_count = $diff->h;
        $format = ['час', 'часа', 'часов'];
    } else {
        $date_count = $diff->i;
        $format = ['минута', 'минуты', 'минут'];
    }

    $dateType = get_noun_plural_form($date_count, ...$format);

    return $date_count . " " . $dateType . " назад";
}

/**
 * Функция добавляет дату создания, время существования и заголовок каждому посту в массиве,
 * так же переопределяет имена полей в ассоциативном массиве и задает тип поста.
 *
 * Пример:
 * normalizePosts([[
 *       "id" => "3",
 *       "title" => "Моя мечта",
 *       "content" => "coast-medium.jpg",
 *       "author" => "Лариса",
 *       "login" => "Лариса",
 *       "type_id" => "3",
 *       "views_count" => "50",
 *       "avatar_path" => "userpic-larisa-small.jpg",
 *       ],
 *       [
 *       "id" => "4",
 *       "title" => "Лучшие курсы",
 *       "content" => "www.htmlacademy.ru",
 *       "author" => "Владик",
 *       "login" => "Владик",
 *       "type_id" => "5",
 *       "views_count" => "180",
 *       "avatar_path" => "userpic.jpg",
 *   ]]);
 * result = [[
 *          "id" => "3",
 *          "title" => "Моя мечта",
 *          "type" => "post-photo",
 *          "contain" => "coast-medium.jpg",
 *          "user_name" => "Лариса",
 *          "avatar" => "userpic-larisa-small.jpg",
 *          "views_count" => "50",
 *          "created_date" => "2021-09-23 15:31:40",
 *          "time_ago" => "3 минуты назад",
 *          "date_title" => "23.09.2021 15:31",
 *       ],
 *       [
 *          "id" => "4",
 *          "title" => "Лучшие курсы",
 *          "type" => "post-link",
 *          "contain" => "www.htmlacademy.ru",
 *          "user_name" => "Владик",
 *          "avatar" => "userpic.jpg",
 *          "views_count" => "180",
 *          "created_date" => "2021-09-23 13:34:40",
 *          "time_ago" => "2 часа назад",
 *          "date_title" => "23.09.2021 13:34",
 *   ]]
 *
 * @param array $posts<array{id: string, title: string, content: string, author: string, login: string, type_id:string, views_count: string, avatar_path: string}>
 * @param array $post_types<array{id: string, name: string, icon_class: string}>
 * @return array<array{id: string, title: string, type: string, contain: string, user_name: string, avatar: string, views_count:string, created_date:string, time_ago: string, date_title: string}>
 */
function normalizePosts(array $posts, array $post_types): array
{
    $result = [];

    foreach ($posts as $post) {
        $result[] = normalizePost($post, $post_types);
    }

    return $result;
}

function normalizePost(array $post, array $post_types): array {
    if ($post === []) {
        return $post;
    }

    $created_date = date_create($post["created_date"]);
    $type_key = array_search((string) $post["type_id"], array_column($post_types, "id"), true);

    return [
        "id" => (string) $post["id"],
        "title" => $post["title"],
        "contain" => $post["content"],
        "author" => $post["author"],
        "user_name" => $post["login"],
        "avatar" => $post["avatar_path"],
        "views_count" => $post["views_count"],
        "created_date" => $post["created_date"],
        "type" => "post-" . $post_types[$type_key]["icon_class"],
        "time_ago" => getTimeAgo($created_date),
        "date_title" => date_format($created_date, "d.m.Y H:i"),
    ];
}

/**
 * Функция переопределяет имена полей в ассоциативном массиве.
 *
 * Пример:
 * normalizePostTypes([[
 *       "id" => "2",
 *       "name" => "Ссылка",
 *       "icon_class" => "link",
 *       ],
 *       [
 *       "id" => "3",
 *       "name" => "Картинка",
 *       "icon_class" => "photo",
 *   ]]);
 * result = [[
 *       "id" => "2",
 *       "name" => "Ссылка",
 *       "icon_class" => "link",
 *       ],
 *       [
 *       "id" => "3",
 *       "name" => "Картинка",
 *       "icon_class" => "photo",
 *   ]]
 *
 * @param array $post_types <array{id: string, name: string, icon_class: string}>
 * @return array<array{id: string, name: string, icon_class: string}>
 */
function normalizePostTypes(array $post_types): array
{
    $result = [];

    foreach ($post_types as $post_type) {
        $result[] = [
            "id" => (string) $post_type["id"],
            "name" => $post_type["name"],
            "icon_class" => $post_type["icon_class"],
        ];
    }

    return $result;
}

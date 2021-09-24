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
        array_push($resultArray, $contentPart);
        if ($resultLength >= $maxLength) {
            break;
        }
        $resultLength += 1;
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
    $days_divider = 1;

    if ($diff->days > 35) { // больше 5 недель
        $date_format = '%m';
        $first_form = 'месяц';
        $second_form = 'месяца';
        $third_form = 'месяцев';
    } elseif ($diff->days > 7) { // больше 7 дней
        $date_format = '%a';
        $first_form = 'неделя';
        $second_form = 'недели';
        $third_form = 'недель';
        $days_divider = 7;
    } elseif ($diff->d > 0) { // больше 24 часов
        $date_format = '%d';
        $first_form = 'день';
        $second_form = 'дня';
        $third_form = 'дней';
    } elseif ($diff->h > 0) { // больше часа
        $date_format = '%h';
        $first_form = 'час';
        $second_form = 'часа';
        $third_form = 'часов';
    } else {
        $date_format = '%i';
        $first_form = 'минута';
        $second_form = 'минуты';
        $third_form = 'минут';
    }

    $date_count = ceil((int)date_interval_format($diff, $date_format) / $days_divider);
    $dateType = get_noun_plural_form($date_count, $first_form, $second_form, $third_form);

    return $date_count . " " . $dateType . " назад";
}

/**
 * Функция добавляет дату создания, время существования и заголовок каждому посту в массиве.
 *
 * Пример:
 * normalizePosts([[
 *       "title" => "Моя мечта",
 *       "type" => "post-photo",
 *       "contain" => "coast-medium.jpg",
 *       "user_name" => "Лариса",
 *       "avatar" => "userpic-larisa-small.jpg",
 *       ],
 *       [
 *       "title" => "Лучшие курсы",
 *       "type" => "post-link",
 *       "contain" => "www.htmlacademy.ru",
 *       "user_name" => "Владик",
 *       "avatar" => "userpic.jpg",
 *   ]]);
 * result = [[
 *          "title" => "Моя мечта",
 *          "type" => "post-photo",
 *          "contain" => "coast-medium.jpg",
 *          "user_name" => "Лариса",
 *          "avatar" => "userpic-larisa-small.jpg",
 *          "created_date" => "2021-09-23 15:31:40",
 *          "time_ago" => "3 минуты назад",
 *          "date_title" => "23.09.2021 15:31",
 *       ],
 *       [
 *          "title" => "Лучшие курсы",
 *          "type" => "post-link",
 *          "contain" => "www.htmlacademy.ru",
 *          "user_name" => "Владик",
 *          "avatar" => "userpic.jpg",
 *          "created_date" => "2021-09-23 13:34:40",
 *          "time_ago" => "2 часа назад",
 *          "date_title" => "23.09.2021 13:34",
 *   ]]
 *
 * @param array - массив постов пользователей
 */
function normalizePosts(array $posts): array
{
    foreach ($posts as $index => $post) {
        $posts[$index]["created_date"] = generate_random_date($index); // Временное поле, пока не получим данные из базы
        $created_date = date_create($posts[$index]["created_date"]);
        $posts[$index]["time_ago"] = getTimeAgo($created_date);
        $posts[$index]["date_title"] = date_format($created_date, 'd.m.Y H:i');
    }
    return $posts;
}

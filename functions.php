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
 * result = ["content" => "Американский драматический сериал в жанре фэнтези, является адаптацией цикла романов
 *                      «Песнь Льда и Пламени». Создатели сериала — сценаристы Дэвид Беньофф и Дэн Вайс, съёмки
 *                      ведутся в Северной Ирландии, Хорватии, Исландии, Испании, Марокко...",
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
        $resultLength += strlen($contentPart);
        array_push($resultArray, $contentPart);
        if ($resultLength >= $maxLength) {
            break;
        }
    }
    $truncated = count($contentArray) !== count($resultArray);
    $result = $truncated ? implode(" ", $resultArray) . "..." : implode(" ", $resultArray);
    return [
        "content" => $result,
        "truncated" => $truncated,
    ];
}

/**
 * Генерирует новую дату создания поста по его индексу, для каждого поста генерируется случайная дата.
 * Так же высчитывает время существования поста.
 *
 * Примеры:
 * 1) getPostDate(0);
 * result = ["created_date" => "2021-09-23 15:34:40",
 *         "alt_date" => "19 минут назад"
 *         "title" => "23.09.2021 15:34"]
 *
 * 2) getPostDate(5);
 * result = ["created_date" => "2021-09-23 15:34:40",
 *         "alt_date" => "19 минут назад"
 *         "title" => "23.09.2021 15:34"]
 *
 * @param int $index - индекс поста.
 * @return array [
 *  created_date => date - дата создания поста
 *  alt_date => string - период существования поста
 *  title => string - дата создания поста в виде "дд.мм.гггг чч: мм"]
 */
function getPostDate(int $index): array
{
    $random_date = generate_random_date($index);
    $created_date = date_create($random_date);
    $current_date = date_create();
    $diff = date_diff($current_date, $created_date);
    $days_divider = 1;

    if ($diff->days > 35) { // больше 5 недель
        $date_format = '%m';
        $first_form = 'месяц';
        $second_form = 'месяца';
        $third_form = 'месяцев';
    } else {
        if ($diff->days > 7) { // больше 7 дней
            $date_format = '%a';
            $first_form = 'неделя';
            $second_form = 'недели';
            $third_form = 'недель';
            $days_divider = 7;
        } else {
            if ($diff->d > 0) { // больше 24 часов
                $date_format = '%d';
                $first_form = 'день';
                $second_form = 'дня';
                $third_form = 'дней';
            } else {
                if ($diff->h > 0) { // больше часа
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
            }
        }
    }

    $date_count = ceil((int)date_interval_format($diff, $date_format) / $days_divider);
    $dateType = get_noun_plural_form($date_count, $first_form, $second_form, $third_form);
    $altDate = $date_count . " " . $dateType . " назад";

    return [
        "created_date" => $random_date,
        "alt_date" => $altDate,
        "title" => $created_date->format("d.m.Y H:i"),
    ];
}

<?php
/**
 * @var $connect mysqli - подключение к базе данных
 */

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
 * @param string $additional_text - дополнительный текст в конце строки
 * @return string "19 минут назад"
 */
function getTimeAgo(DateTime $created_date, string $additional_text = "назад"): string
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

    return $date_count . " " . $dateType . " " . $additional_text;
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
 * @param array $posts<array>
 * @param array $post_types<array>
 * @return array<array>
 */
function normalizePosts(array $posts, array $post_types, array $user_likes): array
{
    $result = [];

    foreach ($posts as $post) {
        $result[] = normalizePost($post, $post_types, $user_likes);
    }

    return $result;
}

/**
 * Функция-адаптер для поста
 * @param array $post
 * @param array $post_types
 * @param array $user_likes
 * @return array
 */
function normalizePost(array $post, array $post_types, array $user_likes): array
{
    if ($post === []) {
        return $post;
    }

    $created_date = date_create($post["created_at"]);
    $type_key = array_search((string) $post["type_id"], array_column($post_types, "id"), true);

    return [
        "id" => (string) $post["id"],
        "title" => $post["title"],
        "contain" => $post["content"],
        "author" => $post["author"],
        "user_id" => $post["user_id"],
        "user_name" => $post["login"],
        "avatar" => $post["avatar_path"],
        "views_count" => $post["views_count"],
        "comments_count" => $post["comments_count"],
        "created_date" => $post["created_at"],
        "type" => "post-" . $post_types[$type_key]["icon_class"],
        "time_ago" => getTimeAgo($created_date),
        "date_title" => date_format($created_date, "d.m.Y H:i"),
        "likes_count" => $post["likes_count"],
        "is_liked" => in_array($post["id"], array_column($user_likes, "post_id"), true),
        "login_origin" => $post["login_origin"],
        "avatar_origin" => $post["avatar_path_origin"],
        "reposts_count" => $post["reposts_count"],
        "user_id_origin" => $post["user_id_original"],
        "post_id_origin" => $post["post_id_original"],
    ];
}

/**
 * Функция-адаптер для пользователя
 * @param array $user
 * @return array
 */
function normalizeUser(array $user): array
{
    if ($user === []) {
        return $user;
    }

    $registered_at = date_create($user["registered_at"]);

    return [
        "id" => (string) $user["id"],
        "email" => $user["email"],
        "user_name" => $user["login"],
        "avatar" => $user["avatar_path"],
        "registered_date" => $user["registered_at"],
        "subscribers_count" => $user["subscribers_count"],
        "posts_count" => $user["posts_count"],
        "posts_viewed" => [],
        "time_ago" => getTimeAgo($registered_at, "на сайте"),
        "date_title" => date_format($registered_at, "d.m.Y H:i"),
    ];
}

/**
 * Функция-адаптер для массива пользователей
 * @param array $users
 * @return array
 */
function normalizeUsers(array $users): array
{
    $result = [];

    foreach ($users as $user) {
        $result[] = normalizeUser($user);
    }

    return $result;
}

/**
 * Функция-адаптер для лайка
 * @param array $like
 * @param array $post_types
 * @return array
 */
function normalizeLike(array $like, array $post_types): array
{
    if ($like === []) {
        return $like;
    }

    $like_at = date_create($like["like_at"]);
    $type_key = array_search((string) $like["type_id"], array_column($post_types, "id"), true);

    return [
        "id" => (string) $like["id"],
        "user_id" => (string) $like["user_id"],
        "user_name" => $like["login"],
        "avatar" => $like["avatar_path"],
        "like_at" => $like["like_at"],
        "post_id" => (string) $like["post_id"],
        "post_type" => "post-mini--" . $post_types[$type_key]["icon_class"],
        "post_content" => $like["content"],
        "preview" => $like["preview"],
        "post_title" => $like["title"],
        "time_ago" => getTimeAgo($like_at),
        "date_title" => date_format($like_at, "d.m.Y H:i"),
    ];
}

/**
 * Функция-адаптер для массива лайков
 * @param array $likes
 * @param array $post_types
 * @return array
 */
function normalizeLikes(array $likes, array $post_types): array
{
    $result = [];

    foreach ($likes as $like) {
        $result[] = normalizeLike($like, $post_types);
    }

    return $result;
}

/**
 * Функция-адаптер для комментария
 * @param array $comment
 * @return array
 */
function normalizeComment(array $comment): array
{
    if ($comment === []) {
        return $comment;
    }

    $comment_at = date_create($comment["created_at"]);

    return [
        "id" => (string) $comment["id"],
        "user_id" => (string) $comment["user_id"],
        "user_name" => $comment["login"],
        "avatar" => $comment["avatar_path"],
        "comment_at" => $comment["created_at"],
        "post_id" => (string) $comment["post_id"],
        "comment" => $comment["content"],
        "time_ago" => getTimeAgo($comment_at),
        "date_title" => date_format($comment_at, "d.m.Y H:i"),
    ];
}

/**
 * Функция-адаптер для массива комментариев
 * @param array $comments
 * @return array
 */
function normalizeComments(array $comments): array
{
    $result = [];

    foreach ($comments as $comment) {
        $result[] = normalizeComment($comment);
    }

    return $result;
}

/**
 * Функция-адаптер для подписки
 * @param array $subscription
 * @return array
 */
function normalizeSubscription(array $subscription): array
{
    if ($subscription === []) {
        return $subscription;
    }

    $subscribe_at = date_create($subscription["subscribe_at"]);

    return [
        "id" => (string) $subscription["id"],
        "author_id" => (string) $subscription["author_id"],
        "subscribe_at" => $subscription["subscribe_at"],
        "time_ago" => getTimeAgo($subscribe_at),
        "date_title" => date_format($subscribe_at, "d.m.Y H:i"),
    ];
}

/**
 * Функция-адаптер для массива подписок
 * @param array $subscriptions
 * @return array
 */
function normalizeSubscriptions(array $subscriptions): array
{
    $result = [];

    foreach ($subscriptions as $subscription) {
        $result[] = normalizeSubscription($subscription);
    }

    return $result;
}

/**
 * Функция-адаптер хештега
 * @param array $hashtag
 * @return array
 */
function normalizeHashtag(array $hashtag): array
{
    if ($hashtag === []) {
        return $hashtag;
    }

    return [
        "id" => (string) $hashtag["id"],
        "post_id" => (string) $hashtag["post_id"],
        "name" => $hashtag["name"],
    ];
}

/**
 * Функция-адаптер для массива хештегов
 * @param array $hashtags
 * @return array
 */
function normalizeHashtags(array $hashtags): array
{
    $result = [];

    foreach ($hashtags as $hashtag) {
        $result[] = normalizeHashtag($hashtag);
    }

    return $result;
}

/**
 * Функция-адаптер для сообщения
 * @param array $message
 * @return array
 */
function normalizeMessage(array $message): array
{
    if ($message === []) {
        return $message;
    }

    $created_at = date_create($message["created_at"]);

    return [
        "id" => (string) $message["id"],
        "created_date" => (string) $message["created_at"],
        "content" => $message["content"],
        "user_id" => (string) $message["user_id"],
        "user_name" => $message["login"],
        "avatar" => $message["avatar_path"],
        "recipient_id" => (string) $message["recipient_id"],
        "recipient_name" => $message["recipient_login"],
        "recipient_avatar" => $message["avatar_path_recipient"],
        "unread_count" => $message["unread_count"],
        "time_ago" => getTimeAgo($created_at),
        "date_title" => date_format($created_at, "d.m.Y H:i"),
    ];
}

/**
 * Функция-адаптер для массива сообщений
 * @param array $messages
 * @return array
 */
function normalizeMessages(array $messages): array
{
    $result = [];

    foreach ($messages as $message) {
        $result[] = normalizeMessage($message);
    }

    return $result;
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

/**
 * Функция для получения первого типа постов из массива
 * @param array $post_types
 * @return string
 */
function getFirstTypeId(array $post_types): string
{
    return $post_types[0] ? $post_types[0]["id"] : "";
}

/**
 * Функция для проверки заполнения поля
 * @param string $field_name
 * @param string $field_title
 * @return string
 */
function checkFilling(string $field_name, string $field_title): string
{
    $error_message = "";
    if (isset($_POST[$field_name]) && trim($_POST[$field_name]) === "") {
        $error_message = $field_title . ". Это поле должно быть заполнено.";
    }

    return $error_message;
}

/**
 * Функция для проверки заполнения длины поля
 * @param string $text
 * @param string $field_title
 * @param int $length
 * @return string
 */
function checkLength(string $text, string $field_title, int $length): string
{
    $error_message = "";
    if (mb_strlen($text, "UTF-8") > $length) {
        $error_message = $field_title . ". Длина этого поля не может превышать " . $length . " символов";
    }

    return $error_message;
}

/**
 * Функция добавления ошибки заполнения формы
 * @param array $errors
 * @param string $error_message
 * @param string $field_name
 * @return array
 */
function addError(array $errors, string $error_message, string $field_name): array
{
    if ($error_message !== "") {
        $errors[$field_name][] = $error_message;
    }

    return $errors;
}

/**
 * Функция для проверки правильного заполнения тегов
 * @param $pattern
 * @param $tags
 * @param $errors
 * @return array
 */
function checkTags($pattern, $tags, $errors): array
{
    foreach ($tags as $tag) {
        if (!preg_match($pattern, $tag)) {
            $errors = addError($errors, "Неверный формат тега " . $tag, "tags");
        }
    }

    return $errors;
}

/**
 * Функция для проверки типа загружаемого изображения
 * @param $pattern
 * @param $type
 * @return string
 */
function checkPictureType($pattern, $type): string
{
    $error = "";
    if (!preg_match($pattern, $type)) {
        $error = "Неверный формат файла";
    }

    return $error;
}

/**
 * Функция для получения полного пути хранения изображения
 * @param $full_path
 * @param $file_name
 * @return string
 */
function getFilePath($full_path, $file_name): string
{
    return $full_path . basename($file_name);
}

/**
 * Функция для загрузки изображения переданного с компьютера
 * @param $tmp_path
 * @param $full_path
 * @param $file_name
 * @return void
 */
function downloadFile($tmp_path, $full_path, $file_name)
{
    if ($tmp_path !== "") {
        $file_path = getFilePath($full_path, $file_name);
        move_uploaded_file($tmp_path, $file_path);
    }
}

/**
 * Функция для загрузки изображения переданного по ссылке из интернета
 * @param $content
 * @param $full_path
 * @param $file_name
 * @return void
 */
function downloadContent($content, $full_path, $file_name)
{
    if ($content !== "") {
        $download_photo = file_get_contents($content);
        $file_path = getFilePath($full_path, $file_name);
        file_put_contents($file_path, $download_photo);
    }
}

/**
 * Функция для проверки правильного URL-адреса
 * @param $url
 * @param $errors
 * @return string
 */
function getValidateURL($url, $errors): string
{
    $result = "";

    if (count($errors) === 0) {
        $result = filter_var($url, FILTER_VALIDATE_URL);
    }

    return $result;
}

/**
 * Функция для проверки правильного URL-адреса
 * @param $url
 * @return string
 */
function checkURL($url): string
{
    if ($url === "") {
        return "Не заполнено поле ссылка";
    }

    return filter_var($url, FILTER_VALIDATE_URL) ? "" : "Неверная ссылка";
}

/**
 * Функция для проверки правильного URL-адреса youtube
 * @param $url
 * @return string
 */
function checkYoutubeURL($url): string
{
    if ($url === "") {
        $result = "Не заполнено поле ссылка youtube";
    } elseif (!filter_var($url, FILTER_VALIDATE_URL)) {
        $result = "Неверная ссылка на видео";
    } else {
        $result = check_youtube_url($url);
    }

    return $result;
}

/**
 * Функция для обработки изображения загруженного с компьютера
 * @param $web_name
 * @param $field
 * @param $result
 * @param $uploads_dir
 * @return array
 */
function addPictureFile($web_name, $field, $result, $uploads_dir): array
{
    if ($_FILES[$web_name]["error"] !== 0) {
        return $result;
    }

    $file = $_FILES[$web_name];

    $result["errors"] = addError(
        $result["errors"],
        checkPictureType("/image\/(jpg|jpeg|png|gif)/i", $file["type"]),
        $web_name
    );

    $result[$field] = $uploads_dir . $file["name"];
    $result["file_name"] = $file["name"];
    $result["tmp_path"] = $file["tmp_name"];

    return $result;
}

/**
 * Функция для обработки изображения загруженного по ссылке из интернета
 * @param $web_name
 * @param $result
 * @param $uploads_dir
 * @return array
 */
function addPictureURL($web_name, $result, $uploads_dir): array
{
    if ($result["file_name"] !== "") {
        return $result;
    }

    if ($_POST[$web_name] === "") {
        $result["errors"] = addError(
            $result["errors"],
            "Необходимо выбрать изображение с компьютера или указать ссылку из интернета.",
            $web_name
        );
        return $result;
    }

    $result["picture_url"] = filter_var($_POST[$web_name], FILTER_VALIDATE_URL);
    $photo_info = pathinfo($result["picture_url"]);
    $result["errors"] = addError(
        $result["errors"],
        checkPictureType(
            "/(jpg|jpeg|png|gif)/i",
            $photo_info["extension"] ?? ""
        ),
        $web_name
    );
    $result["file_name"] = $photo_info["basename"];
    $result["content"] = $uploads_dir . $result["file_name"];

    return $result;
}

/**
 * Функция для обработки URL-адреса
 * @param $web_name
 * @param $result
 * @param $field
 * @return array
 */
function addWebsite($web_name, $result, $field): array
{
    $result[$field] = $_POST[$web_name];
    $result["errors"] = addError($result["errors"], checkLength($result[$field], "Ссылка", 1000), $web_name);
    $result["errors"] = addError($result["errors"], checkURL($result[$field]), $web_name);
    $result["content"] = getValidateURL($result[$field], $result["errors"]);

    return $result;
}

/**
 * Функция для обработки видео-ссылки
 * @param $web_name
 * @param $result
 * @param $field
 * @return array
 */
function addVideoURL($web_name, $result, $field): array
{
    $result[$field] = $_POST[$web_name];
    $result["errors"] = addError($result["errors"], checkLength($result[$field], $web_name, 1000), $web_name);
    $result["errors"] = addError($result["errors"], checkYoutubeURL($result[$field]), $web_name);
    $result["content"] = getValidateURL($result[$field], $result["errors"]);

    return $result;
}

/**
 * Функция для обработки текста
 * @param $web_name
 * @param $result
 * @param $field
 * @param $required_empty_filed
 * @param int $length
 * @return array
 */
function addTextContent($web_name, $result, $field, $required_empty_filed, int $length = 1000): array
{
    $result["errors"] = addError(
        $result["errors"],
        checkFilling($web_name, $required_empty_filed[$web_name]),
        $web_name
    );
    $result["errors"] = addError(
        $result["errors"],
        checkLength($_POST[$web_name], $required_empty_filed[$web_name], $length),
        $web_name
    );

    $result[$field] = $_POST[$web_name] ?? "";

    return $result;
}

/**
 * Функция для обработки тегов
 * @param $field
 * @param $result
 * @return array
 */
function addTags($field, $result): array
{
    if (isset($_POST[$field]) && $_POST[$field] !== "") {
        $result[$field] = explode(" ", $_POST[$field]);
        $result["errors"] = checkTags("/^#[A-Za-zА-яËё0-9]{1,19}$/u", $result[$field], $result["errors"]);
    }

    return $result;
}

/**
 * Функция для обработки email
 * @param $field
 * @param $web_name
 * @param $result
 * @param $connect
 * @return array
 */
function addEmail($field, $web_name, $result, $connect): array
{
    $result["errors"] = addError($result["errors"], checkFilling($web_name, "Email"), $web_name);
    if ($_POST[$web_name] === "") {
        return $result;
    }

    $result[$field] = $_POST[$web_name];

    $result["errors"] = addError($result["errors"], checkLength($result[$field], "Email", 320), $web_name);

    $email = filter_var($result[$field], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $result["errors"] = addError($result["errors"], "Неверно заполнен email", $web_name);
        return $result;
    }

    $user = getUserByEmail($connect, $email);
    if (count($user) > 0) {
        $result["errors"] = addError($result["errors"], "Пользователь с таким email уже существует", $web_name);
        return $result;
    }

    return $result;
}

/**
 * Функция для обработки логина
 * @param $field
 * @param $web_name
 * @param $result
 * @param $connect
 * @return array
 */
function addLogin($field, $web_name, $result, $connect): array
{
    $result = addTextContent($web_name, $result, $field, ["login" => "Логин"], 128);

    if ($_POST[$web_name] === "") {
        return $result;
    }

    $login = $_POST[$web_name];
    $user = getUserByLoginOrEmail($connect, $login);
    if (count($user) > 0) {
        $result["errors"] = addError($result["errors"], "Пользователь с таким логином уже существует", $web_name);
        return $result;
    }

    $result[$field] = $login;

    return $result;
}

/**
 * Функция для проверки пароля
 * @param $web_name
 * @param $result
 * @param $check_field
 * @param $field_title
 * @return array
 */
function checkPassword($web_name, $result, $check_field, $field_title): array
{
    $result["errors"] = addError($result["errors"], checkFilling($web_name, $field_title), $web_name);

    $password = $_POST[$web_name];

    if ($password === "") {
        return $result;
    }

    if ($password !== $_POST[$check_field]) {
        $result["errors"] = addError($result["errors"], "Пароли не совпадают", $web_name);
        return $result;
    }

    return $result;
}

/**
 * Функция для получения хеша пароля
 * @param $password
 * @return string
 */
function getHashPassword($password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Функция для обработки пароля
 * @param $field
 * @param $web_name
 * @param $result
 * @return array
 */
function addPassword($field, $web_name, $result): array
{
    $result = checkPassword($web_name, $result, "password-repeat", "Пароль");

    if (count($result["errors"]) > 0) {
        return $result;
    }

    $result[$field] = getHashPassword($_POST[$web_name]);

    return $result;
}

/**
 * Функция для обработки повтора пароля
 * @param $web_name
 * @param $result
 * @return array
 */
function addPasswordRepeat($web_name, $result): array
{
    return checkPassword($web_name, $result, "password", "Повтор пароля");
}

/**
 * Функция для перенаправления на другую страницу
 * @param $page
 * @return void
 */
function redirectTo($page)
{
    header("Location: $page");
    exit();
}

/**
 * Функция для получения текущего авторизованного пользователя
 * @return array
 */
function getUserAuthentication(): array
{
    return $_SESSION['user'] ?? [];
}

/**
 * Функция для конвертации массива тегов в строку
 * @param $tags
 * @return string
 */
function getTagsString($tags): string
{
    return implode(" ", $tags);
}

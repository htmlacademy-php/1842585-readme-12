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
function getTimeAgo(DateTime $created_date,  string $additional_text = "назад"): string
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

function filter_post_likes($like, $post_id): bool {
    return $like["post_id"] === $post_id;
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
function normalizePosts(array $posts, array $post_types, array $user_likes): array
{
    $result = [];

    foreach ($posts as $post) {
        $result[] = normalizePost($post, $post_types, $user_likes);
    }

    return $result;
}

function normalizePost(array $post, array $post_types, array $user_likes): array {
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
        "user_id" => $post["user_id"],
        "user_name" => $post["login"],
        "avatar" => $post["avatar_path"],
        "views_count" => $post["views_count"],
        "comments_count" => $post["comments_count"],
        "created_date" => $post["created_date"],
        "type" => "post-" . $post_types[$type_key]["icon_class"],
        "time_ago" => getTimeAgo($created_date),
        "date_title" => date_format($created_date, "d.m.Y H:i"),
        "likes_count" => $post["likes_count"],
        "is_liked" => in_array($post["id"], array_column($user_likes, "post_id"), true),
    ];
}

function normalizeUser(array $user): array {
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
        "time_ago" => getTimeAgo($registered_at, "на сайте"),
        "date_title" => date_format($registered_at, "d.m.Y H:i"),
    ];
}

function normalizeUsers(array $users): array {
    $result = [];

    foreach ($users as $user) {
        $result[] = normalizeUser($user);
    }

    return $result;
}

function normalizeLike(array $like, array $post_types): array {
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

function normalizeLikes(array $likes, array $post_types): array {
    $result = [];

    foreach ($likes as $like) {
        $result[] = normalizeLike($like, $post_types);
    }

    return $result;
}

function normalizeComment(array $comment): array {
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

function normalizeComments(array $comments): array {
    $result = [];

    foreach ($comments as $comment) {
        $result[] = normalizeComment($comment);
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

function getFirstTypeId(array $post_types): string {
    return $post_types[0] ? $post_types[0]["id"] : "";
}

function checkFilling(string $field_name, string $field_title): string {
    $error_message = "";
    if (isset($_POST[$field_name]) && $_POST[$field_name] === "") {
        $error_message = $field_title . ". Это поле должно быть заполнено.";
    }

    return $error_message;
}

function addError(array $errors, string $error_message, string $field_name): array {
    if ($error_message !== "") {
        $errors[$field_name][] = $error_message;
    }

    return $errors;
}

function checkTags($pattern, $tags, $errors): array
{
    foreach ($tags as $tag) {
        if (!preg_match($pattern, $tag)) {
            $errors = addError($errors, "Неверный формат тега " . $tag, "tags");
        }
    }

    return $errors;
}

function checkPictureType($pattern, $type): string {
    $error = "";
    if (!preg_match($pattern, $type)) {
        $error = "Неверный формат файла";
    }

    return $error;
}

function getFilePath($full_path, $file_name): string {
    return $full_path . basename($file_name);
}

function downloadFile($tmp_path, $full_path, $file_name) {
    if ($tmp_path !== "") {
        $file_path = getFilePath($full_path, $file_name);
        move_uploaded_file($tmp_path, $file_path);
    }
}

function downloadContent($content, $full_path, $file_name) {
    if ($content !== "") {
        $download_photo = file_get_contents($content);
        $file_path = getFilePath($full_path, $file_name);
        file_put_contents($file_path, $download_photo);
    }
}

function getValidateURL($url, $errors): string {
    $result = "";

    if (count($errors) === 0) {
        $result = filter_var($url, FILTER_VALIDATE_URL);
    }

    return $result;
}

function checkURL($url): string {
    if ($url === "") {
        return "Не заполнено поле ссылка";
    }

    return filter_var($url, FILTER_VALIDATE_URL) ? "" : "Неверная ссылка";
}

function checkYoutubeURL($url): string {
    if ($url === "") {
        $result = "Не заполнено поле ссылка youtube";
    } else if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $result = "Неверная ссылка на видео";
    } else {
        $result = check_youtube_url($url);
    }

    return $result;
}

function addPictureFile($web_name, $field, $result, $uploads_dir): array {
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

function addPictureURL($web_name, $result, $uploads_dir): array {
    if (isset($result["picture"])) {
        return $result;
    }

    if ($_POST[$web_name] === "") {
        $result["errors"] = addError($result["errors"], "Необходимо выбрать изображение с компьютера или указать ссылку из интернета.", $web_name);
        return $result;
    }

    $result["picture_url"] = filter_var($_POST[$web_name], FILTER_VALIDATE_URL);
    $photo_info = pathinfo($result["picture_url"]);
    $result["errors"] = addError($result["errors"], checkPictureType("/(jpg|jpeg|png|gif)/i", $photo_info["extension"] ?? ""), $web_name);
    $result["file_name"] = $photo_info["basename"];
    $result["content"] = $uploads_dir . $result["file_name"];

    return $result;
}

function addWebsite($web_name, $result, $field): array {
    $website = $_POST[$web_name];
    $result["errors"] = addError($result["errors"], checkURL($website), $web_name);
    $result["content"] = getValidateURL($website, $result["errors"]);
    $result[$field] = $result["content"];

    return $result;
}

function addVideoURL($web_name, $result, $field): array {
    $video_url = $_POST[$web_name];
    $result["errors"] = addError($result["errors"], checkYoutubeURL($video_url), $web_name);
    $result["content"] = getValidateURL($video_url, $result["errors"]);
    $result[$field] = $result["content"];

    return $result;
}

function addTextContent($web_name, $result, $field, $required_empty_filed): array {
    $result["errors"] = addError($result["errors"], checkFilling($web_name, $required_empty_filed[$web_name]), $web_name);
    $result[$field] = $_POST[$web_name] ?? "";

    return $result;
}

function addTags($field, $result): array {
    if (isset($_POST[$field]) && $_POST[$field] !== "") {
        $result[$field] = explode(" ", $_POST[$field]);
        $result["errors"] = checkTags("/^#[A-Za-zА-Яа-яËё0-9]{1,19}$/", $result[$field], $result["errors"]);
    }

    return $result;
}

function addEmail($field, $web_name, $result): array {
    $result["errors"] = addError($result["errors"], checkFilling($web_name, "Email"), $web_name);
    if ($_POST[$web_name] === "") {
        return $result;
    }

    $email = filter_var($_POST[$web_name], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $result["errors"] = addError($result["errors"], "Неверно заполнен email", $web_name);
        return $result;
    }

    $user = getUserByEmail($connect, $email);
    if (count($user) > 0) {
        $result["errors"] = addError($result["errors"], "Пользователь с таким email уже существует", $web_name);
        return $result;
    }

    $result[$field] = $email;

    return $result;
}

function addLogin($field, $web_name, $result): array {
    $result = addTextContent($web_name, $result, $field, ["login" => "Логин"]);

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

function checkPassword($web_name, $result, $check_field, $field_title): array {
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

function getHashPassword($password): string {
    return password_hash($password, PASSWORD_DEFAULT);
}

function addPassword($field, $web_name, $result): array {
    $result = checkPassword($web_name, $result, "password-repeat", "Пароль");

    if (count($result["errors"]) > 0) {
        return $result;
    }

    $result[$field] = getHashPassword($_POST[$web_name]);

    return $result;
}

function addPasswordRepeat($web_name, $result): array {
    return checkPassword($web_name, $result, "password", "Повтор пароля");
}

function redirectTo($page) {
    header("Location: $page");
    exit();
}

function getUserAuthentication(): array {
    return $_SESSION['user'] ?? [];
}

<?php

$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect === false) {
    die("Ошибка подключения: " . mysqli_connect_error());
}
// Запрос для типов постов
function fetchPostTypes()
{
    $queryPostTypes = "SELECT id, name, icon_class from content_types";

    return fetchData($queryPostTypes);
}

// Получаем шесть самых популярных постов и их авторов, а так же типы постов
function fetchPopularPosts()
{
    $queryPopularPosts = "SELECT created_at as created_date,
        posts.id,
        title,
        content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path
    FROM posts
        INNER JOIN users
            ON user_id = users.id
    ORDER BY views_count DESC
    LIMIT 6";

    return fetchData($queryPopularPosts);
}

function fetchData($query)
{
    global $connect;
    $result = mysqli_query($connect, $query);
    if ($result === false) {
        die("Ошибка получения данных по типам постов: " . mysqli_error($connect));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

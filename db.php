<?php

$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
} else {
    // Получаем шесть самых популярных постов и их авторов, а так же типы постов
    $query = "SELECT created_at as created_date,
           title,
           content as contain,
           author,
           users.login as user_name,
           content_types.name as type_name,
           content_types.icon_class as type,
           views_count,
           users.avatar_path as avatar
    FROM posts
        INNER JOIN users
            ON user_id = users.id
        INNER JOIN content_types
            ON type_id = content_types.id
    ORDER BY views_count DESC
    LIMIT 6";
    $result = mysqli_query($connect, $query);
    if ($result == false) {
        print("Ошибка получения данных: " . mysqli_error($connect));
        $posts = [];
    } else {
        $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

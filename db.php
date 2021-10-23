<?php

$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect === false) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

// Запрос для типов постов
function fetchPostTypes(): array
{
    $query = "SELECT id, name, icon_class from content_types";

    return fetchData(prepareResult($query));
}

// Получаем шесть самых популярных постов и их авторов, а так же типы постов
function fetchPopularPosts(): array
{
    $query = "SELECT created_at as created_date,
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

    return fetchData(prepareResult($query));
}

function fetchPopularPostsByType($type_id): array {
    $query = "SELECT created_at as created_date,
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
    WHERE type_id = ?
    ORDER BY views_count DESC
    LIMIT 6";

    return fetchData(prepareResult($query, "i", [$type_id]));
}

function checkResult($result, $connect, $err_message): void
{
    if ($result === false) {
        die($err_message . ": " . mysqli_error($connect));
    }
}

function fetchData($result): array
{
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function fetchAssocData($result): array
{
    $result = mysqli_fetch_assoc($result);
    return $result ?? [];
}

function prepareResult($query, $types = "", $params = []): mysqli_result {
    global $connect;

    $stmt = mysqli_prepare($connect, $query);
    checkResult($stmt, $connect, "Ошибка подготовки запроса");

    if ($types !== "") {
        $result = mysqli_stmt_bind_param($stmt, $types, ...$params);
        checkResult($result, $connect, "Ошибка установки параметров");
    }

    $result = mysqli_stmt_execute($stmt);
    checkResult($result, $connect, "Ошибка выполнения запроса");

    $result = mysqli_stmt_get_result($stmt);
    checkResult($result, $connect, "Ошибка получения данных");

    return $result;
}

function preparePostResult($query, $types, $params): string {
    global $connect;

    $stmt = mysqli_prepare($connect, $query);
    checkResult($stmt, $connect, "Ошибка подготовки запроса");

    $result = mysqli_stmt_bind_param($stmt, $types, ...$params);
    checkResult($result, $connect, "Ошибка установки параметров");

    $result = mysqli_stmt_execute($stmt);
    checkResult($result, $connect, "Ошибка выполнения запроса");

    return (string) mysqli_insert_id($connect);
}

function fetchPostById($post_id): array
{
    $query = "SELECT created_at as created_date,
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
    WHERE posts.id = ?";

    return fetchAssocData(prepareResult($query, "i", [$post_id]));
}

function addPost($post): string {
    $query = "INSERT INTO posts (
            created_at,
            title,
            content,
            author,
            picture_url,
            video_url,
            website,
            user_id,
            type_id
        ) VALUES (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?
        )";

    return preparePostResult($query, "sssssssii", $post);
}

function getTagByName($name): array {
    $query = "SELECT
        id
    FROM hashtags
    WHERE name = ?";

    return fetchAssocData(prepareResult($query, "s", [$name]));
}

function addNewTag($tag): string {
    $query = "INSERT INTO hashtags (
                name
            ) VALUES (
                ?
            )";

    return preparePostResult($query, "s", $tag);
}

function addPostTag($post_tag): string {
    $query = "INSERT INTO post_hashtags (
                post_id, hashtag_id
            ) VALUES (
                ?, ?
            )";

    return preparePostResult($query, "ii", $post_tag);
}

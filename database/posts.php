<?php

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

function fetchPostSubscribes($user_id): array
{
    $query = "SELECT
        created_at as created_date,
        posts.id,
        title,
        content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path
    FROM posts
        INNER JOIN subscribes s
            ON user_id = s.author_id
        INNER JOIN users
            ON user_id = users.id
    WHERE s.subscribe_id = ?";

    return fetchAssocData(prepareResult($query, "i", [$user_id]));
}

function fetchPostSubscribesByType($type_id, $user_id): array
{
    $query = "SELECT
        created_at as created_date,
        posts.id,
        title,
        content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path
    FROM posts
        INNER JOIN subscribes s
            ON user_id = s.author_id
        INNER JOIN users
            ON user_id = users.id
    WHERE type_id = ? AND s.subscribe_id = ?";

    return fetchAssocData(prepareResult($query, "ii", [$type_id, $user_id]));
}

function searchPosts($search): array
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
    WHERE MATCH(title, content) AGAINST(?)";

    return fetchData(prepareResult($query, "s", [$search]));
}

function searchPostsByHashtag($hashtag): array
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
        INNER JOIN post_hashtags ph
            ON posts.id = ph.post_id
        INNER JOIN hashtags h on ph.hashtag_id = h.id
    WHERE MATCH(h.name) AGAINST(?)
    ORDER BY created_at DESC";

    return fetchData(prepareResult($query, "s", [$hashtag]));
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

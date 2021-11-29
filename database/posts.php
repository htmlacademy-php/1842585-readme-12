<?php

// Запрос для типов постов
function fetchPostTypes($connect): array
{
    $query = "SELECT id, name, icon_class from content_types";

    return fetchData(prepareResult($connect, $query));
}

// Получаем шесть самых популярных постов и их авторов, а так же типы постов
function fetchPopularPosts($connect, $offset, $sort_field, $sort_direction): array
{
    $query = "SELECT
        posts.created_at as created_date,
        posts.id,
        title,
        posts.content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count
    FROM posts
        INNER JOIN users
            ON user_id = users.id
        LEFT JOIN likes l
            ON posts.id = l.post_id
        LEFT JOIN post_comments pc
            ON posts.id = pc.post_id
    GROUP BY
        posts.created_at,
        posts.id,
        posts.content,
        title,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id
    ORDER BY " . $sort_field . " " . $sort_direction . "
    LIMIT 6 OFFSET ?";

    return fetchData(prepareResult($connect, $query, "i", [$offset]));
}

function fetchPopularPostsByType($connect, $type_id, $offset, $sort_field, $sort_direction): array {
    $query = "SELECT
        posts.created_at as created_date,
        posts.id,
        title,
        posts.content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count
    FROM posts
        INNER JOIN users
            ON user_id = users.id
        LEFT JOIN likes l
            ON posts.id = l.post_id
        LEFT JOIN post_comments pc
            ON posts.id = pc.post_id
    WHERE type_id = ?
    GROUP BY
        posts.created_at,
        posts.id,
        title,
        posts.content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id
    ORDER BY " . $sort_field . " " . $sort_direction . "
    LIMIT 6 OFFSET ?";

    return fetchData(prepareResult($connect, $query, "ii", [$type_id, $offset]));
}

function fetchPostById($connect, $post_id): array
{
    $query = "SELECT
        posts.created_at as created_date,
        posts.id,
        title,
        posts.content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count
    FROM posts
        INNER JOIN users
            ON user_id = users.id
        LEFT JOIN likes l
            ON posts.id = l.post_id
        LEFT JOIN post_comments pc
            ON posts.id = pc.post_id
    WHERE posts.id = ?
    GROUP BY
        posts.created_at,
        posts.id,
        title,
        posts.content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id";

    return fetchAssocData(prepareResult($connect, $query, "i", [$post_id]));
}

function fetchPostSubscribes($connect, $user_id): array
{
    $query = "SELECT
        posts.created_at as created_date,
        posts.id,
        title,
        posts.content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count
    FROM posts
        INNER JOIN subscribes s
            ON user_id = s.author_id
        INNER JOIN users
            ON user_id = users.id
        LEFT JOIN likes l
            ON posts.id = l.post_id
        LEFT JOIN post_comments pc
            ON posts.id = pc.post_id
    WHERE s.subscribe_id = ?
    GROUP BY
        posts.created_at,
        posts.id,
        title,
        posts.content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id";

    return fetchData(prepareResult($connect, $query, "i", [$user_id]));
}

function fetchPostSubscribesByType($connect, $type_id, $user_id): array
{
    $query = "SELECT
        posts.created_at as created_date,
        posts.id,
        title,
        posts.content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count
    FROM posts
        INNER JOIN subscribes s
            ON user_id = s.author_id
        INNER JOIN users
            ON user_id = users.id
        LEFT JOIN likes l
            ON posts.id = l.post_id
        LEFT JOIN post_comments pc
            ON posts.id = pc.post_id
    WHERE type_id = ? AND s.subscribe_id = ?
    GROUP BY
        posts.created_at,
        posts.id,
        title,
        posts.content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id";

    return fetchData(prepareResult($connect, $query, "ii", [$type_id, $user_id]));
}

function getPostsByUserId($connect, $user_id): array
{
    $query = "SELECT
        posts.created_at as created_date,
        posts.id,
        title,
        posts.content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count
    FROM posts
        INNER JOIN users
            ON user_id = users.id
        LEFT JOIN likes l
            ON posts.id = l.post_id
        LEFT JOIN post_comments pc
            ON posts.id = pc.post_id
    WHERE posts.user_id = ?
    GROUP BY
        posts.created_at,
        posts.id,
        title,
        posts.content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id
    ORDER BY posts.created_at";

    return fetchData(prepareResult($connect, $query, "i", [$user_id]));
}

function searchPosts($connect, $search): array
{
    $query = "SELECT
        posts.created_at as created_date,
        posts.id,
        title,
        posts.content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count
    FROM posts
        INNER JOIN users
            ON user_id = users.id
        LEFT JOIN likes l
            ON posts.id = l.post_id
        LEFT JOIN post_comments pc
            ON posts.id = pc.post_id
    WHERE MATCH(title, posts.content) AGAINST(?)
    GROUP BY
        posts.created_at,
        posts.id,
        title,
        posts.content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id";

    return fetchData(prepareResult($connect, $query, "s", [$search]));
}

function searchPostsByHashtag($connect, $hashtag): array
{
    $query = "SELECT
        posts.created_at as created_date,
        posts.id,
        title,
        posts.content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count
    FROM posts
        INNER JOIN users
            ON user_id = users.id
        INNER JOIN post_hashtags ph
            ON posts.id = ph.post_id
        INNER JOIN hashtags h
            ON ph.hashtag_id = h.id
        LEFT JOIN likes l
            ON posts.id = l.post_id
        LEFT JOIN post_comments pc
            ON posts.id = pc.post_id
    WHERE MATCH(h.name) AGAINST(?)
    GROUP BY
        created_date,
        posts.id,
        title,
        posts.content,
        author,
        users.login,
        type_id,
        views_count,
        users.avatar_path,
        posts.user_id
    ORDER BY created_date DESC";

    return fetchData(prepareResult($connect, $query, "s", [$hashtag]));
}

function getPostsCountByUserId($connect, $user_id): array {
    $query = "SELECT
        COUNT(id) AS count
    FROM posts
    WHERE user_id = ?";

    return fetchAssocData(prepareResult($connect, $query, "i", [$user_id]));
}

function getPostsCount($connect): array {
    $query = "SELECT
        COUNT(id) AS count
    FROM posts";

    return fetchAssocData(prepareResult($connect, $query));
}

function getPostsCountByType($connect, $type_id): array {
    $query = "SELECT
        COUNT(id) AS count
    FROM posts
    WHERE type_id = ?";

    return fetchAssocData(prepareResult($connect, $query, "i", [$type_id]));
}

function getPostsColumns($connect): array {
    $query = "SHOW COLUMNS FROM posts";

    return fetchData(prepareResult($connect, $query));
}

function addPost($connect, $post): string {
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

    preparePostResult($connect, $query, "sssssssii", $post);

    return getInsertId($connect);
}

function updatePostViews($connect, $post_id): string {
    $query = "UPDATE posts
    SET views_count = views_count + 1
    WHERE id = ?";

    preparePostResult($connect, $query, "i", [$post_id]);

    return getInsertId($connect);
}

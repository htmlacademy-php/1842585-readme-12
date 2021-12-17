<?php

/**
 * Функция для получения массива типов постов
 * @param $connect
 * @return array
 */
function fetchPostTypes($connect): array
{
    $query = "SELECT id, name, icon_class from content_types";

    return fetchData(prepareResult($connect, $query));
}

/**
 * Функция для получения массива популярных постов
 * @param $connect
 * @param $offset
 * @param $sort_field
 * @param $sort_direction
 * @return array
 */
function fetchPopularPosts($connect, $offset, $sort_field, $sort_direction): array
{
    $query = "SELECT
        posts.created_at,
        posts.id,
        posts.title,
        posts.content,
        posts.author,
        users.login,
        posts.type_id,
        posts.views_count,
        users.avatar_path,
        posts.user_id,
        uspr.login as login_origin,
        uspr.avatar_path as avatar_path_origin,
        posts.user_id_original,
        posts.post_id_original,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count,
        COUNT(DISTINCT(pr.id)) AS reposts_count
    FROM posts
        INNER JOIN users
            ON user_id = users.id
        LEFT JOIN likes l
            ON posts.id = l.post_id
        LEFT JOIN post_comments pc
            ON posts.id = pc.post_id
        LEFT JOIN posts pr
            ON posts.id = pr.post_id_original
        LEFT JOIN users uspr
            ON posts.user_id_original = uspr.id
    GROUP BY
        created_at,
        id,
        content,
        title,
        author,
        login,
        type_id,
        views_count,
        avatar_path,
        user_id,
        login_origin,
        avatar_path_origin,
        user_id_original,
        post_id_original
    ORDER BY " . $sort_field . " " . $sort_direction . "
    LIMIT 6 OFFSET ?";

    return fetchData(prepareResult($connect, $query, "i", [$offset]));
}

/**
 * Функция для получения массива популярных постов по типу поста
 * @param $connect
 * @param $type_id
 * @param $offset
 * @param $sort_field
 * @param $sort_direction
 * @return array
 */
function fetchPopularPostsByType($connect, $type_id, $offset, $sort_field, $sort_direction): array
{
    $query = "SELECT
        posts.created_at,
        posts.id,
        posts.title,
        posts.content,
        posts.author,
        users.login,
        posts.type_id,
        posts.views_count,
        users.avatar_path,
        posts.user_id,
        uspr.login as login_origin,
        uspr.avatar_path as avatar_path_origin,
        posts.user_id_original,
        posts.post_id_original,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count,
        COUNT(DISTINCT(pr.id)) AS reposts_count
    FROM posts
        INNER JOIN users
            ON user_id = users.id
        LEFT JOIN likes l
            ON posts.id = l.post_id
        LEFT JOIN post_comments pc
            ON posts.id = pc.post_id
        LEFT JOIN posts pr
            ON posts.id = pr.post_id_original
        LEFT JOIN users uspr
            ON posts.user_id_original = uspr.id
    WHERE posts.type_id = ?
    GROUP BY
        created_at,
        id,
        content,
        title,
        author,
        login,
        type_id,
        views_count,
        avatar_path,
        user_id,
        login_origin,
        avatar_path_origin,
        user_id_original,
        post_id_original
    ORDER BY " . $sort_field . " " . $sort_direction . "
    LIMIT 6 OFFSET ?";

    return fetchData(prepareResult($connect, $query, "ii", [$type_id, $offset]));
}

/**
 * Функция для получения поста по идентификатору
 * @param $connect
 * @param $post_id
 * @return array
 */
function fetchPostById($connect, $post_id): array
{
    $query = "SELECT
        posts.created_at,
        posts.id,
        posts.title,
        posts.content,
        posts.author,
        users.login,
        posts.type_id,
        posts.views_count,
        users.avatar_path,
        posts.user_id,
        posts.picture_url,
        posts.video_url,
        posts.website,
        uspr.login as login_origin,
        uspr.avatar_path as avatar_path_origin,
        posts.user_id_original,
        posts.post_id_original,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count,
        COUNT(DISTINCT(pr.id)) AS reposts_count
    FROM posts
        INNER JOIN users
            ON user_id = users.id
        LEFT JOIN likes l
            ON posts.id = l.post_id
        LEFT JOIN post_comments pc
            ON posts.id = pc.post_id
        LEFT JOIN posts pr
            ON posts.id = pr.post_id_original
        LEFT JOIN users uspr
            ON posts.user_id_original = uspr.id
    WHERE posts.id = ?
    GROUP BY
        created_at,
        id,
        content,
        title,
        author,
        login,
        type_id,
        views_count,
        avatar_path,
        user_id,
        login_origin,
        avatar_path_origin,
        user_id_original,
        post_id_original,
        picture_url,
        video_url,
        website";

    return fetchAssocData(prepareResult($connect, $query, "i", [$post_id]));
}

/**
 * Функция для получения массива постов подписчиков
 * @param $connect
 * @param $user_id
 * @return array
 */
function fetchPostSubscribes($connect, $user_id): array
{
    $query = "SELECT
        posts.created_at,
        posts.id,
        posts.title,
        posts.content,
        posts.author,
        users.login,
        posts.type_id,
        posts.views_count,
        users.avatar_path,
        posts.user_id,
        uspr.login as login_origin,
        uspr.avatar_path as avatar_path_origin,
        posts.user_id_original,
        posts.post_id_original,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count,
        COUNT(DISTINCT(pr.id)) AS reposts_count
    FROM posts
        INNER JOIN subscribes s
            ON user_id = s.author_id
        INNER JOIN users
            ON user_id = users.id
        LEFT JOIN likes l
            ON posts.id = l.post_id
        LEFT JOIN post_comments pc
            ON posts.id = pc.post_id
        LEFT JOIN posts pr
            ON posts.id = pr.post_id_original
        LEFT JOIN users uspr
            ON posts.user_id_original = uspr.id
    WHERE s.subscribe_id = ?
    GROUP BY
        created_at,
        id,
        content,
        title,
        author,
        login,
        type_id,
        views_count,
        avatar_path,
        user_id,
        login_origin,
        avatar_path_origin,
        user_id_original,
        post_id_original";

    return fetchData(prepareResult($connect, $query, "i", [$user_id]));
}

/**
 * Функция для получения массива постов подписчиков по типу поста
 * @param $connect
 * @param $type_id
 * @param $user_id
 * @return array
 */
function fetchPostSubscribesByType($connect, $type_id, $user_id): array
{
    $query = "SELECT
        posts.created_at,
        posts.id,
        posts.title,
        posts.content,
        posts.author,
        users.login,
        posts.type_id,
        posts.views_count,
        users.avatar_path,
        posts.user_id,
        uspr.login as login_origin,
        uspr.avatar_path as avatar_path_origin,
        posts.user_id_original,
        posts.post_id_original,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count,
        COUNT(DISTINCT(pr.id)) AS reposts_count
    FROM posts
        INNER JOIN subscribes s
            ON user_id = s.author_id
        INNER JOIN users
            ON user_id = users.id
        LEFT JOIN likes l
            ON posts.id = l.post_id
        LEFT JOIN post_comments pc
            ON posts.id = pc.post_id
        LEFT JOIN posts pr
            ON posts.id = pr.post_id_original
        LEFT JOIN users uspr
            ON posts.user_id_original = uspr.id
    WHERE posts.type_id = ? AND s.subscribe_id = ?
    GROUP BY
        created_at,
        id,
        content,
        title,
        author,
        login,
        type_id,
        views_count,
        avatar_path,
        user_id,
        login_origin,
        avatar_path_origin,
        user_id_original,
        post_id_original";

    return fetchData(prepareResult($connect, $query, "ii", [$type_id, $user_id]));
}

/**
 * Функция для получения массива постов пользователя
 * @param $connect
 * @param $user_id
 * @return array
 */
function getPostsByUserId($connect, $user_id): array
{
    $query = "SELECT
        posts.created_at,
        posts.id,
        posts.title,
        posts.content,
        posts.author,
        users.login,
        posts.type_id,
        posts.views_count,
        users.avatar_path,
        posts.user_id,
        uspr.login as login_origin,
        uspr.avatar_path as avatar_path_origin,
        posts.user_id_original,
        posts.post_id_original,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count,
        COUNT(DISTINCT(pr.id)) AS reposts_count
    FROM posts
        INNER JOIN users
            ON user_id = users.id
        LEFT JOIN likes l
            ON posts.id = l.post_id
        LEFT JOIN post_comments pc
            ON posts.id = pc.post_id
        LEFT JOIN posts pr
            ON posts.id = pr.post_id_original
        LEFT JOIN users uspr
            ON posts.user_id_original = uspr.id
    WHERE posts.user_id = ?
    GROUP BY
        created_at,
        id,
        content,
        title,
        author,
        login,
        type_id,
        views_count,
        avatar_path,
        user_id,
        login_origin,
        avatar_path_origin,
        user_id_original,
        post_id_original
    ORDER BY posts.created_at";

    return fetchData(prepareResult($connect, $query, "i", [$user_id]));
}

/**
 * Функция для поиска постов
 * @param $connect
 * @param $search
 * @return array
 */
function searchPosts($connect, $search): array
{
    $query = "SELECT
        posts.created_at,
        posts.id,
        posts.title,
        posts.content,
        posts.author,
        users.login,
        posts.type_id,
        posts.views_count,
        users.avatar_path,
        posts.user_id,
        uspr.login as login_origin,
        uspr.avatar_path as avatar_path_origin,
        posts.user_id_original,
        posts.post_id_original,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count,
        COUNT(DISTINCT(pr.id)) AS reposts_count
    FROM posts
        INNER JOIN users
            ON user_id = users.id
        LEFT JOIN likes l
            ON posts.id = l.post_id
        LEFT JOIN post_comments pc
            ON posts.id = pc.post_id
        LEFT JOIN posts pr
            ON posts.id = pr.post_id_original
        LEFT JOIN users uspr
            ON posts.user_id_original = uspr.id
    WHERE MATCH(posts.title, posts.content) AGAINST(?)
    GROUP BY
        created_at,
        id,
        content,
        title,
        author,
        login,
        type_id,
        views_count,
        avatar_path,
        user_id,
        login_origin,
        avatar_path_origin,
        user_id_original,
        post_id_original";

    return fetchData(prepareResult($connect, $query, "s", [$search]));
}

/**
 * Функция для поиска постов по тегу
 * @param $connect
 * @param $hashtag
 * @return array
 */
function searchPostsByHashtag($connect, $hashtag): array
{
    $query = "SELECT
        posts.created_at,
        posts.id,
        posts.title,
        posts.content,
        posts.author,
        users.login,
        posts.type_id,
        posts.views_count,
        users.avatar_path,
        posts.user_id,
        uspr.login as login_origin,
        uspr.avatar_path as avatar_path_origin,
        posts.user_id_original,
        posts.post_id_original,
        COUNT(DISTINCT(l.id)) AS likes_count,
        COUNT(DISTINCT(pc.id)) AS comments_count,
        COUNT(DISTINCT(pr.id)) AS reposts_count
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
        LEFT JOIN posts pr
            ON posts.id = pr.post_id_original
        LEFT JOIN users uspr
            ON posts.user_id_original = uspr.id
    WHERE MATCH(h.name) AGAINST(?)
    GROUP BY
        created_at,
        id,
        content,
        title,
        author,
        login,
        type_id,
        views_count,
        avatar_path,
        user_id,
        login_origin,
        avatar_path_origin,
        user_id_original,
        post_id_original
    ORDER BY created_at DESC";

    return fetchData(prepareResult($connect, $query, "s", [$hashtag]));
}

/**
 * Функция для получения количества постов пользователя
 * @param $connect
 * @param $user_id
 * @return array
 */
function getPostsCountByUserId($connect, $user_id): array
{
    $query = "SELECT
        COUNT(id) AS count
    FROM posts
    WHERE user_id = ?";

    return fetchAssocData(prepareResult($connect, $query, "i", [$user_id]));
}

/**
 * Функция для получения общего количества постов
 * @param $connect
 * @return array
 */
function getPostsCount($connect): array
{
    $query = "SELECT
        COUNT(id) AS count
    FROM posts";

    return fetchAssocData(prepareResult($connect, $query));
}

/**
 * Функция для получения количества постов по типу поста
 * @param $connect
 * @param $type_id
 * @return array
 */
function getPostsCountByType($connect, $type_id): array
{
    $query = "SELECT
        COUNT(id) AS count
    FROM posts
    WHERE type_id = ?";

    return fetchAssocData(prepareResult($connect, $query, "i", [$type_id]));
}

/**
 * Функция для получения массива постов созданных с помощью репоста
 * @param $connect
 * @param $user_id
 * @param $user_id_original
 * @param $post_id_original
 * @return array
 */
function getPostsRepost($connect, $user_id, $user_id_original, $post_id_original): array
{
    $query = "SELECT
        id AS count
    FROM posts
    WHERE user_id = ? AND user_id_original = ? AND post_id_original = ?";

    return fetchAssocData(prepareResult($connect, $query, "iii", [$user_id, $user_id_original, $post_id_original]));
}

/**
 * Функция для добавления поста
 * @param $connect
 * @param $post
 * @return string
 */
function addPost($connect, $post): string
{
    $query = "INSERT INTO posts (
            created_at,
            title,
            content,
            author,
            picture_url,
            video_url,
            website,
            user_id,
            type_id,
            post_id_original,
            user_id_original
        ) VALUES (
            ?,
            ?,
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

    preparePostResult($connect, $query, "sssssssiiii", $post);

    return getInsertId($connect);
}

/**
 * Функция для обновления счетчика просмотров поста
 * @param $connect
 * @param $post_id
 * @return string
 */
function updatePostViews($connect, $post_id): string
{
    $query = "UPDATE posts
    SET views_count = views_count + 1
    WHERE id = ?";

    preparePostResult($connect, $query, "i", [$post_id]);

    return getInsertId($connect);
}

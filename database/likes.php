<?php

/**
 * Функция для получения массива лайков поставленных пользователю
 * @param $connect
 * @param $user_id
 * @return array
 */
function getLikesByUserId($connect, $user_id): array
{
    $query = "SELECT
       l.id,
       l.user_id,
       u.login,
       u.avatar_path,
       l.like_at,
       l.post_id,
       p.type_id,
       p.content,
       p.preview,
       p.title
    FROM likes l
        JOIN users u
            ON u.id = l.user_id
        JOIN posts p
            ON p.id = l.post_id
    WHERE p.user_id = ?
    ORDER BY l.like_at DESC";

    return fetchData(prepareResult($connect, $query, "i", [$user_id]));
}

/**
 * Функция для получения массива лайков пользователя
 * @param $connect
 * @param $user_id
 * @return array
 */
function getUserLikes($connect, $user_id): array
{
    $query = "SELECT
       id,
       post_id,
       like_at
    FROM likes
    WHERE user_id = ?";

    return fetchData(prepareResult($connect, $query, "i", [$user_id]));
}

/**
 * Функция для получения лайка пользователя, поставленного посту
 * @param $connect
 * @param $user_id
 * @param $post_id
 * @return array
 */
function getUserLike($connect, $user_id, $post_id): array
{
    $query = "SELECT
       id,
       like_at
    FROM likes
    WHERE user_id = ? AND post_id = ?";

    return fetchAssocData(prepareResult($connect, $query, "ii", [$user_id, $post_id]));
}

/**
 * Функция для добавления лайка в базу
 * @param $connect
 * @param $like
 * @return string
 */
function addLike($connect, $like): string
{
    $query = "INSERT INTO likes (
            user_id,
            post_id,
            like_at
        ) VALUES (
            ?,
            ?,
            ?
        )";

    preparePostResult($connect, $query, "iis", $like);

    return getInsertId($connect);
}

/**
 * Функция удаления лайка из базы
 * @param $connect
 * @param $like_id
 * @return bool
 */
function deleteLike($connect, $like_id): bool
{
    $query = "DELETE
    FROM likes
    WHERE id = ?";

    return preparePostResult($connect, $query, "i", [$like_id]);
}

<?php

/**
 * Функция для получения массива с комментариями по идентификатору поста
 * @param $connect
 * @param $post_id
 * @return array
 */
function getCommentsByPostId($connect, $post_id): array
{
    $query = "SELECT
        pc.id,
        created_at,
        content,
        user_id,
        post_id,
        u.avatar_path,
        u.login
    FROM post_comments pc
        JOIN users u
            ON u.id = pc.user_id
    WHERE post_id = ?
    ORDER BY created_at DESC";

    return fetchData(prepareResult($connect, $query, "i", [$post_id]));
}

/**
 * Функция для получения массива из двух комментариев по идентификатору поста
 * @param $connect
 * @param $post_id
 * @return array
 */
function getTwoCommentsByPostId($connect, $post_id): array
{
    $query = "SELECT
        pc.id,
        created_at,
        content,
        user_id,
        post_id,
        u.avatar_path,
        u.login
    FROM post_comments pc
        JOIN users u
            ON u.id = pc.user_id
    WHERE post_id = ?
    ORDER BY created_at DESC
    LIMIT 2";

    return fetchData(prepareResult($connect, $query, "i", [$post_id]));
}

/**
 * Функция для добавления комментария в базу
 * @param $connect
 * @param $comment
 * @return string
 */
function addComment($connect, $comment): string
{
    $query = "INSERT INTO post_comments (
                created_at,
                content,
                user_id,
                post_id
            ) VALUES (
                ?,
                ?,
                ?,
                ?
            )";

    preparePostResult($connect, $query, "ssii", $comment);

    return getInsertId($connect);
}

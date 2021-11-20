<?php

function getLikes($connect): array
{
    $query = "SELECT
       id,
       user_id,
       post_id,
       like_at
    FROM likes";

    return fetchData(prepareResult($connect, $query));
}

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

    return fetchData(prepareResult($connect, $query,"i" , [$user_id]));
}

function getUserLikes($connect, $user_id): array
{
    $query = "SELECT
       id,
       post_id,
       like_at
    FROM likes
    WHERE user_id = ?";

    return fetchData(prepareResult($connect, $query,"i" , [$user_id]));
}

function getLikesCountByUserId($connect, $user_id): array {
    $query = "SELECT
       COUNT(l.id) AS likes_count
    FROM likes l
        JOIN posts p
            ON p.id = l.post_id
    WHERE p.user_id = ?";

    return fetchAssocData(prepareResult($connect, $query,"i" , [$user_id]));
}

function getUserLike($connect, $user_id, $post_id): array {
    $query = "SELECT
       id,
       like_at
    FROM likes
    WHERE user_id = ? AND post_id = ?";

    return fetchAssocData(prepareResult($connect, $query,"ii" , [$user_id, $post_id]));
}

function addLike($connect, $like): string {
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

function deleteLike($connect, $like_id): bool {
    $query = "DELETE
    FROM likes
    WHERE id = ?";

    return preparePostResult($connect, $query, "i", [$like_id]);
}

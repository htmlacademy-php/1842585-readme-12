<?php

/**
 * Функция для получения массива тегов поста
 * @param $connect
 * @param $post_id
 * @return array
 */
function getPostTags($connect, $post_id): array
{
    $query = "SELECT
        ph.id,
        ph.post_id,
        h.name
    FROM post_hashtags ph
    INNER JOIN hashtags h
        ON ph.hashtag_id = h.id
    WHERE post_id = ?";

    return fetchData(prepareResult($connect, $query, "s", [$post_id]));
}

/**
 * Функция для получения массива тегов всех постов
 * @param $connect
 * @return array
 */
function getPostsTags($connect): array
{
    $query = "SELECT
        ph.id,
        ph.post_id,
        h.name
    FROM post_hashtags ph
    INNER JOIN hashtags h
        ON ph.hashtag_id = h.id";

    return fetchData(prepareResult($connect, $query));
}

/**
 * Функция для получения тега по имени
 * @param $connect
 * @param $name
 * @return array
 */
function getTagByName($connect, $name): array
{
    $query = "SELECT
        id
    FROM hashtags
    WHERE name = ?";

    return fetchAssocData(prepareResult($connect, $query, "s", [$name]));
}

/**
 * Функция для добавления тега
 * @param $connect
 * @param $tag
 * @return string
 */
function addNewTag($connect, $tag): string
{
    $query = "INSERT INTO hashtags (
                name
            ) VALUES (
                ?
            )";

    preparePostResult($connect, $query, "s", $tag);

    return getInsertId($connect);
}

/**
 * Функция для привязки тега к посту
 * @param $connect
 * @param $post_tag
 * @return string
 */
function addPostTag($connect, $post_tag): string
{
    $query = "INSERT INTO post_hashtags (
                post_id, hashtag_id
            ) VALUES (
                ?, ?
            )";

    preparePostResult($connect, $query, "ii", $post_tag);

    return getInsertId($connect);
}

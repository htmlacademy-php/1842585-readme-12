<?php

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

function getTagByName($connect, $name): array
{
    $query = "SELECT
        id
    FROM hashtags
    WHERE name = ?";

    return fetchAssocData(prepareResult($connect, $query, "s", [$name]));
}

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

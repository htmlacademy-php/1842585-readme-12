<?php

function getTagByName($connect, $name): array {
    $query = "SELECT
        id
    FROM hashtags
    WHERE name = ?";

    return fetchAssocData(prepareResult($connect, $query, "s", [$name]));
}

function addNewTag($connect, $tag): string {
    $query = "INSERT INTO hashtags (
                name
            ) VALUES (
                ?
            )";

    preparePostResult($connect, $query, "s", $tag);

    return getInsertId($connect);
}

function addPostTag($connect, $post_tag): string {
    $query = "INSERT INTO post_hashtags (
                post_id, hashtag_id
            ) VALUES (
                ?, ?
            )";

    preparePostResult($connect, $query, "ii", $post_tag);

    return getInsertId($connect);
}

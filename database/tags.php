<?php

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

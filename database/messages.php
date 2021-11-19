<?php

function addMessage($connect, $message): string {
    $query = "INSERT INTO messages (
            created_at,
            content,
            user_id,
            recipient_id
        ) VALUES (
            ?,
            ?,
            ?,
            ?
        )";

    preparePostResult($connect, $query, "ssii", $message);

    return getInsertId($connect);
}

<?php

function getMessagesByRecipient($connect, $user_id, $recipient_id): array
{
    $query = "SELECT
            m.id,
            m.created_at,
            m.content,
            m.user_id,
            u.login,
            u.avatar_path,
            m.recipient_id,
            ur.login as recipient_login,
            ur.avatar_path as avatar_path_recipient,
            0 as unread_count
    FROM messages m
    INNER JOIN users u
        ON m.user_id = u.id
    INNER JOIN users ur
        ON m.recipient_id = ur.id
    WHERE (m.user_id = ? AND m.recipient_id = ?) OR (m.user_id = ? AND m.recipient_id = ?)
    ORDER BY
        m.created_at";

    return fetchData(prepareResult($connect, $query, "iiii", [$user_id, $recipient_id, $recipient_id, $user_id]));
}

function getLastMessagesEveryRecipient($connect, $user_id): array
{
    $query = "SELECT
        m.id,
        m.created_at,
        m.content,
        m.user_id,
        u.login,
        u.avatar_path,
        m.recipient_id,
        ur.login as recipient_login,
        ur.avatar_path as avatar_path_recipient,
        unrm.unread_count
    FROM messages m
             INNER JOIN users u
                        ON m.user_id = u.id
             INNER JOIN users ur
                        ON m.recipient_id = ur.id
             INNER JOIN (SELECT
                             mes.user_id,
                             mes.recipient_id,
                             MAX(mes.id) as last_message_id
                         FROM (SELECT
                                   user_id,
                                   recipient_id,
                                   id
                               FROM messages WHERE user_id = ?
                               UNION
                               SELECT
                                   recipient_id,
                                   user_id,
                                   id
                               FROM messages WHERE recipient_id = ?) mes
                         GROUP BY
                             mes.user_id,
                             mes.recipient_id
                 ) ml
                        ON (m.user_id = ml.recipient_id AND
                            m.recipient_id = ml.user_id OR
                            m.user_id = ml.user_id AND
                            m.recipient_id = ml.recipient_id) AND m.id = ml.last_message_id
             LEFT JOIN (SELECT
                            unrm.recipient_id,
                            unrm.user_id,
                            COUNT(unrm.id) as unread_count
                        FROM messages unrm
                        WHERE is_read = 0
                        GROUP BY
                            unrm.recipient_id,
                            unrm.user_id
    ) unrm
                       ON ml.user_id = unrm.recipient_id AND ml.recipient_id = unrm.user_id";

    return fetchData(prepareResult($connect, $query, "ii", [$user_id, $user_id]));
}

function getAllUnreadMessages($connect, $user_id): array
{
    $query = "SELECT
        COUNT(m.id) as count
    FROM messages m
    WHERE m.recipient_id = ? AND m.is_read = 0";

    return fetchAssocData(prepareResult($connect, $query, "i", [$user_id]));
}

function addMessage($connect, $message): string
{
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

function updateStatusMessage($connect, $message_id): string
{
    $query = "UPDATE messages
    SET is_read = 1
    WHERE id = ?";

    preparePostResult($connect, $query, "i", [$message_id]);

    return getInsertId($connect);
}

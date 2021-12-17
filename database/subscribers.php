<?php

function getSubscribersByUserId($connect, $user_id): array
{
    $query = "SELECT
       u.id,
       u.registered_at,
       u.email,
       u.login,
       u.avatar_path,
       COUNT(DISTINCT(s2.id)) AS subscribers_count,
       COUNT(DISTINCT(p.id)) AS posts_count
    FROM subscribes s
        INNER JOIN users u
            ON u.id = s.subscribe_id
        LEFT JOIN subscribes s2
            ON u.id = s2.author_id
        LEFT JOIN posts p on u.id = p.user_id
    WHERE s.author_id = ?
    GROUP BY
        u.id,
        u.registered_at,
        u.email,
        u.login,
        u.avatar_path";

    return fetchData(prepareResult($connect, $query, "i", [$user_id]));
}

function getSubscription($connect, $user_id, $author_id): array
{
    $query = "SELECT
       id,
       subscribe_at
    FROM subscribes
    WHERE subscribe_id = ? AND author_id = ?";

    return fetchAssocData(prepareResult($connect, $query, "ii", [$user_id, $author_id]));
}

function getSubscriptionsByUserId($connect, $user_id): array
{
    $query = "SELECT
       id,
       author_id,
       subscribe_at
    FROM subscribes
    WHERE subscribe_id = ?";

    return fetchData(prepareResult($connect, $query, "i", [$user_id]));
}

function getSubscribersCountByUserId($connect, $user_id): array
{
    $query = "SELECT
       COUNT(s.id) AS count
    FROM subscribes s
        INNER JOIN users u on u.id = s.subscribe_id
    WHERE author_id = ?";

    return fetchAssocData(prepareResult($connect, $query, "i", [$user_id]));
}

function addSubscription($connect, $subscription): string
{
    $query = "INSERT INTO subscribes (
            author_id,
            subscribe_id,
            subscribe_at
        ) VALUES (
            ?,
            ?,
            ?
        )";

    preparePostResult($connect, $query, "iis", $subscription);

    return getInsertId($connect);
}

function deleteSubscription($connect, $subscription_id): bool
{
    $query = "DELETE
    FROM subscribes
    WHERE id = ?";

    return preparePostResult($connect, $query, "i", [$subscription_id]);
}

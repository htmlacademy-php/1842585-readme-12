<?php

function getUserByEmail($connect, $email): array
{
    $query = "SELECT
        u.id,
        u.registered_at,
        u.email,
        u.login,
        u.password,
        u.avatar_path,
        COUNT(DISTINCT(s.id)) AS subscribers_count,
        COUNT(DISTINCT(p.id)) AS posts_count
    FROM users u
    LEFT JOIN subscribes s
        ON u.id = s.author_id
    LEFT JOIN posts p
        ON u.id = p.user_id
    WHERE u.email = ?
    GROUP BY
        u.id,
        u.registered_at,
        u.email,
        u.login,
        u.password,
        u.avatar_path";

    return fetchAssocData(prepareResult($connect, $query, "s", [$email]));
}

function getUserByLoginOrEmail($connect, $login): array
{
    $query = "SELECT
        u.id,
        u.registered_at,
        u.email,
        u.login,
        u.password,
        u.avatar_path,
        COUNT(DISTINCT(s.id)) AS subscribers_count,
        COUNT(DISTINCT(p.id)) AS posts_count
    FROM users u
    LEFT JOIN subscribes s
        ON u.id = s.author_id
    LEFT JOIN posts p
        ON u.id = p.user_id
    WHERE u.login = ? OR u.email = ?
    GROUP BY
        u.id,
        u.registered_at,
        u.email,
        u.login,
        u.password,
        u.avatar_path";

    return fetchAssocData(prepareResult($connect, $query, "ss", [$login, $login]));
}

function getUserById($connect, $id): array
{
    $query = "SELECT
        u.id,
        u.registered_at,
        u.email,
        u.login,
        u.avatar_path,
        COUNT(DISTINCT(s.id)) AS subscribers_count,
        COUNT(DISTINCT(p.id)) AS posts_count
    FROM users u
    LEFT JOIN subscribes s
        ON u.id = s.author_id
    LEFT JOIN posts p
        ON u.id = p.user_id
    WHERE u.id = ?
    GROUP BY
        u.id,
        u.registered_at,
        u.email,
        u.login,
        u.avatar_path";

    return fetchAssocData(prepareResult($connect, $query, "i", [$id]));
}

function addUser($connect, $user): string {
    $query = "INSERT INTO users (
            registered_at,
            email,
            login,
            password,
            avatar_path
        ) VALUES (
            ?,
            ?,
            ?,
            ?,
            ?
        )";

    preparePostResult($connect, $query, "sssss", $user);

    return getInsertId($connect);
}

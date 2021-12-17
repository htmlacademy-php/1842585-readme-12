<?php

/**
 * Функция для получения пользователя по email
 * @param $connect
 * @param $email
 * @return array
 */
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

/**
 * Функция для получения пользователя по email или логину
 * @param $connect
 * @param $login
 * @return array
 */
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

/**
 * Функция для получения пользователя по идентификатору
 * @param $connect
 * @param $id
 * @return array
 */
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

/**
 * Функция для добавления пользователя
 * @param $connect
 * @param $user
 * @return string
 */
function addUser($connect, $user): string
{
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

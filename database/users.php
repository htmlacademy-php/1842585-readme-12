<?php

function getUserByEmail($connect, $email): array
{
    $query = "SELECT
        id,
        registered_at,
        email,
        login,
        password,
        avatar_path
    FROM users
    WHERE email = ?";

    return fetchAssocData(prepareResult($connect, $query, "s", [$email]));
}

function getUserByLoginOrEmail($connect, $login): array
{
    $query = "SELECT
        id,
        registered_at,
        email,
        login,
        password,
        avatar_path
    FROM users
    WHERE login = ? OR email = ?";

    return fetchAssocData(prepareResult($connect, $query, "ss", [$login, $login]));
}

function getUserById($connect, $id): array
{
    $query = "SELECT
        id,
        registered_at,
        email,
        login,
        avatar_path
    FROM users
    WHERE id = ?";

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

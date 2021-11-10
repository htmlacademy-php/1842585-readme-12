<?php

function getUserByEmail($email): array
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

    return fetchAssocData(prepareResult($query, "s", [$email]));
}

function getUserByLoginOrEmail($login): array
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

    return fetchAssocData(prepareResult($query, "ss", [$login, $login]));
}

function addUser($user): string {
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

    return preparePostResult($query, "sssss", $user);
}

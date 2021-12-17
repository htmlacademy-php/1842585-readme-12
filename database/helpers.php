<?php

function checkResult($result, $connect, $err_message): void
{
    if ($result === false) {
        die($err_message . ": " . mysqli_error($connect));
    }
}

function fetchData($result): array
{
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function fetchAssocData($result): array
{
    $result = mysqli_fetch_assoc($result);
    return $result ?? [];
}

function prepareResult($connect, $query, $types = "", $params = []): mysqli_result
{

    $stmt = mysqli_prepare($connect, $query);
    checkResult($stmt, $connect, "Ошибка подготовки запроса");

    if ($types !== "") {
        $result = mysqli_stmt_bind_param($stmt, $types, ...$params);
        checkResult($result, $connect, "Ошибка установки параметров");
    }

    $result = mysqli_stmt_execute($stmt);
    checkResult($result, $connect, "Ошибка выполнения запроса");

    $result = mysqli_stmt_get_result($stmt);
    checkResult($result, $connect, "Ошибка получения данных");

    return $result;
}

function preparePostResult($connect, $query, $types, $params): bool
{

    $stmt = mysqli_prepare($connect, $query);
    checkResult($stmt, $connect, "Ошибка подготовки запроса");

    $result = mysqli_stmt_bind_param($stmt, $types, ...$params);
    checkResult($result, $connect, "Ошибка установки параметров");

    $result = mysqli_stmt_execute($stmt);
    checkResult($result, $connect, "Ошибка выполнения запроса");

    return $result;
}

function getInsertId($connect): string
{
    return (string) mysqli_insert_id($connect);
}

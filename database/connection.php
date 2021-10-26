<?php

$connect = mysqli_connect("localhost", "root", "root", "readme");
if ($connect === false) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

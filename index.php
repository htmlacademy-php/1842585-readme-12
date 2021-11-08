<?php
session_start();
require_once("functions.php");

if (count(getUserAuthentication()) > 0) {
    redirectTo("/feed.php");
}

redirectTo("/login.php");

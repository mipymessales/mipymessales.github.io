<?php

session_start();

include_once "Host.php";
if (!isset($_SESSION['user'])) {
    header("Location:" .   Host::getHOSTNAME(). "login.php");
    exit();
}


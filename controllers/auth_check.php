<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['idrestaurant']) || intval($_SESSION['idrestaurant'])<=0) {
    header("Location: /login");
    exit();
}


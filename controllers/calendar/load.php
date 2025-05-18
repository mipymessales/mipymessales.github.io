<?php

// load.php
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,3).'/');
include_once ROOT_DIR."pdo/conexion.php";
global $base_de_datos;
$stmt = $base_de_datos->prepare("SELECT * FROM events");
$stmt->execute();

$events = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $events[] = [
        'id' => $row['id'],
        'id_mesa' => $row['id_mesa'],
        'title' => $row['title'],
        'start' => $row['start'],
        'end' => $row['end']
    ];
}

header('Content-Type: application/json');
echo json_encode($events);


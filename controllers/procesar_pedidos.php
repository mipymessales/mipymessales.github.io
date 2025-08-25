<?php

define('ROOT_DIR', dirname(__FILE__, 2) . '/');
include_once ROOT_DIR . "pdo/conexion.php";
require_once ROOT_DIR . "controllers/class.SqlInjectionUtils.php";
global $base_de_datos;
try {
    // Obtener pedidos pendientes
    $sql = "SELECT * FROM pedidos_pendientes WHERE estado = 'PENDIENTE'";
    $stmt = $base_de_datos->query($sql);
    $pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($pendientes as $pedido) {
        $base_de_datos->beginTransaction();

        // 1. Insertar cliente
        $clienteStmt = $base_de_datos->prepare("
            INSERT INTO cliente (full_name, restaurantid, id_mesa) 
            VALUES (:nombre, :restaurantid, 0)
        ");
        $clienteStmt->execute([
            ":nombre" => $pedido["nombre"],
            ":restaurantid" => $pedido["restaurantid"]
        ]);
        $idcliente = $base_de_datos->lastInsertId();

        // 2. Insertar cada producto del carrito
        $carrito = json_decode($pedido["carrito"], true);
        if (is_array($carrito)) {
            foreach ($carrito as $item) {
                $detalleStmt = $base_de_datos->prepare("
                    INSERT INTO pedidoscliente 
                    (id_cliente, id_plato, nombre, cantidad, precioventa, telefono, direccion, correo, restaurantid, categoria, ip)
                    VALUES (:idcliente, :idplato, :nombre, :cantidad, :precioventa, :telefono, :direccion, :correo, :restaurantid, :categoria, :ip)
                ");
                $detalleStmt->execute([
                    ":idcliente" => $idcliente,
                    ":idplato" => $item['id'],
                    ":nombre" => $pedido["nombre"],
                    ":cantidad" => $item['cantidad'],
                    ":precioventa" => $item['precio'],
                    ":telefono" => $pedido["telefono"],
                    ":direccion" => $pedido["direccion"],
                    ":correo" => $pedido["correo"],
                    ":restaurantid" => $pedido["restaurantid"],
                    ":categoria" => $item['categoria'],
                    ":ip" =>  $pedido["ip"] ?? ""
                ]);
            }
        }

        // 3. Marcar pedido como procesado
        $update = $base_de_datos->prepare("UPDATE pedidos_pendientes SET estado = 'PROCESADO' WHERE id = :id");
        $update->execute([":id" => $pedido["id"]]);

        $base_de_datos->commit();
    }

    echo "Procesados " . count($pendientes) . " pedidos\n";

} catch (Exception $e) {
    if ($base_de_datos->inTransaction()) {
        $base_de_datos->rollBack();
    }
    error_log("Error al procesar pedidos: " . $e->getMessage());
    echo "Error: " . $e->getMessage();
}

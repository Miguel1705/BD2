<?php

include('db_connect.php');
session_start();

if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    die();
}
if (isset($_GET['id_producto'])) {
    $id_producto = mysqli_real_escape_string($conn, $_GET['id_producto']);

    $query = "SELECT PRECIO_VENTA_ACT, VALOR_IVA FROM producto WHERE ID_PRODUCTO = '$id_producto'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $precio_venta = $row['PRECIO_VENTA_ACT'];
        $valor_iva = $row['VALOR_IVA'];

        // Devuelve los resultados en formato JSON
        echo json_encode(['precio_venta' => $precio_venta, 'valor_iva' => $valor_iva]);
    } else {
        // Maneja el error si la consulta no es exitosa
        echo json_encode(['error' => 'Error en la consulta']);
    }
} else {
    // Maneja el caso en que no se proporciona el ID_PRODUCTO
    echo json_encode(['error' => 'ID_PRODUCTO no proporcionado']);
}

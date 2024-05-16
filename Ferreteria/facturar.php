<?php
include('db_connect.php');
session_start();

if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Facturas</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background: url('frt.jpg') center center fixed;
                background-size: cover;
            }

            h1 {
                text-align: center;
                color: white;
            }

            .menu {
                background-color: #333;
                overflow: hidden;
            }

            .menu a {
                float: left;
                display: block;
                color: white;
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
            }

            .menu a:hover {
                background-color: #ddd;
                color: black;
            }

            button {
                background-color: #4CAF50;
                color: white;
                padding: 10px 15px;
                border: none;
                cursor: pointer;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                background-color: white;
            }

            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #333;
                color: white;
            }

            tr:hover {
                background-color: white;
            }

            a {
                text-decoration: none;
            }

            button a {
                color: white;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <h1>Facturas - Ferrimundo</h1>
        <div class="menu">
            <a href="welcome.php">Inicio</a>
            <a href="productos.php">Inventario</a>
            <a href="cliente.php">Clientes</a>      
        </div>

        <button onclick="location.href = 'nueva_factura.php'">Nueva Factura</button>

        <!-- Agrega la tabla para mostrar clientes -->
        <table>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Tipo de factura</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Descuento</th>
                <th>IVA</th>
                <th>Subtotal</th>
                <th>Saldo</th>         
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            <?php
            // Realiza la consulta a la base de datos para obtener las facturas
            $query = "SELECT * FROM factura";
            $result = mysqli_query($conn, $query);

            // Muestra las facturas en la tabla
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['ID_FACTURA']}</td>";
                echo "<td>{$row['FECHA_FACTURA']}</td>";
                echo "<td>{$row['ID_TIPOFAC']}</td>";
                echo "<td>{$row['CLIENTE_FACTURA']}</td>";
                echo "<td>{$row['TOTAL_FACTURA']}</td>";
                echo "<td>{$row['DESCUENTO_FACTURA']}</td>";
                echo "<td>{$row['IVA_FACTURA']}</td>";
                echo "<td>{$row['SUBTOTAL_FACTURA']}</td>";
                echo "<td>{$row['SALDO_FACTURA']}</td>";
                echo "<td>{$row['ESTADO_FACTURA']}</td>";
                echo "<td><a href='agregar_detalle.php?id={$row['ID_FACTURA']}'><button>Agregar Detalle</button></a></td>";                
                echo "</tr>";
            }
            ?>
        </table>
    </body>
</html>
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
   <title>Inventario - Ferrimundo</title>
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
   <h1>Inventario - Ferrimundo</h1>
   <div class="menu">
      <a href="welcome.php">Inicio</a>
      <a href="facturar.php">Facturar</a>
      <a href="cliente.php">Cliente</a>
   </div>

   <button onclick="location.href='agregar_producto.php'">Agregar Producto</button>

   <!-- Agrega la tabla para mostrar productos -->
   <table>
       <tr>
           <th>ID</th>
           <th>Código</th>
           <th>Descripción</th>
           <th>Existencia</th>
           <th>Bodega</th>
           <th>Precio Actual</th>
           <th>Precio Anterior</th>
           <th>Costo</th>
           <th>Margen de utilidad</th>
           <th>Stock max</th>
           <th>Stock min</th>
           <th>Descuento max</th>
           <th>Descuento min</th>
           <th>IVA</th>
           <th>Estado</th>
           <th>Acciones</th>
       </tr>
      <?php
   // Realiza la consulta a la base de datos para obtener los productos
   $query = "SELECT * FROM producto";
   $result = mysqli_query($conn, $query);

   // Muestra los productos en la tabla
   while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td>{$row['ID_PRODUCTO']}</td>";
      echo "<td>{$row['COD_PRODUCTO']}</td>";
      echo "<td>{$row['DES_PRODUCTO']}</td>";
      echo "<td>{$row['EXIS_PRODUCTO']}</td>";
      echo "<td>{$row['BODEGA_PRODUCTO']}</td>";
      echo "<td>{$row['PRECIO_VENTA_ACT']}</td>";
      echo "<td>{$row['PRECIO_VENTA_ANT']}</td>";
      echo "<td>{$row['COSTO_VENTA']}</td>";
      echo "<td>{$row['MARGEN_UTILIDAD']}</td>";
      echo "<td>{$row['STOCK_MAXIMO']}</td>";
      echo "<td>{$row['STOCK_MINIMO']}</td>";
      echo "<td>{$row['DSCTO_MAXIMO']}</td>";
      echo "<td>{$row['DSCTO_MINIMO']}</td>";
      echo "<td>{$row['VALOR_IVA']}</td>";
      echo "<td>{$row['ESTADO_PRODUCTO']}</td>";
      echo "<td><a href='editar_producto.php?id={$row['ID_PRODUCTO']}'>Editar</a></td>";
      echo "</tr>";
   }
?>
   </table>
</body>
</html>

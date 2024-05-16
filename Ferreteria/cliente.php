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
   <title>Clientes</title>
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
   <h1>Clientes - Ferrimundo</h1>
   <div class="menu">
      <a href="welcome.php">Inicio</a>
      <a href="productos.php">Inventario</a>
      <a href="facturar.php">Facturar</a>    
   </div>
   
   <button onclick="location.href='agregar_cliente.php'">Agregar Cliente</button>
   
   <table>
      <tr>
         <th>ID</th>
         <th>NIT</th>
         <th>Razón Social</th>
         <th>Tipo cliente</th>
         <th>Nombre</th>
         <th>Apellido</th>
         <th>Dirección</th>
         <th>Ciudad</th>
         <th>Correo</th>
         <th>Teléfono</th>
         <th>Código Postal</th>
         <th>Cupo Crédito</th>
         <th>Saldo</th>
         <th>Compras Mes</th>
         <th>Pagos Mes</th>
         <th>Empresa</th>
         <th>Estado</th>
         <th>Acciones</th>
      </tr>
      <?php
      // Realiza la consulta a la base de datos para obtener los clientes
      $query = "SELECT * FROM cliente";
      $result = mysqli_query($conn, $query);

      // Muestra los clientes en la tabla
      while ($row = mysqli_fetch_assoc($result)) {
         echo "<tr>";
         echo "<td>{$row['ID_CLIENTE']}</td>";
         echo "<td>{$row['NIT_CLIENTE']}</td>";
         echo "<td>{$row['RSOCIAL_CLIENTE']}</td>";
         echo "<td>{$row['ID_TIPO_CLIENTE']}</td>";
         echo "<td>{$row['NOMBRE_CLIENTE']}</td>";
         echo "<td>{$row['APELLIDO_CLIENTE']}</td>";
         echo "<td>{$row['DIR_CLIENTE']}</td>";
         echo "<td>{$row['ID_CIUDAD']}</td>";
         echo "<td>{$row['CORREO_CLIENTE']}</td>";
         echo "<td>{$row['TEL_CLIENTE']}</td>";
         echo "<td>{$row['CODPOSTAL_CLIENTE']}</td>";
         echo "<td>{$row['CUPOCREDITO_CLIENTE']}</td>";
         echo "<td>{$row['SALDO_CLIENTE']}</td>";
         echo "<td>{$row['COMPRASMES_CLIENTE']}</td>";
         echo "<td>{$row['PAGOSMES_CLIENTE']}</td>";
         echo "<td>{$row['ID_EMPRESA']}</td>";
         echo "<td>{$row['ESTADO_CLIENTE']}</td>"; 
         echo "<td><a href='editar_cliente.php?id={$row['ID_CLIENTE']}'>Editar</a></td>"; // Enlace de edición
         echo "</tr>";
      }
      ?>
   </table>
</body>
</html>

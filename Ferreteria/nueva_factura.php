<?php
include('db_connect.php');
session_start();

if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    die();
}

$error = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera los datos del formulario de factura
    $fechaFactura = mysqli_real_escape_string($conn, $_POST['fecha_factura']);
    $idTipoFac = isset($_POST['id_tipofac']) ? (int) $_POST['id_tipofac'] : 0;
    $idCliente = isset($_POST['id_cliente']) ? (int) $_POST['id_cliente'] : 0;
    $totalFactura = isset($_POST['total_factura']) ? floatval($_POST['total_factura']) : 0.0;
    $descuentoFactura = isset($_POST['descuento_factura']) ? floatval($_POST['descuento_factura']) : 0.0;
    $ivaFactura = isset($_POST['iva_factura']) ? floatval($_POST['iva_factura']) : 0.0;
    $subtotalFactura = isset($_POST['subtotal_factura']) ? floatval($_POST['subtotal_factura']) : 0.0;
    $saldoFactura = isset($_POST['saldo_factura']) ? floatval($_POST['saldo_factura']) : 0.0;
    $estadoFactura = isset($_POST['estado_factura']) ? strtoupper($_POST['estado_factura']) : '';

    // Validaciones
    if (empty($fechaFactura) || empty($idTipoFac) || empty($idCliente) || empty($estadoFactura)) {
        $error = "Error: Los campos son obligatorios";
    } elseif ($idTipoFac < 0 || $idCliente < 0) {
        $error = "Error: Los campos de valores numericos deben ser números positivos.";
    } elseif ($estadoFactura !== 'A' && $estadoFactura !== 'I') {
        $error = "Error: El estado de la factura debe ser 'A' (Activo) o 'I' (Inactivo).";
    }
    // Realiza la llamada al procedimiento almacenado para crear la factura
    $createFacturaQuery = "CALL CreateFactura('$fechaFactura', '$idTipoFac', '$idCliente', '$totalFactura', '$descuentoFactura', '$ivaFactura', '$subtotalFactura', '$saldoFactura', '$estadoFactura')";
    $resultFactura = mysqli_query($conn, $createFacturaQuery);

    if ($resultFactura) {
        $successMessage = "Factura creada exitosamente";
        header("location: facturar.php?success=1");
    } else {
        $error = "Error al agregar la factura: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Nueva Factura</title>
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

      form {
         max-width: 600px;
         margin: 0 auto;
         background-color: white;
         padding: 20px;
         border-radius: 10px;
         margin-top: 20px;
      }

      label {
         display: block;
         margin-bottom: 8px;
      }

      input {
         width: 100%;
         padding: 8px;
         margin-bottom: 12px;
         box-sizing: border-box;
      }

      button {
         background-color: #4CAF50;
         color: white;
         padding: 10px 15px;
         border: none;
         cursor: pointer;
      }

      button[type="button"] {
         background-color: #f44336;
      }

      p {
         margin-top: 10px;
         font-weight: bold;
      }

      input[readonly] {
         background-color: #f5f5f5;
         cursor: not-allowed;
      }

      input[type="submit"][disabled] {
         background-color: #d3d3d3;
         cursor: not-allowed;
      }
   </style>
    </head>
    <body>
        <h1>Nueva Factura - Ferrimundo</h1>        
        <div class="menu">
            <a href="facturar.php">Volver</a>                  
        </div>                
        <form action="" method="post">

            <label for="fecha_factura">Fecha Factura:</label>
            <input type="date" name="fecha_factura" required>

            <label for="id_tipofac">Tipo de Factura:</label>
            <input type="number" name="id_tipofac" required>

            <label for="id_cliente">Cliente:</label>
            <input type="number" name="id_cliente" required>

            <label for="total_factura">Total:</label>
            <input type="number" name="total_factura" readonly>

            <label for="descuento_factura">Descuento:</label>
            <input type="number" name="descuento_factura" required>

            <label for="iva_factura">IVA:</label>
            <input type="number" name="iva_factura" value="19" readonly>

            <label for="subtotal_factura">Subtotal:</label>
            <input type="number" name="subtotal_factura" readonly>

            <label for="saldo_factura">Saldo:</label>
            <input type="number" name="saldo_factura" required>

            <label for="estado_factura">Estado:</label>
            <input type="text" name="estado_factura" required>            

            <input type="submit" value="Crear Factura">

        </form>
        <script>
            function validarFormulario() {
                var fechaFactura = document.getElementById('fecha_factura').value.trim();
                var idTipoFac = document.getElementById('id_tipofac').value.trim();
                var idCliente = document.getElementById('id_cliente').value.trim();
                var totalFactura = document.getElementById('total_factura').value.trim();
                var descuentoFactura = document.getElementById('descuento_factura').value.trim();
                var ivaFactura = document.getElementById('iva_factura').value.trim();
                var subtotalFactura = document.getElementById('subtotal_factura').value.trim();
                var saldoFactura = document.getElementById('saldo_factura').value.trim();
                var estadoFactura = document.getElementById('estado_factura').value.trim();
                if (
                        fechaFactura === "" ||
                        idTipoFac === "" ||
                        idCliente === "" ||
                        totalFactura === "" ||
                        descuentoFactura === "" ||
                        ivaFactura === "" ||
                        subtotalFactura === "" ||
                        saldoFactura === "" ||
                        estadoFactura === "" ||
                        ) {
                    alert("Error: Todos los campos son obligatorios.");
                    return false;
                }
                // Validación números positivo)
                if (idTipoFac < 0 || isNaN(idTipoFac) || !Number.isInteger(Number(idTipoFac))) {
                    alert("Error: El id del tipo de facturacion debe ser un número entero positivo.");
                    return false;
                }

                if (idCliente < 0 || isNaN(idCliente) || !Number.isInteger(parseFloat(idCliente))) {
                    alert("Error: La id del cliente debe ser un número entero positivo.");
                    return false;
                }

                if (descuentoFactura < 0 || isNaN(descuentoFactura) || !Number.isInteger(Number(descuentoFactura))) {
                    alert("Error: El descuento debe ser un número positivo.");
                    return false;
                }

                if (saldoFactura < 0 || isNaN(saldoFactura) || !Number.isInteger(parseFloat(saldoFactura))) {
                    alert("Error: El saldo debe ser un número positivo.");
                    return false;
                }



                if (estadoFactura !== 'A' && estadoFactura !== 'I') {
                    alert("Error: El estado de la factura debe ser 'A' (Activo) o 'I' (Inactivo).");
                    return false;
                }

                return true; // Permite el envío del formulario si todas las validaciones son exitosas
            }

        </script>
        <div style="font-size: 11px; color: #cc0000; margin-top: 10px;"><?php echo $error; ?></div>
    </body>
</html>
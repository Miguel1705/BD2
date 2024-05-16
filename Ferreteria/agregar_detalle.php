<?php
include('db_connect.php');
session_start();

if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    die();
}
$error = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_factura'])) {
    $idFactura = isset($_POST['id_factura']) ? intval($_POST['id_factura']) : 0;

    // Realiza la actualización de los campos TOTAL_FACTURA y SUBTOTAL_FACTURA
    $subtotalQuery = "SELECT SUM(CANT_VENDIDA * PRECIO_VENTA) AS SUBTOTAL FROM detallefactura WHERE ID_FACTURA = $idFactura";
    $resultSubtotal = mysqli_query($conn, $subtotalQuery);

    if ($resultSubtotal && $rowSubtotal = mysqli_fetch_assoc($resultSubtotal)) {
        $subtotalFactura = $rowSubtotal['SUBTOTAL'];
    } else {
        $subtotalFactura = 0;
    }

    // Calcular el total de la factura sumando el IVA
    $totalConIva = $subtotalFactura + ($subtotalFactura * 0.19);  // Asumiendo un IVA del 19%
    // Actualizar la factura con los nuevos valores
    $updateFacturaQuery = "UPDATE factura SET SUBTOTAL_FACTURA = $subtotalFactura, TOTAL_FACTURA = $totalConIva WHERE ID_FACTURA = $idFactura";
    $resultUpdate = mysqli_query($conn, $updateFacturaQuery);

    if ($resultUpdate) {
        $successMessage = "Factura actualizada exitosamente";
    } else {
        $error = "Error al actualizar la factura: " . mysqli_error($conn);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crear_detalle'])) {
    $idFactura = isset($_POST['id_factura']) ? intval($_POST['id_factura']) : 0;
    $idProducto = isset($_POST['id_producto_1']) ? intval($_POST['id_producto_1']) : 0;
    $cantidadVendida = isset($_POST['cant_vendida_1']) ? intval($_POST['cant_vendida_1']) : 0;
    $precioVenta = isset($_POST['precio_producto_1']) ? floatval($_POST['precio_producto_1']) : 0.0;
    $valorIva = isset($_POST['iva_1']) ? floatval($_POST['iva_1']) : 0.0;
    $descuentoDetalle = isset($_POST['desto_detfac_1']) ? floatval($_POST['desto_detfac_1']) : 0.0;

    //Validaciones
    if (empty($idProducto) || empty($cantidadVendida)) {
        $error = "Error: Los campos son obligatorios";
    } elseif ($idProducto < 0 || $cantidadVendida < 0) {
        $error = "Error: Los campos de valores numericos deben ser números positivos.";
    }

    // Realiza la llamada al procedimiento almacenado para crear el detalle de factura
    $createDetalleQuery = "CALL CreateDetalleFactura($idFactura, $idProducto, $cantidadVendida, '$precioVenta', '$valorIva', '$descuentoDetalle')";
    $resultDetalle = mysqli_query($conn, $createDetalleQuery);

    if ($resultDetalle) {
        $successMessage = "Factura creada exitosamente";
        header("location: facturar.php?success=1");
        exit();
    } else {
        $error = "Error al agregar el detalle de la factura: " . mysqli_error($conn);
    }
}
// Verificar si se proporciona un ID de factura en la URL
if (!empty($_GET['id'])) {
    $facturaId = mysqli_real_escape_string($conn, $_GET['id']);

    // Obtener datos de la factura
    $queryFactura = "SELECT * FROM factura WHERE ID_FACTURA = $facturaId";
    $resultFactura = mysqli_query($conn, $queryFactura);

    if ($resultFactura && mysqli_num_rows($resultFactura) > 0) {
        $facturaData = mysqli_fetch_assoc($resultFactura);
    } else {
        // Manejar el caso en que no se encuentre la factura
        echo "Factura no encontrada.";
        exit();
    }
} else {
    // Manejar el caso en que no se proporciona un ID de factura
    echo "ID de factura no proporcionado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agregar Detalle</title>
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
    <body>
        <h1>Agregar Detalle - Ferrimundo</h1>
        <div class="menu">
            <a href="facturar.php">Volver</a>            
        </div>

        <!-- Formulario de Factura -->
        <h2>Factura</h2>
        <form action="" method="post" onsubmit="return actualizarFactura()">
            <label for="fecha_factura">Fecha Factura:</label>
            <input type="date" name="fecha_factura" value="<?= $facturaData['FECHA_FACTURA'] ?>" readonly>

            <label for="id_tipofac">Tipo de Factura:</label>
            <input type="number" name="id_tipofac" value="<?= $facturaData['ID_TIPOFAC'] ?>"readonly >

            <label for="id_cliente">Cliente:</label>
            <input type="number" name="id_cliente" value="<?= $facturaData['CLIENTE_FACTURA'] ?>" readonly>

            <label for="total_factura">Total:</label>
            <input type="number" name="total_factura" readonly>

            <label for="descuento_factura">Descuento:</label>
            <input type="number" name="descuento_factura" value="<?= $facturaData['DESCUENTO_FACTURA'] ?>" readonly>

            <label for="iva_factura">IVA:</label>
            <input type="number" name="iva_factura" value="19" readonly>

            <label for="subtotal_factura">Subtotal:</label>
            <input type="number" name="subtotal_factura" readonly>

            <label for="saldo_factura">Saldo:</label>
            <input type="number" name="saldo_factura" value="<?= $facturaData['SALDO_FACTURA'] ?>" readonly>

            <label for="estado_factura">Estado:</label>
            <input type="text" name="estado_factura" value="<?= $facturaData['ESTADO_FACTURA'] ?>" readonly>

            <input type="hidden" name="id_factura" value="<?= $facturaData['ID_FACTURA'] ?>">

            <input type="submit" name="actualizar_factura" value="Actualizar Factura">

        </form>
        <!-- Formulario de Detalle de Factura -->
        <form action="" method="post" onsubmit="return crearDetalle()">

            <div id="detalle-container">
                <h2>Detalle de la Factura</h2>
                <div class="detalle-row" id="detalle-row-1">
                    <label for="id_factura">ID de la factura:</label>
                    <input type="number" name="id_factura" required>
                    <label for="id_producto_1">Producto:</label>
                    <input type="number" name="id_producto_1" required onchange="consultarProducto(this)">
                    <label for="cant_vendida_1">Cantidad:</label>
                    <input type="number" name="cant_vendida_1" required oninput="calcularTotalFactura()">
                    <label for="precio_producto_1">Precio sin IVA:</label>
                    <input type="number" name="precio_producto_1" readonly oninput="calcularTotalFactura()">
                    <label for="iva_1">IVA:</label>
                    <input type="number" name="iva_1" readonly>
                    <label for="desto_detfac_1">Descuento:</label>
                    <input type="number" name="desto_detfac_1" value="0" readonly>

                </div>
            </div>

            <button type="button" onclick="agregarProducto()">Agregar Producto</button>            
            <br>

            <input type="submit" name="crear_detalle" value="Crear Detalle">
        </form>
<?php
if ($successMessage) {
    echo "<p style='color: green;'>$successMessage</p>";
}

if ($error) {
    echo "<p style='color: red;'>$error</p>";
}
?>
        <script>
            let contadorProductos = 1;

            function agregarProducto() {
                contadorProductos++;

                const detalleContainer = document.getElementById('detalle-container');

                const nuevaFila = document.createElement('div');
                nuevaFila.className = 'detalle-row';
                nuevaFila.id = `detalle-row-${contadorProductos}`;

                nuevaFila.innerHTML = `
                <label for="id_producto_${contadorProductos}">Producto ${contadorProductos}:</label>
                <input type="number" name="id_producto_${contadorProductos}" required onchange="consultarProducto(this)">
                <label for="cant_vendida${contadorProductos}">Cantidad:</label>
                <input type="number" name="cant_vendida_${contadorProductos}" required oninput="calcularTotalFactura()">
                <label for="precio_producto_${contadorProductos}">Precio sin IVA:</label>
                <input type="number" name="precio_producto_${contadorProductos}" readonly oninput="calcularTotalFactura()">
                <label for="iva_${contadorProductos}">IVA:</label>
                <input type="number" name="iva_${contadorProductos}" readonly>
                <label for="descto_detfac_${contadorProductos}">Descuento:</label>
                <input type="number" name="descto_detfac_${contadorProductos}" value="0" readonly>
                <button type="button" onclick="eliminarProducto(${contadorProductos})">Eliminar Producto</button>
            `;

                detalleContainer.appendChild(nuevaFila);
                calcularTotalFactura();
            }

            function eliminarProducto(id) {
                const detalleRow = document.getElementById(`detalle-row-${id}`);
                detalleRow.remove();
                calcularTotalFactura();
            }

            function calcularTotalFactura() {
                let totalFactura = 0;

                // Suma los totales de cada producto
                for (let i = 1; i <= contadorProductos; i++) {
                    const cantidad = parseFloat(document.querySelector(`[name="cant_vendida_${i}"]`).value) || 0;
                    const precio = parseFloat(document.querySelector(`[name="precio_producto_${i}"]`).value) || 0;
                    totalFactura += cantidad * precio;
                }

                // Recupera el valor de descuento
                const descuentoFactura = parseFloat(document.querySelector('[name="descuento_factura"]').value) || 0;

                // Fija el IVA en un 19%
                const ivaFactura = 19;

                // Calcula el subtotal
                const subtotal = totalFactura;

                // Actualiza el campo subtotal_factura
                document.querySelector('[name="subtotal_factura"]').value = subtotal.toFixed(2);

                // Calcula el total aplicando el descuento y sumando el IVA
                const totalConDescuento = subtotal - (subtotal * (descuentoFactura / 100));
                const totalConIva = totalConDescuento + (totalConDescuento * (ivaFactura / 100));

                // Actualiza el campo total_factura
                document.querySelector('[name="total_factura"]').value = totalConIva.toFixed(2);
            }


            function consultarProducto(input) {
                // Obtén el valor de ID_PRODUCTO ingresado por el usuario
                const idProducto = input.value;

                // Realiza una consulta AJAX para obtener el precio e IVA del producto
                // Asegúrate de ajustar la URL a tu entorno y estructura de archivos
                fetch('consultar_producto.php?id_producto=' + idProducto)
                        .then(response => response.json())
                        .then(data => {
                            // Actualiza los campos de precio e IVA
                            const idRow = input.name.split('_')[2];
                            document.querySelector(`[name="precio_producto_${idRow}"]`).value = data.precio_venta;
                            document.querySelector(`[name="iva_${idRow}"]`).value = data.valor_iva;
                        })
                        .catch(error => {
                            console.error('Error al consultar el producto:', error);
                            // Puedes manejar el error de alguna manera aquí, por ejemplo, mostrando un mensaje al usuario
                        });
            }

            function actualizarFactura() {
                var totalFactura = parseFloat(document.getElementById('total_factura').value) || 0;
                var subtotalFactura = parseFloat(document.getElementById('subtotal_factura').value) || 0;

                // Actualiza los campos de la factura antes de enviar el formulario
                document.getElementById('total_factura').value = totalFactura.toFixed(2);
                document.getElementById('subtotal_factura').value = subtotalFactura.toFixed(2);

                alert('Factura actualizada exitosamente');
                return true; // Devuelve true para permitir el envío del formulario
            }


            function crearDetalle() {
                // Realizar validaciones adicionales antes de enviar el formulario
                var idFactura = document.querySelector('[name="id_factura"]').value.trim();
                var idProducto = document.querySelector('[name="id_producto_1"]').value.trim();
                var cantidadVendida = document.querySelector('[name="cant_vendida_1"]').value.trim();
                var precioVenta = document.querySelector('[name="precio_producto_1"]').value.trim();
                var valorIva = document.querySelector('[name="iva_1"]').value.trim();
                var descuentoDetalle = document.querySelector('[name="desto_detfac_1"]').value.trim();

                // Validar que todos los campos estén completos
                if (idFactura === "" || idProducto === "" || cantidadVendida === "" || precioVenta === "" || valorIva === "" || descuentoDetalle === "") {
                    alert("Error: Todos los campos son obligatorios.");
                    return false;
                }

                // Validar que idProducto y cantidadVendida sean números positivos
                if (idProducto < 0 || isNaN(idProducto) || !Number.isInteger(parseFloat(idProducto))) {
                    alert("Error: El id del producto debe ser un número entero positivo.");
                    return false;
                }

                if (cantidadVendida < 0 || isNaN(cantidadVendida) || !Number.isInteger(parseFloat(cantidadVendida))) {
                    alert("Error: La cantidad debe ser un número entero positivo.");
                    return false;
                }
                // Mostrar mensaje de éxito
                alert('Detalle de factura creado exitosamente');

                // Devolver true para permitir el envío del formulario
                return true;
            }
        </script>
    </body>

</html>
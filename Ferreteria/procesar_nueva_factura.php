<?php
include('db_connect.php');
session_start();

if (!isset($_SESSION['login_user'])) {
   header("location: login.php");
   die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Recupera los datos del formulario de factura
   $fechaFactura = mysqli_real_escape_string($conn, $_POST['fecha_factura']);
   $idTipoFac = isset($_POST['id_tipo_fac']) ? (int)$_POST['id_tipo_fac'] : 0;
   $idCliente = isset($_POST['id_cliente']) ? (int)$_POST['id_cliente'] : 0;
   $totalFactura = isset($_POST['total_factura']) ? floatval($_POST['total_factura']) : 0.0;
   $descuentoFactura = isset($_POST['descuento_factura']) ? floatval($_POST['descuento_factura']) : 0.0;
   $ivaFactura = isset($_POST['iva_factura']) ? floatval($_POST['iva_factura']) : 0.0;
   $subtotalFactura = isset($_POST['subtotal_factura']) ? floatval($_POST['subtotal_factura']) : 0.0;
   $saldoFactura = isset($_POST['saldo_factura']) ? floatval($_POST['saldo_factura']) : 0.0;
   $estadoFactura = isset($_POST['estado_factura']) ? strtoupper($_POST['estado_factura']) : '';

   // Validaciones y procesamiento de datos para la factura...

   // Inserta la factura en la base de datos
   $insertFacturaQuery = "CALL CreateFactura('$fechaFactura', $idTipoFac, $idCliente, $totalFactura, $descuentoFactura, $ivaFactura, $subtotalFactura, $saldoFactura, '$estadoFactura')";
   $resultFactura = mysqli_query($conn, $insertFacturaQuery);

   if ($resultFactura) {
      // Obtiene el ID de la factura recién creada
      //$idFactura = mysqli_insert_id($conn);

      // Procesa los detalles de la factura
      for ($i = 1; isset($_POST["id_producto_$i"]); $i++) {
         $idProducto = (int)$_POST["id_producto_$i"];
         $cantidad = (int)$_POST["cantidad_$i"];
         $precioVenta = floatval($_POST["precio_venta_$i"]);
         $valorIva = floatval($_POST["valor_iva_$i"]);
         $descuentoDetalle = floatval($_POST["descuento_detalle_$i"]);

         // Inserta el detalle de la factura en la base de datos
         $insertDetalleQuery = "CALL CreateDetalleFactura($idFactura, $idProducto, $cantidad, $precioVenta, $valorIva, $descuentoDetalle)";
         $resultDetalle = mysqli_query($conn, $insertDetalleQuery);

         if (!$resultDetalle) {
            // Manejo de errores si falla la inserción del detalle
            $error = "Error al agregar el detalle de la factura: " . mysqli_error($conn);
            break;
         }
      }

      // Verifica si hubo algún error en el proceso
      if (isset($error)) {
         // Manejo de errores si hay algún problema
         echo $error;
      } else {
         // Redirige o realiza otras acciones después de la creación exitosa
         header("location: facturar.php?success=true");
      }
   } else {
      // Manejo de errores si falla la inserción de la factura
      $error = "Error al agregar la factura: " . mysqli_error($conn);
      echo $error;
   }
}

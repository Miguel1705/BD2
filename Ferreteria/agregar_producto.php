<?php
include('db_connect.php');
session_start();

if (!isset($_SESSION['login_user'])) {
   header("location: login.php");
   die();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Recupera los datos del formulario
   $idCategoria = isset($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : 0;
   $codProducto = mysqli_real_escape_string($conn, $_POST['cod_producto']);
   $desProducto = mysqli_real_escape_string($conn, $_POST['des_producto']);
   $exisProducto = isset($_POST['exis_producto']) ? (int)$_POST['exis_producto'] : 0;
   $bodegaProducto = isset($_POST['bodega_producto'])? (int)$_POST['bodega_producto'] : 0;
   $precioVentaAct = isset($_POST['precio_venta_act']) ? floatval($_POST['precio_venta_act']) : 0.0;
   $precioVentaAnt = isset($_POST['precio_venta_ant']) ? floatval($_POST['precio_venta_ant']) : 0.0;
   $costoVenta = isset($_POST['costo_venta']) ? floatval($_POST['costo_venta']) : 0.0;
   $margenUtilidad = isset($_POST['margen_utilidad']) ? floatval($_POST['margen_utilidad']) : 0.0;
   $stockMax = isset($_POST['stock_maximo']) ? (int)$_POST['stock_maximo'] : 0;
   $stockMin = isset($_POST['stock_minimo']) ? (int)$_POST['stock_minimo'] : 0;
   $dsctoMax = isset($_POST['dscto_maximo']) ? (int)$_POST['dscto_maximo'] : 0;
   $dsctoMin = isset($_POST['dscto_minimo']) ? (int)$_POST['dscto_minimo'] : 0;
   $valorIva = isset($_POST['valor_iva']) ? floatval($_POST['valor_iva']) : 0.0;
   $estadoProducto = isset($_POST['estado_producto']) ? strtoupper($_POST['estado_producto']) : '';

   // Validaciones 
   if (empty($codProducto) || empty($desProducto) || !ctype_alnum($codProducto)) {
      $error = "Error: Código del producto y descripción son obligatorios y deben contener solo caracteres alfanuméricos.";
   } elseif (strlen($codProducto) > 12) {
      $error = "Error: El código del producto no puede tener más de 12 caracteres.";
   } elseif (strlen($desProducto) > 60) {
      $error = "Error: La descripción del producto no puede tener más de 60 caracteres.";
   } elseif (!ctype_digit($exisProducto) || $exisProducto < 0) {
      $error = "Error: La existencia del producto debe ser un número entero positivo.";
   } elseif ($precioVentaAct < 0 || $precioVentaAnt < 0 || $costoVenta < 0 || $margenUtilidad < 0 || $valorIva < 0) {
      $error = "Error: Los campos de precios y valores deben ser números positivos.";
   } elseif ($estadoProducto !== 'A' && $estadoProducto !== 'I') {
      $error = "Error: El estado del producto debe ser 'A' (Activo) o 'I' (Inactivo).";
   } elseif (empty ($idCategoria) || empty ($exisProducto) || empty ($bodegaProducto) ||empty ($precioVentaAct) || empty ($precioVentaAnt) || empty ($costoVenta) || empty ($margenUtilidad) || empty ($dsctoMax) || empty ($dsctoMin) || empty ($valorIva) || empty ($estadoProducto)){
       $error = "Error: Los campos son obligatorios";
   }

   // Realiza la inserción en la base de datos
   if ($exisProducto < 0 || $bodegaProducto < 0 || $precioVentaAct < 0 || $precioVentaAnt < 0 || $costoVenta < 0 || $margenUtilidad < 0 || $stockMax < 0 || $stockMin < 0 || $dsctoMax < 0 || $dsctoMin < 0 || $valorIva < 0) {
        $error = "Error: Los campos numéricos deben ser positivos.";
    } else {
        $insertQuery = "INSERT INTO producto (COD_PRODUCTO, DES_PRODUCTO, EXIS_PRODUCTO, BODEGA_PRODUCTO, PRECIO_VENTA_ACT, PRECIO_VENTA_ANT, COSTO_VENTA, MARGEN_UTILIDAD, STOCK_MINIMO, STOCK_MAXIMO, DSCTO_MINIMO, DSCTO_MAXIMO, VALOR_IVA, ESTADO_PRODUCTO, ID_CATEGORIA) VALUES ('$codProducto', '$desProducto', $exisProducto, $bodegaProducto, $precioVentaAct, $precioVentaAnt, $costoVenta, $margenUtilidad, $stockMin, $stockMax, $dsctoMin, $dsctoMax, $valorIva, '$estadoProducto', $idCategoria)";
        $result = mysqli_query($conn, $insertQuery);

        if ($result) {
            header("location: productos.php");
        } else {
            $error = "Error al agregar el producto: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Agregar Producto - Ferrimundo</title>
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
   <h1>Agregar Producto - Ferrimundo</h1>
   <h4>Utilice el numero que corresponda a la categoria</h4>
   <h5>1=Pintura  2=Herramienta  3=Plomeria  4=Maderas  5=Electricidad</h5>
   <div class="menu">
      <a href="productos.php">Volver</a>
   </div>
   <!-- Formulario para agregar productos -->
   <div class="formulario">
      <form action="" method="post">
         <label for="cod_producto">Código:</label>
         <input type="text" name="cod_producto" required>

         <label for="des_producto">Descripción:</label>
         <input type="text" name="des_producto" required>
         
         <label for="id_categoria">Categoría:</label>
         <input type="number" name="id_categoria" required>

         <label for="exis_producto">Existencias:</label>
         <input type="number" name="exis_producto" required>

         <label for="bodega_producto"> Bodega: </label>
         <input type="number" name="bodega_producto" required>
         
         <label for="precio_venta_act"> Precio actual: </label>
         <input type="number" name="precio_venta_act" required>
         
         <label for="precio_venta_ant"> Precio anterior: </label>
         <input type="number" name="precio_venta_ant" required>
         
         <label for="costo_venta"> Costo: </label>
         <input type="number" name="costo_venta" required>
         
         <label for="margen_utilidad"> Margen de utilidad: </label>
         <input type="number" name="margen_utilidad" required>
         
         <label for="stock_maximo"> Stock max: </label>
         <input type="number" name="stock_maximo" required>
         
         <label for="stock_minimo"> Stock min: </label>
         <input type="number" name="stock_minimo" required>
         
         <label for="dscto_maximo"> Descuento max: </label>
         <input type="number" name="dscto_maximo" required>
         
         <label for="dscto_minimo"> Descuento min: </label>
         <input type="number" name="dscto_minimo" required>
         
         <label for="valor_iva"> IVA: </label>
         <input type="number" name="valor_iva" required>
         
         <label for="estado_producto"> Estado: </label>
         <input type="text" name="estado_producto" required>

         <input type="submit" value="Agregar Producto">
      </form>
   </div>
   
   <script>
      function restaurarTexto(input) {
      // Si la caja de texto está vacía, restaura el texto predeterminado y hace el texto invisible nuevamente
      if (!input.value.trim()) {
        input.value = "A para activo, I para inactivo";
        input.style.color = "transparent";
      }
    }
       
    function validarFormulario() {
       var codProducto = document.getElementById('cod_producto').value;
       var desProducto = document.getElementById('des_producto').value;
       var exisProducto = document.getElementById('exis_producto').value;
       var bodegaProducto = document.getElementById('bodega_producto').value;
       var precioVentaAct = document.getElementById('precio_venta_act').value;
       var precioVentaAnt = document.getElementById('precio_venta_ant').value;
       var costoVenta = document.getElementById('costo_venta').value;
       var margenUtilidad = document.getElementById('margen_utilidad').value;
       var stockMin = document.getElementById('stock_minimo').value;
       var stockMax = document.getElementById('stock_maximo').value;
       var dsctoMin = document.getElementById('dscto_minimo').value;
       var dsctoMax = document.getElementById('dscto_maximo').value;
       var valorIva = document.getElementById('valor_iva').value;
       var estadoProducto = document.getElementById('estado_producto').value;
       var idCategoria = document.getElementById('id_categoria').value;

       // Validación del código del producto (solo caracteres alfanuméricos)
       if (!codProducto.match(/^[a-zA-Z0-9]+$/)) {
          alert("Error: El código del producto debe contener solo caracteres alfanuméricos.");
          return false;
       }
       
       // Validación de los campos(no pueden estar vacios)
       if (
            codProducto.trim() === "" ||
            desProducto.trim() === "" ||
            exisProducto.trim() === "" ||
            bodegaProducto.trim() === "" ||
            precioVentaAct.trim() === "" ||
            precioVentaAnt.trim() === "" ||
            costoVenta.trim() === "" ||
            margenUtilidad.trim() === "" ||
            stockMin.trim() === "" ||
            stockMax.trim() === "" ||
            dsctoMin.trim() === "" ||
            dsctoMax.trim() === "" ||
            valorIva.trim() === "" ||
            estadoProducto.trim() === "" ||
            idCategoria.trim() === ""
        ) {
            alert("Error: Todos los campos son obligatorios.");
            return false;

       // Validación de número entero positivo
       if (exisProducto < 0 || isNaN(exisProducto) || !Number.isInteger(parseFloat(exisProducto))) {
          alert("Error: La existencia del producto debe ser un número entero positivo.");
          return false;
       }
       
       if(bodegaProducto < 0 ||isNaN(bodegaProducto) || !Number.isInteger(parseFloat(bodegaProducto))){
           alert("Error: La bodega del producto debe ser un número entero positivo.");
          return false;
       }
       
       if(precioVentaAct < 0 ||isNaN(precioVentaAct) || !Number.isInteger(parseFloat(precioVentaAct))){
           alert("Error: El precio de venta debe ser un número entero positivo.");
          return false;
       }
       
       if(precioVentaAnt < 0 ||isNaN(precioVentaAnt) || !Number.isInteger(parseFloat(precioVentaAnt))){
           alert("Error: El precio de venta debe ser un número entero positivo.");
          return false;
       }
       
       if(costoVenta < 0 ||isNaN(costoVenta) || !Number.isInteger(parseFloat(costoVenta))){
           alert("Error: El costo debe ser un número entero positivo.");
          return false;
       }
       
       if(margenUtilidad < 0 ||isNaN(margenUtilidad) || !Number.isInteger(parseFloat(margenUtilidad))){
           alert("Error: El margen de utilidad debe ser un número entero positivo.");
          return false;
       }
       
       if(stockMin < 0 ||isNaN(stockMin) || !Number.isInteger(parseFloat(stockMin))){
           alert("Error: El stock debe ser un número entero positivo.");
          return false;
       }
       
       if(stockMax < 0 ||isNaN(stockMax) || !Number.isInteger(parseFloat(stockMax))){
           alert("Error: El stock debe ser un número entero positivo.");
          return false;
       }
       
       if(dsctoMin < 0 ||isNaN(dsctoMin) || !Number.isInteger(parseFloat(dsctoMin))){
           alert("Error: El descuento debe ser un número entero positivo.");
          return false;
       }
       
       if(dsctoMax < 0 ||isNaN(dsctoMax) || !Number.isInteger(parseFloat(dsctoMax))){
           alert("Error: El descuento debe ser un número entero positivo.");
          return false;
       }
       
       if(valorIva < 0 ||isNaN(valorIva) || !Number.isInteger(parseFloat(valorIva))){
           alert("Error: El IVA debe ser un número entero positivo.");
          return false;
       }
       
       if(idCategoria < 0 ||isNaN(idCategoria) || !Number.isInteger(parseFloat(idCategoria))){
           alert("Error: La categoria debe ser un número entero positivo.");
          return false;
       }

       // Validación del estado del producto (solo 'A' o 'I')
       if (estadoProducto !== 'A' && estadoProducto !== 'I') {
          alert("Error: El estado del producto debe ser 'A' (Activo) o 'I' (Inactivo).");
          return false;
       }

       return true; // Permite el envío del formulario si todas las validaciones son exitosas
    }
       </script>

   <div style="font-size: 11px; color: #cc0000; margin-top: 10px;"><?php echo $error; ?></div>
</body>
</html>

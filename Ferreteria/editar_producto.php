<?php
include('db_connect.php');
session_start();

if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    die();
}

$error = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar_cambios'])) {
    // Recupera los datos del formulario
    $idProducto = isset($_POST['id_producto']) ? (int) $_POST['id_producto'] : 0;
    $codProducto = mysqli_real_escape_string($conn, $_POST['cod_producto']);
    $desProducto = mysqli_real_escape_string($conn, $_POST['des_producto']);
    $exisProducto = isset($_POST['exis_producto']) ? (int) $_POST['exis_producto'] : 0;
    $bodegaProducto = isset($_POST['bodega_producto']) ? (int) $_POST['bodega_producto'] : 0;
    $precioVentaAnt = isset($_POST['precio_venta_ant']) ? floatval($_POST['precio_venta_ant']) : 0.0;
    $precioVentaAct = isset($_POST['precio_venta_act']) ? floatval($_POST['precio_venta_act']) : 0.0;
    $costoVenta = isset($_POST['costo_venta']) ? floatval($_POST['costo_venta']) : 0.0;
    $margenUtilidad = isset($_POST['margen_utilidad']) ? floatval($_POST['margen_utilidad']) : 0.0;
    $stockMax = isset($_POST['stock_maximo']) ? (int) $_POST['stock_maximo'] : 0;
    $stockMin = isset($_POST['stock_minimo']) ? (int) $_POST['stock_minimo'] : 0;
    $dsctoMax = isset($_POST['dscto_maximo']) ? (int) $_POST['dscto_maximo'] : 0;
    $dsctoMin = isset($_POST['dscto_minimo']) ? (int) $_POST['dscto_minimo'] : 0;
    $valorIva = isset($_POST['valor_iva']) ? floatval($_POST['valor_iva']) : 0.0;
    $estadoProducto = isset($_POST['estado_producto']) ? strtoupper($_POST['estado_producto']) : '';
    $idCategoria = isset($_POST['id_categoria']) ? (int) $_POST['id_categoria'] : 0;

    // Validaciones 
    if (!ctype_alnum($codProducto)) {
        $error = "Error: Código del producto debe contener solo caracteres alfanuméricos.";
    } elseif (strlen($codProducto) > 12) {
        $error = "Error: El código del producto no puede tener más de 12 caracteres.";
    } elseif (strlen($desProducto) > 60) {
        $error = "Error: La descripción del producto no puede tener más de 60 caracteres.";
    } elseif ($exisProducto < 0) {
        $error = "Error: La existencia del producto debe ser un número entero positivo.";
    } elseif ($precioVentaAct < 0 || $precioVentaAnt < 0 || $costoVenta < 0 || $margenUtilidad < 0 || $valorIva < 0) {
        $error = "Error: Los campos de precios y valores deben ser números positivos.";
    } elseif ($estadoProducto !== 'A' && $estadoProducto !== 'I') {
        $error = "Error: El estado del producto debe ser 'A' (Activo) o 'I' (Inactivo).";
    }

    // Realiza la llamada al procedimiento almacenado para actualizar el producto
    if (empty($error)) {
        $updateQuery = "CALL UpdateProduct($idProducto, '$codProducto', '$desProducto', $exisProducto, $bodegaProducto, $precioVentaAnt, $precioVentaAct, $costoVenta, $margenUtilidad, $stockMax, $stockMin, $dsctoMax, $dsctoMin, $valorIva, '$estadoProducto')";
        $result = mysqli_query($conn, $updateQuery);

        if ($result) {
            $successMessage = "Producto actualizado exitosamente";
            header("location: productos.php");
        } else {
            $error = "Error al actualizar el producto: " . mysqli_error($conn);
        }
    }
}

// Obtener la información del producto para rellenar el formulario
if (isset($_GET['id'])) {
    $idProducto = (int) $_GET['id'];
    $query = "SELECT * FROM producto WHERE ID_PRODUCTO = $idProducto";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $producto = mysqli_fetch_assoc($result);
    } else {
        // Maneja el caso en que no se encuentre el producto con el ID proporcionado
        echo "Producto no encontrado";
        exit();
    }
} else {
    // Maneja el caso en que no se proporcionó un ID válido
    echo "ID de producto no válido";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Producto</title>
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
        <h1>Editar Producto - Ferrimundo</h1>
        <h4>Si va a actualizar la categoria recuerde que son:</h4>
        <h5>1=Pintura  2=Herramienta  3=Plomeria  4=Maderas  5=Electricidad</h5>
        <div class="menu">
            <a href="productos.php">Volver</a>            
        </div>
        <form action="" method="post">
            <input type="hidden" name="id_producto" value="<?php echo $producto['ID_PRODUCTO']; ?>">
            <label for="cod_producto">Código:</label>
            <input type="text" name="cod_producto" value="<?php echo $producto['COD_PRODUCTO']; ?>" >

            <label for="des_producto">Descripción:</label>
            <input type="text" name="des_producto" value="<?php echo $producto['DES_PRODUCTO']; ?>" >

            <label for="id_categoria">Categoria:</label>
            <input type="number" name="id_categoria" value="<?php echo $producto['ID_CATEGORIA']; ?>" >

            <label for="exis_producto">Existencias  :</label>
            <input type="number" name="exis_producto" value="<?php echo $producto['EXIS_PRODUCTO']; ?>" >

            <label for="bodega_producto">Bodega:</label>
            <input type="number" name="bodega_producto" value="<?php echo $producto['BODEGA_PRODUCTO']; ?>" >

            <label for="precio_venta_act">Precio actual:</label>
            <input type="number" name="precio_venta_act" value="<?php echo $producto['PRECIO_VENTA_ACT']; ?>" >

            <label for="precio_venta_ant">Precio anterior:</label>
            <input type="number" name="precio_venta_ant" value="<?php echo $producto['PRECIO_VENTA_ANT']; ?>" >

            <label for="costo_venta">Costo:</label>
            <input type="number" name="costo_venta" value="<?php echo $producto['COSTO_VENTA']; ?>" >

            <label for="margen_utilidad">Margen de utilidad:</label>
            <input type="number" name="margen_utilidad" value="<?php echo $producto['MARGEN_UTILIDAD']; ?>" >

            <label for="stock_maximo">Stock max:</label>
            <input type="number" name="stock_maximo" value="<?php echo $producto['STOCK_MAXIMO']; ?>" >

            <label for="stock_minimo">Stock min:</label>
            <input type="number" name="stock_minimo" value="<?php echo $producto['STOCK_MINIMO']; ?>" >

            <label for="dscto_maximo">Descuento max:</label>
            <input type="number" name="dscto_maximo" value="<?php echo $producto['DSCTO_MAXIMO']; ?>" >

            <label for="dscto_minimo">Descuento min:</label>
            <input type="number" name="dscto_minimo" value="<?php echo $producto['DSCTO_MINIMO']; ?>" >

            <label for="valor_iva">IVA:</label>
            <input type="number" name="valor_iva" value="<?php echo $producto['VALOR_IVA']; ?>" >

            <label for="estado_producto">Estado:</label>
            <input type="text" name="estado_producto" value="<?php echo $producto['ESTADO_PRODUCTO']; ?>" >

            <input type="submit" name="guardar_cambios" value="Guardar Cambios">
        </form>
        <div style="font-size: 11px; color: #cc0000; margin-top: 10px;"><?php echo isset($error) ? $error : ''; ?></div>
    </body>
</html>
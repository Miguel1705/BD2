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
    $idCliente = isset($_POST['id_cliente']) ? (int) $_POST['id_cliente'] : 0;
    $nitCliente = mysqli_real_escape_string($conn, $_POST['nit_cliente']);
    $rsocialCliente = mysqli_real_escape_string($conn, $_POST['rsocial_cliente']);
    $idTipoCliente = isset($_POST['id_tipo_cliente']) ? (int) $_POST['id_tipo_cliente'] : 0;
    $nombreCliente = mysqli_real_escape_string($conn, $_POST['nombre_cliente']);
    $apellidoCliente = mysqli_real_escape_string($conn, $_POST['apellido_cliente']);
    $dirCliente = mysqli_real_escape_string($conn, $_POST['dir_cliente']);
    $idCiudad = isset($_POST['id_ciudad']) ? (int) $_POST['id_ciudad'] : 0;
    $correoCliente = mysqli_real_escape_string($conn, $_POST['correo_cliente']);
    $telCliente = mysqli_real_escape_string($conn, $_POST['tel_cliente']);
    $codPostalCliente = mysqli_real_escape_string($conn, $_POST['codpostal_cliente']);
    $cupoCreditoCliente = isset($_POST['cupocredito_cliente']) ? floatval($_POST['cupocredito_cliente']) : 0.0;
    $saldoCliente = isset($_POST['saldo_cliente']) ? floatval($_POST['saldo_cliente']) : 0.0;
    $comprasMesCliente = isset($_POST['comprasmes_cliente']) ? floatval($_POST['comprasmes_cliente']) : 0.0;
    $pagosMesCliente = isset($_POST['pagosmes_cliente']) ? floatval($_POST['pagosmes_cliente']) : 0.0;
    $idEmpresa = isset($_POST['id_empresa']) ? intval($_POST['id_empresa']) : 0;
    $estadoCliente = isset($_POST['estado_cliente']) ? strtoupper($_POST['estado_cliente']) : '';

    // Validaciones
    if (strlen($nitCliente) > 15) {
        $error = "Error: El NIT no puede tener más de 15 caracteres.";
    } elseif ($idTipoCliente < 0 || $idCiudad < 0 || $idEmpresa < 0) {
        $error = "Error: Los campos de id deben ser numeros positivos";
    } elseif (strlen($rsocialCliente) > 70) {
        $error = "Error: La Razón Social no puede tener más de 70 caracteres.";
    } elseif ($estadoCliente !== 'A' && $estadoCliente !== 'I') {
        $error = "Error: El estado del cliente debe ser 'A' (Activo) o 'I' (Inactivo).";
    } elseif (strlen($correoCliente) > 85) {
        $error = "Error: El correo no puede tener más de 85 caracteres.";
    } elseif (!filter_var($correoCliente, FILTER_VALIDATE_EMAIL)) {
        $error = "Error: El formato del correo electrónico es inválido.";
    } elseif (strlen($telCliente) > 30) {
        $error = "Error: El telefono no puede tener más de 30 caracteres.";
    } elseif (strlen($codPostalCliente) > 15) {
        $error = "Error: El codigo postal no puede tener más de 15 caracteres.";
    } elseif ($cupoCreditoCliente < 0 || $saldoCliente < 0 || $comprasMesCliente < 0 || $pagosMesCliente < 0) {
        $error = "Error: Los campos de valores deben ser números positivos.";
    }

    // Realiza la llamada al procedimiento almacenado para editar el cliente
    if (empty($error)) {
        $editQuery = "CALL UpdateCliente($idCliente, '$nitCliente', '$rsocialCliente', $idTipoCliente, '$nombreCliente', '$apellidoCliente', '$dirCliente', $idCiudad, '$correoCliente', '$telCliente', '$codPostalCliente', $cupoCreditoCliente, $saldoCliente, $comprasMesCliente, $pagosMesCliente, $idEmpresa, '$estadoCliente')";
        $result = mysqli_query($conn, $editQuery);

        if ($result) {
            $successMessage = "Cliente actualizado exitosamente";
            header("location: cliente.php");
        } else {
            $error = "Error al editar el cliente: " . mysqli_error($conn);
        }
    }
}

// Obtener la información del cliente para rellenar el formulario
if (isset($_GET['id'])) {
    $idCliente = (int) $_GET['id'];
    $query = "SELECT * FROM cliente WHERE ID_CLIENTE = $idCliente";
    $result = mysqli_query($conn, $query);
    $cliente = mysqli_fetch_assoc($result);
} else {
    // Redirigir a la página de clientes si no se proporciona un ID válido
    header("location: cliente.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Cliente</title>
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
        <h1>Editar Cliente - Ferrimundo</h1>
        <div class="menu">
            <a href="cliente.php">Volver</a>
        </div>
        <div class="formulario">
            <form action="" method="post">
                <input type="hidden" name="id_cliente" value="<?php echo $cliente['ID_CLIENTE']; ?>">

                <label for="nit_cliente">NIT:</label>
                <input type="text" name="nit_cliente" value="<?php echo $cliente['NIT_CLIENTE']; ?>">

                <label for="rsocial_cliente">Razon social:</label>
                <input type="text" name="rsocial_cliente" value="<?php echo $cliente['RSOCIAL_CLIENTE']; ?>">

                <label for="id_tipo_cliente">Tipo de cliente:</label>
                <input type="number" name="id_tipo_cliente" value="<?php echo $cliente['ID_TIPO_CLIENTE']; ?>">

                <label for="nombre_cliente">Nombre:</label>
                <input type="text" name="nombre_cliente" value="<?php echo $cliente['NOMBRE_CLIENTE']; ?>">

                <label for="apellido_cliente"> Apellido: </label>
                <input type="text" name="apellido_cliente" value="<?php echo $cliente['APELLIDO_CLIENTE']; ?>">

                <label for="dir_cliente"> Direccion: </label>
                <input type="text" name="dir_cliente" value="<?php echo $cliente['DIR_CLIENTE']; ?>">

                <label for="id_ciudad"> Ciudad: </label>
                <input type="text" name="id_ciudad" value="<?php echo $cliente['ID_CIUDAD']; ?>">

                <label for="correo_cliente"> Correo: </label>
                <input type="text" name="correo_cliente" value="<?php echo $cliente['CORREO_CLIENTE']; ?>">

                <label for="tel_cliente"> Telefono: </label>
                <input type="text" name="tel_cliente" value="<?php echo $cliente['TEL_CLIENTE']; ?>">

                <label for="codpostal_cliente"> Codigo postal: </label>
                <input type="text" name="codpostal_cliente" value="<?php echo $cliente['CODPOSTAL_CLIENTE']; ?>">

                <label for="cupocredito_cliente"> Cupo credito: </label>
                <input type="number" name="cupocredito_cliente" value="<?php echo $cliente['CUPOCREDITO_CLIENTE']; ?>">

                <label for="saldo_cliente"> Saldo: </label>
                <input type="number" name="saldo_cliente" value="<?php echo $cliente['SALDO_CLIENTE']; ?>">

                <label for="comprasmes_cliente"> Compras mes: </label>
                <input type="number" name="comprasmes_cliente" value="<?php echo $cliente['COMPRASMES_CLIENTE']; ?>">

                <label for="pagosmes_cliente"> Pagos mes: </label>
                <input type="number" name="pagosmes_cliente" value="<?php echo $cliente['PAGOSMES_CLIENTE']; ?>">                  

                <label for="estado_cliente"> Estado </label>
                <input type="text" name="estado_cliente" value="<?php echo $cliente['ESTADO_CLIENTE']; ?>">

                <label for="id_empresa"> Empresa: </label>
                <input type="number" name="id_empresa" value="<?php echo $cliente['ID_EMPRESA']; ?>">

                <input type="submit" value="Guardar Cambios">
            </form>
        </div>

        <div style="font-size: 11px; color: #cc0000; margin-top: 10px;"><?php echo $error; ?></div>
    </body>
</html>
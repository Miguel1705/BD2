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
    // Recupera los datos del formulario
    $nitCliente = mysqli_real_escape_string($conn, $_POST['nit_cliente']);
    $rsocialCliente = mysqli_real_escape_string($conn, $_POST['rsocial_cliente']);
    $idTipoCliente = isset($_POST['id_tipo_cliente']) ? (int)$_POST['id_tipo_cliente'] : 0;
    $nombreCliente = mysqli_real_escape_string($conn, $_POST['nombre_cliente']);
    $apellidoCliente = mysqli_real_escape_string($conn, $_POST['apellido_cliente']);
    $dirCliente = mysqli_real_escape_string($conn, $_POST['dir_cliente']);
    $idCiudad = isset($_POST['id_ciudad']) ? (int)$_POST['id_ciudad'] : 0;
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
    if (empty($nitCliente) || empty($rsocialCliente) || empty($idTipoCliente) || empty($nombreCliente) || empty($apellidoCliente) || empty($dirCliente) || empty($idCiudad) || empty($correoCliente) || empty($telCliente) || empty($codPostalCliente) || empty($cupoCreditoCliente) || empty($saldoCliente) || empty($comprasMesCliente) || empty($pagosMesCliente) || empty($idEmpresa) || empty($estadoCliente)) {
      $error = "Error: Todos los campos son obligatorios";
    }elseif (strlen($nitCliente) > 15 ) {
        $error = "Error: El NIT no puede tener más de 15 caracteres.";
    } elseif ($idTipoCliente <0 || $idCiudad < 0 || $idEmpresa < 0) {
        $error = "Error: Los campos de id deben ser numeros positivos";
    } elseif (strlen($rsocialCliente) > 70) {
        $error = "Error: La Razón Social no puede tener más de 70 caracteres.";
    }elseif ($estadoCliente !== 'A' && $estadoCliente !== 'I') {
      $error = "Error: El estado del cliente debe ser 'A' (Activo) o 'I' (Inactivo).";
    } elseif (strlen($correoCliente) > 85) {
        $error = "Error: El correo no puede tener más de 85 caracteres.";
    }elseif (!filter_var($correoCliente, FILTER_VALIDATE_EMAIL)) {
        $error = "Error: El formato del correo electrónico es inválido.";
    }elseif (strlen($telCliente) > 30) {
        $error = "Error: El telefono no puede tener más de 30 caracteres.";
    }elseif (strlen($codPostalCliente) > 15) {
        $error = "Error: El codigo postal no puede tener más de 15 caracteres.";
    } elseif ($cupoCreditoCliente < 0 || $saldoCliente < 0 || $comprasMesCliente < 0 || $pagosMesCliente < 0) {
        $error = "Error: Los campos de valores deben ser números positivos.";
    } 
    
    // Realiza la llamada al procedimiento almacenado para insertar el cliente
    if (empty($error)) {
        $insertQuery = "CALL InsertCliente('$nitCliente', '$rsocialCliente', $idTipoCliente, '$nombreCliente', '$apellidoCliente', '$dirCliente', $idCiudad, '$correoCliente', '$telCliente', '$codPostalCliente', $cupoCreditoCliente, $saldoCliente, $comprasMesCliente, $pagosMesCliente, $idEmpresa, '$estadoCliente')";
        $result = mysqli_query($conn, $insertQuery);

        if ($result) { 
            $successMessage = "Cliente creado exitosamente";
            header("location: cliente.php");
        } else {
            $error = "Error al agregar el cliente: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Agregar Cliente</title>
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
   <h1>Agregar Cliente - Ferrimundo</h1>   
   <div class="menu">
      <a href="cliente.php">Volver</a>
   </div>
   <div class="formulario">
      <form action="" method="post">
         <label for="nit_cliente">NIT:</label>
         <input type="text" name="nit_cliente" required>

         <label for="rsocial_cliente">Razon social:</label>
         <input type="text" name="rsocial_cliente" required>
         
         <label for="id_tipo_cliente">Tipo de cliente:</label>
         <input type="number" name="id_tipo_cliente" required>

         <label for="nombre_cliente">Nombre:</label>
         <input type="text" name="nombre_cliente" required>

         <label for="apellido_cliente"> Apellido: </label>
         <input type="text" name="apellido_cliente" required>
         
         <label for="dir_cliente"> Direccion: </label>
         <input type="text" name="dir_cliente" required>
         
         <label for="id_ciudad"> Ciudad: </label>
         <input type="text" name="id_ciudad" required>
         
         <label for="correo_cliente"> Correo: </label>
         <input type="text" name="correo_cliente" required>
         
         <label for="tel_cliente"> Telefono: </label>
         <input type="text" name="tel_cliente" required>
         
         <label for="codpostal_cliente"> Codigo postal: </label>
         <input type="text" name="codpostal_cliente" required>
         
         <label for="cupocredito_cliente"> Cupo credito: </label>
         <input type="number" name="cupocredito_cliente" required>
         
         <label for="saldo_cliente"> Saldo: </label>
         <input type="number" name="saldo_cliente" required>
         
         <label for="comprasmes_cliente"> Compras mes: </label>
         <input type="number" name="comprasmes_cliente" required>
         
         <label for="pagosmes_cliente"> Pagos mes: </label>
         <input type="number" name="pagosmes_cliente" required>                  
                  
         <label for="estado_cliente"> Estado </label>
         <input type="text" name="estado_cliente" required>
         
         <label for="id_empresa"> Empresa: </label>
         <input type="number" name="id_empresa" required>

         <input type="submit" value="Agregar Cliente">
      </form>
   </div>
   
   <script>
            
    function validarFormulario() {
       var nitCliente = document.getElementById('nit_cliente').value.trim();
       var rsocialCliente = document.getElementById('rsocial_cliente').value.trim();
       var idTipoCliente = document.getElementById('id_tipo_cliente').value.trim();
       var nombreCliente = document.getElementById('nombre_cliente').value.trim();
       var apellidoCliente = document.getElementById('apellido_cliente').value.trim();
       var dirCliente = document.getElementById('dir_cliente').value.trim();
       var idCiudad = document.getElementById('id_ciudad').value.trim();
       var correoCliente = document.getElementById('correo_cliente').value.trim();
       var telCliente = document.getElementById('tel_cliente').value.trim();
       var codPostalCliente = document.getElementById('codpostal_cliente').value.trim();
       var cupoCreditoCliente = document.getElementById('cupocredito_cliente').value.trim();
       var saldoCliente = document.getElementById('saldo_cliente').value.trim();
       var comprasMesCliente = document.getElementById('comprasmes_cliente').value.trim();
       var pagosMesCliente = document.getElementById('pagosmes_cliente').value.trim();       
       var estadoCliente = document.getElementById('estado_cliente').value.trim();
       var idEmpresa = document.getElementById('id_empresa').value.trim();
    
       // Validación de los campos(no pueden estar vacios)
       if (
            nitCliente === "" ||
            rsocialCliente === "" ||
            idTipoCliente === "" ||
            nombreCliente === "" ||
            apellidoCliente === "" ||
            dirCliente === "" ||
            idCiudad === "" ||
            correoCliente === "" ||
            telCliente === "" ||
            codPostalCliente === "" ||
            cupoCreditoCliente === "" ||
            saldoCliente === "" ||
            comprasMesCliente === "" ||
            pagosMesCliente === "" ||
            estadoCliente === "" ||
            idEmpresa === ""
        ) {
            alert("Error: Todos los campos son obligatorios.");
            return false;
        

       // Validación números positivo)
       if (idTipoCliente < 0 || isNaN(idTipoCliente) || !Number.isInteger(parseFloat(idTipoCliente))) {
          alert("Error: El tipo de cliente debe ser un número entero positivo.");
          return false;
       }
       
       if (idCiudad < 0 || isNaN(idCiudad) || !Number.isInteger(parseFloat(idCiudad))) {
          alert("Error: La ciudad debe ser un número entero positivo.");
          return false;
       }
       
       if (cupoCreditoCliente < 0 || isNaN(cupoCreditoCliente) || !Number.isInteger(parseFloat(cupoCreditoCliente))) {
          alert("Error: El cupo credito debe ser un número positivo.");
          return false;
       }
       
       if (saldoCliente < 0 || isNaN(saldoCliente) || !Number.isInteger(parseFloat(saldoCliente))) {
          alert("Error: El saldo debe ser un número positivo.");
          return false;
       }
       
       if (comprasMesCliente < 0 || isNaN(comprasMesCliente) || !Number.isInteger(parseFloat(comprasMesCliente))) {
          alert("Error: La compras del mes deben ser un número positivo.");
          return false;
       }
       
       if (pagosMesCliente < 0 || isNaN(pagosMesCliente) || !Number.isInteger(parseFloat(pagosMesCliente))) {
          alert("Error: Los pagos del mes deben ser un número positivo.");
          return false;
       }
       
       if (idEmpresa < 0 || isNaN(idEmpresa) || !Number.isInteger(parseInt(idEmpresa))) {
          alert("Error: La empresa debe ser un número entero positivo.");
          return false;
       }

       // Validación del estado del cliente (solo 'A' o 'I')
       if (estadoCliente !== 'A' && estadoCliente !== 'I') {
          alert("Error: El estado del cliente debe ser 'A' (Activo) o 'I' (Inactivo).");
          return false;
       }

       return true; // Permite el envío del formulario si todas las validaciones son exitosas
    }
       </script>

   <div style="font-size: 11px; color: #cc0000; margin-top: 10px;"><?php echo $error; ?></div>
</body>
</html>

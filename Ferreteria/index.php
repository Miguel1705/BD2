<?php
include("db_connect.php");
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $myusername = mysqli_real_escape_string($conn, $_POST['username']);
    $mypassword = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT id FROM users WHERE username = '$myusername' and password = '$mypassword'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);

        // Si el resultado coincide con $myusername y $mypassword, la fila debe ser 1
        if ($count == 1) {
            session_start();
            $_SESSION['login_user'] = $myusername;
            header("location: welcome.php");
        } else {
            $error = "Tu nombre de usuario o contraseña es inválida";
        }
    } else {
        echo "Error en la consulta SQL: " . mysqli_error($conn);
    }
}
?>
<html>
    <head>
        <title>Inicio de sesion</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                background: url('frt.jpg') center center fixed;
                background-size: cover;

            }
            .login-form {
                width: 300px;
                background-color: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            }
            .login-form label {
                display: block;
                margin-bottom: 10px;
            }
            .login-form input[type="text"], .login-form input[type="password"] {
                border: none;
                border-bottom: 1px solid black;
                background-color: transparent;
                outline: none;
                width: 100%;
                ;
            }

            .login-form input[type="submit"] {
                width: 100%;
                margin-top: 20px;
                padding: 10px;
                background-color: #007BFF;
                color: white;
                border: none;
                border-radius: 5px;
            }

        </style>
    </head>
    <body>
        <div class="login-form">
            <form action = "" method = "post">
                <label for="username">Usuario:</label>
                <input type = "text" name = "username" id="username" class = "box"/>
                <label for="password">Contraseña:</label>
                <input type = "password" name = "password" id="password" class = "box" />
                <input type = "submit" value = " Ingresar "/>
            </form>
            <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
        </div>
    </body>
</html>

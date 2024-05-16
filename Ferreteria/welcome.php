<?php
include('db_connect.php');
session_start();

$user_check = $_SESSION['login_user'];

$ses_sql = mysqli_query($conn, "select username from users where username = '$user_check' ");

$row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);

$login_session = $row['username'];

if (!isset($_SESSION['login_user'])) {
    header("location:login.php");
    die();
}
?>
<html>
    <head>
        <title>Ferrimundo</title>
        <style>
            body {
                background-image: url('frt.jpg');
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: cover;
                margin: 0;
                padding: 0;
            }

            .menu {
                overflow: hidden;
                background-color: #333;
                text-align: center;
            }

            .menu a {
                
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

            h1 {
                color: white;
                text-align: center;
                font-size: 80px;
            }
        </style>
    </head>
    <body>

        <div class="menu">
            <a href="productos.php">Inventario</a>
            <a href="facturar.php">Facturar</a>
            <a href="cliente.php">Cliente</a>
        </div>
        <h1>BIENVENIDO A FERRIMUNDO</h1>
    </body>
</html>

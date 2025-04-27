<!-- filepath: c:\xampp\htdocs\Pagina_Web_proyecto\formulario_cliente.php -->
<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: inicio_gestion.php');
    exit;
}

// Leer los formularios enviados desde formularios.json
$formulariosFile = 'formularios.json';
$formularios = file_exists($formulariosFile) ? json_decode(file_get_contents($formulariosFile), true) : [];
$formularios = is_array($formularios) ? $formularios : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formularios de Cliente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        header {
            background-color: #1e88e5;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .menu-user {
            display: flex;
            gap: 20px;
        }
        .menu-user a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .menu-user a:hover {
            text-decoration: underline;
        }
        .user-menu {
            position: relative;
            display: inline-block;
        }
        .user-circle {
            width: 40px;
            height: 40px;
            background-color: #fff;
            color: #1e88e5;
            font-size: 1.5em;
            font-weight: bold;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }
        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
            z-index: 1000;
        }
        .dropdown a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: red;
            font-weight: bold;
            text-align: center;
        }
        .dropdown a:hover {
            background-color: #f4f4f9;
        }
        .user-menu:hover .dropdown {
            display: block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #1e88e5;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>Formularios de Cliente</h1>
        <div class="menu-user">
            <a href="inicio_gestion.php">Inicio</a>
            <a href="tarifas.php">Tarifas</a>
            <a href="cliente.php">Clientes</a>
            <div class="user-menu">
                <div class="user-circle">
                    <?php echo strtoupper(substr($_SESSION['usuario'], 0, 1)); ?>
                </div>
                <div class="dropdown">
                    <a href="?logout=true">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <h2>Formularios Enviados</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Empresa</th>
                    <th>Servicio</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($formularios as $formulario): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($formulario['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($formulario['email']); ?></td>
                        <td><?php echo htmlspecialchars($formulario['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($formulario['empresa']); ?></td>
                        <td><?php echo htmlspecialchars($formulario['servicio']); ?></td>
                        <td><?php echo htmlspecialchars($formulario['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($formulario['fecha']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
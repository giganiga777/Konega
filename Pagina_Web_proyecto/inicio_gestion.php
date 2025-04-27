<!-- filepath: c:\xampp\htdocs\Pagina_Web_proyecto\inicio_gestion.php -->
<?php
session_start();

// Usuarios permitidos
$usuarios = [
    'esteban' => 'Konega_2007',
    'tomas' => 'Konega_2007'
];

// Manejo del login
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario'], $_POST['password'])) {
    $usuario = strtolower(trim($_POST['usuario']));
    $password = trim($_POST['password']);

    if (isset($usuarios[$usuario]) && $usuarios[$usuario] === $password) {
        $_SESSION['usuario'] = $usuario;
    } else {
        $error = 'Usuario o contraseña incorrectos.';
    }
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    $mostrarLogin = true;
} else {
    // Leer datos de clientes y tarifas
    $clientesFile = 'clientes.json';
    $clientes = file_exists($clientesFile) ? json_decode(file_get_contents($clientesFile), true) : [];
    $clientes = is_array($clientes) ? $clientes : []; // Asegurarse de que sea un array
    $clientesActivos = count($clientes);

    // Establecer el número de tarifas contratadas a 9 por defecto
    $tarifasContratadas = 9;

    // Leer el contador de visitas desde visitas.json
    $visitasFile = 'visitas.json';
    $visitas = file_exists($visitasFile) ? json_decode(file_get_contents($visitasFile), true) : 0;

    // Leer el contador de formularios desde formularios.json
    $formulariosFile = 'formularios.json';
    $formularios = file_exists($formulariosFile) ? json_decode(file_get_contents($formulariosFile), true) : [];
    $formularios = is_array($formularios) ? $formularios : [];
    $formulariosEnviados = count($formularios); // Contar los formularios enviados
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Gestión</title>
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
        main {
            padding: 20px;
        }
        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 40px;
            flex: 1;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }
        .stat:hover {
            background-color: #f0f0f0;
            transform: scale(1.05);
        }
        .stat h3 {
            margin: 0;
            font-size: 1.8em;
            color: #1565c0;
        }
        .stat p {
            font-size: 2.5em;
            margin: 10px 0 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php if (isset($mostrarLogin) && $mostrarLogin): ?>
        <div class="login-form">
            <h2>Iniciar Sesión</h2>
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="usuario" placeholder="Usuario" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Iniciar Sesión</button>
            </form>
        </div>
    <?php else: ?>
        <header>
            <h1>Gestión de Empleados</h1>
            <div class="user-menu">
                <div class="user-circle">
                    <?php echo strtoupper(substr($_SESSION['usuario'], 0, 1)); ?>
                </div>
                <div class="dropdown">
                    <a href="?logout=true">Cerrar Sesión</a>
                </div>
            </div>
        </header>
        <main>
            <h2>Bienvenido, <?php echo ucfirst($_SESSION['usuario']); ?>!</h2>
            <div class="stats">
                <div class="stat" onclick="window.location.href='cliente.php'">
                    <h3>Clientes Activos</h3>
                    <p><?php echo $clientesActivos; ?></p>
                </div>
                <div class="stat" onclick="window.location.href='tarifas.php'">
                    <h3>Tarifas</h3>
                    <p><?php echo $tarifasContratadas; ?></p>
                </div>
                <div class="stat" onclick="window.location.href='formulario_cliente.php'">
                    <h3>Formularios de Cliente</h3>
                    <p><?php echo $formulariosEnviados; ?></p>
                </div>
                <div class="stat">
                    <h3>Visitas a la Página</h3>
                    <p><?php echo $visitas; ?></p>
                </div>
            </div>
        </main>
    <?php endif; ?>
</body>
</html>
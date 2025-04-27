<!-- filepath: c:\xampp\htdocs\Pagina_Web_proyecto\tarifas.php -->
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
    // Leer datos de tarifas
    $tarifasFile = 'tarifas.json';
    $tarifas = file_exists($tarifasFile) ? json_decode(file_get_contents($tarifasFile), true) : [];
    $tarifas = is_array($tarifas) ? $tarifas : []; // Asegurarse de que sea un array

    // Añadir el Plan Básico al principio si no existe
    $planBasico = [
    "nombre" => "🟢 Plan Básico – “Presencia Online”",
        "descripcion" => "Ideal para negocios que solo necesitan estar visibles.\n\nPágina web de 1 a 3 secciones (Inicio, Quiénes somos, Contacto)\n\nDiseño adaptable a móviles\n\nFormulario de contacto simple\n\nIntegración con redes sociales\n\nEntrega en 3-5 días\n\nHosting y dominio no incluidos (opcional con coste extra)",
        "precio" => "100-150 €"
    ];
    if (empty($tarifas) || $tarifas[0]['nombre'] !== $planBasico['nombre']) {
        array_unshift($tarifas, $planBasico);
        file_put_contents($tarifasFile, json_encode($tarifas, JSON_PRETTY_PRINT));
    }

    // Manejar solicitudes POST para añadir, editar o eliminar tarifas
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            // Añadir tarifa
            $nuevaTarifa = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'precio' => $_POST['precio']
            ];
            $tarifas[] = $nuevaTarifa;
        } elseif ($_POST['action'] === 'edit') {
            // Editar tarifa
            $index = $_POST['index'];
            $tarifas[$index] = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'precio' => $_POST['precio']
            ];
        } elseif ($_POST['action'] === 'delete') {
            // Eliminar tarifa
            $index = $_POST['index'];
            array_splice($tarifas, $index, 1);
        }

        // Guardar los cambios en el archivo JSON
        file_put_contents($tarifasFile, json_encode($tarifas, JSON_PRETTY_PRINT));
        header('Location: tarifas.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tarifas</title>
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
            align-items: center;
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
        main {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        .btn {
            background-color: #1e88e5;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #1565c0;
        }
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 2000;
            width: 90%;
            max-width: 400px;
        }
        .modal.active {
            display: block;
        }
        .modal h3 {
            margin-top: 0;
            color: #1565c0;
        }
        .modal form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .modal form input, .modal form textarea {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        .modal form textarea {
            resize: none;
            height: 100px;
        }
        .modal form button {
            background-color: #1e88e5;
            color: white;
            padding: 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal form button:hover {
            background-color: #1565c0;
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
            <h1>Gestión de Tarifas</h1>
            <div class="menu-user">
                <a href="inicio_gestion.php">Inicio</a>
                <a href="cliente.php">Clientes</a>
                <a href="formulario_cliente.php">Formularios de Cliente</a> <!-- Nuevo enlace -->
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
            <h2>Tarifas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tarifas as $index => $tarifa): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tarifa['nombre']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($tarifa['descripcion'])); ?></td>
                            <td><?php echo htmlspecialchars($tarifa['precio']); ?></td>
                            <td>
                                <button class="btn" onclick="editarTarifa(<?php echo $index; ?>)">Editar</button>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                                    <button type="submit" class="btn" onclick="return confirm('¿Estás seguro de eliminar esta tarifa?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button class="btn" onclick="abrirModal()">Añadir Tarifa</button>
        </main>

        <!-- Modal para Añadir Tarifa -->
        <div class="modal" id="modalTarifa">
            <h3>Añadir Tarifa</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <input type="text" name="nombre" placeholder="Nombre de la Tarifa" required>
                <textarea name="descripcion" placeholder="Descripción de la Tarifa" required></textarea>
                <input type="text" name="precio" placeholder="Precio de la Tarifa" required>
                <button type="submit">Guardar</button>
            </form>
        </div>

        <script>
            function abrirModal() {
                document.getElementById('modalTarifa').classList.add('active');
            }
        </script>
    <?php endif; ?>
</body>
</html>
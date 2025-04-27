<!-- filepath: c:\xampp\htdocs\Pagina_Web_proyecto\cliente.php -->
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
    // Leer datos de clientes
    $clientesFile = 'clientes.json';
    $clientes = file_exists($clientesFile) ? json_decode(file_get_contents($clientesFile), true) : [];
    $clientes = is_array($clientes) ? $clientes : []; // Asegurarse de que sea un array

    // Leer datos de tarifas
    $tarifasFile = 'tarifas.json';
    $tarifas = file_exists($tarifasFile) ? json_decode(file_get_contents($tarifasFile), true) : [];
    $tarifas = is_array($tarifas) ? $tarifas : []; // Asegurarse de que sea un array

    // Manejar solicitudes POST para añadir, editar o eliminar clientes
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            if ($_POST['action'] === 'add') {
                // Añadir cliente
                $nuevoCliente = [
                    'nombre' => $_POST['nombre'],
                    'email' => $_POST['email'],
                    'telefono' => $_POST['telefono'],
                    'tarifas' => json_decode($_POST['tarifas'], true) // Convertir JSON a array
                ];
                $clientes[] = $nuevoCliente;
            } elseif ($_POST['action'] === 'edit') {
                // Editar cliente
                $index = $_POST['index'];
                $clientes[$index] = [
                    'nombre' => $_POST['nombre'],
                    'email' => $_POST['email'],
                    'telefono' => $_POST['telefono'],
                    'tarifas' => json_decode($_POST['tarifas'], true)
                ];
            } elseif ($_POST['action'] === 'delete') {
                // Eliminar cliente
                $index = $_POST['index'];
                array_splice($clientes, $index, 1);
            }

            // Guardar los cambios en el archivo JSON
            file_put_contents($clientesFile, json_encode($clientes, JSON_PRETTY_PRINT));
            header('Location: cliente.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
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
            padding: 20px; /* Añadir margen interno al formulario */
            align-items: center; /* Centrar los elementos horizontalmente */
        }
        .modal form input {
            padding: 10px; /* Reducir el tamaño del relleno */
            font-size: 14px; /* Reducir el tamaño de la fuente */
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 90%; /* Ajustar el ancho para que no toque los bordes */
            box-sizing: border-box; /* Incluir el padding en el ancho total */
        }
        .modal form input:focus {
            border-color: #1e88e5;
            outline: none;
        }
        .tarifas-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .tarifa-item {
            padding: 5px 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f4f4f9;
            cursor: pointer;
            user-select: none;
        }
        .tarifa-item.selected {
            background-color: #1e88e5;
            color: white;
            border-color: #1e88e5;
        }
        .modal form button {
            background-color: #1e88e5;
            color: white;
            padding: 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 90%; /* Asegurar que el botón esté alineado con los campos */
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
            <h1>Gestión de Clientes</h1>
            <div class="menu-user">
                <a href="inicio_gestion.php">Inicio</a>
                <a href="tarifas.php">Tarifas</a>
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
            <h2>Clientes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Tarifas Contratadas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $index => $cliente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                            <td><?php echo htmlspecialchars(implode(', ', $cliente['tarifas'] ?? [])); ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                                    <button type="submit" class="btn">Editar</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                                    <button type="submit" class="btn" onclick="return confirm('¿Estás seguro de eliminar este cliente?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button class="btn" onclick="abrirModal()">Añadir Cliente</button>
        </main>

        <!-- Modal para Añadir Cliente -->
        <div class="modal" id="modalCliente">
            <h3>Añadir Cliente</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <input type="text" name="nombre" placeholder="Nombre Completo" required>
                <input type="email" name="email" placeholder="Correo Electrónico" required>
                <input 
                    type="tel" 
                    name="telefono" 
                    placeholder="Teléfono" 
                    pattern="[0-9]{9}" 
                    title="Debe contener exactamente 9 dígitos" 
                    maxlength="9" 
                    required>
                <label for="tarifas">Tarifas Contratadas:</label>
                <div class="tarifas-container">
                    <?php foreach ($tarifas as $tarifa): ?>
                        <div class="tarifa-item" data-value="<?php echo htmlspecialchars($tarifa['nombre']); ?>">
                            <?php echo htmlspecialchars($tarifa['nombre']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="tarifas" id="tarifasSeleccionadas">
                <button type="submit">Guardar</button>
            </form>
        </div>

        <script>
            function abrirModal() {
                document.getElementById('modalCliente').classList.add('active');
            }

            document.querySelectorAll('.tarifa-item').forEach(item => {
                item.addEventListener('click', () => {
                    item.classList.toggle('selected');
                    actualizarTarifasSeleccionadas();
                });
            });

            function actualizarTarifasSeleccionadas() {
                const seleccionadas = Array.from(document.querySelectorAll('.tarifa-item.selected'))
                    .map(item => item.getAttribute('data-value'));
                document.getElementById('tarifasSeleccionadas').value = JSON.stringify(seleccionadas);
            }
        </script>
    <?php endif; ?>
</body>
</html>
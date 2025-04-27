<?php
// Si se ha enviado el formulario, se procesa la información.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y limpiar los datos
    $nombre      = trim($_POST['nombre']);
    $email       = trim($_POST['email']);
    $telefono    = trim($_POST['telefono']);
    $empresa     = trim($_POST['empresa']);
    $servicio    = trim($_POST['servicio']);
    $descripcion = trim($_POST['descripcion']);

    // Leer el archivo formularios.json
    $formulariosFile = 'formularios.json';
    $formularios = file_exists($formulariosFile) ? json_decode(file_get_contents($formulariosFile), true) : [];
    $formularios = is_array($formularios) ? $formularios : [];

    // Añadir el nuevo formulario al archivo JSON
    $nuevoFormulario = [
        'nombre' => $nombre,
        'email' => $email,
        'telefono' => $telefono,
        'empresa' => $empresa,
        'servicio' => $servicio,
        'descripcion' => $descripcion,
        'fecha' => date('Y-m-d H:i:s') // Añadir fecha de envío
    ];
    $formularios[] = $nuevoFormulario;

    // Guardar los datos actualizados en formularios.json
    file_put_contents($formulariosFile, json_encode($formularios, JSON_PRETTY_PRINT));

    // Mostrar mensaje de éxito y redirigir
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Formulario Enviado</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: #f4f4f9;
                color: #333;
            }
            .message-container {
                text-align: center;
                background-color: #ffffff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                max-width: 500px;
                width: 90%;
            }
            .message-container h1 {
                font-size: 1.8em;
                color: #1e88e5;
                margin-bottom: 20px;
            }
            .message-container p {
                font-size: 1.2em;
                margin-bottom: 20px;
            }
            .spinner {
                border: 4px solid #f3f3f3;
                border-top: 4px solid #1e88e5;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                animation: spin 1s linear infinite;
                margin: 0 auto;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
        <script>
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 5000); // Redirigir después de 5 segundos
        </script>
    </head>
    <body>
        <div class='message-container'>
            <h1>¡Formulario Enviado!</h1>
            <p>Su solicitud ha sido enviada. En breve un técnico se pondrá en contacto con usted.</p>
            <p>Redirigiendo a la página principal...</p>
            <div class='spinner'></div>
        </div>
    </body>
    </html>";
    exit;
}

// Obtener el título de la tarifa desde la URL
$tarifa = isset($_GET['tarifa']) ? htmlspecialchars($_GET['tarifa']) : 'Tarifa Desconocida';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contratar - <?php echo $tarifa; ?></title>
  <style>
      /* Estilos del formulario */
      body {
         font-family: 'Roboto', sans-serif;
         background: #f5faff;
         color: #333;
         line-height: 1.6;
      }
      header {
         background: #0D47A1;
         padding: 20px;
         text-align: center;
         color: #fff;
      }
      .form-container {
         max-width: 600px;
         margin: 40px auto;
         background: #fff;
         padding: 30px;
         border-radius: 8px;
         box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      }
      .form-container h2 {
         text-align: center;
         color: #0D47A1;
         margin-bottom: 20px;
      }
      .form-container label {
         display: block;
         margin-bottom: 8px;
         font-weight: bold;
         color: #0D47A1;
      }
      .form-container input,
      .form-container textarea {
         width: 100%;
         padding: 10px;
         margin-bottom: 20px;
         border: 1px solid #ccc;
         border-radius: 5px;
         font-size: 1em;
      }
      .form-container input:focus,
      .form-container textarea:focus {
         outline: none;
         border-color: #0D47A1;
         box-shadow: 0 0 5px rgba(13, 71, 161, 0.5);
      }
      .form-container input[type="submit"] {
         background: linear-gradient(45deg, #0D47A1, #1976D2);
         color: #fff;
         padding: 12px 25px;
         border: none;
         border-radius: 25px;
         font-size: 1rem;
         cursor: pointer;
         transition: transform 0.3s, box-shadow 0.3s;
         display: block;
         margin: 0 auto;
      }
      .form-container input[type="submit"]:hover {
         transform: scale(1.05);
         box-shadow: 0 8px 15px rgba(0,0,0,0.3);
      }
  </style>
</head>
<body>
  <header>
    <h1>Contratar - <?php echo $tarifa; ?></h1>
  </header>

  <div class="form-container">
    <h2>Formulario de Contratación</h2>
    <form action="contratar.php" method="POST">
      <label for="nombre">Nombre Completo</label>
      <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="Tu correo electrónico" required>

      <label for="telefono">Teléfono</label>
      <input type="tel"
             id="telefono"
             name="telefono"
             placeholder="Tu número de teléfono"
             required
             pattern="[0-9]{9}"
             maxlength="9"
             title="Introduce exactamente 9 dígitos">

      <label for="empresa">Nombre de tu Empresa</label>
      <input type="text" id="empresa" name="empresa" placeholder="Nombre de la empresa" required>

      <label for="servicio">Servicio que Ofreces</label>
      <input type="text" id="servicio" name="servicio" placeholder="Servicio que ofreces" required>

      <label for="descripcion">Descripción Breve</label>
      <textarea id="descripcion" name="descripcion" placeholder="Explícanos brevemente lo que necesitas" rows="5" required></textarea>

      <input type="submit" value="Enviar">
    </form>
  </div>
</body>
</html>

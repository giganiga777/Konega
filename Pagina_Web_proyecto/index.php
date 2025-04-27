<!-- filepath: c:\xampp\htdocs\Pagina_Web_proyecto\index.php -->
<?php
// Ruta del archivo visitas.json
$visitasFile = 'visitas.json';

// Leer el número actual de visitas
$visitas = file_exists($visitasFile) ? json_decode(file_get_contents($visitasFile), true) : 0;

// Incrementar el contador de visitas
$visitas++;

// Guardar el nuevo valor en visitas.json
file_put_contents($visitasFile, json_encode($visitas));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio - Konega</title>
  <link rel="stylesheet" href="style.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <!-- Navbar -->
  <header>
    <nav class="navbar">
      <div class="logo">
          <a href="index.php"> <!-- Asegurarse de que el logo ya apunta a index.php -->
              <img src="images/logo_konega.png" alt="Logo de Konega" />
          </a>
      </div>
      <div class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </nav>
  </header>

  <!-- Menú Overlay -->
  <div class="menu-overlay" id="menuOverlay">
    <ul class="overlay-menu">
      <li><a href="index.php">🏠 Inicio</a></li>
      <li><a href="sobre.html">👥 Acerca de</a></li>
      <li><a href="servicios.html">🛠️ Servicios</a></li>
    </ul>
  </div>

  <!-- Hero Section -->
  <section class="hero animated fade-in">
    <div class="hero-overlay">
      <h1>🚀 Transforma tu Negocio con Soluciones Digitales</h1>
      <p>🌟 Diseñamos experiencias únicas para destacar en el mundo digital.</p>
      <a href="servicios.html" class="btn">✨ Descubre Más</a>
    </div>
  </section>

  <!-- How It Works Section -->
  <section class="how-it-works animated scale-up">
    <h2>🔧 ¿Cómo Trabajamos?</h2>
    <div class="steps">
      <div class="step">
        <i class="fas fa-calendar-alt"></i>
        <h3>Planificación</h3>
        <p>Definimos tus objetivos y trazamos un plan claro.</p>
      </div>
      <div class="step">
        <i class="fas fa-paint-brush"></i>
        <h3>Diseño</h3>
        <p>Creación de diseños modernos y personalizados.</p>
      </div>
      <div class="step">
        <i class="fas fa-code"></i>
        <h3>Desarrollo</h3>
        <p>Construcción de tu web con tecnología avanzada.</p>
      </div>
      <div class="step">
        <i class="fas fa-rocket"></i>
        <h3>Lanzamiento</h3>
        <p>Publicamos tu web y te acompañamos en el proceso.</p>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section class="services animated fade-in">
    <h2>🛠️ Nuestros Servicios</h2>
    <div class="service-cards">
      <div class="card">
        <i class="fas fa-laptop-code"></i>
        <h3>Diseño Web</h3>
        <p>Creación de sitios web responsivos y funcionales.</p>
        <a href="servicios.html" class="btn">Más Información</a>
      </div>
      <div class="card">
        <i class="fas fa-tools"></i>
        <h3>Soporte Técnico</h3>
        <p>Mantenimiento y actualizaciones constantes.</p>
        <a href="servicios.html" class="btn">Descubre Más</a>
      </div>
      <div class="card">
        <i class="fas fa-palette"></i>
        <h3>Diseño Gráfico</h3>
        <p>Diseños únicos que reflejan tu identidad.</p>
        <a href="servicios.html" class="btn">Ver Detalles</a>
      </div>
    </div>
  </section>

  <!-- Testimonios Section -->
  <section class="testimonials animated scale-up">
    <h2>💬 Testimonios</h2>
    <div class="testimonial-cards">
      <div class="testimonial">
        <i class="fas fa-user-circle"></i>
        <h3>Juan Pérez</h3>
        <p>“🌟 Konega transformó nuestra presencia digital. ¡Increíble trabajo!”</p>
      </div>
      <div class="testimonial">
        <i class="fas fa-user-circle"></i>
        <h3>María López</h3>
        <p>“🚀 Su equipo es profesional y atento. ¡Altamente recomendado!”</p>
      </div>
      <div class="testimonial">
        <i class="fas fa-user-circle"></i>
        <h3>Carlos García</h3>
        <p>“💡 Excelente atención al cliente y resultados impresionantes.”</p>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section class="faq animated fade-in">
    <h2>❓ Preguntas Frecuentes</h2>
    <div class="faq-cards">
      <div class="faq-item">
        <h3>📌 ¿Cuánto tiempo toma desarrollar un sitio web?</h3>
        <p>El tiempo depende del alcance del proyecto, generalmente entre 4 y 8 semanas.</p>
      </div>
      <div class="faq-item">
        <h3>📌 ¿Ofrecen soporte técnico después del lanzamiento?</h3>
        <p>¡Claro! Ofrecemos soporte continuo para garantizar que tu sitio funcione perfectamente.</p>
      </div>
      <div class="faq-item">
        <h3>📌 ¿Qué incluye el diseño gráfico?</h3>
        <p>Incluye logotipos, banners, y otros elementos visuales personalizados para tu marca.</p>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="contact-info">
    <h2>📞 Contáctanos</h2>
    <p>Email: <a href="mailto:konega14@gmail.com">konega14@gmail.com</a></p>
    <p>Teléfono/WhatsApp: <a href="https://wa.me/34653265348" target="_blank">+34 653265348</a></p>
  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Konega. Todos los derechos reservados. 🌐</p>
  </footer>

  <!-- Script -->
  <script src="script.js"></script>
</body>
</html>
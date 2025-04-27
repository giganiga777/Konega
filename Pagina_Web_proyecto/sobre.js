document.addEventListener("DOMContentLoaded", function() {
    // ----- MENÚ HAMBURGUESA -----
    const hamburger = document.getElementById("hamburger");
    const menuOverlay = document.getElementById("menuOverlay");
  
    hamburger.addEventListener("click", function() {
      menuOverlay.classList.toggle("active");
    });
  
    document.querySelectorAll(".overlay-menu a").forEach(link => {
      link.addEventListener("click", function() {
        menuOverlay.classList.remove("active");
      });
    });
  
    menuOverlay.addEventListener("click", function(e) {
      if (e.target === menuOverlay) {
        menuOverlay.classList.remove("active");
      }
    });
  
    // ----- INTERSECTION OBSERVER PARA ACTIVAR ANIMACIONES -----
    const animatedElements = document.querySelectorAll(".animated");

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("start");
            observer.unobserve(entry.target); // Deja de observar el elemento una vez que aparece
          }
        });
      },
      { threshold: 0.2 }
    );

    animatedElements.forEach((el) => observer.observe(el));
  
    // ----- SIMULACIÓN DE AUTENTICACIÓN (Ejemplo) -----
    // Aquí puedes conectar con tu sistema de login. Por ejemplo, si el usuario está logeado,
    // reemplaza el botón "Entrar" por un círculo con la inicial.
    // Para este ejemplo, usaremos una variable 'userLoggedIn'. Cambia esto por tu lógica real.
    let userLoggedIn = false; // Cambia a true para simular usuario autenticado
    const userAuth = document.getElementById("userAuth");
    const loginBtn = document.getElementById("loginBtn");

    if (userLoggedIn) {
      // Reemplazar el botón "Entrar" por el icono del usuario
      userAuth.innerHTML = `<div class="user-circle" id="userCircle">A</div>
                            <div class="user-dropdown" id="userDropdown">
                              <a href="#" id="logoutBtn">Cerrar sesión</a>
                            </div>`;
      // Animar: acercar el circulito (puedes ajustar con CSS, por ejemplo, margin-right: 5px;)
      const userCircle = document.getElementById("userCircle");
      userCircle.style.marginRight = "0";

      // Mostrar el dropdown al clicar el circulito
      userCircle.addEventListener("click", function() {
        const dropdown = document.getElementById("userDropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
      });

      // Lógica para cerrar sesión (simulación)
      const logoutBtn = document.getElementById("logoutBtn");
      logoutBtn.addEventListener("click", function(e) {
        e.preventDefault();
        // Aquí implementas la lógica de cerrar sesión
        alert("Sesión cerrada");
        // Por ejemplo, recargar la página o redirigir a login, etc.
      });
    } else {
      // Si no está logeado, el botón de login redirige a la página de registro/login
      loginBtn.addEventListener("click", function() {
        window.location.href = "login.html"; // Crea esta página de login si aún no existe
      });
    }
});

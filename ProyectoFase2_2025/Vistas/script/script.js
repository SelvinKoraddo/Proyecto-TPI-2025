document.addEventListener("DOMContentLoaded", () => {

  // Función para aplicar efecto hover a las tarjetas
  const applyHover = () => {
    document.querySelectorAll(".tech-card").forEach((card) => {
      card.addEventListener("mouseenter", function () {
        this.style.transform = "translateY(-10px) scale(1.02)";
      });
      card.addEventListener("mouseleave", function () {
        this.style.transform = "translateY(0) scale(1)";
      });
    });
  };

  applyHover();

  // Contenedores
  const resultsContainer = document.getElementById("search-results");
  let messageContainer = document.getElementById("search-message");

  // Crear contenedor de mensajes si no existe
  if (!messageContainer) {
    messageContainer = document.createElement("div");
    messageContainer.id = "search-message";
    messageContainer.style.color = "red";
    messageContainer.style.marginTop = "10px";
    resultsContainer.parentNode.insertBefore(messageContainer, resultsContainer);
  }

  const searchForm = document.querySelector(".search-form");

  // Manejar envío del formulario
  searchForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const serviceType = document.getElementById("service-type").value;
    const location = document.getElementById("location").value.trim();

    // Limpiar resultados y mensaje previo
    resultsContainer.innerHTML = "";
    messageContainer.textContent = "";

    if (!serviceType || !location) {
      messageContainer.textContent = "Por favor selecciona un tipo de servicio y ubicación";
      return;
    }

    // Mostrar mensaje de carga
    resultsContainer.innerHTML = "<p style='text-align:center;'>Buscando técnicos...</p>";

    // fetch al archivo PHP correcto (con 's')
    fetch("../Vistas/Buscar-Tecnicos.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `service_type=${encodeURIComponent(serviceType)}&location=${encodeURIComponent(location)}`
    })
      .then(res => res.text())
      .then(html => {
        resultsContainer.innerHTML = html;
        applyHover();
      })
      .catch(err => {
        console.error(err);
        resultsContainer.innerHTML = "<p style='text-align:center; color:red;'>Error al cargar resultados. Intenta de nuevo.</p>";
      });
  });

  // Navegación suave para enlaces internos
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) target.scrollIntoView({ behavior: "smooth" });
    });
  });
});

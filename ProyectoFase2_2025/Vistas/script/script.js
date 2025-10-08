// Funcionalidad básica del formulario
document.querySelector(".search-form").addEventListener("submit", function (e) {
  e.preventDefault();
  const serviceType = document.getElementById("service-type").value;
  const location = document.getElementById("location").value;

  if (!serviceType || !location) {
    alert("Por favor, selecciona un tipo de servicio y ubicación");
    return;
  }

  // Simular búsqueda
  alert(`Buscando técnicos de ${serviceType} en ${location}...`);
});

// Efecto de hover en las tarjetas de servicio
document.querySelectorAll(".service-card").forEach((card) => {
  card.addEventListener("mouseenter", function () {
    this.style.transform = "translateY(-10px) scale(1.02)";
  });

  card.addEventListener("mouseleave", function () {
    this.style.transform = "translateY(0) scale(1)";
  });
});

// Navegación suave
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) {
      target.scrollIntoView({
        behavior: "smooth",
      });
    }
  });
});

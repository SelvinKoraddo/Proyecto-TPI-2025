
// Funcionalidad para buscar Tecnico

document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".search-form");

  if (form) {
    form.addEventListener("submit", function (e) {
      const serviceType = document.getElementById("service-type").value;

      // Validar que se seleccione un tipo de servicio
      if (!serviceType) {
        e.preventDefault();
        alert("Por favor, selecciona un tipo de servicio");
        return;
      }

      // Si todo está correcto, se envía normalmente
      // (irá a BuscarTecnicos.php?especialidad=...)
    });
  }

  
  // Efecto de hover en las tarjetas de servicio
 
  document.querySelectorAll(".service-card").forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-10px) scale(1.02)";
      this.style.transition = "transform 0.3s ease";
    });

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0) scale(1)";
    });

    
  });

  // Navegación suave 
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({
          behavior: "smooth",
          block: "start"
        });
      }
    });
  });
});

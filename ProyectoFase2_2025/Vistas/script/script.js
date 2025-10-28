// Funcionalidad básica del formulario
document.querySelector(".search-form").addEventListener("submit", function (e) {
  const serviceType = document.getElementById("service-type").value;

  if (!serviceType) {
    e.preventDefault(); // Solo previene si no hay selección
    alert("Por favor, selecciona un tipo de servicio");
    return;
  }

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

// Navegación suave para anclas internas
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

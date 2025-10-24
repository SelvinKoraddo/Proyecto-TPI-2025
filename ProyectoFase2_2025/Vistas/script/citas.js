document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("cita-form");
    if (!form) return;

    form.addEventListener("submit", (e) => {
        const inicio = document.getElementById("fecha_inicio").value;
        const fin = document.getElementById("fecha_fin").value;

        if (inicio >= fin) {
            e.preventDefault();
            alert("La fecha de fin debe ser mayor que la fecha de inicio.");
            return;
        }

        // Confirmación antes de enviar
        const confirmar = confirm("¿Deseas confirmar la reserva de esta cita?");
        if (!confirmar) e.preventDefault();
    });
});

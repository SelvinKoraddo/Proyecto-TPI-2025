document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("contactForm");
    if (!form) return;

    form.addEventListener("submit", e => {
        e.preventDefault();

        const mensaje = document.getElementById("mensaje").value.trim();
        const id_tecnico = form.dataset.tecnico;

        if (!mensaje) {
            alert("Escribe un mensaje antes de enviar.");
            return;
        }

        fetch("../Vistas/procesar_contacto.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id_tecnico=${encodeURIComponent(id_tecnico)}&contenido=${encodeURIComponent(mensaje)}`
        })
        .then(res => res.json())
        .then(data => {
            const respuestaDiv = document.getElementById("respuesta");
            if (data.success) {
                respuestaDiv.style.color = "green";
                respuestaDiv.textContent = "Mensaje enviado correctamente.";
                form.reset();
            } else {
                respuestaDiv.style.color = "red";
                respuestaDiv.textContent = "Error: " + data.error;
            }
        })
        .catch(err => {
            console.error(err);
            const respuestaDiv = document.getElementById("respuesta");
            respuestaDiv.style.color = "red";
            respuestaDiv.textContent = "Error al enviar mensaje. Intenta de nuevo.";
        });
    });
});

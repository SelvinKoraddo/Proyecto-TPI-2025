paypal.Buttons({
  fundingSource: paypal.FUNDING.PAYPAL,
  style: {
    color: 'blue',
    shape: 'pill',
    label: 'pay',
    layout: 'vertical',
  },
  createOrder: function (data, actions) {
    return actions.order.create({
      purchase_units: [{
        amount: {
          value: PAGO_DATA.monto
        }
      }]
    });
  },
  onApprove: function (data, actions) {
    return actions.order.capture().then(function (details) {
      console.log("Pago completado:", details);

      // Llamar a tu backend para guardar el pago
      fetch("../Controladores/pagoController.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          paypal_id_orden: details.id,
          id_solicitud: PAGO_DATA.id_solicitud,
          id_tecnico: PAGO_DATA.id_tecnico,
          monto: PAGO_DATA.monto,
          estado: details.status
        })
      })
      .then(res => res.json())
      .then(result => {
        console.log("Respuesta del servidor:", result);

        if (result.success) {
          alert("✅ Pago realizado con éxito. ¡Gracias!");
          window.location.href = "Home.php"; // Redirigir al home después de pagar
        } else {
          alert("⚠️ Error al registrar el pago: " + (result.error || "desconocido"));
        }
      })
      .catch(err => {
        console.error("Error al enviar datos al backend:", err);
        alert("Ocurrió un error al procesar el pago.");
      });
    });
  },

  onError: function (err) {
    console.error("Error de PayPal:", err);
    alert("❌ Error con el pago. Intente de nuevo.");
  }
}).render("#paypal-button-container");

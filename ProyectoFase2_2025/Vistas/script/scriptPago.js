paypal.Buttons({
  fundingSource: paypal.FUNDING.PAYPAL,
  style: {
    color: 'blue',
    shape: 'pill',
    label: 'pay',
    layout: 'vertical',
  },
  createOrder: function (data, actions) {
    // usa el monto real
    return actions.order.create({
      purchase_units: [{
        amount: { value: PAGO_DATA.monto }
      }]
    });
  },
  onApprove: function (data, actions) {
    return actions.order.capture().then(function (details) {
      alert('Pago realizado por ' + details.payer.name.given_name + ' por $' + PAGO_DATA.monto);

      // Enviar datos al backend
      fetch('../Controladores/PagoController.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          id_solicitud: PAGO_DATA.id_solicitud,
          id_usuario: PAGO_DATA.id_usuario,
          id_tecnico: PAGO_DATA.id_tecnico,
          paypal_id_orden: data.orderID,
          estado: details.status === 'COMPLETED' ? 'completed' : 'pending',
          monto: PAGO_DATA.monto
        })
      })
      .then(res => res.json())
      .then(response => {
        if (response.success) {
          alert('Pago registrado correctamente en la base de datos');
          window.location.href = "../Vistas/Home.php";
        } else {
          console.error('Error al registrar pago:', response.error);
        }
      });
    });
  }
}).render('#paypal-button-container');

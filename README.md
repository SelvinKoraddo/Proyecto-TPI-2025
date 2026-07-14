# Proyecto TPI 2025 - Plataforma de Servicios Tecnicos

Este proyecto es una aplicacion web desarrollada en PHP que permite conectar clientes con tecnicos especializados. La plataforma incluye funcionalidades para registro de usuarios, busqueda de tecnicos, creacion de solicitudes, reserva de citas, mensajeria, pagos, resenas y administracion del sistema.

## Tecnologias utilizadas

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- XAMPP

## Estructura del proyecto

```text
ProyectoFase2_2025/
|-- Controladores/
|   |-- buscarTecnicosControlador.php
|   |-- mensajeControlador.php
|   |-- pagoController.php
|   |-- resenaControlador.php
|   |-- reservarCitaControlador.php
|   |-- solicitudControlador.php
|   `-- tecnicoControlador.php
|
|-- Modelos/
|   |-- Conexion.php
|   |-- buscarTecnicoModelo.php
|   |-- gestionarSolicitudes.php
|   |-- gestionarTecnicos.php
|   |-- mensajeModelo.php
|   |-- PagosModel.php
|   |-- resenaModelo.php
|   |-- reservarCitaModelo.php
|   |-- solicitudModelo.php
|   `-- tecnicoModelo.php
|
|-- Vistas/
|   |-- Login.php
|   |-- RegistroCliente.php
|   |-- RegistroTecnico.php
|   |-- Home.php
|   |-- HomeTecnicos.php
|   |-- BuscarTecnicos.php
|   |-- DetallesTecnico.php
|   |-- ReservarCita.php
|   |-- Pago.php
|   |-- MensajeriaCliente.php
|   |-- MensajeriaTecnico.php
|   |-- adminPanel.php
|   |-- css/
|   |-- imagenes/
|   `-- script/
|
|-- index.php
`-- proyectofinal2025.sql
```

## Funcionalidades principales

### Cliente

- Registro e inicio de sesion.
- Busqueda de tecnicos disponibles.
- Visualizacion de detalles de tecnicos.
- Creacion de solicitudes de servicio.
- Reserva de citas.
- Envio de mensajes a tecnicos.
- Realizacion de pagos.
- Publicacion de resenas.

### Tecnico

- Registro e inicio de sesion.
- Visualizacion de solicitudes.
- Gestion de perfil.
- Comunicacion con clientes.
- Revision de citas y servicios asignados.

### Administrador

- Gestion de usuarios.
- Gestion de solicitudes.
- Suspension de cuentas.
- Visualizacion de reportes y estadisticas.
- Configuracion general del sistema.

## Instalacion y configuracion

1. Copiar o clonar el proyecto dentro de la carpeta `htdocs` de XAMPP.

```text
C:\xampp\htdocs\Proyecto-TPI-2025\ProyectoFase2_2025
```

2. Iniciar los servicios de XAMPP:

- Apache
- MySQL

3. Crear la base de datos en MySQL o phpMyAdmin.

4. Importar el archivo SQL incluido en el proyecto:

```text
proyectofinal2025.sql
```

5. Verificar la configuracion de conexion a la base de datos en:

```text
Modelos/Conexion.php
```

6. Acceder al proyecto desde el navegador:

```text
http://localhost/Proyecto-TPI-2025/ProyectoFase2_2025/
```

El archivo `index.php` redirige automaticamente a la pantalla de inicio de sesion.

## Base de datos

El proyecto incluye el archivo `proyectofinal2025.sql`, el cual contiene la estructura y datos necesarios para inicializar la base de datos del sistema.

## Archivos principales

- `index.php`: punto de entrada del sistema.
- `Modelos/Conexion.php`: archivo de conexion con la base de datos.
- `Vistas/Login.php`: pantalla de inicio de sesion.
- `Vistas/Home.php`: pagina principal para clientes.
- `Vistas/HomeTecnicos.php`: pagina principal para tecnicos.
- `Vistas/adminPanel.php`: panel administrativo.


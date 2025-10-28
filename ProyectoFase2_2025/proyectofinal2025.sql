-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-10-2025 a las 08:31:35
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12
CREATE DATABASE proyectofinal2025;
use proyectofinal2025;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyectofinal2025`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita`
--

CREATE TABLE `cita` (
  `id_cita` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `estado` enum('pendiente','confirmada','reprogramada','finalizada','cancelada') NOT NULL DEFAULT 'pendiente',
  `notas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad`
--

CREATE TABLE `especialidad` (
  `id_especialidad` int(11) AUTO_INCREMENT PRIMARY KEY,
  `nombre` varchar(80) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Definiendo especialidades
INSERT INTO especialidad (nombre, descripcion)
VALUES
('Refrigeradoras', 'Reparación y mantenimiento de refrigeradoras'),
('Lavadoras', 'Servicio técnico para lavadoras y secadoras'),
('Hornos', 'Reparación de hornos eléctricos y a gas'),
('Televisores', 'Reparación de TV LED, LCD y Smart TV'),
('Albañilería', 'Servicios de construcción y remodelación'),
('Electricidad', 'Instalaciones y mantenimiento eléctrico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

CREATE TABLE `mensaje` (
  `id_mensaje` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_creado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_tecnico` int(11) NOT NULL,
  `paypal_id_orden` varchar(80) NOT NULL,
  `estado` enum('pending','rejected','completed') NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil_tecnico`
--

CREATE TABLE `perfil_tecnico` (
  `id_tecnico` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tarifa_hora` decimal(10,2) NOT NULL DEFAULT 0.00,
  `zona_trabajo` varchar(120) NOT NULL,
  `estado` enum('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  `aprobado_por` int(11) DEFAULT NULL,
  `fecha_aprobado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resena`
--

CREATE TABLE `resena` (
  `id_resena` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_tecnico` int(11) NOT NULL,
  `calificacion` tinyint(4) NOT NULL,
  `comentario` varchar(300) DEFAULT NULL,
  `fecha_creada` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud`
--

CREATE TABLE `solicitud` (
  `id_solicitud` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_tecnico` int(11) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `estado` enum('creada','asignada','en_proceso','completada','cancelada') NOT NULL DEFAULT 'creada',
  `fecha_programacion` datetime DEFAULT NULL,
  `direccion_servicio` varchar(180) NOT NULL,
  `monto` decimal(10,2) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnico_especialidad`
--

CREATE TABLE `tecnico_especialidad` (
  `id_tecnico` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_completo` varchar(120) NOT NULL,
  `contrasena_hash` varchar(255) NOT NULL,
  `rol` enum('cliente','tecnico','admin') NOT NULL DEFAULT 'cliente',
  `telefono` varchar(25) DEFAULT NULL,
  `correo` varchar(120) NOT NULL,
  `fecha_creado` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cita`
--
ALTER TABLE `cita`
  ADD PRIMARY KEY (`id_cita`),
  ADD KEY `ix_cita_sol` (`id_solicitud`,`fecha_inicio`);

--
-- Indices de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `fk_msg_user` (`id_usuario`),
  ADD KEY `ix_msg_sol` (`id_solicitud`,`fecha_creado`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD UNIQUE KEY `uq_pago_paypal` (`paypal_id_orden`),
  ADD KEY `ix_pago_sol` (`id_solicitud`),
  ADD KEY `ix_pago_cli` (`id_usuario`),
  ADD KEY `ix_pago_tec` (`id_tecnico`);

--
-- Indices de la tabla `perfil_tecnico`
--
ALTER TABLE `perfil_tecnico`
  ADD PRIMARY KEY (`id_tecnico`),
  ADD UNIQUE KEY `uq_perfil_por_usuario` (`id_usuario`),
  ADD KEY `fk_pt_aprobado_por` (`aprobado_por`),
  ADD KEY `ix_pt_zona` (`zona_trabajo`),
  ADD KEY `ix_pt_estado` (`estado`);

--
-- Indices de la tabla `resena`
--
ALTER TABLE `resena`
  ADD PRIMARY KEY (`id_resena`),
  ADD UNIQUE KEY `uq_resena_sol_cli` (`id_solicitud`,`id_usuario`),
  ADD KEY `fk_res_cli` (`id_usuario`),
  ADD KEY `ix_res_tec` (`id_tecnico`);

--
-- Indices de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `fk_sol_cliente` (`id_usuario`),
  ADD KEY `ix_sol_estado` (`estado`),
  ADD KEY `ix_sol_tecnico` (`id_tecnico`,`estado`);

--
-- Indices de la tabla `tecnico_especialidad`
--
ALTER TABLE `tecnico_especialidad`
  ADD PRIMARY KEY (`id_tecnico`,`id_especialidad`),
  ADD KEY `fk_te_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `uq_usuarios_correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cita`
--
ALTER TABLE `cita`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `perfil_tecnico`
--
ALTER TABLE `perfil_tecnico`
  MODIFY `id_tecnico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `resena`
--
ALTER TABLE `resena`
  MODIFY `id_resena` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cita`
--
ALTER TABLE `cita`
  ADD CONSTRAINT `fk_cita_sol` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitud` (`id_solicitud`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD CONSTRAINT `fk_msg_sol` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitud` (`id_solicitud`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_msg_user` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `fk_pago_cli` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pago_sol` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitud` (`id_solicitud`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pago_tec` FOREIGN KEY (`id_tecnico`) REFERENCES `perfil_tecnico` (`id_tecnico`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `perfil_tecnico`
--
ALTER TABLE `perfil_tecnico`
  ADD CONSTRAINT `fk_pt_aprobado_por` FOREIGN KEY (`aprobado_por`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pt_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `resena`
--
ALTER TABLE `resena`
  ADD CONSTRAINT `fk_res_cli` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_res_sol` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitud` (`id_solicitud`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_res_tec` FOREIGN KEY (`id_tecnico`) REFERENCES `perfil_tecnico` (`id_tecnico`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD CONSTRAINT `fk_sol_cliente` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sol_tecnico` FOREIGN KEY (`id_tecnico`) REFERENCES `perfil_tecnico` (`id_tecnico`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `tecnico_especialidad`
--
ALTER TABLE `tecnico_especialidad`
  ADD CONSTRAINT `fk_te_especialidad` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_te_tecnico` FOREIGN KEY (`id_tecnico`) REFERENCES `perfil_tecnico` (`id_tecnico`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

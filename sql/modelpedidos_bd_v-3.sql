-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-07-2025 a las 06:50:51
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `modelpedidos_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acabados`
--

CREATE TABLE `acabados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `acabados`
--

INSERT INTO `acabados` (`id`, `nombre`) VALUES
(2, 'Anillado'),
(3, 'Enmicado'),
(1, 'Numeración'),
(4, 'Plastificado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivos_formatos`
--

CREATE TABLE `archivos_formatos` (
  `id` int(11) NOT NULL,
  `formato_id` int(11) NOT NULL,
  `tipo` enum('edicion','visualizacion') NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `archivos_formatos`
--

INSERT INTO `archivos_formatos` (`id`, `formato_id`, `tipo`, `nombre_archivo`, `ruta_archivo`) VALUES
(1, 2, 'edicion', 'actual-server-permisos.jpg', 'http://localhost/modelpedidos/src/reporte_formatos.php'),
(2, 2, 'visualizacion', 'trabajo_final.pdf', 'http://localhost/modelpedidos/src/reporte_formatos.php'),
(3, 2, 'edicion', 'actual-server-permisos.jpg', 'http://localhost/modelpedidos/src/reporte_formatos.php'),
(4, 2, 'visualizacion', 'trabajo_final.pdf', 'http://localhost/modelpedidos/src/reporte_formatos.php'),
(5, 3, 'edicion', 'actual-server-permisos.jpg', 'http://localhost/modelpedidos/src/reporte_formatos.php'),
(6, 3, 'visualizacion', 'trabajo_final.pdf', 'http://localhost/modelpedidos/src/reporte_formatos.php'),
(7, 3, 'edicion', 'actual-server-permisos.jpg', 'http://localhost/modelpedidos/src/reporte_formatos.php'),
(8, 3, 'visualizacion', 'trabajo_final.pdf', 'http://localhost/modelpedidos/src/reporte_formatos.php'),
(21, 5, 'edicion', '', 'http://localhost/modelpedidos/src/reporte_formatos.php'),
(22, 5, 'visualizacion', '', 'http://localhost/modelpedidos/src/reporte_formatos.php'),
(25, 6, 'edicion', '', 'http://localhost/modelpedidos/src/reporte_formatos.php'),
(26, 6, 'visualizacion', '', 'http://localhost/modelpedidos/src/reporte_formatos.php'),
(29, 4, 'edicion', '', 'http://localhost/modelpedidos/src/reporte_formatos.php'),
(30, 4, 'visualizacion', '', 'http://localhost/modelpedidos/src/reporte_formatos.php'),
(31, 7, 'edicion', 'actual-server-permisos.jpg', ''),
(32, 7, 'visualizacion', 'trabajo_final.pdf', ''),
(33, 7, 'edicion', 'actual-server-permisos.jpg', ''),
(34, 7, 'visualizacion', 'trabajo_final.pdf', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `nom_empresa` varchar(100) NOT NULL,
  `RUC` varchar(20) DEFAULT NULL,
  `lugares_entrega` text DEFAULT NULL,
  `nom_comprador` varchar(100) NOT NULL,
  `celular_comprador` varchar(20) DEFAULT NULL,
  `correo_comprador` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `compradores_adicionales` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`compradores_adicionales`)),
  `lugares_extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`lugares_extra`)),
  `activa` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `nom_empresa`, `RUC`, `lugares_entrega`, `nom_comprador`, `celular_comprador`, `correo_comprador`, `fecha_registro`, `compradores_adicionales`, `lugares_extra`, `activa`) VALUES
(1, 'TechSolutions SAC', '20567893451', 'Av. Lima 123, Oficina 45', 'Juan Pérez', '987654321', 'juan.perez@techsolutions.com', '2025-07-16 23:22:34', NULL, NULL, 1),
(2, 'Distribuidora Atlántida', '20123456789', 'Calle Los Pinos 456, Warehouse 2', 'María Gómez', '912345678', 'maria.gomez@atlantic.com.pe', '2025-07-16 23:22:34', NULL, NULL, 1),
(3, 'Farmacorp EIRL', '20654321876', 'Jr. Amazonas 789 y Av. Primavera 321', 'Carlos Rojas', '976543210', 'crojas@farmacorp.com', '2025-07-16 23:22:34', NULL, NULL, 1),
(4, 'Constructora Diamante', '20789123456', 'Proyecto Urb. Las Flores, Mz. L Lt. 8', 'Luisa Fernández', '934567890', 'lfernandez@diamante.pe', '2025-07-16 23:22:34', NULL, NULL, 1),
(5, 'Importadora Orion LLC', '20345678912', 'Almacén Zona Industrial A-7', 'Roberto Vargas', '945678901', 'rvargas@orion-import.com', '2025-07-16 23:22:34', NULL, NULL, 1),
(6, '', '2021548785', NULL, '', NULL, NULL, '2025-07-16 23:52:26', NULL, NULL, 1),
(7, '', '2021548785', NULL, '', NULL, NULL, '2025-07-16 23:56:13', NULL, NULL, 1),
(8, 'cotrans s.a.', '123545678', NULL, 'jorge fernandez', NULL, NULL, '2025-07-16 23:57:07', NULL, NULL, 1),
(9, 'Ratas y Ratones SAC', '232323', 'Av.Aviación 525', 'Mr.Rats', '123456789', 'rats@rats.com', '2025-07-17 00:04:20', NULL, NULL, 1),
(10, 'Nueva empresa', '342343', 'Cerro Colorado', 'Rosario', '123456789', 'rosario@prueba.com', '2025-07-17 00:17:41', NULL, NULL, 1),
(11, 'prueba 005', '12345678', 'arequipa', 'joseluis', '123456789', 'prueba005@qwe.com', '2025-07-17 00:26:07', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formatos`
--

CREATE TABLE `formatos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `dimensiones` enum('A6','A5','A4','Carta','Oficio') NOT NULL,
  `copias_por_juego` tinyint(4) NOT NULL,
  `version` varchar(20) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `formatos`
--

INSERT INTO `formatos` (`id`, `titulo`, `descripcion`, `dimensiones`, `copias_por_juego`, `version`, `empresa_id`, `fecha_creacion`) VALUES
(1, 'Vale de consumo', 'formato color negro ', 'A5', 1, 'v-01', 5, '2025-07-17 22:39:06'),
(2, 'Vale de consumo', 'formato color negro ', 'A5', 1, 'v-01', 5, '2025-07-17 22:49:21'),
(3, 'kardex', 'color amarillo, cartulina escolar', 'A6', 1, 'v01', 10, '2025-07-17 22:56:05'),
(4, 'control almacen v22', 'formato cambio', 'A4', 2, '01', 5, '2025-07-17 23:02:26'),
(5, 'ticket consumo 33', 'papel quimico, impresion color azul', 'A5', 1, '01', 8, '2025-07-17 23:04:23'),
(6, 'Checlist volquete 2', 'Original, copia verde, copia amarilla, block de 100 juegos', 'A5', 2, '01', 8, '2025-07-18 00:16:34'),
(7, 'reporte trabajo de campo', 'documento donde varian usualmento nombres de los tecnicos participantes', 'A5', 1, '2', 8, '2025-07-18 03:40:39'),
(8, 'tractor oruga 003 cambio', 'doble formato medio', 'A4', 2, '335', 8, '2025-07-18 03:51:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formatos_acabados`
--

CREATE TABLE `formatos_acabados` (
  `formato_id` int(11) NOT NULL,
  `acabado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `formatos_acabados`
--

INSERT INTO `formatos_acabados` (`formato_id`, `acabado_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(7, 2),
(8, 1),
(8, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` enum('admin','editor') NOT NULL DEFAULT 'editor',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password_hash`, `rol`, `fecha_creacion`) VALUES
(1, 'admin', '$2y$10$20WFr16twdbxkXbwOyycxuNXz.uXR.Y5ciCTYShCCIFJLB6JGwLse', 'admin', '2025-07-17 16:51:54');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acabados`
--
ALTER TABLE `acabados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `archivos_formatos`
--
ALTER TABLE `archivos_formatos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formato_id` (`formato_id`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `formatos`
--
ALTER TABLE `formatos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- Indices de la tabla `formatos_acabados`
--
ALTER TABLE `formatos_acabados`
  ADD PRIMARY KEY (`formato_id`,`acabado_id`),
  ADD KEY `acabado_id` (`acabado_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acabados`
--
ALTER TABLE `acabados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `archivos_formatos`
--
ALTER TABLE `archivos_formatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `formatos`
--
ALTER TABLE `formatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `archivos_formatos`
--
ALTER TABLE `archivos_formatos`
  ADD CONSTRAINT `archivos_formatos_ibfk_1` FOREIGN KEY (`formato_id`) REFERENCES `formatos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `formatos`
--
ALTER TABLE `formatos`
  ADD CONSTRAINT `formatos_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`);

--
-- Filtros para la tabla `formatos_acabados`
--
ALTER TABLE `formatos_acabados`
  ADD CONSTRAINT `formatos_acabados_ibfk_1` FOREIGN KEY (`formato_id`) REFERENCES `formatos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `formatos_acabados_ibfk_2` FOREIGN KEY (`acabado_id`) REFERENCES `acabados` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

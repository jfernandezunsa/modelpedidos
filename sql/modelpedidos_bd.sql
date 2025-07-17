-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-07-2025 a las 02:46:38
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
  `lugares_extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`lugares_extra`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `nom_empresa`, `RUC`, `lugares_entrega`, `nom_comprador`, `celular_comprador`, `correo_comprador`, `fecha_registro`, `compradores_adicionales`, `lugares_extra`) VALUES
(1, 'TechSolutions SAC', '20567893451', 'Av. Lima 123, Oficina 45', 'Juan Pérez', '987654321', 'juan.perez@techsolutions.com', '2025-07-16 23:22:34', NULL, NULL),
(2, 'Distribuidora Atlántida', '20123456789', 'Calle Los Pinos 456, Warehouse 2', 'María Gómez', '912345678', 'maria.gomez@atlantic.com.pe', '2025-07-16 23:22:34', NULL, NULL),
(3, 'Farmacorp EIRL', '20654321876', 'Jr. Amazonas 789 y Av. Primavera 321', 'Carlos Rojas', '976543210', 'crojas@farmacorp.com', '2025-07-16 23:22:34', NULL, NULL),
(4, 'Constructora Diamante', '20789123456', 'Proyecto Urb. Las Flores, Mz. L Lt. 8', 'Luisa Fernández', '934567890', 'lfernandez@diamante.pe', '2025-07-16 23:22:34', NULL, NULL),
(5, 'Importadora Orion LLC', '20345678912', 'Almacén Zona Industrial A-7', 'Roberto Vargas', '945678901', 'rvargas@orion-import.com', '2025-07-16 23:22:34', NULL, NULL),
(6, '', '2021548785', NULL, '', NULL, NULL, '2025-07-16 23:52:26', NULL, NULL),
(7, '', '2021548785', NULL, '', NULL, NULL, '2025-07-16 23:56:13', NULL, NULL),
(8, 'cotrans s.a.', '123545678', NULL, 'jorge fernandez', NULL, NULL, '2025-07-16 23:57:07', NULL, NULL),
(9, 'rats ss', '232323', NULL, 'cart', NULL, NULL, '2025-07-17 00:04:20', NULL, NULL),
(10, 'Nueva empresa', '342343', 'Cerro Colorado', 'Rosario', '123456789', 'rosario@prueba.com', '2025-07-17 00:17:41', NULL, NULL),
(11, 'prueba 005', '12345678', 'arequipa', 'joseluis', '123456789', 'prueba005@qwe.com', '2025-07-17 00:26:07', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

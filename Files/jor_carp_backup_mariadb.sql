-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 13-01-2020 a las 19:37:08
-- Versión del servidor: 10.3.16-MariaDB
-- Versión de PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Volcando estructura de base de datos para jor_carp
CREATE DATABASE IF NOT EXISTS `jor_carp` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `jor_carp`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers`
--

CREATE TABLE `customers` (
  `cus_id` int(11) UNSIGNED NOT NULL,
  `cus_name` varchar(255) NOT NULL,
  `cus_nif` varchar(255) NOT NULL,
  `cus_address1` varchar(255) NOT NULL,
  `cus_address2` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoices`
--

CREATE TABLE `invoices` (
  `inv_id` int(11) NOT NULL,
  `inv_obj` text NOT NULL,
  `cus_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `inv_ref` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `outgoing`
--

CREATE TABLE `outgoing` (
  `out_id` int(11) UNSIGNED NOT NULL,
  `sup_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `out_ref` varchar(255) NOT NULL,
  `out_date` varchar(255) NOT NULL,
  `out_gross` float UNSIGNED NOT NULL,
  `out_igic` float UNSIGNED NOT NULL,
  `out_total` float UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suppliers`
--

CREATE TABLE `suppliers` (
  `sup_id` int(11) UNSIGNED NOT NULL,
  `sup_name` varchar(255) NOT NULL,
  `sup_cif` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `suppliers`
--

INSERT INTO `suppliers` (`sup_id`, `sup_name`, `sup_cif`) VALUES
(1, 'LEROY MERLIN', 'B84818442'),
(2, 'DISA', 'A38013611'),
(3, 'BRICOMART', 'B84406289'),
(4, 'NICODEMO', '42684100X'),
(5, 'ATLÁNTICO', '54066823X'),
(6, 'COMERCIAL CID', 'A35045756'),
(7, 'MADERAS EL PINO', 'B35027143'),
(8, 'HERRAJES FAMAR', 'B35744630');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_mail` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_status` int(11) NOT NULL DEFAULT 0 COMMENT '0 = Blocked; 1 = Validated',
  `user_privileges` int(11) NOT NULL DEFAULT 0 COMMENT '0 = User; 1 = Admin'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_mail`, `user_password`, `user_status`, `user_privileges`) VALUES
(1, 'Jonathan', 'j@j.com', '17f1df9f24dcdbbad02ae0f620e4ca53', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`cus_id`);

--
-- Indices de la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`inv_id`),
  ADD KEY `fk_cus_id` (`cus_id`);

--
-- Indices de la tabla `outgoing`
--
ALTER TABLE `outgoing`
  ADD PRIMARY KEY (`out_id`),
  ADD KEY `sup_id` (`sup_id`);

--
-- Indices de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`sup_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `customers`
--
ALTER TABLE `customers`
  MODIFY `cus_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `invoices`
--
ALTER TABLE `invoices`
  MODIFY `inv_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `outgoing`
--
ALTER TABLE `outgoing`
  MODIFY `out_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `sup_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_cus_id` FOREIGN KEY (`cus_id`) REFERENCES `customers` (`cus_id`);

--
-- Filtros para la tabla `outgoing`
--
ALTER TABLE `outgoing`
  ADD CONSTRAINT `sup_id` FOREIGN KEY (`sup_id`) REFERENCES `suppliers` (`sup_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

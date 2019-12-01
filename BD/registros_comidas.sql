-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 01-12-2019 a las 21:35:44
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `registros_comidas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `Id_categoria` int(11) NOT NULL DEFAULT '0',
  `Nombre` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`Id_categoria`, `Nombre`) VALUES
(0, 'Hortalizas'),
(1, 'Frutas'),
(2, 'Cereales integrales y derivados'),
(3, 'Legumbres'),
(4, 'L'),
(5, 'Carnes magras y huevo'),
(6, 'Aceite'),
(7, 'Grasas vegetales'),
(8, 'Grasas animales y trans'),
(9, 'Dulces light');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_comida`
--

CREATE TABLE IF NOT EXISTS `categoria_comida` (
  `Id_categoria` int(11) DEFAULT NULL,
  `Id_comida` int(11) DEFAULT NULL,
  KEY `Id_categoria` (`Id_categoria`),
  KEY `Id_comida` (`Id_comida`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `categoria_comida`
--

INSERT INTO `categoria_comida` (`Id_categoria`, `Id_comida`) VALUES
(0, 0),
(0, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 5),
(2, 6),
(2, 7),
(3, 8),
(3, 9),
(3, 10),
(4, 11),
(4, 12),
(4, 13),
(5, 14),
(5, 15),
(5, 16),
(6, 17),
(6, 18),
(6, 19),
(7, 20),
(7, 21),
(7, 22),
(8, 23),
(8, 24),
(8, 25),
(9, 26),
(9, 27),
(9, 28),
(5, 29),
(2, 29),
(5, 30),
(2, 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comida`
--

CREATE TABLE IF NOT EXISTS `comida` (
  `Id_comida` int(11) NOT NULL DEFAULT '0',
  `Nombre` varchar(50) DEFAULT NULL,
  `Cant_porcion` int(11) DEFAULT NULL,
  `Cred_porcion` float DEFAULT NULL,
  `Id_unidad_cant_porcion` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id_comida`),
  UNIQUE KEY `Nombre` (`Nombre`),
  KEY `Id_unidad_cant_porcion` (`Id_unidad_cant_porcion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `comida`
--

INSERT INTO `comida` (`Id_comida`, `Nombre`, `Cant_porcion`, `Cred_porcion`, `Id_unidad_cant_porcion`) VALUES
(0, 'zanahoria', 100, 0.75, 2),
(1, 'remolacha', 60, 1, 2),
(2, 'manzana', 100, 1, 2),
(3, 'mandarina', 50, 0.5, 2),
(4, 'banana', 120, 2.5, 2),
(5, 'arroz blanco', 1, 4, 5),
(6, 'arroz integral', 1, 2.75, 5),
(7, 'barra de cereal light', 23, 2, 2),
(8, 'arvejas', 33, 2.25, 2),
(9, 'lentejas', 33, 2.25, 2),
(10, 'porotos', 33, 2, 2),
(11, 'leche descremada', 200, 0.5, 7),
(12, 'queso untable descremado', 10, 0.25, 2),
(13, 'yogur descremado', 200, 0.75, 2),
(14, 'huevo', 1, 2.25, 0),
(15, 'pechuga de pollo sin piel', 200, 3, 2),
(16, 'vacio', 150, 6.5, 2),
(17, 'aceite de oliva', 1, 2, 1),
(18, 'aceite de girasol', 1, 2.25, 1),
(19, 'aceite de soja', 1, 2, 1),
(20, 'nuez', 5, 1.5, 8),
(21, 'almendras', 10, 1.5, 0),
(22, 'aceitunas verdes', 1, 0.25, 0),
(23, 'mayonesa', 1, 2.5, 1),
(24, 'salsa golf light', 1, 1, 1),
(25, 'manteca', 1, 3.75, 1),
(26, 'mermelada light', 1, 0.25, 1),
(27, 'dulce de batata', 50, 3, 2),
(28, 'dulce de leche', 1, 0.75, 1),
(29, 'empanada de carne al horno', 1, 6, 0),
(30, 'tarta de atún', 1, 3, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `momento`
--

CREATE TABLE IF NOT EXISTS `momento` (
  `Id_momento` int(11) NOT NULL DEFAULT '0',
  `Nombre` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`Id_momento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `momento`
--

INSERT INTO `momento` (`Id_momento`, `Nombre`) VALUES
(0, 'Desayuno'),
(1, 'Media Mañana'),
(2, 'Almuerzo'),
(3, 'Media Tarde'),
(4, 'Merienda'),
(5, 'Cena');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--

CREATE TABLE IF NOT EXISTS `registro` (
  `Id_registro` int(11) NOT NULL AUTO_INCREMENT,
  `Fecha` date DEFAULT NULL,
  `Id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id_registro`),
  KEY `Id_usuario` (`Id_usuario`),
  KEY `id e fecha unico` (`Id_usuario`,`Fecha`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=111 ;

--
-- Volcado de datos para la tabla `registro`
--

INSERT INTO `registro` (`Id_registro`, `Fecha`, `Id_usuario`) VALUES
(6, '2018-09-04', 1),
(7, '2018-09-17', 1),
(4, '2018-10-01', 1),
(3, '2018-10-03', 1),
(5, '2018-10-18', 1),
(2, '2018-10-20', 1),
(1, '2018-10-21', 1),
(8, '2018-11-20', 1),
(101, '2018-11-25', 1),
(103, '2018-11-26', 1),
(105, '2018-11-27', 1),
(106, '2018-11-28', 1),
(109, '2018-11-29', 1),
(108, '2018-11-30', 13),
(110, '2019-12-01', 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_comida`
--

CREATE TABLE IF NOT EXISTS `registro_comida` (
  `Id_registro` int(11) DEFAULT NULL,
  `Id_comida` int(11) DEFAULT NULL,
  `Cantidad` float DEFAULT NULL,
  `Id_momento` int(11) DEFAULT NULL,
  KEY `Id_momento` (`Id_momento`),
  KEY `Id_registro` (`Id_registro`),
  KEY `Id_comida` (`Id_comida`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `registro_comida`
--

INSERT INTO `registro_comida` (`Id_registro`, `Id_comida`, `Cantidad`, `Id_momento`) VALUES
(103, 5, 1, 0),
(103, 2, 1, 1),
(103, 20, 5, 2),
(103, 6, 1, 3),
(103, 26, 1, 4),
(103, 29, 2, 4),
(105, 29, 1, 5),
(108, 0, 3, 0),
(109, 5, 1.5, 0),
(109, 4, 1, 1),
(109, 15, 2, 2),
(109, 7, 1, 3),
(109, 2, 5, 4),
(110, 5, 2, 0),
(110, 3, 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token`
--

CREATE TABLE IF NOT EXISTS `token` (
  `Id_token` int(11) NOT NULL AUTO_INCREMENT,
  `Fecha_creacion` date NOT NULL,
  `Fecha_expira` date NOT NULL,
  `Id_usuario` int(11) NOT NULL,
  `Valido` tinyint(1) NOT NULL,
  PRIMARY KEY (`Id_token`),
  KEY `Id_usuario` (`Id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=67 ;

--
-- Volcado de datos para la tabla `token`
--

INSERT INTO `token` (`Id_token`, `Fecha_creacion`, `Fecha_expira`, `Id_usuario`, `Valido`) VALUES
(1, '2018-09-30', '2018-10-07', 1, 0),
(2, '2018-09-30', '2018-10-07', 1, 0),
(4, '2018-10-16', '2018-10-23', 1, 1),
(5, '2018-10-16', '2018-10-23', 1, 1),
(6, '2018-10-16', '2018-10-23', 1, 1),
(7, '2018-10-16', '2018-10-23', 1, 1),
(8, '2018-10-16', '2018-10-23', 1, 1),
(9, '2018-10-16', '2018-10-23', 1, 1),
(10, '2018-10-16', '2018-10-23', 1, 1),
(11, '2018-10-16', '2018-10-23', 1, 1),
(12, '2018-10-16', '2018-10-23', 1, 1),
(13, '2018-10-16', '2018-10-23', 1, 1),
(14, '2018-10-16', '2018-10-23', 1, 1),
(15, '2018-10-16', '2018-10-23', 1, 1),
(16, '2018-10-16', '2018-10-23', 1, 1),
(17, '2018-10-16', '2018-10-23', 1, 1),
(18, '2018-10-16', '2018-10-23', 1, 1),
(19, '2018-10-16', '2018-10-23', 1, 1),
(20, '2018-10-16', '2018-10-23', 1, 1),
(21, '2018-10-16', '2018-10-23', 1, 1),
(22, '2018-10-16', '2018-10-23', 1, 1),
(23, '2018-10-16', '2018-10-23', 1, 1),
(24, '2018-10-16', '2018-10-23', 1, 1),
(25, '2018-10-16', '2018-10-23', 1, 1),
(26, '2018-10-16', '2018-10-23', 1, 1),
(27, '2018-10-16', '2018-10-23', 1, 1),
(28, '2018-10-16', '2018-10-23', 1, 1),
(29, '2018-10-17', '2018-10-24', 1, 1),
(30, '2018-10-22', '2018-10-29', 1, 1),
(31, '2018-10-29', '2018-11-05', 1, 1),
(32, '2018-10-31', '2018-11-07', 1, 1),
(33, '2018-11-07', '2018-11-14', 1, 1),
(34, '2018-11-20', '2018-11-27', 1, 1),
(35, '2018-11-22', '2018-11-29', 1, 1),
(36, '2018-11-22', '2018-11-29', 1, 1),
(37, '2018-11-23', '2018-11-30', 1, 1),
(38, '2018-11-23', '2018-11-30', 1, 1),
(40, '2018-11-26', '2018-12-03', 1, 1),
(41, '2018-11-26', '2018-12-03', 1, 1),
(48, '2018-11-26', '2018-12-03', 10, 1),
(49, '2018-11-26', '2018-12-03', 1, 1),
(50, '2018-11-27', '2018-12-04', 1, 1),
(51, '2018-11-29', '2018-12-06', 11, 1),
(52, '2018-11-29', '2018-12-06', 11, 1),
(53, '2018-11-29', '2018-12-06', 13, 1),
(54, '2018-11-30', '2018-12-07', 1, 1),
(55, '2018-11-30', '2018-12-07', 1, 0),
(56, '2018-12-01', '2018-12-08', 1, 1),
(57, '2018-12-01', '2018-12-08', 14, 1),
(58, '2018-12-01', '2018-12-08', 1, 1),
(59, '2018-12-01', '2018-12-08', 15, 1),
(60, '2019-01-30', '2019-02-06', 1, 1),
(61, '2019-12-01', '2019-12-08', 16, 1),
(62, '2019-12-01', '2019-12-08', 16, 1),
(63, '2019-12-01', '2019-12-08', 14, 1),
(64, '2019-12-01', '2019-12-08', 1, 1),
(65, '2019-12-01', '2019-12-08', 1, 1),
(66, '2019-12-01', '2019-12-08', 16, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_cant_porcion`
--

CREATE TABLE IF NOT EXISTS `unidad_cant_porcion` (
  `Id_unidad_cant_porcion` int(11) NOT NULL DEFAULT '0',
  `Nombre` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`Id_unidad_cant_porcion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `unidad_cant_porcion`
--

INSERT INTO `unidad_cant_porcion` (`Id_unidad_cant_porcion`, `Nombre`) VALUES
(0, 'unidad/es'),
(1, 'cucharada/s tamaño postre'),
(2, 'gramo/s'),
(3, 'kilogramo/s'),
(4, 'plato/s abundante/s'),
(5, 'plato/s de postre'),
(6, 'taza/s de té'),
(7, 'cm3'),
(8, 'mariposa/s');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `Id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(32) DEFAULT NULL,
  `Contra` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`Id_usuario`),
  UNIQUE KEY `Nombre` (`Nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`Id_usuario`, `Nombre`, `Contra`) VALUES
(1, 'test', 'test12345'),
(2, 'juan f', '12345678'),
(10, 'test23451', '12345678'),
(11, 'user1', '111111111'),
(13, 'test2', 'test2'),
(14, 'Test3', 'test3'),
(15, 'yo', '77777777'),
(16, 'juan', '12345678');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categoria_comida`
--
ALTER TABLE `categoria_comida`
  ADD CONSTRAINT `categoria_comida_ibfk_1` FOREIGN KEY (`Id_categoria`) REFERENCES `categoria` (`Id_categoria`),
  ADD CONSTRAINT `categoria_comida_ibfk_2` FOREIGN KEY (`Id_comida`) REFERENCES `comida` (`Id_comida`);

--
-- Filtros para la tabla `comida`
--
ALTER TABLE `comida`
  ADD CONSTRAINT `comida_ibfk_1` FOREIGN KEY (`Id_unidad_cant_porcion`) REFERENCES `unidad_cant_porcion` (`Id_unidad_cant_porcion`);

--
-- Filtros para la tabla `registro`
--
ALTER TABLE `registro`
  ADD CONSTRAINT `registro_ibfk_1` FOREIGN KEY (`Id_usuario`) REFERENCES `usuario` (`Id_usuario`);

--
-- Filtros para la tabla `registro_comida`
--
ALTER TABLE `registro_comida`
  ADD CONSTRAINT `registro_comida_ibfk_1` FOREIGN KEY (`Id_registro`) REFERENCES `registro` (`Id_registro`),
  ADD CONSTRAINT `registro_comida_ibfk_2` FOREIGN KEY (`Id_comida`) REFERENCES `comida` (`Id_comida`),
  ADD CONSTRAINT `registro_comida_ibfk_3` FOREIGN KEY (`Id_momento`) REFERENCES `momento` (`Id_momento`);

--
-- Filtros para la tabla `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `token_ibfk_1` FOREIGN KEY (`Id_usuario`) REFERENCES `usuario` (`Id_usuario`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

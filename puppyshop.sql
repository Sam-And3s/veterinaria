-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 05-05-2025 a las 17:45:32
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `puppyshop`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_product`
--

DROP TABLE IF EXISTS `categorias_product`;
CREATE TABLE IF NOT EXISTS `categorias_product` (
  `id_cat` int NOT NULL AUTO_INCREMENT,
  `nom_cat` varchar(100) NOT NULL,
  PRIMARY KEY (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categorias_product`
--

INSERT INTO `categorias_product` (`id_cat`, `nom_cat`) VALUES
(1, 'Alimentos'),
(2, 'Salud'),
(3, 'Juguetes'),
(4, 'Accesorios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

DROP TABLE IF EXISTS `citas`;
CREATE TABLE IF NOT EXISTS `citas` (
  `id_citas` int NOT NULL AUTO_INCREMENT,
  `id_usu` int NOT NULL,
  `id_horario` int NOT NULL,
  `id_tip_serv` int NOT NULL,
  `descripcion` text NOT NULL,
  `id_tamaño` int NOT NULL,
  `id_estado` int NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id_citas`),
  KEY `id_estado` (`id_estado`),
  KEY `id_horario` (`id_horario`),
  KEY `id_tamaño` (`id_tamaño`),
  KEY `id_tip_serv` (`id_tip_serv`),
  KEY `id_usu` (`id_usu`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id_citas`, `id_usu`, `id_horario`, `id_tip_serv`, `descripcion`, `id_tamaño`, `id_estado`, `fecha`) VALUES
(28, 91485, 22, 1, 'fvybuhijmolk', 2, 3, '2025-04-01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

DROP TABLE IF EXISTS `detalle_pedido`;
CREATE TABLE IF NOT EXISTS `detalle_pedido` (
  `id_detalle_pedido` int NOT NULL AUTO_INCREMENT,
  `id_pedido` int NOT NULL,
  `id_product` int NOT NULL,
  `cantidad` int NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_detalle_pedido`),
  KEY `id_pedido` (`id_pedido`),
  KEY `id_product` (`id_product`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id_detalle_pedido`, `id_pedido`, `id_product`, `cantidad`, `subtotal`) VALUES
(1, 1, 43, 1, 22312.00),
(2, 1, 34, 1, 231.00),
(3, 2, 39, 1, 3.00),
(4, 2, 43, 1, 22312.00),
(5, 2, 34, 1, 231.00),
(6, 2, 37, 1, 23.00),
(7, 2, 35, 1, 44.00),
(8, 3, 39, 1, 3.00),
(9, 3, 43, 1, 22312.00),
(10, 3, 34, 1, 231.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dispo_dia`
--

DROP TABLE IF EXISTS `dispo_dia`;
CREATE TABLE IF NOT EXISTS `dispo_dia` (
  `id_dia` int NOT NULL AUTO_INCREMENT,
  `fech_dia` date NOT NULL,
  `cup_tot` int NOT NULL,
  `sin_servicio` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_dia`),
  UNIQUE KEY `fech_dia` (`fech_dia`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `dispo_dia`
--

INSERT INTO `dispo_dia` (`id_dia`, `fech_dia`, `cup_tot`, `sin_servicio`) VALUES
(20, '2025-04-01', 23, 0),
(21, '2025-04-02', 20, 0),
(22, '2025-04-03', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo_vet`
--

DROP TABLE IF EXISTS `equipo_vet`;
CREATE TABLE IF NOT EXISTS `equipo_vet` (
  `num_doc` int NOT NULL,
  `id_tipo_doc` int NOT NULL,
  `nom_eqp` varchar(100) NOT NULL,
  `apell_eqp` varchar(100) NOT NULL,
  `correo_eqp` varchar(100) NOT NULL,
  `telefono_eqp` varchar(20) DEFAULT NULL,
  `prop_eqp` varchar(100) DEFAULT NULL,
  `desc_eqp` text,
  `foto_eqp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`num_doc`),
  UNIQUE KEY `correo_eqp` (`correo_eqp`),
  KEY `fk_equipo_tipo_doc` (`id_tipo_doc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `equipo_vet`
--

INSERT INTO `equipo_vet` (`num_doc`, `id_tipo_doc`, `nom_eqp`, `apell_eqp`, `correo_eqp`, `telefono_eqp`, `prop_eqp`, `desc_eqp`, `foto_eqp`) VALUES
(1106632131, 1, 'Lui231321', '2432141', 'lol21313so@gmail.com', '23', 'c22epillo', 'sadasda', 'assets/team/team_68098bc143dda.jpeg'),
(1106633642, 2, 'Luis fernando', 'galvis', 'lolso@gmail.com', '213123', 'cepillo2312', 'sdad', 'assets/team/team_6806ef9e84f8e.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

DROP TABLE IF EXISTS `estado`;
CREATE TABLE IF NOT EXISTS `estado` (
  `id_estado` int NOT NULL AUTO_INCREMENT,
  `nom_estado` varchar(50) NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `nom_estado`) VALUES
(1, 'activo'),
(2, 'inactivo'),
(3, 'confirmado'),
(4, 'cancelado'),
(5, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

DROP TABLE IF EXISTS `horario`;
CREATE TABLE IF NOT EXISTS `horario` (
  `id_dispo` int NOT NULL AUTO_INCREMENT,
  `id_dia` int NOT NULL,
  `id_horario` int NOT NULL,
  `disponible` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_dispo`),
  KEY `id_dia` (`id_dia`),
  KEY `id_horario` (`id_horario`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `horario`
--

INSERT INTO `horario` (`id_dispo`, `id_dia`, `id_horario`, `disponible`) VALUES
(1, 20, 20, 0),
(2, 20, 21, 0),
(3, 20, 22, 0),
(4, 20, 23, 0),
(5, 20, 24, 0),
(6, 20, 25, 0),
(7, 20, 26, 1),
(8, 20, 27, 1),
(9, 20, 28, 1),
(10, 20, 29, 1),
(11, 20, 30, 1),
(12, 20, 31, 1),
(13, 20, 20, 1),
(14, 20, 21, 1),
(15, 20, 22, 0),
(16, 20, 23, 0),
(17, 20, 24, 0),
(18, 20, 25, 0),
(19, 20, 26, 1),
(20, 20, 27, 1),
(21, 20, 28, 1),
(22, 20, 29, 1),
(23, 20, 30, 1),
(24, 20, 31, 1),
(25, 22, 20, 1),
(26, 22, 21, 1),
(27, 22, 22, 0),
(28, 22, 23, 1),
(29, 22, 24, 0),
(30, 22, 25, 0),
(31, 22, 26, 1),
(32, 22, 27, 1),
(33, 22, 28, 1),
(34, 22, 29, 1),
(35, 22, 30, 1),
(36, 22, 31, 1),
(37, 21, 20, 1),
(38, 21, 21, 1),
(39, 21, 22, 1),
(40, 21, 23, 1),
(41, 21, 24, 0),
(42, 21, 25, 1),
(43, 21, 26, 1),
(44, 21, 27, 1),
(45, 21, 28, 1),
(46, 21, 29, 1),
(47, 21, 30, 1),
(48, 21, 31, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario_dispo`
--

DROP TABLE IF EXISTS `horario_dispo`;
CREATE TABLE IF NOT EXISTS `horario_dispo` (
  `id_horario` int NOT NULL AUTO_INCREMENT,
  `hora_ini` time NOT NULL,
  `hora_fin` time NOT NULL,
  `cup_dispo` int NOT NULL,
  PRIMARY KEY (`id_horario`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `horario_dispo`
--

INSERT INTO `horario_dispo` (`id_horario`, `hora_ini`, `hora_fin`, `cup_dispo`) VALUES
(20, '08:00:00', '09:00:00', 2),
(21, '09:00:00', '10:00:00', 10),
(22, '10:00:00', '11:00:00', 10),
(23, '11:00:00', '12:00:00', 10),
(24, '12:00:00', '13:00:00', 10),
(25, '13:00:00', '14:00:00', 10),
(26, '14:00:00', '15:00:00', 10),
(27, '15:00:00', '16:00:00', 10),
(28, '16:00:00', '17:00:00', 10),
(29, '17:00:00', '18:00:00', 10),
(30, '18:00:00', '19:00:00', 10),
(31, '19:00:00', '20:00:00', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id_pedido` int NOT NULL AUTO_INCREMENT,
  `documento_usu` int NOT NULL,
  `fecha_pedido` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_estado` int NOT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `documento_usu` (`documento_usu`),
  KEY `id_estado` (`id_estado`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `documento_usu`, `fecha_pedido`, `id_estado`) VALUES
(1, 91485, '2025-04-23 19:43:25', 3),
(2, 91485, '2025-04-23 19:50:08', 5),
(3, 91485, '2025-04-23 19:55:39', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id_product` int NOT NULL AUTO_INCREMENT,
  `nom_product` varchar(100) NOT NULL,
  `img_product` varchar(255) DEFAULT NULL,
  `id_cat` int NOT NULL,
  `prec_product` decimal(10,2) NOT NULL,
  `stock_product` int NOT NULL,
  PRIMARY KEY (`id_product`),
  KEY `id_cat` (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_product`, `nom_product`, `img_product`, `id_cat`, `prec_product`, `stock_product`) VALUES
(34, '31311', 'assets/products/product_68070feeeeca0.png', 2, 231.00, 24214),
(35, 'sdas', 'assets/products/product_68070ff8d6dd1.jfif', 3, 44.00, 44),
(36, 'hfg', 'assets/products/product_68071004205fb.png', 4, 2.00, 4),
(37, 'sdas', 'assets/products/product_6807100f85e38.png', 3, 23.00, 4),
(38, 'sdasf', 'assets/products/product_6807101d97f84.png', 3, 24.00, 4),
(39, 'sda', 'assets/products/product_680710266f0c7.png', 1, 3.00, 3),
(40, '523', 'assets/products/product_6807102f8c7a6.jpeg', 2, 23.00, 4),
(41, 'fdsbv', 'assets/products/product_68071037e6477.png', 4, 22.00, 4),
(42, 'hola', 'assets/products/product_6807cd410baaa.jpeg', 3, 222.00, 22),
(43, 'holasdampodmasoi', 'assets/products/product_6807cd522ab1c.jpeg', 1, 22312.00, 23123);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nom_rol` varchar(50) NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nom_rol`) VALUES
(1, 'Administrador'),
(2, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

DROP TABLE IF EXISTS `servicios`;
CREATE TABLE IF NOT EXISTS `servicios` (
  `id_serv` int NOT NULL AUTO_INCREMENT,
  `nom_serv` varchar(100) NOT NULL,
  `precio_serv` decimal(10,2) NOT NULL,
  `descrip_serv` text NOT NULL,
  `id_tip_serv` int NOT NULL,
  PRIMARY KEY (`id_serv`),
  KEY `id_tip_serv` (`id_tip_serv`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_serv`, `nom_serv`, `precio_serv`, `descrip_serv`, `id_tip_serv`) VALUES
(23, '23hola202', 12312.00, 'hola servicios', 1),
(24, '12312fsd2', 312312.00, 'sdadsa', 3),
(25, 'sfafas', 222.00, 'fsafs', 3),
(26, 'holac2121', 30000.00, 'Sin descripción', 2),
(27, 'spa222', 2312.00, 'sadas', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tamaño`
--

DROP TABLE IF EXISTS `tamaño`;
CREATE TABLE IF NOT EXISTS `tamaño` (
  `id_tamaño` int NOT NULL AUTO_INCREMENT,
  `nom_tam` varchar(50) NOT NULL,
  `prec_tam` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_tamaño`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tamaño`
--

INSERT INTO `tamaño` (`id_tamaño`, `nom_tam`, `prec_tam`) VALUES
(1, 'pequeño', 0.00),
(2, 'mediano', 3000.00),
(3, 'grande', 5000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_servicios`
--

DROP TABLE IF EXISTS `tipos_servicios`;
CREATE TABLE IF NOT EXISTS `tipos_servicios` (
  `id_tip_serv` int NOT NULL AUTO_INCREMENT,
  `nom_tip_serv` varchar(100) NOT NULL,
  PRIMARY KEY (`id_tip_serv`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tipos_servicios`
--

INSERT INTO `tipos_servicios` (`id_tip_serv`, `nom_tip_serv`) VALUES
(1, 'spa'),
(2, 'peluqueria'),
(3, 'consulta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_doc_eqp`
--

DROP TABLE IF EXISTS `tipo_doc_eqp`;
CREATE TABLE IF NOT EXISTS `tipo_doc_eqp` (
  `id_tipo_doc` int NOT NULL AUTO_INCREMENT,
  `nom_tipo_doc` varchar(50) NOT NULL,
  `sigla_tipo_doc` varchar(5) NOT NULL,
  PRIMARY KEY (`id_tipo_doc`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tipo_doc_eqp`
--

INSERT INTO `tipo_doc_eqp` (`id_tipo_doc`, `nom_tipo_doc`, `sigla_tipo_doc`) VALUES
(1, 'Cédula de Ciudadanía', 'CC'),
(2, 'Cédula de Extranjería', 'CE'),
(3, 'Tarjeta de Identidad', 'TI'),
(4, 'Pasaporte', 'PP');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `documento_usu` int NOT NULL,
  `tipo_doc_usu` varchar(10) NOT NULL,
  `nom_usu` varchar(100) NOT NULL,
  `apell_usu` varchar(100) NOT NULL,
  `num_usu` varchar(20) NOT NULL,
  `direc_usu` varchar(255) DEFAULT NULL,
  `correo_usu` varchar(100) NOT NULL,
  `contra_usu` varchar(255) NOT NULL,
  `id_rol` int NOT NULL,
  `id_estado` int NOT NULL,
  PRIMARY KEY (`documento_usu`),
  UNIQUE KEY `correo_usu` (`correo_usu`),
  KEY `id_estado` (`id_estado`),
  KEY `id_rol` (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`documento_usu`, `tipo_doc_usu`, `nom_usu`, `apell_usu`, `num_usu`, `direc_usu`, `correo_usu`, `contra_usu`, `id_rol`, `id_estado`) VALUES
(91485, 'CC', 'jose', 'sjaosaj', '21312312', 'ososo', 'luis123@gmail.com', '$2y$10$A.XHAxklNB3BGj4b2xIk9eiPCzANY7RY1kGNQUUplbtr73Vlo7Ita', 2, 1),
(12312312, 'CE', 'dsanjomk', 'sdah kj', '3243', 'rdg', 'sdwas@gmail.com', '$2y$10$rDY7kgBvtZCRbi3af8xQy.OOIDndlJVsOhZhwz8E2M5vRKvFUI9yC', 1, 1);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`id_horario`) REFERENCES `horario_dispo` (`id_horario`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`id_tamaño`) REFERENCES `tamaño` (`id_tamaño`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `citas_ibfk_4` FOREIGN KEY (`id_tip_serv`) REFERENCES `tipos_servicios` (`id_tip_serv`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `citas_ibfk_5` FOREIGN KEY (`id_usu`) REFERENCES `usuario` (`documento_usu`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `equipo_vet`
--
ALTER TABLE `equipo_vet`
  ADD CONSTRAINT `fk_equipo_tipo_doc` FOREIGN KEY (`id_tipo_doc`) REFERENCES `tipo_doc_eqp` (`id_tipo_doc`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_cat`) REFERENCES `categorias_product` (`id_cat`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`id_tip_serv`) REFERENCES `tipos_servicios` (`id_tip_serv`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

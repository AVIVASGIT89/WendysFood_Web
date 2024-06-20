-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para wendysfood
CREATE DATABASE IF NOT EXISTS `wendysfood` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `wendysfood`;

-- Volcando estructura para tabla wendysfood.forma_pago
CREATE TABLE IF NOT EXISTS `forma_pago` (
  `ID_FORMA_PAGO` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE_FORMA_PAGO` varchar(50) NOT NULL DEFAULT '',
  `ESTADO_REGISTRO` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ID_FORMA_PAGO`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla wendysfood.forma_pago: ~3 rows (aproximadamente)
DELETE FROM `forma_pago`;
INSERT INTO `forma_pago` (`ID_FORMA_PAGO`, `NOMBRE_FORMA_PAGO`, `ESTADO_REGISTRO`) VALUES
	(1, 'Efectivo', 1),
	(2, 'Yape', 1),
	(3, 'Plin', 1);

-- Volcando estructura para evento wendysfood.JOB_ACT_FECHA_VENTA
DELIMITER //
CREATE EVENT `JOB_ACT_FECHA_VENTA` ON SCHEDULE EVERY 1 DAY STARTS '2024-06-19 00:03:00' ON COMPLETION PRESERVE ENABLE DO UPDATE venta V
	SET V.FECHA_VENTA = DATE(CURDATE() - 1)
	WHERE V.ID_VENTA IN(SELECT V.ID_VENTA
							  FROM venta V
							  WHERE DATE_FORMAT(V.FECHA_VENTA,'%Y-%m-%d') = CURDATE()
							 )//
DELIMITER ;

-- Volcando estructura para tabla wendysfood.mesa
CREATE TABLE IF NOT EXISTS `mesa` (
  `ID_MESA` int(11) NOT NULL AUTO_INCREMENT,
  `NRO_MESA` varchar(50) NOT NULL DEFAULT '',
  `TOTAL_CONSUMO` decimal(20,2) NOT NULL DEFAULT 0.00,
  `ESTADO_MESA` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Disponible, 2 = Ocupado',
  `ID_VENTA_OCUPACION` int(11) DEFAULT NULL,
  `ULTIMA_OCUPACION` datetime DEFAULT NULL,
  `USUARIO_ULT_OCUPACION` text DEFAULT NULL,
  `VISIBLE` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Visible, 2 = No visible',
  `ESTADO_REGISTRO` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Activo, 0 = Inactivo',
  PRIMARY KEY (`ID_MESA`),
  UNIQUE KEY `NRO_MESA` (`NRO_MESA`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla wendysfood.mesa: ~9 rows (aproximadamente)
DELETE FROM `mesa`;
INSERT INTO `mesa` (`ID_MESA`, `NRO_MESA`, `TOTAL_CONSUMO`, `ESTADO_MESA`, `ID_VENTA_OCUPACION`, `ULTIMA_OCUPACION`, `USUARIO_ULT_OCUPACION`, `VISIBLE`, `ESTADO_REGISTRO`) VALUES
	(1, 'Delivery', 0.00, 1, NULL, NULL, NULL, 0, 1),
	(2, '1', 0.00, 1, NULL, '2024-06-20 14:59:09', NULL, 1, 1),
	(3, '2', 0.00, 1, NULL, '2024-06-20 14:57:29', NULL, 1, 1),
	(4, '3', 0.00, 1, NULL, '2024-06-20 14:58:41', NULL, 1, 1),
	(5, '4', 0.00, 1, NULL, '2024-06-20 14:58:50', NULL, 1, 1),
	(6, '5', 0.00, 1, NULL, '2024-03-02 00:34:30', NULL, 1, 1),
	(7, '6', 0.00, 1, NULL, '2024-06-20 14:57:56', NULL, 1, 1),
	(8, '7', 0.00, 1, NULL, '2024-06-20 14:57:43', NULL, 1, 1),
	(9, '8', 0.00, 1, NULL, NULL, NULL, 1, 1);

-- Volcando estructura para función wendysfood.NOMPROPIO
DELIMITER //
CREATE FUNCTION `NOMPROPIO`(x CHAR(250)) RETURNS char(250) CHARSET utf8 COLLATE utf8_general_ci
BEGIN

DECLARE STR VARCHAR(250);
DECLARE L_STR VARCHAR(250);

SET STR='';
SET L_STR='';

WHILE x REGEXP ' ' DO
SELECT SUBSTRING_INDEX(x, ' ', 1) INTO L_STR;
SELECT SUBSTRING(x, LOCATE(' ', x)+1) INTO x;
SELECT CONCAT(STR, ' ', CONCAT(UPPER(SUBSTRING(L_STR,1,1)),LOWER(SUBSTRING(L_STR,2)))) INTO STR;
END WHILE;
RETURN LTRIM(CONCAT(STR, ' ', CONCAT(UPPER(SUBSTRING(x,1,1)),LOWER(SUBSTRING(x,2)))));
END//
DELIMITER ;

-- Volcando estructura para tabla wendysfood.pago
CREATE TABLE IF NOT EXISTS `pago` (
  `ID_PAGO` int(11) NOT NULL AUTO_INCREMENT,
  `ID_TIPO_PAGO` int(11) NOT NULL,
  `FECHA_PAGO` date NOT NULL,
  `CONCEPTO_PAGO` varchar(400) NOT NULL DEFAULT '',
  `MONTO_PAGO` decimal(20,2) NOT NULL,
  `USUARIO_REGISTRO` varchar(50) NOT NULL DEFAULT '',
  `FECHA_REGISTRO` datetime NOT NULL,
  `ESTADO_REGISTRO` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Activo, 0 = Inactivo',
  PRIMARY KEY (`ID_PAGO`),
  KEY `FK_pago_pago_tipo` (`ID_TIPO_PAGO`),
  CONSTRAINT `FK_pago_pago_tipo` FOREIGN KEY (`ID_TIPO_PAGO`) REFERENCES `pago_tipo` (`ID_TIPO_PAGO`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla wendysfood.pago: ~12 rows (aproximadamente)
DELETE FROM `pago`;
INSERT INTO `pago` (`ID_PAGO`, `ID_TIPO_PAGO`, `FECHA_PAGO`, `CONCEPTO_PAGO`, `MONTO_PAGO`, `USUARIO_REGISTRO`, `FECHA_REGISTRO`, `ESTADO_REGISTRO`) VALUES
	(1, 2, '2024-03-01', 'chela', 60.00, 'ADMIN', '2024-03-02 08:20:50', 0),
	(2, 1, '2024-03-02', 'pollo y gallina', 80.00, 'ADMIN', '2024-03-02 08:21:09', 1),
	(3, 1, '2024-03-01', 'gaseosas', 90.00, 'ADMIN', '2024-03-02 08:21:34', 1),
	(4, 1, '2024-02-14', 'limpieza de cocina', 60.00, 'ADMIN', '2024-03-02 08:23:57', 1),
	(5, 3, '2024-02-27', 'limpieza de cocina', 60.00, 'ADMIN', '2024-03-02 08:24:13', 1),
	(6, 1, '2024-03-01', 'limpieza de cocina', 80.00, 'ADMIN', '2024-03-02 08:24:57', 1),
	(7, 1, '2024-02-25', 'pollo y gallina', 90.00, 'ADMIN', '2024-03-02 08:25:10', 1),
	(8, 3, '2024-02-29', 'limpieza de cocina', 6.00, 'ADMIN', '2024-03-02 08:26:52', 1),
	(9, 2, '2024-02-29', 'chela', 90.00, 'ADMIN', '2024-03-02 08:27:27', 1),
	(10, 2, '2024-02-28', 'cocinero', 63.00, 'ADMIN', '2024-03-02 08:28:23', 1),
	(11, 2, '2024-02-16', 'cocinero', 59.00, 'ADMIN', '2024-03-02 08:28:48', 1),
	(12, 1, '2024-03-02', 'limpieza de cocina', 90.00, 'ADMIN', '2024-03-02 09:29:29', 1);

-- Volcando estructura para tabla wendysfood.pago_tipo
CREATE TABLE IF NOT EXISTS `pago_tipo` (
  `ID_TIPO_PAGO` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE_TIPO_PAGO` varchar(50) NOT NULL DEFAULT '',
  `ESTADO_REGISTRO` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ID_TIPO_PAGO`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla wendysfood.pago_tipo: ~3 rows (aproximadamente)
DELETE FROM `pago_tipo`;
INSERT INTO `pago_tipo` (`ID_TIPO_PAGO`, `NOMBRE_TIPO_PAGO`, `ESTADO_REGISTRO`) VALUES
	(1, 'Proveedores', 1),
	(2, 'Personal', 1),
	(3, 'Otros', 1);

-- Volcando estructura para tabla wendysfood.producto
CREATE TABLE IF NOT EXISTS `producto` (
  `ID_PRODUCTO` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO_PRODUCTO` varchar(50) NOT NULL,
  `NOMBRE_PRODUCTO` varchar(200) NOT NULL DEFAULT '',
  `TIPO_COCINA` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Cocina, 2 = Carrito',
  `PRECIO_UNITARIO` decimal(20,2) NOT NULL DEFAULT 0.00,
  `TIPO_PRECIO` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Automatico, 2 = Manual',
  `USUARIO_REGISTRO` varchar(50) DEFAULT NULL,
  `FECHA_REGISTRO` datetime DEFAULT NULL,
  `ESTADO_REGISTRO` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Activo, 0 = Inactivo',
  PRIMARY KEY (`ID_PRODUCTO`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla wendysfood.producto: ~50 rows (aproximadamente)
DELETE FROM `producto`;
INSERT INTO `producto` (`ID_PRODUCTO`, `CODIGO_PRODUCTO`, `NOMBRE_PRODUCTO`, `TIPO_COCINA`, `PRECIO_UNITARIO`, `TIPO_PRECIO`, `USUARIO_REGISTRO`, `FECHA_REGISTRO`, `ESTADO_REGISTRO`) VALUES
	(1, '1010', 'Producto General', 1, 0.00, 2, 'ADMIN', '2024-02-25 15:48:18', 1),
	(2, '1011', 'Caldo Solo', 1, 7.00, 1, 'ADMIN', '2024-02-25 15:48:46', 1),
	(3, '1012', 'Caldo Presa Mediana', 1, 10.00, 1, 'ADMIN', '2024-02-25 15:49:34', 1),
	(4, '1013', 'Caldo Presa Grande', 1, 12.00, 1, 'ADMIN', '2024-02-25 15:49:58', 1),
	(5, '2011', 'Alita Broaster', 2, 7.00, 1, 'ADMIN', '2024-02-25 15:50:38', 1),
	(6, '2012', 'Pierna Broaster', 2, 9.00, 1, 'ADMIN', '2024-02-25 15:50:58', 1),
	(7, '2013', 'Entrepierna Broaster', 2, 9.00, 1, 'ADMIN', '2024-02-25 15:51:43', 1),
	(8, '2014', 'Pecho Broaster', 2, 10.00, 1, 'ADMIN', '2024-02-25 15:52:02', 1),
	(9, '2015', 'Mostrito Alita', 2, 9.00, 1, 'ADMIN', '2024-02-25 15:52:29', 1),
	(10, '2016', 'Mostrito Pierna', 2, 11.00, 1, 'ADMIN', '2024-02-25 15:53:33', 1),
	(11, '2017', 'Mostrito Entrepierna', 2, 11.00, 1, 'ADMIN', '2024-02-25 15:54:14', 1),
	(12, '2018', 'Mostrito Pecho', 2, 12.00, 1, 'ADMIN', '2024-02-25 15:54:35', 1),
	(13, '2019', 'Salchipapa', 2, 7.00, 1, 'ADMIN', '2024-02-25 15:54:59', 1),
	(14, '4011', 'Jugo Natural Papaya', 1, 7.00, 1, 'ADMIN', '2024-02-25 15:57:14', 1),
	(15, '4012', 'Jugo Natural Fresa', 1, 7.00, 1, 'ADMIN', '2024-02-25 15:57:34', 1),
	(16, '4013', 'Jugo Natural Lucuma', 1, 7.00, 1, 'ADMIN', '2024-02-25 15:57:53', 1),
	(17, '4014', 'Jugo Natural Piña', 1, 7.00, 1, 'ADMIN', '2024-02-25 15:58:25', 1),
	(18, '4015', 'Jugo Natural Mango', 1, 7.00, 1, 'ADMIN', '2024-02-25 15:58:45', 1),
	(19, '4016', 'Jugo Natural Surtido', 1, 7.00, 1, 'ADMIN', '2024-02-25 15:59:21', 1),
	(20, '4017', 'Jugo Con Leche Fresa', 1, 9.00, 1, 'ADMIN', '2024-02-25 15:59:39', 1),
	(21, '4018', 'Jugo Con Leche Lucuma', 1, 9.00, 1, 'ADMIN', '2024-02-25 16:00:00', 1),
	(22, '4019', 'Jugo Con Leche Platano', 1, 9.00, 1, 'ADMIN', '2024-02-25 16:00:43', 1),
	(23, '4020', 'Jugo Con Leche Mango', 1, 9.00, 1, 'ADMIN', '2024-02-25 16:01:17', 1),
	(24, '4021', 'Jugo Especial', 1, 9.00, 1, 'ADMIN', '2024-02-25 16:01:46', 1),
	(25, '3011', 'Arroz Chaufa', 1, 11.00, 1, 'ADMIN', '2024-02-25 19:37:03', 1),
	(26, '3012', 'Pollo Saltado', 1, 13.00, 1, 'ADMIN', '2024-02-25 19:37:33', 1),
	(27, '3013', 'Pollo A La Plancha', 1, 13.00, 1, 'ADMIN', '2024-02-25 19:38:11', 1),
	(28, '3014', 'Aeropuerto', 1, 12.00, 1, 'ADMIN', '2024-02-25 19:38:33', 1),
	(29, '3015', 'Tallarin Saltado', 1, 13.00, 1, 'ADMIN', '2024-02-25 19:39:06', 1),
	(30, '3016', 'Tallarin Chifa', 1, 12.00, 1, 'ADMIN', '2024-02-25 19:39:31', 1),
	(31, '3017', 'Pollo Con Verduras', 1, 13.00, 1, 'ADMIN', '2024-02-25 19:39:54', 1),
	(32, '3018', 'Arroz Salvaje', 1, 12.00, 1, 'ADMIN', '2024-02-25 19:40:28', 1),
	(33, '3019', 'Combinado', 1, 13.00, 1, 'ADMIN', '2024-02-25 19:40:50', 1),
	(34, '3020', 'Saltado De Pollo Con Chaufa', 1, 15.00, 1, 'ADMIN', '2024-02-25 19:41:24', 1),
	(35, '2021', 'Chicharron De Pollo', 1, 15.00, 1, 'ADMIN', '2024-02-25 19:41:54', 1),
	(36, '3022', 'Lomo Saltado (carne)', 1, 17.00, 1, 'ADMIN', '2024-02-25 19:42:26', 1),
	(37, '3023', 'Arroz Chaufa De Carne', 1, 16.00, 1, 'ADMIN', '2024-02-25 19:43:01', 1),
	(38, '3024', 'Sopa Wantan', 1, 10.00, 1, 'ADMIN', '2024-02-25 19:43:32', 1),
	(39, '3025', 'Wantan Frito (10 Unidades)', 1, 8.00, 1, 'ADMIN', '2024-02-25 19:43:59', 1),
	(40, '3026', 'Gallina Acevichada', 1, 11.00, 1, 'ADMIN', '2024-02-25 19:44:23', 1),
	(41, '5011', 'Hamburguesa Clasica Pollo', 2, 4.00, 1, 'ADMIN', '2024-02-25 19:44:49', 1),
	(42, '5012', 'Hamburguesa Clasica Carne', 2, 4.00, 1, 'ADMIN', '2024-02-25 19:45:06', 1),
	(43, '5013', 'Pan Con Palta', 1, 3.00, 1, 'ADMIN', '2024-02-25 19:45:40', 1),
	(44, '5014', 'Pan Con Huevo Frito', 1, 4.00, 1, 'ADMIN', '2024-02-25 19:46:00', 1),
	(45, '7011', 'Ensalada Fruta Chico', 1, 7.00, 1, 'ADMIN', '2024-02-25 19:46:38', 1),
	(46, '7012', 'Ensalada Fruta Grande', 1, 12.00, 1, 'ADMIN', '2024-02-25 19:47:08', 1),
	(47, '6011', 'Chicha Morada 1/2 Jarra', 1, 4.00, 1, 'ADMIN', '2024-02-25 19:47:53', 1),
	(48, '6012', 'Chicha Morada 1 Jarra', 1, 7.00, 1, 'ADMIN', '2024-02-25 19:48:25', 1),
	(49, '6013', 'Cafe', 1, 2.00, 1, 'ADMIN', '2024-02-25 19:49:00', 1),
	(50, '6014', 'Infusion', 1, 2.00, 1, 'ADMIN', '2024-02-25 19:49:23', 1);

-- Volcando estructura para tabla wendysfood.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `ID_USUARIO` int(11) NOT NULL AUTO_INCREMENT,
  `USUARIO` varchar(50) NOT NULL DEFAULT '0',
  `CLAVE` varchar(100) NOT NULL DEFAULT '',
  `NOMBRE_USUARIO` varchar(200) DEFAULT '',
  `APELLIDO_USUARIO` varchar(200) DEFAULT '',
  `ROL_USUARIO` int(11) NOT NULL,
  `FECHA_REGISTRO` datetime DEFAULT NULL,
  `ESTADO_REGISTRO` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Activo, 0 = Inactivo',
  PRIMARY KEY (`ID_USUARIO`),
  UNIQUE KEY `USUARIO` (`USUARIO`),
  KEY `FK_usuario_rol_usuario` (`ROL_USUARIO`),
  CONSTRAINT `FK_usuario_rol_usuario` FOREIGN KEY (`ROL_USUARIO`) REFERENCES `usuario_rol` (`ID_ROL`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla wendysfood.usuario: ~6 rows (aproximadamente)
DELETE FROM `usuario`;
INSERT INTO `usuario` (`ID_USUARIO`, `USUARIO`, `CLAVE`, `NOMBRE_USUARIO`, `APELLIDO_USUARIO`, `ROL_USUARIO`, `FECHA_REGISTRO`, `ESTADO_REGISTRO`) VALUES
	(1, 'ADMIN', 'admin24', 'Administrador', '', 1, '2024-03-08 15:04:26', 1),
	(2, 'WVIVAS', 'wvivas01', 'Wendy', 'Vivas', 1, '2024-03-08 15:04:27', 1),
	(3, 'LVIVAS', 'lvivas2', 'Lizbeth', 'Vivas', 2, '2024-03-08 15:04:28', 1),
	(4, 'VENDEDOR1', 'vendedor24', 'Vendedor 1', '', 2, '2024-03-08 15:04:30', 0),
	(5, 'VENTAS2', 'ventas2', 'Vendedor 2', '', 2, '2024-03-08 15:30:37', 1),
	(6, 'VENTAS3', 'ventas3', 'Vendedor 3', '', 2, '2024-03-08 15:31:15', 1);

-- Volcando estructura para tabla wendysfood.usuario_rol
CREATE TABLE IF NOT EXISTS `usuario_rol` (
  `ID_ROL` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE_ROL` varchar(50) NOT NULL,
  `ESTADO_REGISTRO` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Activo, 0 = Inactivo',
  PRIMARY KEY (`ID_ROL`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla wendysfood.usuario_rol: ~2 rows (aproximadamente)
DELETE FROM `usuario_rol`;
INSERT INTO `usuario_rol` (`ID_ROL`, `NOMBRE_ROL`, `ESTADO_REGISTRO`) VALUES
	(1, 'Administrador', 1),
	(2, 'Vendedor', 1);

-- Volcando estructura para tabla wendysfood.venta
CREATE TABLE IF NOT EXISTS `venta` (
  `ID_VENTA` int(11) NOT NULL AUTO_INCREMENT,
  `TIPO_VENTA` int(11) NOT NULL COMMENT '1 = Mesa, 2 = Delivery',
  `ID_USUARIO_VENTA` int(11) NOT NULL,
  `ID_MESA` int(11) NOT NULL,
  `NRO_PRODUCTOS` int(11) DEFAULT NULL,
  `ID_FORMA_PAGO` int(11) DEFAULT NULL,
  `USUARIO_VENTA` varchar(50) NOT NULL DEFAULT '',
  `FECHA_VENTA` datetime NOT NULL,
  `BASE_VENTA` decimal(20,2) NOT NULL DEFAULT 0.00,
  `IGV_VENTA` decimal(20,2) NOT NULL DEFAULT 0.00,
  `TOTAL_VENTA` decimal(20,2) NOT NULL,
  `ESTADO_VENTA` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Registrado, 2 = Finalizado, 3 = Cancelado',
  `FECHA_CANCELACION` datetime DEFAULT NULL,
  `USUARIO_CANCELACION` varchar(50) DEFAULT NULL,
  `CLIENTE_DELIVERY` varchar(100) DEFAULT NULL,
  `ESTADO_REGISTRO` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Activo, 0 = Desactivo',
  PRIMARY KEY (`ID_VENTA`),
  KEY `FK_venta_mesa` (`ID_MESA`),
  KEY `FK_venta_usuario` (`ID_USUARIO_VENTA`),
  CONSTRAINT `FK_venta_mesa` FOREIGN KEY (`ID_MESA`) REFERENCES `mesa` (`ID_MESA`),
  CONSTRAINT `FK_venta_usuario` FOREIGN KEY (`ID_USUARIO_VENTA`) REFERENCES `usuario` (`ID_USUARIO`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla wendysfood.venta: ~28 rows (aproximadamente)
DELETE FROM `venta`;
INSERT INTO `venta` (`ID_VENTA`, `TIPO_VENTA`, `ID_USUARIO_VENTA`, `ID_MESA`, `NRO_PRODUCTOS`, `ID_FORMA_PAGO`, `USUARIO_VENTA`, `FECHA_VENTA`, `BASE_VENTA`, `IGV_VENTA`, `TOTAL_VENTA`, `ESTADO_VENTA`, `FECHA_CANCELACION`, `USUARIO_CANCELACION`, `CLIENTE_DELIVERY`, `ESTADO_REGISTRO`) VALUES
	(1, 1, 1, 3, 3, 3, 'ADMIN', '2024-02-25 19:54:05', 20.34, 3.66, 24.00, 2, NULL, NULL, NULL, 1),
	(2, 1, 1, 2, 2, 1, 'ADMIN', '2024-02-28 23:13:00', 14.41, 2.59, 17.00, 2, NULL, NULL, NULL, 1),
	(3, 2, 1, 1, 3, 3, 'ADMIN', '2024-03-01 23:15:30', 24.58, 4.42, 29.00, 2, NULL, NULL, NULL, 1),
	(4, 1, 1, 5, 2, 2, 'ADMIN', '2024-03-01 23:16:42', 15.25, 2.75, 18.00, 2, NULL, NULL, NULL, 1),
	(5, 2, 1, 1, 1, 3, 'ADMIN', '2024-02-25 23:17:46', 5.93, 1.07, 7.00, 2, NULL, NULL, NULL, 1),
	(6, 2, 1, 1, 1, 1, 'ADMIN', '2024-03-01 23:17:52', 5.93, 1.07, 7.00, 2, NULL, NULL, NULL, 1),
	(7, 2, 1, 1, 3, 1, 'ADMIN', '2024-03-01 23:17:59', 24.58, 4.42, 29.00, 2, NULL, NULL, NULL, 1),
	(8, 2, 1, 1, 2, NULL, 'ADMIN', '2024-03-01 23:18:07', 15.25, 2.75, 18.00, 3, '2024-03-08 13:54:52', 'ADMIN', NULL, 1),
	(9, 1, 1, 3, 2, 3, 'ADMIN', '2024-03-02 00:33:01', 18.64, 3.36, 22.00, 2, NULL, NULL, NULL, 1),
	(10, 1, 1, 6, 2, 1, 'ADMIN', '2024-03-02 00:34:30', 18.64, 3.36, 22.00, 2, NULL, NULL, NULL, 1),
	(11, 2, 1, 1, 2, 3, 'ADMIN', '2024-03-02 00:37:14', 11.86, 2.14, 14.00, 2, NULL, NULL, NULL, 1),
	(12, 2, 1, 1, 2, NULL, 'ADMIN', '2024-03-02 00:38:09', 15.25, 2.75, 18.00, 3, '2024-03-02 00:41:42', 'ADMIN', NULL, 1),
	(13, 1, 1, 3, 2, 3, 'ADMIN', '2024-05-15 12:28:19', 14.41, 2.59, 17.00, 2, NULL, NULL, NULL, 1),
	(14, 2, 1, 1, 1, 1, 'ADMIN', '2024-05-15 16:24:12', 8.47, 1.53, 10.00, 2, NULL, NULL, NULL, 1),
	(15, 2, 1, 1, 2, 2, 'ADMIN', '2024-05-15 16:42:05', 18.64, 3.36, 22.00, 2, NULL, NULL, NULL, 1),
	(16, 2, 1, 1, 2, 1, 'ADMIN', '2024-05-15 16:44:28', 14.41, 2.59, 17.00, 2, NULL, NULL, NULL, 1),
	(17, 2, 1, 1, 1, 2, 'ADMIN', '2024-05-15 16:48:05', 8.47, 1.53, 10.00, 2, NULL, NULL, NULL, 1),
	(18, 1, 1, 3, 2, 2, 'ADMIN', '2024-05-15 19:01:11', 18.64, 3.36, 22.00, 2, NULL, NULL, NULL, 1),
	(19, 2, 1, 1, 2, 2, 'ADMIN', '2024-05-15 19:01:23', 16.10, 2.90, 19.00, 2, NULL, NULL, NULL, 1),
	(20, 2, 1, 1, 2, 1, 'ADMIN', '2024-05-16 09:34:00', 18.64, 3.36, 22.00, 2, NULL, NULL, 'Alex', 1),
	(21, 1, 1, 3, 2, 2, 'ADMIN', '2024-05-16 10:06:21', 14.41, 2.59, 17.00, 2, NULL, NULL, '', 1),
	(22, 1, 1, 3, 2, 1, 'ADMIN', '2024-06-19 00:00:00', 14.41, 2.59, 17.00, 2, NULL, NULL, '', 1),
	(23, 1, 1, 5, 2, 3, 'ADMIN', '2024-06-19 00:00:00', 14.41, 2.59, 17.00, 2, NULL, NULL, '', 1),
	(24, 1, 1, 8, 2, 2, 'ADMIN', '2024-06-19 00:00:00', 14.41, 2.59, 17.00, 2, NULL, NULL, '', 1),
	(25, 1, 1, 7, 3, 1, 'ADMIN', '2024-06-19 00:00:00', 23.73, 4.27, 28.00, 2, NULL, NULL, '', 1),
	(26, 1, 1, 4, 4, 1, 'ADMIN', '2024-06-19 00:00:00', 30.51, 5.49, 36.00, 2, NULL, NULL, '', 1),
	(27, 1, 1, 5, 3, 1, 'ADMIN', '2024-06-19 00:00:00', 24.58, 4.42, 29.00, 2, NULL, NULL, '', 1),
	(28, 1, 1, 2, 1, 2, 'ADMIN', '2024-06-19 00:00:00', 5.93, 1.07, 7.00, 2, NULL, NULL, '', 1);

-- Volcando estructura para tabla wendysfood.venta_detalle
CREATE TABLE IF NOT EXISTS `venta_detalle` (
  `ID_DETALLE_VENTA` int(11) NOT NULL AUTO_INCREMENT,
  `ID_VENTA` int(11) NOT NULL,
  `ID_PRODUCTO` int(11) NOT NULL,
  `PRECIO_UNITARIO` decimal(20,2) NOT NULL DEFAULT 0.00,
  `CANTIDAD` int(11) NOT NULL,
  `SUBTOTAL_BASE` decimal(20,2) NOT NULL,
  `SUBTOTAL_IGV` decimal(20,2) NOT NULL,
  `SUBTOTAL` decimal(20,2) NOT NULL,
  `ESTADO_REGISTRO` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Activo, 0 = Desactivo',
  PRIMARY KEY (`ID_DETALLE_VENTA`),
  KEY `FK_venta_detalle_venta` (`ID_VENTA`),
  KEY `FK_venta_detalle_producto` (`ID_PRODUCTO`),
  CONSTRAINT `FK_venta_detalle_producto` FOREIGN KEY (`ID_PRODUCTO`) REFERENCES `producto` (`ID_PRODUCTO`),
  CONSTRAINT `FK_venta_detalle_venta` FOREIGN KEY (`ID_VENTA`) REFERENCES `venta` (`ID_VENTA`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla wendysfood.venta_detalle: ~58 rows (aproximadamente)
DELETE FROM `venta_detalle`;
INSERT INTO `venta_detalle` (`ID_DETALLE_VENTA`, `ID_VENTA`, `ID_PRODUCTO`, `PRECIO_UNITARIO`, `CANTIDAD`, `SUBTOTAL_BASE`, `SUBTOTAL_IGV`, `SUBTOTAL`, `ESTADO_REGISTRO`) VALUES
	(1, 1, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(2, 1, 2, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(3, 1, 5, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(4, 2, 2, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(5, 2, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(6, 3, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(7, 3, 4, 12.00, 1, 10.17, 1.83, 12.00, 1),
	(8, 3, 5, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(9, 4, 5, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(10, 4, 25, 11.00, 1, 9.32, 1.68, 11.00, 1),
	(11, 5, 5, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(12, 6, 5, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(13, 7, 2, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(14, 7, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(15, 7, 4, 12.00, 1, 10.17, 1.83, 12.00, 1),
	(16, 8, 25, 11.00, 1, 9.32, 1.68, 11.00, 1),
	(17, 8, 14, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(18, 9, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(19, 9, 4, 12.00, 1, 10.17, 1.83, 12.00, 1),
	(20, 10, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(21, 10, 4, 12.00, 1, 10.17, 1.83, 12.00, 1),
	(22, 11, 14, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(23, 11, 5, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(24, 12, 25, 11.00, 1, 9.32, 1.68, 11.00, 1),
	(25, 12, 2, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(26, 13, 5, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(27, 13, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(28, 14, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(29, 15, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(30, 15, 4, 12.00, 1, 10.17, 1.83, 12.00, 1),
	(31, 16, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(32, 16, 2, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(33, 17, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(34, 18, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(35, 18, 4, 12.00, 1, 10.17, 1.83, 12.00, 1),
	(36, 19, 4, 12.00, 1, 10.17, 1.83, 12.00, 1),
	(37, 19, 2, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(38, 20, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(39, 20, 4, 12.00, 1, 10.17, 1.83, 12.00, 1),
	(40, 21, 5, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(41, 21, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(42, 22, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(43, 22, 2, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(44, 23, 5, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(45, 23, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(46, 24, 5, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(47, 24, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(48, 25, 25, 11.00, 1, 9.32, 1.68, 11.00, 1),
	(49, 25, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(50, 25, 5, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(51, 26, 5, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(52, 26, 2, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(53, 26, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(54, 26, 4, 12.00, 1, 10.17, 1.83, 12.00, 1),
	(55, 27, 5, 7.00, 1, 5.93, 1.07, 7.00, 1),
	(56, 27, 3, 10.00, 1, 8.47, 1.53, 10.00, 1),
	(57, 27, 4, 12.00, 1, 10.17, 1.83, 12.00, 1),
	(58, 28, 5, 7.00, 1, 5.93, 1.07, 7.00, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

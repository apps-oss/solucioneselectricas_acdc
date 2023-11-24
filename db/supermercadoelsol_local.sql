-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 25-07-2023 a las 15:36:09
-- Versión del servidor: 10.3.38-MariaDB-0ubuntu0.20.04.1
-- Versión de PHP: 7.4.3-4ubuntu2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `supermercadoelsol`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `abono_credito`
--

CREATE TABLE `abono_credito` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_abono_credito` int(11) NOT NULL,
  `id_credito` int(11) NOT NULL,
  `abono` float NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `tipo_doc` varchar(25) NOT NULL,
  `num_doc` varchar(60) NOT NULL,
  `id_apertura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `abono_historial`
--

CREATE TABLE `abono_historial` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_abono_historial` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `abono` float NOT NULL,
  `saldo_ante` decimal(12,2) NOT NULL,
  `saldo_ultimo` decimal(12,2) NOT NULL,
  `arr_abono_creditos` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'array guardar id_abono_credito, tabla abono_credito; o\r\ntabla venta_cuotas X abono',
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_apertura` int(11) NOT NULL,
  `cuotas` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'tipo credito general=0, por cuotas=1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `access_conf`
--

CREATE TABLE `access_conf` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_conf` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `hash` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `access_conf`
--

INSERT INTO `access_conf` (`id_server`, `unique_id`, `id_conf`, `id_sucursal`, `hash`) VALUES
(1, 'O5f05eb3ed058b1.65548931', 1, 1, 'OIQWAjLkd79ggETQkaSF3UW5x4wPRQkmfHqJsHLVY7aKPbs642CaWcON3+3OMUrKnjc6j6Qd0PvrNLwP11cGioWaHob/OFyq5fx2lBwzT8UK4iLKjzbr/6lpk9KNOKxM8TRtYmJjpaVFUKGU4EOYaW9CeFXbJNZdvrNJpIhxRFg=');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `altclitocli`
--

CREATE TABLE `altclitocli` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id` int(11) NOT NULL,
  `datax` text NOT NULL,
  `id_sucursal_origen` int(11) NOT NULL,
  `id_sucursal_destino` int(11) NOT NULL,
  `ejecutado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `altclitocli`
--

INSERT INTO `altclitocli` (`id_server`, `unique_id`, `id`, `datax`, `id_sucursal_origen`, `id_sucursal_destino`, `ejecutado`) VALUES
(0, 'S62a20ea72643a1.44150541', 1, 'eyJjaXBoZXJ0ZXh0IjoiRDJTVG9lQ2hKT21DbEdUWVwvVkVhMURZanp5bkJlQ2NRM0NRZEVlekJURVUySGY4XC9MTlNWMm5wNnBVYURpaVZIY0VXZkM1UG9nRnJrM1dnenNrMmNZTW5QV0RPNUJXMitkOURwYTZLQlhJRmRtRHJNKzVGMHpiYVNSMWhGZFMxSjlSTVwveGFIalJNUGRJVzJjTTNyQUNMc0I1N21kK1dRUkhjSFpjYjczT3BUakFBMVVxblVZYVpDZklacEVXQWxrekdVUGgwd09oVjNCclhlck45V25TaDRUdGpyUkZoeWNVZmR1c0xnR1RoQjJZd1dacEhLNDAzamFXaXkzMHl3Y1Z0aHJoRkdlNG1BcU9YenlCZ2tHMWc9PSIsIml2IjoiZjc0NmEyMzgzZGI0YmY3YjY4Njc1MGNiMGQ5NjdlMmUiLCJzYWx0IjoiOGNhYzVhOWQ0OWRkYTY0MjBiNmFiN2Q0ZWVjMWIyMzVkZWIzMzlkY2E4ODM0NjI5MzhjNDFjZjBhY2VhMWU2ZDQyOTk1MTQ3NDhhY2YyN2UxYTcyYjhkZjUyMDdlNTg4ZGU0ZWUyNzIwZDM0MjFhYmVmMjkxZTc2ODE1OTMyZjhjZDFiOGQ1MGFkNWJkNzdmNDEyN2FjZDU3MDNlNmNkZDM2NTQyNWQxOGY2MTE2YmI0Zjk1NzUzM2U2MjdmYmRjOTcwZWYxZjc1M2M0MjZmZGVjNjU0NGQ2Y2FjYTRiNjE2YjhjNTJmNDlhMjE5MTk4OWUyNDA5NDk2OTkwYzk4NWUwMzk2OTM4MGRmMDhlZWFhMzdiNTgwM2JkYWEzZDhhOWYzOWE5YTE3NDMyNWVhMTRlMTk4ZmNhNDIxNGQzOTllMTQyYmIzZjg4Mjg4NGQ2NmVmOWVlZmY5Mzc3NDgwNWMwMDcwYWRmYzYzZTk3MjRhYTU4YzJhYTM5ZjJiYzI2MGM1ZTBkZTQwYzY0YzQyOWY1NTQ4MWExYWMwZTE1YWZhYTk5ZDU5ZDQyYjU2YmNiZmI3N2ZjYmU2YjZmZGI3YmEyZTg0ZDQ4OGU0YWQyMGIxMDMyMDY1YTI0NjAxZDJjNzk0YmNkZWFiNmQ5ZGJhODllZjhkYTU5ZjNhYzZjNGEiLCJpdGVyYXRpb25zIjo5OTl9', 2, 1, 0),
(0, 'S62a20f31d35a02.99338378', 2, 'eyJjaXBoZXJ0ZXh0IjoiNWNKeHFaM2s2WlFZMk9nWFhwVjE5Z25EVE5hWXpwMHgzZ2xLVU5pRG44Nk5CMHN1TGtpY2ZJa3AzZlFxN3F3YUVNaWt0SnlnTTdQVGxFKzd4dTQ5b0dabGRqd3JwUnM2alRZT0xVUEFQWjdWNW9KdFFlcldJQ1wva1VTNTdTT002eXlZbzZ4UXpXd1FKbTVlOTR6VnRmOTIxOXdpK2VIbDVWazVpaUk1cEhHcXNlVnpcL1wvZ2M5eWhSTDZTNTYwQ2IxUjN3VTFFZ3hsSGllU1VYVDdiWUpmXC9UVTZOOEVINHdWYjgrMGJqcnFaYzQ9IiwiaXYiOiIwYjA1MzhhYzMxNDIyNThkNTY1MGJmODkyOTFmMjRkMSIsInNhbHQiOiJhZjFiYjRlOGI0N2QwODA3MDI1ZmQ5MjY5NTVjZjNmNWNiZDQxMjRkMDBhOTAxNmRkYzA1MjgwMjkzMTY2MWZiOWRlYWIyMDA0YjQ2ZjhiODc5NWI3ZjJhOGY1YWZmNTc5MGNhMTQ4NWQ0Nzg3MGQ5ODE5YmViOGFmZDgwZjFmNzQ0MzhiNjE0NDFlMDc0OTU4ZmRhZjQ2ZDkwYmExMjBiZWYwYzU1ZDFjYzZmZjZjOWY2NjIzYzA2ZDc0NDEwODVmNTkyNGU3Yjk5ZTcyYWZhNTgxNjU0ZjI1OTJlNjgxOTZhYTFiYzMyMmZiY2NhYWY4YWI3MmE4YjE3MmUzODJiMzg0OGUyYzA3MWFlZWRiNWJiNmI5ZWEyYWEzYmM5OGI2YjlkYjRhZDhhODAxMjk0ZGFiMjY4MWU4MDhjNmM3NmEzNmRjODk1NWJhMDI4NTM2Zjg1ZDQ0YmU3ZGQ3Nzg0MjY4MzJmMTYwNDNkZWMwYzk1MDQ1NTk4YTdkYzYxODgxMGVhYTUwYmQzM2Y3YmViNDdiNTdlOGE4ZWUwZTFiN2ZkZDYzZTI2NmY1NzYzYTFlOTBkNjQ1YzU3NTllNmQxNzE2ZjFjYTRiMTJmNjBmZTBlMzMwMzAyMmJmNGVhNWI5ODc4Mjg1NDY1NjlhY2NhYjhhYmE1YWMzN2ZlNDQwNyIsIml0ZXJhdGlvbnMiOjk5OX0=', 2, 1, 0),
(0, 'S64a9e539c90f92.93927816', 3, 'eyJjaXBoZXJ0ZXh0IjoiUCtIWks0a3N0bVhLYlFuWFpSTGVBTzJXMFNUVDJZdFNibVwvK2hpejByWGNkZkw1Y2piZTAzeUJjMWNLUXpCNyt0U1BYN1lzMWE4ZlAzNXlpWmQ2Uzh4NmhsWjNKc2FTYjdjcVZMTE5OcndcL2dwTlVaTEZlbjc3aGpteW1JYkNLNWJCcjVCYndJTklkZmRFVVhkK3N1c1N2QWxrN1pqWUo5YnhvZDNlazc5ZWpBYTY3TjQ3N0NYR2QrV3V2QlB6XC9yQVdiWWpVR09xdnVZYUMrcWNDWDRXc1lrQUlUdHdzQ2VVaEgwbnNcL2txeDQ9IiwiaXYiOiJjNjYxZDY1OWEyNjM5ZTNhMjY2MWVmNmRiYjA1ODA3MCIsInNhbHQiOiJjODQ3YzllYzJhMWQ0ZWMxNTc0MzA0ODAzZGU4ZmVhMjc5MGNmZjU1MmFkYTU2YTVkZjkxN2VkMzU4MDMyOGQ3NzY4ZTFmMTI5NjI4YWM2OTBlNTZkZmRlYTQ4NDBlMTQwNjZjOWJkY2I2YTg1NWU0NjI2OTQ5MWYzYjViYWNmY2YzMTQ3MGQ4ZGRjMjVkMzI5MjQyY2FlNjlmZDE0NWI5MjZlZTQxNTFjYTMyY2M5ZDkzYWJjZjQ4YzkxYzA2YjAxZmY0ZTUxYmRjMDYwYzZhOThmMDNmMDgxN2FlZWY0Mjk2MjhkM2JjNmY1YjBmNDRkYWQ1MWUzODdkMmViZDFkNzIxNjhjYjViNTI5ZjQ5ZGM5ZTI5NzgyZDdkZGIyMjM2YzE2MjM0YzM0NzIyNmFiN2VlZWE1NWZlMmFiNzljZjAxMTRkOTBmMWNhNTVmYWY3NTQzZTI2YTlmM2NlZThiZjkxM2Q4ODAwZmVjZWZiNGM4Y2EwZjYwZmZjOGY0ODZkZmRjZmMyZmU3ODBlNjk1ODFjNDFmYWRkY2QzYTkyNzBhNzExNjQwZWE3MDVlOGI4ZTIwNWM4ZmU5OTA0YzlhMTcxYTRlYTRiOGUxYTJjODdkNjhjZTQ1NzlkNGFkNTM1MDJiNDc2MmE2YmQxOTVkMDk0YmJkZDE0ZGYyODdlOSIsIml0ZXJhdGlvbnMiOjk5OX0=', 2, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apertura_caja`
--

CREATE TABLE `apertura_caja` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_apertura` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `caja` int(11) NOT NULL,
  `turno_vigente` tinyint(1) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `turno` int(11) NOT NULL,
  `monto_apertura` double NOT NULL,
  `monto_ch` decimal(10,2) NOT NULL,
  `monto_ch_actual` decimal(10,2) NOT NULL,
  `tiket_inicia` int(11) NOT NULL,
  `factura_inicia` int(11) NOT NULL,
  `credito_fiscal_inicia` int(11) NOT NULL,
  `dev_inicia` int(11) NOT NULL,
  `vigente` tinyint(1) NOT NULL,
  `monto_vendido` double NOT NULL,
  `galones_inicio` decimal(10,2) NOT NULL,
  `cortado` tinyint(1) NOT NULL COMMENT '0 = NO CORTADO POR DEFECTO; 1= SI SE HIZO CORTE DE ESTA APERTURA ',
  `id_corte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `apertura_caja`
--

INSERT INTO `apertura_caja` (`id_server`, `unique_id`, `id_sucursal`, `id_apertura`, `fecha`, `hora`, `caja`, `turno_vigente`, `id_empleado`, `turno`, `monto_apertura`, `monto_ch`, `monto_ch_actual`, `tiket_inicia`, `factura_inicia`, `credito_fiscal_inicia`, `dev_inicia`, `vigente`, `monto_vendido`, `galones_inicio`, `cortado`, `id_corte`) VALUES
(0, 'S64a84ea41c9193.54376980', 1, 1, '2023-07-07', '11:43:00', 2, 0, 1, 2, 50, '0.00', '0.00', 0, 0, 0, 0, 0, 134.75, '0.00', 0, 0),
(0, 'S64a9a13acf61c2.76568167', 1, 2, '2023-07-08', '11:47:38', 2, 0, 1, 2, 10, '0.00', '0.00', 0, 0, 0, 0, 0, 44, '0.00', 0, 0),
(0, 'S64a9e437a560c5.31171558', 2, 3, '2023-07-08', '16:33:27', 6, 0, 7, 2, 20, '0.00', '0.00', 0, 0, 0, 0, 0, 74, '0.00', 0, 0),
(0, 'S64ac130a9894b6.91179262', 1, 4, '2023-07-10', '08:17:46', 2, 0, 1, 2, 50, '0.00', '0.00', 0, 0, 0, 0, 0, 100, '0.00', 0, 0),
(0, 'S64ac2fe2ecea60.23267873', 2, 5, '2023-07-10', '10:20:50', 6, 1, 7, 1, 50, '0.00', '0.00', 0, 0, 0, 0, 1, 0, '0.00', 0, 0),
(0, 'S64b5cccd76ef92.70875037', 1, 6, '2023-07-17', '17:20:45', 2, 0, 2, 2, 25, '0.00', '0.00', 0, 0, 0, 0, 0, 45, '0.00', 0, 0),
(0, 'S64b5ce1b147ee4.60214365', 1, 7, '2023-07-17', '17:26:19', 3, 0, 3, 2, 50, '0.00', '0.00', 0, 0, 0, 0, 0, 70, '0.00', 0, 0),
(0, 'S64b85d86be30d5.11874925', 1, 8, '2023-07-19', '16:02:46', 2, 1, 2, 2, 10, '0.00', '0.00', 0, 0, 0, 0, 0, 12, '0.00', 1, 7),
(0, 'S64b860b21548a4.64288544', 1, 9, '2023-07-19', '16:16:18', 3, 0, 3, 2, 20, '0.00', '0.00', 0, 0, 0, 0, 0, 24, '0.00', 0, 0),
(0, 'S64b966d39b0a70.95112822', 1, 10, '2023-07-20', '10:54:43', 3, 0, 3, 2, 12, '0.00', '0.00', 0, 0, 0, 0, 0, 34.52, '0.00', 0, 0),
(0, 'S64c0092a834a28.75251248', 1, 11, '2023-07-25', '11:40:58', 2, 1, 2, 1, 10, '0.00', '0.00', 0, 0, 0, 0, 1, 0, '0.00', 0, 0),
(0, 'S64c02dbc1bdff4.11459770', 1, 12, '2023-07-25', '14:17:00', 4, 1, 4, 1, 20, '0.00', '0.00', 0, 0, 0, 0, 1, 0, '0.00', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arqueo_conceptos`
--

CREATE TABLE `arqueo_conceptos` (
  `id` smallint(4) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `alias_tipopago` varchar(4) NOT NULL,
  `multiplicador` smallint(4) NOT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `arqueo_conceptos`
--

INSERT INTO `arqueo_conceptos` (`id`, `descripcion`, `alias_tipopago`, `multiplicador`, `activo`) VALUES
(1, 'BILLETES DE 100', 'CON', 100, 1),
(2, 'BILLETES DE 50', 'CON', 50, 1),
(3, 'BILLETES DE 20', 'CON', 20, 1),
(4, 'BILLETES DE 10', 'CON', 10, 1),
(5, 'BILLETES DE 5', 'CON', 5, 1),
(6, 'BILLETES DE 1', 'CON', 1, 1),
(7, 'MONEDAS', 'CON', 1, 1),
(8, 'REMESAS', 'CON', 1, 1),
(9, 'CHEQUES', 'CHE', 1, 1),
(10, 'TARJETAS', 'TAR', 1, 1),
(11, 'VALES', 'VAL', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arqueo_corte`
--

CREATE TABLE `arqueo_corte` (
  `id_arqueo` smallint(4) NOT NULL,
  `id_apertura` int(11) NOT NULL,
  `id_concepto` int(11) NOT NULL,
  `alias_tipopago` varchar(4) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `total` decimal(12,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `arqueo_corte`
--

INSERT INTO `arqueo_corte` (`id_arqueo`, `id_apertura`, `id_concepto`, `alias_tipopago`, `cantidad`, `subtotal`, `total`) VALUES
(1, 8, 1, '', '0.00', '0.00', '12.0000'),
(2, 8, 2, '', '0.00', '0.00', '12.0000'),
(3, 8, 3, '', '0.00', '0.00', '12.0000'),
(4, 8, 4, '', '1.00', '10.00', '12.0000'),
(5, 8, 5, '', '0.00', '0.00', '12.0000'),
(6, 8, 6, '', '0.00', '0.00', '12.0000'),
(7, 8, 7, '', '0.00', '0.00', '12.0000'),
(8, 8, 8, '', '0.00', '0.00', '12.0000'),
(9, 8, 9, '', '0.00', '0.00', '12.0000'),
(10, 8, 10, '', '2.00', '2.00', '12.0000'),
(11, 8, 11, '', '0.00', '0.00', '12.0000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banco`
--

CREATE TABLE `banco` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_banco` int(1) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `logo` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bomba`
--

CREATE TABLE `bomba` (
  `id` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `numero` smallint(6) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `diesel` tinyint(1) NOT NULL,
  `regular` tinyint(1) NOT NULL,
  `super` tinyint(1) NOT NULL,
  `activa` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bomba_manguera`
--

CREATE TABLE `bomba_manguera` (
  `id` int(11) NOT NULL,
  `id_bomba` int(11) NOT NULL,
  `id_manguera` int(11) NOT NULL,
  `combustible` smallint(1) NOT NULL COMMENT ' 	1-REGULAR 2-SUPER 3-DIESEL 	'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_caja` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `serie` varchar(100) NOT NULL,
  `desde` int(11) NOT NULL,
  `hasta` int(11) NOT NULL,
  `correlativo_dispo` int(11) NOT NULL,
  `resolucion` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `activa` tinyint(1) NOT NULL,
  `tipo_caja` tinyint(1) NOT NULL COMMENT '1=TIENDA, 2=PISTA, 3=TRANSPORTE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`id_server`, `unique_id`, `id_sucursal`, `id_caja`, `nombre`, `serie`, `desde`, `hasta`, `correlativo_dispo`, `resolucion`, `fecha`, `activa`, `tipo_caja`) VALUES
(1, 'O5f05eb3edefc49.58616938', 1, 2, 'CAJA #1 ', '22SD00000002', 1, 1000000, 6, 'ASC-15041-046104-2022', '2022-09-16', 1, 1),
(1, 'O5f05eb3edefc49.58616938', 1, 3, 'CAJA #2', '22SD00000002', 1, 1000000, 7, 'ASC-15041-046104-2022', '2022-09-16', 1, 1),
(1, 'O5f05eb3edefc49.58616938', 1, 4, 'CAJA #3', '22SD00000002', 1, 1000000, 3, 'ASC-15041-046104-2022', '2022-09-16', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `nombre_cat` varchar(30) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `tienda` tinyint(1) NOT NULL,
  `pista` tinyint(1) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `combustible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_server`, `unique_id`, `id_categoria`, `nombre_cat`, `descripcion`, `tienda`, `pista`, `id_sucursal`, `combustible`) VALUES
(0, 'S64a84d22f3da89.83272619', 1, 'HELADOS', 'HELADOS', 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_proveedor`
--

CREATE TABLE `categoria_proveedor` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria_proveedor`
--

INSERT INTO `categoria_proveedor` (`id_server`, `unique_id`, `id_categoria`, `nombre`, `descripcion`) VALUES
(1, 'O5f05eb3fcf9d50.73076700', 1, 'Consumidor', ''),
(2, 'O5f05eb3fd0ffa1.90155333', 2, 'Contribuyente', ''),
(3, 'O5f05eb3fd30de2.22710207', 3, 'Gran Contribuyente', ''),
(4, 'O5f05eb3fd507d0.02180707', 4, 'Contribuyente Exento', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_doc_MH`
--

CREATE TABLE `cat_doc_MH` (
  `ID` int(11) NOT NULL,
  `codigo` varchar(4) NOT NULL,
  `descripcion` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cat_doc_MH`
--

INSERT INTO `cat_doc_MH` (`ID`, `codigo`, `descripcion`) VALUES
(1, '02', 'CARNET DE RESIDENTE'),
(2, '03', 'PASAPORTE'),
(3, '13', 'DUI'),
(4, '36', 'NIT'),
(5, '37', 'OTRO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cheque`
--

CREATE TABLE `cheque` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_cheque` int(11) NOT NULL,
  `cheque` varchar(50) DEFAULT NULL,
  `monto` float NOT NULL,
  `id_movimiento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `categoria` int(1) DEFAULT NULL,
  `nombre` varchar(200) DEFAULT NULL,
  `negocio` varchar(200) NOT NULL,
  `razon_social` varchar(250) NOT NULL,
  `direccion` text DEFAULT NULL,
  `municipio` smallint(4) DEFAULT NULL,
  `depto` smallint(2) DEFAULT NULL,
  `pais` varchar(11) DEFAULT NULL,
  `dui` varchar(15) DEFAULT NULL,
  `nit` varchar(20) DEFAULT NULL,
  `nrc` varchar(12) DEFAULT NULL,
  `giro` varchar(150) DEFAULT NULL,
  `telefono1` varchar(12) DEFAULT NULL,
  `telefono2` varchar(12) DEFAULT NULL,
  `codcliente` varchar(12) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ultventa` date DEFAULT NULL,
  `acumulado` int(1) DEFAULT NULL,
  `saldo` int(1) DEFAULT NULL,
  `percibe` int(1) DEFAULT 0,
  `retiene` tinyint(1) DEFAULT 0,
  `retiene10` tinyint(1) NOT NULL,
  `inactivo` tinyint(1) NOT NULL,
  `latitud` double NOT NULL,
  `longitud` double NOT NULL,
  `fecha_nac` date DEFAULT NULL,
  `id_vendedor` int(11) NOT NULL,
  `dias_credito` smallint(4) NOT NULL,
  `limite_credito` decimal(10,4) NOT NULL,
  `consumo_interno` tinyint(1) NOT NULL,
  `cod_act_eco` varchar(20) DEFAULT NULL,
  `nombre_comercial` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_server`, `unique_id`, `id_sucursal`, `id_cliente`, `categoria`, `nombre`, `negocio`, `razon_social`, `direccion`, `municipio`, `depto`, `pais`, `dui`, `nit`, `nrc`, `giro`, `telefono1`, `telefono2`, `codcliente`, `email`, `ultventa`, `acumulado`, `saldo`, `percibe`, `retiene`, `retiene10`, `inactivo`, `latitud`, `longitud`, `fecha_nac`, `id_vendedor`, `dias_credito`, `limite_credito`, `consumo_interno`, `cod_act_eco`, `nombre_comercial`) VALUES
(0, 'S64a84f5e69df01.76160780', 1, 1, 2, 'FREDY ERNESTO TURCIOS REYES', 'NUNGUNO', '', 'SAN MIGUEL', 17, 12, NULL, '05203761-3', '', '325750-2', '', '', '', '', 'turcios095@gmail.com', NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, '1995-07-17', 0, 90, '10000.0000', 0, '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_dif`
--

CREATE TABLE `cliente_dif` (
  `id_dif` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `numero_dif` varchar(20) NOT NULL,
  `embarcacion` varchar(250) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `limite_galon` double(10,4) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL COMMENT 'eliminado = 1',
  `unique_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `numero_doc` varchar(20) NOT NULL,
  `fecha` date DEFAULT NULL,
  `iva` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `hora` time DEFAULT NULL,
  `fecha_ingreso` date NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `alias_tipodoc` char(5) NOT NULL,
  `total_percepcion` float NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `dias_credito` int(11) NOT NULL,
  `anulada` tinyint(1) NOT NULL,
  `finalizada` tinyint(1) NOT NULL,
  `imp_comb` varchar(250) NOT NULL COMMENT 'impuestos compra de combustibles, fovial, cotrans, etc 	'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id_server`, `unique_id`, `id_sucursal`, `id_compra`, `id_proveedor`, `numero_doc`, `fecha`, `iva`, `total`, `hora`, `fecha_ingreso`, `id_empleado`, `alias_tipodoc`, `total_percepcion`, `id_pedido`, `dias_credito`, `anulada`, `finalizada`, `imp_comb`) VALUES
(0, 'S64bfdcb51cd2f7.45790012', 1, 1, 1, '123', '2023-07-25', '0.00', '10.00', '08:31:17', '2023-07-25', 1, 'COF', 0, 0, 0, 0, 1, ''),
(0, 'S64bfe1dee5e0a0.16573023', 1, 2, 1, '123', '2023-07-25', '0.00', '10.00', '08:53:18', '2023-07-25', 1, 'COF', 0, 0, 0, 0, 1, ''),
(0, 'S64bfe1fb4088b4.98086277', 1, 3, 1, '123', '2023-07-25', '0.00', '10.00', '08:53:47', '2023-07-25', 1, 'COF', 0, 0, 0, 0, 1, ''),
(0, 'S64bfe298679055.60237919', 1, 4, 1, '123', '2023-07-25', '0.00', '10.00', '08:56:24', '2023-07-25', 1, 'COF', 0, 0, 0, 0, 1, ''),
(0, 'S64bfe527612905.65575515', 1, 5, 1, '23', '2023-07-25', '0.00', '10.00', '09:07:19', '2023-07-25', 1, 'COF', 0, 0, 0, 0, 1, ''),
(0, 'S64bfe7e2571c13.10212716', 1, 6, 1, '234', '2023-07-25', '0.00', '15.00', '09:18:58', '2023-07-25', 1, 'COF', 0, 0, 0, 0, 1, ''),
(0, 'S64bfe830d95935.78756642', 1, 7, 1, '1213', '2023-07-25', '0.00', '20.00', '09:20:16', '2023-07-25', 1, 'COF', 0, 0, 0, 0, 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra2`
--

CREATE TABLE `compra2` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `numero_doc` varchar(20) NOT NULL,
  `fecha` date DEFAULT NULL,
  `iva` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `hora` time DEFAULT NULL,
  `fecha_ingreso` date NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `alias_tipodoc` char(5) NOT NULL,
  `total_percepcion` float NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `dias_credito` int(11) NOT NULL,
  `anulada` tinyint(1) NOT NULL,
  `finalizada` tinyint(1) NOT NULL,
  `id_ubicacion` int(11) NOT NULL,
  `imp_comb` varchar(250) NOT NULL COMMENT 'impuestos compra de combustibles, fovial, cotrans, etc'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `compra2`
--

INSERT INTO `compra2` (`id_server`, `unique_id`, `id_sucursal`, `id_compra`, `id_proveedor`, `numero_doc`, `fecha`, `iva`, `total`, `hora`, `fecha_ingreso`, `id_empleado`, `alias_tipodoc`, `total_percepcion`, `id_pedido`, `dias_credito`, `anulada`, `finalizada`, `id_ubicacion`, `imp_comb`) VALUES
(0, 'S64bfe9af0eaa87.80460466', 1, 1, 1, '655', '2023-07-25', '0.00', '1.00', '09:26:39', '2023-07-25', 1, 'COF', 0, 0, 0, 0, 1, 0, ''),
(0, 'S64bfeb7c4f9c18.05661937', 1, 2, 1, '1', '2023-07-25', '0.00', '10.00', '09:34:20', '2023-07-25', 1, 'COF', 0, 0, 0, 0, 1, 0, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_imp_combust`
--

CREATE TABLE `compra_imp_combust` (
  `id` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_impuesto` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `imp_nombre` varchar(30) NOT NULL,
  `total_imp` decimal(10,4) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_dif` int(11) NOT NULL,
  `galones_dif` decimal(10,4) NOT NULL,
  `anulada` tinyint(1) NOT NULL,
  `aplica_dif` tinyint(1) NOT NULL,
  `aplica_impuesto` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_configuracion` int(11) NOT NULL,
  `sms` int(11) NOT NULL,
  `user` varchar(100) NOT NULL,
  `hash` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config_dir`
--

CREATE TABLE `config_dir` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_config_dir` int(11) NOT NULL,
  `dir_print_script` varchar(50) NOT NULL,
  `shared_printer_matrix` varchar(50) NOT NULL,
  `shared_printer_pos` varchar(50) NOT NULL,
  `shared_print_barcode` varchar(250) NOT NULL,
  `rollo_etiqueta` int(11) NOT NULL,
  `media_type` char(2) NOT NULL DEFAULT 'DT' COMMENT ' 	DT= Térmico Directo TT= Transferencia Térmica 	',
  `leftmarginlabel` smallint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `config_dir`
--

INSERT INTO `config_dir` (`id_server`, `unique_id`, `id_sucursal`, `id_config_dir`, `dir_print_script`, `shared_printer_matrix`, `shared_printer_pos`, `shared_print_barcode`, `rollo_etiqueta`, `media_type`, `leftmarginlabel`) VALUES
(1, 'O5f05eb3fda9427.14226162', 1, 1, 'localhost/impresion_elsol/', '//localhost/facturacion', '//localhost/ticket', '//localhost/barcode', 1, 'DT', 220);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config_pos`
--

CREATE TABLE `config_pos` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_config_pos` int(11) NOT NULL,
  `alias_tipodoc` char(4) NOT NULL,
  `header1` varchar(50) NOT NULL,
  `header2` varchar(50) DEFAULT NULL,
  `header3` varchar(50) DEFAULT NULL,
  `header4` varchar(50) DEFAULT NULL,
  `header5` varchar(50) DEFAULT NULL,
  `header6` varchar(50) DEFAULT NULL,
  `header7` varchar(50) DEFAULT NULL,
  `header8` varchar(50) DEFAULT NULL,
  `header9` varchar(50) DEFAULT NULL,
  `header10` varchar(50) DEFAULT NULL,
  `footer1` varchar(50) NOT NULL,
  `footer2` varchar(50) DEFAULT NULL,
  `footer3` varchar(50) DEFAULT NULL,
  `footer4` varchar(50) DEFAULT NULL,
  `footer5` varchar(50) DEFAULT NULL,
  `footer6` varchar(50) DEFAULT NULL,
  `footer7` varchar(50) DEFAULT NULL,
  `footer8` varchar(50) DEFAULT NULL,
  `footer9` varchar(50) DEFAULT NULL,
  `footer10` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `config_pos`
--

INSERT INTO `config_pos` (`id_server`, `unique_id`, `id_sucursal`, `id_config_pos`, `alias_tipodoc`, `header1`, `header2`, `header3`, `header4`, `header5`, `header6`, `header7`, `header8`, `header9`, `header10`, `footer1`, `footer2`, `footer3`, `footer4`, `footer5`, `footer6`, `footer7`, `footer8`, `footer9`, `footer10`) VALUES
(1, 'O5f05eb3fdc5e43.90064528', 1, 1, 'TIK', '', '', '', '', '', '', '', '', '', '', 'GRACIAS POR SU COMPRA, VUELVA PRONTO...', '', '', '', '', '', '', '', '', ''),
(1, 'O5f05eb3fdc5e43.90064528', 2, 2, 'TIK', '', '', '', '', '', '', '', '', '', '', 'GRACIAS POR SU COMPRA, VUELVA PRONTO...', '', '', '', '', '', '', '', '', ''),
(1, 'O5f05eb3fdc5e43.90064528', 3, 3, 'TIK', '', '', '', '', '', '', '', '', '', '', 'GRACIAS POR SU COMPRA, VUELVA PRONTO...', '', '', '', '', '', '', '', '', ''),
(0, 'S62cf3eb0c76d37.80425879', 4, 7, '', 'prueba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GRACIAS POR SU COMPRA, VUELVA PRONTO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0, 'S63c604c6423e71.96403853', 5, 8, '', 'PuntoHogar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GRACIAS POR SU COMPRA, VUELVA PRONTO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0, 'S64a9de3f69a6d3.13940430', 2, 9, '', 'DISTRIBUIDORA IDE HELADOS CREMOSA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GRACIAS POR SU COMPRA, VUELVA PRONTO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consignacion`
--

CREATE TABLE `consignacion` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_consignacion` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `concepto` text NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `numero_doc` varchar(30) NOT NULL,
  `total` float NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `anulada` tinyint(1) NOT NULL DEFAULT 0,
  `id_sucursal` int(11) NOT NULL,
  `finalizada` tinyint(1) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `saldo` float NOT NULL,
  `abono` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consignacion_detalle`
--

CREATE TABLE `consignacion_detalle` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_consignacion_detalle` int(11) NOT NULL,
  `id_consignacion` int(11) NOT NULL,
  `id_prod_serv` int(11) NOT NULL,
  `cantidad` float NOT NULL,
  `costo` float NOT NULL,
  `precio_venta` float NOT NULL,
  `subtotal` float NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `tipo_prod_serv` varchar(30) NOT NULL COMMENT 'PRODUCTO o SERVICIO',
  `id_sucursal` int(11) NOT NULL,
  `cant_facturado` float NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `presentacion` int(11) NOT NULL,
  `unidad` int(11) NOT NULL,
  `cantidad_faltante` int(11) NOT NULL,
  `saldo` float NOT NULL,
  `abono` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consumo_mes_dif`
--

CREATE TABLE `consumo_mes_dif` (
  `id_consumo_dif` int(11) NOT NULL,
  `id_dif` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `cons_gal_mes` int(11) NOT NULL COMMENT 'consumo mes galon',
  `mes` smallint(2) NOT NULL,
  `anio` mediumint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `controlcaja`
--

CREATE TABLE `controlcaja` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_corte` int(11) NOT NULL,
  `fecha` varchar(10) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `id_apertura` int(11) NOT NULL,
  `caja` varchar(3) DEFAULT NULL,
  `turno` int(1) DEFAULT NULL,
  `cajero` varchar(10) DEFAULT NULL,
  `fecha_corte` date NOT NULL,
  `hora_corte` time NOT NULL,
  `tiket` int(11) NOT NULL,
  `ticket_e` int(11) NOT NULL,
  `tinicio` int(5) DEFAULT NULL,
  `tfinal` int(5) DEFAULT NULL,
  `totalnot` int(2) DEFAULT NULL,
  `texento` double DEFAULT NULL,
  `tgravado` double DEFAULT NULL,
  `totalt` double DEFAULT NULL,
  `finicio` int(5) DEFAULT NULL,
  `ffinal` int(5) DEFAULT NULL,
  `totalnof` int(2) DEFAULT NULL,
  `fexento` double DEFAULT NULL,
  `fgravado` double DEFAULT NULL,
  `totalf` double DEFAULT NULL,
  `cfinicio` int(4) DEFAULT NULL,
  `cffinal` int(4) DEFAULT NULL,
  `totalnocf` int(1) DEFAULT NULL,
  `cfexento` double DEFAULT NULL,
  `cfgravado` double DEFAULT NULL,
  `totalcf` double DEFAULT NULL,
  `rinicio` int(11) NOT NULL,
  `rfinal` int(11) NOT NULL,
  `totalnor` int(11) NOT NULL,
  `rexento` double NOT NULL,
  `rgravado` double NOT NULL,
  `totalr` double NOT NULL,
  `cashinicial` double DEFAULT NULL,
  `vtacontado` double DEFAULT NULL,
  `vtaefectivo` double DEFAULT NULL,
  `vtatcredito` double DEFAULT NULL,
  `totalgral` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `cashfinal` double DEFAULT NULL,
  `diferencia` double DEFAULT NULL,
  `totalnodev` int(1) DEFAULT NULL,
  `totalnoanu` int(1) DEFAULT NULL,
  `depositos` decimal(6,2) DEFAULT NULL,
  `vales` double DEFAULT NULL,
  `tarjetas` double DEFAULT NULL,
  `depositon` int(1) DEFAULT NULL,
  `valen` int(1) DEFAULT NULL,
  `tarjetan` int(1) DEFAULT NULL,
  `ingresos` double DEFAULT NULL,
  `tcredito` int(1) DEFAULT NULL,
  `ncortex` int(1) DEFAULT NULL,
  `ncortez` int(1) DEFAULT NULL,
  `ncortezm` int(1) DEFAULT NULL,
  `cerrado` int(1) DEFAULT NULL,
  `tipo_corte` varchar(20) NOT NULL,
  `monto_ch` float NOT NULL,
  `retencion` float NOT NULL,
  `tinicio_e` int(11) NOT NULL,
  `tfinal_e` int(11) NOT NULL,
  `tdoctexe` int(11) NOT NULL,
  `tottexe` decimal(10,2) NOT NULL,
  `finicio_e` int(11) NOT NULL,
  `ffinal_e` int(11) NOT NULL,
  `tdocfexe` int(11) NOT NULL,
  `totfexe` decimal(10,2) NOT NULL,
  `cfinicio_e` int(11) NOT NULL,
  `cffinal_e` int(11) NOT NULL,
  `tdoccfexe` int(11) NOT NULL,
  `totcfexe` decimal(10,2) NOT NULL,
  `czxe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `controlcaja`
--

INSERT INTO `controlcaja` (`id_server`, `unique_id`, `id_sucursal`, `id_corte`, `fecha`, `id_empleado`, `id_apertura`, `caja`, `turno`, `cajero`, `fecha_corte`, `hora_corte`, `tiket`, `ticket_e`, `tinicio`, `tfinal`, `totalnot`, `texento`, `tgravado`, `totalt`, `finicio`, `ffinal`, `totalnof`, `fexento`, `fgravado`, `totalf`, `cfinicio`, `cffinal`, `totalnocf`, `cfexento`, `cfgravado`, `totalcf`, `rinicio`, `rfinal`, `totalnor`, `rexento`, `rgravado`, `totalr`, `cashinicial`, `vtacontado`, `vtaefectivo`, `vtatcredito`, `totalgral`, `subtotal`, `cashfinal`, `diferencia`, `totalnodev`, `totalnoanu`, `depositos`, `vales`, `tarjetas`, `depositon`, `valen`, `tarjetan`, `ingresos`, `tcredito`, `ncortex`, `ncortez`, `ncortezm`, `cerrado`, `tipo_corte`, `monto_ch`, `retencion`, `tinicio_e`, `tfinal_e`, `tdoctexe`, `tottexe`, `finicio_e`, `ffinal_e`, `tdocfexe`, `totfexe`, `cfinicio_e`, `cffinal_e`, `tdoccfexe`, `totcfexe`, `czxe`) VALUES
(0, 'S64a9a135be8912.42554601', 1, 1, '', 1, 1, NULL, 1, NULL, '2023-07-07', '21:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 3, 0, 0, 84.75, 84.75, 0, 0, 0, 0, 0, 0, 50, NULL, 0, NULL, 134.75, NULL, 134.75, 0, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'C', 0, 0, 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0),
(0, 'S64ac13061e2881.86703409', 1, 2, '', 1, 2, NULL, 1, NULL, '2023-07-08', '21:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 34, 34, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10, NULL, 0, NULL, 44, NULL, 44, 0, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'C', 0, 0, 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0),
(0, 'S64ac2fddcdfe04.57950427', 2, 3, '', 7, 3, NULL, 1, NULL, '2023-07-08', '21:00:00', 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 54, 54, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20, NULL, 0, NULL, 74, NULL, 74, 0, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'C', 0, 0, 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0),
(0, 'S64b5ccb9687345.35429922', 1, 4, '', 1, 4, NULL, 1, NULL, '2023-07-10', '21:00:00', 0, 0, 0, 2, 0, 0, 50, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, NULL, 0, NULL, 100, NULL, 100, 0, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'C', 0, 0, 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0),
(0, 'S64b85d62c704d2.33640200', 1, 5, '', 2, 6, NULL, 1, NULL, '2023-07-17', '21:00:00', 0, 0, 1, 1, 0, 0, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 25, NULL, 0, NULL, 45, NULL, 45, 0, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'C', 0, 0, 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0),
(0, 'S64b85df8ae6b60.97625090', 1, 6, '', 3, 7, NULL, 1, NULL, '2023-07-17', '21:00:00', 0, 0, 1, 1, 0, 0, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, NULL, 0, NULL, 70, NULL, 70, 0, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'C', 0, 0, 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0),
(0, 'S64b85e76b28434.78325692', 1, 7, '', 2, 8, '2', 1, NULL, '2023-07-19', '16:04:47', 0, 0, 2, 2, 1, NULL, NULL, 2, 0, 0, 0, NULL, NULL, 0, 0, 0, 0, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 10, NULL, 0, NULL, 12, NULL, 12, 0, 0, NULL, NULL, 0, 2, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'X', 0, 0, 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0),
(0, 'S64b943eecf3917.18256266', 1, 8, '', 3, 9, NULL, 1, NULL, '2023-07-19', '21:00:00', 0, 0, 2, 2, 0, 0, 4, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20, NULL, 0, NULL, 24, NULL, 24, 0, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'C', 0, 0, 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0),
(0, 'S64c0091fb6e9c5.32385142', 1, 9, '', 3, 10, NULL, 1, NULL, '2023-07-20', '21:00:00', 0, 0, 3, 6, 0, 0, 18, 18, 1, 1, 0, 0, 0, 0, 1, 1, 0, 0, 4.52, 4.52, 0, 0, 0, 0, 0, 0, 12, NULL, 0, NULL, 34.52, NULL, 34.52, 0, 0, NULL, NULL, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'C', 0, 0, 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0, 0, 0, '0.00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `correlativo`
--

CREATE TABLE `correlativo` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_numdoc` int(11) NOT NULL,
  `tik` int(11) NOT NULL DEFAULT 0,
  `cof` int(11) NOT NULL DEFAULT 0,
  `ccf` int(11) NOT NULL DEFAULT 0,
  `ref` int(11) NOT NULL DEFAULT 0,
  `ii` int(11) NOT NULL DEFAULT 0,
  `di` int(11) NOT NULL DEFAULT 0,
  `ai` int(11) NOT NULL,
  `ti` int(11) NOT NULL,
  `voc` int(11) NOT NULL,
  `aj` int(11) NOT NULL,
  `cot` int(11) NOT NULL,
  `tre` int(11) NOT NULL,
  `trr` int(11) NOT NULL,
  `dev` int(11) NOT NULL,
  `nc` int(11) NOT NULL,
  `ped` int(11) NOT NULL COMMENT 'Pedidos',
  `pdp` int(11) NOT NULL,
  `cof_e` int(11) NOT NULL,
  `ccf_e` int(11) NOT NULL,
  `nc_e` int(11) NOT NULL,
  `dev_e` int(11) NOT NULL,
  `con` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `correlativo`
--

INSERT INTO `correlativo` (`id_server`, `unique_id`, `id_sucursal`, `id_numdoc`, `tik`, `cof`, `ccf`, `ref`, `ii`, `di`, `ai`, `ti`, `voc`, `aj`, `cot`, `tre`, `trr`, `dev`, `nc`, `ped`, `pdp`, `cof_e`, `ccf_e`, `nc_e`, `dev_e`, `con`) VALUES
(1, 'O5f05eb400e59b6.44715651', 1, 1, 0, 1, 1, 0, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion`
--

CREATE TABLE `cotizacion` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_cotizacion` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `vigencia` int(11) NOT NULL,
  `numero_doc` varchar(15) NOT NULL,
  `total` float NOT NULL,
  `impresa` tinyint(1) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion_detalle`
--

CREATE TABLE `cotizacion_detalle` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_detalle` int(11) NOT NULL,
  `id_cotizacion` int(11) NOT NULL,
  `id_prod_serv` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` float NOT NULL,
  `subtotal` float NOT NULL,
  `tipo_prod_serv` varchar(20) NOT NULL,
  `id_presentacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credito`
--

CREATE TABLE `credito` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_credito` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipo_doc` varchar(50) NOT NULL COMMENT 'credito consolidado antiguo CCA; sera el codigo en vez de COF o CCF; si se guarda creditos antiguos no asociados a nuevas facturas',
  `numero_doc` varchar(50) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `dias` int(11) NOT NULL,
  `total` float NOT NULL,
  `abono` float NOT NULL,
  `saldo` float NOT NULL,
  `finalizada` tinyint(1) NOT NULL,
  `cuotas` tinyint(1) NOT NULL,
  `pedido` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `credito`
--

INSERT INTO `credito` (`id_server`, `unique_id`, `id_sucursal`, `id_credito`, `id_cliente`, `fecha`, `tipo_doc`, `numero_doc`, `id_factura`, `dias`, `total`, `abono`, `saldo`, `finalizada`, `cuotas`, `pedido`) VALUES
(0, 'S64a87fef861862.45308501', 1, 1, 1, '2023-07-07', 'CCF', '2', 3, 0, 29.38, 0, 29.38, 0, 0, 0),
(0, 'S64a87fef869203.42178898', 1, 2, 1, '2023-07-07', 'CCF', '2', 3, 0, 29.38, 0, 29.38, 0, 0, 0),
(0, 'S64a87fef8705c1.69681978', 1, 3, 1, '2023-07-07', 'CCF', '2', 3, 0, 29.38, 0, 29.38, 0, 0, 0),
(0, 'S64a87fef876f01.82860472', 1, 4, 1, '2023-07-07', 'CCF', '2', 3, 0, 29.38, 0, 29.38, 0, 0, 0),
(0, 'S64a87fef87cb65.61138908', 1, 5, 1, '2023-07-07', 'CCF', '2', 3, 0, 29.38, 0, 29.38, 0, 0, 0),
(0, 'S64a87fef885665.62790934', 1, 6, 1, '2023-07-07', 'CCF', '2', 3, 0, 29.38, 0, 29.38, 0, 0, 0),
(0, 'S64b96a07bb03e5.09676173', 1, 7, 1, '2023-07-20', 'COF', '3', 20, 0, 2, 0, 2, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_por_pagar_abonos`
--

CREATE TABLE `cuentas_por_pagar_abonos` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_abono` int(11) NOT NULL,
  `id_cuentas_por_pagar` int(11) NOT NULL,
  `abono` decimal(10,4) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_banco`
--

CREATE TABLE `cuenta_banco` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_cuenta` int(11) NOT NULL,
  `numero_cuenta` varchar(50) NOT NULL,
  `nombre_cuenta` varchar(100) NOT NULL,
  `id_banco` int(11) NOT NULL,
  `observaciones` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta_pagar`
--

CREATE TABLE `cuenta_pagar` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_cuenta_pagar` int(11) NOT NULL,
  `id_compra` int(11) DEFAULT NULL,
  `hora` time NOT NULL,
  `numero_doc` varchar(15) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `monto` float DEFAULT NULL,
  `saldo_pend` float NOT NULL,
  `fecha_vence` date DEFAULT NULL,
  `comentario` varchar(10) DEFAULT NULL,
  `dias_credito` int(3) DEFAULT NULL,
  `alias_tipodoc` char(5) NOT NULL,
  `id_empleado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuota`
--

CREATE TABLE `cuota` (
  `id_cuota` int(11) NOT NULL,
  `id_venta_cuota` int(11) NOT NULL,
  `fecha_vence` date NOT NULL,
  `valorcuota` decimal(10,2) NOT NULL,
  `cuotanumero` smallint(3) NOT NULL COMMENT 'CUOTA 0=PRIMA',
  `pagada` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_pago` date DEFAULT NULL,
  `id_factura` int(11) DEFAULT NULL,
  `abonocuota` decimal(8,2) NOT NULL,
  `saldocuota` decimal(8,2) NOT NULL,
  `fecha_ult_abono` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_departamento` int(11) NOT NULL COMMENT 'ID del departamento',
  `nombre_departamento` varchar(30) NOT NULL COMMENT 'Nombre del departamento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Departamentos de El Salvador';

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`id_server`, `unique_id`, `id_departamento`, `nombre_departamento`) VALUES
(1, 'O5f05eb40135001.25680380', 1, 'Ahuachapán'),
(2, 'O5f05eb401408b5.02743458', 2, 'Santa Ana'),
(3, 'O5f05eb40161022.48856100', 3, 'Sonsonate'),
(4, 'O5f05eb40182db5.95090534', 4, 'La Libertad'),
(5, 'O5f05eb401a2e50.39463793', 5, 'Chalatenango'),
(6, 'O5f05eb401c4479.29050638', 6, 'San Salvador'),
(7, 'O5f05eb401e5fe5.36412348', 7, 'Cuscatlán'),
(8, 'O5f05eb40207836.82365702', 8, 'La Paz'),
(9, 'O5f05eb40228958.36965162', 9, 'Cabañas'),
(10, 'O5f05eb40249ce5.41146105', 10, 'San Vicente'),
(11, 'O5f05eb4026b108.61176837', 11, 'Usulután'),
(12, 'O5f05eb4028e4e6.77060759', 12, 'Morazán'),
(13, 'O5f05eb402ae8d4.99014875', 13, 'San Miguel'),
(14, 'O5f05eb402d0161.28754843', 14, 'La Unión');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentoMH`
--

CREATE TABLE `departamentoMH` (
  `id_departamento` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `departamentoMH`
--

INSERT INTO `departamentoMH` (`id_departamento`, `nombre`) VALUES
(1, 'Ahuachapán'),
(2, 'Santa Ana'),
(3, 'Sonsonate'),
(4, 'Chalatenango'),
(5, 'La Libertad'),
(6, 'San Salvador'),
(7, 'Cuscatlán'),
(8, 'La Paz'),
(9, 'Cabañas'),
(10, 'San Vicente'),
(11, 'Usulután'),
(12, 'San Miguel'),
(13, 'Morazán'),
(14, 'La Unión');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_apertura`
--

CREATE TABLE `detalle_apertura` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_detalle` int(11) NOT NULL,
  `id_apertura` int(11) NOT NULL,
  `turno` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `vigente` tinyint(1) NOT NULL,
  `caja` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_apertura`
--

INSERT INTO `detalle_apertura` (`id_server`, `unique_id`, `id_sucursal`, `id_detalle`, `id_apertura`, `turno`, `id_usuario`, `fecha`, `hora`, `vigente`, `caja`) VALUES
(0, 'S64a84ea41d7d19.97052958', 1, 1, 1, 1, 1, '2023-07-07', '11:43:00', 0, 2),
(0, 'S64a9a135c032f6.70095578', 0, 2, 1, 2, 0, '2023-07-07', '21:00:00', 0, 0),
(0, 'S64a9a13acff5d7.35156682', 1, 3, 2, 1, 1, '2023-07-08', '11:47:38', 0, 2),
(0, 'S64a9e437a5fda9.16625296', 2, 4, 3, 1, 7, '2023-07-08', '16:33:27', 0, 6),
(0, 'S64ac130621c749.62202499', 0, 5, 2, 2, 0, '2023-07-08', '21:00:00', 0, 0),
(0, 'S64ac130a993366.95565518', 1, 6, 4, 1, 1, '2023-07-10', '08:17:46', 0, 2),
(0, 'S64ac2fddd03114.23555893', 0, 7, 3, 2, 0, '2023-07-08', '21:00:00', 0, 0),
(0, 'S64ac2fe2ee04d6.72292114', 2, 8, 5, 1, 7, '2023-07-10', '10:20:50', 1, 6),
(0, 'S64b5ccb96c2191.57305161', 0, 9, 4, 2, 0, '2023-07-10', '21:00:00', 0, 0),
(0, 'S64b5cccd777063.23537538', 1, 10, 6, 1, 2, '2023-07-17', '17:20:45', 0, 2),
(0, 'S64b5ce1b14fa45.78397233', 1, 11, 7, 1, 3, '2023-07-17', '17:26:19', 0, 3),
(0, 'S64b85d62c8f092.70240461', 0, 12, 6, 2, 0, '2023-07-17', '21:00:00', 0, 0),
(0, 'S64b85d86beb700.38551830', 1, 13, 8, 1, 2, '2023-07-19', '16:02:46', 0, 2),
(0, 'S64b85df8b0c7b6.32617272', 0, 14, 7, 2, 0, '2023-07-17', '21:00:00', 0, 0),
(0, 'S64b85e76b7b592.55231134', 0, 15, 8, 2, 0, '2023-07-19', '16:06:46', 1, 0),
(0, 'S64b860b21623d6.09816401', 1, 16, 9, 1, 3, '2023-07-19', '16:16:18', 0, 3),
(0, 'S64b943eed25d69.20126621', 0, 17, 9, 2, 0, '2023-07-19', '21:00:00', 0, 0),
(0, 'S64b966d39c8157.04537208', 1, 18, 10, 1, 3, '2023-07-20', '10:54:43', 0, 3),
(0, 'S64c0091fb95687.66112484', 0, 19, 10, 2, 0, '2023-07-20', '21:00:00', 0, 0),
(0, 'S64c0092a8463a4.04174311', 1, 20, 11, 1, 2, '2023-07-25', '11:40:58', 1, 2),
(0, 'S64c02dbc1c8ee6.39497357', 1, 21, 12, 1, 4, '2023-07-25', '14:17:00', 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_det_compra` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_server_prod` int(11) NOT NULL,
  `cantidad` decimal(10,4) DEFAULT NULL,
  `ultcosto` decimal(5,2) DEFAULT NULL,
  `exento` tinyint(1) NOT NULL,
  `subtotal` float NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `id_server_presen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `detalle_compra`
--

INSERT INTO `detalle_compra` (`id_server`, `unique_id`, `id_sucursal`, `id_det_compra`, `id_compra`, `id_producto`, `id_server_prod`, `cantidad`, `ultcosto`, `exento`, `subtotal`, `id_presentacion`, `id_server_presen`) VALUES
(0, 'S64bfdcb5256516.59246215', 0, 1, 1, 1, 0, '10.0000', '1.00', 0, 10, 1, 0),
(0, 'S64bfe1deea6758.84940960', 0, 2, 2, 1, 0, '10.0000', '1.00', 0, 10, 1, 0),
(0, 'S64bfe1fb46ed48.65351804', 0, 3, 3, 1, 0, '10.0000', '1.00', 0, 10, 1, 0),
(0, 'S64bfe2986c16d7.76096971', 0, 4, 4, 1, 0, '10.0000', '1.00', 0, 10, 1, 0),
(0, 'S64bfe527632dc4.78130386', 0, 5, 5, 1, 0, '10.0000', '1.00', 0, 10, 1, 0),
(0, 'S64bfe7e25d8424.96329083', 0, 6, 6, 1, 0, '15.0000', '1.00', 0, 15, 1, 0),
(0, 'S64bfe830dd4890.28397877', 0, 7, 7, 1, 0, '20.0000', '1.00', 0, 20, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra2`
--

CREATE TABLE `detalle_compra2` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_det_compra` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_server_prod` int(11) NOT NULL,
  `cantidad` decimal(10,4) DEFAULT NULL,
  `ultcosto` decimal(5,2) DEFAULT NULL,
  `exento` tinyint(1) NOT NULL,
  `subtotal` float NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `id_server_presen` int(11) NOT NULL,
  `fecha_vence` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `detalle_compra2`
--

INSERT INTO `detalle_compra2` (`id_server`, `unique_id`, `id_sucursal`, `id_det_compra`, `id_compra`, `id_producto`, `id_server_prod`, `cantidad`, `ultcosto`, `exento`, `subtotal`, `id_presentacion`, `id_server_presen`, `fecha_vence`) VALUES
(0, 'S64bfe9af0f80e6.47611421', 0, 1, 1, 1, 0, '1.0000', '1.00', 0, 1, 1, 0, '0000-00-00'),
(0, 'S64bfeb7c51c7a4.16556928', 0, 2, 2, 1, 0, '10.0000', '1.00', 0, 10, 1, 0, '0000-00-00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_voucher`
--

CREATE TABLE `detalle_voucher` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_detalle` int(11) NOT NULL,
  `fecha` varchar(11) DEFAULT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `cargo` varchar(11) DEFAULT NULL,
  `porcentage` varchar(11) DEFAULT NULL,
  `descuento` varchar(11) DEFAULT NULL,
  `devolucion` varchar(11) DEFAULT NULL,
  `bonificacion` varchar(11) DEFAULT NULL,
  `retencion` varchar(11) DEFAULT NULL,
  `vin` varchar(11) DEFAULT NULL,
  `saldo` varchar(11) DEFAULT NULL,
  `id_cuenta_pagar` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones`
--

CREATE TABLE `devoluciones` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_dev` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_factura_dev` int(11) NOT NULL,
  `cant` int(11) NOT NULL,
  `monto` float NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `nombre` text NOT NULL,
  `dui` text NOT NULL,
  `telefono` int(11) NOT NULL,
  `exento` decimal(10,2) NOT NULL,
  `gravado` decimal(10,2) NOT NULL,
  `concepto` varchar(250) NOT NULL,
  `tipo` tinyint(1) NOT NULL COMMENT '0=AJUSTE, 1 =DEV'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones_corte`
--

CREATE TABLE `devoluciones_corte` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_dev` int(11) NOT NULL,
  `id_corte` int(11) NOT NULL,
  `n_devolucion` varchar(30) NOT NULL,
  `t_devolucion` double NOT NULL,
  `afecta` varchar(30) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `exento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones_det`
--

CREATE TABLE `devoluciones_det` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_dev_det` int(11) NOT NULL,
  `id_dev` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cant` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `bonificacion` int(11) NOT NULL,
  `id_factura_detalle` int(11) NOT NULL,
  `exento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `nit` varchar(20) NOT NULL,
  `dui` varchar(16) NOT NULL,
  `direccion` varchar(250) NOT NULL,
  `telefono1` varchar(12) NOT NULL,
  `telefono2` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `salariobase` float NOT NULL,
  `id_tipo_empleado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id_server`, `unique_id`, `id_sucursal`, `id_empleado`, `nombre`, `apellido`, `nit`, `dui`, `direccion`, `telefono1`, `telefono2`, `email`, `salariobase`, `id_tipo_empleado`) VALUES
(0, 'S64a84fb70621a9.12552425', 1, 1, 'JOSE ALFREDO', 'PAZ', '4666-666666-666-6', '55446464-4', 'SAN MIGUEL', '', '', '', 365, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `idempresa` int(1) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `razonsocial` varchar(60) DEFAULT NULL,
  `direccion` varchar(250) DEFAULT NULL,
  `ubicacion` varchar(23) DEFAULT NULL,
  `nrc` varchar(20) DEFAULT NULL,
  `nit` varchar(17) DEFAULT NULL,
  `giro` varchar(60) DEFAULT NULL,
  `telefono1` varchar(10) DEFAULT NULL,
  `depto` varchar(10) DEFAULT NULL,
  `direccion2` varchar(32) DEFAULT NULL,
  `telefono2` varchar(9) DEFAULT NULL,
  `logo` varchar(250) NOT NULL,
  `iva` decimal(5,2) NOT NULL,
  `monto_retencion1` decimal(5,2) NOT NULL,
  `monto_retencion10` decimal(5,2) NOT NULL,
  `monto_percepcion` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id_server`, `unique_id`, `idempresa`, `descripcion`, `razonsocial`, `direccion`, `ubicacion`, `nrc`, `nit`, `giro`, `telefono1`, `depto`, `direccion2`, `telefono2`, `logo`, `iva`, `monto_retencion1`, `monto_retencion10`, `monto_percepcion`) VALUES
(1, 'O5f05eb405a2e80.45664621', 1, 'SUPER MERCADO EL SOL', 'ELECTROSTAR', 'BARRIO LAS DELICIAS, CALLE RUTA MILITAR, FRENTE A ESTADIO MUNICIPAL, SANTA ROSA DE LIMA LA UNION', 'San Miguel, El Salvador', '263285-3', '1217-130217-102-0', 'ELECTRODOMESTICOS', '2661-4740', 'SAN MIGUEL', 'SAN MIGUEL', '', 'img/64b5c797c6546_elsol1.jpeg', '13.00', '100.00', '100.00', '100.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estante`
--

CREATE TABLE `estante` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_estante` int(11) NOT NULL,
  `id_ubicacion` int(11) NOT NULL,
  `descripcion` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `numero_doc` varchar(30) NOT NULL,
  `referencia` varchar(15) NOT NULL,
  `numero_ref` int(11) NOT NULL,
  `subtotal` float NOT NULL,
  `sumas` float NOT NULL,
  `suma_gravado` float NOT NULL,
  `iva` float NOT NULL,
  `retencion` decimal(8,2) NOT NULL,
  `percepcion` decimal(8,2) NOT NULL,
  `venta_exenta` float NOT NULL,
  `total_menos_retencion` float NOT NULL,
  `total` float NOT NULL,
  `descuento` float NOT NULL,
  `porcentaje` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `anulada` tinyint(1) NOT NULL DEFAULT 0,
  `id_empleado` int(11) NOT NULL,
  `finalizada` tinyint(1) NOT NULL,
  `impresa` tinyint(1) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `serie` varchar(25) NOT NULL,
  `serie_e` varchar(25) NOT NULL,
  `num_fact_impresa` varchar(30) NOT NULL,
  `hora` time NOT NULL,
  `turno` int(11) NOT NULL,
  `id_apertura` int(11) NOT NULL,
  `id_apertura_pagada` int(11) NOT NULL,
  `credito` tinyint(1) NOT NULL,
  `abono` decimal(8,2) NOT NULL,
  `saldo` decimal(8,2) NOT NULL,
  `afecta` int(11) NOT NULL,
  `tipo_documento` varchar(20) NOT NULL,
  `caja` int(11) NOT NULL,
  `numero_doc_e` varchar(30) NOT NULL,
  `num_fact_impresa_e` varchar(30) NOT NULL,
  `nombre` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `direccion` text NOT NULL,
  `precio_aut` int(11) NOT NULL,
  `clave` varchar(6) NOT NULL,
  `subt_bonifica` decimal(11,4) NOT NULL,
  `id_dev` int(11) NOT NULL DEFAULT 0,
  `pagar` decimal(11,4) NOT NULL,
  `extra_nombre` varchar(50) NOT NULL,
  `tot_cotrans` decimal(10,4) NOT NULL,
  `tot_fovial` decimal(10,4) NOT NULL,
  `tipo_pago` varchar(20) NOT NULL,
  `total_efectivo` decimal(10,4) NOT NULL COMMENT 'valor pagado en efectivo',
  `total_credito` decimal(10,4) NOT NULL COMMENT 'valor del credito',
  `total_tarjeta` decimal(10,4) NOT NULL COMMENT 'valor pagado con tarjeta',
  `num_transac` varchar(25) NOT NULL,
  `datos_extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `costo` tinyint(1) NOT NULL,
  `id_resolucion` int(11) NOT NULL COMMENT 'resolucion de documento vigente 	',
  `ventacuotas` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`id_server`, `unique_id`, `id_sucursal`, `id_factura`, `id_cliente`, `fecha`, `numero_doc`, `referencia`, `numero_ref`, `subtotal`, `sumas`, `suma_gravado`, `iva`, `retencion`, `percepcion`, `venta_exenta`, `total_menos_retencion`, `total`, `descuento`, `porcentaje`, `id_usuario`, `anulada`, `id_empleado`, `finalizada`, `impresa`, `tipo`, `serie`, `serie_e`, `num_fact_impresa`, `hora`, `turno`, `id_apertura`, `id_apertura_pagada`, `credito`, `abono`, `saldo`, `afecta`, `tipo_documento`, `caja`, `numero_doc_e`, `num_fact_impresa_e`, `nombre`, `direccion`, `precio_aut`, `clave`, `subt_bonifica`, `id_dev`, `pagar`, `extra_nombre`, `tot_cotrans`, `tot_fovial`, `tipo_pago`, `total_efectivo`, `total_credito`, `total_tarjeta`, `num_transac`, `datos_extra`, `costo`, `id_resolucion`, `ventacuotas`) VALUES
(0, 'S64a8502b516cc5.12432208', 1, 1, 1, '2023-07-07', '0000000001_CCF', '', 0, 55.37, 49, 49, 6.37, '0.00', '0.00', 0, 55.37, 55.37, 0, 0, 1, 0, 1, 1, 1, 'CREDITO FISCAL', '', '', '1', '11:49:31', 1, 1, 1, 0, '0.00', '0.00', 0, 'CCF', 2, '', '', ' ', ' ', 0, '', '0.0000', 0, '55.3700', '', '0.0000', '0.0000', 'CON', '0.0000', '0.0000', '0.0000', ' ', '', 0, 2, 0),
(0, 'S64a879f45c5f97.35422490', 1, 2, 1, '2023-07-07', '0000000002_CCF', '', 0, 29.38, 26, 26, 3.38, '0.00', '0.00', 0, 29.38, 29.38, 0, 0, 1, 0, 1, 1, 0, 'CREDITO FISCAL', '', '', '2', '14:47:48', 1, 1, 1, 0, '0.00', '0.00', 0, 'CCF', 2, '', '', '', '', 0, '', '0.0000', 0, '29.3800', '', '0.0000', '0.0000', '0', '0.0000', '0.0000', '0.0000', '', '', 0, 2, 0),
(0, 'S64a879f4608ed9.93880225', 1, 3, 1, '2023-07-07', '0000000003_CCF', '', 0, 29.38, 26, 26, 3.38, '0.00', '0.00', 0, 29.38, 29.38, 0, 0, 1, 0, 1, 1, 1, 'CREDITO FISCAL', '', '', '2', '14:47:48', 1, 1, 1, 1, '0.00', '0.00', 0, 'CCF', 2, '', '', ' ', ' ', 0, '', '0.0000', 0, '29.3800', '', '0.0000', '0.0000', 'CRE', '0.0000', '5.0000', '0.0000', ' ', '', 0, 2, 0),
(0, 'S64a9a15dd760f4.77633707', 1, 4, 1, '2023-07-08', '0000000001_COF', '', 0, 34, 34, 34, 0, '0.00', '0.00', 0, 34, 34, 0, 0, 1, 0, 1, 1, 1, 'FACTURA CONSUMIDOR', '', '', '2', '11:48:13', 1, 2, 2, 0, '0.00', '0.00', 0, 'COF', 2, '', '', ' ', ' ', 0, '', '0.0000', 0, '34.0000', '', '0.0000', '0.0000', 'CON', '0.0000', '0.0000', '0.0000', ' ', '', 0, 1, 0),
(0, 'S64a9e74332f988.56637989', 2, 5, 1, '2023-07-08', '0000000001_COF', '', 0, 54, 54, 54, 0, '0.00', '0.00', 0, 54, 54, 0, 0, 7, 0, 1, 1, 1, 'FACTURA CONSUMIDOR', '', '', '10', '16:46:27', 1, 3, 3, 0, '0.00', '0.00', 0, 'COF', 6, '', '', ' ', ' ', 0, '', '0.0000', 0, '54.0000', '', '0.0000', '0.0000', 'TRA,CON', '0.0000', '0.0000', '0.0000', ' ', '', 0, 1, 0),
(0, 'S64ac134dc1c523.36198774', 1, 6, 1, '2023-07-10', '0000000000_TIK', '', 0, 4, 4, 4, 0, '0.00', '0.00', 0, 4, 4, 0, 0, 1, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '', '08:18:53', 1, 4, 4, 0, '0.00', '0.00', 0, 'TIK', 2, '', '', ' ', ' ', 0, '', '0.0000', 0, '4.0000', '', '0.0000', '0.0000', '', '5.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64ac1375da9d57.90088143', 1, 7, 1, '2023-07-10', '0000000001_TIK', '', 0, 4, 4, 4, 0, '0.00', '0.00', 0, 4, 4, 0, 0, 1, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '', '08:19:33', 1, 4, 4, 0, '0.00', '0.00', 0, 'TIK', 2, '', '', ' ', ' ', 0, '', '0.0000', 0, '4.0000', '', '0.0000', '0.0000', 'CON', '0.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64ac2ff5b8de16.67853920', 2, 8, 1, '2023-07-10', '0000000000_TIK', '', 0, 16, 16, 16, 0, '0.00', '0.00', 0, 16, 16, 0, 0, 7, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '', '10:21:09', 1, 5, 5, 0, '0.00', '0.00', 0, 'TIK', 6, '', '', ' ', ' ', 0, '', '0.0000', 0, '16.0000', '', '0.0000', '0.0000', 'CON', '0.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64ac306e86ec58.45727860', 1, 9, 1, '2023-07-10', '0000000002_TIK', '', 0, 42, 42, 42, 0, '0.00', '0.00', 0, 42, 42, 0, 0, 1, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '', '10:23:10', 1, 4, 4, 0, '0.00', '0.00', 0, 'TIK', 2, '', '', ' ', ' ', 0, '', '0.0000', 0, '42.0000', '', '0.0000', '0.0000', 'CON', '0.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64ac30a75bf0a8.74812861', 2, 10, 1, '2023-07-10', '0000000001_TIK', '', 0, 22.5, 22.5, 22.5, 0, '0.00', '0.00', 0, 22.5, 22.5, 0, 0, 7, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '', '10:24:07', 1, 5, 5, 0, '0.00', '0.00', 0, 'TIK', 6, '', '', ' ', ' ', 0, '', '0.0000', 0, '22.5000', '', '0.0000', '0.0000', 'TAR', '0.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64b5cee1533149.14197546', 1, 11, 1, '2023-07-17', '0000000001_TIK', '', 0, 20, 20, 20, 0, '0.00', '0.00', 0, 20, 20, 0, 0, 3, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '', '17:29:37', 1, 7, 7, 0, '0.00', '0.00', 0, 'TIK', 3, '', '', ' ', ' ', 0, '', '0.0000', 0, '20.0000', '', '0.0000', '0.0000', 'CON', '0.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64b5cf36e1c306.44826813', 1, 12, 1, '2023-07-17', '0000000001_TIK', '', 0, 20, 20, 20, 0, '0.00', '0.00', 0, 20, 20, 0, 0, 2, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '', '17:31:02', 1, 6, 6, 0, '0.00', '0.00', 0, 'TIK', 2, '', '', ' ', ' ', 0, '', '0.0000', 0, '20.0000', '', '0.0000', '0.0000', 'CON', '0.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64b85da0b93de1.04959552', 1, 13, -1, '2023-07-19', '0000000002_TIK', '', 0, 2, 2, 2, 0, '0.00', '0.00', 0, 2, 2, 0, 0, 2, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '', '16:03:12', 1, 8, 8, 0, '0.00', '0.00', 0, 'TIK', 2, '', '', ' ', ' ', 0, '', '0.0000', 0, '2.0000', '', '0.0000', '0.0000', 'TAR', '0.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64b860bd489118.33206145', 1, 14, -1, '2023-07-19', '0000000002_TIK', '', 0, 4, 4, 4, 0, '0.00', '0.00', 0, 4, 4, 0, 0, 3, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '', '16:16:29', 1, 9, 9, 0, '0.00', '0.00', 0, 'TIK', 3, '', '', ' ', ' ', 0, '', '0.0000', 0, '4.0000', '', '0.0000', '0.0000', 'CON', '0.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64b966eeca6d72.37366213', 1, 15, -1, '2023-07-20', '0000000003_TIK', '', 0, 2, 2, 2, 0, '0.00', '0.00', 0, 2, 2, 0, 0, 3, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '', '10:55:10', 1, 10, 10, 0, '0.00', '0.00', 0, 'TIK', 3, '', '', ' ', ' ', 0, '', '0.0000', 0, '2.0000', '', '0.0000', '0.0000', 'CON', '0.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64b9677e8fb2e2.67539866', 1, 16, -1, '2023-07-20', '0000000004_TIK', '', 0, 2, 2, 2, 0, '0.00', '0.00', 0, 2, 2, 0, 0, 3, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '', '10:57:34', 1, 10, 10, 0, '0.00', '0.00', 0, 'TIK', 3, '', '', ' ', ' ', 0, '', '0.0000', 0, '2.0000', '', '0.0000', '0.0000', 'CON', '0.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64b968e6360458.20894074', 1, 17, -1, '2023-07-20', '0000000005_TIK', '', 0, 10, 10, 10, 0, '0.00', '0.00', 0, 10, 10, 0, 0, 3, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '0000000005', '11:03:34', 1, 10, 10, 0, '0.00', '0.00', 0, 'TIK', 3, '', '', ' ', ' ', 0, '', '0.0000', 0, '10.0000', '', '0.0000', '0.0000', '', '20.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64b9694f1ecd33.64373420', 1, 18, -1, '2023-07-20', '0000000006_TIK', '', 0, 4, 4, 4, 0, '0.00', '0.00', 0, 4, 4, 0, 0, 3, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '6', '11:05:19', 1, 10, 10, 0, '0.00', '0.00', 0, 'TIK', 3, '', '', ' ', ' ', 0, '', '0.0000', 0, '4.0000', '', '0.0000', '0.0000', '', '5.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64b969a3ac7ce4.73197640', 1, 19, 1, '2023-07-20', '0000000001_CCF', '', 0, 4.52, 4, 4, 0.52, '0.00', '0.00', 0, 4.52, 4.52, 0, 0, 3, 0, 1, 1, 1, 'CREDITO FISCAL', '', '', '4', '11:06:43', 1, 10, 10, 0, '0.00', '0.00', 0, 'CCF', 3, '', '', ' ', ' ', 0, '', '0.0000', 0, '4.5200', '', '0.0000', '0.0000', '', '5.0000', '0.0000', '0.0000', ' ', '', 0, 2, 0),
(0, 'S64b969f031fe42.46006370', 1, 20, 1, '2023-07-20', '0000000001_COF', '', 0, 2, 2, 2, 0, '0.00', '0.00', 0, 2, 2, 0, 0, 3, 0, 1, 1, 1, 'FACTURA CONSUMIDOR', '', '', '3', '11:08:00', 1, 10, 10, 1, '0.00', '0.00', 0, 'COF', 3, '', '', ' ', ' ', 0, '', '0.0000', 0, '2.0000', '', '0.0000', '0.0000', 'CRE', '0.0000', '2.0000', '0.0000', ' ', '', 0, 1, 0),
(0, 'S64c009396b8830.73807175', 1, 21, -1, '2023-07-25', '0000000003_TIK', '', 0, 20, 20, 20, 0, '0.00', '0.00', 0, 20, 20, 0, 0, 2, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '3', '11:41:13', 1, 11, 11, 0, '0.00', '0.00', 0, 'TIK', 2, '', '', ' ', ' ', 0, '', '0.0000', 0, '20.0000', '', '0.0000', '0.0000', '', '20.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64c009915cef46.01496013', 1, 22, -1, '2023-07-25', '0000000004_TIK', '', 0, 2, 2, 2, 0, '0.00', '0.00', 0, 2, 2, 0, 0, 2, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '4', '11:42:41', 1, 11, 11, 0, '0.00', '0.00', 0, 'TIK', 2, '', '', ' ', ' ', 0, '', '0.0000', 0, '2.0000', '', '0.0000', '0.0000', 'TAR', '0.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64c02820f368b3.59026416', 1, 23, -1, '2023-07-25', '0000000005_TIK', '', 0, 2, 2, 2, 0, '0.00', '0.00', 0, 2, 2, 0, 0, 2, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '5', '13:53:04', 1, 11, 11, 0, '0.00', '0.00', 0, 'TIK', 2, '', '', ' ', ' ', 0, '', '0.0000', 0, '2.0000', '', '0.0000', '0.0000', '', '5.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64c02dc38d2d91.02519410', 1, 24, -1, '2023-07-25', '0000000001_TIK', '', 0, 2, 2, 2, 0, '0.00', '0.00', 0, 2, 2, 0, 0, 4, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '1', '14:17:07', 1, 12, 12, 0, '0.00', '0.00', 0, 'TIK', 4, '', '', ' ', ' ', 0, '', '0.0000', 0, '2.0000', '', '0.0000', '0.0000', '', '5.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0),
(0, 'S64c02dd332f6e3.78579795', 1, 25, -1, '2023-07-25', '0000000002_TIK', '', 0, 2, 2, 2, 0, '0.00', '0.00', 0, 2, 2, 0, 0, 4, 0, 1, 1, 1, 'TICKET', '22SD00000002', '', '2', '14:17:23', 1, 12, 12, 0, '0.00', '0.00', 0, 'TIK', 4, '', '', ' ', ' ', 0, '', '0.0000', 0, '2.0000', '', '0.0000', '0.0000', '', '2.0000', '0.0000', '0.0000', ' ', '', 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_detalle`
--

CREATE TABLE `factura_detalle` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_factura_detalle` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_prod_serv` int(11) NOT NULL,
  `id_server_prod` int(11) NOT NULL,
  `cantidad` decimal(11,4) NOT NULL,
  `precio_venta` decimal(11,4) NOT NULL,
  `subtotal` decimal(11,2) NOT NULL,
  `descuento` float NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `tipo_prod_serv` varchar(30) NOT NULL COMMENT 'PRODUCTO o SERVICIO',
  `id_factura_dia` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `impresa_lote` tinyint(1) NOT NULL,
  `hora` time NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `id_server_presen` int(11) NOT NULL,
  `exento` int(11) NOT NULL,
  `bonificacion` decimal(11,4) NOT NULL,
  `subt_bonifica` decimal(11,4) NOT NULL,
  `combustible` tinyint(1) NOT NULL,
  `impuesto` decimal(10,4) NOT NULL,
  `total` decimal(12,4) NOT NULL,
  `subtotal_iva` decimal(10,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `factura_detalle`
--

INSERT INTO `factura_detalle` (`id_server`, `unique_id`, `id_sucursal`, `id_factura_detalle`, `id_factura`, `id_prod_serv`, `id_server_prod`, `cantidad`, `precio_venta`, `subtotal`, `descuento`, `id_empleado`, `tipo_prod_serv`, `id_factura_dia`, `fecha`, `impresa_lote`, `hora`, `id_presentacion`, `id_server_presen`, `exento`, `bonificacion`, `subt_bonifica`, `combustible`, `impuesto`, `total`, `subtotal_iva`) VALUES
(0, 'S64a8502b5435b2.97267290', 1, 1, 1, 3273, 0, '1.0000', '25.0000', '25.00', 0, 1, 'SERVICIO', 0, '2023-07-07', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '25.0000', '0.0000'),
(0, 'S64a8502b548cc0.25128578', 1, 2, 1, 1, 0, '12.0000', '2.0000', '24.00', 0, 1, 'PRODUCTO', 0, '2023-07-07', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '24.0000', '0.0000'),
(0, 'S64a879f45d5795.48269198', 1, 3, 2, 18, 0, '1.0000', '10.0000', '10.00', 0, 1, 'SERVICIO', 0, '2023-07-07', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '10.0000', '0.0000'),
(0, 'S64a879f45d9176.28662895', 1, 4, 2, 1, 0, '8.0000', '2.0000', '16.00', 0, 1, 'PRODUCTO', 0, '2023-07-07', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '16.0000', '0.0000'),
(0, 'S64a879f4618dd1.47173106', 1, 5, 3, 18, 0, '1.0000', '10.0000', '10.00', 0, 1, 'SERVICIO', 0, '2023-07-07', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '10.0000', '0.0000'),
(0, 'S64a879f461cd50.96383675', 1, 6, 3, 1, 0, '8.0000', '2.0000', '16.00', 0, 1, 'PRODUCTO', 0, '2023-07-07', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '16.0000', '0.0000'),
(0, 'S64a9a15dd85542.78860141', 1, 7, 4, 3274, 0, '1.0000', '10.0000', '10.00', 0, 1, 'SERVICIO', 0, '2023-07-08', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '10.0000', '0.0000'),
(0, 'S64a9a15dd8a5a7.11007454', 1, 8, 4, 1, 0, '12.0000', '2.0000', '24.00', 0, 1, 'PRODUCTO', 0, '2023-07-08', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '24.0000', '0.0000'),
(0, 'S64a9e743363160.15244525', 2, 9, 5, 3275, 0, '1.0000', '10.0000', '10.00', 0, 7, 'SERVICIO', 0, '2023-07-08', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '10.0000', '0.0000'),
(0, 'S64a9e74337d3b4.07429032', 2, 10, 5, 3276, 0, '1.0000', '10.0000', '10.00', 0, 7, 'SERVICIO', 0, '2023-07-08', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '10.0000', '0.0000'),
(0, 'S64a9e7433a2034.12494754', 2, 11, 5, 3277, 0, '1.0000', '10.0000', '10.00', 0, 7, 'SERVICIO', 0, '2023-07-08', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '10.0000', '0.0000'),
(0, 'S64a9e7433c4068.84051984', 2, 12, 5, 1, 0, '12.0000', '2.0000', '24.00', 0, 7, 'PRODUCTO', 0, '2023-07-08', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '24.0000', '0.0000'),
(0, 'S64ac134dc2b587.99532818', 1, 13, 6, 1, 0, '2.0000', '2.0000', '4.00', 0, 1, 'PRODUCTO', 0, '2023-07-10', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '4.0000', '0.0000'),
(0, 'S64ac1375dc4b01.21510383', 1, 14, 7, 1, 0, '2.0000', '2.0000', '4.00', 0, 1, 'PRODUCTO', 0, '2023-07-10', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '4.0000', '0.0000'),
(0, 'S64ac2ff5bb4193.60442244', 2, 15, 8, 1, 0, '8.0000', '2.0000', '16.00', 0, 7, 'PRODUCTO', 0, '2023-07-10', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '16.0000', '0.0000'),
(0, 'S64ac306e8888e4.93237203', 1, 16, 9, 3278, 0, '1.0000', '10.0000', '10.00', 0, 1, 'SERVICIO', 0, '2023-07-10', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '10.0000', '0.0000'),
(0, 'S64ac306e88fd36.90810139', 1, 17, 9, 1, 0, '16.0000', '2.0000', '32.00', 0, 1, 'PRODUCTO', 0, '2023-07-10', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '32.0000', '0.0000'),
(0, 'S64ac30a75f61e5.08117403', 2, 18, 10, 3279, 0, '1.0000', '2.5000', '2.50', 0, 7, 'SERVICIO', 0, '2023-07-10', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '2.5000', '0.0000'),
(0, 'S64ac30a76136e3.71097281', 2, 19, 10, 1, 0, '10.0000', '2.0000', '20.00', 0, 7, 'PRODUCTO', 0, '2023-07-10', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '20.0000', '0.0000'),
(0, 'S64b5cee154a277.12651176', 1, 20, 11, 1, 0, '10.0000', '2.0000', '20.00', 0, 3, 'PRODUCTO', 0, '2023-07-17', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '20.0000', '0.0000'),
(0, 'S64b5cf36e38332.00278832', 1, 21, 12, 1, 0, '10.0000', '2.0000', '20.00', 0, 2, 'PRODUCTO', 0, '2023-07-17', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '20.0000', '0.0000'),
(0, 'S64b85da0b9ad28.08580646', 1, 22, 13, 1, 0, '1.0000', '2.0000', '2.00', 0, 2, 'PRODUCTO', 0, '2023-07-19', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '2.0000', '0.0000'),
(0, 'S64b860bd4a3049.65885302', 1, 23, 14, 1, 0, '2.0000', '2.0000', '4.00', 0, 3, 'PRODUCTO', 0, '2023-07-19', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '4.0000', '0.0000'),
(0, 'S64b966eecad347.03336236', 1, 24, 15, 1, 0, '1.0000', '2.0000', '2.00', 0, 3, 'PRODUCTO', 0, '2023-07-20', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '2.0000', '0.0000'),
(0, 'S64b9677e932fd6.19459809', 1, 25, 16, 1, 0, '1.0000', '2.0000', '2.00', 0, 3, 'PRODUCTO', 0, '2023-07-20', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '2.0000', '0.0000'),
(0, 'S64b968e6388703.85197443', 1, 26, 17, 1, 0, '5.0000', '2.0000', '10.00', 0, 3, 'PRODUCTO', 0, '2023-07-20', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '10.0000', '0.0000'),
(0, 'S64b9694f202f92.40121723', 1, 27, 18, 1, 0, '2.0000', '2.0000', '4.00', 0, 3, 'PRODUCTO', 0, '2023-07-20', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '4.0000', '0.0000'),
(0, 'S64b969a3ace0c7.01516357', 1, 28, 19, 1, 0, '2.0000', '2.0000', '4.00', 0, 3, 'PRODUCTO', 0, '2023-07-20', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '4.0000', '0.0000'),
(0, 'S64b969f033e516.02177461', 1, 29, 20, 1, 0, '1.0000', '2.0000', '2.00', 0, 3, 'PRODUCTO', 0, '2023-07-20', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '2.0000', '0.0000'),
(0, 'S64c009396c0568.51411527', 1, 30, 21, 1, 0, '10.0000', '2.0000', '20.00', 0, 2, 'PRODUCTO', 0, '2023-07-25', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '20.0000', '0.0000'),
(0, 'S64c009915fa8f2.75706450', 1, 31, 22, 1, 0, '1.0000', '2.0000', '2.00', 0, 2, 'PRODUCTO', 0, '2023-07-25', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '2.0000', '0.0000'),
(0, 'S64c028210074f0.64242268', 1, 32, 23, 1, 0, '1.0000', '2.0000', '2.00', 0, 2, 'PRODUCTO', 0, '2023-07-25', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '2.0000', '0.0000'),
(0, 'S64c02dc38d8dd6.57674770', 1, 33, 24, 1, 0, '1.0000', '2.0000', '2.00', 0, 4, 'PRODUCTO', 0, '2023-07-25', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '2.0000', '0.0000'),
(0, 'S64c02dd334f3f4.78894603', 1, 34, 25, 1, 0, '1.0000', '2.0000', '2.00', 0, 4, 'PRODUCTO', 0, '2023-07-25', 0, '00:00:00', 1, 0, 0, '0.0000', '0.0000', 0, '0.0000', '2.0000', '0.0000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_pago`
--

CREATE TABLE `factura_pago` (
  `id` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `alias_tipopago` varchar(4) NOT NULL,
  `subtotal` decimal(12,4) NOT NULL,
  `total_facturado` decimal(12,4) NOT NULL,
  `datos_extra` varchar(250) NOT NULL COMMENT 'tarjeta: numero transaccion;\r\ncheque: numero y banco; credito: dias plazo; transferencia o remesa: numero y efectivo: valor entregado y cambio'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='pPara hacer pagos de cualquier forma incluso pagos combinado';

--
-- Volcado de datos para la tabla `factura_pago`
--

INSERT INTO `factura_pago` (`id`, `id_factura`, `alias_tipopago`, `subtotal`, `total_facturado`, `datos_extra`) VALUES
(1, 1, 'CON', '55.3700', '55.3700', '{\"efectivo\":60,\"cambio\":4.63}'),
(2, 3, 'CRE', '5.0000', '29.3800', '{\"dias_credito\":30}'),
(3, 4, 'CON', '34.0000', '34.0000', '{\"efectivo\":40,\"cambio\":6}'),
(4, 5, 'TRA', '29.0000', '54.0000', '{\"transferencia\":\"43\",\"banco\":\"\"}'),
(5, 5, 'CON', '25.0000', '54.0000', '{\"efectivo\":25,\"cambio\":0}'),
(6, 7, 'CON', '4.0000', '4.0000', '{\"efectivo\":5,\"cambio\":1}'),
(7, 8, 'CON', '16.0000', '16.0000', '{\"efectivo\":20,\"cambio\":4}'),
(8, 9, 'CON', '42.0000', '42.0000', '{\"efectivo\":50,\"cambio\":8}'),
(9, 10, 'TAR', '22.5000', '22.5000', '{\"transaccion\":\"HSHDS\"}'),
(10, 11, 'CON', '20.0000', '20.0000', '{\"efectivo\":20,\"cambio\":0}'),
(11, 12, 'CON', '20.0000', '20.0000', '{\"efectivo\":20,\"cambio\":0}'),
(12, 13, 'TAR', '2.0000', '2.0000', '{\"transaccion\":\"FRE312\"}'),
(13, 14, 'CON', '4.0000', '4.0000', '{\"efectivo\":5,\"cambio\":1}'),
(14, 15, 'CON', '2.0000', '2.0000', '{\"efectivo\":5,\"cambio\":3}'),
(15, 16, 'CON', '2.0000', '2.0000', '{\"efectivo\":2,\"cambio\":0}'),
(16, 20, 'CRE', '2.0000', '2.0000', '{\"dias_credito\":15}'),
(17, 22, 'TAR', '2.0000', '2.0000', '{\"transaccion\":\"DKIE44\"}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fact_imp_combust`
--

CREATE TABLE `fact_imp_combust` (
  `id` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_impuesto` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `imp_nombre` varchar(30) NOT NULL,
  `total_imp` decimal(10,4) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_dif` int(11) NOT NULL,
  `galones_dif` decimal(10,4) NOT NULL,
  `anulada` tinyint(1) NOT NULL,
  `aplica_dif` tinyint(1) NOT NULL,
  `aplica_impuesto` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `giroMH`
--

CREATE TABLE `giroMH` (
  `id` int(11) NOT NULL,
  `codigo` varchar(6) NOT NULL,
  `descripcion` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `giroMH`
--

INSERT INTO `giroMH` (`id`, `codigo`, `descripcion`) VALUES
(1, '01111', 'Cultivo de cereales excepto arroz y para forrajes'),
(2, '01112', 'Cultivo de legumbres'),
(3, '01113', 'Cultivo de semillas oleaginosas'),
(4, '01114', 'Cultivo de plantas para la preparación de semillas'),
(5, '01119', 'Cultivo de otros cereales excepto arroz y forrajeros n.c.p.'),
(6, '01120', 'Cultivo de arroz'),
(7, '01131', 'Cultivo de raíces y tubérculos'),
(8, '01132', 'Cultivo de brotes, bulbos, vegetales tubérculos y cultivos similares'),
(9, '01133', 'Cultivo hortícola de fruto'),
(10, '01134', 'Cultivo de hortalizas de hoja y otras hortalizas ncp'),
(11, '01140', 'Cultivo de caña de azúcar'),
(12, '01150', 'Cultivo de tabaco'),
(13, '01161', 'Cultivo de algodón'),
(14, '01162', 'Cultivo de fibras vegetales excepto algodón'),
(15, '01191', 'Cultivo de plantas no perennes  para la producción de semillas y flores'),
(16, '01192', 'Cultivo de cereales y pastos para la alimentación animal'),
(17, '01199', 'Producción de cultivos no estacionales  ncp'),
(18, '01220', 'Cultivo de frutas tropicales'),
(19, '01230', 'Cultivo de cítricos'),
(20, '01240', 'Cultivo de frutas de pepita y hueso'),
(21, '01251', 'Cultivo de frutas ncp'),
(22, '01252', 'Cultivo de otros frutos  y nueces de árboles y arbustos'),
(23, '01260', 'Cultivo de frutos oleaginosos'),
(24, '01271', 'Cultivo de café'),
(25, '01272', 'Cultivo de plantas para la elaboración de bebidas excepto café'),
(26, '01281', 'Cultivo de especias y aromáticas'),
(27, '01282', 'Cultivo de plantas para la obtención de productos medicinales y farmacéuticos'),
(28, '01291', 'Cultivo de árboles de hule (caucho) para la obtención de látex'),
(29, '01292', 'Cultivo de plantas para la obtención de productos químicos y colorantes'),
(30, '01299', 'Producción de cultivos perennes ncp'),
(31, '01300', 'Propagación de plantas'),
(32, '01301', 'Cultivo de plantas y flores ornamentales'),
(33, '01410', 'Cría y engorde de ganado bovino'),
(34, '01420', 'Cría de caballos y otros equinos'),
(35, '01440', 'Cría de ovejas y cabras'),
(36, '01450', 'Cría de cerdos'),
(37, '01460', 'Cría de aves de corral y producción de huevos'),
(38, '01491', 'Cría de abejas apicultura para la obtención de miel y otros productos apícolas'),
(39, '01492', 'Cría de conejos'),
(40, '01493', 'Cría de iguanas y garrobos'),
(41, '01494', 'Cría de mariposas y otros insectos'),
(42, '01499', 'Cría y obtención de productos animales n.c.p.'),
(43, '01500', 'Cultivo de productos agrícolas en combinación con la cría de animales'),
(44, '01611', 'Servicios de maquinaria agrícola'),
(45, '01612', 'Control de plagas'),
(46, '01613', 'Servicios de riego'),
(47, '01614', 'Servicios de contratación de mano de obra para la agricultura'),
(48, '01619', 'Servicios agrícolas ncp'),
(49, '01621', 'Actividades para mejorar la reproducción, el crecimiento y el rendimiento de los animales y sus productos'),
(50, '01622', 'Servicios de mano de obra pecuaria'),
(51, '01629', 'Servicios pecuarios ncp'),
(52, '01631', 'Labores post cosecha de preparación de los productos agrícolas para su comercialización o para la industria'),
(53, '01632', 'Servicio de beneficio de café'),
(54, '01633', 'Servicio de beneficiado de plantas textiles (incluye el beneficiado cuando este es realizado en la misma explotación agropecuaria)'),
(55, '01640', 'Tratamiento de semillas para la propagación'),
(56, '01700', 'Caza ordinaria y mediante trampas, repoblación de animales de caza y servicios conexos'),
(58, '02100', 'Silvicultura y otras actividades forestales'),
(59, '02200', 'Extracción de madera'),
(60, '02300', 'Recolección de productos diferentes a la madera'),
(61, '02400', 'Servicios de apoyo a la silvicultura'),
(63, '03110', 'Pesca marítima de altura y costera'),
(64, '03120', 'Pesca de agua dulce'),
(65, '03210', 'Acuicultura marítima'),
(66, '03220', 'Acuicultura de agua dulce'),
(67, '03300', 'Servicios de apoyo a la pesca y acuicultura'),
(68, '05100', 'Extracción de hulla'),
(69, '05200', 'Extracción y aglomeración de lignito'),
(70, '06100', 'Extracción de petróleo crudo'),
(71, '06200', 'Extracción de gas natural'),
(72, '07100', 'Extracción de minerales  de hierro'),
(73, '07210', 'Extracción de minerales de uranio y torio'),
(74, '07290', 'Extracción de minerales metalíferos no ferrosos'),
(75, '08100', 'Extracción de piedra, arena y arcilla'),
(76, '08910', 'Extracción de minerales para la fabricación de abonos y productos químicos'),
(77, '08920', 'Extracción y aglomeración de turba'),
(78, '08930', 'Extracción de sal'),
(79, '08990', 'Explotación de otras minas y canteras ncp'),
(80, '09100', 'Actividades de apoyo a la extracción de petróleo y gas natural'),
(81, '09900', 'Actividades de apoyo a la explotación de minas y canteras'),
(82, '10101', 'Servicio de rastros y mataderos de bovinos y porcinos'),
(83, '10102', 'Matanza y procesamiento de bovinos y porcinos'),
(84, '10103', 'Matanza y procesamientos de aves de corral'),
(85, '10104', 'Elaboración y conservación de embutidos y tripas naturales'),
(86, '10105', 'Servicios de conservación y empaque de carnes'),
(87, '10106', 'Elaboración y conservación de grasas y aceites animales'),
(88, '10107', 'Servicios de molienda de carne'),
(89, '10108', 'Elaboración de productos de carne ncp'),
(90, '10201', 'Procesamiento y conservación de pescado, crustáceos y moluscos'),
(91, '10209', 'Fabricación de productos de pescado ncp'),
(92, '10301', 'Elaboración de jugos de frutas y hortalizas'),
(93, '10302', 'Elaboración y envase de jaleas, mermeladas y frutas deshidratadas'),
(94, '10309', 'Elaboración de productos de frutas y hortalizas n.c.p.'),
(95, '10401', 'Fabricación de aceites y grasas vegetales y animales comestibles'),
(96, '10402', 'Fabricación de aceites y grasas vegetales y animales no comestibles'),
(97, '10409', 'Servicio de maquilado de aceites'),
(98, '10501', 'Fabricación de productos lácteos excepto sorbetes y quesos sustitutos'),
(99, '10502', 'Fabricación de sorbetes y helados'),
(100, '10503', 'Fabricación de quesos'),
(101, '10611', 'Molienda de cereales'),
(102, '10612', 'Elaboración de cereales para el desayuno y similares'),
(103, '10613', 'Servicios de beneficiado de productos agrícolas ncp (excluye Beneficio de azúcar rama 1072  y beneficio de café rama 0163)'),
(104, '10621', 'Fabricación de almidón'),
(105, '10628', 'Servicio de molienda de maíz húmedo molino para nixtamal'),
(106, '10711', 'Elaboración de tortillas'),
(107, '10712', 'Fabricación de pan, galletas y barquillos'),
(108, '10713', 'Fabricación de repostería'),
(109, '10721', 'Ingenios azucareros'),
(110, '10722', 'Molienda de caña de azúcar para la elaboración de dulces'),
(111, '10723', 'Elaboración de jarabes de azúcar y otros similares'),
(112, '10724', 'Maquilado de azúcar de caña'),
(113, '10730', 'Fabricación de cacao, chocolates y  productos de confitería'),
(114, '10740', 'Elaboración de macarrones, fideos, y productos farináceos similares'),
(115, '10750', 'Elaboración de comidas y platos preparados para la reventa en locales y/o  para exportación'),
(116, '10791', 'Elaboración de productos de café'),
(117, '10792', 'Elaboración de especies, sazonadores y condimentos'),
(118, '10793', 'Elaboración de sopas, cremas y consomé'),
(119, '10794', 'Fabricación de bocadillos tostados y/o fritos'),
(120, '10799', 'Elaboración de productos alimenticios ncp'),
(121, '10800', 'Elaboración de alimentos preparados para animales'),
(122, '11012', 'Fabricación de aguardiente y licores'),
(123, '11020', 'Elaboración de vinos'),
(124, '11030', 'Fabricación de cerveza'),
(125, '11041', 'Fabricación de aguas gaseosas'),
(126, '11042', 'Fabricación y envasado  de agua'),
(127, '11043', 'Elaboración de refrescos'),
(128, '11048', 'Maquilado de aguas gaseosas'),
(129, '11049', 'Elaboración de bebidas no alcohólicas'),
(130, '12000', 'Elaboración de productos de tabaco'),
(131, '13111', 'Preparación de fibras textiles'),
(132, '13112', 'Fabricación de hilados'),
(133, '13120', 'Fabricación de telas'),
(134, '13130', 'Acabado de productos textiles'),
(135, '13910', 'Fabricación de tejidos de punto y  ganchillo'),
(136, '13921', 'Fabricación de productos textiles para el hogar'),
(137, '13922', 'Sacos, bolsas y otros artículos textiles'),
(138, '13929', 'Fabricación de artículos confeccionados con materiales textiles, excepto prendas de vestir n.c.p'),
(139, '13930', 'Fabricación de tapices y alfombras'),
(140, '13941', 'Fabricación de cuerdas de henequén y otras fibras naturales (lazos, pitas)'),
(141, '13942', 'Fabricación de redes de diversos materiales'),
(142, '13948', 'Maquilado de productos trenzables de cualquier material (petates, sillas, etc.)'),
(143, '13991', 'Fabricación de adornos, etiquetas y otros artículos para prendas de vestir'),
(144, '13992', 'Servicio de bordados en artículos y prendas de tela'),
(145, '13999', 'Fabricación de productos textiles ncp'),
(146, '14101', 'Fabricación de ropa  interior, para dormir y similares'),
(147, '14102', 'Fabricación de ropa para niños'),
(148, '14103', 'Fabricación de prendas de vestir para ambos sexos'),
(149, '14104', 'Confección de prendas a medida'),
(150, '14105', 'Fabricación de prendas de vestir para deportes'),
(151, '14106', 'Elaboración de artesanías de uso personal confeccionadas especialmente de materiales textiles'),
(152, '14108', 'Maquilado  de prendas de vestir, accesorios y otros'),
(153, '14109', 'Fabricación de prendas y accesorios de vestir n.c.p.'),
(154, '14200', 'Fabricación de artículos de piel'),
(155, '14301', 'Fabricación de calcetines, calcetas, medias (panty house) y otros similares'),
(156, '14302', 'Fabricación de ropa interior de tejido de punto'),
(157, '14309', 'Fabricación de prendas de vestir de tejido de punto ncp'),
(158, '15110', 'Curtido y adobo de cueros; adobo y teñido de pieles'),
(159, '15121', 'Fabricación de maletas, bolsos de mano y otros artículos de marroquinería'),
(160, '15122', 'Fabricación de monturas, accesorios y vainas talabartería'),
(161, '15123', 'Fabricación de artesanías principalmente de cuero natural y sintético'),
(162, '15128', 'Maquilado de artículos de cuero natural, sintético y de otros materiales'),
(163, '15201', 'Fabricación de calzado'),
(164, '15202', 'Fabricación de partes y accesorios de calzado'),
(165, '15208', 'Maquilado de calzado y partes de calzado'),
(166, '16100', 'Aserradero y acepilladura de madera'),
(167, '16210', 'Fabricación de madera laminada, terciada, enchapada y contrachapada, paneles para la construcción'),
(168, '16220', 'Fabricación de partes y piezas de carpintería para edificios y construcciones'),
(169, '16230', 'Fabricación de envases y recipientes de madera'),
(170, '16292', 'Fabricación de artesanías de madera, semillas,  materiales trenzables'),
(171, '16299', 'Fabricación de productos de madera, corcho, paja y materiales trenzables ncp'),
(172, '17010', 'Fabricación de pasta de madera, papel y cartón'),
(173, '17020', 'Fabricación de papel y cartón ondulado y envases de papel y cartón'),
(174, '17091', 'Fabricación de artículos de papel y cartón de uso personal y doméstico'),
(175, '17092', 'Fabricación de productos de papel ncp'),
(176, '18110', 'Impresión'),
(177, '18120', 'Servicios relacionados con la impresión'),
(178, '18200', 'Reproducción de grabaciones'),
(179, '19100', 'Fabricación de productos de hornos de coque'),
(180, '19201', 'Fabricación de combustible'),
(181, '19202', 'Fabricación de aceites y lubricantes'),
(182, '20111', 'Fabricación de materias primas para la fabricación de colorantes'),
(183, '20112', 'Fabricación de materiales curtientes'),
(184, '20113', 'Fabricación de gases industriales'),
(185, '20114', 'Fabricación de alcohol etílico'),
(186, '20119', 'Fabricación de sustancias químicas básicas'),
(187, '20120', 'Fabricación de abonos y fertilizantes'),
(188, '20130', 'Fabricación de plástico y caucho en formas primarias'),
(189, '20210', 'Fabricación de plaguicidas y otros productos químicos de uso agropecuario'),
(190, '20220', 'Fabricación de pinturas, barnices y productos de revestimiento similares; tintas de imprenta y masillas'),
(191, '20231', 'Fabricación de jabones, detergentes y similares para limpieza'),
(192, '20232', 'Fabricación de perfumes, cosméticos y productos de higiene y cuidado personal, incluyendo tintes, champú, etc.'),
(193, '20291', 'Fabricación de tintas y colores para escribir y pintar; fabricación de cintas para impresoras'),
(194, '20292', 'Fabricación de productos pirotécnicos, explosivos y municiones'),
(195, '20299', 'Fabricación de productos químicos n.c.p.'),
(196, '20300', 'Fabricación de fibras artificiales'),
(197, '21001', 'Manufactura de productos farmacéuticos, sustancias químicas y productos botánicos'),
(198, '21008', 'Maquilado de medicamentos'),
(199, '22110', 'Fabricación de cubiertas y cámaras; renovación y recauchutado de cubiertas'),
(200, '22190', 'Fabricación de otros productos de caucho'),
(201, '22201', 'Fabricación de envases plásticos'),
(202, '22202', 'Fabricación de productos plásticos para uso personal o doméstico'),
(203, '22208', 'Maquila de plásticos'),
(204, '22209', 'Fabricación de productos plásticos n.c.p.'),
(205, '23101', 'Fabricación de vidrio'),
(206, '23102', 'Fabricación de recipientes y envases de vidrio'),
(207, '23108', 'Servicio de maquilado'),
(208, '23109', 'Fabricación de productos de vidrio ncp'),
(209, '23910', 'Fabricación de productos refractarios'),
(210, '23920', 'Fabricación de productos de arcilla para la construcción'),
(211, '23931', 'Fabricación de productos de cerámica y porcelana no refractaria'),
(212, '23932', 'Fabricación de productos de cerámica y porcelana ncp'),
(213, '23940', 'Fabricación de cemento, cal y yeso'),
(214, '23950', 'Fabricación de artículos de hormigón, cemento y yeso'),
(215, '23960', 'Corte, tallado y acabado de la piedra'),
(216, '23990', 'Fabricación de productos minerales no metálicos ncp'),
(217, '24100', 'Industrias básicas de hierro y acero'),
(218, '24200', 'Fabricación de productos primarios de metales preciosos y metales no ferrosos'),
(219, '24310', 'Fundición de hierro y acero'),
(220, '24320', 'Fundición de metales no ferrosos'),
(221, '25111', 'Fabricación de productos metálicos para uso estructural'),
(222, '25118', 'Servicio de maquila para la fabricación de estructuras metálicas'),
(223, '25120', 'Fabricación de tanques, depósitos y recipientes de metal'),
(224, '25130', 'Fabricación de generadores de vapor, excepto calderas de agua caliente  para calefacción central'),
(225, '25200', 'Fabricación de armas y municiones'),
(226, '25910', 'Forjado, prensado, estampado y laminado de metales; pulvimetalurgia'),
(227, '25920', 'Tratamiento y revestimiento de metales'),
(228, '25930', 'Fabricación de artículos de cuchillería, herramientas de mano y artículos de ferretería'),
(229, '25991', 'Fabricación de envases y artículos conexos de metal'),
(230, '25992', 'Fabricación de artículos metálicos de uso personal y/o doméstico'),
(231, '25999', 'Fabricación de productos elaborados de metal ncp'),
(232, '26100', 'Fabricación de componentes electrónicos'),
(233, '26200', 'Fabricación de computadoras y equipo conexo'),
(234, '26300', 'Fabricación de equipo de comunicaciones'),
(235, '26400', 'Fabricación de aparatos  electrónicos de consumo para audio, video radio y televisión'),
(236, '26510', 'Fabricación de instrumentos y aparatos para medir, verificar, ensayar, navegar y de control de procesos industriales'),
(237, '26520', 'Fabricación de relojes y piezas de relojes'),
(238, '26600', 'Fabricación de equipo médico de irradiación y equipo electrónico de uso médico y terapéutico'),
(239, '26700', 'Fabricación de instrumentos de óptica y equipo fotográfico'),
(240, '26800', 'Fabricación de medios magnéticos y ópticos'),
(241, '27100', 'Fabricación de motores, generadores , transformadores eléctricos, aparatos de distribución y control de electricidad'),
(242, '27200', 'Fabricación de pilas, baterías y acumuladores'),
(243, '27310', 'Fabricación de cables de fibra óptica'),
(244, '27320', 'Fabricación de otros  hilos y cables eléctricos'),
(245, '27330', 'Fabricación de dispositivos de cableados'),
(246, '27400', 'Fabricación de equipo eléctrico de iluminación'),
(247, '27500', 'Fabricación de aparatos de uso doméstico'),
(248, '27900', 'Fabricación de otros tipos de equipo eléctrico'),
(249, '28110', 'Fabricación de motores y turbinas, excepto motores para aeronaves, vehículos automotores y motocicletas'),
(250, '28120', 'Fabricación de equipo hidráulico'),
(251, '28130', 'Fabricación de otras bombas, compresores, grifos y válvulas'),
(252, '28140', 'Fabricación de cojinetes, engranajes, trenes de engranajes y piezas de transmisión'),
(253, '28150', 'Fabricación de hornos y quemadores'),
(254, '28160', 'Fabricación de equipo de elevación y manipulación'),
(255, '28170', 'Fabricación de maquinaria y equipo de oficina'),
(256, '28180', 'Fabricación de herramientas manuales'),
(257, '28190', 'Fabricación de otros tipos de maquinaria de uso general'),
(258, '28210', 'Fabricación de maquinaria agropecuaria y forestal'),
(259, '28220', 'Fabricación de máquinas para conformar metales y maquinaria herramienta'),
(260, '28230', 'Fabricación de maquinaria metalúrgica'),
(261, '28240', 'Fabricación de maquinaria para la explotación de minas y canteras y para obras de construcción'),
(262, '28250', 'Fabricación de maquinaria para la elaboración de alimentos, bebidas y tabaco'),
(263, '28260', 'Fabricación de maquinaria para la elaboración de productos textiles, prendas de vestir y cueros'),
(264, '28291', 'Fabricación de máquinas para imprenta'),
(265, '28299', 'Fabricación de maquinaria de uso especial ncp'),
(267, '29100', 'Fabricación vehículos automotores'),
(268, '29200', 'Fabricación de carrocerías para vehículos automotores; fabricación de remolques y semiremolques'),
(269, '29300', 'Fabricación de partes, piezas y accesorios para vehículos automotores'),
(271, '30110', 'Fabricación de buques'),
(272, '30120', 'Construcción y reparación de embarcaciones de recreo'),
(273, '30200', 'Fabricación de locomotoras y de material rodante'),
(274, '30300', 'Fabricación de aeronaves y naves espaciales'),
(275, '30400', 'Fabricación de vehículos militares de combate'),
(276, '30910', 'Fabricación de motocicletas'),
(277, '30920', 'Fabricación de bicicletas y sillones de ruedas para inválidos'),
(278, '30990', 'Fabricación de equipo de transporte ncp'),
(280, '31001', 'Fabricación de colchones y somier'),
(281, '31002', 'Fabricación de muebles y otros productos de madera a medida'),
(282, '31008', 'Servicios de maquilado de muebles'),
(283, '31009', 'Fabricación de muebles ncp'),
(285, '32110', 'Fabricación de joyas platerías y joyerías'),
(286, '32120', 'Fabricación de joyas de imitación (fantasía) y artículos conexos'),
(287, '32200', 'Fabricación de instrumentos musicales'),
(288, '32301', 'Fabricación de artículos de deporte'),
(289, '32308', 'Servicio de maquila de productos deportivos'),
(290, '32401', 'Fabricación de juegos de mesa y de salón'),
(291, '32402', 'Servicio de maquilado de juguetes y juegos'),
(292, '32409', 'Fabricación de juegos y juguetes n.c.p.'),
(293, '32500', 'Fabricación de instrumentos y materiales médicos y odontológicos'),
(294, '32901', 'Fabricación de lápices, bolígrafos, sellos y artículos de librería en general'),
(295, '32902', 'Fabricación de escobas, cepillos, pinceles y similares'),
(296, '32903', 'Fabricación de artesanías de materiales diversos'),
(297, '32904', 'Fabricación de artículos de uso personal y domésticos n.c.p.'),
(298, '32905', 'Fabricación de accesorios para las confecciones y la marroquinería n.c.p.'),
(299, '32908', 'Servicios de maquila ncp'),
(300, '32909', 'Fabricación de productos manufacturados n.c.p.'),
(302, '33110', 'Reparación y mantenimiento de productos elaborados de metal'),
(303, '33120', 'Reparación y mantenimiento de maquinaria'),
(304, '33130', 'Reparación y mantenimiento de equipo electrónico y óptico'),
(305, '33140', 'Reparación y mantenimiento  de equipo eléctrico'),
(306, '33150', 'Reparación y mantenimiento de equipo de transporte, excepto vehículos automotores'),
(307, '33190', 'Reparación y mantenimiento de equipos n.c.p.'),
(308, '33200', 'Instalación de maquinaria y equipo industrial'),
(311, '35101', 'Generación de energía eléctrica'),
(312, '35102', 'Transmisión de energía eléctrica'),
(313, '35103', 'Distribución de energía eléctrica'),
(314, '35200', 'Fabricación de gas, distribución de combustibles gaseosos por tuberías'),
(315, '35300', 'Suministro de vapor y agua caliente'),
(318, '36000', 'Captación, tratamiento y suministro de agua'),
(320, '37000', 'Evacuación de aguas residuales (alcantarillado)'),
(322, '38110', 'Recolección y transporte de desechos sólidos proveniente de hogares y  sector urbano'),
(323, '38120', 'Recolección de desechos peligrosos'),
(324, '38210', 'Tratamiento y eliminación de desechos inicuos'),
(325, '38220', 'Tratamiento y eliminación de desechos peligrosos'),
(326, '38301', 'Reciclaje de desperdicios y desechos textiles'),
(327, '38302', 'Reciclaje de desperdicios y desechos de plástico y caucho'),
(328, '38303', 'Reciclaje de desperdicios y desechos de vidrio'),
(329, '38304', 'Reciclaje de desperdicios y desechos de papel y cartón'),
(330, '38305', 'Reciclaje de desperdicios y desechos metálicos'),
(331, '38309', 'Reciclaje de desperdicios y desechos no metálicos  n.c.p.'),
(333, '39000', 'Actividades de Saneamiento y otros Servicios de Gestión de Desechos'),
(336, '41001', 'Construcción de edificios residenciales'),
(337, '41002', 'Construcción de edificios no residenciales'),
(339, '42100', 'Construcción de carreteras, calles y caminos'),
(340, '42200', 'Construcción de proyectos de servicio público'),
(341, '42900', 'Construcción de obras de ingeniería civil n.c.p.'),
(343, '43110', 'Demolición'),
(344, '43120', 'Preparación de terreno'),
(345, '43210', 'Instalaciones eléctricas'),
(346, '43220', 'Instalación de fontanería, calefacción y aire acondicionado'),
(347, '43290', 'Otras instalaciones para obras de construcción'),
(348, '43300', 'Terminación y acabado de edificios'),
(349, '43900', 'Otras actividades especializadas de construcción'),
(350, '43901', 'Fabricación de techos y materiales diversos'),
(353, '45100', 'Venta de vehículos automotores'),
(354, '45201', 'Reparación mecánica de vehículos automotores'),
(355, '45202', 'Reparaciones eléctricas del automotor y recarga de baterías'),
(356, '45203', 'Enderezado y pintura de vehículos automotores'),
(357, '45204', 'Reparaciones de radiadores, escapes y silenciadores'),
(358, '45205', 'Reparación y reconstrucción de vías, stop y otros artículos de fibra de vidrio'),
(359, '45206', 'Reparación de llantas de vehículos automotores'),
(360, '45207', 'Polarizado de vehículos (mediante la adhesión de papel especial a los vidrios)'),
(361, '45208', 'Lavado y pasteado de vehículos (carwash)'),
(362, '45209', 'Reparaciones de vehículos n.c.p.'),
(363, '45211', 'Remolque de vehículos automotores'),
(364, '45301', 'Venta de partes, piezas y accesorios nuevos para vehículos automotores'),
(365, '45302', 'Venta de partes, piezas y accesorios usados para vehículos automotores'),
(366, '45401', 'Venta de motocicletas'),
(367, '45402', 'Venta de repuestos, piezas y accesorios de motocicletas'),
(368, '45403', 'Mantenimiento y reparación  de motocicletas'),
(370, '46100', 'Venta al por mayor a cambio de retribución o por contrata'),
(371, '46201', 'Venta al por mayor de materias primas agrícolas'),
(372, '46202', 'Venta al por mayor de productos de la silvicultura'),
(373, '46203', 'Venta al por mayor de productos pecuarios y de granja'),
(374, '46211', 'Venta de productos para uso agropecuario'),
(375, '46291', 'Venta al por mayor de granos básicos (cereales, leguminosas)'),
(376, '46292', 'Venta  al por mayor de semillas mejoradas para cultivo'),
(377, '46293', 'Venta  al por mayor de café oro y uva'),
(378, '46294', 'Venta  al por mayor de caña de azúcar'),
(379, '46295', 'Venta al por mayor de flores, plantas  y otros productos naturales'),
(380, '46296', 'Venta al por mayor de productos agrícolas'),
(381, '46297', 'Venta  al por mayor de ganado bovino (vivo)'),
(382, '46298', 'Venta al por mayor de animales porcinos, ovinos, caprino, canículas, apícolas, avícolas vivos'),
(383, '46299', 'Venta de otras especies vivas del reino animal'),
(384, '46301', 'Venta al por mayor de alimentos'),
(385, '46302', 'Venta al por mayor de bebidas'),
(386, '46303', 'Venta al por mayor de tabaco'),
(387, '46371', 'Venta al por mayor de frutas, hortalizas (verduras), legumbres y tubérculos'),
(388, '46372', 'Venta al por mayor de pollos, gallinas destazadas, pavos y otras aves'),
(389, '46373', 'Venta al por mayor de carne bovina y porcina, productos de carne y embutidos'),
(390, '46374', 'Venta  al por mayor de huevos'),
(391, '46375', 'Venta al por mayor de productos lácteos'),
(392, '46376', 'Venta al por mayor de productos farináceos de panadería (pan dulce, cakes, respostería, etc.)'),
(393, '46377', 'Venta al por mayor de pastas alimenticas, aceites y grasas comestibles vegetal y animal'),
(394, '46378', 'Venta al por mayor de sal comestible'),
(395, '46379', 'Venta al por mayor de azúcar'),
(396, '46391', 'Venta al por mayor de abarrotes (vinos, licores, productos alimenticios envasados, etc.)'),
(397, '46392', 'Venta al por mayor de aguas gaseosas'),
(398, '46393', 'Venta al por mayor de agua purificada'),
(399, '46394', 'Venta al por mayor de refrescos y otras bebidas, líquidas o en polvo'),
(400, '46395', 'Venta al por mayor de cerveza y licores'),
(401, '46396', 'Venta al por mayor de hielo'),
(402, '46411', 'Venta al por mayor de hilados, tejidos y productos textiles de mercería'),
(403, '46412', 'Venta al por mayor de artículos textiles excepto confecciones para el hogar'),
(404, '46413', 'Venta al por mayor de confecciones textiles para el hogar'),
(405, '46414', 'Venta al por mayor de prendas de vestir y accesorios de vestir'),
(406, '46415', 'Venta al por mayor de ropa usada'),
(407, '46416', 'Venta al por mayor de calzado'),
(408, '46417', 'Venta al por mayor de artículos de marroquinería y talabartería'),
(409, '46418', 'Venta al por mayor de artículos de peletería'),
(410, '46419', 'Venta al por mayor de otros artículos textiles n.c.p.'),
(411, '46471', 'Venta al por mayor de instrumentos musicales'),
(412, '46472', 'Venta al por mayor de colchones, almohadas, cojines, etc.'),
(413, '46473', 'Venta al por mayor de artículos de aluminio para el hogar y para otros usos'),
(414, '46474', 'Venta al por mayor de depósitos y otros artículos plásticos para el hogar y otros usos, incluyendo los desechables de durapax  y no desechables'),
(415, '46475', 'Venta al por mayor de cámaras fotográficas, accesorios y materiales'),
(416, '46482', 'Venta al por mayor de medicamentos, artículos y otros productos de uso veterinario'),
(417, '46483', 'Venta al por mayor de productos y artículos de belleza  y de  uso personal'),
(418, '46484', 'Venta de produtos farmacéuticos y medicinales'),
(419, '46491', 'Venta al por mayor de productos medicinales, cosméticos, perfumería y productos de limpieza'),
(420, '46492', 'Venta al por mayor de relojes y artículos de joyería'),
(421, '46493', 'Venta al por mayor de electrodomésticos y artículos del hogar excepto bazar;  artículos de iluminación'),
(422, '46494', 'Venta al por mayor de artículos de bazar y similares'),
(423, '46495', 'Venta al por mayor de artículos de óptica'),
(424, '46496', 'Venta al por mayor de revistas, periódicos, libros, artículos de librería y artículos de papel y cartón en general'),
(425, '46497', 'Venta de artículos deportivos, juguetes y rodados'),
(426, '46498', 'Venta al por mayor de productos usados para el hogar o el uso personal'),
(427, '46499', 'Venta al por mayor de enseres domésticos y de uso personal n.c.p.'),
(428, '46500', 'Venta al por mayor de bicicletas, partes, accesorios y otros'),
(429, '46510', 'Venta al por mayor de computadoras, equipo periférico y programas informáticos'),
(430, '46520', 'Venta al por mayor de equipos de comunicación'),
(431, '46530', 'Venta al por mayor de maquinaria y equipo agropecuario, accesorios, partes y suministros'),
(432, '46590', 'Venta de equipos e instrumentos de uso profesional y cientÍfico y aparatos de medida y control'),
(433, '46591', 'Venta al por mayor de maquinaria equipo, accesorios y materiales para la industria de la madera y  sus  productos'),
(434, '46592', 'Venta al por mayor de maquinaria,  equipo, accesorios y materiales para las industria gráfica y del papel, cartón y productos de papel y cartón'),
(435, '46593', 'Venta al por mayor de maquinaria, equipo, accesorios y materiales para la  industria de  productos químicos, plástico y caucho'),
(436, '46594', 'Venta al por mayor de maquinaria, equipo, accesorios y materiales para la industria metálica y de sus productos'),
(437, '46595', 'Venta al por mayor de equipamiento para uso médico, odontológico, veterinario y servicios conexos'),
(438, '46596', 'Venta al por mayor de maquinaria, equipo, accesorios y partes para la industria de la alimentación'),
(439, '46597', 'Venta al por mayor de maquinaria, equipo, accesorios y partes para la industria textil, confecciones y cuero'),
(440, '46598', 'Venta al por mayor de maquinaria, equipo y accesorios para la construcción y explotación de minas y canteras'),
(441, '46599', 'Venta al por mayor de otro tipo de maquinaria y equipo con sus accesorios y partes'),
(442, '46610', 'Venta al por mayor  de otros combustibles sólidos, líquidos, gaseosos y de productos conexos'),
(443, '46612', 'Venta al por mayor de combustibles para automotores, aviones, barcos, maquinaria  y otros'),
(444, '46613', 'Venta al por mayor de lubricantes, grasas y  otros aceites para automotores, maquinaria  industrial, etc.'),
(445, '46614', 'Venta al por mayor de gas propano'),
(446, '46615', 'Venta al  por mayor de leña y carbón'),
(447, '46620', 'Venta al por mayor de metales y minerales metalíferos'),
(448, '46631', 'Venta al por mayor de puertas, ventanas, vitrinas y similares'),
(449, '46632', 'Venta al por mayor de artículos de ferretería y pinturerías'),
(450, '46633', 'Vidrierías'),
(451, '46634', 'Venta al por mayor de maderas'),
(452, '46639', 'Venta al por mayor de materiales para la construcción n.c.p.'),
(453, '46691', 'Venta al por mayor de sal industrial sin yodar'),
(454, '46692', 'Venta al por mayor de productos intermedios y desechos de origen textil'),
(455, '46693', 'Venta al por mayor de productos intermedios y desechos de origen metálico'),
(456, '46694', 'Venta al por mayor de productos intermedios y desechos de papel y cartón'),
(458, '46695', 'Venta al por mayor fertilizantes, abonos, agroquímicos y productos similares'),
(459, '46696', 'Venta al por mayor de productos intermedios y desechos de origen plástico'),
(460, '46697', 'Venta al por mayor de tintas para imprenta, productos curtientes y materias y productos colorantes'),
(461, '46698', 'Venta de productos intermedios y desechos de origen químico y de caucho'),
(462, '46699', 'Venta al por mayor de productos intermedios y desechos ncp'),
(463, '46701', 'Venta de algodón en oro'),
(464, '46900', 'Venta al por mayor de otros productos'),
(465, '46901', 'Venta al por mayor de cohetes y otros productos pirotécnicos'),
(466, '46902', 'Venta al por mayor de articulos diversos para consumo humano'),
(467, '46903', 'Venta al por mayor de armas de fuego, municiones y accesorios'),
(468, '46904', 'Venta al por mayor de toldos y tiendas de campaña de cualquier material'),
(469, '46905', 'Venta al por mayor de exhibidores publicitarios y rótulos'),
(470, '46906', 'Venta al por mayor de artículos promociónales  diversos'),
(472, '47111', 'Venta en supermercados'),
(473, '47112', 'Venta en tiendas de articulos de primera necesidad'),
(474, '47119', 'Almacenes (venta de diversos artículos)'),
(475, '47190', 'Venta al por menor de otros productos en comercios no especializados'),
(476, '47199', 'Venta de establecimientos no especializados con surtido compuesto principalmente de alimentos, bebidas y tabaco'),
(477, '47211', 'Venta al por menor  de frutas y hortalizas'),
(478, '47212', 'Venta al por menor de carnes, embutidos y productos de granja'),
(479, '47213', 'Venta al por menor de pescado y mariscos'),
(480, '47214', 'Venta al por menor de productos  lácteos'),
(481, '47215', 'Venta al por menor de productos de panadería, repostería y galletas'),
(482, '47216', 'Venta al por menor de huevos'),
(483, '47217', 'Venta al por menor de carnes y productos cárnicos'),
(484, '47218', 'Venta al por menor  de granos básicos y otros'),
(485, '47219', 'Venta al por menor de alimentos n.c.p.'),
(486, '47221', 'Venta al por menor de hielo'),
(487, '47223', 'Venta de bebidas no alcohólicas, para su consumo fuera del establecimiento'),
(488, '47224', 'Venta de bebidas alcohólicas, para su consumo fuera del establecimiento'),
(489, '47225', 'Venta de bebidas alcohólicas para su consumo dentro del establecimiento'),
(490, '47230', 'Venta al por menor de tabaco'),
(491, '47300', 'Venta de combustibles, lubricantes y otros (gasolineras)'),
(492, '47411', 'Venta al por menor de computadoras y equipo periférico'),
(493, '47412', 'Venta de equipo y accesorios de telecomunicación'),
(494, '47420', 'Venta al por menor de equipo de audio y video'),
(495, '47510', 'Venta al por menor de hilados, tejidos y productos textiles de mercería; confecciones para el hogar y textiles n.c.p.'),
(496, '47521', 'Venta al por menor de productos de madera'),
(497, '47522', 'Venta al por menor de artículos de ferretería'),
(498, '47523', 'Venta al por menor de productos de pinturerías'),
(499, '47524', 'Venta al por menor en vidrierías'),
(500, '47529', 'Venta al por menor de materiales de construcción y artículos conexos'),
(501, '47530', 'Venta al por menor de tapices, alfombras y revestimientos de paredes y pisos en comercios  especializados'),
(502, '47591', 'Venta al por menor de muebles'),
(503, '47592', 'Venta al por menor de artículos de bazar'),
(504, '47593', 'Venta al por menor de aparatos electrodomésticos, repuestos y accesorios'),
(505, '47594', 'Venta al por menor de artículos eléctricos y de iluminación'),
(506, '47598', 'Venta al por menor de instrumentos musicales'),
(507, '47610', 'Venta al por menor de libros, periódicos y artículos de papelería en comercios especializados'),
(508, '47620', 'Venta al por menor de discos láser, cassettes, cintas de video y otros'),
(509, '47630', 'Venta al por menor de productos y equipos de deporte'),
(510, '47631', 'Venta al por menor de bicicletas, accesorios y repuestos'),
(511, '47640', 'Venta al por menor de juegos y juguetes  en comercios especializados'),
(512, '47711', 'Venta al por menor de prendas de vestir y accesorios de vestir'),
(513, '47712', 'Venta al por menor de calzado'),
(514, '47713', 'Venta al por menor de artículos de peletería, marroquinería y talabartería'),
(515, '47721', 'Venta al por menor de medicamentos farmacéuticos y otros materiales y artículos de uso médico, odontológico y veterinario'),
(516, '47722', 'Venta al por menor de productos cosméticos y de tocador'),
(517, '47731', 'Venta al por menor de productos de joyería, bisutería, óptica, relojería'),
(518, '47732', 'Venta al por menor de plantas, semillas, animales y artículos conexos'),
(519, '47733', 'Venta al por menor de combustibles de uso doméstico (gas propano y gas licuado)'),
(520, '47734', 'Venta al por menor de artesanías, artículos cerámicos y recuerdos en general'),
(521, '47735', 'Venta al por menor de ataúdes, lápidas y cruces, trofeos, artículos religiosos en general'),
(522, '47736', 'Venta al por menor de armas de fuego, municiones y accesorios'),
(523, '47737', 'Venta al por menor de artículos de cohetería y pirotécnicos'),
(524, '47738', 'Venta al por menor de artículos desechables de uso personal y doméstico (servilletas, papel higiénico, pañales, toallas sanitarias, etc.)'),
(525, '47739', 'Venta al por menor de otros productos  n.c.p.'),
(526, '47741', 'Venta al por menor de artículos usados'),
(527, '47742', 'Venta al por menor de textiles y confecciones usados'),
(528, '47743', 'Venta al por menor de libros, revistas, papel y cartón usados'),
(529, '47749', 'Venta al por menor de productos usados n.c.p.'),
(530, '47811', 'Venta al por menor de frutas, verduras y hortalizas'),
(531, '47812', 'Venta al por menor de carnes, embutidos y productos de granja'),
(532, '47814', 'Venta al por menor de productos lácteos'),
(533, '47815', 'Venta al por menor de productos de panadería, galletas y similares'),
(534, '47816', 'Venta al por menor de bebidas'),
(535, '47818', 'Venta al por menor en tiendas de mercado y puestos'),
(536, '47821', 'Venta al por menor de hilados, tejidos y productos textiles de mercería en puestos de mercados y ferias'),
(537, '47822', 'Venta al por menor de artículos textiles excepto confecciones para el hogar en puestos de mercados y ferias'),
(538, '47823', 'Venta al por menor de confecciones textiles para el hogar en puestos de mercados y ferias'),
(539, '47824', 'Venta al por menor de prendas de vestir, accesorios de vestir y similares en puestos de mercados y ferias'),
(540, '47825', 'Venta al por menor de ropa usada'),
(541, '47826', 'Venta al por menor de calzado, artículos de marroquinería y talabartería en puestos de mercados y ferias'),
(542, '47827', 'Venta al por menor de artículos de marroquinería y talabartería en puestos de mercados y ferias'),
(543, '47829', 'Venta al por menor de artículos textiles ncp en puestos de mercados y ferias'),
(544, '47891', 'Venta al por menor de animales, flores y productos conexos en puestos de feria y mercados'),
(545, '47892', 'Venta al por menor de productos medicinales, cosméticos, de tocador y de limpieza en puestos de ferias y mercados'),
(546, '47893', 'Venta al por menor de artículos de bazar en puestos de ferias y mercados'),
(547, '47894', 'Venta al por menor de artículos de papel, envases, libros, revistas y conexos en puestos de feria y mercados'),
(548, '47895', 'Venta al por menor de materiales de construcción, electrodomésticos, accesorios para autos y similares en puestos de feria y mercados'),
(549, '47896', 'Venta al por menor de equipos accesorios para las comunicaciones en puestos de feria y mercados'),
(550, '47899', 'Venta al por menor en puestos de ferias y mercados n.c.p.'),
(551, '47910', 'Venta al por menor por correo o Internet'),
(552, '47990', 'Otros tipos de venta al por menor no realizada, en almacenes, puestos de venta o mercado'),
(555, '49110', 'Transporte interurbano de pasajeros  por ferrocarril'),
(556, '49120', 'Transporte de carga por ferrocarril'),
(557, '49211', 'Transporte de pasajeros urbanos e interurbano mediante buses'),
(558, '49212', 'Transporte de pasajeros interdepartamental mediante microbuses'),
(559, '49213', 'Transporte de pasajeros urbanos e interurbano mediante microbuses'),
(560, '49214', 'Transporte de pasajeros interdepartamental mediante buses'),
(561, '49221', 'Transporte internacional de pasajeros'),
(562, '49222', 'Transporte de pasajeros mediante taxis y autos con chofer'),
(563, '49223', 'Transporte escolar'),
(564, '49225', 'Transporte de pasajeros para excursiones'),
(565, '49226', 'Servicios de transporte de personal'),
(566, '49229', 'Transporte de pasajeros por vía terrestre ncp'),
(567, '49231', 'Transporte de carga urbano'),
(568, '49232', 'Transporte nacional de carga'),
(569, '49233', 'Transporte de carga  internacional'),
(570, '49234', 'Servicios de  mudanza'),
(571, '49235', 'Alquiler de vehículos de carga con conductor'),
(572, '49300', 'Transporte por oleoducto o gasoducto'),
(574, '50110', 'Transporte de pasajeros marítimo y de cabotaje'),
(575, '50120', 'Transporte de carga marítimo y de cabotaje'),
(576, '50211', 'Transporte de pasajeros por vías de navegación interiores'),
(577, '50212', 'Alquiler de equipo de transporte de pasajeros por vías de navegación interior con conductor'),
(578, '50220', 'Transporte de carga por vías de navegación interiores'),
(580, '51100', 'Transporte aéreo de pasajeros'),
(581, '51201', 'Transporte de carga por vía aérea'),
(582, '51202', 'Alquiler de equipo de aerotransporte  con operadores para el propósito de transportar carga'),
(584, '52101', 'Alquiler de instalaciones de almacenamiento en zonas francas'),
(585, '52102', 'Alquiler de silos para conservación y almacenamiento de granos'),
(586, '52103', 'Alquiler de instalaciones con refrigeración para almacenamiento y conservación de alimentos y otros productos'),
(587, '52109', 'Alquiler de bodegas para almacenamiento y depósito n.c.p.'),
(588, '52211', 'Servicio de garaje y estacionamiento'),
(589, '52212', 'Servicios de terminales para el transporte por vía terrestre'),
(590, '52219', 'Servicios para el transporte por vía terrestre n.c.p.'),
(591, '52220', 'Servicios para el transporte acuático'),
(592, '52230', 'Servicios para el transporte aéreo'),
(593, '52240', 'Manipulación de carga'),
(594, '52290', 'Servicios para el transporte ncp'),
(595, '52291', 'Agencias de tramitaciones aduanales'),
(597, '53100', 'Servicios de  correo nacional'),
(598, '53200', 'Actividades de correo distintas a las actividades postales nacionales'),
(601, '55101', 'Actividades de alojamiento para estancias cortas'),
(602, '55102', 'Hoteles'),
(603, '55200', 'Actividades de campamentos, parques de vehículos de recreo y parques de caravanas'),
(604, '55900', 'Alojamiento n.c.p.'),
(606, '56101', 'Restaurantes'),
(607, '56106', 'Pupusería'),
(608, '56107', 'Actividades varias de restaurantes'),
(609, '56108', 'Comedores'),
(610, '56109', 'Merenderos ambulantes'),
(611, '56210', 'Preparación de comida para eventos especiales'),
(612, '56291', 'Servicios de provisión de comidas por contrato'),
(613, '56292', 'Servicios de concesión de cafetines y chalet en empresas e instituciones'),
(614, '56299', 'Servicios de preparación de comidas ncp'),
(615, '56301', 'Servicio de expendio de bebidas en salones y bares'),
(616, '56302', 'Servicio de expendio de bebidas en puestos callejeros, mercados y ferias'),
(619, '58110', 'Edición de libros, folletos, partituras y otras ediciones distintas a estas'),
(620, '58120', 'Edición de directorios y listas de correos'),
(621, '58130', 'Edición de periódicos, revistas y otras publicaciones periódicas'),
(622, '58190', 'Otras actividades de edición'),
(623, '58200', 'Edición de programas informáticos (software)'),
(625, '59110', 'Actividades de producción cinematográfica'),
(626, '59120', 'Actividades de post producción de películas, videos y programas  de televisión'),
(627, '59130', 'Actividades de distribución de películas cinematográficas, videos y programas de televisión'),
(628, '59140', 'Actividades de exhibición de películas cinematográficas y cintas de vídeo'),
(629, '59200', 'Actividades de edición y grabación de música'),
(631, '60100', 'Servicios de difusiones de radio'),
(632, '60201', 'Actividades de programación y difusión de televisión abierta'),
(633, '60202', 'Actividades de suscripción y difusión de televisión por cable y/o suscripción'),
(634, '60299', 'Servicios de televisión, incluye televisión por cable'),
(635, '60900', 'Programación y transmisión de radio y televisión'),
(637, '61101', 'Servicio de telefonía'),
(638, '61102', 'Servicio de Internet '),
(639, '61103', 'Servicio de telefonía fija'),
(640, '61109', 'Servicio de Internet n.c.p.'),
(641, '61201', 'Servicios de telefonía celular'),
(642, '61202', 'Servicios de Internet inalámbrico'),
(643, '61209', 'Servicios de telecomunicaciones inalámbrico n.c.p.'),
(644, '61301', 'Telecomunicaciones satelitales'),
(645, '61309', 'Comunicación vía satélite n.c.p.'),
(646, '61900', 'Actividades de telecomunicación n.c.p.'),
(648, '62010', 'Programación Informática'),
(649, '62020', 'Consultorias y gestión de servicios informáticos'),
(650, '62090', 'Otras actividades de tecnología de información y servicios de computadora'),
(652, '63110', 'Procesamiento de datos y actividades relacionadas'),
(653, '63120', 'Portales WEB'),
(654, '63910', 'Servicios de Agencias de Noticias'),
(655, '63990', 'Otros servicios de información  n.c.p.'),
(658, '64110', 'Servicios provistos por el Banco Central de El salvador'),
(659, '64190', 'Bancos'),
(660, '64192', 'Entidades dedicadas al envío de remesas'),
(661, '64199', 'Otras entidades financieras'),
(662, '64200', 'Actividades de sociedades de cartera'),
(663, '64300', 'Fideicomisos, fondos y otras fuentes de financiamiento'),
(664, '64910', 'Arrendamiento financieros'),
(665, '64920', 'Asociaciones cooperativas de ahorro y crédito dedicadas a la intermediación financiera'),
(666, '64921', 'Instituciones emisoras de tarjetas de crédito y otros'),
(667, '64922', 'Tipos de crédito ncp'),
(668, '64928', 'Prestamistas y casas de empeño'),
(669, '64990', 'Actividades de servicios financieros, excepto la financiación de planes de seguros y de pensiones n.c.p.'),
(671, '65110', 'Planes de seguros de vida'),
(672, '65120', 'Planes de seguro excepto de vida'),
(673, '65199', 'Seguros generales de todo tipo'),
(674, '65200', 'Planes se seguro'),
(675, '65300', 'Planes de pensiones'),
(677, '66110', 'Administración de mercados financieros (Bolsa de Valores)'),
(678, '66120', 'Actividades bursátiles (Corredores de Bolsa)'),
(679, '66190', 'Actividades auxiliares de la intermediación financiera ncp'),
(680, '66210', 'Evaluación de riesgos y daños'),
(681, '66220', 'Actividades de agentes y corredores de seguros'),
(682, '66290', 'Otras actividades auxiliares de seguros y fondos de pensiones'),
(683, '66300', 'Actividades de administración de fondos'),
(686, '68101', 'Servicio de alquiler y venta de lotes en cementerios'),
(687, '68109', 'Actividades inmobiliarias realizadas con bienes propios o arrendados n.c.p.'),
(688, '68200', 'Actividades Inmobiliarias Realizadas a Cambio de una Retribución o por Contrata'),
(691, '69100', 'Actividades jurídicas'),
(692, '69200', 'Actividades de contabilidad, teneduría de libros y auditoría; asesoramiento en materia de impuestos'),
(694, '70100', 'Actividades de oficinas centrales de sociedades de cartera'),
(695, '70200', 'Actividades de consultoria en gestión empresarial'),
(697, '71101', 'Servicios de arquitectura y planificación urbana y servicios conexos'),
(698, '71102', 'Servicios de ingeniería'),
(699, '71103', 'Servicios de agrimensura, topografía, cartografía, prospección y geofísica y servicios conexos'),
(700, '71200', 'Ensayos y análisis técnicos'),
(702, '72100', 'Investigaciones y desarrollo experimental en el campo de las ciencias naturales y la ingeniería'),
(703, '72199', 'Investigaciones científicas'),
(704, '72200', 'Investigaciones y desarrollo experimental en el campo de las ciencias sociales y las humanidades científica y desarrollo'),
(706, '73100', 'Publicidad'),
(707, '73200', 'Investigación de mercados y realización de encuestas de opinión pública'),
(709, '74100', 'Actividades de diseño especializado'),
(710, '74200', 'Actividades de fotografía'),
(711, '74900', 'Servicios profesionales y científicos ncp'),
(713, '75000', 'Actividades veterinarias'),
(716, '77101', 'Alquiler de equipo de transporte terrestre'),
(717, '77102', 'Alquiler de equipo de transporte acuático'),
(718, '77103', 'Alquiler de equipo de transporte  por vía aérea'),
(719, '77210', 'Alquiler y arrendamiento de equipo de recreo y deportivo'),
(720, '77220', 'Alquiler de cintas de video y discos'),
(721, '77290', 'Alquiler de otros efectos personales y enseres domésticos'),
(722, '77300', 'Alquiler de maquinaria y equipo'),
(723, '77400', 'Arrendamiento de productos de propiedad intelectual'),
(725, '78100', 'Obtención y dotación de personal'),
(726, '78200', 'Actividades de las agencias de trabajo temporal'),
(727, '78300', 'Dotación de recursos humanos y gestión; gestión de las funciones de recursos humanos'),
(729, '79110', 'Actividades de agencias de viajes y organizadores de viajes; actividades de asistencia a turistas'),
(730, '79120', 'Actividades de los operadores turísticos'),
(731, '79900', 'Otros servicios de reservas y actividades relacionadas'),
(733, '80100', 'Servicios de seguridad privados'),
(734, '80201', 'Actividades de servicios de sistemas de seguridad'),
(735, '80202', 'Actividades para la prestación de sistemas de seguridad'),
(736, '80300', 'Actividades de investigación'),
(738, '81100', 'Actividades combinadas de mantenimiento de edificios e instalaciones'),
(739, '81210', 'Limpieza general de edificios'),
(740, '81290', 'Otras actividades combinadas de mantenimiento de edificios e instalaciones ncp'),
(741, '81300', 'Servicio de jardinería'),
(743, '82110', 'Servicios administrativos de oficinas'),
(744, '82190', 'Servicio de fotocopiado y similares, excepto en imprentas'),
(745, '82200', 'Actividades de las centrales de llamadas (call center)'),
(746, '82300', 'Organización de convenciones y ferias de negocios'),
(747, '82910', 'Actividades de agencias de cobro y oficinas de crédito'),
(748, '82921', 'Servicios de envase y empaque de productos alimenticios'),
(749, '82922', 'Servicios de envase y empaque de productos medicinales'),
(750, '82929', 'Servicio de envase y empaque ncp'),
(751, '82990', 'Actividades de apoyo empresariales ncp'),
(754, '84110', 'Actividades de la Administración Pública en general'),
(755, '84111', 'Alcaldías Municipales'),
(756, '84120', 'Regulación de las actividades de prestación de servicios sanitarios, educativos, culturales y otros servicios sociales, excepto seguridad social'),
(757, '84130', 'Regulación y facilitación de la actividad económica'),
(758, '84210', 'Actividades de administración y funcionamiento del Ministerio de Relaciones Exteriores'),
(759, '84220', 'Actividades de defensa'),
(760, '84230', 'Actividades de mantenimiento del orden público y de seguridad'),
(761, '84300', 'Actividades de planes de seguridad social de afiliación obligatoria'),
(764, '85101', 'Guardería educativa'),
(765, '85102', 'Enseñanza preescolar o parvularia'),
(766, '85103', 'Enseñanza primaria'),
(767, '85104', 'Servicio de educación preescolar y primaria integrada'),
(768, '85211', 'Enseñanza secundaria tercer ciclo (7°, 8° y 9° )'),
(769, '85212', 'Enseñanza secundaria  de formación general  bachillerato'),
(770, '85221', 'Enseñanza secundaria de formación técnica y profesional'),
(771, '85222', 'Enseñanza secundaria de formación técnica y profesional integrada con enseñanza primaria'),
(772, '85301', 'Enseñanza superior universitaria'),
(773, '85302', 'Enseñanza superior no universitaria'),
(774, '85303', 'Enseñanza superior integrada a educación secundaria y/o primaria'),
(775, '85410', 'Educación deportiva y recreativa'),
(776, '85420', 'Educación cultural'),
(777, '85490', 'Otros tipos de enseñanza n.c.p.'),
(778, '85499', 'Enseñanza formal'),
(779, '85500', 'Servicios de apoyo a la enseñanza'),
(782, '86100', 'Actividades de hospitales'),
(783, '86201', 'Clínicas médicas'),
(784, '86202', 'Servicios de Odontología'),
(785, '86203', 'Servicios médicos'),
(786, '86901', 'Servicios de análisis y estudios de diagnóstico'),
(787, '86902', 'Actividades de atención de la salud humana'),
(788, '86909', 'Otros Servicio relacionados con la salud ncp'),
(790, '87100', 'Residencias de ancianos con atención de enfermería');
INSERT INTO `giroMH` (`id`, `codigo`, `descripcion`) VALUES
(791, '87200', 'Instituciones dedicadas al tratamiento del retraso mental, problemas de salud mental y el uso indebido de sustancias nocivas'),
(792, '87300', 'Instituciones dedicadas al cuidado de ancianos y discapacitados'),
(793, '87900', 'Actividades de asistencia a niños y jóvenes'),
(794, '87901', 'Otras actividades de atención en instituciones'),
(796, '88100', 'Actividades de asistencia sociales sin alojamiento para ancianos y discapacitados'),
(797, '88900', 'servicios sociales sin alojamiento ncp'),
(800, '90000', 'Actividades creativas artísticas y de esparcimiento'),
(802, '91010', 'Actividades de bibliotecas y archivos'),
(803, '91020', 'Actividades de museos y preservación de lugares y edificios históricos'),
(804, '91030', 'Actividades de jardínes botánicos, zoológicos y de reservas naturales'),
(806, '92000', 'Actividades de juegos y apuestas'),
(808, '93110', 'Gestión de instalaciones deportivas'),
(809, '93120', 'Actividades de clubes deportivos'),
(810, '93190', 'Otras actividades deportivas'),
(811, '93210', 'Actividades de parques de atracciones y parques temáticos'),
(812, '93291', 'Discotecas y salas de baile'),
(813, '93298', 'Centros vacacionales'),
(814, '93299', 'Actividades de esparcimiento ncp'),
(817, '94110', 'Actividades de organizaciones empresariales y de empleadores'),
(818, '94120', 'Actividades de organizaciones profesionales'),
(819, '94200', 'Actividades de sindicatos'),
(820, '94910', 'Actividades de organizaciones religiosas'),
(821, '94920', 'Actividades de organizaciones políticas'),
(822, '94990', 'Actividades de asociaciones n.c.p.'),
(824, '95110', 'Reparación de computadoras y equipo periférico'),
(825, '95120', 'Reparación de equipo de comunicación'),
(826, '95210', 'Reparación de aparatos electrónicos de consumo'),
(827, '95220', 'Reparación de aparatos doméstico y equipo de hogar y jardín'),
(828, '95230', 'Reparación de calzado y artículos de cuero'),
(829, '95240', 'Reparación de muebles y accesorios para el hogar'),
(830, '95291', 'Reparación de Instrumentos musicales'),
(831, '95292', 'Servicios de cerrajería y copiado de llaves'),
(832, '95293', 'Reparación de joyas y relojes'),
(833, '95294', 'Reparación de bicicletas, sillas de ruedas y rodados n.c.p.'),
(834, '95299', 'Reparaciones de enseres personales n.c.p.'),
(836, '96010', 'Lavado y limpieza de prendas de tela y de piel, incluso la limpieza en seco'),
(837, '96020', 'Peluquería y otros tratamientos de belleza'),
(838, '96030', 'Pompas fúnebres y actividades conexas'),
(839, '96091', 'Servicios de sauna y otros servicios para la estética corporal n.c.p.'),
(840, '96092', 'Servicios n.c.p.'),
(843, '97000', 'Actividad de los hogares en calidad de empleadores de personal doméstico'),
(845, '98100', 'Actividades indiferenciadas de producción de bienes de los hogares privados para uso propio'),
(846, '98200', 'Actividades indiferenciadas de producción de servicios de los hogares privados para uso propio'),
(849, '99000', 'Actividades de organizaciones y órganos extraterritoriales'),
(852, '10001', 'Empleados'),
(853, '10002', 'Jubilado'),
(854, '10003', 'Estudiante'),
(855, '10004', 'Desempleado'),
(856, '10005', 'Otros');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuestosMH`
--

CREATE TABLE `impuestosMH` (
  `id` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `valor` decimal(10,4) NOT NULL,
  `resumen` tinyint(1) NOT NULL,
  `cuerpo` tinyint(1) NOT NULL,
  `advalorem` tinyint(1) NOT NULL,
  `cantidad` tinyint(1) NOT NULL,
  `porcentaje` tinyint(1) NOT NULL,
  `alias` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `impuestosMH`
--

INSERT INTO `impuestosMH` (`id`, `codigo`, `descripcion`, `valor`, `resumen`, `cuerpo`, `advalorem`, `cantidad`, `porcentaje`, `alias`) VALUES
(1, '20', ' Impuesto al Valor Agregado 13%', '13.0000', 1, 0, 0, 0, 1, 'IVA'),
(2, 'C3', ' Impuesto al Valor Agregado (exportaciones) 0%', '0.0000', 1, 0, 0, 0, 0, ''),
(3, '59', ' Turismo: por alojamiento (5%)', '5.0000', 1, 0, 0, 0, 1, ''),
(4, '71', ' Turismo: salida del país por vía aérea $7.00', '7.0000', 1, 0, 0, 1, 0, ''),
(5, 'D1', ' FOVIAL ($0.20 Ctvs. por galón)', '0.2000', 1, 0, 0, 1, 0, 'FOVIAL'),
(6, 'C8', ' COTRANS ($0.10 Ctvs. por galón)', '0.2000', 1, 0, 0, 1, 0, 'COTRANS'),
(7, 'D5', ' Otras tasas casos especiales', '0.0000', 1, 0, 0, 0, 0, ''),
(8, 'D4', ' Otros impuestos casos especiales', '0.0000', 1, 0, 0, 0, 0, ''),
(9, 'A8', ' Impuesto Especial al Combustible (0%, 0.5%, 1%)', '0.0000', 0, 1, 0, 0, 1, ''),
(10, '57', ' Impuesto industria de Cemento', '0.0000', 0, 1, 0, 0, 0, ''),
(11, '90', ' Impuesto especial a la primera matrícula', '0.0000', 0, 1, 0, 0, 0, ''),
(12, 'D4', ' Otros impuestos casos especiales', '0.0000', 0, 1, 0, 0, 0, ''),
(13, 'D5', ' Otras tasas casos especiales', '0.0000', 0, 1, 0, 0, 0, ''),
(14, 'A6', 'Impuesto ad- valorem, armas de fuego, municiones explosivas y artículos similares ', '0.0000', 1, 1, 0, 0, 0, ''),
(15, 'C5', 'Impuesto ad- valorem por diferencial de precios de bebidas alcohólicas (8%) ', '0.0000', 1, 0, 1, 0, 0, ''),
(16, 'C6', 'Impuesto ad- valorem por diferencial de precios al tabaco cigarrillos (39%) ', '0.0000', 1, 0, 1, 0, 0, ''),
(17, 'C7', 'Impuesto ad- valorem por diferencial de precios al tabaco cigarros (100%) ', '0.0000', 1, 0, 1, 0, 0, ''),
(18, '19', 'Fabricante de Bebidas Gaseosas, Isotónicas, Deportivas, Fortificantes, Energizante o Estimulante', '0.0000', 1, 0, 1, 0, 0, ''),
(19, '28', 'Importador de Bebidas Gaseosas, Isotónicas, Deportivas, Fortificantes, Energizante o Estimulante ', '0.0000', 1, 0, 1, 0, 0, ''),
(20, '31', 'Detallistas o Expendedores de Bebidas Alcohólicas', '0.0000', 1, 0, 1, 0, 0, ''),
(21, '32', 'Fabricante de Cerveza', '0.0000', 1, 0, 1, 0, 0, ''),
(22, '33', 'Importador de Cerveza', '0.0000', 1, 0, 1, 0, 0, ''),
(23, '34', 'Fabricante de Productos de Tabaco', '0.0000', 1, 0, 1, 0, 0, ''),
(24, '35', 'Importador de Productos de Tabaco', '0.0000', 1, 0, 1, 0, 0, ''),
(25, '36', 'Fabricante de Armas de Fuego, Municiones y Artículos Similares', '0.0000', 1, 0, 1, 0, 0, ''),
(26, '37', 'Importador de Arma de Fuego, Munición y Artículos. Similares', '0.0000', 1, 0, 1, 0, 0, ''),
(27, '38', 'Fabricante de Explosivos', '0.0000', 1, 0, 1, 0, 0, ''),
(28, '39', 'Importador de Explosivos', '0.0000', 1, 0, 1, 0, 0, ''),
(29, '42', 'Fabricante de Productos Pirotécnicos', '0.0000', 1, 0, 1, 0, 0, ''),
(30, '43', 'Importador de Productos Pirotécnicos', '0.0000', 1, 0, 1, 0, 0, ''),
(31, '44', 'Productor de Tabaco', '0.0000', 1, 0, 1, 0, 0, ''),
(32, '50', 'Distribuidor de Bebidas Gaseosas, Isotónicas, Deportivas, Fortificantes, Energizante o Estimulante ', '0.0000', 1, 0, 1, 0, 0, ''),
(33, '51', 'Bebidas Alcohólicas', '0.0000', 1, 0, 1, 0, 0, ''),
(34, '52', 'Cerveza', '0.0000', 1, 0, 1, 0, 0, ''),
(35, '53', 'Productos del Tabaco', '0.0000', 1, 0, 1, 0, 0, ''),
(36, '54', 'Bebidas Carbonatadas o Gaseosas Simples o Endulzadas', '0.0000', 1, 0, 1, 0, 0, ''),
(37, '55', 'Otros Específicos', '0.0000', 1, 0, 1, 0, 0, ''),
(38, '58', 'Alcohol', '0.0000', 1, 0, 1, 0, 0, ''),
(39, '77', 'Importador de Jugos, Néctares, Bebidas con Jugo y Refrescos', '0.0000', 1, 0, 1, 0, 0, ''),
(40, '78', 'Distribuidor de Jugos, Néctares, Bebidas con Jugo y Refrescos', '0.0000', 1, 0, 1, 0, 0, ''),
(41, '79', 'Sobre Llamadas Telefónicas Provenientes del Ext.', '0.0000', 1, 0, 1, 0, 0, ''),
(42, '85', 'Detallista de Jugos, Néctares, Bebidas con Jugo y Refrescos', '0.0000', 1, 0, 1, 0, 0, ''),
(43, '86', 'Fabricante de Preparaciones Concentradas o en Polvo para la Elaboración de Bebidas ', '0.0000', 1, 0, 1, 0, 0, ''),
(44, '91', 'Fabricante de Jugos, Néctares, Bebidas con Jugo y Refrescos', '0.0000', 1, 0, 1, 0, 0, ''),
(45, '92', 'Importador de Preparaciones Concentradas o en Polvo para la Elaboración de Bebidas ', '0.0000', 1, 0, 1, 0, 0, ''),
(46, 'A1', 'Específicos y Ad-Valorem', '0.0000', 1, 0, 1, 0, 0, ''),
(47, 'A5', 'Bebidas Gaseosas, Isotónicas, Deportivas, Fortificantes, Energizantes o Estimulantes ', '0.0000', 1, 0, 1, 0, 0, ''),
(48, 'A7', 'Alcohol Etílico', '0.0000', 1, 0, 1, 0, 0, ''),
(49, 'A9', 'Sacos Sintéticos', '0.0000', 0, 0, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuestos_gasolina`
--

CREATE TABLE `impuestos_gasolina` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(8) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `valor` decimal(6,2) NOT NULL,
  `dif` tinyint(1) NOT NULL COMMENT 'Si es 1, no aplica para embarcacion DIF',
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `impuestos_gasolina`
--

INSERT INTO `impuestos_gasolina` (`id`, `unique_id`, `nombre`, `descripcion`, `valor`, `dif`, `activo`) VALUES
(2, '', 'FOVIAL', 'FOVIAL', '0.20', 1, 1),
(4, 'S6297040', 'COTRANS', 'COTRANS', '0.10', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laboratorio`
--

CREATE TABLE `laboratorio` (
  `id_laboratorio` int(11) NOT NULL,
  `laboratorio` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `laboratorio`
--

INSERT INTO `laboratorio` (`id_laboratorio`, `laboratorio`) VALUES
(1, 'CALOX'),
(2, 'CADIFAR'),
(3, 'DIAVETSA'),
(4, 'UNIPHARM'),
(5, 'ASEAL'),
(6, 'PROVECSA'),
(7, 'FRANCO INVERSIONES'),
(8, 'GENETICA'),
(9, 'PRIAL'),
(10, 'ABBOTT'),
(11, 'LOPEZ'),
(12, 'GSK'),
(13, 'GARDEN HOUSE'),
(14, 'PFIZER'),
(15, 'MERCK'),
(16, 'BIAL'),
(17, 'GRUPO FARMA'),
(18, 'MORE PHARMA'),
(19, 'OPHIA'),
(20, 'BOEHRINGER INGELHEIM'),
(21, 'TERAMED'),
(22, 'ASTA MEDICA'),
(23, 'ALTIAN PHARMA'),
(24, 'ADHINTER'),
(25, 'DALT PHARMA'),
(26, 'JOHNSON '),
(27, 'AVENT'),
(28, 'VIJOSA'),
(29, 'PISA'),
(30, 'C- IMBERTON'),
(31, 'COLGATE'),
(32, 'ALPES SUIZOS'),
(33, 'COFASA'),
(34, 'BIOKEMICAL'),
(35, 'DELTA'),
(36, 'FARDEL'),
(37, 'STEIN'),
(38, 'TERAPEUTICOS MEDICINALES'),
(39, 'CHINOIN'),
(40, 'MC '),
(41, 'SAIMED'),
(42, 'ARGUS '),
(43, 'NORDIC'),
(44, 'SUIZOS'),
(45, 'PAILL'),
(46, 'DISTRIBUIDORA CUSCATLAN'),
(47, 'NIPRO MEDICAL'),
(48, 'HIPOALERGIC'),
(49, 'SOLARIS'),
(50, 'RAZEL'),
(51, 'MEDA'),
(52, 'SANOFI'),
(53, 'MEDICROPOLIS'),
(54, 'BIOGALENIC'),
(55, 'RIASA S.R.L'),
(56, 'MONERVA S.A. DE C.V.'),
(57, 'ECOMED'),
(58, 'ENMILEN'),
(59, 'LAFAGE'),
(60, 'LILLY'),
(61, 'LA SANTE'),
(62, 'UNIPHARM'),
(63, 'GRUNENTHAL'),
(64, 'FERRER'),
(65, 'ANCALMO'),
(66, 'CHALVER'),
(67, 'TEXPOL'),
(68, 'FUTURA'),
(69, 'MAFESA'),
(70, 'LABORATORIOS ROWALT'),
(71, 'Laboratorio Generix '),
(72, 'WELLCO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lectura_bomba`
--

CREATE TABLE `lectura_bomba` (
  `id_lectura` int(11) NOT NULL,
  `id_apertura` int(11) NOT NULL,
  `gal_diesel` decimal(12,2) NOT NULL,
  `gal_regular` decimal(12,2) NOT NULL,
  `gal_super` decimal(12,2) NOT NULL,
  `total_gal` decimal(12,2) NOT NULL,
  `dinero_diesel` decimal(12,2) NOT NULL,
  `dinero_regular` decimal(12,2) NOT NULL,
  `dinero_super` decimal(12,2) NOT NULL,
  `total_dinero` decimal(10,2) NOT NULL,
  `total_impuestos` decimal(10,4) NOT NULL,
  `devolucion_regular` decimal(10,2) NOT NULL,
  `devolucion_super` decimal(10,2) NOT NULL,
  `devolucion_diesel` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_corte` time NOT NULL,
  `id_sucursal` smallint(2) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lectura_detalle_bomba`
--

CREATE TABLE `lectura_detalle_bomba` (
  `id` int(11) NOT NULL,
  `id_lectura` int(11) NOT NULL,
  `id_bomba` smallint(2) NOT NULL,
  `id_tipo_combustible` smallint(1) NOT NULL COMMENT '1=SUPER, 2=REGULAR, 3=DIESEL',
  `id_manguera` int(11) NOT NULL,
  `combustible` varchar(20) NOT NULL,
  `fecha` date NOT NULL,
  `inicio_combustible` decimal(12,2) NOT NULL,
  `fin_combustible` decimal(12,2) NOT NULL,
  `galones` double(12,2) NOT NULL,
  `inicio_dinero` decimal(12,2) NOT NULL,
  `fin_dinero` decimal(12,2) NOT NULL,
  `total_dinero` decimal(10,2) NOT NULL,
  `id_sucursal` smallint(2) NOT NULL,
  `hora_corte` time NOT NULL,
  `diferencia` decimal(12,2) NOT NULL,
  `facturado_sistema` decimal(12,2) NOT NULL DEFAULT 0.00,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lectura_lub_dia`
--

CREATE TABLE `lectura_lub_dia` (
  `id` int(11) NOT NULL,
  `id_apertura` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `inv_sistema` decimal(12,2) NOT NULL,
  `conteo` decimal(12,2) NOT NULL,
  `diferencia` decimal(10,2) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_cambio_local`
--

CREATE TABLE `log_cambio_local` (
  `id_log_cambio` int(11) NOT NULL,
  `id_server` int(11) DEFAULT NULL,
  `process` varchar(250) NOT NULL,
  `tabla` varchar(250) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_sucursal` int(11) DEFAULT NULL,
  `id_primario` int(11) DEFAULT NULL,
  `subido` int(11) NOT NULL,
  `verificado` int(11) NOT NULL,
  `prioridad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `log_cambio_local`
--

INSERT INTO `log_cambio_local` (`id_log_cambio`, `id_server`, `process`, `tabla`, `fecha`, `hora`, `id_usuario`, `id_sucursal`, `id_primario`, `subido`, `verificado`, `prioridad`) VALUES
(1, NULL, 'insert', 'producto', '2023-07-01', '15:06:40', 1, 1, 1, 0, 0, 1),
(2, NULL, 'insert', 'producto', '2023-07-07', '11:41:45', 1, 1, 1, 0, 0, 1),
(3, NULL, 'update', 'correlativo', '2023-07-07', '11:42:18', 1, 1, 1, 0, 0, 1),
(4, NULL, 'update', 'correlativo', '2023-07-07', '11:49:31', 1, 1, 1, 0, 0, 1),
(5, NULL, 'update', 'correlativo', '2023-07-07', '14:47:48', 1, 1, 1, 0, 0, 1),
(6, NULL, 'update', 'correlativo', '2023-07-07', '14:47:48', 1, 1, 1, 0, 0, 1),
(7, NULL, 'update', 'correlativo', '2023-07-08', '11:48:13', 1, 1, 1, 0, 0, 1),
(8, NULL, 'update', 'correlativo', '2023-07-20', '11:06:43', 1, 1, 1, 0, 0, 1),
(9, NULL, 'update', 'correlativo', '2023-07-20', '11:08:00', 1, 1, 1, 0, 0, 1),
(10, NULL, 'update', 'correlativo', '2023-07-25', '08:31:17', 1, 1, 1, 0, 0, 1),
(11, NULL, 'update', 'correlativo', '2023-07-25', '08:53:18', 1, 1, 1, 0, 0, 1),
(12, NULL, 'update', 'correlativo', '2023-07-25', '08:53:47', 1, 1, 1, 0, 0, 1),
(13, NULL, 'update', 'correlativo', '2023-07-25', '08:56:24', 1, 1, 1, 0, 0, 1),
(14, NULL, 'update', 'correlativo', '2023-07-25', '09:07:19', 1, 1, 1, 0, 0, 1),
(15, NULL, 'update', 'correlativo', '2023-07-25', '09:18:58', 1, 1, 1, 0, 0, 1),
(16, NULL, 'update', 'correlativo', '2023-07-25', '09:20:16', 1, 1, 1, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_detalle_cambio_local`
--

CREATE TABLE `log_detalle_cambio_local` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_detalle_cambio` int(11) NOT NULL,
  `id_log_cambio` int(11) NOT NULL,
  `tabla` varchar(250) NOT NULL,
  `id_verificador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `log_detalle_cambio_local`
--

INSERT INTO `log_detalle_cambio_local` (`id_server`, `unique_id`, `id_detalle_cambio`, `id_log_cambio`, `tabla`, `id_verificador`) VALUES
(0, 'S64a09560232817.64958770', 1, 1, 'producto', 1),
(0, 'S64a84e592d7974.46520311', 2, 2, 'producto', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_update_local`
--

CREATE TABLE `log_update_local` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_log_cambio` int(11) NOT NULL,
  `query` text NOT NULL,
  `tabla` varchar(250) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_sucursal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `log_update_local`
--

INSERT INTO `log_update_local` (`id_server`, `unique_id`, `id_log_cambio`, `query`, `tabla`, `fecha`, `hora`, `id_usuario`, `id_sucursal`) VALUES
(0, 'S64b5cc36bc2205.57433371', 1, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6362cb0a2202c4.89363106\'', 'usuario_modulo', '2023-07-17', '17:18:14', 1, 1),
(0, 'S64b5cc36bca953.67391088', 2, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6362cb0a228d96.25917668\'', 'usuario_modulo', '2023-07-17', '17:18:14', 1, 1),
(0, 'S64b5cc36bcf225.58793040', 3, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6362cb0a22d806.82934067\'', 'usuario_modulo', '2023-07-17', '17:18:14', 1, 1),
(0, 'S64b5cc36bd4e36.46108504', 4, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6362cb0a232ed3.76880914\'', 'usuario_modulo', '2023-07-17', '17:18:14', 1, 1),
(0, 'S64b5cc36bdb711.08890585', 5, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6362cb0a23a137.93558671\'', 'usuario_modulo', '2023-07-17', '17:18:14', 1, 1),
(0, 'S64b5cc675604f0.21650650', 6, 'DELETE FROM usuario_modulo WHERE unique_id =\'O5f05eba87df1c2.39986741\'', 'usuario_modulo', '2023-07-17', '17:19:03', 1, 1),
(0, 'S64b5cc67571392.15374711', 7, 'DELETE FROM usuario_modulo WHERE unique_id =\'O5f05eba8800899.86361920\'', 'usuario_modulo', '2023-07-17', '17:19:03', 1, 1),
(0, 'S64b5cc675796d2.37320172', 8, 'DELETE FROM usuario_modulo WHERE unique_id =\'O5f05eba8821b44.49774214\'', 'usuario_modulo', '2023-07-17', '17:19:03', 1, 1),
(0, 'S64b5cc67584da9.05620187', 9, 'DELETE FROM usuario_modulo WHERE unique_id =\'O5f05eba8842949.77780368\'', 'usuario_modulo', '2023-07-17', '17:19:03', 1, 1),
(0, 'S64b5cc9bafa993.25394983', 10, 'DELETE FROM usuario_modulo WHERE unique_id =\'O5f05eba875aa90.25911644\'', 'usuario_modulo', '2023-07-17', '17:19:55', 1, 1),
(0, 'S64b5cc9bb0b590.57774665', 11, 'DELETE FROM usuario_modulo WHERE unique_id =\'O5f05eba877b7e6.64068302\'', 'usuario_modulo', '2023-07-17', '17:19:55', 1, 1),
(0, 'S64b5cc9bb15ae5.02805153', 12, 'DELETE FROM usuario_modulo WHERE unique_id =\'O5f05eba879cad2.60557030\'', 'usuario_modulo', '2023-07-17', '17:19:55', 1, 1),
(0, 'S64b5cc9bb1db07.97795843', 13, 'DELETE FROM usuario_modulo WHERE unique_id =\'O5f05eba87bdd03.98714413\'', 'usuario_modulo', '2023-07-17', '17:19:55', 1, 1),
(0, 'S64b8557c6c6636.31590933', 14, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7931040.30790756\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c6e7eb9.65887377', 15, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79363d1.16211814\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c6fa064.83703911', 16, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7939d36.81729326\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c70d6c4.13580052', 17, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec793d3b8.78592621\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c726395.00992987', 18, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7940d79.07260421\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c733433.36914992', 19, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7944495.37226011\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c739067.50328546', 20, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7947d25.31947119\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c74cf50.69904494', 21, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec794b690.95017471\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c75ab28.52686989', 22, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec794fbf0.29456553\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c7696c6.93328012', 23, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7952f67.07872404\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c780069.93388995', 24, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7956643.43550320\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c78cbb3.93170098', 25, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7959907.25525842\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c798cf4.76944646', 26, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec795cff2.16217402\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c7ae9a2.85217946', 27, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79602d8.29548472\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c7bdc82.51532360', 28, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7963775.31886443\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c7ccd77.11721982', 29, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7967073.76990319\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c7e5229.91548617', 30, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec796a772.83397742\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c7f30f1.47102790', 31, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec796dd06.63291979\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c803be5.86508378', 32, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79710a6.86200698\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c8215e1.72773905', 33, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79743b1.39839874\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c836479.44543388', 34, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7977693.30281075\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c848ad6.12448484', 35, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec797aa61.23193803\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c8656c7.09018331', 36, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec797e422.66668366\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c879274.06520298', 37, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7981983.01582944\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c88cad1.59845670', 38, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79851a5.92185852\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c8ab9a2.95383297', 39, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7988418.22252237\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c8c1ab0.39556322', 40, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec798bef3.42831274\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c8d5df9.59257279', 41, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec798f356.92274946\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c8f37c3.68449431', 42, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79928b2.53593462\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c907aa7.18497857', 43, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7995e92.05394018\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c91b8e2.79862851', 44, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7999207.62295916\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c939902.12273706', 45, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec799c555.08185955\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c94cf80.31180947', 46, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec799f855.81614045\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c95de28.16757427', 47, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79a2b29.99509548\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c976577.58825326', 48, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79a5ff8.65109608\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c982a71.37370753', 49, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79a9f67.48621042\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c98a735.74255884', 50, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79ad948.36311754\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c9969a3.07984747', 51, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79b1378.73912270\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c9ab3e2.68839442', 52, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79b4f76.11656730\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c9b9093.41169742', 53, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79b89f4.48855603\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c9c7d53.39206799', 54, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79bc505.41599783\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c9dea11.03651407', 55, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79c00c7.48406509\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c9ec627.82172074', 56, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79c3b89.97297588\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557c9f82a5.17958240', 57, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79c72a4.64291103\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ca0dbf5.74460681', 58, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79cad06.62068596\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ca199f8.55615937', 59, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79ce2d6.10980801\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ca25211.32832208', 60, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79d1823.34849153\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ca3acf0.71504154', 61, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79d4e83.03824764\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ca49dd1.81709518', 62, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79d8598.48332321\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ca59172.59018301', 63, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79dbf84.64388759\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ca70e71.54952094', 64, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79df556.70079654\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ca7db31.98467884', 65, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79e2b37.83660951\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ca83cf5.63560748', 66, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79e6592.22142775\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ca8e9a7.06291787', 67, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79ec8a9.26272499\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557caa4f11.14922472', 68, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79efe55.73989353\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cab1c14.29463075', 69, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79f3536.84193084\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cabc9f8.33505967', 70, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79f6b53.17096001\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cac23f7.04964194', 71, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79fa819.80741080\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cad4f89.37036536', 72, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec79fe643.12583573\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cae1ea9.49475606', 73, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a02218.39089173\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557caedb85.60939498', 74, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a05c88.26216687\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cb038f7.77221740', 75, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a09994.49550830\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cb105d4.92471558', 76, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a0d220.96481260\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cb1a878.26985637', 77, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a10a70.08596855\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cb20524.78342467', 78, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a14321.41468587\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cb35b03.53938295', 79, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a17b02.36108942\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cb404f9.29947390', 80, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a1b222.11360280\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cb4d3b3.65527928', 81, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a1e8f5.08583381\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cb62556.26666147', 82, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a220e4.74445920\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cb6e2a0.50593466', 83, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a25ad7.86858108\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cb7b1a8.37809541', 84, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a292f4.56966512\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cb92e33.44572353', 85, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a2c8d4.52604112\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cba6e61.90735872', 86, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a2fe97.40366585\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cbb92c2.79310375', 87, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a334b2.36659256\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cbd5717.70962166', 88, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a36b55.36402742\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cbe8578.04420481', 89, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a3a1a9.36094231\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cbfa1d2.78314271', 90, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a3db99.92474823\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cc177d0.18945865', 91, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a41f82.91690025\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cc2cc79.60283205', 92, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a45a37.75878317\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cc42574.37899962', 93, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a49873.10125677\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cc5f6f5.06148878', 94, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a4ce57.44248252\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cc72ed2.92339827', 95, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a50733.97709718\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ccc3c64.09422270', 96, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a541f6.92317069\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cd14c57.67919017', 97, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a57916.06184050\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cd82842.60398049', 98, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a5af81.96357561\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cd97464.02164727', 99, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a5e602.98500485\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cdadaa8.52327219', 100, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a61ca9.09550483\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cdd92f2.49619325', 101, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a65b78.85442155\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cdf0738.03208360', 102, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a69382.73843250\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ce0f1f8.58591715', 103, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a6c9d3.42571358\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ce24db1.58812056', 104, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a705e1.78192429\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ce3bc05.69792705', 105, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a73ce5.58805095\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ce586c6.23122017', 106, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a773b7.24217688\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ce6d502.34536966', 107, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a7aa26.21802771\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ce82141.77879440', 108, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a7e083.79428504\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cea2262.28473234', 109, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a81782.76841797\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ceb85d1.07764879', 110, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a84f69.67000065\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cece3a7.60545369', 111, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a88719.78753147\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557ceec242.51452263', 112, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a8bda7.69126493\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cf008a6.48202477', 113, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a8f730.63104636\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cf14b09.90067027', 114, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a931f7.94458483\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557cf32a81.49477670', 115, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a96b99.97858517\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557d006864.82140166', 116, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a9a239.86150028\'', 'usuario_modulo', '2023-07-19', '15:28:28', 1, 1),
(0, 'S64b8557d01b045.55833240', 117, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7a9d8b1.16921144\'', 'usuario_modulo', '2023-07-19', '15:28:29', 1, 1),
(0, 'S64b8557d03bee6.76698517', 118, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7aa1318.07760107\'', 'usuario_modulo', '2023-07-19', '15:28:29', 1, 1),
(0, 'S64b8557d052a37.36120266', 119, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7aa4b07.27846605\'', 'usuario_modulo', '2023-07-19', '15:28:29', 1, 1),
(0, 'S64b8557d066ce7.53739685', 120, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7aa82e9.41358893\'', 'usuario_modulo', '2023-07-19', '15:28:29', 1, 1),
(0, 'S64b8557d083dd2.92018503', 121, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7aab976.11720113\'', 'usuario_modulo', '2023-07-19', '15:28:29', 1, 1),
(0, 'S64b8557d0970c7.95202270', 122, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7aaf310.61347585\'', 'usuario_modulo', '2023-07-19', '15:28:29', 1, 1),
(0, 'S64b8557d0aac49.01876787', 123, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7ab2a22.11973089\'', 'usuario_modulo', '2023-07-19', '15:28:29', 1, 1),
(0, 'S64b8557d0c98d2.03901384', 124, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6298cec7ab60f1.25816168\'', 'usuario_modulo', '2023-07-19', '15:28:29', 1, 1),
(0, 'S64b856165a7be5.29208313', 125, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d17589e1f6.88047915\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856165c1615.13871612', 126, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1758acb84.60704382\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856165d1644.76421651', 127, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1758bb3f3.96912376\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856165deb09.63861372', 128, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1758c9760.17052365\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856165f8059.54568816', 129, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1758d5022.22036215\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616606510.58145385', 130, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1758e08d8.74093475\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616614506.61358442', 131, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1758eb517.92940979\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616632562.54063084', 132, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1758f6ba3.36809166\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b8561664d3d6.78585237', 133, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1759014f4.71505933\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616664845.89881794', 134, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d17590c3a6.89221990\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856166874d9.12419021', 135, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175916276.12559628\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b8561669f1f0.58506561', 136, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175926ba6.33148097\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856166b5f04.20123511', 137, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175935605.81201147\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856166d65d5.14358643', 138, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175943d66.58591300\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856166ebab3.62679709', 139, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d17594fa70.17440057\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856166ffd84.13554342', 140, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d17595a1c1.53792204\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616720b84.70381668', 141, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175962fa1.58185618\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b8561672e907.93689709', 142, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d17596d5c2.66206034\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b8561674f0a5.37948912', 143, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175977558.53789371\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616764ab0.15184263', 144, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1759828d5.06795008\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b8561677ada3.60841455', 145, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d17598d570.35615027\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b8561679b385.83432335', 146, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175998c86.17657757\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856167ae9f3.84407194', 147, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1759a6649.87131007\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856167c1221.51150601', 148, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1759b67c0.98315607\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856167e0825.10888580', 149, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1759c6448.85220825\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616806d85.88635239', 150, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1759d6400.78943922\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b8561681fa23.19892829', 151, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1759e47d0.73657558\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b8561683b591.53195621', 152, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1759f14f2.91107373\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b8561684af60.31355570', 153, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d1759fdc38.58489784\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616858d02.17268209', 154, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175a0b247.36775883\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616872213.76176815', 155, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175a19ef2.03535544\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b8561687e268.87397808', 156, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175a26ad0.72968773\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b8561688be59.95353993', 157, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175a31760.77508755\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856168a5211.18249994', 158, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175a3d020.66567126\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856168b0f54.75451345', 159, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175a48c70.87701981\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856168bc356.22169395', 160, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175a58b17.29311891\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856168d3ee1.02624776', 161, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175a6b2b7.91590844\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856168df5b5.59635832', 162, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175a80df0.28902666\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856168ec0f7.21275709', 163, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175a8e507.39781609\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616903d76.49510213', 164, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175a9a3d5.99151322\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856169106c0.08246852', 165, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175aa67e3.05052921\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b8561691c9f8.29997575', 166, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175ab2740.49602652\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616935062.50952851', 167, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175abd238.57576468\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616944ef8.21243664', 168, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175ac8b77.55256420\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616951c92.97363807', 169, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175ad49c3.02744064\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616969f11.37296018', 170, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175adf824.70028879\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616976db3.05304107', 171, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175aebc45.31457738\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616987320.00694328', 172, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175af7fd7.99749083\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856169a1c45.25784819', 173, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175b03307.47549495\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856169ad993.50521278', 174, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175b0d5c8.64444029\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856169bb545.46170894', 175, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175b18e59.30774014\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856169d9111.30635234', 176, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175b235d9.23155062\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856169e5999.40180966', 177, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175b2ed78.25912417\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856169f1520.09130033', 178, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175b39295.57386587\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b856169f8307.50579220', 179, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175b44460.61193863\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616a11879.99751342', 180, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175b50424.89390252\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616a20de5.98371044', 181, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175b5b891.48107882\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616a2ee81.56317368', 182, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175b66e15.08368555\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616a370a0.00984583', 183, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175b72664.18425597\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616a53b42.58460179', 184, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175b7ef49.91721499\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616a66d53.08959124', 185, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175b89632.19514402\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616a7b949.16507052', 186, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175b95904.77146241\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616a9ccc9.78830912', 187, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175ba0e04.54236976\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616ab2ff6.23182392', 188, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175bacbf1.11119165\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616ac7990.60933969', 189, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175bb78c1.21556094\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616ae8b44.89613780', 190, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175bc2a35.79806835\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616afe2c2.63839985', 191, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175bce011.05878574\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616b11414.57839858', 192, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175bd8625.15417927\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616b2f224.57001306', 193, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175be5890.70077411\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616b44488.18836352', 194, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175bf2b91.63960691\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616b56d45.14913222', 195, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175bfcfa3.52814417\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616b74973.09340565', 196, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175c06cb8.21328986\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616b874d6.21717124', 197, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175c13313.16356241\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616b99491.84499363', 198, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175c1e429.09901427\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616bb9589.60031010', 199, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175c29102.98705959\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616bcf3c0.39506184', 200, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175c32cf2.04432381\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616be2563.16526323', 201, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175c3ec82.20430139\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616c00bc0.02131694', 202, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175c4a800.41735639\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616c14318.96052922', 203, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175c53962.39230564\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616c27822.88236304', 204, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175c5d422.73322914\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616c46759.94955552', 205, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175c683a6.32574542\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616c5cc10.41304564', 206, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175c76470.82875574\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616c72721.30624420', 207, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175c89428.31131799\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616c94424.16890990', 208, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175c969c5.20125356\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616ca9af3.48878079', 209, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175ca6729.61859543\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616cb8be6.38688665', 210, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175cb65c5.56751685\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616cd0da6.54539082', 211, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175cc44c7.86499060\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616ce0245.33127108', 212, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175cd4185.43829211\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616ceed78.00844338', 213, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175ce4720.19328464\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616cf7685.05049469', 214, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175cf4252.95795961\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616d0fab5.44303659', 215, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175d03dc3.63184325\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616d1fe47.81753167', 216, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175d11bc9.23435560\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616d2e205.14801785', 217, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175d1f453.62681193\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616d45dc8.39736694', 218, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175d2d2b2.72071594\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616d55f86.19857552', 219, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175d3af76.13063067\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616d650f1.07673414', 220, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175d48365.33610207\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616d7f9f4.88917419', 221, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175d552e6.18258577\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616d8dd76.90254665', 222, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175d62758.66666688\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616da01f4.70821874', 223, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175d6d922.73443484\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616dbbdb4.75321750', 224, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175d7a220.83902847\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616dcd125.84541551', 225, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175d86c33.99261010\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616de1592.91058481', 226, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175d92cf7.17442557\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616dfd139.29319945', 227, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175d9e2d3.12360378\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616e0a985.78981338', 228, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175dab7a8.09947581\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616e18396.67477769', 229, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175db9a26.89776988\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616e23646.54472847', 230, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175dc7b14.00883616\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616e3ba77.26536526', 231, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175dd5c80.22684661\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616e4ad50.39333666', 232, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175de6150.93609449\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616e5b303.00503871', 233, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175df4654.12518561\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616e7a6e3.17734062', 234, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175e01fb0.54320768\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616e91a66.81158476', 235, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175e0f956.56008577\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616ea5838.07114791', 236, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175e1c411.14294242\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616ec53a5.14603696', 237, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175e29632.31619481\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616ef1562.26220230', 238, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175e37477.30777589\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616f04de0.31512420', 239, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175e448d7.31266228\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616f18799.01364488', 240, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175e51df3.88015023\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1),
(0, 'S64b85616f38d24.55773746', 241, 'DELETE FROM usuario_modulo WHERE unique_id =\'S6468d175e5cda7.76468434\'', 'usuario_modulo', '2023-07-19', '15:31:02', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote`
--

CREATE TABLE `lote` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_lote` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `fecha_entrada` date NOT NULL,
  `numero` int(11) NOT NULL,
  `cantidad` decimal(11,4) NOT NULL,
  `precio` decimal(11,4) NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `vencimiento` date NOT NULL,
  `estado` varchar(25) NOT NULL,
  `referencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lote`
--

INSERT INTO `lote` (`id_server`, `unique_id`, `id_sucursal`, `id_lote`, `id_producto`, `fecha_entrada`, `numero`, `cantidad`, `precio`, `id_presentacion`, `vencimiento`, `estado`, `referencia`) VALUES
(0, 'S64a84e7a708397.20429985', 1, 1, 1, '2023-07-07', 1, '0.0000', '1.0000', 1, '0000-00-00', 'FINALIZADO', 1),
(0, 'S64a9e64ba63f15.28186826', 2, 2, 1, '2023-07-08', 2, '70.0000', '1.0000', 1, '0000-00-00', 'VIGENTE', 1),
(0, 'S64bfdcb522e713.31771943', 1, 3, 1, '2023-07-25', 3, '1.0000', '1.0000', 1, '0000-00-00', 'VIGENTE', 2),
(0, 'S64bfe1dee99f27.89210639', 1, 4, 1, '2023-07-25', 4, '10.0000', '1.0000', 1, '0000-00-00', 'VIGENTE', 3),
(0, 'S64bfe1fb457c89.98788560', 1, 5, 1, '2023-07-25', 5, '10.0000', '1.0000', 1, '0000-00-00', 'VIGENTE', 4),
(0, 'S64bfe2986b9153.39512740', 1, 6, 1, '2023-07-25', 6, '10.0000', '1.0000', 1, '0000-00-00', 'VIGENTE', 5),
(0, 'S64bfe52762cda4.00114054', 1, 7, 1, '2023-07-25', 7, '10.0000', '1.0000', 1, '0000-00-00', 'VIGENTE', 6),
(0, 'S64bfe7e25c7db6.94871971', 1, 8, 1, '2023-07-25', 8, '15.0000', '1.0000', 1, '0000-00-00', 'VIGENTE', 7),
(0, 'S64bfe830dcb5a5.72683946', 1, 9, 1, '2023-07-25', 9, '20.0000', '1.0000', 1, '0000-00-00', 'VIGENTE', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `id_marca` int(11) NOT NULL,
  `marca` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id_marca`, `marca`) VALUES
(1, 'GENERICA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `margen_cols_form`
--

CREATE TABLE `margen_cols_form` (
  `id` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `alias` varchar(10) NOT NULL,
  `marg_sup` int(11) NOT NULL,
  `h1` varchar(20) NOT NULL,
  `h2` varchar(20) NOT NULL,
  `h3` varchar(20) NOT NULL,
  `h4` varchar(20) NOT NULL,
  `h5` varchar(20) NOT NULL,
  `h6` varchar(20) NOT NULL,
  `h7` varchar(20) NOT NULL,
  `h8` varchar(20) NOT NULL,
  `h9` varchar(20) NOT NULL,
  `h10` varchar(20) NOT NULL,
  `cols_body` int(11) NOT NULL,
  `marg_body` int(11) NOT NULL,
  `marg_foot` smallint(3) NOT NULL,
  `col_body_arr` varchar(24) NOT NULL,
  `lines_body` int(11) NOT NULL,
  `f1` varchar(20) NOT NULL,
  `f2` varchar(20) NOT NULL,
  `f3` varchar(20) NOT NULL,
  `f4` varchar(20) NOT NULL,
  `f5` varchar(20) NOT NULL,
  `f6` varchar(20) NOT NULL,
  `f7` varchar(20) NOT NULL,
  `f8` varchar(20) NOT NULL,
  `f9` varchar(20) NOT NULL,
  `f10` varchar(20) NOT NULL,
  `descrip` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `margen_cols_form`
--

INSERT INTO `margen_cols_form` (`id`, `id_sucursal`, `alias`, `marg_sup`, `h1`, `h2`, `h3`, `h4`, `h5`, `h6`, `h7`, `h8`, `h9`, `h10`, `cols_body`, `marg_body`, `marg_foot`, `col_body_arr`, `lines_body`, `f1`, `f2`, `f3`, `f4`, `f5`, `f6`, `f7`, `f8`, `f9`, `f10`, `descrip`) VALUES
(1, 1, 'CCF', 10, '10,60,10,30', '10,60,10,30', '10,60,10,30', '10,60,10,30', '10,60,10,30', '10,60,10,30', '10,60,10,30', '0', '0', '0', 5, 4, 2, '3,46,3,10,3,10,5,5,4,11', 13, '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '0', '0', '# TABLA: margen_cols_form ## CAMPOS: id, alias, marg_sup, h1, h2, h3, h4, h5, h6, h7, h8, h9, h10,  cols_body, marg_body, col_body_arr, lines_body,  f1, f2, f3, f4, f5, f6, f7, f8, f9, f10, descrip   ## DESCRIPCION DE CAMPOS: - **alias:** (Tipo VARCHAR) Guardar el tipo de impresión: CCF para crédito fiscal, NCR para nota de crédito, etc. -  **marg_sup:** (Tipo INT)  guardar el margen superior de tipo de impresión. -  **h1, h2, h3, h4, h5, h6, h7, h8, h9, h10 :** (Array tipo INT), cada uno es una fila del encabezado del documento, guarda en cada array el tamaño de cada columna de un encabezado por ej (3,10,20). que serán la posición 0, el margen inicial, la posicion 1 puede ser un dato, la posicion 2 puede ser un espacio entre columnas, así sucesivamente con cada encabezado. -  -  **f1, f2, f3, f4, f5, f6, f7, f8, f9, f10 :** (Array tipo INT), cada uno es una fila del pie de página del documento, guarda en cada array el tamaño de cada columna de un pie de página por ej (3,10,20). que serán la posición 0, el margen inicial, la posicion 1 puede ser un dato, la posicion 2 puede ser un espacio entre columnas, así sucesivamente con cada pie de página.  -  **marg_body :**  (valor tipo INT), guarda un valor entero que es un margen que se inserta entre el encabezado, y el inicio del cuerpo o detalle del documento a imprimir  -  **col_body_arr :** (Array tipo INT),  guarda en el array el tamaño de cada columna del detalle del documento por ej (3,10,3,40). que serán la posición 0: el margen inicial, la posicion 1 puede ser un dato digamos cantidad,  la posicion 2 puede ser un espacio entre columnas, la posición 3: puede ser la descripción de un producto así sucesivamente.  -  **lines_body:**   (valor tipo INT), guarda un valor entero que es el número de líneas del cuerpo o detalle.  -  **descrip:**  ( tipo TEXT), Usado para guardar la descripción de los campos de la tabla __margen_cols_form__ y su uso a la hora de imprimir documentos, en un impresor tipo matricial   -  **cols_body:** No usado de momento'),
(2, 1, 'COF', 10, '16,50,3,33', '16,84', '19,84', '27,84', '0', '0', '0', '0', '0', '0', 5, 3, 3, '3,7,2,47,10,5,5,12', 28, '10,50,20,5,12', '10,50,20,5,12', '80,5,12', '80,5,12', '80,5,12', '80,5,12', '6,30,4,21,9', '6,30,4,21,9', '6,30,4,21,9', '0', '<h1 id=\"tabla-margen_cols_form\">TABLA: margen_cols_form</h1> <h2 id=\"campos\">CAMPOS:</h2> <p>id, alias, marg_sup, h1, h2, h3, h4, h5, h6, h7, h8, h9, h10, cols_body, marg_body, col_body_arr, lines_body, f1, f2, f3, f4, f5, f6, f7, f8, f9, f10, descrip</p> <h2 id=\"descripcion-de-campos\">DESCRIPCION DE CAMPOS:</h2> <ul> <li><strong>alias:</strong> (Tipo VARCHAR) Guardar el tipo de impresión: CCF para crédito fiscal, NCR para nota de crédito, etc.</li> <li><strong>marg_sup:</strong> (Tipo INT) guardar el margen superior de tipo de impresión.</li> <li><strong>h1, h2, h3, h4, h5, h6, h7, h8, h9, h10 :</strong> (Array tipo INT), cada uno es una fila del encabezado del documento, guarda en cada array el tamaño de cada columna de un encabezado por ej (3,10,20). que serán la posición 0, el margen inicial, la posicion 1 puede ser un dato, la posicion 2 puede ser un espacio entre columnas, así sucesivamente con cada encabezado.</li> <li></li> <li><strong>f1, f2, f3, f4, f5, f6, f7, f8, f9, f10 :</strong> (Array tipo INT), cada uno es una fila del pie de página del documento, guarda en cada array el tamaño de cada columna de un pie de página por ej (3,10,20). que serán la posición 0, el margen inicial, la posicion 1 puede ser un dato, la posicion 2 puede ser un espacio entre columnas, así sucesivamente con cada pie de página.</li> <li><strong>marg_body :</strong> (valor tipo INT), guarda un valor entero que es un margen que se inserta entre el encabezado, y el inicio del cuerpo o detalle del documento a imprimir</li> <li><strong>col_body_arr :</strong> (Array tipo INT), guarda en el array el tamaño de cada columna del detalle del documento por ej (3,10,3,40). que serán la posición 0: el margen inicial, la posicion 1 puede ser un dato digamos cantidad, la posicion 2 puede ser un espacio entre columnas, la posición 3: puede ser la descripción de un producto así sucesivamente.</li> <li><strong>lines_body:</strong> (valor tipo INT), guarda un valor entero que es el número de líneas del cuerpo o detalle.</li> <li><strong>descrip:</strong> ( tipo TEXT), Usado para guardar la descripción de los campos de la tabla <strong>margen_cols_form</strong> y su uso a la hora de imprimir documentos, en un impresor tipo matricial</li> <li><strong>cols_body:</strong> No usado de momento</li> </ul>'),
(3, 1, 'NCR', 8, '76,10,20,10', '20,80', '24,45,27,20', '13,20,10,42,16,20', '32,40', '60,46', '0,1', '0,1', '0', '0', 5, 3, 0, '6,9,4,59,12,9,9,14', 10, '18,51,30,25', '18,51,30,25', '18,51,30,25', '18,51,30,25', '18,51,30,25', '18,51,30,25', '18,51,30,25', '0,0', '0,0', '15,50,30,26', '# TABLA: margen_cols_form ## CAMPOS: id, alias, marg_sup, h1, h2, h3, h4, h5, h6, h7, h8, h9, h10,  cols_body, marg_body, col_body_arr, lines_body,  f1, f2, f3, f4, f5, f6, f7, f8, f9, f10, descrip   ## DESCRIPCION DE CAMPOS: - **alias:** (Tipo VARCHAR) Guardar el tipo de impresión: CCF para crédito fiscal, NCR para nota de crédito, etc. -  **marg_sup:** (Tipo INT)  guardar el margen superior de tipo de impresión. -  **h1, h2, h3, h4, h5, h6, h7, h8, h9, h10 :** (Array tipo INT), cada uno es una fila del encabezado del documento, guarda en cada array el tamaño de cada columna de un encabezado por ej (3,10,20). que serán la posición 0, el margen inicial, la posicion 1 puede ser un dato, la posicion 2 puede ser un espacio entre columnas, así sucesivamente con cada encabezado. -  -  **f1, f2, f3, f4, f5, f6, f7, f8, f9, f10 :** (Array tipo INT), cada uno es una fila del pie de página del documento, guarda en cada array el tamaño de cada columna de un pie de página por ej (3,10,20). que serán la posición 0, el margen inicial, la posicion 1 puede ser un dato, la posicion 2 puede ser un espacio entre columnas, así sucesivamente con cada pie de página.  -  **marg_body :**  (valor tipo INT), guarda un valor entero que es un margen que se inserta entre el encabezado, y el inicio del cuerpo o detalle del documento a imprimir  -  **col_body_arr :** (Array tipo INT),  guarda en el array el tamaño de cada columna del detalle del documento por ej (3,10,3,40). que serán la posición 0: el margen inicial, la posicion 1 puede ser un dato digamos cantidad,  la posicion 2 puede ser un espacio entre columnas, la posición 3: puede ser la descripción de un producto así sucesivamente.  -  **lines_body:**   (valor tipo INT), guarda un valor entero que es el número de líneas del cuerpo o detalle.  -  **descrip:**  ( tipo TEXT), Usado para guardar la descripción de los campos de la tabla __margen_cols_form__ y su uso a la hora de imprimir documentos, en un impresor tipo matricial   -  **cols_body:** No usado de momento'),
(4, 2, 'CCF', 7, '10,60,10,30', '10,60,10,30', '10,60,10,30', '10,60,10,30', '10,60,10,30', '10,60,10,30', '10,60,10,30', '0', '0', '0', 5, 4, 2, '3,46,3,10,3,10,5,5,4,11', 13, '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '0', '0', '# TABLA: margen_cols_form ## CAMPOS: id, alias, marg_sup, h1, h2, h3, h4, h5, h6, h7, h8, h9, h10,  cols_body, marg_body, col_body_arr, lines_body,  f1, f2, f3, f4, f5, f6, f7, f8, f9, f10, descrip   ## DESCRIPCION DE CAMPOS: - **alias:** (Tipo VARCHAR) Guardar el tipo de impresión: CCF para crédito fiscal, NCR para nota de crédito, etc. -  **marg_sup:** (Tipo INT)  guardar el margen superior de tipo de impresión. -  **h1, h2, h3, h4, h5, h6, h7, h8, h9, h10 :** (Array tipo INT), cada uno es una fila del encabezado del documento, guarda en cada array el tamaño de cada columna de un encabezado por ej (3,10,20). que serán la posición 0, el margen inicial, la posicion 1 puede ser un dato, la posicion 2 puede ser un espacio entre columnas, así sucesivamente con cada encabezado. -  -  **f1, f2, f3, f4, f5, f6, f7, f8, f9, f10 :** (Array tipo INT), cada uno es una fila del pie de página del documento, guarda en cada array el tamaño de cada columna de un pie de página por ej (3,10,20). que serán la posición 0, el margen inicial, la posicion 1 puede ser un dato, la posicion 2 puede ser un espacio entre columnas, así sucesivamente con cada pie de página.  -  **marg_body :**  (valor tipo INT), guarda un valor entero que es un margen que se inserta entre el encabezado, y el inicio del cuerpo o detalle del documento a imprimir  -  **col_body_arr :** (Array tipo INT),  guarda en el array el tamaño de cada columna del detalle del documento por ej (3,10,3,40). que serán la posición 0: el margen inicial, la posicion 1 puede ser un dato digamos cantidad,  la posicion 2 puede ser un espacio entre columnas, la posición 3: puede ser la descripción de un producto así sucesivamente.  -  **lines_body:**   (valor tipo INT), guarda un valor entero que es el número de líneas del cuerpo o detalle.  -  **descrip:**  ( tipo TEXT), Usado para guardar la descripción de los campos de la tabla __margen_cols_form__ y su uso a la hora de imprimir documentos, en un impresor tipo matricial   -  **cols_body:** No usado de momento'),
(5, 3, 'CCF', 8, '10,60,10,30', '10,60,10,30', '10,60,10,30', '10,60,10,30', '10,60,10,30', '10,60,10,30', '10,60,10,30', '0', '0', '0', 5, 4, 2, '3,46,3,10,3,10,5,5,4,11', 10, '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '5,72,3,22,10', '0', '0', '# TABLA: margen_cols_form ## CAMPOS: id, alias, marg_sup, h1, h2, h3, h4, h5, h6, h7, h8, h9, h10,  cols_body, marg_body, col_body_arr, lines_body,  f1, f2, f3, f4, f5, f6, f7, f8, f9, f10, descrip   ## DESCRIPCION DE CAMPOS: - **alias:** (Tipo VARCHAR) Guardar el tipo de impresión: CCF para crédito fiscal, NCR para nota de crédito, etc. -  **marg_sup:** (Tipo INT)  guardar el margen superior de tipo de impresión. -  **h1, h2, h3, h4, h5, h6, h7, h8, h9, h10 :** (Array tipo INT), cada uno es una fila del encabezado del documento, guarda en cada array el tamaño de cada columna de un encabezado por ej (3,10,20). que serán la posición 0, el margen inicial, la posicion 1 puede ser un dato, la posicion 2 puede ser un espacio entre columnas, así sucesivamente con cada encabezado. -  -  **f1, f2, f3, f4, f5, f6, f7, f8, f9, f10 :** (Array tipo INT), cada uno es una fila del pie de página del documento, guarda en cada array el tamaño de cada columna de un pie de página por ej (3,10,20). que serán la posición 0, el margen inicial, la posicion 1 puede ser un dato, la posicion 2 puede ser un espacio entre columnas, así sucesivamente con cada pie de página.  -  **marg_body :**  (valor tipo INT), guarda un valor entero que es un margen que se inserta entre el encabezado, y el inicio del cuerpo o detalle del documento a imprimir  -  **col_body_arr :** (Array tipo INT),  guarda en el array el tamaño de cada columna del detalle del documento por ej (3,10,3,40). que serán la posición 0: el margen inicial, la posicion 1 puede ser un dato digamos cantidad,  la posicion 2 puede ser un espacio entre columnas, la posición 3: puede ser la descripción de un producto así sucesivamente.  -  **lines_body:**   (valor tipo INT), guarda un valor entero que es el número de líneas del cuerpo o detalle.  -  **descrip:**  ( tipo TEXT), Usado para guardar la descripción de los campos de la tabla __margen_cols_form__ y su uso a la hora de imprimir documentos, en un impresor tipo matricial   -  **cols_body:** No usado de momento'),
(6, 2, 'COF', 8, '16,50,3,33', '16,84', '19,84', '27,84', '0', '0', '0', '0', '0', '0', 5, 3, 3, '3,7,2,47,10,5,5,12', 31, '10,50,20,5,12', '10,50,20,5,12', '80,5,12', '80,5,12', '80,5,12', '80,5,12', '0', '0', '0', '0', '<h1 id=\"tabla-margen_cols_form\">TABLA: margen_cols_form</h1> <h2 id=\"campos\">CAMPOS:</h2> <p>id, alias, marg_sup, h1, h2, h3, h4, h5, h6, h7, h8, h9, h10, cols_body, marg_body, col_body_arr, lines_body, f1, f2, f3, f4, f5, f6, f7, f8, f9, f10, descrip</p> <h2 id=\"descripcion-de-campos\">DESCRIPCION DE CAMPOS:</h2> <ul> <li><strong>alias:</strong> (Tipo VARCHAR) Guardar el tipo de impresión: CCF para crédito fiscal, NCR para nota de crédito, etc.</li> <li><strong>marg_sup:</strong> (Tipo INT) guardar el margen superior de tipo de impresión.</li> <li><strong>h1, h2, h3, h4, h5, h6, h7, h8, h9, h10 :</strong> (Array tipo INT), cada uno es una fila del encabezado del documento, guarda en cada array el tamaño de cada columna de un encabezado por ej (3,10,20). que serán la posición 0, el margen inicial, la posicion 1 puede ser un dato, la posicion 2 puede ser un espacio entre columnas, así sucesivamente con cada encabezado.</li> <li></li> <li><strong>f1, f2, f3, f4, f5, f6, f7, f8, f9, f10 :</strong> (Array tipo INT), cada uno es una fila del pie de página del documento, guarda en cada array el tamaño de cada columna de un pie de página por ej (3,10,20). que serán la posición 0, el margen inicial, la posicion 1 puede ser un dato, la posicion 2 puede ser un espacio entre columnas, así sucesivamente con cada pie de página.</li> <li><strong>marg_body :</strong> (valor tipo INT), guarda un valor entero que es un margen que se inserta entre el encabezado, y el inicio del cuerpo o detalle del documento a imprimir</li> <li><strong>col_body_arr :</strong> (Array tipo INT), guarda en el array el tamaño de cada columna del detalle del documento por ej (3,10,3,40). que serán la posición 0: el margen inicial, la posicion 1 puede ser un dato digamos cantidad, la posicion 2 puede ser un espacio entre columnas, la posición 3: puede ser la descripción de un producto así sucesivamente.</li> <li><strong>lines_body:</strong> (valor tipo INT), guarda un valor entero que es el número de líneas del cuerpo o detalle.</li> <li><strong>descrip:</strong> ( tipo TEXT), Usado para guardar la descripción de los campos de la tabla <strong>margen_cols_form</strong> y su uso a la hora de imprimir documentos, en un impresor tipo matricial</li> <li><strong>cols_body:</strong> No usado de momento</li> </ul>'),
(7, 3, 'COF', 6, '54,12', '10,70', '14,70', '14,70', '0', '0', '0', '0', '0', '0', 5, 3, 3, '1,5,1,26,7,5,5,10', 16, '6,30,4,21,9', '6,30,4,21,9', '6,30,4,21,9', '6,30,4,21,9', '6,30,4,21,9', '6,30,4,21,9', '6,30,4,21,9', '6,30,4,21,9', '6,30,4,21,9', '0', '<h1 id=\"tabla-margen_cols_form\">TABLA: margen_cols_form</h1> <h2 id=\"campos\">CAMPOS:</h2> <p>id, alias, marg_sup, h1, h2, h3, h4, h5, h6, h7, h8, h9, h10, cols_body, marg_body, col_body_arr, lines_body, f1, f2, f3, f4, f5, f6, f7, f8, f9, f10, descrip</p> <h2 id=\"descripcion-de-campos\">DESCRIPCION DE CAMPOS:</h2> <ul> <li><strong>alias:</strong> (Tipo VARCHAR) Guardar el tipo de impresión: CCF para crédito fiscal, NCR para nota de crédito, etc.</li> <li><strong>marg_sup:</strong> (Tipo INT) guardar el margen superior de tipo de impresión.</li> <li><strong>h1, h2, h3, h4, h5, h6, h7, h8, h9, h10 :</strong> (Array tipo INT), cada uno es una fila del encabezado del documento, guarda en cada array el tamaño de cada columna de un encabezado por ej (3,10,20). que serán la posición 0, el margen inicial, la posicion 1 puede ser un dato, la posicion 2 puede ser un espacio entre columnas, así sucesivamente con cada encabezado.</li> <li></li> <li><strong>f1, f2, f3, f4, f5, f6, f7, f8, f9, f10 :</strong> (Array tipo INT), cada uno es una fila del pie de página del documento, guarda en cada array el tamaño de cada columna de un pie de página por ej (3,10,20). que serán la posición 0, el margen inicial, la posicion 1 puede ser un dato, la posicion 2 puede ser un espacio entre columnas, así sucesivamente con cada pie de página.</li> <li><strong>marg_body :</strong> (valor tipo INT), guarda un valor entero que es un margen que se inserta entre el encabezado, y el inicio del cuerpo o detalle del documento a imprimir</li> <li><strong>col_body_arr :</strong> (Array tipo INT), guarda en el array el tamaño de cada columna del detalle del documento por ej (3,10,3,40). que serán la posición 0: el margen inicial, la posicion 1 puede ser un dato digamos cantidad, la posicion 2 puede ser un espacio entre columnas, la posición 3: puede ser la descripción de un producto así sucesivamente.</li> <li><strong>lines_body:</strong> (valor tipo INT), guarda un valor entero que es el número de líneas del cuerpo o detalle.</li> <li><strong>descrip:</strong> ( tipo TEXT), Usado para guardar la descripción de los campos de la tabla <strong>margen_cols_form</strong> y su uso a la hora de imprimir documentos, en un impresor tipo matricial</li> <li><strong>cols_body:</strong> No usado de momento</li> </ul>');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_menu` int(11) NOT NULL,
  `nombre` varchar(250) DEFAULT NULL,
  `prioridad` int(11) DEFAULT NULL,
  `icono` varchar(250) NOT NULL,
  `visible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id_server`, `unique_id`, `id_menu`, `nombre`, `prioridad`, `icono`, `visible`) VALUES
(1, 'O5f05eb409009e5.46706007', 1, 'Productos', 5, 'fa fa-archive', 1),
(2, 'O5f05eb409179a0.18650115', 2, 'Clientes', 1, 'fa fa-users', 1),
(3, 'O5f05eb4093a7c3.20066664', 3, 'Proveedores', 2, 'fa fa-truck', 1),
(4, 'O5f05eb4095af10.27286419', 4, 'Ubicaciones', 4, 'fa fa-database', 1),
(5, 'O5f05eb4097ace5.11347275', 5, 'Facturación', 8, 'fa fa-money', 1),
(6, 'O5f05eb4099d124.33084211', 6, 'Inventario', 7, 'fa fa-table', 1),
(7, 'O5f05eb409bee79.14738654', 7, 'Caja', 12, 'fa fa-money', 1),
(8, 'O5f05eb40a03963.45333314', 8, 'Cuentas por Cobrar', 10, ' fa fa-credit-card', 1),
(9, 'O5f05eb40a23679.51184023', 9, 'Empleados', 3, 'fa fa-users', 1),
(10, 'O5f05eb40a44847.17519732', 10, 'Bancos', 9, 'fa fa-bank', 0),
(11, 'O5f05eb40a65fc6.76958696', 11, 'Compras', 6, 'fa fa-cart-arrow-down', 1),
(12, 'O5f05eb40a876e0.30839970', 12, 'Cuentas por Pagar', 11, 'fa fa-balance-scale', 1),
(13, 'O5f05eb40aa7f76.15900424', 13, 'Cotizaciones', 13, 'fa fa-file-pdf-o', 1),
(14, 'O5f05eb40ac92f6.64346636', 14, 'Traslados', 15, 'fa fa-exchange', 0),
(15, 'O5f05eb40aeb060.65265702', 15, 'Utilidades', 16, 'fa fa-gears', 1),
(16, 'O5f05eb40b0a8a3.32582765', 16, 'Pedidos', 14, 'fa fa-file', 0),
(17, 'O5f05eb40b2ab31.39514402', 17, 'Reportes', 15, 'fa fa-file-pdf-o', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelo`
--

CREATE TABLE `modelo` (
  `id_modelo` int(11) NOT NULL,
  `id_marca` int(11) NOT NULL,
  `modelo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modelo`
--

INSERT INTO `modelo` (`id_modelo`, `id_marca`, `modelo`) VALUES
(-1, -1, 'NO ASIGNADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `nombre` varchar(250) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `filename` varchar(250) DEFAULT NULL,
  `mostrarmenu` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`id_server`, `unique_id`, `id_modulo`, `id_menu`, `nombre`, `descripcion`, `filename`, `mostrarmenu`) VALUES
(1, 'O5f05eb40b557d6.80674465', 1, 2, 'Admin Clientes', 'Admin Clientes', 'admin_cliente.php', 1),
(2, 'O5f05eb40b6cda1.21804727', 2, 2, 'Agregar Cliente', 'Agregar Cliente', 'agregar_cliente.php', 0),
(3, 'O5f05eb40b98960.03796961', 3, 2, 'Editar Cliente', 'Editar Cliente', 'editar_cliente.php', 0),
(4, 'O5f05eb40bb3023.17484334', 4, 0, 'Borrar Cliente', 'Borrar Cliente', 'borrar_cliente.php', 0),
(5, 'O5f05eb40bd51e2.11388209', 5, 2, 'Ver Cliente', 'Ver Cliente', 'ver_cliente.php', 0),
(6, 'O5f05eb40bf6df6.08826724', 6, 1, 'Admin Productos', 'Admin Productos', 'admin_producto.php', 1),
(7, 'O5f05eb40c17e84.99146662', 7, 1, 'Agregar Producto', 'Agregar Producto', 'agregar_producto.php', 0),
(8, 'O5f05eb40c396a4.82413968', 8, 1, 'Editar Producto', 'Editar Producto', 'editar_producto.php', 0),
(9, 'O5f05eb40c5a989.22673514', 9, 0, 'Borrar Producto', 'Borrar Producto', 'borrar_producto.php', 0),
(10, 'O5f05eb40c7c621.03604395', 10, 1, 'Ver Producto', 'Ver Producto', 'ver_producto.php', 0),
(11, 'O5f05eb40c9e083.97450774', 11, 1, 'Admin Categorías', 'Admin Categorías', 'admin_categoria.php', 1),
(12, 'O5f05eb40cbecc6.75279752', 12, 1, 'Agregar Categoría', 'Agregar Categoría', 'agregar_categoria.php', 0),
(13, 'O5f05eb40ce1fd4.53208572', 13, 1, 'Editar Categoría', 'Editar Categoría', 'editar_categoria.php', 0),
(14, 'O5f05eb40d03081.95012102', 14, 1, 'Borrar Categoría', 'Borrar Categoría', 'borrar_categoria.php', 0),
(15, 'O5f05eb40d22bd6.81663215', 15, 1, 'Admin Presentaciones', 'Admin Presentaciones', 'admin_presentacion.php', 1),
(16, 'O5f05eb40d44094.61197172', 16, 1, 'Agregar Presentación', 'Agregar Presentación', 'agregar_presentacion.php', 0),
(17, 'O5f05eb40d66cd4.02334532', 17, 1, 'Editar Presentación', 'Editar Presentación', 'editar_presentacion.php', 0),
(18, 'O5f05eb40d880b1.22139139', 18, 0, 'Borrar Presentación', 'Borrar Presentación', 'borrar_presentacion.php', 0),
(19, 'O5f05eb40da9501.59194579', 19, 3, 'Admin Proveedores', 'Admin Proveedores', 'admin_proveedor.php', 1),
(20, 'O5f05eb40dcaa91.12494562', 20, 3, 'Agregar Proveedor', 'Agregar Proveedor', 'agregar_proveedor.php', 0),
(21, 'O5f05eb40debff4.96361423', 21, 3, 'Editar Proveedor', 'Editar Proveedor', 'editar_proveedor.php', 0),
(22, 'O5f05eb40e0c888.43765922', 22, 3, 'Borrar Proveedor', 'Borrar Proveedor', 'borrar_proveedor.php', 0),
(23, 'O5f05eb40e2d962.77880686', 23, 3, 'Ver Proveedor', 'Ver Proveedor', 'ver_proveedor.php', 0),
(24, 'O5f05eb40e4f194.15626220', 24, 4, 'Ubicaciones', 'Administrar Ubicaciones', 'admin_ubicacion.php', 1),
(25, 'O5f05eb40e708d2.43703360', 25, 4, 'Agregar Ubicación', 'Agregar  Ubicación', 'agregar_ubicacion.php', 0),
(26, 'O5f05eb40e91652.56127768', 26, 4, 'Editar Ubicación', 'Editar Ubicación', 'editar_ubicacion.php', 0),
(27, 'O5f05eb40eb23c0.97318258', 27, 4, 'Borrar Ubicación', 'Borrar Ubicación', 'borrar_ubicacion.php', 0),
(28, 'O5f05eb40ed3615.50647172', 28, 4, 'Estantes', 'Administrar Estantes', 'admin_estante.php', 1),
(29, 'O5f05eb40ef7843.15126785', 29, 4, 'Agregar Estante', 'Agregar Estante', 'agregar_estante.php', 0),
(30, 'O5f05eb40f16e09.24203944', 30, 4, 'Editar Estante', 'Editar  Estante', 'editar_estante.php', 0),
(31, 'O5f05eb40f37b60.00827457', 31, 4, 'Borrar Estante', 'Borrar  Estante', 'borrar_estante.php', 0),
(32, 'O5f05eb41017006.00931282', 32, 4, 'Admin Asignación', 'Admin Asignación', 'admin_asignacion.php', 1),
(33, 'O5f05eb41039f57.28698843', 33, 4, 'Agregar Asignación', 'Agregar Asignación', 'agregar_asignacion.php', 0),
(34, 'O5f05eb410599d1.56660408', 34, 4, 'Admin no Asignado', 'Admin no Asignado', 'admin_producto_no_asignado.php', 0),
(35, 'O5f05eb4107afe1.63279681', 35, 5, 'Pre Venta', 'Pre Venta', 'preventa.php', 0),
(36, 'O5f05eb4109c499.45761506', 36, 5, 'Venta', 'Venta', 'venta.php', 1),
(37, 'O5f05eb410bf365.82456772', 37, 6, 'Cargas de Inventario', 'Cargas de Inventario', 'ingreso_inventario.php', 1),
(38, 'O5f05eb410df348.43628305', 38, 6, 'Descargos de Inventario', 'Descargos de Inventario', 'descargo_inventario.php', 1),
(39, 'O5f05eb41100792.71390260', 39, 4, 'Movimientos', 'Movimientos', 'admin_movimiento_asignacion.php', 1),
(40, 'O5f05eb41121077.53639053', 40, 4, 'Ver Detalle Movimiento', 'Ver Detalle Movimiento', 'ver_detalle_mov.php', 0),
(41, 'O5f05eb41143fe3.20302729', 41, 7, 'Admin Corte', 'Admin Corte', 'admin_corte.php', 1),
(42, 'O5f05eb41163996.99684593', 42, 7, 'Admin Caja', 'Admin Caja', 'admin_caja.php', 1),
(43, 'O5f05eb41184aa5.98721589', 43, 8, 'Admin Créditos', 'Admin Créditos', 'admin_credito.php', 0),
(44, 'O5f05eb411a6e67.01786190', 44, 8, 'Abono Crédito', 'Abono Crédito', 'abono_credito.php', 0),
(45, 'O5f05eb411c9bd0.47966233', 45, 9, 'Admin Empleados', 'Admin Empleados', 'admin_empleado.php', 1),
(46, 'O5f05eb411e9dc6.09963749', 46, 9, 'Agregar Empleado', 'Agregar Empleado', 'agregar_empleado.php', 0),
(47, 'O5f05eb4120b712.48363516', 47, 9, 'Editar Empleado', 'Editar Empleado', 'editar_empleado.php', 0),
(48, 'O5f05eb4122d0f2.55169895', 48, 9, 'Borrar Empleado', 'Borrar Empleado', 'borrar_empleado.php', 0),
(49, 'O5f05eb4124fb23.66721793', 49, 9, 'Ver Empleado', 'Ver Empleado', 'ver_empleado.php', 0),
(50, 'O5f05eb4126ebf0.13796136', 50, 6, 'Consultar Stock', 'Consultar Stock', 'admin_stock.php', 1),
(54, 'O5f05eb4128fd77.61425381', 54, 10, 'Admin Bancos', 'Admin Bancos', 'admin_banco.php', 1),
(55, 'O5f05eb412b23e0.76265701', 55, 10, 'Agregar Banco', 'Agregar Banco', 'agregar_banco.php', 0),
(56, 'O5f05eb412d40c4.02090032', 56, 10, 'Editar Banco', 'Editar Banco', 'editar_banco.php', 0),
(57, 'O5f05eb412f42e3.98087482', 57, 10, 'Borrar Banco', 'Borrar Banco', 'borrar_banco.php', 0),
(58, 'O5f05eb41315275.52911793', 58, 10, 'Ver Banco', 'Ver Banco', 'ver_banco.php', 0),
(59, 'O5f05eb41338459.62191627', 59, 10, 'Admin Cuentas', 'Admin Cuentas', 'cuenta_banco.php', 0),
(60, 'O5f05eb4135b335.28674118', 60, 10, 'Agregar Cuenta', 'Agregar Cuenta', 'agregar_cuenta_banco.php', 0),
(61, 'O5f05eb4137c891.96084833', 61, 10, 'Editar Cuenta', 'Editar Cuenta', 'editar_cuenta_banco.php', 0),
(62, 'O5f05eb4139cd34.42773754', 62, 10, 'Borrar Cuenta', 'Borrar Cuenta', 'cuenta_banco.php', 0),
(63, 'O5f05eb413be8a1.21194885', 63, 10, 'Admin Movimientos', 'Admin Movimiento', 'admin_mov_cta_banco.php', 1),
(64, 'O5f05eb413e1a43.57423716', 64, 10, 'Agregar Movimientos', 'Agregar Movimiento', 'agreg_mov_cta_banco.php', 0),
(65, 'O5f05eb41400a87.41266529', 65, 10, 'Editar Movimientos', 'Editar Movimiento', 'editar_mov_cta_banco.php', 0),
(66, 'O5f05eb41421500.84234036', 66, 10, 'Borrar Movimientos', 'Borrar Movimiento', 'borrar_mov_cta_banco.php', 0),
(68, 'O5f05eb41442002.05211020', 68, 11, 'Admin Compras', 'Admin Compras', 'admin_compras_fecha.php', 1),
(69, 'O5f05eb41464be4.30941382', 69, 6, 'Ajuste de Inventario', 'Ajuste de Inventario', 'ajuste_inventario.php', 1),
(70, 'O5f05eb41484aa6.80497387', 70, 6, 'Reporte Ajuste', 'Reporte Ajuste', 'reporte_ajuste.php', 0),
(71, 'O5f05eb414a6d19.90850503', 71, 0, 'Hoja de conteo', 'Hoja de conteo', 'hoja_conteo.php', 0),
(77, 'O5f05eb41570b15.16047866', 77, 13, 'Admin Cotizaciones', 'Admin Cotizaciones', 'admin_cotizacion.php', 1),
(78, 'O5f05eb41591212.80473418', 78, 13, 'Agregar Cotización', 'Agregar Cotización', 'agregar_cotizacion.php', 0),
(79, 'O5f05eb415b1261.17905602', 79, 13, 'Editar Cotización', 'Editar Cotización', 'editar_cotizacion.php', 0),
(80, 'O5f05eb415d3ef8.42416532', 80, 13, 'Borrar Cotización', 'Borrar Cotización', 'borrar_cotizacion.php', 0),
(81, 'O5f05eb415f4df1.95021631', 81, 13, 'Imprimir Cotización', 'Imprimir Cotización', 'cotizacion.php', 0),
(82, 'O5f05eb41615a01.32542084', 82, 11, 'Agregar Compra', 'Agregar Compra', 'compras.php', 0),
(83, 'O5f05eb416382a4.10908511', 83, 11, 'Ver Compra', 'Ver Compra', 'ver_compra.php', 0),
(84, 'O5f05eb41658b06.18552533', 84, 6, 'Admin Ajuste', 'Admin Ajuste', 'admin_ajuste.php', 1),
(85, 'O5f05eb41678f03.39197378', 85, 14, 'Admin Traslado', 'Admin Traslado', 'admin_traslados.php', 1),
(86, 'O5f05eb4169aa19.80914362', 86, 14, 'Realizar Traslado', 'Realizar Traslado', 'traslado_producto.php', 0),
(87, 'O5f05eb416bbe71.68828167', 87, 14, 'Anular Traslado ', 'Anular Traslado ', 'anular_traslado.php', 0),
(88, 'O5f05eb417055d7.64623279', 88, 14, 'Ver Traslado ', 'Ver Traslado ', 'ver_traslado.php', 0),
(89, 'O5f05eb41728117.72018645', 89, 14, 'Reporte Traslado ', 'Reporte Traslado ', 'reporte_traslado.php', 0),
(90, 'O5f05eb4174a232.29328678', 90, 14, 'Reporte Traslado Recibido ', 'Reporte Traslado ', 'reporte_traslado.php', 0),
(91, 'O5f05eb4176a4d4.64882050', 91, 14, 'Recibir Traslado', 'Recibir Traslado', 'recibir_traslado.php', 0),
(92, 'O5f05eb4178b359.75549721', 92, 15, 'Admin Usuario', 'Admin Usuario', 'admin_usuarios.php', 1),
(93, 'O5f05eb417ac3a6.66387409', 93, 15, 'Agregar Usuario', 'Agregar Usuario', 'agregar_usuario.php', 0),
(94, 'O5f05eb417cde21.93346982', 94, 15, 'Editar Usuario', 'Editar Usuario', 'editar_usuario.php', 0),
(95, 'O5f05eb417eede2.31067181', 95, 15, 'Borrar Usuario', 'Borrar Usuario', 'borrar_usuario.php', 0),
(96, 'O5f05eb4180fce8.80401493', 96, 15, 'Permisos Usuario', 'Permisos Usuario', 'permiso_usuario.php', 0),
(97, 'O5f05eb41830fa2.52731268', 97, 15, 'Admin Empresa', 'Admin Empresa', 'admin_empresa.php', 1),
(98, 'O5f05eb41851923.59364838', 98, 16, 'Admin Pedidos', 'Admin Pedidos', 'admin_pedido.php', 1),
(99, 'O5f05eb4188d972.21223503', 99, 16, 'Agregar Pedido', 'Agregar Pedido', 'agregar_pedido.php', 0),
(100, 'O5f05eb418ae261.13604734', 100, 16, 'Editar Pedido', 'Editar Pedido', 'editar_pedido.php', 0),
(101, 'O5f05eb418cfd29.95596321', 101, 16, 'Borrar Pedido', 'Anular Pedido', 'borrar_pedido.php', 0),
(103, 'O5f05eb418f0a05.83103610', 103, 16, 'Reporte Pedido', 'Reporte Pedido', 'reporte_pedido.php', 0),
(106, 'O5f05eb41912c30.67486844', 106, 5, 'Admin Facturas', 'Admin Facturas', 'admin_factura_rangos.php', 1),
(107, 'O5f05eb419328c2.90434042', 107, 5, 'Ver Factura', 'Ver Factura', 'ver_factura.php', 0),
(108, 'O5f05eb41953a69.64081182', 108, 5, 'Reimprimir Factura ', 'Reimprimir Factura ', 'reimprimir_factura.php', 0),
(109, 'O5f05eb419750b4.84898084', 109, 5, 'Anular Factura ', 'Anular Factura ', 'anular_factura.php', 0),
(110, 'O5f05eb419974d0.00386389', 110, 5, 'Devolución', 'Devolución', 'devolucion.php', 0),
(111, 'O5f05eb419b7319.33813009', 111, 7, 'Admin Movimiento Caja', 'Admin Movimiento Caja', 'admin_movimiento_caja.php', 1),
(112, 'O5f05eb419da677.46993171', 112, 7, 'Agregar ingreso caja', 'Agregar ingreso caja', 'agregar_ingreso_caja.php', 0),
(113, 'O5f05eb41a14bb8.59855440', 113, 7, 'Agregar salida caja', 'Agregar salida caja', 'agregar_salida_caja.php', 0),
(114, 'O5f05eb41a1f576.38455241', 114, 7, 'Editar Movimiento caja', 'Editar Movimiento caja', 'editar_movimiento_caja.php', 0),
(115, 'O5f05eb41a3cf93.32857213', 115, 7, 'Imprimir Movimiento', 'Imprimir Movimiento', 'imprimir_movimiento.php', 0),
(116, 'O5f05eb41a46e63.60453754', 116, 7, 'Borrar Movimiento caja', 'Borrar Movimiento caja', 'Borrar_movimiento_caja.php', 0),
(117, 'O5f05eb41a64563.68833884', 117, 7, 'Corte de Caja', 'Corte de caja diario', 'corte_caja_diario.php', 0),
(118, 'O5f05eb41a6e815.94144447', 118, 5, 'Facturas Pendientes', 'Admin Facturas Pendientes', 'admin_pendiente_rangos.php', 1),
(119, 'O5f05eb41a8f638.74970180', 119, 0, 'Hoja de conteo', 'Hoja de conteo', 'generar_hoja.php', 0),
(120, 'O5f05eb41ab09a6.86058616', 120, 5, 'Admin Devoluciones', 'Devoluciones ', 'admin_devoluciones_rangos.php', 0),
(121, 'O5f05eb41ad25f9.66536186', 121, 17, 'Kardex', 'Reporte Kardex', 'reporte_kardex.php', 1),
(122, 'O5f05eb41af3188.68776028', 122, 17, 'Inventario', 'Inventario', 'ver_reporte_inventario.php', 1),
(123, 'O5f05eb41b25740.72140719', 123, 0, 'Libro de compras ', 'Libro de compras ', 'ver_libro_compras.php', 0),
(124, 'O5f05eb41b46565.91156608', 124, 0, 'Reposición de producto', 'Reposición de producto', 'admin_movimiento_pendiente.php', 0),
(126, 'O5f05eb41b68401.52350919', 126, 17, 'Kardex General', 'Reporte Kardex General', 'reporte_kardex_general.php', 1),
(127, 'O5f05eb41b88981.27862250', 127, 0, 'Resumen de vales', 'Resumen de vales', 'resumen_vale.php', 0),
(128, 'O5f05eb41ba97c1.56833042', 128, 0, 'Ventas a Contribuyentes', 'Libro de Ventas a Contribuyentes', 'ver_libro_ventas_a_contribuyente.php', 0),
(129, 'O5f05eb41bcaf98.66472746', 129, 0, 'Ventas a Consumidores', 'Libro de Ventas a Consumidores', 'ver_libro_ventas_a_consumidores.php', 0),
(130, 'O5f05eb41becfb1.85061161', 130, 0, 'Reporte Fiscal', 'Reporte Fiscal', 'ver_reporte_fiscal.php', 0),
(132, 'O5f05eb41c369d4.28550866', 132, 7, 'Apertura de caja', 'Apertura de caja', 'apertura_caja.php', 0),
(133, 'O5f05eb41c575d4.30502875', 133, 6, 'Administrar lotes', 'administrar lotes', 'admin_lotes.php', 1),
(134, 'O5f05eb41c78835.16429705', 134, 12, 'Admin cuentas por pagar', 'Admin cuentas por pagar', 'admin_cxp_p.php', 1),
(135, 'O5f05eb41c99d36.08775523', 135, 0, 'Admin cuentas por pagar proveedor', 'Admin cuentas por pagar proveedor', 'admin_cxp.php', 0),
(136, 'O5f05eb41cba1b5.11522091', 136, 4, 'Agregar Reasignación', 'Agregar Reasignación', 'agregar_reasignacion.php', 0),
(137, 'O5f05eb41cda5c0.73067800', 137, 15, 'Backup', 'Backup', 'backup.php', 1),
(138, 'O5f05eb41cfc287.66689246', 138, 0, 'Ticket de auditoria', 'Ticket de auditoria', 'ticket_dia.php', 0),
(139, 'O5f05eb41d1db81.80041399', 139, 0, 'Depuracion', 'Depuracion', 'depuracion.php', 0),
(140, 'O5f05eb41d3fad3.12141556', 140, 4, 'Admin transferencias', 'Admin transferencias', 'admin_transferencia.php', 1),
(141, 'O5f05eb41d5f6a5.72491309', 141, 4, 'Agregar Transferencia', 'Agregar Transferencia', 'agregar_transferencia.php', 0),
(142, 'O5f05eb41d81a81.90248161', 142, 5, 'Admin Autorización', 'Admin Autorización', 'admin_autorizacion.php', 1),
(143, 'O5f05eb41dbe234.90974124', 143, 5, 'Agregar Autorización', 'Agregar Autorización', 'agregar_autorizacion.php', 0),
(146, 'O5f05eb41dbe234.90974125', 144, 17, 'Reporte Ingresos y Egresos', 'Reporte Ingresos y Egresos', 'reporte_entrada_salida.php', 1),
(147, 'O5f05eb41dbe234.90974126', 145, 17, 'Reporte de utilidades', 'Reporte de utilidades', 'ver_reporte_utilidad.php', 1),
(144, 'O5f05eb41dbe234.90974127', 146, 17, 'Reporte de Reposicion', 'Reporte de Reposicion', 'ver_reporte_reposicion.php', 1),
(145, 'O5f05eb41dbe234.90974128', 147, 17, 'Reporte de utilidades Por dia', 'Reporte de utilidades Por dia', 'ver_reporte_utilidades_diarias.php', 1),
(68, 'O5f05eb41442002.05211020', 151, 11, 'Admin Compras Guardada', 'Admin Compras Guardada', 'admin_compra_guardada.php', 1),
(0, '', 152, 17, 'Reporte Z', 'Reporte Z', 'reportez.php', 1),
(138, 'O5f05eb41cfc287.66689246', 153, 17, 'Ticket de auditoria', 'Ticket de auditoria', 'ticket_dia.php', 1),
(134, 'O5f05eb41c78835.16429705', 154, 12, 'Cuentas por pagar Proveedor', 'Cuentas por pagar Proveedor', 'admin_cxp.php', 0),
(134, 'O5f05eb41c78835.16429705', 155, 12, 'Realizar Abono', 'Realizar Abono', 'realizar_abono.php', 0),
(0, '', 156, 17, 'Reportes de facturas y estados de cuenta', 'Reportes de facturas y estados de cuenta', 'ver_reporte_ventas.php', 1),
(0, '', 157, 17, 'Reporte fiscal', 'Reporte fiscal', 'ver_reporte_fiscal.php', 1),
(0, '', 158, 17, 'Libro de compras', 'Libro de compras', 'ver_libro_compras.php', 1),
(0, '', 159, 17, 'Ventas a consumidores finales', 'Ventas a consumidores finales', 'ver_libro_ventas_a_consumidores.php', 1),
(0, '', 160, 17, 'Ventas a Contribuyentes', 'Ventas a Contribuyentes', 'ver_libro_ventas_a_contribuyente.php', 1),
(0, 'O5f05eb41c78835.16429705', 161, 17, 'Reporte de ventas de productos por proveedor', 'Reporte de ventas de cada mes de cada producto por el proveedor seleccionado en un rango de fechas', 'ver_reporte_productos_proveedor.php', 1),
(0, 'O5f05eb41c78835.16429705', 162, 17, 'Reporte de ventas totales por proveedor', 'Reporte de total con y sin IVA de las ventas por mes de el proveedor seleccionado.', 'ver_reporte_ventas_proveedor.php', 1),
(1, 'O5f05eb41c78835.16429705', 163, 17, 'Reporte de ventas de producto por cliente', 'Reporte de ventas de producto por cliente', 'ver_reporte_productos_cliente.php', 1),
(97, 'O5f05eb41830fa2.52731268', 164, 15, 'Admin Sucursales', 'Admin Sucursales', 'admin_sucursal.php', 1),
(36, 'O5f05eb4109c499.45761506', 165, 5, 'Venta X Cuotas', 'Venta X Cuotas', 'venta_cuotas.php', 0),
(133, 'O5f05eb41c575d4.30502875', 170, 6, 'Traslado Prod. a Sucursal', 'Traslado Productos a otra a Sucursal', 'admin_traslados.php', 1),
(117, 'O5f05eb41a64563.78833867', 171, 7, 'Corte de Caja Pendiente', 'Corte de Caja Pendiente', 'admin_apertura_nofin.php', 1),
(43, 'O5f05eb41184aa5.98721589', 172, 8, 'Admin Abono Créditos', 'Admin Abono Créditos', 'admin_abono_credito.php', 1),
(43, 'O5f05eb41184aa5.98721589', 173, 8, 'Admin Abono Cuotas', 'Admin Abono Cuotas', 'admin_abono_cuota.php', 0),
(6, 'O5f05eb40bf6df6.08826725', 174, 1, 'Admin Marcas', 'Admin Marcas', 'admin_marca.php', 1),
(6, 'O5f05eb40bf6df6.08826724', 175, 1, 'Agregar Marcas', 'Agregar Marcas', 'agregar_marca.php', 0),
(6, 'O5f05eb40bf6df6.08826717', 176, 1, 'Editar Marcas', 'Editar  Marcas', 'editar_marca.php', 0),
(6, 'O5f05eb40bf6df6.18826737', 177, 1, 'Borrar Marcas', 'Borrar  Marcas', 'borrar_marca.php', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento_caja_tipo`
--

CREATE TABLE `movimiento_caja_tipo` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `ingreso` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento_producto`
--

CREATE TABLE `movimiento_producto` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_movimiento` int(11) NOT NULL,
  `correlativo` varchar(20) NOT NULL,
  `concepto` varchar(250) NOT NULL,
  `total` float NOT NULL,
  `tipo` varchar(13) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `proceso` varchar(50) NOT NULL,
  `referencia` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_suc_origen` int(11) NOT NULL,
  `id_suc_destino` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_traslado` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `conteo` varchar(100) NOT NULL,
  `sistema` varchar(100) NOT NULL,
  `numero_doc` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimiento_producto`
--

INSERT INTO `movimiento_producto` (`id_server`, `unique_id`, `id_sucursal`, `id_movimiento`, `correlativo`, `concepto`, `total`, `tipo`, `proceso`, `referencia`, `id_empleado`, `fecha`, `hora`, `id_suc_origen`, `id_suc_destino`, `id_proveedor`, `id_compra`, `id_traslado`, `id_factura`, `numero`, `conteo`, `sistema`, `numero_doc`) VALUES
(0, 'S64a84e7a6f9c36.02125218', 1, 1, '0000001_II', 'INVENTARIO INICIAL', 100, 'ENTRADA', 'II', 1, 1, '2023-07-07', '11:42:18', 1, 1, 0, 0, 0, 0, 0, '', '', ''),
(0, 'S64a8502b51b293.31603909', 1, 2, '0000000001_CCF', 'VENTA', 55.37, 'SALIDA', 'CCF', 1, 1, '2023-07-07', '11:49:31', 1, 1, 0, 0, 0, 1, 0, '', '', ''),
(0, 'S64a879f45cbce4.88339406', 1, 3, '0000000002_CCF', 'VENTA', 29.38, 'SALIDA', 'CCF', 2, 1, '2023-07-07', '14:47:48', 1, 1, 0, 0, 0, 2, 0, '', '', ''),
(0, 'S64a879f460cf31.71730992', 1, 4, '0000000003_CCF', 'VENTA', 29.38, 'SALIDA', 'CCF', 3, 1, '2023-07-07', '14:47:48', 1, 1, 0, 0, 0, 3, 0, '', '', ''),
(0, 'S64a9a15dd7ba47.51879703', 1, 5, '0000000001_COF', 'VENTA', 34, 'SALIDA', 'COF', 1, 1, '2023-07-08', '11:48:13', 1, 1, 0, 0, 0, 4, 0, '', '', ''),
(0, 'S64a9e64ba117d9.64843347', 2, 6, '0000001_II', 'INVENTARIO INICIAL', 100, 'ENTRADA', 'II', 1, 7, '2023-07-08', '16:42:19', 2, 2, 0, 0, 0, 0, 0, '', '', ''),
(0, 'S64a9e74333b6c7.25084424', 2, 7, '0000000001_COF', 'VENTA', 54, 'SALIDA', 'COF', 1, 7, '2023-07-08', '16:46:27', 2, 2, 0, 0, 0, 5, 0, '', '', ''),
(0, 'S64ac134dc25d86.70086765', 1, 8, '0000000000_TIK', 'VENTA', 4, 'SALIDA', 'TIK', 0, 1, '2023-07-10', '08:18:53', 1, 1, 0, 0, 0, 6, 0, '', '', ''),
(0, 'S64ac1375db0df5.88801649', 1, 9, '0000000001_TIK', 'VENTA', 4, 'SALIDA', 'TIK', 1, 1, '2023-07-10', '08:19:33', 1, 1, 0, 0, 0, 7, 0, '', '', ''),
(0, 'S64ac2ff5b9d847.38732351', 2, 10, '0000000000_TIK', 'VENTA', 16, 'SALIDA', 'TIK', 0, 7, '2023-07-10', '10:21:09', 2, 2, 0, 0, 0, 8, 0, '', '', ''),
(0, 'S64ac306e8734c8.25303041', 1, 11, '0000000002_TIK', 'VENTA', 42, 'SALIDA', 'TIK', 2, 1, '2023-07-10', '10:23:10', 1, 1, 0, 0, 0, 9, 0, '', '', ''),
(0, 'S64ac30a75ccb92.44685592', 2, 12, '0000000001_TIK', 'VENTA', 22.5, 'SALIDA', 'TIK', 1, 7, '2023-07-10', '10:24:07', 2, 2, 0, 0, 0, 10, 0, '', '', ''),
(0, 'S64b5cee153cc92.36549211', 1, 13, '0000000001_TIK', 'VENTA', 20, 'SALIDA', 'TIK', 1, 3, '2023-07-17', '17:29:37', 1, 1, 0, 0, 0, 11, 0, '', '', ''),
(0, 'S64b5cf36e21223.72311471', 1, 14, '0000000001_TIK', 'VENTA', 20, 'SALIDA', 'TIK', 1, 2, '2023-07-17', '17:31:02', 1, 1, 0, 0, 0, 12, 0, '', '', ''),
(0, 'S64b85da0b97254.94876679', 1, 15, '0000000002_TIK', 'VENTA', 2, 'SALIDA', 'TIK', 2, 2, '2023-07-19', '16:03:12', 1, 1, 0, 0, 0, 13, 0, '', '', ''),
(0, 'S64b860bd492856.95904465', 1, 16, '0000000002_TIK', 'VENTA', 4, 'SALIDA', 'TIK', 2, 3, '2023-07-19', '16:16:29', 1, 1, 0, 0, 0, 14, 0, '', '', ''),
(0, 'S64b966eecaa267.79482562', 1, 17, '0000000003_TIK', 'VENTA', 2, 'SALIDA', 'TIK', 3, 3, '2023-07-20', '10:55:10', 1, 1, 0, 0, 0, 15, 0, '', '', ''),
(0, 'S64b9677e904761.60469330', 1, 18, '0000000004_TIK', 'VENTA', 2, 'SALIDA', 'TIK', 4, 3, '2023-07-20', '10:57:34', 1, 1, 0, 0, 0, 16, 0, '', '', ''),
(0, 'S64b968e636bbd0.66649135', 1, 19, '0000000005_TIK', 'VENTA', 10, 'SALIDA', 'TIK', 5, 3, '2023-07-20', '11:03:34', 1, 1, 0, 0, 0, 17, 0, '', '', ''),
(0, 'S64b9694f1f8a53.07994826', 1, 20, '0000000006_TIK', 'VENTA', 4, 'SALIDA', 'TIK', 6, 3, '2023-07-20', '11:05:19', 1, 1, 0, 0, 0, 18, 0, '', '', ''),
(0, 'S64b969a3acaef0.14473220', 1, 21, '0000000001_CCF', 'VENTA', 4.52, 'SALIDA', 'CCF', 1, 3, '2023-07-20', '11:06:43', 1, 1, 0, 0, 0, 19, 0, '', '', ''),
(0, 'S64b969f032fd24.04098517', 1, 22, '0000000001_COF', 'VENTA', 2, 'SALIDA', 'COF', 1, 3, '2023-07-20', '11:08:00', 1, 1, 0, 0, 0, 20, 0, '', '', ''),
(0, 'S64bfdcb51dfd76.94833504', 1, 23, '0000002_II', 'COMPRA DE PRODUCTO', 10, 'ENTRADA', 'II', 2, 1, '2023-07-25', '08:31:17', 1, 1, 1, 1, 0, 0, 0, '', '', ''),
(0, 'S64bfe1dee66e52.00991976', 1, 24, '0000003_II', 'COMPRA DE PRODUCTO', 10, 'ENTRADA', 'II', 3, 1, '2023-07-25', '08:53:18', 1, 1, 1, 2, 0, 0, 0, '', '', ''),
(0, 'S64bfe1fb413db8.31664401', 1, 25, '0000004_II', 'COMPRA DE PRODUCTO', 10, 'ENTRADA', 'II', 4, 1, '2023-07-25', '08:53:47', 1, 1, 1, 3, 0, 0, 0, '', '', ''),
(0, 'S64bfe298682ed7.04604406', 1, 26, '0000005_II', 'COMPRA DE PRODUCTO', 10, 'ENTRADA', 'II', 5, 1, '2023-07-25', '08:56:24', 1, 1, 1, 4, 0, 0, 0, '', '', ''),
(0, 'S64bfe527617cf4.31494176', 1, 27, '0000006_II', 'COMPRA DE PRODUCTO', 10, 'ENTRADA', 'II', 6, 1, '2023-07-25', '09:07:19', 1, 1, 1, 5, 0, 0, 0, '', '', ''),
(0, 'S64bfe7e2578f43.08027389', 1, 28, '0000007_II', 'COMPRA DE PRODUCTO', 15, 'ENTRADA', 'II', 7, 1, '2023-07-25', '09:18:58', 1, 1, 1, 6, 0, 0, 0, '', '', ''),
(0, 'S64bfe830daa8f0.36042296', 1, 29, '0000008_II', 'COMPRA DE PRODUCTO', 20, 'ENTRADA', 'II', 8, 1, '2023-07-25', '09:20:16', 1, 1, 1, 7, 0, 0, 0, '', '', ''),
(0, 'S64c009396bc7e1.25664169', 1, 30, '0000000003_TIK', 'VENTA', 20, 'SALIDA', 'TIK', 3, 2, '2023-07-25', '11:41:13', 1, 1, 0, 0, 0, 21, 0, '', '', ''),
(0, 'S64c009915d2bb3.33386461', 1, 31, '0000000004_TIK', 'VENTA', 2, 'SALIDA', 'TIK', 4, 2, '2023-07-25', '11:42:41', 1, 1, 0, 0, 0, 22, 0, '', '', ''),
(0, 'S64c02820f39079.50897260', 1, 32, '0000000005_TIK', 'VENTA', 2, 'SALIDA', 'TIK', 5, 2, '2023-07-25', '13:53:04', 1, 1, 0, 0, 0, 23, 0, '', '', ''),
(0, 'S64c02dc38d6147.38913690', 1, 33, '0000000001_TIK', 'VENTA', 2, 'SALIDA', 'TIK', 1, 4, '2023-07-25', '14:17:07', 1, 1, 0, 0, 0, 24, 0, '', '', ''),
(0, 'S64c02dd3333b14.69259770', 1, 34, '0000000002_TIK', 'VENTA', 2, 'SALIDA', 'TIK', 2, 4, '2023-07-25', '14:17:23', 1, 1, 0, 0, 0, 25, 0, '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento_producto_detalle`
--

CREATE TABLE `movimiento_producto_detalle` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_detalle` int(11) NOT NULL,
  `id_movimiento` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_server_prod` int(11) NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `id_server_presen` int(11) NOT NULL,
  `cantidad` decimal(11,4) NOT NULL,
  `costo` float NOT NULL,
  `precio` float NOT NULL,
  `stock_anterior` decimal(11,4) NOT NULL,
  `stock_actual` decimal(11,4) NOT NULL,
  `proceso` varchar(50) NOT NULL,
  `referencia` int(11) NOT NULL,
  `lote` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimiento_producto_detalle`
--

INSERT INTO `movimiento_producto_detalle` (`id_server`, `unique_id`, `id_sucursal`, `id_detalle`, `id_movimiento`, `id_producto`, `id_server_prod`, `id_presentacion`, `id_server_presen`, `cantidad`, `costo`, `precio`, `stock_anterior`, `stock_actual`, `proceso`, `referencia`, `lote`, `fecha`, `hora`) VALUES
(0, 'S64a84e7a701c30.79813119', 0, 1, 1, 1, 0, 1, 0, '100.0000', 1, 2, '0.0000', '100.0000', '', 0, 1, '2023-07-07', '11:42:18'),
(0, 'S64a8502b58c3f2.89634676', 0, 2, 2, 1, 0, 1, 0, '12.0000', 1, 2, '100.0000', '88.0000', '', 0, 0, '2023-07-07', '11:49:31'),
(0, 'S64a879f45eb449.42816377', 0, 3, 3, 1, 0, 1, 0, '8.0000', 1, 2, '88.0000', '80.0000', '', 0, 0, '2023-07-07', '14:47:48'),
(0, 'S64a879f46261f2.47640666', 0, 4, 4, 1, 0, 1, 0, '8.0000', 1, 2, '80.0000', '72.0000', '', 0, 0, '2023-07-07', '14:47:48'),
(0, 'S64a9a15dda6141.96640115', 0, 5, 5, 1, 0, 1, 0, '12.0000', 1, 2, '72.0000', '60.0000', '', 0, 0, '2023-07-08', '11:48:13'),
(0, 'S64a9e64ba3c416.40422513', 0, 6, 6, 1, 0, 1, 0, '100.0000', 1, 2, '0.0000', '100.0000', '', 0, 2, '2023-07-08', '16:42:19'),
(0, 'S64a9e7433f0466.21241472', 0, 7, 7, 1, 0, 1, 0, '12.0000', 1, 2, '100.0000', '88.0000', '', 0, 0, '2023-07-08', '16:46:27'),
(0, 'S64ac134dc3bde6.39547375', 0, 8, 8, 1, 0, 1, 0, '2.0000', 1, 2, '60.0000', '58.0000', '', 0, 0, '2023-07-10', '08:18:53'),
(0, 'S64ac1375ddac93.51683440', 0, 9, 9, 1, 0, 1, 0, '2.0000', 1, 2, '58.0000', '56.0000', '', 0, 0, '2023-07-10', '08:19:33'),
(0, 'S64ac2ff5be5e45.75172697', 0, 10, 10, 1, 0, 1, 0, '8.0000', 1, 2, '88.0000', '80.0000', '', 0, 0, '2023-07-10', '10:21:09'),
(0, 'S64ac306e8a2053.41272270', 0, 11, 11, 1, 0, 1, 0, '16.0000', 1, 2, '56.0000', '40.0000', '', 0, 0, '2023-07-10', '10:23:10'),
(0, 'S64ac30a76452a9.10201528', 0, 12, 12, 1, 0, 1, 0, '10.0000', 1, 2, '80.0000', '70.0000', '', 0, 0, '2023-07-10', '10:24:07'),
(0, 'S64b5cee156af71.39212720', 0, 13, 13, 1, 0, 1, 0, '10.0000', 1, 2, '40.0000', '30.0000', '', 0, 0, '2023-07-17', '17:29:37'),
(0, 'S64b5cf36e43d08.31353610', 0, 14, 14, 1, 0, 1, 0, '10.0000', 1, 2, '30.0000', '20.0000', '', 0, 0, '2023-07-17', '17:31:02'),
(0, 'S64b85da0ba5388.87681316', 0, 15, 15, 1, 0, 1, 0, '1.0000', 1, 2, '20.0000', '19.0000', '', 0, 0, '2023-07-19', '16:03:12'),
(0, 'S64b860bd4b11f5.67693967', 0, 16, 16, 1, 0, 1, 0, '2.0000', 1, 2, '19.0000', '17.0000', '', 0, 0, '2023-07-19', '16:16:29'),
(0, 'S64b966eecb9080.25510115', 0, 17, 17, 1, 0, 1, 0, '1.0000', 1, 2, '17.0000', '16.0000', '', 0, 0, '2023-07-20', '10:55:10'),
(0, 'S64b9677e947056.75569126', 0, 18, 18, 1, 0, 1, 0, '1.0000', 1, 2, '16.0000', '15.0000', '', 0, 0, '2023-07-20', '10:57:34'),
(0, 'S64b968e63bd649.98734640', 0, 19, 19, 1, 0, 1, 0, '5.0000', 1, 2, '15.0000', '10.0000', '', 0, 0, '2023-07-20', '11:03:34'),
(0, 'S64b9694f22b451.26520604', 0, 20, 20, 1, 0, 1, 0, '2.0000', 1, 2, '10.0000', '8.0000', '', 0, 0, '2023-07-20', '11:05:19'),
(0, 'S64b969a3adaed3.96798355', 0, 21, 21, 1, 0, 1, 0, '2.0000', 1, 2, '8.0000', '6.0000', '', 0, 0, '2023-07-20', '11:06:43'),
(0, 'S64b969f03706f2.59325145', 0, 22, 22, 1, 0, 1, 0, '1.0000', 1, 2, '6.0000', '5.0000', '', 0, 0, '2023-07-20', '11:08:00'),
(0, 'S64bfdcb521cf66.18643427', 0, 23, 23, 1, 0, 1, 0, '10.0000', 1, 2, '5.0000', '15.0000', '', 0, 3, '2023-07-25', '08:31:17'),
(0, 'S64bfe1dee8c962.38881452', 0, 24, 24, 1, 0, 1, 0, '10.0000', 1, 2, '15.0000', '25.0000', '', 0, 4, '2023-07-25', '08:53:18'),
(0, 'S64bfe1fb435538.80069727', 0, 25, 25, 1, 0, 1, 0, '10.0000', 1, 2, '25.0000', '35.0000', '', 0, 5, '2023-07-25', '08:53:47'),
(0, 'S64bfe2986a5433.69666389', 0, 26, 26, 1, 0, 1, 0, '10.0000', 1, 2, '35.0000', '45.0000', '', 0, 6, '2023-07-25', '08:56:24'),
(0, 'S64bfe527624cf2.48901206', 0, 27, 27, 1, 0, 1, 0, '10.0000', 1, 2, '45.0000', '55.0000', '', 0, 7, '2023-07-25', '09:07:19'),
(0, 'S64bfe7e259e316.45648952', 0, 28, 28, 1, 0, 1, 0, '15.0000', 1, 2, '55.0000', '70.0000', '', 0, 8, '2023-07-25', '09:18:58'),
(0, 'S64bfe830dc0f14.22735760', 0, 29, 29, 1, 0, 1, 0, '20.0000', 1, 2, '70.0000', '90.0000', '', 0, 9, '2023-07-25', '09:20:16'),
(0, 'S64c009396c9ce4.62621095', 0, 30, 30, 1, 0, 1, 0, '10.0000', 1, 2, '90.0000', '80.0000', '', 0, 0, '2023-07-25', '11:41:13'),
(0, 'S64c0099161b214.24901809', 0, 31, 31, 1, 0, 1, 0, '1.0000', 1, 2, '80.0000', '79.0000', '', 0, 0, '2023-07-25', '11:42:41'),
(0, 'S64c0282100f0a8.68275492', 0, 32, 32, 1, 0, 1, 0, '1.0000', 1, 2, '79.0000', '78.0000', '', 0, 0, '2023-07-25', '13:53:04'),
(0, 'S64c02dc38e1574.08912759', 0, 33, 33, 1, 0, 1, 0, '1.0000', 1, 2, '78.0000', '77.0000', '', 0, 0, '2023-07-25', '14:17:07'),
(0, 'S64c02dd336ece8.12609779', 0, 34, 34, 1, 0, 1, 0, '1.0000', 1, 2, '77.0000', '76.0000', '', 0, 0, '2023-07-25', '14:17:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento_producto_pendiente`
--

CREATE TABLE `movimiento_producto_pendiente` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_detalle` int(11) NOT NULL,
  `id_movimiento` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_server_prod` int(11) NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `id_server_presen` int(11) NOT NULL,
  `cantidad` decimal(11,4) NOT NULL,
  `costo` decimal(11,4) NOT NULL,
  `precio` decimal(11,4) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `repuesto` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento_stock_ubicacion`
--

CREATE TABLE `movimiento_stock_ubicacion` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_movimiento` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_server_prod` int(11) NOT NULL,
  `id_origen` int(11) NOT NULL,
  `id_destino` int(11) NOT NULL,
  `cantidad` decimal(11,4) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `anulada` int(11) NOT NULL,
  `afecta` int(11) NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `id_server_presen` int(11) NOT NULL,
  `id_mov_prod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimiento_stock_ubicacion`
--

INSERT INTO `movimiento_stock_ubicacion` (`id_server`, `unique_id`, `id_sucursal`, `id_movimiento`, `id_producto`, `id_server_prod`, `id_origen`, `id_destino`, `cantidad`, `fecha`, `hora`, `anulada`, `afecta`, `id_presentacion`, `id_server_presen`, `id_mov_prod`) VALUES
(0, 'S64a84e7a70afe6.19739364', 1, 1, 1, 0, 0, 1, '100.0000', '2023-07-07', '11:42:18', 0, 0, 1, 0, 1),
(0, 'S64a8502b563b99.95569543', 1, 2, 1, 0, 1, 0, '12.0000', '2023-07-07', '11:49:31', 0, 0, 1, 0, 2),
(0, 'S64a879f45e72b7.11361848', 1, 3, 1, 0, 1, 0, '8.0000', '2023-07-07', '14:47:48', 0, 0, 1, 0, 3),
(0, 'S64a879f46223e9.58858660', 1, 4, 1, 0, 1, 0, '8.0000', '2023-07-07', '14:47:48', 0, 0, 1, 0, 4),
(0, 'S64a9a15dd9efd2.11036989', 1, 5, 1, 0, 1, 0, '12.0000', '2023-07-08', '11:48:13', 0, 0, 1, 0, 5),
(0, 'S64a9e64ba801f6.42083477', 2, 6, 1, 0, 0, 2, '100.0000', '2023-07-08', '16:42:19', 0, 0, 1, 0, 6),
(0, 'S64a9e7433dc9f4.21499140', 2, 7, 1, 0, 2, 0, '12.0000', '2023-07-08', '16:46:27', 0, 0, 1, 0, 7),
(0, 'S64ac134dc34de3.90880452', 1, 8, 1, 0, 1, 0, '2.0000', '2023-07-10', '08:18:53', 0, 0, 1, 0, 8),
(0, 'S64ac1375dcaf44.77104404', 1, 9, 1, 0, 1, 0, '2.0000', '2023-07-10', '08:19:33', 0, 0, 1, 0, 9),
(0, 'S64ac2ff5bdb6b9.22318126', 2, 10, 1, 0, 2, 0, '8.0000', '2023-07-10', '10:21:09', 0, 0, 1, 0, 10),
(0, 'S64ac306e89b947.29888673', 1, 11, 1, 0, 1, 0, '16.0000', '2023-07-10', '10:23:10', 0, 0, 1, 0, 11),
(0, 'S64ac30a762b797.90891655', 2, 12, 1, 0, 2, 0, '10.0000', '2023-07-10', '10:24:07', 0, 0, 1, 0, 12),
(0, 'S64b5cee1561256.12854814', 1, 13, 1, 0, 1, 0, '10.0000', '2023-07-17', '17:29:37', 0, 0, 1, 0, 13),
(0, 'S64b5cf36e3f9a0.38039960', 1, 14, 1, 0, 1, 0, '10.0000', '2023-07-17', '17:31:02', 0, 0, 1, 0, 14),
(0, 'S64b85da0ba1249.49986346', 1, 15, 1, 0, 1, 0, '1.0000', '2023-07-19', '16:03:12', 0, 0, 1, 0, 15),
(0, 'S64b860bd4ab380.36904414', 1, 16, 1, 0, 1, 0, '2.0000', '2023-07-19', '16:16:29', 0, 0, 1, 0, 16),
(0, 'S64b966eecb3994.18408749', 1, 17, 1, 0, 1, 0, '1.0000', '2023-07-20', '10:55:10', 0, 0, 1, 0, 17),
(0, 'S64b9677e93c509.56621676', 1, 18, 1, 0, 1, 0, '1.0000', '2023-07-20', '10:57:34', 0, 0, 1, 0, 18),
(0, 'S64b968e63a23d1.72253193', 1, 19, 1, 0, 1, 0, '5.0000', '2023-07-20', '11:03:34', 0, 0, 1, 0, 19),
(0, 'S64b9694f2217d2.57099240', 1, 20, 1, 0, 1, 0, '2.0000', '2023-07-20', '11:05:19', 0, 0, 1, 0, 20),
(0, 'S64b969a3ad64f5.18632785', 1, 21, 1, 0, 1, 0, '2.0000', '2023-07-20', '11:06:43', 0, 0, 1, 0, 21),
(0, 'S64b969f03589d0.80539238', 1, 22, 1, 0, 1, 0, '1.0000', '2023-07-20', '11:08:00', 0, 0, 1, 0, 22),
(0, 'S64bfdcb5245308.77973048', 1, 23, 1, 0, 0, 3, '10.0000', '2023-07-25', '08:31:17', 0, 0, 1, 0, 23),
(0, 'S64bfe1deea1965.86953764', 1, 24, 1, 0, 0, 1, '10.0000', '2023-07-25', '08:53:18', 0, 0, 1, 0, 24),
(0, 'S64bfe1fb464937.37586686', 1, 25, 1, 0, 0, 1, '10.0000', '2023-07-25', '08:53:47', 0, 0, 1, 0, 25),
(0, 'S64bfe2986bcf76.81604332', 1, 26, 1, 0, 0, 1, '10.0000', '2023-07-25', '08:56:24', 0, 0, 1, 0, 26),
(0, 'S64bfe52762fc61.69352279', 1, 27, 1, 0, 0, 1, '10.0000', '2023-07-25', '09:07:19', 0, 0, 1, 0, 27),
(0, 'S64bfe7e25d2763.43213120', 1, 28, 1, 0, 0, 1, '15.0000', '2023-07-25', '09:18:58', 0, 0, 1, 0, 28),
(0, 'S64bfe830dcf360.84705957', 1, 29, 1, 0, 0, 1, '20.0000', '2023-07-25', '09:20:16', 0, 0, 1, 0, 29),
(0, 'S64c009396c6131.33063130', 1, 30, 1, 0, 1, 0, '10.0000', '2023-07-25', '11:41:13', 0, 0, 1, 0, 30),
(0, 'S64c00991602062.02970046', 1, 31, 1, 0, 1, 0, '1.0000', '2023-07-25', '11:42:41', 0, 0, 1, 0, 31),
(0, 'S64c0282100c915.92567460', 1, 32, 1, 0, 1, 0, '1.0000', '2023-07-25', '13:53:04', 0, 0, 1, 0, 32),
(0, 'S64c02dc38ddf35.29181808', 1, 33, 1, 0, 1, 0, '1.0000', '2023-07-25', '14:17:07', 0, 0, 1, 0, 33),
(0, 'S64c02dd3366ab0.95879861', 1, 34, 1, 0, 1, 0, '1.0000', '2023-07-25', '14:17:23', 0, 0, 1, 0, 34);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mov_caja`
--

CREATE TABLE `mov_caja` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(1) DEFAULT NULL,
  `id_movimiento` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_empleado` int(1) DEFAULT NULL,
  `idtransace` int(11) NOT NULL,
  `alias_tipodoc` char(4) NOT NULL,
  `numero_doc` varchar(30) NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `concepto` varchar(50) DEFAULT NULL,
  `corte` int(1) DEFAULT NULL,
  `cobrado` tinyint(1) NOT NULL,
  `cliente` varchar(40) NOT NULL,
  `duui` varchar(10) NOT NULL,
  `entrada` tinyint(1) NOT NULL,
  `salida` tinyint(1) NOT NULL,
  `anulado` tinyint(1) NOT NULL,
  `turno` int(11) NOT NULL,
  `id_apertura` int(11) NOT NULL,
  `nombre_recibe` varchar(100) NOT NULL,
  `nombre_autoriza` varchar(100) NOT NULL,
  `nombre_proveedor` varchar(100) NOT NULL,
  `iva` float NOT NULL,
  `id_tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mov_cta_banco`
--

CREATE TABLE `mov_cta_banco` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_movimiento` int(11) NOT NULL,
  `id_cuenta` int(11) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `alias_tipodoc` varchar(15) NOT NULL,
  `numero_doc` varchar(50) NOT NULL,
  `entrada` float NOT NULL,
  `salida` float NOT NULL,
  `saldo` float NOT NULL,
  `fecha` date NOT NULL,
  `responsable` varchar(150) NOT NULL,
  `concepto` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipio`
--

CREATE TABLE `municipio` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_municipio` int(11) NOT NULL COMMENT 'ID del municipio',
  `nombre_municipio` varchar(60) NOT NULL COMMENT 'Nombre del municipio',
  `id_departamento_municipio` int(11) NOT NULL COMMENT 'Departamento al cual pertenece el municipio'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Municipios de El Salvador';

--
-- Volcado de datos para la tabla `municipio`
--

INSERT INTO `municipio` (`id_server`, `unique_id`, `id_municipio`, `nombre_municipio`, `id_departamento_municipio`) VALUES
(1, 'O5f05eb4264d952.15071005', 1, 'Ahuachapán', 1),
(2, 'O5f05eb4265ea72.47300429', 2, 'Jujutla', 1),
(3, 'O5f05eb4267f051.21192471', 3, 'Atiquizaya', 1),
(4, 'O5f05eb426a1481.75000987', 4, 'Concepción de Ataco', 1),
(5, 'O5f05eb426c2638.25299786', 5, 'El Refugio', 1),
(6, 'O5f05eb426e3358.06449427', 6, 'Guaymango', 1),
(7, 'O5f05eb42704a19.90851594', 7, 'Apaneca', 1),
(8, 'O5f05eb42725ce0.10749991', 8, 'San Francisco Menéndez', 1),
(9, 'O5f05eb427472f8.95859854', 9, 'San Lorenzo', 1),
(10, 'O5f05eb427682d2.47552413', 10, 'San Pedro Puxtla', 1),
(11, 'O5f05eb427890d9.61936292', 11, 'Tacuba', 1),
(12, 'O5f05eb427a9be4.60522321', 12, 'Turín', 1),
(13, 'O5f05eb427f1a54.79660319', 13, 'Candelaria de la Frontera', 2),
(14, 'O5f05eb42811745.68066749', 14, 'Chalchuapa', 2),
(15, 'O5f05eb42833143.17645082', 15, 'Coatepeque', 2),
(16, 'O5f05eb428537b6.74300373', 16, 'El Congo', 2),
(17, 'O5f05eb42874a19.86408779', 17, 'El Porvenir', 2),
(18, 'O5f05eb42895ef7.61512454', 18, 'Masahuat', 2),
(19, 'O5f05eb428b6a43.87092835', 19, 'Metapán', 2),
(20, 'O5f05eb42902040.21580209', 20, 'San Antonio Pajonal', 2),
(21, 'O5f05eb42924d29.87679285', 21, 'San Sebastián Salitrillo', 2),
(22, 'O5f05eb42943e20.02147314', 22, 'Santa Ana', 2),
(23, 'O5f05eb42964de8.91476992', 23, 'Santa Rosa Guachipilín', 2),
(24, 'O5f05eb42985802.56729999', 24, 'Santiago de la Frontera', 2),
(25, 'O5f05eb429a8963.83041979', 25, 'Texistepeque', 2),
(26, 'O5f05eb429c7183.58337306', 26, 'Acajutla', 3),
(27, 'O5f05eb429ea416.78572954', 27, 'Armenia', 3),
(28, 'O5f05eb42a0c047.70284121', 28, 'Caluco', 3),
(29, 'O5f05eb42a2d5e8.80867855', 29, 'Cuisnahuat', 3),
(30, 'O5f05eb42a4e2f8.06252531', 30, 'Izalco', 3),
(31, 'O5f05eb42a6f608.71345193', 31, 'Juayúa', 3),
(32, 'O5f05eb42a91472.33209333', 32, 'Nahuizalco', 3),
(33, 'O5f05eb42ad7680.71065217', 33, 'Nahulingo', 3),
(34, 'O5f05eb42af7eb6.33729739', 34, 'Salcoatitán', 3),
(35, 'O5f05eb42b19201.45637142', 35, 'San Antonio del Monte', 3),
(36, 'O5f05eb42b3ac84.84193324', 36, 'San Julián', 3),
(37, 'O5f05eb42b5b759.58332190', 37, 'Santa Catarina Masahuat', 3),
(38, 'O5f05eb42b7c234.95068298', 38, 'Santa Isabel Ishuatán', 3),
(39, 'O5f05eb42b9d785.57966286', 39, 'Santo Domingo de Guzmán', 3),
(40, 'O5f05eb42bbf058.86086664', 40, 'Sonsonate', 3),
(41, 'O5f05eb42bdfce9.65358959', 41, 'Sonzacate', 3),
(42, 'O5f05eb42c00d87.97895616', 42, 'Alegría', 11),
(43, 'O5f05eb42c21b35.23161731', 43, 'Berlín', 11),
(44, 'O5f05eb42c42c20.90100095', 44, 'California', 11),
(45, 'O5f05eb42c63a60.90007833', 45, 'Concepción Batres', 11),
(46, 'O5f05eb42cacb43.85341637', 46, 'El Triunfo', 11),
(47, 'O5f05eb42ccd506.70382492', 47, 'Ereguayquín', 11),
(48, 'O5f05eb42cee9f7.49901962', 48, 'Estanzuelas', 11),
(49, 'O5f05eb42d0f750.62511213', 49, 'Jiquilisco', 11),
(50, 'O5f05eb42d30901.12027912', 50, 'Jucuapa', 11),
(51, 'O5f05eb42d51472.35479417', 51, 'Jucuarán', 11),
(52, 'O5f05eb42d73848.57527076', 52, 'Mercedes Umaña', 11),
(53, 'O5f05eb42d935c1.39809065', 53, 'Nueva Granada', 11),
(54, 'O5f05eb42db4e08.22288885', 54, 'Ozatlán', 11),
(55, 'O5f05eb42dd57a6.67982781', 55, 'Puerto El Triunfo', 11),
(56, 'O5f05eb42df83d1.54884971', 56, 'San Agustín', 11),
(57, 'O5f05eb42e32ba2.87235273', 57, 'San Buenaventura', 11),
(58, 'O5f05eb42e55075.70708293', 58, 'San Dionisio', 11),
(59, 'O5f05eb42e75fe0.00677079', 59, 'San Francisco Javier', 11),
(60, 'O5f05eb42e97249.69563140', 60, 'Santa Elena', 11),
(61, 'O5f05eb42eb8732.03756217', 61, 'Santa María', 11),
(62, 'O5f05eb42ed8198.32347920', 62, 'Santiago de María', 11),
(63, 'O5f05eb42efaf67.09449228', 63, 'Tecapán', 11),
(64, 'O5f05eb42f1ce06.43393936', 64, 'Usulután', 11),
(65, 'O5f05eb42f3e233.37455682', 65, 'Carolina', 13),
(66, 'O5f05eb43029053.54346498', 66, 'Chapeltique', 13),
(67, 'O5f05eb4304aac5.67620258', 67, 'Chinameca', 13),
(68, 'O5f05eb4306c906.48720060', 68, 'Chirilagua', 13),
(69, 'O5f05eb4308c941.64825385', 69, 'Ciudad Barrios', 13),
(70, 'O5f05eb430ada27.10262517', 70, 'Comacarán', 13),
(71, 'O5f05eb430ce605.86122295', 71, 'El Tránsito', 13),
(72, 'O5f05eb430f0226.46271024', 72, 'Lolotique', 13),
(73, 'O5f05eb431116e9.12914046', 73, 'Moncagua', 13),
(74, 'O5f05eb431329c4.06632274', 74, 'Nueva Guadalupe', 13),
(75, 'O5f05eb43156539.66160814', 75, 'Nuevo Edén de San Juan', 13),
(76, 'O5f05eb43175cf7.31457112', 76, 'Quelepa', 13),
(77, 'O5f05eb43197706.76543826', 77, 'San Antonio del Mosco', 13),
(78, 'O5f05eb431b7cf0.62422369', 78, 'San Gerardo', 13),
(79, 'O5f05eb431fed59.30370748', 79, 'San Jorge', 13),
(80, 'O5f05eb4321eec1.45189537', 80, 'San Luis de la Reina', 13),
(81, 'O5f05eb43240712.48344769', 81, 'San Miguel', 13),
(82, 'O5f05eb432618d5.91097735', 82, 'San Rafael Oriente', 13),
(83, 'O5f05eb43284a81.42444058', 83, 'Sesori', 13),
(84, 'O5f05eb432a4a23.22092227', 84, 'Uluazapa', 13),
(85, 'O5f05eb432c5e86.89033440', 85, 'Arambala', 12),
(86, 'O5f05eb432e6814.55484062', 86, 'Cacaopera', 12),
(87, 'O5f05eb433085f4.08007347', 87, 'Chilanga', 12),
(88, 'O5f05eb43328a97.90608276', 88, 'Corinto', 12),
(89, 'O5f05eb43349d89.88774485', 89, 'Delicias de Concepción', 12),
(90, 'O5f05eb4336af43.87810935', 90, 'El Divisadero', 12),
(91, 'O5f05eb4338d691.77333479', 91, 'El Rosario', 12),
(92, 'O5f05eb433ad522.08954035', 92, 'Gualococti', 12),
(93, 'O5f05eb433fa904.49888055', 93, 'Guatajiagua', 12),
(94, 'O5f05eb4341ba60.95447922', 94, 'Joateca', 12),
(95, 'O5f05eb4343dc42.95830046', 95, 'Jocoaitique', 12),
(96, 'O5f05eb4345dc29.12465360', 96, 'Jocoro', 12),
(97, 'O5f05eb4347f0e2.83946693', 97, 'Lolotiquillo', 12),
(98, 'O5f05eb434a0b54.38512421', 98, 'Meanguera', 12),
(99, 'O5f05eb434c2919.95373960', 99, 'Osicala', 12),
(100, 'O5f05eb4350b078.43105775', 100, 'Perquín', 12),
(101, 'O5f05eb4352bc14.51191186', 101, 'San Carlos', 12),
(102, 'O5f05eb4354d534.05069864', 102, 'San Fernando', 12),
(103, 'O5f05eb43570036.04604032', 103, 'San Francisco Gotera', 12),
(104, 'O5f05eb4358fee8.43876916', 104, 'San Isidro', 12),
(105, 'O5f05eb435b0dd5.77072355', 105, 'San Simón', 12),
(106, 'O5f05eb435d26b5.21794520', 106, 'Sensembra', 12),
(107, 'O5f05eb435f4ba7.63030805', 107, 'Sociedad', 12),
(108, 'O5f05eb43615960.58493714', 108, 'Torola', 12),
(109, 'O5f05eb436382f8.01080123', 109, 'Yamabal', 12),
(110, 'O5f05eb4365a832.07581480', 110, 'Yoloaiquín', 12),
(111, 'O5f05eb4367b440.37051463', 111, 'La Unión', 14),
(112, 'O5f05eb4369b6d0.05875922', 112, 'San Alejo', 14),
(113, 'O5f05eb436e08f0.85916902', 113, 'Yucuaiquín', 14),
(114, 'O5f05eb43701c02.38900619', 114, 'Conchagua', 14),
(115, 'O5f05eb437240b6.72431998', 115, 'Intipucá', 14),
(116, 'O5f05eb43744c35.53626094', 116, 'San José', 14),
(117, 'O5f05eb43765e25.74171455', 117, 'El Carmen', 14),
(118, 'O5f05eb43787312.01082444', 118, 'Yayantique', 14),
(119, 'O5f05eb437a86e6.43588326', 119, 'Bolívar', 14),
(120, 'O5f05eb437c9405.45366969', 120, 'Meanguera del Golfo', 14),
(121, 'O5f05eb437ea4f3.40548376', 121, 'Santa Rosa de Lima', 14),
(122, 'O5f05eb4380c3a2.36689096', 122, 'Pasaquina', 14),
(123, 'O5f05eb4382f539.91746309', 123, 'ANAMOROS', 14),
(124, 'O5f05eb43865cb0.30855251', 124, 'Nueva Esparta', 14),
(125, 'O5f05eb43887e91.04337986', 125, 'El Sauce', 14),
(126, 'O5f05eb438a8e11.88984619', 126, 'Concepción de Oriente', 14),
(127, 'O5f05eb438cbad6.00073278', 127, 'Polorós', 14),
(128, 'O5f05eb438eca00.26586173', 128, 'Lislique ', 14),
(129, 'O5f05eb4390ce97.62965718', 129, 'Antiguo Cuscatlán', 4),
(130, 'O5f05eb4392e782.16690249', 130, 'Chiltiupán', 4),
(131, 'O5f05eb43950bd7.53431337', 131, 'Ciudad Arce', 4),
(132, 'O5f05eb43971173.96966638', 132, 'Colón', 4),
(133, 'O5f05eb43992a64.38671662', 133, 'Comasagua', 4),
(134, 'O5f05eb439b4d31.70667489', 134, 'Huizúcar', 4),
(135, 'O5f05eb439d85b0.82054150', 135, 'Jayaque', 4),
(136, 'O5f05eb439f8a79.71048352', 136, 'Jicalapa', 4),
(137, 'O5f05eb43a3b956.76906165', 137, 'La Libertad', 4),
(138, 'O5f05eb43a5c2a3.16151455', 138, 'Santa Tecla', 4),
(139, 'O5f05eb43a7e2f7.74842941', 139, 'Nuevo Cuscatlán', 4),
(140, 'O5f05eb43a9ecd9.11754201', 140, 'San Juan Opico', 4),
(141, 'O5f05eb43abf651.44883048', 141, 'Quezaltepeque', 4),
(142, 'O5f05eb43ae0ed3.24688945', 142, 'Sacacoyo', 4),
(143, 'O5f05eb43b01fa1.76900007', 143, 'San José Villanueva', 4),
(144, 'O5f05eb43b4e2e7.99527578', 144, 'San Matías', 4),
(145, 'O5f05eb43b6ed35.03472043', 145, 'San Pablo Tacachico', 4),
(146, 'O5f05eb43b904b7.54054197', 146, 'Talnique', 4),
(147, 'O5f05eb43bb26c2.81158148', 147, 'Tamanique', 4),
(148, 'O5f05eb43bd3935.13786729', 148, 'Teotepeque', 4),
(149, 'O5f05eb43bf51e0.18640656', 149, 'Tepecoyo', 4),
(150, 'O5f05eb43c15a06.79911254', 150, 'Zaragoza', 4),
(151, 'O5f05eb43c383d2.09812516', 151, 'Agua Caliente', 5),
(152, 'O5f05eb43c5a2a6.40045407', 152, 'Arcatao', 5),
(153, 'O5f05eb43c7b346.77973094', 153, 'Azacualpa', 5),
(154, 'O5f05eb43c9bd59.11262361', 154, 'Cancasque', 5),
(155, 'O5f05eb43cbdfd7.12347728', 155, 'Chalatenango', 5),
(156, 'O5f05eb43cded28.78886347', 156, 'Citalá', 5),
(157, 'O5f05eb43d00d78.86427667', 157, 'Comapala', 5),
(158, 'O5f05eb43d22cd4.70034693', 158, 'Concepción Quezaltepeque', 5),
(159, 'O5f05eb43d44100.51740729', 159, 'Dulce Nombre de María', 5),
(160, 'O5f05eb43d64fd3.34748606', 160, 'El Carrizal', 5),
(161, 'O5f05eb43d86e37.46591278', 161, 'El Paraíso', 5),
(162, 'O5f05eb43da9063.98127434', 162, 'La Laguna', 5),
(163, 'O5f05eb43dc9d20.74587296', 163, 'La Palma', 5),
(164, 'O5f05eb43deb548.24007242', 164, 'La Reina', 5),
(165, 'O5f05eb43e0cf72.72980681', 165, 'Las Vueltas', 5),
(166, 'O5f05eb43e2da30.93023545', 166, 'Nueva Concepción', 5),
(167, 'O5f05eb43e50482.14655158', 167, 'Nueva Trinidad', 5),
(168, 'O5f05eb43e70595.24075927', 168, 'Nombre de Jesús', 5),
(169, 'O5f05eb43e91b42.37983337', 169, 'Ojos de Agua', 5),
(170, 'O5f05eb43ecefa1.07515783', 170, 'Potonico', 5),
(171, 'O5f05eb43ef1979.46162784', 171, 'San Antonio de la Cruz', 5),
(172, 'O5f05eb43f12909.46587691', 172, 'San Antonio Los Ranchos', 5),
(173, 'O5f05eb43f33dd2.59992011', 173, 'San Fernando', 5),
(174, 'O5f05eb440132c2.56115941', 174, 'San Francisco Lempa', 5),
(175, 'O5f05eb44034c57.66724213', 175, 'San Francisco Morazán', 5),
(176, 'O5f05eb44056369.02204107', 176, 'San Ignacio', 5),
(177, 'O5f05eb44077196.59106514', 177, 'San Isidro Labrador', 5),
(178, 'O5f05eb44097dd5.53296263', 178, 'Las Flores', 5),
(179, 'O5f05eb440ba167.61483562', 179, 'San Luis del Carmen', 5),
(180, 'O5f05eb440dab97.74794336', 180, 'San Miguel de Mercedes', 5),
(181, 'O5f05eb440fc222.54723805', 181, 'San Rafael', 5),
(182, 'O5f05eb4411d1e1.80466872', 182, 'Santa Rita', 5),
(183, 'O5f05eb44162de1.72653055', 183, 'Tejutla', 5),
(184, 'O5f05eb44183749.29254038', 184, 'Cojutepeque', 7),
(185, 'O5f05eb441a4c05.27365158', 185, 'Candelaria', 7),
(186, 'O5f05eb441c65a6.91473808', 186, 'El Carmen', 7),
(187, 'O5f05eb441e7fd9.97684321', 187, 'El Rosario', 7),
(188, 'O5f05eb44208b93.95752804', 188, 'Monte San Juan', 7),
(189, 'O5f05eb4422a624.91391840', 189, 'Oratorio de Concepción', 7),
(190, 'O5f05eb4424d702.42295795', 190, 'San Bartolomé Perulapía', 7),
(191, 'O5f05eb4426fe35.58142984', 191, 'San Cristóbal', 7),
(192, 'O5f05eb4428ffb1.36565676', 192, 'San José Guayabal', 7),
(193, 'O5f05eb442b1747.64101753', 193, 'San Pedro Perulapán', 7),
(194, 'O5f05eb442d2432.26517052', 194, 'San Rafael Cedros', 7),
(195, 'O5f05eb442f4745.39970580', 195, 'San Ramón', 7),
(196, 'O5f05eb44316be5.55093327', 196, 'Santa Cruz Analquito', 7),
(197, 'O5f05eb44337a56.14023542', 197, 'Santa Cruz Michapa', 7),
(198, 'O5f05eb443594e7.84750089', 198, 'Suchitoto', 7),
(199, 'O5f05eb443864d8.64866099', 199, 'Tenancingo', 7),
(200, 'O5f05eb443a7d65.92947112', 200, 'Aguilares', 6),
(201, 'O5f05eb443c8475.65666050', 201, 'Apopa', 6),
(202, 'O5f05eb443e9968.39304034', 202, 'Ayutuxtepeque', 6),
(203, 'O5f05eb4440c002.73328974', 203, 'Cuscatancingo', 6),
(204, 'O5f05eb4442cdb6.55099914', 204, 'Ciudad Delgado', 6),
(205, 'O5f05eb4444e499.31684350', 205, 'El Paisnal', 6),
(206, 'O5f05eb4446eb88.77142559', 206, 'Guazapa', 6),
(207, 'O5f05eb44491940.91071840', 207, 'Ilopango', 6),
(208, 'O5f05eb444b2a47.22613167', 208, 'Mejicanos', 6),
(209, 'O5f05eb444d3fc1.96627178', 209, 'Nejapa', 6),
(210, 'O5f05eb444f40e0.20552756', 210, 'Panchimalco', 6),
(211, 'O5f05eb44516469.42697774', 211, 'Rosario de Mora', 6),
(212, 'O5f05eb4455a5d1.21969657', 212, 'San Marcos', 6),
(213, 'O5f05eb4457a8c4.23190478', 213, 'San Martín', 6),
(214, 'O5f05eb4459d143.43937866', 214, 'San Salvador', 6),
(215, 'O5f05eb445bd872.80905098', 215, 'Santiago Texacuangos', 6),
(216, 'O5f05eb445ddb52.33034181', 216, 'Santo Tomás', 6),
(217, 'O5f05eb445ffeb7.88786232', 217, 'Soyapango', 6),
(218, 'O5f05eb44621477.16404241', 218, 'Tonacatepeque', 6),
(219, 'O5f05eb44642a89.53125309', 219, 'Zacatecoluca', 8),
(220, 'O5f05eb44663b92.84389355', 220, 'Cuyultitán', 8),
(221, 'O5f05eb44684716.06548139', 221, 'El Rosario', 8),
(222, 'O5f05eb446a6885.53363669', 222, 'Jerusalén', 8),
(223, 'O5f05eb446c9869.72549157', 223, 'Mercedes La Ceiba', 8),
(224, 'O5f05eb446e9740.34568326', 224, 'Olocuilta', 8),
(225, 'O5f05eb4472f793.55978479', 225, 'Paraíso de Osorio', 8),
(226, 'O5f05eb44750482.08296257', 226, 'San Antonio Masahuat', 8),
(227, 'O5f05eb447728a5.60939595', 227, 'San Emigdio', 8),
(228, 'O5f05eb44793575.82334506', 228, 'San Francisco Chinameca', 8),
(229, 'O5f05eb447b5428.18831978', 229, 'San Pedro Masahuat', 8),
(230, 'O5f05eb447d7057.24976777', 230, 'San Juan Nonualco', 8),
(231, 'O5f05eb447f8a89.30305300', 231, 'San Juan Talpa', 8),
(232, 'O5f05eb44840236.55670989', 232, 'San Juan Tepezontes', 8),
(233, 'O5f05eb448614c1.93648435', 233, 'San Luis La Herradura', 8),
(234, 'O5f05eb44882a57.26774057', 234, 'San Luis Talpa', 8),
(235, 'O5f05eb448a64a0.72398721', 235, 'San Miguel Tepezontes', 8),
(236, 'O5f05eb448c5949.51698908', 236, 'San Pedro Nonualco', 8),
(237, 'O5f05eb448e6c37.32770062', 237, 'San Rafael Obrajuelo', 8),
(238, 'O5f05eb44907f69.35989729', 238, 'Santa María Ostuma', 8),
(239, 'O5f05eb4492ab67.21133439', 239, 'Santiago Nonualco', 8),
(240, 'O5f05eb44949dc5.76415345', 240, 'Tapalhuaca', 8),
(241, 'O5f05eb4498a704.23388914', 241, 'Cinquera', 9),
(242, 'O5f05eb449952b7.57546222', 242, 'Dolores', 9),
(243, 'O5f05eb449b6136.71549893', 243, 'Guacotecti', 9),
(244, 'O5f05eb449d9e36.72393453', 244, 'Ilobasco', 9),
(245, 'O5f05eb449fe279.44820497', 245, 'Jutiapa', 9),
(246, 'O5f05eb44a1f0e0.22683119', 246, 'San Isidro', 9),
(247, 'O5f05eb44a3ffd4.49844589', 247, 'Sensuntepeque', 9),
(248, 'O5f05eb44a62268.92413116', 248, 'Tejutepeque', 9),
(249, 'O5f05eb44a82547.93617534', 249, 'Victoria', 9),
(250, 'O5f05eb44aa4284.11847000', 250, 'Apastepeque', 10),
(251, 'O5f05eb44ac5323.67747106', 251, 'Guadalupe', 10),
(252, 'O5f05eb44ae81f4.44127984', 252, 'San Cayetano Istepeque', 10),
(253, 'O5f05eb44b08eb3.47856753', 253, 'San Esteban Catarina', 10),
(254, 'O5f05eb44b29a65.99025393', 254, 'San Ildefonso', 10),
(255, 'O5f05eb44b4b4f8.20837842', 255, 'San Lorenzo', 10),
(256, 'O5f05eb44b6d0b9.09618531', 256, 'San Sebastián', 10),
(257, 'O5f05eb44b8d1e7.79856619', 257, 'San Vicente', 10),
(258, 'O5f05eb44baff40.20065560', 258, 'Santa Clara', 10),
(259, 'O5f05eb44bd11d9.51326535', 259, 'Santo Domingo', 10),
(260, 'O5f05eb44bf36b8.02873900', 260, 'Tecoluca', 10),
(261, 'O5f05eb44c13e63.25290676', 261, 'Tepetitán', 10),
(262, 'O5f05eb44c34e58.45874013', 262, 'Verapaz', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipioMH`
--

CREATE TABLE `municipioMH` (
  `id` smallint(3) NOT NULL,
  `codigo` smallint(3) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `id_departamento` smallint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `municipioMH`
--

INSERT INTO `municipioMH` (`id`, `codigo`, `nombre`, `id_departamento`) VALUES
(1, 0, 'Otro País', 0),
(2, 1, 'AHUACHAPÁN', 1),
(3, 2, 'APANECA', 1),
(4, 3, 'ATIQUIZAYA', 1),
(5, 4, 'CONCEPCIÓN DE ATACO', 1),
(6, 5, 'EL REFUGIO', 1),
(7, 6, 'GUAYMANGO', 1),
(8, 7, 'JUJUTLA', 1),
(9, 8, 'SAN FRANCISCO MENÉNDEZ', 1),
(10, 9, 'SAN LORENZO', 1),
(11, 10, 'SAN PEDRO PUXTLA', 1),
(12, 11, 'TACUBA', 1),
(13, 12, 'TURÍN', 1),
(14, 1, 'CANDELARIA DE LA FRONTERA', 2),
(15, 2, 'COATEPEQUE', 2),
(16, 3, 'CHALCHUAPA', 2),
(17, 4, 'EL CONGO', 2),
(18, 5, 'EL PORVENIR', 2),
(19, 6, 'MASAHUAT', 2),
(20, 7, 'METAPÁN', 2),
(21, 8, 'SAN ANTONIO PAJONAL', 2),
(22, 9, 'SAN SEBASTIÁN SALITRILLO', 2),
(23, 10, 'SANTA ANA', 2),
(24, 11, 'STA ROSA GUACHI', 2),
(25, 12, 'STGO D LA FRONT', 2),
(26, 13, 'TEXISTEPEQUE', 2),
(27, 1, 'ACAJUTLA', 3),
(28, 2, 'ARMENIA', 3),
(29, 3, 'CALUCO', 3),
(30, 4, 'CUISNAHUAT', 3),
(31, 5, 'STA I ISHUATAN', 3),
(32, 6, 'IZALCO', 3),
(33, 7, 'JUAYÚA', 3),
(34, 8, 'NAHUIZALCO', 3),
(35, 9, 'NAHULINGO', 3),
(36, 10, 'SALCOATITÁN', 3),
(37, 11, 'SAN ANTONIO DEL MONTE', 3),
(38, 12, 'SAN JULIÁN', 3),
(39, 13, 'STA C MASAHUAT', 3),
(40, 14, 'SANTO DOMINGO GUZMÁN', 3),
(41, 15, 'SONSONATE', 3),
(42, 16, 'SONZACATE', 3),
(43, 1, 'AGUA CALIENTE', 4),
(44, 2, 'ARCATAO', 4),
(45, 3, 'AZACUALPA', 4),
(46, 4, 'CITALÁ', 4),
(47, 5, 'COMALAPA', 4),
(48, 6, 'CONCEPCIÓN QUEZALTEPEQUE', 4),
(49, 7, 'CHALATENANGO', 4),
(50, 8, 'DULCE NOM MARÍA', 4),
(51, 9, 'EL CARRIZAL', 4),
(52, 10, 'EL PARAÍSO', 4),
(53, 11, 'LA LAGUNA', 4),
(54, 12, 'LA PALMA', 4),
(55, 13, 'LA REINA', 4),
(56, 14, 'LAS VUELTAS', 4),
(57, 15, 'NOMBRE DE JESUS', 4),
(58, 16, 'NVA CONCEPCIÓN', 4),
(59, 17, 'NUEVA TRINIDAD', 4),
(60, 18, 'OJOS DE AGUA', 4),
(61, 19, 'POTONICO', 4),
(62, 20, 'SAN ANT LA CRUZ', 4),
(63, 21, 'SAN ANT RANCHOS', 4),
(64, 22, 'SAN FERNANDO', 4),
(65, 23, 'SAN FRANCISCO LEMPA', 4),
(66, 24, 'SAN FRANCISCO MORAZÁN', 4),
(67, 25, 'SAN IGNACIO', 4),
(68, 26, 'SAN I LABRADOR', 4),
(69, 27, 'SAN J CANCASQUE', 4),
(70, 28, 'SAN JOSE FLORES', 4),
(71, 29, 'SAN LUIS CARMEN', 4),
(72, 30, 'SN MIG MERCEDES', 4),
(73, 31, 'SAN RAFAEL', 4),
(74, 32, 'SANTA RITA', 4),
(75, 33, 'TEJUTLA', 4),
(76, 1, 'ANTGO CUSCATLÁN', 5),
(77, 2, 'CIUDAD ARCE', 5),
(78, 3, 'COLON', 5),
(79, 4, 'COMASAGUA', 5),
(80, 5, 'CHILTIUPAN', 5),
(81, 6, 'HUIZÚCAR', 5),
(82, 7, 'JAYAQUE', 5),
(83, 8, 'JICALAPA', 5),
(84, 9, 'LA LIBERTAD', 5),
(85, 10, 'NUEVO CUSCATLÁN', 5),
(86, 11, 'SANTA TECLA', 5),
(87, 12, 'QUEZALTEPEQUE', 5),
(88, 13, 'SACACOYO', 5),
(89, 14, 'SN J VILLANUEVA', 5),
(90, 15, 'SAN JUAN OPICO', 5),
(91, 16, 'SAN MATÍAS', 5),
(92, 17, 'SAN P TACACHICO', 5),
(93, 18, 'TAMANIQUE', 5),
(94, 19, 'TALNIQUE', 5),
(95, 20, 'TEOTEPEQUE', 5),
(96, 21, 'TEPECOYO', 5),
(97, 22, 'ZARAGOZA', 5),
(98, 1, 'AGUILARES', 6),
(99, 2, 'APOPA', 6),
(100, 3, 'AYUTUXTEPEQUE', 6),
(101, 4, 'CUSCATANCINGO', 6),
(102, 5, 'EL PAISNAL', 6),
(103, 6, 'GUAZAPA', 6),
(104, 7, 'ILOPANGO', 6),
(105, 8, 'MEJICANOS', 6),
(106, 9, 'NEJAPA', 6),
(107, 10, 'PANCHIMALCO', 6),
(108, 11, 'ROSARIO DE MORA', 6),
(109, 12, 'SAN MARCOS', 6),
(110, 13, 'SAN MARTIN', 6),
(111, 14, 'SAN SALVADOR', 6),
(112, 15, 'STG TEXACUANGOS', 6),
(113, 16, 'SANTO TOMAS', 6),
(114, 17, 'SOYAPANGO', 6),
(115, 18, 'TONACATEPEQUE', 6),
(116, 19, 'CIUDAD DELGADO', 6),
(117, 1, 'CANDELARIA', 7),
(118, 2, 'COJUTEPEQUE', 7),
(119, 3, 'EL CARMEN', 7),
(120, 4, 'EL ROSARIO', 7),
(121, 5, 'MONTE SAN JUAN', 7),
(122, 6, 'ORAT CONCEPCIÓN', 7),
(123, 7, 'SAN B PERULAPIA', 7),
(124, 8, 'SAN CRISTÓBAL', 7),
(125, 9, 'SAN J GUAYABAL', 7),
(126, 10, 'SAN P PERULAPÁN', 7),
(127, 11, 'SAN RAF CEDROS', 7),
(128, 12, 'SAN RAMON', 7),
(129, 13, 'STA C ANALQUITO', 7),
(130, 14, 'STA C MICHAPA', 7),
(131, 15, 'SUCHITOTO', 7),
(132, 16, 'TENANCINGO', 7),
(133, 1, 'CUYULTITÁN', 8),
(134, 2, 'EL ROSARIO', 8),
(135, 3, 'JERUSALÉN', 8),
(136, 4, 'MERCED LA CEIBA', 8),
(137, 5, 'OLOCUILTA', 8),
(138, 6, 'PARAÍSO OSORIO', 8),
(139, 7, 'SN ANT MASAHUAT', 8),
(140, 8, 'SAN EMIGDIO', 8),
(141, 9, 'SN FCO CHINAMEC', 8),
(142, 10, 'SAN J NONUALCO', 8),
(143, 11, 'SAN JUAN TALPA', 8),
(144, 12, 'SAN JUAN TEPEZONTES', 8),
(145, 13, 'SAN LUIS TALPA', 8),
(146, 14, 'SAN MIGUEL TEPEZONTES', 8),
(147, 15, 'SAN PEDRO MASAHUAT', 8),
(148, 16, 'SAN PEDRO NONUALCO', 8),
(149, 17, 'SAN R OBRAJUELO', 8),
(150, 18, 'STA MA OSTUMA', 8),
(151, 19, 'STGO NONUALCO', 8),
(152, 20, 'TAPALHUACA', 8),
(153, 21, 'ZACATECOLUCA', 8),
(154, 22, 'SN LUIS LA HERR', 8),
(155, 1, 'CINQUERA', 9),
(156, 2, 'GUACOTECTI', 9),
(157, 3, 'ILOBASCO', 9),
(158, 4, 'JUTIAPA', 9),
(159, 5, 'SAN ISIDRO', 9),
(160, 6, 'SENSUNTEPEQUE', 9),
(161, 7, 'TEJUTEPEQUE', 9),
(162, 8, 'VICTORIA', 9),
(163, 9, 'DOLORES', 9),
(164, 1, 'APASTEPEQUE', 10),
(165, 2, 'GUADALUPE', 10),
(166, 3, 'SAN CAY ISTEPEQ', 10),
(167, 4, 'SANTA CLARA', 10),
(168, 5, 'SANTO DOMINGO', 10),
(169, 6, 'SN EST CATARINA', 10),
(170, 7, 'SAN ILDEFONSO', 10),
(171, 8, 'SAN LORENZO', 10),
(172, 9, 'SAN SEBASTIÁN', 10),
(173, 10, 'SAN VICENTE', 10),
(174, 11, 'TECOLUCA', 10),
(175, 12, 'TEPETITÁN', 10),
(176, 13, 'VERAPAZ', 10),
(177, 1, 'ALEGRÍA', 11),
(178, 2, 'BERLÍN', 11),
(179, 3, 'CALIFORNIA', 11),
(180, 4, 'CONCEP BATRES', 11),
(181, 5, 'EL TRIUNFO', 11),
(182, 6, 'EREGUAYQUÍN', 11),
(183, 7, 'ESTANZUELAS', 11),
(184, 8, 'JIQUILISCO', 11),
(185, 9, 'JUCUAPA', 11),
(186, 10, 'JUCUARÁN', 11),
(187, 11, 'MERCEDES UMAÑA', 11),
(188, 12, 'NUEVA GRANADA', 11),
(189, 13, 'OZATLÁN', 11),
(190, 14, 'PTO EL TRIUNFO', 11),
(191, 15, 'SAN AGUSTÍN', 11),
(192, 16, 'SN BUENAVENTURA', 11),
(193, 17, 'SAN DIONISIO', 11),
(194, 18, 'SANTA ELENA', 11),
(195, 19, 'SAN FCO JAVIER', 11),
(196, 20, 'SANTA MARÍA', 11),
(197, 21, 'STGO DE MARÍA', 11),
(198, 22, 'TECAPÁN', 11),
(199, 23, 'USULUTÁN', 11),
(200, 1, 'CAROLINA', 12),
(201, 2, 'CIUDAD BARRIOS', 12),
(202, 3, 'COMACARÁN', 12),
(203, 4, 'CHAPELTIQUE', 12),
(204, 5, 'CHINAMECA', 12),
(205, 6, 'CHIRILAGUA', 12),
(206, 7, 'EL TRANSITO', 12),
(207, 8, 'LOLOTIQUE', 12),
(208, 9, 'MONCAGUA', 12),
(209, 10, 'NUEVA GUADALUPE', 12),
(210, 11, 'NVO EDÉN S JUAN', 12),
(211, 12, 'QUELEPA', 12),
(212, 13, 'SAN ANT D MOSCO', 12),
(213, 14, 'SAN GERARDO', 12),
(214, 15, 'SAN JORGE', 12),
(215, 16, 'SAN LUIS REINA', 12),
(216, 17, 'SAN MIGUEL', 12),
(217, 18, 'SAN RAF ORIENTE', 12),
(218, 19, 'SESORI', 12),
(219, 20, 'ULUAZAPA', 12),
(220, 1, 'ARAMBALA', 13),
(221, 2, 'CACAOPERA', 13),
(222, 3, 'CORINTO', 13),
(223, 4, 'CHILANGA', 13),
(224, 5, 'DELIC DE CONCEP', 13),
(225, 6, 'EL DIVISADERO', 13),
(226, 7, 'EL ROSARIO', 13),
(227, 8, 'GUALOCOCTI', 13),
(228, 9, 'GUATAJIAGUA', 13),
(229, 10, 'JOATECA', 13),
(230, 11, 'JOCOAITIQUE', 13),
(231, 12, 'JOCORO', 13),
(232, 13, 'LOLOTIQUILLO', 13),
(233, 14, 'MEANGUERA', 13),
(234, 15, 'OSICALA', 13),
(235, 16, 'PERQUÍN', 13),
(236, 17, 'SAN CARLOS', 13),
(237, 18, '|SAN FERNANDO', 13),
(238, 19, 'SAN FCO GOTERA', 13),
(239, 20, 'SAN ISIDRO', 13),
(240, 21, 'SAN SIMÓN', 13),
(241, 22, 'SENSEMBRA', 13),
(242, 23, 'SOCIEDAD', 13),
(243, 24, 'TOROLA', 13),
(244, 25, 'YAMABAL', 13),
(245, 26, 'YOLOAIQUÍN', 13),
(246, 1, 'ANAMOROS', 14),
(247, 2, 'BOLÍVAR', 14),
(248, 3, 'CONCEP DE OTE', 14),
(249, 4, 'CONCHAGUA', 14),
(250, 5, 'EL CARMEN', 14),
(251, 6, 'EL SAUCE', 14),
(252, 7, 'INTIPUCÁ', 14),
(253, 8, 'LA UNIÓN', 14),
(254, 9, 'LISLIQUE', 14),
(255, 10, 'MEANG DEL GOLFO', 14),
(256, 11, 'NUEVA ESPARTA', 14),
(257, 12, 'PASAQUINA', 14),
(258, 13, 'POLORÓS', 14),
(259, 14, 'SAN ALEJO', 14),
(260, 15, 'SAN JOSE', 14),
(261, 16, 'SANTA ROSA LIMA', 14),
(262, 17, 'YAYANTIQUE', 14),
(263, 18, 'YUCUAIQUÍN', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nc_corte`
--

CREATE TABLE `nc_corte` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_nc` int(11) NOT NULL,
  `id_corte` int(11) NOT NULL,
  `n_nc` int(11) NOT NULL,
  `t_nc` double NOT NULL,
  `afecta` varchar(30) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `exento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paises`
--

CREATE TABLE `paises` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id` int(11) NOT NULL,
  `iso` char(2) DEFAULT NULL,
  `nombre` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros_cuota`
--

CREATE TABLE `parametros_cuota` (
  `id` int(11) NOT NULL COMMENT 'p',
  `porc_min_prima` decimal(5,2) NOT NULL COMMENT 'porcentaje minimo  prima',
  `porc_min_int_mes` decimal(5,2) NOT NULL COMMENT 'porcentaje minimo interes mensual',
  `fecha_inicio` date NOT NULL COMMENT 'fecha menor a la actual para que se encuentren activos los parametros',
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `numero_doc` varchar(30) NOT NULL,
  `total` float NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `anulada` tinyint(1) NOT NULL DEFAULT 0,
  `id_sucursal` int(11) NOT NULL,
  `finalizada` tinyint(1) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha_factura` date NOT NULL,
  `fecha_entrega` date NOT NULL,
  `lugar_entrega` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `transporte` varchar(20) NOT NULL,
  `id_departamento` int(11) NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `observaciones` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `hash` varchar(60) NOT NULL,
  `hora_pedido` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `numero_doc` varchar(30) NOT NULL,
  `referencia` varchar(15) NOT NULL,
  `numero_ref` int(11) NOT NULL,
  `subtotal` float NOT NULL,
  `sumas` float NOT NULL,
  `suma_gravado` float NOT NULL,
  `iva` float NOT NULL,
  `retencion` decimal(8,2) NOT NULL,
  `percepcion` decimal(8,2) NOT NULL,
  `venta_exenta` float NOT NULL,
  `total_menos_retencion` float NOT NULL,
  `total` float NOT NULL,
  `descuento` float NOT NULL,
  `porcentaje` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `anulada` tinyint(1) NOT NULL DEFAULT 0,
  `id_empleado` int(11) NOT NULL,
  `finalizada` tinyint(1) NOT NULL,
  `impresa` tinyint(1) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `serie` varchar(25) NOT NULL,
  `serie_e` varchar(25) NOT NULL,
  `num_fact_impresa` varchar(30) NOT NULL,
  `hora` time NOT NULL,
  `turno` int(11) NOT NULL,
  `id_apertura` int(11) NOT NULL,
  `id_apertura_pagada` int(11) NOT NULL,
  `credito` tinyint(1) NOT NULL,
  `abono` decimal(8,2) NOT NULL,
  `saldo` decimal(8,2) NOT NULL,
  `afecta` int(11) NOT NULL,
  `tipo_documento` varchar(20) NOT NULL,
  `caja` int(11) NOT NULL,
  `numero_doc_e` varchar(30) NOT NULL,
  `num_fact_impresa_e` varchar(30) NOT NULL,
  `nombre` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `direccion` text NOT NULL,
  `precio_aut` int(11) NOT NULL,
  `clave` varchar(6) NOT NULL,
  `subt_bonifica` decimal(11,4) NOT NULL,
  `id_dev` int(11) NOT NULL DEFAULT 0,
  `pagar` decimal(11,4) NOT NULL,
  `extra_nombre` varchar(50) NOT NULL,
  `tot_cotrans` decimal(10,4) NOT NULL,
  `tot_fovial` decimal(10,4) NOT NULL,
  `tipo_pago` varchar(20) NOT NULL,
  `total_efectivo` decimal(10,4) NOT NULL COMMENT 'valor pagado en efectivo',
  `total_credito` decimal(10,4) NOT NULL COMMENT 'valor del credito',
  `total_tarjeta` decimal(10,4) NOT NULL COMMENT 'valor pagado con tarjeta',
  `num_transac` varchar(25) NOT NULL,
  `datos_extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `costo` tinyint(1) NOT NULL,
  `id_resolucion` int(11) NOT NULL COMMENT 'resolucion de documento vigente 	',
  `ventacuotas` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_detalle`
--

CREATE TABLE `pedidos_detalle` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_factura_detalle` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `id_prod_serv` int(11) NOT NULL,
  `id_server_prod` int(11) NOT NULL,
  `cantidad` decimal(11,4) NOT NULL,
  `precio_venta` decimal(11,4) NOT NULL,
  `subtotal` decimal(11,2) NOT NULL,
  `descuento` float NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `tipo_prod_serv` varchar(30) NOT NULL COMMENT 'PRODUCTO o SERVICIO',
  `id_factura_dia` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `impresa_lote` tinyint(1) NOT NULL,
  `hora` time NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `id_server_presen` int(11) NOT NULL,
  `exento` int(11) NOT NULL,
  `bonificacion` decimal(11,4) NOT NULL,
  `subt_bonifica` decimal(11,4) NOT NULL,
  `combustible` tinyint(1) NOT NULL,
  `impuesto` decimal(10,4) NOT NULL,
  `total` decimal(12,4) NOT NULL,
  `subtotal_iva` decimal(10,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_pago`
--

CREATE TABLE `pedidos_pago` (
  `id` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `alias_tipopago` varchar(4) NOT NULL,
  `subtotal` decimal(12,4) NOT NULL,
  `total_facturado` decimal(12,4) NOT NULL,
  `datos_extra` varchar(250) NOT NULL COMMENT 'tarjeta: numero transaccion;\r\ncheque: numero y banco; credito: dias plazo; transferencia o remesa: numero y efectivo: valor entregado y cambio'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='pPara hacer pagos de cualquier forma incluso pagos combinado';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalle`
--

CREATE TABLE `pedido_detalle` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_pedido_detalle` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_prod_serv` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` float NOT NULL,
  `subtotal` float NOT NULL,
  `combo` tinyint(1) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `tipo_prod_serv` varchar(30) NOT NULL COMMENT 'PRODUCTO o SERVICIO',
  `id_sucursal` int(11) NOT NULL,
  `cant_facturado` float NOT NULL,
  `unidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_prov`
--

CREATE TABLE `pedido_prov` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_pedido_prov` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `numero` varchar(30) NOT NULL,
  `total` float NOT NULL,
  `id_empleado_proceso` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `fecha_factura` date NOT NULL,
  `numero_factura` varchar(50) NOT NULL,
  `id_empleado_factura` int(11) NOT NULL,
  `fecha_entrega` date NOT NULL,
  `lugar_entrega` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reservado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_prov_detalle`
--

CREATE TABLE `pedido_prov_detalle` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_pedido_detalle` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(11,4) NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `cantidad_enviar` decimal(11,4) NOT NULL,
  `precio_venta` decimal(11,4) NOT NULL,
  `subtotal` decimal(11,2) NOT NULL,
  `tipo_prod_serv` varchar(30) NOT NULL COMMENT 'PRODUCTO o SERVICIO',
  `id_sucursal` int(11) NOT NULL,
  `cant_facturado` decimal(11,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `posicion`
--

CREATE TABLE `posicion` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_posicion` int(11) NOT NULL,
  `id_ubicacion` int(11) NOT NULL,
  `id_estante` int(11) NOT NULL,
  `posicion` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precio_aut`
--

CREATE TABLE `precio_aut` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id` int(11) NOT NULL,
  `clave` varchar(6) NOT NULL,
  `aplicado` tinyint(4) NOT NULL,
  `id_sucursal` tinyint(4) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_generado` date NOT NULL,
  `fecha_aplicado` date NOT NULL,
  `precio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presentacion`
--

CREATE TABLE `presentacion` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(12) NOT NULL,
  `cod_umedidaMH` varchar(3) NOT NULL DEFAULT '59'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `presentacion`
--

INSERT INTO `presentacion` (`id_server`, `unique_id`, `id_sucursal`, `id_presentacion`, `nombre`, `descripcion`, `cod_umedidaMH`) VALUES
(0, 'S64a84d6a757e80.05748874', 0, 1, 'UNIDAD', 'UNIDAD', '59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presentacion_producto`
--

CREATE TABLE `presentacion_producto` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_pp` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_server_prod` int(11) NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `descripcion` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `unidad` float NOT NULL,
  `costo` decimal(10,4) NOT NULL,
  `costo_s_iva` int(11) NOT NULL,
  `precio` decimal(12,4) NOT NULL,
  `precio1` decimal(12,4) NOT NULL,
  `precio2` decimal(12,4) NOT NULL,
  `precio3` decimal(12,4) NOT NULL,
  `precio4` decimal(12,4) NOT NULL,
  `precio5` decimal(12,4) NOT NULL,
  `precio6` decimal(12,4) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `barcode` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `presentacion_producto`
--

INSERT INTO `presentacion_producto` (`id_server`, `unique_id`, `id_pp`, `id_producto`, `id_server_prod`, `id_presentacion`, `descripcion`, `unidad`, `costo`, `costo_s_iva`, `precio`, `precio1`, `precio2`, `precio3`, `precio4`, `precio5`, `precio6`, `activo`, `barcode`) VALUES
(0, 'S64a84e592deb90.45362860', 1, 1, 0, 1, '1X1', 1, '1.0000', 0, '2.0000', '1.7500', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_producto` int(11) NOT NULL,
  `barcode` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `codart` varchar(15) NOT NULL,
  `descripcion` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `codigo` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `marca` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `perecedero` tinyint(1) NOT NULL,
  `exento` tinyint(1) NOT NULL,
  `minimo` int(11) NOT NULL,
  `decimals` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `imagen` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `costo_s_iva` float NOT NULL,
  `costo` float NOT NULL,
  `precio` float NOT NULL,
  `color` text NOT NULL,
  `id_laboratorio` int(11) NOT NULL,
  `composicion` varchar(5) NOT NULL,
  `id_marca` int(11) NOT NULL,
  `id_modelo` int(11) NOT NULL,
  `exclusivo_pedido` smallint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_server`, `unique_id`, `id_producto`, `barcode`, `codart`, `descripcion`, `codigo`, `marca`, `estado`, `perecedero`, `exento`, `minimo`, `decimals`, `id_categoria`, `id_proveedor`, `imagen`, `id_sucursal`, `costo_s_iva`, `costo`, `precio`, `color`, `id_laboratorio`, `composicion`, `id_marca`, `id_modelo`, `exclusivo_pedido`) VALUES
(0, 'S64a84e592cbb00.25816499', 1, '', '1', 'HELADOS #1', '', '1', 1, 0, 0, 0, 0, 1, 1, 'img/productos/64a9e6e3da779_Helados-artesanales.jpg', 0, 0, 0, 0, '', 0, '', 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `categoria` int(1) DEFAULT NULL,
  `tipo` int(1) DEFAULT NULL,
  `codigoant` int(3) DEFAULT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `municipio` varchar(20) DEFAULT NULL,
  `depto` varchar(12) DEFAULT NULL,
  `pais` varchar(10) DEFAULT NULL,
  `contacto` varchar(40) DEFAULT NULL,
  `nrc` varchar(8) DEFAULT NULL,
  `nit` varchar(17) DEFAULT NULL,
  `dui` varchar(12) DEFAULT NULL,
  `giro` varchar(40) DEFAULT NULL,
  `telefono1` varchar(15) DEFAULT NULL,
  `telefono2` varchar(15) DEFAULT NULL,
  `celular` varchar(15) NOT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ultcompra` date DEFAULT NULL,
  `acumulado` int(1) DEFAULT NULL,
  `saldo` int(1) DEFAULT NULL,
  `percibe` int(1) DEFAULT NULL,
  `retiene` int(1) DEFAULT NULL,
  `retiene10` int(1) DEFAULT NULL,
  `a30` int(1) DEFAULT NULL,
  `a60` int(1) DEFAULT NULL,
  `a90` int(1) DEFAULT NULL,
  `m90` int(1) DEFAULT NULL,
  `vencido` int(1) DEFAULT NULL,
  `pagadas` int(1) DEFAULT NULL,
  `pendientes` decimal(7,2) DEFAULT NULL,
  `total1` decimal(7,2) DEFAULT NULL,
  `nombreche` varchar(45) DEFAULT NULL,
  `viñeta` int(1) DEFAULT NULL,
  `nacionalidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_server`, `unique_id`, `id_sucursal`, `id_proveedor`, `categoria`, `tipo`, `codigoant`, `nombre`, `direccion`, `municipio`, `depto`, `pais`, `contacto`, `nrc`, `nit`, `dui`, `giro`, `telefono1`, `telefono2`, `celular`, `fax`, `email`, `ultcompra`, `acumulado`, `saldo`, `percibe`, `retiene`, `retiene10`, `a30`, `a60`, `a90`, `m90`, `vencido`, `pagadas`, `pendientes`, `total1`, `nombreche`, `viñeta`, `nacionalidad`) VALUES
(0, 'S64a84e27f2c699.05616479', 1, 1, 2, 1, NULL, 'PROVEEDOR NO DEFINIDO', 'sAN MIGUEL', '81', '13', NULL, '', '', '', '05239394-4', '', '', '', '', '', '', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 68);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resolucion`
--

CREATE TABLE `resolucion` (
  `id_resolucion` int(11) NOT NULL,
  `resolucion` varchar(25) NOT NULL COMMENT 'para uso de documentos de facturacion',
  `autorizacion` varchar(25) NOT NULL,
  `alias` varchar(5) NOT NULL,
  `fecha` date NOT NULL COMMENT 'entrada en vigencia',
  `desde` int(11) NOT NULL,
  `hasta` int(11) NOT NULL,
  `vigente` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `resolucion`
--

INSERT INTO `resolucion` (`id_resolucion`, `resolucion`, `autorizacion`, `alias`, `fecha`, `desde`, `hasta`, `vigente`) VALUES
(1, 'RESOLUCION COF', 'AUTORIZACION1', 'COF', '2022-01-03', 1, 5000, 1),
(2, 'RESOLUCION CCF', 'AUTORIZACION1', 'CCF', '2022-01-03', 1, 5000, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ruta`
--

CREATE TABLE `ruta` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_ruta` int(11) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `ruta`
--

INSERT INTO `ruta` (`id_server`, `unique_id`, `id_ruta`, `descripcion`, `id_usuario`) VALUES
(0, 'S62b931cd485d68.50577090', 0, 'cass', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutas`
--

CREATE TABLE `rutas` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `ruta` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rutas`
--

INSERT INTO `rutas` (`id_server`, `unique_id`, `id`, `descripcion`, `ruta`) VALUES
(1, 'O5f05eba6b67e89.17178057', 1, 'server', 'http://localhost/karinasyncro/server/mothership.php'),
(2, 'O5f05eba6b7e9b9.88558815', 2, 'local', 'http://localhost/karinasyncro/sistema/slave.php');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ruta_detalle`
--

CREATE TABLE `ruta_detalle` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_ruta_detalle` int(11) NOT NULL,
  `id_ruta` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_departamento` int(11) NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `orden` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `ruta_detalle`
--

INSERT INTO `ruta_detalle` (`id_server`, `unique_id`, `id_ruta_detalle`, `id_ruta`, `id_cliente`, `id_departamento`, `id_municipio`, `orden`) VALUES
(0, 'S62b931cd4b3971.17942844', 0, 0, 3, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `descripcion` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `costo` decimal(14,2) NOT NULL,
  `precio` decimal(14,2) NOT NULL,
  `tipo_prod_servicio` varchar(50) NOT NULL,
  `precio_iva` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `estado`, `descripcion`, `id_sucursal`, `id_categoria`, `costo`, `precio`, `tipo_prod_servicio`, `precio_iva`) VALUES
(1, 1, 'Matenimiento de Computadora', 0, 1, '20.00', '22.12', 'SERVICIO', '25.00'),
(3, 1, 'Reparacion de impresora', 0, 1, '50.00', '44.25', 'SERVICIO', '50.00'),
(4, 1, 'ALOJAMIENTO DE SISTEMA OPENPYME', 0, 1, '150.00', '132.74', 'SERVICIO', '150.00'),
(5, 1, 'Instalación basica de 4 camaras (No inlcluye materiales)', 0, 1, '75.00', '66.37', 'SERVICIO', '75.00'),
(6, 1, 'Instalación de 4 cámaras incluye materiales(4 cajas de registro 1 canaleta y grapas)', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(7, 1, 'instalación de 4 cámaras incluye materiales ', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(8, 1, 'Instalación de 4 cámaras incluye materiales ', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(9, 1, 'Instalación de 4 Cámaras (No incluye materiales)', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(10, 1, 'Reparacion de laptop', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(11, 1, 'Cambio de pantalla de laptop (No incluye repuesto)', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(12, 1, 'Instalación y Materiales', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(13, 1, 'Desarrollo de Modulo para generación de etiquetas ', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(14, 1, 'Instalación y Configuración', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(15, 1, 'ALOJAMIENTO WEB Y NOMBRE DE DOMINIO PARA SISTEMA DE VENTA DE CALZADO', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(16, 1, 'Anclas para bandeja y grapas ', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(17, 1, 'Instalación de infraestructura de red', 0, 1, '225.00', '225.00', 'SERVICIO', '0.00'),
(18, 1, 'SERVICIO DE ALOJAMIENTO WEB Y NOMBRE DE DOMINIO PARA SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(19, 1, 'Empalme de hilos de fibra ', 0, 1, '155.00', '155.00', 'SERVICIO', '0.00'),
(20, 1, 'Diagnostico de falla ', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(21, 1, 'Mantenimiento preventivo a equipo de escritorio ', 0, 1, '26.00', '26.00', 'SERVICIO', '0.00'),
(22, 1, 'Mano de obra', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(23, 1, 'Mantenimiento de Equipos informáticos ', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(24, 1, 'Renovación de dominio para cámaras de vigilancia ', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(25, 1, 'Mantenimiento de planta telefónica', 0, 1, '26.55', '26.55', 'SERVICIO', '0.00'),
(26, 1, 'Estructura y configuración de extensión de conmutador', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(27, 1, 'Reparación de UPS', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(28, 1, 'Cable para auricular RJ11', 0, 1, '3.54', '3.54', 'SERVICIO', '0.00'),
(29, 1, 'Servicio de arrendamiento de servidor dedicado   ', 0, 1, '240.00', '240.00', 'SERVICIO', '0.00'),
(30, 1, 'Respaldo de base de datos diarios ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(31, 1, 'Seguridad de servidores y mantenimiento  ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(32, 1, 'Sistema de control biométrico  ', 0, 1, '500.00', '500.00', 'SERVICIO', '0.00'),
(33, 1, 'Instalación de una cámara CCTV ', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(34, 1, 'Mantenimiento a sistema de vídeo vigilancia ', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(35, 1, 'Garantía en mano de obra 6 meses', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(36, 1, 'GARANTÍA EN MANO DE OBRA 6 MESES ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(37, 1, 'Dominio para monitoreo desde dispositivos móviles ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(38, 1, 'Instalación y configuración de sistema y equipos ', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(39, 1, 'MANO DE OBRA', 0, 1, '500.00', '500.00', 'SERVICIO', '0.00'),
(40, 1, 'Revisión y configuración  sistema de vídeo vigilancia ', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(41, 1, 'Instalación de infraestructura de red y conexión eléctrica.  ', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(42, 1, 'ALOJAMIENTO WEB Y NOMBRE DE DOMINIO PARA SISTEMA DE VENTA DE CALZADO CORRESPONDIENTE AL MES DE ABRIL', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(43, 1, 'ALOJAMIENTO WEB Y NOMBRE DE DOMINIO PARA SISTEMA DE VENTA DE CALZADO CORRESPONDIENTE AL MES DE MARZO', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(44, 1, 'SERVICIO DE MENSAJERIA INTEGRADO A SISTEMA DE VENTA DE CALZADO CORRESPONDIENTES AL MES DE MARZO Y ABRIL DE 2019', 0, 1, '53.10', '53.10', 'SERVICIO', '0.00'),
(45, 1, 'Instalación y configuración para impresión remota ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(46, 1, 'Integración de sistema de abonados de agua potable', 0, 1, '1500.00', '1500.00', 'SERVICIO', '0.00'),
(47, 1, 'Desarrollo de Aplicación Móvil Android para captura de lecturas de medidores', 0, 1, '500.00', '500.00', 'SERVICIO', '0.00'),
(48, 1, 'Capacitación a personal involucrado ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(49, 1, 'Garantía de 1 año con el fabricante ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(50, 1, 'SISTEMA DE GESTION DE PROCESOS MUNICIPALES (SIGPROM)', 0, 1, '3800.00', '3800.00', 'SERVICIO', '0.00'),
(51, 1, '*MODULO DE CONTRIBUYENTES', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(52, 1, '*MODULO DE CONTROL DE CUENTAS CORRIENTES', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(53, 1, '*MODULO DE GENERACION DE NOTIFICACIONES Y RECIBOS DE COBRO', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(54, 1, '*MODULO DE CONTROL MORATORIO Y REPORTERIA', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(55, 1, '*CAPACITACION PARA EL PERSONAL INVOLUCRADO', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(56, 1, 'Instalación y configuración de sistema de vídeo Vigilancia ', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(57, 1, 'SERVICIO DE ALOJAMIENTO WEB Y NOMBRE DE DOMINIO PARA SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE MAYO', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(58, 1, 'SERVICIO DE MENSAJERIA INTEGRADO A SISTEMA DE VENTA DE CALZADO CORRESPONDIENTES AL MES DE MAYO DE 2019', 0, 1, '53.10', '53.10', 'SERVICIO', '0.00'),
(59, 1, 'ALOJAMIENTO WEB Y NOMBRE DE DOMINIO PARA SISTEMA DE VENTA DE CALZADO CORRESPONDIENTE AL MES DE MAYO DE 2019 ', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(60, 1, 'Sistema de inventario y Facturación ', 0, 1, '500.00', '500.00', 'SERVICIO', '0.00'),
(61, 1, 'Reparación de trocal de planta telefónica ', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(62, 1, 'Revisión y diagnostico de sistema de vigilancia  ', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(63, 1, 'REPARACION DE EQUIPO DE COMPUTO', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(64, 1, 'Horas clase mes de Enero  ', 0, 0, '0.00', '0.00', 'SERVICIO', '0.00'),
(65, 1, 'Horas clase mes de Febrero', 0, 0, '0.00', '0.00', 'SERVICIO', '0.00'),
(66, 1, 'Horas clase mes de Marzo    ', 0, 0, '0.00', '0.00', 'SERVICIO', '0.00'),
(67, 1, 'Horas clase mes de Abril', 0, 0, '0.00', '0.00', 'SERVICIO', '0.00'),
(68, 1, 'Mantenimiento Preventivo a equipo Portatil', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(69, 1, 'Mantenimiento Preventivo a impresora combinada', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(70, 1, 'Mantenimiento Preventivo a Impresora Matricial ', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(71, 1, 'Estructuración de red de datos e instalación de cableado eléctrico', 0, 1, '430.03', '430.03', 'SERVICIO', '0.00'),
(72, 1, 'SERVICIO DE ALOJAMIENTO WEB Y NOMBRE DE DOMINIO PARA SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE JUNIO', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(73, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION, MENSAJERIA INTEGRADA Y ALOJAMINETO WEB CORRESPONDIENTE AL MES DE JUNIO', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(74, 1, 'Instalación y configuración de impresoras', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(75, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION, MENSAJERIA INTEGRADA Y ALOJAMINETO WEB CORRESPONDIENTE AL MES DE JULIO', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(76, 1, 'SERVICIO DE ALOJAMIENTO WEB Y NOMBRE DE DOMINIO PARA SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE JULIO', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(77, 1, 'Levantamiento de Inventario por 3 días con personal de 4 personas ', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(78, 1, 'Sistema Informático de control de restaurante ', 0, 1, '450.00', '450.00', 'SERVICIO', '0.00'),
(79, 1, 'Integración de sistema de control de abonados de agua potable a sistema SIGPROM', 0, 1, '1500.00', '1500.00', 'SERVICIO', '0.00'),
(80, 1, 'Desarrollo de Aplicación móvil para registro de consumo de abonados de sistema de Agua Potable', 0, 1, '500.00', '500.00', 'SERVICIO', '0.00'),
(81, 1, 'Horas clase mes de Mayo', 0, 1, '8.00', '8.00', 'SERVICIO', '0.00'),
(82, 1, 'Horas clase mes de Junio', 0, 1, '8.00', '8.00', 'SERVICIO', '0.00'),
(83, 1, 'Parciales', 0, 1, '180.12', '180.12', 'SERVICIO', '0.00'),
(84, 1, 'MANTENIMIENTO CORRECTIVO DE IMPRESORA DE CARNETS ZEBRA ZXP SERIES 3', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(85, 1, 'SERVICIO DE ALOJAMIENTO WEB Y NOMBRE DE DOMINIO PARA SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE AGOSTO', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(86, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION, MENSAJERIA INTEGRADA Y ALOJAMINETO WEB CORRESPONDIENTE AL MES DE AGOSTO', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(87, 1, 'SOPORTE DE SISTEMA DE INVENTARIO Y FACTURACIÓN DE MEDICAMENTOS', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(88, 1, 'REVISION Y DIAGNOSTICO IMPRESOR DE CARNETS ZEBRA ZXP3', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(89, 1, 'ZEBRACARD, PIEZA DE REPUESTO, ZXP3, KIT TABLERO DE LÓGICA PRINCIPAL CON ALAMBRE DE TIERRA ZXP3', 0, 1, '550.00', '550.00', 'SERVICIO', '0.00'),
(90, 1, 'ESTRUCTURACION Y CONFIGURACION DE CABLE PARA CAMARA CCTV', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(91, 1, 'CONFIGURACION DE ACCESO REMOTO A DVR', 0, 1, '20.75', '20.75', 'SERVICIO', '0.00'),
(92, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION, MENSAJERIA INTEGRADA Y ALOJAMINETO WEB CORRESPONDIENTE AL MES DE SEPTIEMBRE', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(93, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION MES DE SEPTIEMBRE 2019', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(94, 1, 'INSTALACION DE SOFTWARE UTILITARIO', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(95, 1, 'Servicio anual de alojamiento de pagina web de 10G  en disco SSD en servidor dedicado', 0, 1, '110.00', '110.00', 'SERVICIO', '0.00'),
(96, 1, 'Renovación anual de sub dominio .SV', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(97, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACIÓN', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(98, 1, 'SERVICIO DE ALOJAMIENTO Y NOMBRE DE DOMINIO', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(99, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION, MENSAJERIA INTEGRADA Y ALOJAMINETO WEB CORRESPONDIENTE AL MES DE OCTUBRE', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(100, 1, '	SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION MES DE OCTUBRE 2019', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(101, 1, 'Sistema Inventario y Facturación ', 0, 1, '500.00', '500.00', 'SERVICIO', '0.00'),
(102, 1, 'servicio de consulta', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(103, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE NOVIEMBRE 2019', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(104, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION, MENSAJERIA INTEGRADA Y ALOJAMINETO WEB CORRESPONDIENTE AL MES DE NOVIEMBRE', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(106, 1, 'INSTALACION DE KIT DE 8 CAMARAS', 0, 1, '918.96', '918.96', 'SERVICIO', '0.00'),
(107, 1, '	SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE DICIEMBRE 2019', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(108, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION, MENSAJERIA INTEGRADA Y ALOJAMINETO WEB CORRESPONDIENTE AL MES DE DICIEMBRE', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(109, 1, 'SERVICIO ILIMITADO DE MENSAJERIA SMS', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(110, 1, 'INSTALACION DE DOS CAMARAS CCTV', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(111, 1, 'SOPORTE EXTENDIDO A SISTEMA INFORMATICO', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(112, 1, 'PAGO MENSUAL POR SOPORTE TÉCNICO EXTENDIDO PARA SISTEMA DE INVENTARIO Y FACTURACIÓN', 0, 1, '65.50', '65.50', 'SERVICIO', '0.00'),
(113, 1, 'ADQUISICION DE SISTEMA INFORMATICO Y EQUIPOS DE COMPUTO.', 0, 1, '3035.00', '3035.00', 'SERVICIO', '0.00'),
(114, 1, 'Estructura de montaje de cámara tipo L', 0, 1, '3.00', '3.00', 'SERVICIO', '0.00'),
(115, 1, 'Mantenimiento de DVR', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(116, 1, 'Mano de obra y materiales para estructura de red de datos', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(117, 1, 'Servicio de Horas Clases', 0, 1, '9.04', '9.04', 'SERVICIO', '0.00'),
(118, 1, 'Exámenes Parciales', 0, 1, '42.18', '42.18', 'SERVICIO', '0.00'),
(119, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION, MENSAJERIA INTEGRADA Y ALOJAMIENTO WEB CORRESPONDIENTE AL MES DE ENERO', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(120, 1, 'SERVICIO ILIMITADO DE MENSAJERIA POR SMS', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(121, 1, '	SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ENERO 2020', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(122, 1, 'SERVICIO DE SOPORTE AL SISTEMA DE LABORATORIO CLINICO', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(123, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(124, 1, '	SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ENERO 2020', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(125, 1, 'Servicio de soporte extendido, sistema de inventario y facturación', 0, 1, '304.00', '304.00', 'SERVICIO', '0.00'),
(126, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO SAN SALVADOR', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(127, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO SAN MIGUEL', 0, 1, '23.00', '23.00', 'SERVICIO', '0.00'),
(128, 1, 'INSTALACION DE KIT CCTV DE 16 CAMARAS', 0, 1, '796.46', '796.46', 'SERVICIO', '0.00'),
(129, 1, 'SERVICIO DE HORAS CLASES', 0, 1, '9.04', '9.04', 'SERVICIO', '0.00'),
(130, 1, 'EXAMENES PARCIALES', 0, 1, '42.18', '42.18', 'SERVICIO', '0.00'),
(131, 1, 'SERVICIO DE ALOJAMIENTO ANUAL  DE SISTEMA CIMA', 0, 1, '240.00', '240.00', 'SERVICIO', '0.00'),
(132, 1, 'DESARROLLO DE APLICACION MOVIL PARA CONTROL DE INVENTARIO', 0, 1, '530.97', '530.97', 'SERVICIO', '0.00'),
(133, 1, 'SERVICIO DE ALOJAMIENTO WEB Y NOMBRE DE DOMINIO PARA SISTEMA DE INVENTARIO Y FACTURACION FEBRERO 2020', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(134, 1, 'ALOJAMIENTO DE PAGINA WEB Y RENOVACION DE DOMINIO DE INTERT', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(135, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE FEBRERO', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(136, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(137, 1, 'SERVICIO DE SOPORTE EXTENDIDO DE SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(138, 1, 'SERVICIO DE SOPORTE EXTENDIDO DE SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '88.49', '88.49', 'SERVICIO', '0.00'),
(139, 1, 'SERVICIO DE SOPORTE EXTENDIDO DE SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(140, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONIDIENTE AL MES DE FEBRERO', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(141, 1, 'SOPORTE EXTENDIDO A SISTEMA DE INVENTARIO Y FACTURACIÓN ', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(142, 0, 'mantenimiento preventivo ', 0, 1, '10.00', '15.00', 'SERVICIO', '0.00'),
(143, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION, MENSAJERIA INTEGRADA Y ALOJAMIENTO WEB CORRESPONDIENTE AL MES DE MARZO', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(144, 1, 'SISTEMA INFORMATICO DE SERVICIOS HOSPITALARIOS ', 0, 1, '3097.35', '3097.35', 'SERVICIO', '0.00'),
(145, 1, 'Soporte tecnico a sistema de inventario y facturacion OPenPyme', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(146, 1, 'Instalación de punto de red ', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(147, 1, 'SOPORTE TECNICO SISTEMA INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ABRIL', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(148, 1, 'INSTALACION DE PUNTO DE RED', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(149, 1, 'PROCESOS DE SELECCION PRUEBA POLIGRAFICA  EVALUACIONES PSICOMETRICAS  **JUAN CARLOS MORALES CANIZALES**', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(150, 1, 'PROCESOS DE SELECCION PRUEBA POLIGRAFICA  EVALUACIONES PSICOMETRICAS  **JUAN CARLOS MORALES CANIZALES**', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(151, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(152, 1, 'EVALUACIONES POLIGRAFICAS ESPECIFICAS ', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(153, 1, 'EVALUACION POLIGRAFICA PRE-EMPLEO (ERIKA LISSETTE SOTO ALVAREZ) )', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(154, 1, 'INVESTIGACION SOCIOECONOMICA (EDWIN LEONEL BARRERA MEJIA))', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(155, 1, 'Servicio de Alojamiento de Sistema Informatico, incluye pago Mayo 2019 hasta Mayo 2020', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(156, 1, 'Servicio de mensajeria SMS de mes de Febrero a Junio ', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(157, 1, 'servicio de alojamiento para sistema de control de ingresos ministerios enmanuel', 0, 1, '240.00', '240.00', 'SERVICIO', '0.00'),
(158, 1, 'SERVICIO DE SOPORTE EXTENDIDO A SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(159, 1, 'SERVICIO SOPORTE TECNICO EXTENDIDO CORRESPONDIENTE A ABRIL  -  MAYO 2020', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(160, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO 10% DESCUENTO', 0, 1, '18.00', '18.00', 'SERVICIO', '0.00'),
(161, 1, 'INSTALACION DE CAMARA Y MATERIALES ', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(162, 1, 'DIAGNOSTICO DE FALLOS DE SISTEMA DE VIDEO VIGILANCIA ', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(163, 1, 'REPARACION DE EQUIPO INFORMATICO', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(164, 1, 'evaluaciones poligraficas preempleo', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(165, 1, 'ADAPTACION DE FORMATO DE FACTURA ', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(166, 1, 'MANTENIMIENTO Y REPARACION DE EQUIPO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(167, 1, 'PRUEBA PRE-EMPLEO JULIO ANTONIO CHICAS ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(168, 1, 'PRUEBA PRE-EMPLEO RAUL ANTONIO LARA GONZALEZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(169, 1, 'RENOVACION DE DOMINIO PUNTOOPTICO.COM.SV', 0, 1, '27.00', '27.00', 'SERVICIO', '0.00'),
(170, 1, 'MATERIALES PARA INSTALACION DE CAMARAS IP', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(171, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(172, 1, 'SOPORTE TECNICO AL SISTEMA FINANCIERO', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(173, 1, 'SOPORTE TECNICO AL SISTEMA FINANCIERO', 0, 1, '2400.00', '2400.00', 'SERVICIO', '0.00'),
(177, 1, 'SOPORTE TECNICO A SISTEMA FINANCIERO', 0, 1, '2400.00', '2400.00', 'SERVICIO', '0.00'),
(178, 1, 'REPARACION DE EQUIPO INFORMATICO', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(179, 1, 'SOPORTE TECNICO PARA SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE JUNIO', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(180, 1, 'MANO DE OBRA ', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(181, 1, 'SISTEMA PARA INVENTARIO Y FACTURACION', 0, 1, '450.00', '450.00', 'SERVICIO', '0.00'),
(182, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO 10% DE DESCUENTO', 0, 1, '15.30', '15.30', 'SERVICIO', '0.00'),
(183, 1, 'JOSE LUCAS GARAY VILLEDA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(184, 1, 'WILFREDO ALEXANDER GUZMAN CRUZ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(185, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DE ABRIL A JULIO 2020', 0, 1, '398.23', '398.23', 'SERVICIO', '0.00'),
(186, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION  DE ABRIL A JULIO 2020 ', 0, 1, '398.23', '398.23', 'SERVICIO', '0.00'),
(187, 1, 'DELL OPTIPLEX 3020 CORE I3 4TA GEN- RAM 4G- DISCO 500G- GARANTIA DE 6 MESES', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(188, 1, 'HÉCTOR GABRIEL ORTEZ MOLINA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(189, 1, 'ALEXIS RAFAEL PINEDA LAÍNEZ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(190, 1, 'DANIS ALEXANDER PRIVADO LOVO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(191, 1, 'WILBER GIOVANI GUZMÁN VILLALTA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(192, 1, 'KATHERINE ELIZABETH MORALES LÓPEZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(193, 1, 'OSCAR ALEJANDRO CASTILLO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(194, 1, 'DIEGO DUBÁN RIVERA RIVERA MART ÍNEZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(195, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE JULIO 2020', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(196, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE A LOS MESES DE MARZO-JULIO 2020', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(197, 1, 'Servicio de Alojamiento,Sincronización de datos, Dominio de Internet y servicios para App Android- (Pago mensual). ', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(198, 1, 'Sistema para pedidos y APP para Android ', 0, 1, '600.00', '600.00', 'SERVICIO', '0.00'),
(199, 1, 'SERVICIO DE ENTREGAS DE PAQUETES  A DOMICILIO', 0, 1, '2.50', '2.50', 'SERVICIO', '0.00'),
(200, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE JULIO 2020', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(201, 1, 'SISTEMA DE INVENTARIO Y FACTURACION BASICO ', 0, 1, '800.00', '800.00', 'SERVICIO', '0.00'),
(202, 1, 'MANTENIMIENTO PREVENTIVO A EQUIPO INFORMATICO', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(203, 1, 'MOISES ALEJANDRO OCHOA ROMERO (10% DE DESCUENTO)', 0, 1, '22.50', '22.50', 'SERVICIO', '0.00'),
(204, 1, 'MARIO ALEXIS ARGUETA SANDOVAL', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(205, 1, 'LESTER STEVEE VILLALTA FLORES', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(206, 1, 'JOSE ALEXIS SANTOS CLAROS', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(207, 1, 'WILBER GIOVANI GUZMAN VILLALTA', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(208, 1, 'CHRISTIAN ENRIQUEZ RUIZ VILLALOBOS', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(209, 1, 'IVAN ALEXANDER MOLINA PERDOMO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(210, 1, 'ELSY ESMERALDA BARRERA DE PRIVADO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(211, 1, 'JOSELINE BEATRIZ PEREZ HERNANDEZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(212, 1, 'DIAGNOSTICO Y CONFIGURACION ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(213, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE AGOSTO 2020', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(214, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE AGOSTO 2020', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(215, 1, 'SOPORTE TECNICO A SISTEMA DE CONTROL DE LABORATORIO CLINICO CORRESPONDIENTE AL MES DE AGOSTO 2020', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(216, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE AGOSTO 2020 2020', 0, 1, '88.49', '88.49', 'SERVICIO', '0.00'),
(217, 1, 'MATERIALES E INSTALACION ELECTRICTICA', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(218, 1, 'SOPORTE TECNICO PARASISTEMA INFORMATICO CORRESPONDIENTE AL MES DE AGOSTO 2020', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(219, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE AGOSTO 2020', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(220, 1, 'SISTEMA DE INVENTARIO Y FACTURACIÓN', 0, 1, '600.00', '600.00', 'SERVICIO', '0.00'),
(221, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONIDIENTE AL MES DE MAYO DE 2020', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(222, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE SEPTIEMBRE', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(223, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE AGOSTO 2020', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(224, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACIÓN', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(225, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE SEPTIEMBRE 2020', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(226, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE SEPTIEMBRE 2020', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(227, 1, '	SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE SEPTIEMBRE 2020', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(228, 1, 'SOPORTE DE SISTEMA DE INVENTARIO Y FACTURACION DE MEDICAMENTOSCORRESPONDIENTE AL MES DE SEPTIEMBRE 2020', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(229, 1, 'SOPORTE DE SISTEMA DE INVENTARIO Y FACTURACION DE MEDICAMENTOSCORRESPONDIENTE AL MES DE SEPTIEMBRE', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(230, 1, 'INSTALACIÓN DE TOMA, CABLE DE RED, CORRIENTE Y MATERIALES', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(231, 1, 'SERVICIO DE TIENDA EN LINEA   APLICACION WEB  SOCIAL MEDIA', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(232, 1, 'MANTENIMIENTO DE SOTFWARE Y HARDWARE A EQUIPO', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(233, 1, 'EQUIPO CORE I3 CON GARANTIA DE 6 MESES', 0, 1, '140.00', '140.00', 'SERVICIO', '0.00'),
(234, 1, 'EUGENIA BEATRIZ HERNANDEZ PALACIOS', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(235, 1, 'KATHERYN ELIZABETH REYES FLORES', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(236, 1, 'MIRNA LORENA VILLALTA AGUILLON ', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(237, 1, 'EQUIPO CORE I3 CON GARANTIA DE 6 MESES', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(238, 1, 'EQUIPO CORE I3 CON GARANTIA DE 6 MESES', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(239, 1, 'EQUIPO CORE I3 CON GARANTIA DE 6 MESES', 0, 1, '140.00', '140.00', 'SERVICIO', '0.00'),
(240, 1, 'KATHERINE JULISSA SOLORZANO MAJANO', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(241, 1, 'JONATHAN ARTURO PANIAGUA PORTILLO', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(242, 1, 'ERICK DANILO HERNANDEZ HERNANDEZ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(243, 1, 'KATHERINE JULISSA SOLORZANO MAJANO', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(244, 1, 'PROCESOS DE SELECCION PRUEBA POLIGRAFICA EVALUACIONES PSICOMETRICAS **DIEGO JOSE MARTINEZ VARGAS**', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(245, 1, 'PROCESOS DE SELECCION PRUEBA POLIGRAFICA EVALUACIONES PSICOMETRICAS **DIEGO JOSE MARTINEZ VARGAS**', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(246, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO 10% DE DESCUENTO', 0, 1, '18.00', '18.00', 'SERVICIO', '0.00'),
(247, 1, 'HELVIN FRANCISCO SANCHEZ TRUJILLO', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(248, 1, 'REYNALDO ALEXANDER TREJO GALVEZ', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(249, 1, 'ANA NOEMI VIGIL DE GARCIA ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(250, 1, 'MANO DE OBRA', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(251, 1, 'MANO DE OBRA', 0, 1, '500.00', '500.00', 'SERVICIO', '0.00'),
(252, 1, 'Reparación de reloj biometrico ', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(253, 1, 'MANO DE OBRA', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(254, 1, 'MANO DE OBRA', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(255, 1, 'REPARACION DE RELOJ BIOMETRICO', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(256, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE OCTUBRE 2020', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(257, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE OCTUBRE 2020', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(258, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE OCTUBRE 2020', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(259, 1, 'REPARACION DE SERVIDOR CON CAMBIO DE DISCO DURO', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(260, 1, 'MANTENIMIENTO PREVENTIVO A SERVIDOR-FOTOCOPIADORA- IMPRESOR LASER', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(261, 1, 'MOUSE', 0, 1, '5.31', '5.31', 'SERVICIO', '0.00'),
(262, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE OCTUBRE 2020', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(263, 1, 'CABLE DE PODER', 0, 1, '3.54', '3.54', 'SERVICIO', '0.00'),
(264, 1, 'ADICION DE MODULO DE REPORTE DE INGRESO Y EGRESO DE PRODUCTOS', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(265, 1, 'MANO DE OBRA', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(266, 1, 'MANO DE OBRA', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(267, 1, 'OSELVIN ELÍ SANTOS SANTOS', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(268, 1, 'ANGÉLICA RAQUEL LOZA CÁRCAMO', 0, 1, '18.00', '18.00', 'SERVICIO', '0.00'),
(269, 1, '	SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE OCTUBRE 2020', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(270, 1, 'MANO DE OBRA', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(271, 1, 'MANO DE OBRA', 0, 1, '155.00', '155.00', 'SERVICIO', '0.00'),
(272, 1, 'SISTEMA DE INVENTARIO Y FACTURACION LITE', 0, 1, '645.00', '645.00', 'SERVICIO', '0.00'),
(273, 1, 'SISTEMA INVENTARIO Y FACTURACIÓN COMPLETO', 0, 1, '1200.00', '1200.00', 'SERVICIO', '0.00'),
(274, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONIDIENTE AL MES DE JUNIO DE 2020', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(275, 1, 'WILLIAM ERNESTO HERNÁNDEZ ECHEVERRÍA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(276, 1, 'GERSON JAVIER HERNÁNDEZ BATRES', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(277, 1, 'WILLIAM ERNESTO HERNÁNDEZ ECHEVERRÍA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(278, 1, 'GERSON JAVIER HERNÁNDEZ BATRES', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(279, 1, 'SISTEMA BASICO DE PUNTO DE VENTA', 0, 1, '600.00', '600.00', 'SERVICIO', '0.00'),
(280, 1, 'SERVICIO DE INSTALACIÓN Y CONFIGURACIÓN DE RED', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(281, 1, 'MATERIALES DE INSTALACIÓN Y MANO DE OBRA', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(282, 1, 'INSTALACIÓN Y CONFIGURACIÓN DE EQUIPOS', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(283, 1, 'ALEXIS JESUS FLORES ALVARADO', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(284, 1, 'SERVICIO DE TIENDA EN LINEA APLICACION WEB SOCIAL MEDIA', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(285, 1, 'MANO DE OBRA', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(286, 1, 'CABLE VNC 18MTS', 0, 1, '12.05', '12.05', 'SERVICIO', '0.00'),
(287, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 5 SALIDAS', 0, 1, '18.98', '18.98', 'SERVICIO', '0.00'),
(288, 1, 'MANO DE OBRA', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(289, 1, 'CABLE VNC 18MTS', 0, 1, '12.05', '12.05', 'SERVICIO', '0.00'),
(290, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 5 SALIDAS', 0, 1, '18.98', '18.98', 'SERVICIO', '0.00'),
(291, 1, 'MANO DE OBRA', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(292, 1, 'REPARACION Y MANTENIMIENTO DE IMPRESORAS ', 0, 1, '22.13', '22.13', 'SERVICIO', '0.00'),
(293, 1, 'CANALETA DE 15 X 10 CON ADHESIVO', 0, 1, '1.70', '1.70', 'SERVICIO', '0.00'),
(294, 1, 'MANO DE OBRA', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(295, 1, 'INSTALACION DE PUNTO DE RED PARA RELOJ BIOMETRICO ', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(296, 1, 'MEMORIA RAM DDR3 2GB', 0, 1, '13.28', '13.28', 'SERVICIO', '0.00'),
(297, 1, 'MANO DE OBRA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(298, 1, 'MANTENIMIETO PREVENTIVO Y CORRECTIVO', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(299, 1, 'MEMORIA RAM DDR3 2GB SAMSUNG', 0, 1, '13.28', '13.28', 'SERVICIO', '0.00'),
(300, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE NOVIEMBRE  2020', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(301, 1, 'KARLA JOHANA COLINDRES ALVAREZ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(302, 1, 'DISCO EXTERNO H.D SEAGATE 1TB EXT.2.5 EXPANSION 3.0U', 0, 1, '57.53', '57.53', 'SERVICIO', '0.00'),
(303, 1, 'MANTENIMIENTO PREVENTIVO A SERVIDOR-FOTOCOPIADORA- IMPRESOR LASER', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(304, 1, 'MANO DE OBRA', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(305, 1, 'OSCAR RENE ESCOLERO RAMIREZ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(306, 1, 'eELVIS ALEXANDER MORALES GUZMAN', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(307, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE NOVIEMBRE  2020', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(308, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE NOVIEMBRE  2020', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(309, 1, ' de  SOPORTE  DE  SISTEMA  DE  INVENTARIO  Y  FACTURACION  DE  MEDICAMENTOS', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(314, 1, ' SOPORTE  DE  SISTEMA  DE  INVENTARIO  Y  FACTURACION  DE  MEDICAMENTOS', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(315, 1, 'LAZARO GABRIEL RAMOS JAUREZ', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(316, 1, 'VILMA ELIZABETH HERNANDEZ DEL CID', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(317, 1, 'BRENDA JAMILETH CHACON GONZALEZ', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(318, 1, 'JACQUELINE BEATRIZ PORTILLO', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(319, 1, 'LAZARO GABRIEL RAMOS JUAREZ', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(320, 1, 'VILMA ELIZABETH HERNANDEZ DEL CID', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(321, 1, 'BRENDA JAMILETH CHACON GONZALEZ', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(322, 1, 'JACQUELINE BEATRIZ PORTILLO', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(323, 1, 'COSTO DE ENVIO', 0, 1, '8.86', '8.86', 'SERVICIO', '0.00'),
(324, 1, 'CANALETA DE 16X10 CON ADHESIVO', 0, 1, '2.66', '2.66', 'SERVICIO', '0.00'),
(325, 1, 'COSTO DE ENVIO', 0, 1, '8.86', '8.86', 'SERVICIO', '0.00'),
(326, 1, '	EQUIPO DELL OPTIPLEX 390 SFF - I3 3.3GHZ - RAM 4GB - SSD 240G', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(327, 1, 'INSTALACION  DE CONECTORES  DE ENERGIA ', 0, 1, '13.01', '13.01', 'SERVICIO', '0.00'),
(328, 1, 'INSTALACION DE  PUNTO DE RED ', 0, 1, '13.01', '13.01', 'SERVICIO', '0.00'),
(329, 1, 'ESTRUCTURACIÓN DE 7 PUNTOS DE RED Y ORDENAMIENTO DE CABLES EN UNIDAD DE LABORATORIO', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(330, 1, 'MANO DE OBRA', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(331, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONIDIENTE AL MES DE JULIO DE 2020', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(332, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONIDIENTE AL MES DE JULIO DE 2020', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(333, 1, 'TONER HP 17A GENUINO BLACK M102 MFP M130W CF217A', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(334, 1, 'TONER HP 17A GENERICO BLACK M102 MFP M130W CF217A', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(335, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE DICIEMBRE 2020', 0, 1, '39.83', '39.83', 'SERVICIO', '0.00'),
(336, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, CORRESPONDIENTE AL MES DE DICIEMBRE DE 2020', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(337, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE DICIEMBRE 2020', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(339, 1, 'SISTEMA INVENTARIO Y FACTURACIÓN VERSIÓN LITE', 0, 1, '700.00', '700.00', 'SERVICIO', '0.00'),
(340, 1, 'YEIMI DANIELA ERAZO JACOBO ', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(341, 1, 'GABRIELA ABIGAIL MARQUEZ PORTILLO', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(342, 1, 'NELLY YANETH CARCAMO DE NOLASCO', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(343, 1, 'JOSE MANUEL RODRIGUEZ BELTRAN', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(344, 1, 'NARCISA YAMILETH FUENTES RAMOS', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(345, 1, 'SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE DICIEMBRE 2020', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(346, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE DICIEMBRE 2020', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(347, 1, 'KEYSTONE CAT5E BLANCO CON CAJA Y TAPADERA', 0, 1, '2.44', '2.44', 'SERVICIO', '0.00'),
(348, 1, 'PATCH CORD UTP CAT5E 2 MTS AZUL', 0, 1, '2.44', '2.44', 'SERVICIO', '0.00'),
(349, 1, 'PATCH CORD UTP CAT5E 1 MT AZUL NAVY', 0, 1, '1.84', '1.84', 'SERVICIO', '0.00'),
(350, 1, 'PATCH PANEL 24 PUERTOS CAT5E', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(351, 1, 'ORDENADOR DE CABLE UNA UNIDA', 0, 1, '33.90', '33.90', 'SERVICIO', '0.00'),
(352, 1, 'SWITCH 24 PUERTOS MARCA NEXXT', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(353, 1, 'REPARACION DE EQUIPO DE COMPUTO', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(354, 1, 'TOMA CORRIENTE Y MATERIALES DE INSTALACION', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(355, 1, 'INSTALACION DE PUERTOS DE RED Y MATERIALES', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(356, 1, 'PATCH CORD UTP CAT5E 1 MT AZUL NAVY', 0, 1, '1.84', '1.84', 'SERVICIO', '0.00'),
(357, 1, 'MANO DE OBRA', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(358, 1, 'MANO DE OBRA Y MANTENIMIENTO DE GABINETE Y UNIDAD DE GRABACION', 0, 1, '107.00', '107.00', 'SERVICIO', '0.00'),
(359, 1, 'KEYSTONE CAT5E BLANCO', 0, 1, '1.80', '1.80', 'SERVICIO', '0.00'),
(360, 1, 'PATCH PANEL 24 PUERTOS CAT5E', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(361, 1, 'PATCH CORD UTP CAT5E 1 MT AZUL NAVY', 0, 1, '1.50', '1.50', 'SERVICIO', '0.00'),
(362, 1, 'BANDEJA PARA GABINETE DE ACERO', 0, 1, '12.00', '12.00', 'SERVICIO', '0.00'),
(363, 1, 'ORDENADOR DE CABLE UNA UNIDAD', 0, 1, '18.00', '18.00', 'SERVICIO', '0.00'),
(364, 1, 'UPS 600VA 8NEMAS-15R', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(365, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACIÓN', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(366, 1, 'TABLET INFANTIL ALCATEL 8052-2COFUS1 GREEN KID-MI 7', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(367, 1, 'JOSE ISAAC GARCIA MARCIA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(368, 1, 'MARVIN DE JESUS ROSALES GONZALEZ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(369, 1, 'JOSE OSVALDO MARIN FLORES', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(370, 1, 'DIMAS ANTONIO SANCHEZ SANCHEZ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(371, 1, 'MARIA ELENA PERALTA DE YANEZ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(372, 1, 'MANO DE OBRA', 0, 1, '600.00', '600.00', 'SERVICIO', '0.00'),
(373, 1, 'RELOJ BIOMETRICO', 0, 1, '275.00', '275.00', 'SERVICIO', '0.00'),
(374, 1, 'MARIO ENRIQUE COREAS GIRON', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(375, 1, 'INSTALACION DE PUNTO DE RED Y MATERIALES', 0, 1, '22.60', '22.60', 'SERVICIO', '0.00'),
(376, 1, 'PROCESOS DE SELECCION PRUEBA POLIGRAFICA EVALUACIONES PSICOMETRICAS', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(377, 1, 'MARIA JOSEFINA VIDES DE ALFARO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(378, 1, 'MAYNOR FABRIZIO CAMPOS FUENTES', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(379, 1, 'CRISTHIAN LEONIDAS VELIZ VILLATORO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(380, 1, 'MIRIAM LETICIA GRANADOS FLORES', 0, 1, '22.00', '22.00', 'SERVICIO', '0.00'),
(381, 1, 'GABINETE DE 6U ABATIBLE', 0, 1, '185.00', '185.00', 'SERVICIO', '0.00'),
(382, 1, 'SISTEMA INFORMATICO DE INVENTARIO Y FACTURACION ', 0, 1, '400.00', '400.00', 'SERVICIO', '0.00'),
(383, 1, 'CHRISTIAN GIOVANNI BARRERA PACAS', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(384, 1, 'MAURICIO ANTONIO CRUZ MALDONADO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(385, 1, 'MATERIALES DE ISNTALACION', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(386, 1, 'MANO DE OBRA', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(387, 1, 'DELMY LORENA LOPEZ LOPEZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(388, 1, 'SANDRA YAMILETH MARQUEZ CRUZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(389, 1, 'JOSE MANUEL MARIN ARANIVA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(390, 1, 'CARLOS WALTER UMAÑA BENITEZ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(391, 1, 'GUILLERMO SANTOS SARAVIA MORENO', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(392, 1, 'FRANKLIN RONALDO PEREZ LARIN', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(393, 1, 'KEVIN ISAAC OLIVARES SANDOVAL', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(394, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, CORRESPONDIENTE AL MES DE ENERO 2021 ', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(395, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONIDIENTE AL MES DE AGOSTO DE 2020', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(396, 1, 'MATERIALES E INSTALACION PARA PUNTO DE RED', 0, 1, '12.00', '12.00', 'SERVICIO', '0.00'),
(397, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE ENERO DE 2021', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(398, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE ENERO 2021', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(399, 1, 'MANO DE OBRA', 0, 1, '177.65', '177.65', 'SERVICIO', '0.00'),
(400, 1, 'KEYSTONE CAT5E BLANCO', 0, 1, '2.44', '2.44', 'SERVICIO', '0.00'),
(401, 1, 'PATCH CORD CAT5 90 CM AZUL', 0, 1, '1.80', '1.80', 'SERVICIO', '0.00'),
(402, 1, 'ORDENADOR DE CABLE UNA UNIDAD', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(403, 1, 'PATCH PANEL 24 PUERTOS CAT5E', 0, 1, '36.00', '36.00', 'SERVICIO', '0.00'),
(404, 1, 'BANDEJA DE TECLADO Y MOUSE PARA RACK', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(405, 1, 'CABLE UTP CAT5 INTEMPERIE', 0, 1, '0.95', '0.95', 'SERVICIO', '0.00'),
(406, 1, 'CABLE UTP CAT5 INTEMPERIE', 0, 1, '0.95', '0.95', 'SERVICIO', '0.00'),
(407, 1, 'BANDEJA DE TECLADO Y MOUSE PARA RACK', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(408, 1, 'PATCH PANEL 24 PUERTOS CAT5E', 0, 1, '36.00', '36.00', 'SERVICIO', '0.00'),
(409, 1, 'ORDENADOR DE CABLE UNA UNIDAD', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(410, 1, 'PATCH CORD CAT5 90 CM AZUL', 0, 1, '1.80', '1.80', 'SERVICIO', '0.00'),
(411, 1, 'KEYSTONE CAT5E BLANCO', 0, 1, '2.44', '2.44', 'SERVICIO', '0.00'),
(412, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ENERO', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(413, 1, 'MIRIAN STEFANI NAVARRETE GARCIA', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(414, 1, 'NATALY LISSETH MERLOS GUZMAN', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(415, 1, 'RODRIGO EDILBERTO PINEDA AVALOS', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(416, 1, 'RODRIGO EDILBERTO PINEDA AVALOS', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(417, 1, 'NATALY LISSETH MERLOS GUZMAN', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(418, 1, 'MIRIAN STEFANIE NAVARRETE GARCIA', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(419, 1, 'IRMA ELIZABETH HERNANDEZ MONTOYA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(420, 1, 'MANTENIMIENTO PREVENTIVO A DVR', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(421, 1, 'DESARROLLO DE MODULO DE MODIFICACIÓN DE RECIBOS', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(422, 1, 'DESARROLLO DE MODULO DE MODIFICACIÓN DE RECIBOS Y CONTRATOS', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(423, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '175.00', '175.00', 'SERVICIO', '0.00'),
(424, 1, 'ALOJAMIENTO DE PAGINA WEB Y RENOVACION DE DOMINIO DE INTERNET', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(425, 1, 'MANO DE OBRA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(426, 1, 'YESSENIA NOHEMY VENTURA ROMANO', 0, 1, '23.00', '23.00', 'SERVICIO', '0.00'),
(427, 1, 'MANTENIMIENTO DE PLANTA TELEFONICA E INSTALACION DE EXTENSION ', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(428, 1, 'KIT DE 6 CAMARAS EPCOM DE 1080 E INSTALACION ', 0, 1, '582.25', '582.25', 'SERVICIO', '0.00'),
(429, 1, 'KIT DE 4 CAMARAS EPCOM DE 1080 E INSTALACION ', 0, 1, '436.89', '436.89', 'SERVICIO', '0.00'),
(430, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONIDIENTE AL MES DE SEPTIEMBRE DE 2020', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(431, 1, 'OCTAVIO JOSE AGUILAR CORRALES', 0, 1, '23.00', '23.00', 'SERVICIO', '0.00'),
(432, 1, 'LENOVO  Serie: MJ01V5NS- modelo: ThinkCenter M73, COMBO TECLADO MOUSE- MONITOR LED AOC 15.6- GARANTIA 6 MESES', 0, 1, '243.37', '243.37', 'SERVICIO', '0.00'),
(433, 1, 'LENOVO  Serie: MJ01R5BV- modelo: ThinkCenter M73, COMBO TECLADO MOUSE- MONITOR LED AOC 15.6- GARANTIA 6 MESES', 0, 1, '243.37', '243.37', 'SERVICIO', '0.00'),
(434, 1, 'LENOVO  Serie: MJ02VA8H- modelo: ThinkCenter M73, COMBO TECLADO MOUSE- MONITOR LED AOC 15.6- GARANTIA 6 MESES', 0, 1, '243.36', '243.36', 'SERVICIO', '0.00'),
(435, 1, 'LENOVO  Serie: MJ029TLH- modelo: ThinkCenter M73, COMBO TECLADO MOUSE- MONITOR LED AOC 15.6- GARANTIA 6 MESES', 0, 1, '243.36', '243.36', 'SERVICIO', '0.00'),
(436, 1, 'LENOVO  Serie: MJ02VA6H- modelo: ThinkCenter M73, COMBO TECLADO MOUSE- MONITOR LED AOC 15.6- GARANTIA 6 MESES', 0, 1, '243.36', '243.36', 'SERVICIO', '0.00'),
(437, 1, 'LENOVO Serie: MJ02VA8H- modelo: ThinkCenter M73, COMBO TECLADO MOUSE- MONITOR LED AOC 15.6- GARANTIA 6 MESES', 0, 1, '243.36', '243.36', 'SERVICIO', '0.00'),
(438, 1, 'LENOVO Serie: MJ01R5BV- modelo: ThinkCenter M73, COMBO TECLADO MOUSE- MONITOR LED AOC 15.6- GARANTIA 6 MESES', 0, 1, '243.36', '243.36', 'SERVICIO', '0.00'),
(439, 1, 'LENOVO Serie: MJ01V5NS- modelo: ThinkCenter M73, COMBO TECLADO MOUSE- MONITOR LED AOC 15.6- GARANTIA 6 MESES', 0, 1, '243.37', '243.37', 'SERVICIO', '0.00'),
(440, 1, 'LENOVO Serie: MJ02VA6H- modelo: ThinkCenter M73, COMBO TECLADO MOUSE- MONITOR LED AOC 15.6- GARANTIA 6 MESES', 0, 1, '243.37', '243.37', 'SERVICIO', '0.00'),
(441, 1, 'LENOVO Serie: MJ029TLH- modelo: ThinkCenter M73, COMBO TECLADO MOUSE- MONITOR LED AOC 15.6- GARANTIA 6 MESES', 0, 1, '243.36', '243.36', 'SERVICIO', '0.00'),
(442, 1, 'MIRLENA MARIELA MARTÍNEZ GALICIA', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(443, 1, 'LAURA LISSETH PAIZ MENDOZA', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(444, 1, 'MANTENIMIENTO A EQUIPO DE PREVENTA', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(445, 1, 'INSTALACIÓN Y CONFIGURACIÓN DE SISTEMA   REVISIÓN DE MODULOS', 0, 1, '125.00', '125.00', 'SERVICIO', '0.00'),
(446, 1, 'MANO DE OBRA', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(447, 1, 'INSTALACIÓN Y CONFIGURACIÓN DE SISTEMA DE VÍDEO VIGILANCIA', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(448, 1, 'INSTALACIÓN DE ESTRUCTURA DE RED DE DATOS', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(449, 1, 'FRANCISCO JAVIER  SALMERON PRADO', 0, 1, '23.00', '23.00', 'SERVICIO', '0.00'),
(450, 1, 'ANGELICA KARINA MELGAR LAZO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(451, 1, 'YANIRA YAMILETH MEMBREÑO MEMBREÑO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(452, 1, 'MARIA JOSÉ DIAZ CHICAS', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(453, 1, 'ANGELICA KARINA MELGAR LAZO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(454, 1, 'YANIRA YAMILETH MEMBREÑO MEMBREÑO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(455, 1, 'MARIA JOSÉ DIAZ CHICAS', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(456, 1, 'ANGELICA KARINA MELGAR LAZO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(457, 1, 'YANIRA YAMILETH MEMBREÑO MEMBREÑO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(458, 1, 'MARIA JOSÉ DIAZ CHICAS', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(459, 1, 'ANGELICA KARINA MELGAR LAZO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(460, 1, 'YANIRA YAMILETH MEMBREÑO MEMBREÑO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(461, 1, 'MARIA JOSÉ DIAZ CHICAS', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(462, 1, 'ANGELICA KARINA MELGAR LAZO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(463, 1, 'YANIRA YAMILETH MEMBREÑO MEMBREÑO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(464, 1, 'MARIA JOSÉ DIAZ CHICAS', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(465, 1, 'ANGÉLICA KARINA MELGAR LAZO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(466, 1, 'YANIRA YAMILETH MEMBREÑO MEMBREÑO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(467, 1, 'MARÍA JOSÉ DÍAZ CHICAS', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(468, 1, 'MANO DE OBRA', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(469, 1, '	SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE FEBRERO DE 2001 DE 2021', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(470, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE FEBRERO DE 201', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(471, 1, 'MANO DE OBRA', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(472, 1, 'CONFIGURACIÓN DE UNIDAD DVR', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(473, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN CORRESPONDIENTE A MES DE FEBRERO 2021', 0, 1, '223.01', '223.01', 'SERVICIO', '0.00'),
(474, 1, 'SOPORTE TECNICO A SISTEMA DEL A MES DE FEBRERO 2021', 0, 1, '223.01', '223.01', 'SERVICIO', '0.00'),
(475, 1, 'INSTALACIÓN Y CONFIGURACIÓN DE EQUIPO DE CAJA', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(476, 1, 'ENVIO', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(477, 1, 'ROLLO DE PAPEL TERMICO', 0, 1, '2.25', '2.25', 'SERVICIO', '0.00'),
(478, 1, 'MANO OBRA MOVER CÁMARAS DE SUCURSAL BASE', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(479, 1, 'Mantenimiento al sistema de Gestión de Cobros Municipales. Gestión de mora, cuentas y reportes.', 0, 1, '1150.00', '1150.00', 'SERVICIO', '0.00'),
(480, 1, 'MATERIALES Y MANO DE OBRA SUCURSAL #5', 0, 1, '270.00', '270.00', 'SERVICIO', '0.00'),
(481, 1, 'MATERIALES Y MANO DE OBRA SUCURSAL #4', 0, 1, '350.00', '350.00', 'SERVICIO', '0.00'),
(482, 1, 'MATERIALES Y MANO DE OBRA SUCURSAL #3', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(483, 1, 'MATERIALES Y MANO DE OBRA SUCURSAL #2', 0, 1, '375.00', '375.00', 'SERVICIO', '0.00'),
(484, 1, 'MATERIALES Y MANO DE OBRA SUCURSAL #1', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(485, 1, 'INSTALACIÓN Y MATERIALES DE TERMINAL BIOMETRICO', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00');
INSERT INTO `servicios` (`id_servicio`, `estado`, `descripcion`, `id_sucursal`, `id_categoria`, `costo`, `precio`, `tipo_prod_servicio`, `precio_iva`) VALUES
(486, 1, 'MATERIALES E INSTALACION DE TOMA CORRIENTE EN SALA DE OXIGENO', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(487, 1, 'MATERIALES E INSTALACION DE ESTRUCTURA PARA CABLE EN TAC', 0, 1, '16.00', '16.00', 'SERVICIO', '0.00'),
(488, 1, 'CAJA DE DINERO 3NSTART', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(489, 1, 'CAJA DE DINERO 3NSTART', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(490, 1, 'MATERIALES E INSTALACIÓN PARA TOMA CORRIENTE DE SALA DE OXIGENO', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(491, 1, 'MATERIALES E INSTALACIÓN PARA ESTRUCTURA DE CABLE DE TAC', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(492, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, CORRESPONDIENTE AL MES DE FEBRERO 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(493, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO,  MES DE MARZO DE 2021', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(494, 1, 'MANO DE OBRA', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(495, 1, 'EVALUACIONES POLIGRAFICAS RUTINA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(496, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(497, 1, 'CABLE VNC 18MTS', 0, 1, '13.62', '13.62', 'SERVICIO', '0.00'),
(498, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACIÓN ', 0, 1, '125.00', '125.00', 'SERVICIO', '0.00'),
(499, 1, 'DISCO DURO DE 240G, SSD', 0, 1, '48.67', '48.67', 'SERVICIO', '0.00'),
(500, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE MARZO', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(501, 1, 'DOMICILIO', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(502, 1, 'ROLLO DE PAPEL TERMICO', 0, 1, '2.25', '2.25', 'SERVICIO', '0.00'),
(503, 1, 'COMPUTADORA HP PROBOOK 430 G1, 8GB DE RAM,  120GB DE DISCO DURO, GARANTIA DE 6 MESES', 0, 1, '420.00', '420.00', 'SERVICIO', '0.00'),
(504, 1, 'EQUIPO DE PUNTO DE VENTA COMPLETO', 0, 1, '659.29', '659.29', 'SERVICIO', '0.00'),
(505, 1, 'INSTALACION Y CONFIGURACION DE SISTEMAS DE VIDEO VIGILANCIA', 0, 1, '628.05', '628.05', 'SERVICIO', '0.00'),
(506, 1, 'SISTEMA INFORMATICO DE INVENTARIO Y FACTURACION- OPENPYMES', 0, 1, '1327.43', '1327.43', 'SERVICIO', '0.00'),
(507, 1, 'EQUIPO DELL 7010, 8GB DE RAM, SSD DE 240GB', 0, 1, '206.33', '206.33', 'SERVICIO', '0.00'),
(508, 1, 'EQUIPO LENOVO THINKCENTRE M73', 0, 1, '196.00', '196.00', 'SERVICIO', '0.00'),
(509, 1, 'DISCO DURO 2 TERAS 3.5', 0, 1, '80.30', '80.30', 'SERVICIO', '0.00'),
(510, 1, 'CABLE VNC 18MTS', 0, 1, '10.66', '10.66', 'SERVICIO', '0.00'),
(511, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 5 SALIDAS', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(512, 1, 'HIK - 8CH HD/AHD/ANALOG DVR HD1080P LITE 1 SATA 1 RJ45 100M', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(513, 1, 'INSTALACION DE ESTRUCTURA DE RED DE DATOS', 0, 1, '66.44', '66.44', 'SERVICIO', '0.00'),
(514, 1, 'INSTALACION Y CONFIGURACION DE SISTEMA DE VIDEO VIGILANCIA', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(515, 1, 'SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '721.77', '721.77', 'SERVICIO', '0.00'),
(516, 1, 'INSTALACION Y CONFIGURACION DE SISTEMA DE VIDEO VIGILANCIA', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(517, 1, 'INSTALACION DE ESTRUCTURA DE RED DE DATOS', 0, 1, '66.44', '66.44', 'SERVICIO', '0.00'),
(518, 1, 'BANDEJA DE ACERO EMPOTRABLE EN PARED', 0, 1, '29.20', '29.20', 'SERVICIO', '0.00'),
(519, 1, 'HIK - TURBO 720 CAMARA BALA 2.8MM IR 20M METAL IP66', 0, 1, '18.20', '18.20', 'SERVICIO', '0.00'),
(520, 1, 'HIK - 8CH HD/AHD/ANALOG DVR HD1080P LITE 1 SATA1', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(521, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 5 SALIDAS', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(522, 1, 'CABLE VNC DE 18 MTS', 0, 1, '10.66', '10.66', 'SERVICIO', '0.00'),
(523, 1, 'DISCO DURO 2 TERAS 3.5', 0, 1, '80.30', '80.30', 'SERVICIO', '0.00'),
(524, 1, 'CAJA DE DINERO 3NSTAR', 0, 1, '78.00', '78.00', 'SERVICIO', '0.00'),
(525, 1, 'EQUIPO LEVONO THINKCENTER M73', 0, 1, '196.00', '196.00', 'SERVICIO', '0.00'),
(526, 1, 'LECTOR DE CODIGO DE BARRAS SC100', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(527, 1, 'UPS CDP 500 VA', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(528, 1, 'IMPRESORA DE RECIBOS SEWOO', 0, 1, '141.59', '141.59', 'SERVICIO', '0.00'),
(529, 1, 'MONITOR LED 15.6 AOC NEGRO', 0, 1, '81.42', '81.42', 'SERVICIO', '0.00'),
(530, 1, 'MONITOR LG LED 19.5 HDMI NUEVO', 0, 1, '115.04', '115.04', 'SERVICIO', '0.00'),
(531, 1, 'EQUIPO DELL 7010, 8GB DE RAM, SSD DE 240GB', 0, 1, '206.33', '206.33', 'SERVICIO', '0.00'),
(532, 1, 'COMBO TECLADO Y MOUSE', 0, 1, '5.75', '5.75', 'SERVICIO', '0.00'),
(533, 1, 'SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '721.77', '721.77', 'SERVICIO', '0.00'),
(534, 1, 'SISTEMA DE INVENTARIO Y FACTURACIÓN', 0, 1, '720.00', '720.00', 'SERVICIO', '0.00'),
(535, 1, 'MARCA: LENOVO, SERIE: PB8TMHP, MODELO: ThinkCenter M92p, RAM 8GB, HD 250GB', 0, 1, '185.84', '185.84', 'SERVICIO', '0.00'),
(536, 1, 'MARCA: LENOVO, SERIE: MJXLDLF, MODELO: ThinkCenter M92p, RAM 8GB, HD 250GB', 0, 1, '185.84', '185.84', 'SERVICIO', '0.00'),
(537, 1, 'MARCA: LENOVO, SERIE: MJ10TH1, MODELO: ThinkCenter M92p, RAM 8GB, HD 250GB', 0, 1, '185.84', '185.84', 'SERVICIO', '0.00'),
(538, 1, 'MARCA: LENOVO, SERIE: PB8TNCG, MODELO: ThinkCenter M92p, RAM 8GB, HD 250GB', 0, 1, '185.84', '185.84', 'SERVICIO', '0.00'),
(539, 1, 'EQUIPO DELL OPTIPLEX 790 I5 3.3GHZ 4GB - SSD 960GB', 0, 1, '292.04', '292.04', 'SERVICIO', '0.00'),
(540, 1, 'MONITOR LG LED 19.5 HDMI NUEVO', 0, 1, '115.04', '115.04', 'SERVICIO', '0.00'),
(541, 1, 'COMBO DE TECLADO MOUSE ALAMBRICO', 0, 1, '7.08', '7.08', 'SERVICIO', '0.00'),
(542, 1, 'CPU DELL OPTIPLEX 790 CORE I5 RAM 4GB HD 250GB', 0, 1, '185.84', '185.84', 'SERVICIO', '0.00'),
(543, 1, 'FLETE', 0, 1, '2.50', '2.50', 'SERVICIO', '0.00'),
(544, 1, 'GARANTIA DE 1 AÑO SERIE:8532110050427280', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(545, 1, 'MANTENIMIENTO  PREVENTIVO Y  CORRECTIVO DE IMPRESOR  EPSO', 0, 1, '17.97', '17.97', 'SERVICIO', '0.00'),
(546, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE  ABRI; DEL 2021', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(547, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, CORRESPONDIENTE AL MES DE ABRIL 2021', 0, 1, '125.00', '125.00', 'SERVICIO', '0.00'),
(548, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN CORRESPONDIENTE A MES DE ABRIL 2021', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(549, 1, 'INSTALACION Y CONFIGURACION DE SERVIDOR', 0, 1, '66.38', '66.38', 'SERVICIO', '0.00'),
(550, 1, 'REPAREACION DE 6 PUNTOS DE RED EN EL TERCER NIVEL', 0, 1, '40.39', '40.39', 'SERVICIO', '0.00'),
(551, 1, 'PUNTO DE RED Y MATERIALES EN CONSULTORIO DE EMERGENCIA', 0, 1, '30.40', '30.40', 'SERVICIO', '0.00'),
(552, 1, 'FORZA POWER TECHNOLOGIES FORZA UPS ON LINE 800 WATT 1000 VA 120V', 0, 1, '274.34', '274.34', 'SERVICIO', '0.00'),
(553, 1, 'SSD 240 GB', 0, 1, '48.67', '48.67', 'SERVICIO', '0.00'),
(554, 1, 'DELL OPTIPLEX 7010 SFF QUAD CORE i5 3.4GHz 4GB RAM 500GB HDD WINDOWS 10', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(555, 1, 'COMBO TECLADO MOUSE ALAMBRICO', 0, 1, '10.62', '10.62', 'SERVICIO', '0.00'),
(556, 1, 'DELL OPTIPLEX 7010 SFF QUAD CORE i5 3.4GHz 4GB RAM 500GB HDD WINDOWS 10', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(557, 1, 'MONITOR LG LED 19.5 HDMI NUEVO', 0, 1, '115.04', '115.04', 'SERVICIO', '0.00'),
(558, 1, 'SSD 240 GB', 0, 1, '48.67', '48.67', 'SERVICIO', '0.00'),
(559, 1, 'FORZA POWER TECHNOLOGIES FORZA UPS ON LINE 800 WATT 1000 VA 120V', 0, 1, '274.34', '274.34', 'SERVICIO', '0.00'),
(560, 1, 'PUNTO DE RED Y MATERIALES EN CONSULTORIO DE EMERGENCIA', 0, 1, '30.40', '30.40', 'SERVICIO', '0.00'),
(561, 1, 'REPAREACION DE 6 PUNTOS DE RED EN EL TERCER NIVEL', 0, 1, '40.39', '40.39', 'SERVICIO', '0.00'),
(562, 1, 'INSTALACION Y CONFIGURACION DE SERVIDOR', 0, 1, '66.38', '66.38', 'SERVICIO', '0.00'),
(563, 1, 'SOPORTE A SISTEMA DE INVENTARIO Y FACTURACIÓN ', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(564, 1, 'SOPORTE APLICACIÓN MOVIL DE PEDIDOS', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(565, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION AL MES DE OCTUBRE DE 2020', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(566, 1, 'ENVIO', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(567, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACIÓN', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(568, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACIÓN', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(569, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACIÓN', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(570, 1, '97 	MANO DE OBRA Y MATERIALES DE INSTALACIÓN', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(571, 1, ' MANO DE OBRA ', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(572, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN CORRESPONDIENTE A MES DE MAYO DE 2021', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(573, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE MAYO DEL 2021', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(574, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, CORRESPONDIENTE AL MES DE MAYO 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(575, 1, '	COMBO TECLADO MOUSE ALAMBRICO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(576, 1, '	IMPRESOR MATRICIAL LX-350', 0, 1, '259.73', '259.73', 'SERVICIO', '0.00'),
(577, 1, 'CAJA DE DINERO BEMATECH', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(578, 1, 'EQUIPO SERVIDOR OPTIPLEX 7010, MEMORIA RAM DDR3 8 GB, 1 DISCO ESTADO SOLIDODE 240G,INTEL CORE I5 3.30GHZTECLADO MOUSE', 0, 1, '257.00', '257.00', 'SERVICIO', '0.00'),
(579, 1, ' ROLLOS DE PAPEL TÉRMICO 8mm', 0, 1, '2.40', '2.40', 'SERVICIO', '0.00'),
(580, 1, 'EQUIPO DELL OPTIPLEX 790 I5 3.3GHZ - RAM 4GB- SSD 120GB, MONITOR AOC 15.6, COMBO TECLADO Y MOUSE', 0, 1, '272.00', '272.00', 'SERVICIO', '0.00'),
(581, 1, 'CAJA DE DINERO BEMATECH', 0, 1, '77.88', '77.88', 'SERVICIO', '0.00'),
(582, 1, 'Servicio de Alojamiento de Sistema Informatico, desde Mayo de 2020 hasta Mayo 2021', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(583, 1, 'DELL OPTIPLEX 7010 SFF QUAD CORE I5 3.4GHZ 4GB RAM500GB', 0, 1, '180.00', '180.00', 'SERVICIO', '0.00'),
(584, 1, 'MONITOR LG LED 19.5 HDMI NUEVO', 0, 1, '115.35', '115.35', 'SERVICIO', '0.00'),
(585, 1, 'DELL 790 CORE I5 SERVIDOR USFF RAM 8GB SSD 250 GB', 0, 1, '270.00', '270.00', 'SERVICIO', '0.00'),
(586, 1, 'ROLLOS DE PAPEL TÉRMICO 8MM', 0, 1, '2.40', '2.40', 'SERVICIO', '0.00'),
(587, 1, 'CAJA DE DINERO BEMATECH', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(588, 1, 'EQUIPO SERVIDOR OPTIPLEX 7010, MEMORIA RAM DDR38 GB, 1 DISCO ESTADO SOLIDODE 240G,INTEL CORE I53.30GHZTECLADO MOUSE', 0, 1, '260.00', '260.00', 'SERVICIO', '0.00'),
(589, 1, 'IMPRESOR MATRICIAL LX-350', 0, 1, '260.00', '260.00', 'SERVICIO', '0.00'),
(590, 1, 'LECTOR DE CODIGO DE BARRAS SC10', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(591, 1, 'EQUIPO DELL OPTIPLEX 790 I5 3.3GHZ - RAM 4GB- SSD120GB, MONITOR AOC 15.6, COMBO TECLADO Y MOUS', 0, 1, '275.00', '275.00', 'SERVICIO', '0.00'),
(592, 1, 'FORZA 750 VA 375 WATT', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(593, 1, 'IMPRESOR TERMICO CUSTOM AMERICAM', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(594, 1, 'SISTEMA DE INVENTARIO Y FACTURACION, INCLUYE LICENCIA PARA 1 SUCURSAL', 0, 1, '1769.91', '1769.91', 'SERVICIO', '0.00'),
(595, 1, 'SISTEMA PARA INVENTARIO Y FACTURACION, INCLUYE LICENCIA PARA 1 SUCURSAL', 0, 1, '1061.95', '1061.95', 'SERVICIO', '0.00'),
(596, 1, 'MANO DE OBRA', 0, 1, '155.00', '155.00', 'SERVICIO', '0.00'),
(597, 1, 'MANO DE OBRA ', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(598, 1, 'ROLLO DE PAPEL TERMICO 8MM', 0, 1, '2.20', '2.20', 'SERVICIO', '0.00'),
(599, 1, 'ROLLO DE PAPEL TERMICO 8MM', 0, 1, '2.20', '2.20', 'SERVICIO', '0.00'),
(600, 1, 'MANO DE OBRA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(601, 1, 'CAJA SUPERFICIAL 1 PUERTO BLANCA', 0, 1, '1.44', '1.44', 'SERVICIO', '0.00'),
(602, 1, 'MANO DE OBRA', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(603, 1, 'CANALETA DE PISO DE 2\" ', 0, 1, '16.00', '16.00', 'SERVICIO', '0.00'),
(604, 1, 'NEXXT RJ45 CONNECTOR CAT6 UNIDAD', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(605, 1, 'KEYSTONE CAT6 LINKBASIC', 0, 1, '3.25', '3.25', 'SERVICIO', '0.00'),
(606, 1, 'TELEFONO GRANDSTREAM GXP2140 IP GS POE PHONEFOUR LINES', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(607, 1, 'PATCH CORD UTP CAT6E 2 MTS AZUL', 0, 1, '3.25', '3.25', 'SERVICIO', '0.00'),
(608, 1, 'SWITCH DE 5 PUERTOS', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(609, 1, 'TAPADERA CIEGA PARA CAJA DE REGISTRO', 0, 1, '0.25', '0.25', 'SERVICIO', '0.00'),
(610, 1, 'CANALETA DE 10X15 CON ADHESIVO', 0, 1, '1.75', '1.75', 'SERVICIO', '0.00'),
(611, 1, 'CABLE UTP CAT 5 UNIDAD METRO', 0, 1, '0.27', '0.27', 'SERVICIO', '0.00'),
(612, 1, 'TAPADERA TOMA CORRIENTE DOBLE', 0, 1, '0.26', '0.26', 'SERVICIO', '0.00'),
(613, 1, 'CAJA DE REGISTRO RECTANGULAR EAGLE', 0, 1, '2.18', '2.18', 'SERVICIO', '0.00'),
(614, 1, 'TOMA CORRIENTE DOBLE', 0, 1, '1.36', '1.36', 'SERVICIO', '0.00'),
(615, 1, 'CABLE TNM #14 METRO', 0, 1, '1.23', '1.23', 'SERVICIO', '0.00'),
(616, 1, 'EVALUACIÓN POLIGRAFICA SOCIOECONÓMICA', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(617, 1, 'ERICK ADALBERTO GONZALEZ RAMIREZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(618, 1, 'CABLE HDMI 5MTS', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(619, 1, 'MANO DE OBRA', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(620, 1, 'COMBO DE TECLADO Y MOUSE XTK-301S', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(621, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE A LOS MESES DE MAYO Y JUNIO DE 2021', 0, 1, '132.75', '132.75', 'SERVICIO', '0.00'),
(622, 1, 'ROLLOS DE PAPEL TERMICO 8MM', 0, 1, '1.95', '1.95', 'SERVICIO', '0.00'),
(623, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, CORRESPONDIENTE AL MES DE JUNIO 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(624, 1, '	SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION AL MES DE NOVIEMBRE DE 2020', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(625, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION AL MES DE NOVIEMBRE DE 2020', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(626, 1, 'SOPORTE APLICACIÓN MOVIL DE PEDIDOS', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(627, 1, 'REPARACION DE IMPRESORA ', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(628, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE JUNIO DEL 2021', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(629, 1, 'RENOVACION DE DOMINIO', 0, 1, '30.97', '30.97', 'SERVICIO', '0.00'),
(630, 1, 'TECLADOS', 0, 1, '26.55', '26.55', 'SERVICIO', '0.00'),
(631, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE A LOS MESES DE MARZO, ABRIL, MAYO Y JUNIO DE 2021', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(632, 1, 'ROLLO DE EQITETAS', 0, 1, '0.89', '0.89', 'SERVICIO', '0.00'),
(633, 1, 'ETIQUETADORA DE PRECIOS MEDELO E0S5500', 0, 1, '26.55', '26.55', 'SERVICIO', '0.00'),
(634, 1, 'CONFIGURACION DE PUNTO DE ACCESO', 0, 1, '1.00', '1.00', 'SERVICIO', '0.00'),
(635, 1, 'MANO DE OBRA', 0, 1, '1.00', '1.00', 'SERVICIO', '0.00'),
(636, 1, 'MATERIALES E INSTALACION DE PUNTO DE RED', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(637, 1, 'SISTEMA DE INVENTARIO Y FACTURACION ', 0, 1, '600.00', '600.00', 'SERVICIO', '0.00'),
(638, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN CORRESPONDIENTE A MES DE JUNIO  2021', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(639, 1, 'MANO DE OBRA', 0, 1, '100.37', '100.37', 'SERVICIO', '0.00'),
(640, 1, 'PRIMER DESEMBOLSO DE FIRMA DE CONTRATO PARA EL DESARROLLO DEL SOFTWARE INTEGRADO FUP', 0, 1, '1600.00', '1600.00', 'SERVICIO', '0.00'),
(641, 1, 'Primer desembolso para el desarrollo del software', 0, 1, '1600.00', '1600.00', 'SERVICIO', '0.00'),
(642, 1, 'MATERIALES DE INSTALACION', 0, 1, '551.41', '551.41', 'SERVICIO', '0.00'),
(643, 1, 'SISTEMA PARA RESTAURANTE', 0, 1, '800.00', '800.00', 'SERVICIO', '0.00'),
(644, 1, 'CUMPUTADORA DELL 7010 I5,  8GB DE RAM, 240GB SSD', 0, 1, '235.00', '235.00', 'SERVICIO', '0.00'),
(645, 1, 'UPS ORBITEC DE 750 VA', 0, 1, '40.03', '40.03', 'SERVICIO', '0.00'),
(646, 1, 'COMBO TECLADO MOUSE KLIP XTREME KCK-251S USB', 0, 1, '12.10', '12.10', 'SERVICIO', '0.00'),
(647, 1, 'MONITOR LED 15.6 AOC NEGRO', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(648, 1, 'COMPUTADORA 7010, I5, 8GB DE RAM, SSD 240', 0, 1, '208.00', '208.00', 'SERVICIO', '0.00'),
(649, 1, 'MANO DE OBRA', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(650, 1, 'MANO DE OBRA', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(651, 1, 'MANO DE OBRA', 0, 1, '100.16', '100.16', 'SERVICIO', '0.00'),
(652, 1, 'MANO DE OBRA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(653, 1, 'INSTALACION DE 16 LAMPARAS, 10 EN SALA DE VENTA Y 6 EN LA BODEGA DE LA SUCURSAL #4', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(654, 1, 'INSTALACION DE 4 LAMPARAS, 2 EN SALA DE VENTA Y 2 EN BODEGA,  CAMBIO DE UN TOMACORRIENTE, INSTALACION DE UN FOCO EN EL BAÑO, REPARACIONDE CORTOSIRCUITO EN VITRINA', 0, 1, '100.16', '100.16', 'SERVICIO', '0.00'),
(655, 1, 'INSTALACIOND E UN TOMA CORRIENTE, 4 LAMPARAS, CAMBIO DE UN VENTILADOR', 0, 1, '46.20', '46.20', 'SERVICIO', '0.00'),
(656, 1, 'INSTALACION DE 4 LAMPARAS, 2 EN SALA DE VENTA Y 2 EN BODEGA, CAMBIO DE UN TOMACORRIENTE, INSTALACION DE UN FOCO EN EL BAÑO, REPARACIONDE CORTOSIRCUITO EN VITRINA, CAMBIO DE VENTILADOR', 0, 1, '100.16', '100.16', 'SERVICIO', '0.00'),
(657, 1, 'MATERIALES DE INSTALACION Y PUNTO DE RED, TOMA ELECTRICO Y MANO DE OBRA', 0, 1, '134.00', '134.00', 'SERVICIO', '0.00'),
(658, 1, 'MONITOR DELL 18.5\" E1916HV G6RN7-857-BBDF', 0, 1, '115.05', '115.05', 'SERVICIO', '0.00'),
(659, 1, 'EQUIPO DELL OPTIPLEX 790 I5 3.3GHZ - RAM 4GB - SSD 960GB', 0, 1, '292.04', '292.04', 'SERVICIO', '0.00'),
(660, 1, 'COMBO TECLADO MOUSE ALAMBRICO', 0, 1, '115.04', '115.04', 'SERVICIO', '0.00'),
(661, 1, 'COMBO TECLADO MOUSE ALAMBRICO', 0, 1, '7.08', '7.08', 'SERVICIO', '0.00'),
(662, 1, 'EQUIPO DELL OPTIPLEX 790 I5 3.3GHZ - RAM 4GB - SSD 960GB', 0, 1, '292.04', '292.04', 'SERVICIO', '0.00'),
(663, 1, 'MONITOR LG LED 19.5 HDMI NUEVO', 0, 1, '115.04', '115.04', 'SERVICIO', '0.00'),
(664, 1, 'COMBO TECLADO MOUSE ALAMBRICO', 0, 1, '7.08', '7.08', 'SERVICIO', '0.00'),
(665, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '60.86', '60.86', 'SERVICIO', '0.00'),
(666, 1, 'SISTEMA DE INVENTARIO Y FACTURACIÓN', 0, 1, '500.00', '500.00', 'SERVICIO', '0.00'),
(667, 1, 'PUNTO DE RED Y MATERIALES', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(668, 1, 'EXTENSION ', 0, 1, '7.00', '7.00', 'SERVICIO', '0.00'),
(669, 1, 'SPLITER 5 SALIDAS ', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(670, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '120.86', '120.86', 'SERVICIO', '0.00'),
(671, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(672, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, CORRESPONDIENTE AL MES DE JULIO 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(673, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, CORRESPONDIENTE AL MES DE JULIO 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(674, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, CORRESPONDIENTE AL MES DE JULIO 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(675, 1, 'SOPORTE  PARA SISTEMA DE INVENTARIO Y FACTURACION PARA FARMACIA, CORRESPONDIENTE AL MES DE JULIO 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(676, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE JUNIO DE 2021', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(677, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE DE AL MES DE JUNIO DE 2021', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(678, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE JUNIO DE 2021', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(679, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE JULIO DE 2021', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(680, 1, '	SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE JULIO DE 2021', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(681, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE JULIO DE 2021', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(682, 1, 'PAGO DE PUBLICIDAD FACEBOOK CORRESPONDIENTE AL MES DE JULIO 2021', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(683, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE JULIO DE 2021', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(684, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE JULIO DE 2021', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(685, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL  MES DE JULIO DE 2021', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(686, 1, ' GALOS MOUSE XTM-310  INALAMBRICO COLOR ROJO', 0, 1, '6.00', '6.00', 'SERVICIO', '0.00'),
(687, 1, 'EXTENSION ELECTRICA', 0, 1, '7.00', '7.00', 'SERVICIO', '0.00'),
(688, 1, 'CAJA Y TAPADERA PARA REGISTRO', 0, 1, '3.50', '3.50', 'SERVICIO', '0.00'),
(689, 1, 'PUNTO DE RED Y MATERIALES', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(690, 1, 'ABRAZADERA PARA BAJANTE CANOA CIRCULAR PVC LISA 3 PLG', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(691, 1, 'ANCLA TACO 1/4 X 1-1/2 PLG', 0, 1, '0.05', '0.05', 'SERVICIO', '0.00'),
(692, 1, 'TORNILLO PARED SECA GALVANIZADO 8 X 2 PLG', 0, 1, '0.05', '0.05', 'SERVICIO', '0.00'),
(693, 1, ' TAPADERA FRONTAL 85 MILIMETROS PARA CANALETA105X65MM', 0, 1, '7.50', '7.50', 'SERVICIO', '0.00'),
(694, 1, ' CANALETA DERIVACIONES ELECTRICAS 105X65MMX2M PLASTICO LG SIN TAPA', 0, 1, '22.00', '22.00', 'SERVICIO', '0.00'),
(695, 1, 'UNION PVC LISA 3 PLG', 0, 1, '4.25', '4.25', 'SERVICIO', '0.00'),
(696, 1, 'PUNTO DE RED Y MATERIALES', 0, 1, '8.85', '8.85', 'SERVICIO', '0.00'),
(697, 1, 'CAJA DE REGISTRO RECTANGULAR EAGL', 0, 1, '1.93', '1.93', 'SERVICIO', '0.00'),
(698, 1, 'TAPADERA CIEGA PARA CAJA DE REGISTRO', 0, 1, '0.22', '0.22', 'SERVICIO', '0.00'),
(699, 1, 'EXTENSION ELECTRICA ', 0, 1, '6.19', '6.19', 'SERVICIO', '0.00'),
(700, 1, 'SPLITER 5 SALIDAS', 0, 1, '4.42', '4.42', 'SERVICIO', '0.00'),
(701, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACIO', 0, 1, '106.96', '106.96', 'SERVICIO', '0.00'),
(702, 1, 'SOPORTE APLICACIÓN MOVIL DE PEDIDOS CORRESPONDIENTE AL MES DE JULIO 2021', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(703, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION AL MES DE DICIEMBRE DE 2020', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(704, 1, 'INSTALACION DE 12 ROSETAS, 10 EN SALA DE VENTA Y 2 EN BODEGA', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(705, 1, 'INSTALACION DE 4 ROSETAS EN SALA DE VENTAS Y 1 EN BODEGA', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(706, 1, 'INSTALACION DE CAJA DE REGISTRO PARA TOMA CORRIENTE', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(707, 1, 'INSTALACION DE CANALETA DE PISO', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(708, 1, 'INSTALACION DE VENTILADOR EN SALA DE VENTA', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(709, 1, 'INSTALACION DE CANALETA DE PISO', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(710, 1, 'INSTALACION DE 1 ROSETA EN SALA DE VENTA', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(711, 1, 'REPARACIÓN DE EQUIPO PORTATIL', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(712, 1, 'MANO DE OBRA', 0, 1, '33.67', '33.67', 'SERVICIO', '0.00'),
(713, 1, 'SERVICIO DE SOPORTE PARA SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '304.00', '304.00', 'SERVICIO', '0.00'),
(714, 1, 'REPARACION DE COMPUTADORA EN USULUTAN', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(715, 1, 'INSTALACION EN EL LOCAL #4', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(716, 1, 'INSTALACION EN EL LOCAL #2', 0, 1, '132.85', '132.85', 'SERVICIO', '0.00'),
(717, 1, 'INSTALACION EN EL LOCAL #1', 0, 1, '91.62', '91.62', 'SERVICIO', '0.00'),
(718, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN CORRESPONDIENTE A MES DE JULIO 2021', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(719, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO SAN MIGUEL,  JULIO DE 2021', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(720, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO SAN MIGUEL,  AGOSTO DE 2021', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(721, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO  A LOS MESES DE MAYO Y JUNIO DE 2021', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(722, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO A LOS MESES DE MAYO Y JUNIO DE 2021', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(723, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO SAN MIGUEL, AGOSTO DE 2021', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(724, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO SAN MIGUEL, JULIO DE 2021', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(725, 1, 'BARCODE SCANNER NEWLAND, MODELO NLS-BS80', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(726, 1, 'BARCODE SCANNER NEWLAND, MODELO NLS-BS80', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(727, 1, 'BARCODE SCANNER NEWLAND, MODELO NLS-BS80 GARANTIA DE 1 A', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(728, 1, 'BARCODE SCANNER NEWLAND, MODELO NLS-BS80, GARANTIA DE 1 AÑO ', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(729, 1, 'MICROFONO PARA CAMARA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(730, 1, 'CONFIGURACION DE PLANTA TELEFONICA PBX', 0, 1, '265.49', '265.49', 'SERVICIO', '0.00'),
(731, 1, 'PAD VOYAGER CLASSIC GRAPHIF', 0, 1, '3.00', '3.00', 'SERVICIO', '0.00'),
(732, 1, 'MOUSE XTECH XTM310', 0, 1, '7.00', '7.00', 'SERVICIO', '0.00'),
(733, 1, 'LAPTOP HP PROBOOK 430, RAM 8GB, SSD120GB, GARANTIA DE 6 MESES ', 0, 1, '380.00', '380.00', 'SERVICIO', '0.00'),
(734, 1, 'CAJA DE DINERO 3NSTAR', 0, 1, '70.79', '70.79', 'SERVICIO', '0.00'),
(735, 1, 'IMPRESOR POS 3NSTAR RPT008 DIRECT THERMAL PRINTE', 0, 1, '154.87', '154.87', 'SERVICIO', '0.00'),
(736, 1, 'EQUIPO DELL OPTIPLEX 790 I5 3.3GHZ - RAM 4GB - SSD 240G', 0, 1, '212.39', '212.39', 'SERVICIO', '0.00'),
(737, 1, 'MANO DE OBRA', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(738, 1, 'MANO DE OBRA ', 0, 1, '135.00', '135.00', 'SERVICIO', '0.00'),
(739, 1, 'MANO DE OBRA ', 0, 1, '104.76', '104.76', 'SERVICIO', '0.00'),
(743, 1, 'Xiaomi MI Home Security Camera 360° , Garantia de 1 año.', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(744, 1, 'DAVID RUDY ARANIVA MENJIVAR', 0, 1, '22.00', '22.00', 'SERVICIO', '0.00'),
(745, 1, 'MANTENIMIENTO PREVENTIVO Y REVISION DE FALLAS DE GRABACIÓN DE SISTEMA DE VIDEOVIGILANCIA', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(746, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(747, 1, 'EQUIPO DELL OPTIPLEX 790 I5 3.3GHZ - RAM 4GB - SSD240GB', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(748, 1, 'LECTOR DE CODIGO DE BARRAS SC100', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(749, 1, 'MONITOR DELL E1920H - MONITOR LED - 19', 0, 1, '145.00', '145.00', 'SERVICIO', '0.00'),
(750, 1, 'UPS 600VA 8NEMAS-15R', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(751, 1, 'DVR 32CH HD/CVI/AHD/CVBS DVR 1080P/4MP LITE 2SATA', 0, 1, '450.00', '450.00', 'SERVICIO', '0.00'),
(752, 1, 'MANO DE OBRA', 0, 1, '600.00', '600.00', 'SERVICIO', '0.00'),
(753, 1, 'FUENTE DE PODER CONMUTADA DE 42 A', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(754, 1, 'DELL INSP 5502 15.6FHD I7-1165G7 8GB SSD512GB 2 GB, GARANTIA DE 1 AÑO', 0, 1, '1370.00', '1370.00', 'SERVICIO', '0.00'),
(755, 1, 'DELL INSP 5502 15.6FHD I7-1165G7 8GB SSD512GB 2 GB, GARANTIA DE 1 AÑO', 0, 1, '1240.00', '1240.00', 'SERVICIO', '0.00'),
(756, 1, 'FLETE POR ENVIO', 0, 1, '1.77', '1.77', 'SERVICIO', '0.00'),
(757, 1, 'MATERIALES DE INSTALACIONES Y MANO DE OBRA', 0, 1, '225.00', '225.00', 'SERVICIO', '0.00'),
(758, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACIÓN', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(759, 1, 'CABLE VNC 18MTS', 0, 1, '12.03', '12.03', 'SERVICIO', '0.00'),
(760, 1, 'CABLE HDMI 15 MTRS', 0, 1, '22.13', '22.13', 'SERVICIO', '0.00'),
(761, 1, 'BANDEJA DE ACERO EMPOTRABLE EN PARED', 0, 1, '31.13', '31.13', 'SERVICIO', '0.00'),
(762, 1, 'SERIE', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(763, 1, 'MJ017BGW', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(764, 1, 'MJ017BNN', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(765, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE AGOSTO DE 2021', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(766, 1, 'PAGO DE PUBLICIDAD FACEBOOK CORRESPONDIENTE AL MES DE AGOSTO 2021', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(767, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE AGOSTO DE 2021', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(768, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE AGOSTO DE 2021', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(769, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE AGOSTO DE 2021', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(770, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, CORRESPONDIENTE AL MES DE AGOSTO 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(771, 1, '	SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA,  AL MES DE AGOSTO 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(772, 1, 'MANO DE OBRA ', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(773, 1, 'SOPORTE APLICACIÓN MOVIL DE PEDIDOS CORRESPONDIENTE AL MES DE AGOSTO 2021', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(774, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES ENERO DEL 2021', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(775, 1, '	SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES AGOSTO 2021', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(776, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO A LOS MESES DE JULIODE 2021', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(777, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO A LOS MESES DE AGOSTO 2021', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(778, 1, 'HIK - 8CH HD/AHD/ANALOG DVR HD1080P LITE 1 SATA 1RJ45 100M', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(779, 1, 'DISCO DURO 3.5 1T', 0, 1, '61.00', '61.00', 'SERVICIO', '0.00'),
(780, 1, 'HIK - TURBO 1080P CAMARA TURRET 2.8MM IR 20M METAL PLASTICO IP66', 0, 1, '23.01', '23.01', 'SERVICIO', '0.00'),
(781, 1, 'CANALETA DE 15 X 10 CON ADHESIVO', 0, 1, '2.65', '2.65', 'SERVICIO', '0.00'),
(782, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 5 SALIDA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(783, 1, 'UPS 600VA ORBITEC', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(784, 1, 'MARCOS JAVIER ARIAS ESCOBAR', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(785, 1, 'FABRICIO BENJAMIN ORTIZ MINERO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(786, 1, 'CARLOS ISAAC PORTILLO FRANCI', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(787, 1, 'FABRICIO BENJAMIN ORTIZ MINERO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(788, 1, 'CARLOS ISAAC PORTILLO FRANCO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(789, 1, 'MARCOS JAVIER ARIAS ESCOBAR', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(790, 1, 'ESTEFANY MARISOL PAZ RODRIGUEZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(791, 1, 'FABRICIO BENJAMIN ORTIZ MINERO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(792, 1, 'CARLOS ISAAC PORTILLO FRANCO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(793, 1, 'MARCOS JAVIER ARIAS ESCOBAR', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(794, 1, 'ESTEFANY MARISOL PAZ RODRIGUEZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(795, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '225.00', '225.00', 'SERVICIO', '0.00'),
(796, 1, 'INSTALACION DE KIT DE 8 CAMARA   MATERIALES DE INSTALACION   MANO DE OBRA', 0, 1, '911.46', '911.46', 'SERVICIO', '0.00'),
(797, 1, '	INSTALACION DE KIT DE 8 CAMARA, MATERIALES DE INSTALACION, MANO DE OBRA.', 0, 1, '911.46', '911.46', 'SERVICIO', '0.00'),
(798, 1, 'OMAR ALEXANDER CANALES ALVAREZ', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(799, 1, 'DEYSI CAROLINA PARADA RODRIGUEZ ', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(800, 1, 'RENE ISAU LEON CONTRERAS', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(801, 1, 'AMELIA BEATRIZ VEGA ARIAS', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(802, 1, 'BLANCA LETICIA MARTINEZ MARTINEZ', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(803, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '8.85', '8.85', 'SERVICIO', '0.00'),
(804, 1, 'KIT DE 4 CAMARAS HIKVISION 1080P   DVR 4CH HD/AHD/ANALOG DVR 1080P  1 DISCO DURO DE 1T', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(805, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(806, 1, 'MOUSE INALAMBRICO KLIXTREME', 0, 1, '8.84', '8.84', 'SERVICIO', '0.00'),
(807, 1, 'MEMORIA MICRO SD 32GB', 0, 1, '8.84', '8.84', 'SERVICIO', '0.00'),
(808, 1, 'DISCO DURO INTERNO SATA  3 TERAS', 0, 1, '114.00', '114.00', 'SERVICIO', '0.00'),
(809, 1, 'HIKVISION DVR 16 CANLES  STANDALONE DV', 0, 1, '123.89', '123.89', 'SERVICIO', '0.00'),
(810, 1, 'FUENTE DE PODER CONMUTADA 18 CHANNEL 120WAT', 0, 1, '56.39', '56.39', 'SERVICIO', '0.00'),
(811, 1, 'UPS ORBITEC 750VA', 0, 1, '48.67', '48.67', 'SERVICIO', '0.00'),
(812, 1, 'CAMARAS DE VIDEOVIGILANCIA EWTTO Y HIKVISION', 0, 1, '630.08', '630.08', 'SERVICIO', '0.00'),
(813, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA ', 0, 1, '880.89', '880.89', 'SERVICIO', '0.00'),
(814, 1, 'CAMARA VIDEOVIGILANCIA HIKVISION 720P', 0, 1, '26.00', '26.00', 'SERVICIO', '0.00'),
(815, 1, 'CAMARA BALA TURBO 1080P HIKVISION 2.8MM IR 20M PLASTICOIP66', 0, 1, '24.77', '24.77', 'SERVICIO', '0.00'),
(816, 1, 'CAMARA VIDEOVIGILANCIA HIKVISION 1080 2MP', 0, 1, '28.32', '28.32', 'SERVICIO', '0.00'),
(817, 1, 'CAMARAS DE VIDEOVIGILANCIA  EWTTO', 0, 1, '48.67', '48.67', 'SERVICIO', '0.00'),
(818, 1, 'MOUSE INALAMBRICO KLIXTREME', 0, 1, '8.84', '8.84', 'SERVICIO', '0.00'),
(819, 1, 'MEMORIA MICRO SD 32GB', 0, 1, '8.84', '8.84', 'SERVICIO', '0.00'),
(820, 1, 'DISCO DURO INTERNO SATA 3 TERAS', 0, 1, '114.00', '114.00', 'SERVICIO', '0.00'),
(821, 1, 'HIKVISION DVR 16 CANLES STANDALONE DV', 0, 1, '123.89', '123.89', 'SERVICIO', '0.00'),
(822, 1, 'FUENTE DE PODER CONMUTADA 18 CHANNEL 120WAT', 0, 1, '56.39', '56.39', 'SERVICIO', '0.00'),
(823, 1, 'UPS ORBITEC 750VA', 0, 1, '48.67', '48.67', 'SERVICIO', '0.00'),
(824, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '880.89', '880.89', 'SERVICIO', '0.00'),
(825, 1, 'MONITOR LCD DELL 19.5 E2016HV VGA 1600x900 BLACK 3Y', 0, 1, '115.04', '115.04', 'SERVICIO', '0.00'),
(826, 1, 'KIT DE 4 CAMARAS HIKVISION 720P   DVR 4CHHD/AHD/ANALOG DVR 720P 1 DISCO DURO DE 1', 0, 1, '245.00', '245.00', 'SERVICIO', '0.00'),
(827, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(828, 1, 'KIT DE 4 CAMARAS HIKVISION, 720P DVR 4CHHD/AHD/ANALOG,1 DISCO DURO DE 1', 0, 1, '245.00', '245.00', 'SERVICIO', '0.00'),
(829, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(830, 1, 'KIT DE 4 CAMARAS HIKVISION, 1080P DVR 4CHHD/AHD/ANALOG,1 DISCO DURO DE 1', 0, 1, '265.00', '265.00', 'SERVICIO', '0.00'),
(831, 1, 'KIT DE 4 CAMARAS HIKVISION, 720P DVR 4CHHD/AHD/ANALOG,1 DISCO DURO DE 1, CABLES VNC DE 18MTS', 0, 1, '265.00', '265.00', 'SERVICIO', '0.00'),
(832, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '133.75', '133.75', 'SERVICIO', '0.00'),
(833, 1, 'CAMARA TURBO BALA 1080P, LENTE FIJO, IP66, IR 20MTS, HIKVISION', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(834, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '275.00', '275.00', 'SERVICIO', '0.00'),
(835, 1, 'Sistema de control de recursos humanos', 0, 1, '1150.45', '1150.45', 'SERVICIO', '0.00'),
(836, 1, 'Reloj biometricos', 0, 1, '265.49', '265.49', 'SERVICIO', '0.00'),
(837, 1, 'EQUIPO COMPLETO DELL OPTIPLEX 3010 SFF, MONITOR DELL 18.5, TECLADO Y MOUSE, GARANTIA DE 6 MESES', 0, 1, '360.00', '360.00', 'SERVICIO', '0.00'),
(838, 1, 'FLETE POR ENVIO', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(839, 1, 'COMBO MOUSE OPTICAL  KLIPXTREME Y AUDIFONOS KLIPXTEME', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(840, 1, 'UPS ORBITEC 600VA', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(841, 1, 'EQUIPO DELL OPTIPLEX 3010 SFF, SERIE: 8VLR6Y1; GARANTIA 1 AÑO', 0, 1, '260.00', '260.00', 'SERVICIO', '0.00'),
(842, 1, 'MATERIALES DE INSTALACION', 0, 1, '62.00', '62.00', 'SERVICIO', '0.00'),
(843, 1, 'MATERIALES DE INSTALACION', 0, 1, '64.00', '64.00', 'SERVICIO', '0.00'),
(844, 1, 'MANO DE OBRA', 0, 1, '125.00', '125.00', 'SERVICIO', '0.00'),
(845, 1, 'MATERIALES DE INSTALACION ', 0, 1, '64.00', '64.00', 'SERVICIO', '0.00'),
(846, 1, 'HUMBERTO JEREMIAS MARTINEZ CRUZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(847, 1, 'JUDITHZA AVICELY RAMOS IRAHETA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(848, 1, 'ESAU ORTIZ ORTIZ ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(849, 1, 'ROLLOS DE PAPEL 57MM', 0, 1, '1.59', '1.59', 'SERVICIO', '0.00'),
(850, 1, 'IMPRESOR PORTATIL ', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(851, 1, '3 CAMARAS XIAOMI MI HOME SECURITY, incluye instalacion', 0, 1, '150.44', '150.44', 'SERVICIO', '0.00'),
(852, 1, 'IMPRESORA DE TICKET 3NSTART', 0, 1, '154.87', '154.87', 'SERVICIO', '0.00'),
(853, 1, 'CAJA 3NSART CD350', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(854, 1, 'MONITOR DELL 18.5', 0, 1, '123.89', '123.89', 'SERVICIO', '0.00'),
(855, 1, 'LECTOR DE CÓDIGO DE BARRAS 3NSTART CS100', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(856, 1, 'EQUIPO PREVENTA LENOVO M93P, Memoria RAM DDR3, 4GB1 Disco de estado solido de 120GB,Garantía de 6 Meses', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(857, 1, 'UPS FORZA 750 VA', 0, 1, '48.67', '48.67', 'SERVICIO', '0.00'),
(858, 1, 'PAGO MITAD IMPRESOR DIRECT THERMAL SHIPPING LABEL PRINTER ', 0, 1, '154.86', '154.86', 'SERVICIO', '0.00'),
(859, 1, 'CAJA DE PAPEL TERMICO 50 UNIDADES', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(860, 1, 'EQUIPO DELL OPTIPLEX 3010 SFF DESKTOP', 0, 1, '230.08', '230.08', 'SERVICIO', '0.00'),
(861, 1, 'IMPRESOR MATRICIAL EPSON LX-350', 0, 1, '230.08', '230.08', 'SERVICIO', '0.00'),
(862, 1, 'UPS FORZA HT-1000LCD', 0, 1, '108.84', '108.84', 'SERVICIO', '0.00'),
(863, 1, 'EQUIPOS DELL  SERVIDOR 3010 CORE I5', 0, 1, '287.61', '287.61', 'SERVICIO', '0.00'),
(864, 1, 'LICENCIA DE SISTEMA POR UNA SUCURSAL SUCURSAL ', 0, 1, '353.98', '353.98', 'SERVICIO', '0.00'),
(865, 1, 'INSTALACION Y CONFIGURACION DE DISPOSITIVO  DE RED', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(866, 1, 'LLAVIN PARA GABINETE ', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(867, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE SEPTIEMBRE DE 2021', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(868, 1, 'PAGO DE PUBLICIDAD FACEBOOK CORRESPONDIENTE AL MES DE SEPTIEMBRE 2021', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(869, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE SEPTIEMBRE DE 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(870, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE SEPTIEMBRE DE 2021', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(871, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE SEPTIEMBRE DE 2021', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(872, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES FEBRERO DEL 2021', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(873, 1, ' EQUIPO DELL OPTIPLEX 3010 SFF DESKTOP', 0, 1, '230.09', '230.09', 'SERVICIO', '0.00'),
(874, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE SEPTIEMBRE 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(875, 1, 'envio por flete', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(876, 1, '7 METROS DE CABLE PARA RED', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(877, 1, 'SWITCH DE 5 PUERTOS D-LINKE', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(878, 1, 'CAJA DE PAPEL TERMICO 8MM 50 UNIDADES', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(879, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '298.93', '298.93', 'SERVICIO', '0.00'),
(880, 1, 'MOUSE INALAMBRICO GALO', 0, 1, '6.19', '6.19', 'SERVICIO', '0.00'),
(881, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '208.49', '208.49', 'SERVICIO', '0.00'),
(882, 1, 'FLETE POR ENVIO', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(883, 1, '3 CAMARAS XIAOMI MI HOME SECURITY, incluye instalacion', 0, 1, '150.44', '150.44', 'SERVICIO', '0.00'),
(884, 1, 'IMPRESORA DE TICKET 3NSTART', 0, 1, '154.87', '154.87', 'SERVICIO', '0.00'),
(885, 1, 'CAJA DE EFECTIVO 3NSART CD350', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(886, 1, 'MONITOR DELL 18.5', 0, 1, '123.89', '123.89', 'SERVICIO', '0.00'),
(887, 1, 'LECTOR DE CÓDIGO DE BARRAS 3NSTART CS100', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(888, 1, 'EQUIPO PREVENTA LENOVO M93P, Memoria RAM DDR3, 4GB1 Disco de estado solido de 120GB,Garantía de 6 Meses', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(889, 1, 'UPS FORZA 750 VA', 0, 1, '48.67', '48.67', 'SERVICIO', '0.00'),
(890, 1, 'flete por envio', 0, 1, '3.00', '3.00', 'SERVICIO', '0.00'),
(891, 1, 'CAJA DE PAPEL TERMICO 8MM 50 UNIDADES', 0, 1, '97.00', '97.00', 'SERVICIO', '0.00'),
(892, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE SEPTIEMBRE2021', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(893, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES SEPTIEMBRE 2021', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(894, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACIÓN', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(895, 1, 'HIK - 8CH HD/AHD/ANALOG DVR HD1080P LITE 1 SATA 1RJ45 100M', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(896, 1, 'CABLE VNC 18MTS', 0, 1, '12.05', '12.05', 'SERVICIO', '0.00'),
(897, 1, 'CAMARA 1080 2MP', 0, 1, '28.32', '28.32', 'SERVICIO', '0.00'),
(898, 1, 'DISCO DURO SATA INTERNO 3.5', 0, 1, '50.27', '50.27', 'SERVICIO', '0.00'),
(899, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 5 SALIDA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(900, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACIÓN', 0, 1, '50.85', '50.85', 'SERVICIO', '0.00'),
(901, 1, 'HIK - 8CH HD/AHD/ANALOG DVR HD1080P LITE 1 SATA 1RJ45 100M', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(902, 1, 'CABLE VNC 18MTS', 0, 1, '12.05', '12.05', 'SERVICIO', '0.00'),
(903, 1, 'CAMARA 1080 2MP', 0, 1, '28.32', '28.32', 'SERVICIO', '0.00'),
(904, 1, 'DISCO DURO SATA INTERNO 3.5', 0, 1, '50.27', '50.27', 'SERVICIO', '0.00'),
(905, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 5 SALIDA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(906, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(907, 1, 'CAJA DE DINERO 3NSTAR', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(908, 1, 'ROLLO DE PAPEL TERMICO 80MM', 0, 1, '1.99', '1.99', 'SERVICIO', '0.00'),
(909, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '130.00', '130.00', 'SERVICIO', '0.00'),
(910, 1, 'SEGUNDO PAGO, CORRESPONDIENTE AL TREINTA POR CIENTO, DEL VALOR TOTAL DEL SERVICIO, EN VALIDACION DE LOS MODULOS : I,II Y II', 0, 1, '4800.00', '4800.00', 'SERVICIO', '0.00'),
(911, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(912, 1, 'MATERIALES DE INSTALACION MANO DE OBRA', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(913, 1, 'Kingston Canvas Select Plus - Tarjeta de memoria flash (adaptador microSDXC a SD Incluido) - 64 GB ', 0, 1, '12.00', '12.00', 'SERVICIO', '0.00'),
(914, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(915, 1, 'IMPRESOR MATRICIAL LX-350', 0, 1, '320.00', '320.00', 'SERVICIO', '0.00'),
(916, 1, 'MANO DE OBRA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(917, 1, 'MANO DE OBRA', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(918, 1, 'MANO DE OBRA', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(919, 1, 'MATERIALES DE INSTALACION ', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(920, 1, 'MANO DE OBRA', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(921, 1, 'LUIS ARMANDO CHAVEZ CABALLERO', 0, 1, '23.00', '23.00', 'SERVICIO', '0.00'),
(922, 1, 'MANTENIMIETO PREVENTO DE COMPUTADORA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(923, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(924, 1, 'OSIRIS BRISEIDA ROMERO RODRIGUEZ', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(925, 1, 'ROSA GLADIS RODRIGUEZ NAVARRETE', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(926, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '275.54', '275.54', 'SERVICIO', '0.00'),
(927, 1, '	MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '274.54', '274.54', 'SERVICIO', '0.00'),
(928, 1, 'EQUIPO LENOVO TINKCENTRE M92P TINY INTEL CORE I5, GARANTIA DE 6 MESES', 0, 1, '154.87', '154.87', 'SERVICIO', '0.00'),
(929, 1, 'EQUIPO LENOVO TINKCENTRE M92P TINY INTEL CORE I5, GARANTIA DE 6 MESES', 0, 1, '154.87', '154.87', 'SERVICIO', '0.00'),
(930, 1, 'MANO DE OBRA', 0, 1, '101.22', '101.22', 'SERVICIO', '0.00'),
(931, 1, 'CAJA DE PAPEL TERMICO', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(932, 1, 'CAJA DE PAPEL TERMICO 80X70MM 50 UNDIDADES', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(933, 1, 'BATERIA 3V PARA DVR HKVISION', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(934, 1, 'MANO DE OBRA', 0, 1, '1.00', '1.00', 'SERVICIO', '0.00'),
(935, 1, 'MANO DE OBRA', 0, 1, '1.00', '1.00', 'SERVICIO', '0.00'),
(936, 1, 'DVR HIKVISION 5MP HD 4 CANALES', 0, 1, '130.00', '130.00', 'SERVICIO', '0.00'),
(937, 1, 'CAMARA 1080 5MP EXTERIORES', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(938, 1, 'MANO DE OBRA', 0, 1, '104.55', '104.55', 'SERVICIO', '0.00'),
(939, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION ', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(940, 1, 'TOMA HEMBRA REGLETA MULTIUSO', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(941, 1, 'MANO DE OBRA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(942, 1, 'TARJETA WIFI LYNX 301 ADAPTADOR', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(943, 1, 'PAD VOYAGER MOUSE', 0, 1, '3.00', '3.00', 'SERVICIO', '0.00'),
(944, 1, 'XTC 308 VGA', 0, 1, '4.00', '4.00', 'SERVICIO', '0.00'),
(945, 1, 'XTM 195 MOUSE ALAMBRICO', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(946, 1, 'TECLADO STYLUS', 0, 1, '8.00', '8.00', 'SERVICIO', '0.00'),
(947, 1, 'EQUIPO DELL OPTIPLEX 3010 SFF DESKTOP GARANTIA DE 6 MESES', 0, 1, '210.00', '210.00', 'SERVICIO', '0.00'),
(948, 1, 'ADAPTADOR WIFI LYNX 301', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(949, 1, 'PAD VOYAGER MOUSE', 0, 1, '3.00', '3.00', 'SERVICIO', '0.00'),
(950, 1, 'XTC 308 VGA', 0, 1, '4.00', '4.00', 'SERVICIO', '0.00'),
(951, 1, 'XTM 195 MOUSE', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(952, 1, 'TECLADO STYLUS ', 0, 1, '8.00', '8.00', 'SERVICIO', '0.00'),
(953, 1, 'EQUIPO DELL OPTIPLEX 3010 SFF, SERIE: 268M6Y1, GARANTIA DE 6 MESES', 0, 1, '210.00', '210.00', 'SERVICIO', '0.00'),
(954, 1, 'CAMARA XIAOMI MAS INTALACION Y MATERIALES', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(955, 1, 'CAJA DE EFECTIVO 3NSTAR', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(956, 1, 'CAJA DE EFECTIVO 3NSTAR', 0, 1, '78.00', '78.00', 'SERVICIO', '0.00'),
(957, 1, 'UPS ORBITEC 750VA', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(958, 1, 'ROLLOS DE PAPEL TERMICO 80MM', 0, 1, '1.90', '1.90', 'SERVICIO', '0.00'),
(959, 1, 'ROLLOS DE PAPEL TERMICO 80MM', 0, 1, '1.90', '1.90', 'SERVICIO', '0.00'),
(960, 1, 'MONITOR DELL 19.5', 0, 1, '130.00', '130.00', 'SERVICIO', '0.00'),
(961, 1, 'BOCINA SPEAKER IKONI', 0, 1, '8.50', '8.50', 'SERVICIO', '0.00'),
(962, 1, 'CAJA DE PAPEL TERMICO 80MM', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(963, 1, 'CAJA DE PAPEL TERMICO 80MM', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(964, 1, 'IMPRESORA TERMICA BEMATECH ', 0, 1, '175.00', '175.00', 'SERVICIO', '0.00'),
(965, 1, 'ROLLOS DE PAPEL TERMICO 57MM', 0, 1, '0.75', '0.75', 'SERVICIO', '0.00'),
(966, 1, 'IMPRESORA TERMICA PORTATIL', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(967, 1, 'UPS ORBITEC  750VA', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(968, 1, 'XTECH TECLADO XTK 160S', 0, 1, '8.00', '8.00', 'SERVICIO', '0.00'),
(969, 1, 'MONITOR DELL P2012, GARANTIA DE 6 MESES', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(970, 1, 'XTECH TECLADO XTK160S', 0, 1, '8.00', '8.00', 'SERVICIO', '0.00'),
(971, 1, 'MONITOR DELL P2012', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(972, 1, 'UPS ORBITEC 750VA', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(973, 1, 'CAMARA IP EWTTO ETM5396', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(974, 1, 'SWITCH DE 5 PUERTOS', 0, 1, '11.00', '11.00', 'SERVICIO', '0.00'),
(975, 1, 'ADAPTADOR WIRLESS NEXXT', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(976, 1, 'DISCO DURO 3.5 1T', 0, 1, '61.00', '61.00', 'SERVICIO', '0.00'),
(977, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(978, 1, 'MANO DE OBRA', 0, 1, '101.75', '101.75', 'SERVICIO', '0.00'),
(979, 1, 'MONITOR DE 19.5 PULGADAS', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(980, 1, ' SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO A LOS MESES DE  SEPTIEMBRE Y OCTUBRE 2021', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(981, 1, 'MONITOR DELL 19.5', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(982, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE OCTUBRE DE 2021', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(983, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE OCTUBRE DE 2021', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(984, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE OCTUBRE 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(985, 1, 'PAGO DE PUBLICIDAD FACEBOOK CORRESPONDIENTE AL MES DE OCTUBRE 2021', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(986, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE OCTUBRE DE 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(987, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE OCTUBRE DE 2021', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(988, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES MARZO DEL 2021', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(989, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES OCTUBRE DEL 2021', 0, 1, '323.01', '323.01', 'SERVICIO', '0.00'),
(990, 1, '', 0, 1, '74.00', '74.00', 'SERVICIO', '0.00');
INSERT INTO `servicios` (`id_servicio`, `estado`, `descripcion`, `id_sucursal`, `id_categoria`, `costo`, `precio`, `tipo_prod_servicio`, `precio_iva`) VALUES
(991, 1, 'SERVICIO DE SISTEMA PARA INVENTARIO Y FACTURACIÓN', 0, 1, '1200.00', '1200.00', 'SERVICIO', '0.00'),
(992, 1, 'ThinkCentre Tiny Desktop M93p(1 SSD 240 GB, Teclado, Mouse, Cable de poder)', 0, 1, '265.49', '265.49', 'SERVICIO', '0.00'),
(993, 1, 'MONITOR DELL 18.5', 0, 1, '128.32', '128.32', 'SERVICIO', '0.00'),
(994, 1, 'UPS ORBITEC 600VA', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(995, 1, 'MANO DE OBRA', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(996, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES OCTUBRE 2021', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(997, 1, 'CAMARA 1080 2MP', 0, 1, '24.95', '24.95', 'SERVICIO', '0.00'),
(998, 1, 'MANO DE OBRA', 0, 1, '281.25', '281.25', 'SERVICIO', '0.00'),
(999, 1, 'MANO DE OBRA', 0, 1, '400.00', '400.00', 'SERVICIO', '0.00'),
(1000, 1, 'BANDEJA PARA GABINETE DE ACERO', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(1001, 1, 'SWITCH 16 PUERTOS 10/100/1000 MBPS NOADMINISTRABLE PARA MONTAJE EN RAC', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1002, 1, 'GABINETE 15U NEXXT', 0, 1, '207.96', '207.96', 'SERVICIO', '0.00'),
(1003, 1, 'ORDENADOR DE CABLE UNA UNIDAD', 0, 1, '26.55', '26.55', 'SERVICIO', '0.00'),
(1004, 1, 'BOBINA CABLE LINKEDPRO PRO-CAT-6-PLUS/500 FTAZUL INTERIOR', 0, 1, '84.50', '84.50', 'SERVICIO', '0.00'),
(1005, 1, 'PATCH CORD UTP CAT6E 3 PIES AZU', 0, 1, '2.25', '2.25', 'SERVICIO', '0.00'),
(1006, 1, 'KEYSTONE CAT6 LINKBASIC', 0, 1, '2.88', '2.88', 'SERVICIO', '0.00'),
(1007, 1, 'TAPADERA CONECTOR DOBLE RJ-45', 0, 1, '1.00', '1.00', 'SERVICIO', '0.00'),
(1008, 1, 'NEXXT  CAJA MONTAJE SUPERFICIE DE RED  BLANCO', 0, 1, '2.21', '2.21', 'SERVICIO', '0.00'),
(1009, 1, 'UPS FORZA HT SERIES HT-1000LCD 500 VATIO', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(1010, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACIÓN', 0, 1, '600.00', '600.00', 'SERVICIO', '0.00'),
(1011, 1, 'PATCH PANEL 24 PUERTOS CAT6', 0, 1, '48.67', '48.67', 'SERVICIO', '0.00'),
(1012, 1, 'CAJA SUPERFICIAL 1 PUERTO BLANC', 0, 1, '1.44', '1.44', 'SERVICIO', '0.00'),
(1013, 1, 'CABLE UTP CAT6 UNIDAD METRO', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(1014, 1, 'CENTRAL TELEFONICA IP  1 PUERTO FXO, 1 PUERTOFXS, 500 USUARIOS SIP, 75 LLAMADAS CONCURRENTES,12 PARTICIPANTES DE VIDEO CONFERENCIA', 0, 1, '309.73', '309.73', 'SERVICIO', '0.00'),
(1015, 1, 'TELEFONO IP  MODELO GXP1615 DOBLE PUERTOETHERNET 10/100 1 CUENTA', 0, 1, '48.67', '48.67', 'SERVICIO', '0.00'),
(1016, 1, 'SWITCH 16 PUERTOS 10/100/1000 MBPS NOADMINISTRABLE PARA MONTAJE EN RAC', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1017, 1, 'NVR MINI 8 POE U1 DE 8 CANALES', 0, 1, '128.32', '128.32', 'SERVICIO', '0.00'),
(1018, 1, 'CAMARAS IP DE VIDEOVIGILANCIA DOMO EXT/INT', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1019, 1, 'TELEFONO GRANDSTREAM GXP2160 DOBLE PUERTO,10/100/1000, 6 CUENTAS', 0, 1, '135.00', '135.00', 'SERVICIO', '0.00'),
(1020, 1, 'PATCH CORD UTP CAT6E 2 MTS AZUL', 0, 1, '3.25', '3.25', 'SERVICIO', '0.00'),
(1021, 1, 'HD SEAGATE 6TB INT. ST6000VX0023 3.5 SHYHAWK 7200RPM SATA 256MB', 0, 1, '223.15', '223.15', 'SERVICIO', '0.00'),
(1022, 1, 'EVALUACIONES POLIGRAFICAS PREEMPLEO PARA AUXILIAR DE PISTA', 0, 1, '23.00', '23.00', 'SERVICIO', '0.00'),
(1023, 1, 'EVALUACIONES POLIGRAFICAS PREEMPLEO PARA TECNICO INSTALADOR', 0, 1, '23.00', '23.00', 'SERVICIO', '0.00'),
(1024, 1, '	EQUIPO LENOVO TINKCENTRE M92P TINY INTEL CORE I5 HD 250 RAM 4GB,SSD 120GB', 0, 1, '220.00', '220.00', 'SERVICIO', '0.00'),
(1025, 1, 'CAMARA XIAOMI 2K', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(1026, 1, 'JOSE ESAU ORTIZ ORTIZ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1027, 1, 'YANIRA DEL CARMEN ORTIZ CAÑENGUEZ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1028, 1, 'CRISTIAM ROXANA APARICIO DE PERDOMO ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1029, 1, 'MANO DE OBRA', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(1030, 1, 'MANO DE OBRA DE INSTALACION DE CAMARAS', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(1031, 1, 'MANO DE OBRA', 0, 1, '400.00', '400.00', 'SERVICIO', '0.00'),
(1032, 1, 'CRISTIAN RICARDO CARDOZA', 0, 1, '22.00', '22.00', 'SERVICIO', '0.00'),
(1033, 1, 'HERBERT ISTMAR HENRIQUEZ HERRERA', 0, 1, '22.00', '22.00', 'SERVICIO', '0.00'),
(1034, 1, 'EDWIN IVAN CUADRA MONDRAGON', 0, 1, '22.00', '22.00', 'SERVICIO', '0.00'),
(1035, 1, 'FRANCISCO ARNOLDO LOPEZ ANDRADE', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(1036, 1, 'RUDY ALEXIS HERNANDEZ GARCIA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(1037, 1, 'MANO DE OBRA', 0, 1, '148.58', '148.58', 'SERVICIO', '0.00'),
(1038, 1, 'MANO DE OBRA', 0, 1, '203.00', '203.00', 'SERVICIO', '0.00'),
(1039, 1, 'LICENCIA INICIAL DE SISTEMA DE RESTAURANTE', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(1040, 1, 'MATERIALES E INSTALACION DE PUNTO DE RED Y TOMA CORRIENTE', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1041, 1, 'MATERIALES DE INSTALACION DE MANO DE OBRA', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1042, 1, 'LICENCIA INICIAL PARA UNA SUCURSAL', 0, 1, '500.00', '500.00', 'SERVICIO', '0.00'),
(1043, 1, 'MANO DE OBRA', 0, 1, '125.00', '125.00', 'SERVICIO', '0.00'),
(1044, 1, 'Fuentes de Poder de 12V 2 AMP', 0, 1, '22.00', '22.00', 'SERVICIO', '0.00'),
(1045, 1, 'Bobina de cable intemperie doble chaqueta', 0, 1, '185.00', '185.00', 'SERVICIO', '0.00'),
(1046, 1, 'Tapadera para caja rj-45 1 puerto', 0, 1, '2.50', '2.50', 'SERVICIO', '0.00'),
(1047, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION ', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(1048, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(1049, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '214.00', '214.00', 'SERVICIO', '0.00'),
(1050, 1, 'MANO DE OBRA', 0, 1, '13.28', '13.28', 'SERVICIO', '0.00'),
(1051, 1, 'BOCINA MASTERTECH', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1052, 1, 'IMPRESORA TERMICA BEMATECH', 0, 1, '185.00', '185.00', 'SERVICIO', '0.00'),
(1053, 1, 'IMPRESORA DE TICKET BEMATECH', 0, 1, '163.72', '163.72', 'SERVICIO', '0.00'),
(1054, 1, 'CAJA DE EFECTIVO ', 0, 1, '88.49', '88.49', 'SERVICIO', '0.00'),
(1055, 1, 'ROLLOS DE PAPEL TERMICO 80mm', 0, 1, '2.12', '2.12', 'SERVICIO', '0.00'),
(1056, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO(motorista)', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(1057, 1, 'REYNA DE LA PAZ MENDOZA PORTILLO(Zuri Guzman)', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(1058, 1, 'JOSE BENJAMIN DIAZ PERLARegina Baires)', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(1059, 1, 'FIDEL ALEXANDER CHIRINO HERNANDEZ(Elenilson Aguilar)', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(1060, 1, 'REYNA DE LA PAZ MENDOZA PORTILLO --- SURI GUZMAN', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(1061, 1, 'FIDEL ALEXANDER CHIRINO HERNANDEZ --- ELENILSON AGUILAR', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(1062, 1, 'JOSE BENJAMIN DIAZ PERLA --- REGINA BAIRES', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(1063, 1, 'TOMA CORRIENTE DOBLE POLARIZADO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(1064, 1, 'MANO DE OBRA', 0, 1, '34.25', '34.25', 'SERVICIO', '0.00'),
(1065, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE NOVIEMBRE DE 2021', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(1066, 1, 'INSTALACION Y CONFIGURACION DE SISTEMA OPERATIVO Y UTILIDADES WINDOWS', 0, 1, '26.55', '26.55', 'SERVICIO', '0.00'),
(1067, 1, 'MATERIALES PARA CAMBIAR UBICACION CAMARA DE VIGILANCIA', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(1068, 1, 'MANTENIMIENTO PREVENTIVO DE DVR', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1069, 1, 'MANTEMINIENTO PREVENTIVO DE COMPUTADORA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1070, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE NOVIEMBRE 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(1071, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE ABRIL DEL 2021', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1072, 1, 'CAJA DE DINERO 3NSTAR', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(1073, 1, 'CAMARA XIAOMI, MICRO SD DE 32GB, TOMA CORRIENTE Y CONFIGURACION', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(1074, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE NOVIEMBRE 2021', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(1075, 1, 'ELABORACION DE APLICACION PARA PUBLICIDAD DE PRODUCTO EN EL TELE', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1076, 1, 'MANO DE OBRA E INSTALACION DE CABLEADO DE RED PARA TV', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(1077, 1, 'MANTENIMIENTO PREVENTIVO, CONFIGURACION E INSTALACION DE SISTEMA OPERATIVO', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(1078, 1, '	ELABORACION DE APLICACION PARA PUBLICIDAD DE PRODUCTO EN EL TV', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1079, 1, 'CONFIGURACION E INSTALACION DE CAMARAS XIAOMI, MEMORIAS MICRO SD', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(1080, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE NOVIEMBRE 2021', 0, 1, '323.01', '323.01', 'SERVICIO', '0.00'),
(1081, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE DICIEMBRE 2021', 0, 1, '323.01', '323.01', 'SERVICIO', '0.00'),
(1082, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION ', 0, 1, '399.54', '399.54', 'SERVICIO', '0.00'),
(1083, 1, 'ROLLOS DE PAPEL TERMICO 80MM', 0, 1, '1.77', '1.77', 'SERVICIO', '0.00'),
(1084, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE NOVIEMBRE DE 2021', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1085, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(1086, 1, 'MATERIALES DE INSTALCION Y MANO DE OBRA', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1087, 1, 'Alcatel 3T 10in Media MTK8766B 2GB 32GB 4G Andr BlK keyboard', 0, 1, '203.54', '203.54', 'SERVICIO', '0.00'),
(1088, 1, 'CAMARA IP DE VIDEOVIGILANCIA XIAOMI', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(1089, 1, 'EQUIPO PUNTO DE VENTA  ----lector codigo de barra, impresora, CPU, monitor, teclado, mouse, UPS, caja de efectivo, 12 rollos de papel)', 0, 1, '642.83', '642.83', 'SERVICIO', '0.00'),
(1090, 1, 'SISTEMA IMFORMATICO PARA RESTAURANTE', 0, 1, '535.00', '535.00', 'SERVICIO', '0.00'),
(1091, 1, 'TABLET ALCATEL IT7', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1092, 1, 'MONITOR TOUCH HP L5015tm', 0, 1, '450.00', '450.00', 'SERVICIO', '0.00'),
(1093, 1, 'IMPRESORA DE TICKET ', 0, 1, '185.00', '185.00', 'SERVICIO', '0.00'),
(1094, 1, 'EQUIPO LENOVO TINKCENTER M93P I5', 0, 1, '260.00', '260.00', 'SERVICIO', '0.00'),
(1095, 1, 'UPS CDP DE 500VA', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1096, 1, 'CAJA DE DIMERO 3NSTAR ', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(1097, 1, 'MONITOR CLASE A--1 HP - 1ACER- 2 DELL', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(1098, 1, 'EQUIPO  DELL OPTIPLEX 7010 CORE I5 - SSD 24OGB -RAM 8GB', 0, 1, '247.79', '247.79', 'SERVICIO', '0.00'),
(1099, 1, 'IMPRESOR EPSON L3210 COLLOR ', 0, 1, '203.54', '203.54', 'SERVICIO', '0.00'),
(1100, 1, 'ROLLOS DE PAPEL TERMICO 80MM', 0, 1, '1.95', '1.95', 'SERVICIO', '0.00'),
(1101, 1, 'FUENTE DE PODER', 0, 1, '22.35', '22.35', 'SERVICIO', '0.00'),
(1102, 1, 'DISCO DURO DE 2T', 0, 1, '81.00', '81.00', 'SERVICIO', '0.00'),
(1103, 1, 'BOBINA DE CABLE UTP CAT6', 0, 1, '86.39', '86.39', 'SERVICIO', '0.00'),
(1104, 1, 'CONCECTORES DC', 0, 1, '0.88', '0.88', 'SERVICIO', '0.00'),
(1105, 1, 'VIDEO BALU DE TORNILLO ', 0, 1, '3.76', '3.76', 'SERVICIO', '0.00'),
(1106, 1, 'DVR MARCA HIKVISION 16 CANALES', 0, 1, '115.93', '115.93', 'SERVICIO', '0.00'),
(1107, 1, 'CAMARAS HIKVISION 1080P', 0, 1, '22.04', '22.04', 'SERVICIO', '0.00'),
(1108, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION ', 0, 1, '75.74', '75.74', 'SERVICIO', '0.00'),
(1109, 1, 'SOPORTE CORESPONDIENTE AL MES DE DICIEMBRE DE 2021', 0, 1, '365.00', '365.00', 'SERVICIO', '0.00'),
(1110, 1, 'SOCIAL MEDIA, CORESPONDIENTE AL MES DE NOVIEMBRE', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(1111, 1, 'SOCIAL MEDIA, CORESPONDIENTE AL MES DE OCTUBRE', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(1112, 1, 'KIT DE 8 CAMARAS, INSTALADO', 0, 1, '600.00', '600.00', 'SERVICIO', '0.00'),
(1113, 1, 'MEMORIA RAM DE 8', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1114, 1, 'EQUIPO SERVIDOR DE LA SUCURSAL #4', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1115, 1, 'CAJA DE EFECTIVO', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(1116, 1, 'IMPRESOR  ZEBRA modelo GC420T, serie: 54j191703811', 0, 1, '326.90', '326.90', 'SERVICIO', '0.00'),
(1117, 1, 'LECTOR CODIGO DE BARRA', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1118, 1, 'IMPRESOR TERMICO BEMATECH', 0, 1, '185.00', '185.00', 'SERVICIO', '0.00'),
(1119, 1, 'CAJA DE PAPEL TERMICO 80MM', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1120, 1, 'Fuente de poder', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(1121, 1, 'Conectores DC', 0, 1, '0.97', '0.97', 'SERVICIO', '0.00'),
(1122, 1, 'Video ballum', 0, 1, '3.98', '3.98', 'SERVICIO', '0.00'),
(1123, 1, 'Disco duro de 1TB', 0, 1, '58.41', '58.41', 'SERVICIO', '0.00'),
(1124, 1, 'DVR 8 canales', 0, 1, '53.10', '53.10', 'SERVICIO', '0.00'),
(1125, 1, 'Cámaras hikvision interior 1080p', 0, 1, '24.79', '24.79', 'SERVICIO', '0.00'),
(1126, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA ', 0, 1, '49.44', '49.44', 'SERVICIO', '0.00'),
(1127, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE DICIEMBREE 2021', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(1128, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE NOVIEMBRE 2021', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(1129, 1, 'DISCO CURO HD SEAGATE 1TB', 0, 1, '57.53', '57.53', 'SERVICIO', '0.00'),
(1130, 1, 'BOBINA DE CABLE, CAT5 INTERIOR, PRISMA', 0, 1, '53.10', '53.10', 'SERVICIO', '0.00'),
(1131, 1, 'CAJA DE PAPEL TERMICO 80MM', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1132, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE DICIEMBRE DE 2021', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1133, 1, 'DVR SLIM TRUBO 8 CANALES, RESOLUCION MAXIMA 1080P, MARCA HIKVISION', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(1134, 1, 'DVR SLIM  8 CANALES, 1080P MARCA HIKVISION', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(1135, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DICIEMBRE 2021', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(1136, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE MAYO  DEL 2021', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1137, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE MAYO  DEL 2021', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1138, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE DICIEMBRE DE 2021', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(1139, 1, 'Sistema de inventario y facturación openpyme', 0, 1, '800.00', '800.00', 'SERVICIO', '0.00'),
(1140, 1, 'CAMARA IP ', 0, 1, '154.87', '154.87', 'SERVICIO', '0.00'),
(1141, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '178.25', '178.25', 'SERVICIO', '0.00'),
(1142, 1, 'PAD PARA MOUSE ARGOM', 0, 1, '1.77', '1.77', 'SERVICIO', '0.00'),
(1143, 1, 'LATA DE AIRE COMPRIMIDO ', 0, 1, '7.96', '7.96', 'SERVICIO', '0.00'),
(1144, 1, 'FOTOCOPIADORA KYOCERA, M2040DN-L, SERIE: VR1585792', 0, 1, '752.21', '752.21', 'SERVICIO', '0.00'),
(1145, 1, 'CAJA DE PAPEL TERMICO 80MM 50 UNIDADES', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(1146, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES DE DICIEMBRE 2021', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1147, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES DE NOVIEMBRE 2021', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1148, 1, 'LICENCIA DE SISTEMA PARA INVENTARIO Y FACTURACION', 0, 1, '9.00', '9.00', 'SERVICIO', '0.00'),
(1149, 1, 'REGLETAS PARA CONEXION DE ENERGIA', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(1150, 1, 'INSTALACION  DE CAMARAS DE VIDEOVIGILANCIA XIAOMI  ', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(1151, 1, 'EQUIPO LENOVO THINKCENTRE M93, RAM 4GB, SSD 240GB.', 0, 1, '389.38', '389.38', 'SERVICIO', '0.00'),
(1152, 1, 'SERVICIO SOCIAL MEDIA, CORRESPONDIENTE A LOS MESES DE JULIO A DICIEMBRE 2021', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(1153, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '105.80', '105.80', 'SERVICIO', '0.00'),
(1154, 1, 'SISTEMA DE INVENTARIO Y FACTURACION   EQUIPO PUNTO DE VENTA', 0, 1, '1965.00', '1965.00', 'SERVICIO', '0.00'),
(1155, 1, 'CAJA DE EFECTIVO', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(1156, 1, 'TECLADO Y MOUSE', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(1157, 1, 'MONITOR  HP CLASE A', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(1158, 1, 'EQUIPO LENOVO THINKCENTRE M93P, RAM 4GB, SSD 240GB.', 0, 1, '230.09', '230.09', 'SERVICIO', '0.00'),
(1159, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(1160, 1, 'CAJA DE EFECTIVO ', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(1161, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1162, 1, '	UPS 600VA ORBITEC', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1163, 1, 'SWITCH DE 5 PUERTOS, MARCA TENDA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1164, 1, 'METRO DE CABLE UTP CAT 6', 0, 1, '0.50', '0.50', 'SERVICIO', '0.00'),
(1165, 1, 'caja de papel termico 80mm', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1166, 1, 'CAMARA  5MP, MARCA HIKVISION', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(1167, 1, 'CABLE UTP CAT 5 UNIDAD METRO INTERPERIE', 0, 1, '0.45', '0.45', 'SERVICIO', '0.00'),
(1168, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1169, 1, 'MONITORES HP - P19b - G4  LCD monitor, 18.5\",HDMI VGA, 1366 x 768, 1 año de garantia', 0, 1, '230.00', '230.00', 'SERVICIO', '0.00'),
(1170, 1, 'Equipo de escritorio Dell OptiPlex 3080 - Micro - Core i5 10500T / 2.3 GHz, combo tecldo y mo', 0, 1, '1120.00', '1120.00', 'SERVICIO', '0.00'),
(1171, 1, 'SERVIDOR VPS XION 2 NUCLEOS, 120SSD, 8RAM, IP PUBLICA, DOMINIO PERSONALIZADO- LICENCIA PARA 1 AñO', 0, 1, '900.00', '900.00', 'SERVICIO', '0.00'),
(1172, 1, 'Silla ejecutiva empresarial', 0, 1, '4.00', '4.00', 'SERVICIO', '0.00'),
(1173, 1, 'Escritorio de oficina para computadora ', 0, 1, '145.00', '145.00', 'SERVICIO', '0.00'),
(1174, 1, 'IMPRESORA DE TANQUE MULTIFUNCIONAL EPSON L3210', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(1175, 1, 'Ups orbitec 750v', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1176, 1, 'Impresor epson de tanque multifuncional L-3210', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(1177, 1, 'Monitor 19.5 clase A en Marca Dell o HP', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(1178, 1, 'Cpu core i5 4ta generación memoria ram de 8g disco sólido de 480 Windows 10 instalado', 0, 1, '345.00', '345.00', 'SERVICIO', '0.00'),
(1179, 1, 'Ups orbitec 750v', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(1180, 1, 'Impresor epson de tanque multifuncional L-3210', 0, 1, '225.00', '225.00', 'SERVICIO', '0.00'),
(1181, 1, 'Monitor 19.5 clase A en Marca Dell o HP', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(1182, 1, 'Cpu core i5 4ta generación memoria ram de 8g disco sólido de 480 Windows 10 instalado', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1183, 1, 'cámara hikvision resolucion 1080p', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1184, 1, 'MANO DE OBRA', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(1185, 1, 'FUENTE DE PODER 1AMP', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(1186, 1, 'PAGO DE PUBLICIDAD FACEBOOK CORRESPONDIENTE AL MES DE DICIEMBRE 2021', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(1187, 1, 'PAGO DE PUBLICIDAD FACEBOOK CORRESPONDIENTE AL MES DE NOVIEMBRE 2021', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(1188, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE DICIEMBRE DE 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(1189, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE NOVIEMBRE DE 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(1190, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE DICIEMBRE DE 2021', 0, 1, '88.49', '88.49', 'SERVICIO', '0.00'),
(1191, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE NOVIEMBREDE 2021', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1192, 1, 'teclado Dell ', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(1193, 1, 'mouse inalambrico', 0, 1, '6.19', '6.19', 'SERVICIO', '0.00'),
(1194, 1, 'punto de red', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(1195, 1, 'equipo dell optiplex 7010', 0, 1, '265.49', '265.49', 'SERVICIO', '0.00'),
(1196, 1, '	CABLE VNC 18MTS', 0, 1, '15.86', '15.86', 'SERVICIO', '0.00'),
(1197, 1, 'EQUIPO LENOVO THINKCENTRE M73, -RAM 4GB --SSD 120GB', 0, 1, '230.09', '230.09', 'SERVICIO', '0.00'),
(1198, 1, 'CAMARA IP JORDAN CON DETECTOR DE MOVIMIENTOS, INCLUYE INSTALACION ELECTRICA Y CONFIGURACION  DE LA CAMARAS.', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(1199, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '51.95', '51.95', 'SERVICIO', '0.00'),
(1200, 1, 'MANO DE OBRA Y MATERIALES ENLACE ENTRE EDIFICIOS', 0, 1, '130.00', '130.00', 'SERVICIO', '0.00'),
(1201, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '117.82', '117.82', 'SERVICIO', '0.00'),
(1202, 1, 'MICROFONO PARA UNA CAMARAS', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1203, 1, '120 METROS DE CABLE UTP, TERMINALES DE VIDEO Y CORRIENTE', 0, 1, '104.00', '104.00', 'SERVICIO', '0.00'),
(1204, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION ', 0, 1, '101.49', '101.49', 'SERVICIO', '0.00'),
(1205, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '12.00', '12.00', 'SERVICIO', '0.00'),
(1206, 1, 'DISCO DURO HDD 4TB', 0, 1, '420.00', '420.00', 'SERVICIO', '0.00'),
(1207, 1, 'ROLLO DE PAPEL TERMICO 8MM', 0, 1, '1.95', '1.95', 'SERVICIO', '0.00'),
(1208, 1, 'costo de envio', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(1209, 1, 'INSTALACION DE SOFTWARE SOLICITADO SEGUN COTIZACION', 0, 1, '500.00', '500.00', 'SERVICIO', '0.00'),
(1210, 1, 'H.D SEAGATE DISCO DURO 1T', 0, 1, '65.99', '65.99', 'SERVICIO', '0.00'),
(1211, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '1.00', '1.00', 'SERVICIO', '0.00'),
(1212, 1, 'KIT DE 4 CAMARAS CCTV 1080, HIKVISION. MONITOR CLASE A, ARNES, MOUSE', 0, 1, '399.11', '399.11', 'SERVICIO', '0.00'),
(1213, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1214, 1, 'Leonardo Fabio Duran Guerrero', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(1215, 1, 'Víctor Enrique Carcamo Quintanilla ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(1216, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES DE ENERO DE 2022', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1217, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES DE DICIEMBRE DE 2021', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1218, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES DE NOVIEMBRE DE 2021', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1219, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES DE OCTUBRE DE 2021', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1220, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES DE SEPTIEMBRE DE 2021', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1221, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES DE ENERO DEL 2022', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1222, 1, 'MANTENIMIENTO DE DVR\'S', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1223, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES DE DICIEMBRE DEL 2021', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1224, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES DE NOVIEMBRE DEL 2021', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1225, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1226, 1, 'CABLE TNM 12-2 BLANCO CORTE', 0, 1, '1.75', '1.75', 'SERVICIO', '0.00'),
(1227, 1, 'RVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE AL MES DE FEBRERO', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(1228, 1, 'RVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE AL MES DE ENERO 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(1229, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION ', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(1230, 1, 'MANTEMINIENTO PREVENTIVO DE EQUIPO INFORMATICO Y SISTEMA DE VIDEO VIGILANCIA SUCURSAL #6', 0, 1, '83.33', '83.33', 'SERVICIO', '0.00'),
(1231, 1, 'MANTEMINIENTO PREVENTIVO DE EQUIPO INFORMATICO Y SISTEMA DE VIDEO VIGILANCIA SUCURSAL #5', 0, 1, '83.33', '83.33', 'SERVICIO', '0.00'),
(1232, 1, 'MANTEMINIENTO PREVENTIVO DE EQUIPO INFORMATICO Y SISTEMA DE VIDEO VIGILANCIA SUCURSAL #4', 0, 1, '83.33', '83.33', 'SERVICIO', '0.00'),
(1233, 1, 'MANTEMINIENTO PREVENTIVO DE EQUIPO INFORMATICO Y SISTEMA DE VIDEO VIGILANCIA SUCURSAL #3', 0, 1, '83.33', '83.33', 'SERVICIO', '0.00'),
(1234, 1, 'MANTEMINIENTO PREVENTIVO DE EQUIPO INFORMATICO Y SISTEMA DE VIDEO VIGILANCIA SUCURSAL #2', 0, 1, '83.34', '83.34', 'SERVICIO', '0.00'),
(1235, 1, 'MANTEMINIENTO PREVENTIVO DE EQUIPO INFORMATICO Y SISTEMA DE VIDEO VIGILANCIA SUCURSAL#1', 0, 1, '83.34', '83.34', 'SERVICIO', '0.00'),
(1236, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1237, 1, 'ENVIO', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(1238, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE ENERO 2022', 0, 1, '323.01', '323.01', 'SERVICIO', '0.00'),
(1239, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '48.39', '48.39', 'SERVICIO', '0.00'),
(1240, 1, 'Soporte app Kapital', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(1241, 1, 'Regleta', 0, 1, '4.42', '4.42', 'SERVICIO', '0.00'),
(1242, 1, '2 Camara IP', 0, 1, '150.44', '150.44', 'SERVICIO', '0.00'),
(1243, 1, 'Equipo de San Miguel', 0, 1, '389.38', '389.38', 'SERVICIO', '0.00'),
(1244, 1, 'Licencia de sistema', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(1245, 1, '10 rollos de papel viñeta', 0, 1, '106.19', '106.19', 'SERVICIO', '0.00'),
(1246, 1, 'Impresor de vinetas Zebra', 0, 1, '331.86', '331.86', 'SERVICIO', '0.00'),
(1247, 1, 'Lector Código de Barra ', 0, 1, '141.59', '141.59', 'SERVICIO', '0.00'),
(1248, 1, 'Impresora HP Multifuncional', 0, 1, '243.36', '243.36', 'SERVICIO', '0.00'),
(1249, 1, 'CAJA DE PAPEL TERMICO 50 UNIDADES, MEDIDA 80MM', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1250, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE MAYO DEL 2021', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1251, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE ABRIL DEL 2021', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1252, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE JUNIO DEL 2021', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1253, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE MAYO DEL 2021', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1254, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ENERO DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1255, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE ENERO DE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(1256, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE JUNIO DEL 2021', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1257, 1, 'SOPORTE TECNICO PARA SISTEMA EXTENDIDO DE INVENTARIO Y FACTURACION DEL MES DE ENERO 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1258, 1, 'MONITOR LCD AOC 19.6 GARANTIA DE 1 AÑO', 0, 1, '145.00', '145.00', 'SERVICIO', '0.00'),
(1259, 1, 'EQUIPO DELL OPTIPLEX 790 I5 3.3GHZ - RAM 8GB - SSD 240GB', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(1260, 1, 'Impresora de escritorio ZD230', 0, 1, '400.00', '400.00', 'SERVICIO', '0.00'),
(1261, 1, 'SOPORTE PARA TV DE 32\"  E INSTALACION.', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(1262, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE VIDEO, MAS TOMA CORRIENTES', 0, 1, '99.40', '99.40', 'SERVICIO', '0.00'),
(1263, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE ENERO 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(1264, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE DICIEMBRE 2021', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(1265, 1, '	KIT DE 4 CAMARAS 1080P   2 CAMARAS CON MICROFONO 1080P, MARCA HIKVISION, CON INSTALACION GRATIS.', 0, 1, '499.00', '499.00', 'SERVICIO', '0.00'),
(1266, 1, 'SOPORTE EMPOTRABLE EN PARED PARA TV DE 32\"', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(1267, 1, '	KIT DE 4 CAMARAS NORMALES 1080P Y 2 CAMARAS CON MICROFONO, MARCA HIKVISION, CON INSTALACION GRATIS.', 0, 1, '489.00', '489.00', 'SERVICIO', '0.00'),
(1268, 1, 'CAJA DE PAPER TERMICO 80MM, 50 UNIDADES', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(1269, 1, 'SERVICIO DE SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES ENERO 2022', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(1270, 1, 'CABLE HDMI 3 MTRS', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(1271, 1, 'IMPRESORA DE TANQUE MULTIFUNCIONAL EPSON', 0, 1, '194.69', '194.69', 'SERVICIO', '0.00'),
(1272, 1, 'GABINETE DE 12U 19 PULGADAS NEGRO', 0, 1, '240.00', '240.00', 'SERVICIO', '0.00'),
(1273, 1, 'UPS FORZA HT SERIES HT-1000LCD 500 VATIOS', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(1274, 1, 'BANDEJA PARA GABINETE DE ACERO', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(1275, 1, 'MI 360° HOME SECURITY CAMERA 2K', 0, 1, '57.52', '57.52', 'SERVICIO', '0.00'),
(1276, 1, 'CAMARAS VIDEOVIGILANCIA CON AUDIO', 0, 1, '48.68', '48.68', 'SERVICIO', '0.00'),
(1277, 1, 'MEMORIA MICRO SD 32GB', 0, 1, '8.86', '8.86', 'SERVICIO', '0.00'),
(1278, 1, 'UPS CDP 500 VA', 0, 1, '39.83', '39.83', 'SERVICIO', '0.00'),
(1279, 1, 'EQUIPO COMPLETO LENOVO THINKCENTRE M93, MONITOR, TECLADO Y MOUSE', 0, 1, '314.14', '314.14', 'SERVICIO', '0.00'),
(1280, 1, 'MTAERIALES DE INSTALACION', 0, 1, '96.98', '96.98', 'SERVICIO', '0.00'),
(1281, 1, 'SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(1282, 1, 'EQUIPO LENOVO THINKCENTRE, PROCESADOR I5, MODELO M73, RAM 4GB, SSD 240GB.', 0, 1, '260.00', '260.00', 'SERVICIO', '0.00'),
(1283, 1, 'IMPRESORA DE RECIBOS', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1284, 1, 'Fuente de alimentación para 6 cámaras', 0, 1, '28.32', '28.32', 'SERVICIO', '0.00'),
(1285, 1, 'Disco duro de 6TB', 0, 1, '241.59', '241.59', 'SERVICIO', '0.00'),
(1286, 1, 'DVR 16 canales marca HIKVISION', 0, 1, '128.32', '128.32', 'SERVICIO', '0.00'),
(1287, 1, 'Fuente de alimentación para 6 cámaras', 0, 1, '28.32', '28.32', 'SERVICIO', '0.00'),
(1288, 1, 'Disco duro de 6TB', 0, 1, '128.32', '128.32', 'SERVICIO', '0.00'),
(1289, 1, 'DVR 16 canales marca HIKVISION', 0, 1, '241.59', '241.59', 'SERVICIO', '0.00'),
(1290, 1, 'Fuente de poder conmutada de 16 camaras', 0, 1, '97.35', '97.35', 'SERVICIO', '0.00'),
(1291, 1, 'Video Balum', 0, 1, '5.31', '5.31', 'SERVICIO', '0.00'),
(1292, 1, 'Disco duro de 6TB Seagate', 0, 1, '241.59', '241.59', 'SERVICIO', '0.00'),
(1293, 1, 'DVR 16 canales marca HIKVISION', 0, 1, '128.32', '128.32', 'SERVICIO', '0.00'),
(1294, 1, 'Cámaras IP 67 marca HIKVISION 1080p metalica', 0, 1, '26.55', '26.55', 'SERVICIO', '0.00'),
(1295, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '356.55', '356.55', 'SERVICIO', '0.00'),
(1296, 1, 'FUENTE DE PODER P/16 CAMARAS , 12V 20A', 0, 1, '125.00', '125.00', 'SERVICIO', '0.00'),
(1297, 1, 'TERMINALES DE VIDEO Y CORRIENTE PARA CAMARAS CCTV(16 Pares Video Balum y 16 Pares conectores DC)', 0, 1, '96.00', '96.00', 'SERVICIO', '0.00'),
(1298, 1, 'MANTENIMIENTO PREVENTIVO Y CORRECTIVO DE 16 CAMARAS DE VIGILANCIA', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1299, 1, 'CABLE UTP, CAT 5E, PARA EXTERIOR, CON MENSAJERO 305 MTS, COLOR NEGRO, METRO', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(1300, 1, 'GABINETE LINKBASIC 6U', 0, 1, '180.00', '180.00', 'SERVICIO', '0.00'),
(1301, 1, 'CAJA DE PAPEL TERMICO 80MM, 50UNIDADES', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1302, 1, 'CAJA DE PAPEL TERMICO 80MM, 50 UNIDADES', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1303, 1, 'PEGAMENTO DE CONTACTO PARA ZAPATERIA AMARILLO DEL TORO', 0, 1, '3.00', '3.00', 'SERVICIO', '0.00'),
(1304, 1, 'cpu lenovo thinkcentre m73, ram 4gb, hd250gb,cable de poder', 0, 1, '180.00', '180.00', 'SERVICIO', '0.00'),
(1305, 1, 'BOBINA DE CABLE UTP CATEGORIA 5E P', 0, 1, '57.52', '57.52', 'SERVICIO', '0.00'),
(1306, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE DICIEMBRE 2021', 0, 1, '323.01', '323.01', 'SERVICIO', '0.00'),
(1307, 1, 'FRANCISCO DANIEL CAMPOS MACHADO', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1308, 1, 'DENY ERNESTO SANCHEZ FLORES', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1309, 1, 'JOSE MIGUEL JOYA BENITEZ', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1310, 1, 'GIOVANI EMMANUEL LACAYO ORTEGA', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1311, 1, 'ROXANA YAMILETH POLIO FUENTES', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1312, 1, 'VANESSA MARIA ALVARADO TURCIOS', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1313, 1, 'VANESSA MARIA ALVARADO TURCIOS', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1314, 1, 'ROXANA YAMILETH POLIO FUENTES', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1315, 1, 'GIOVANI EMMANUEL LACAYO ORTEGA', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1316, 1, 'JOSE MIGUEL JOYA BENITEZ', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1317, 1, 'DENY ERNESTO SANCHEZ FLORES', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1318, 1, 'FRANCISCO DANIEL CAMPOS MACHADO', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1319, 1, 'LEONARDO FABIO DURAN GUERRERO', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1320, 1, 'TOMA CORRIENTE, MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(1321, 1, 'INSTALACION DE 8 CAMARAS HIKVISION, MATERIALES Y MANO DE OBRA', 0, 1, '530.97', '530.97', 'SERVICIO', '0.00'),
(1322, 1, 'MANO DE OBRA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(1323, 1, 'CAMARA TURBO DOMO 1080P, 2MPX, LENTE FIJO, IR 20 MTS, MARCA HIKVISION', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(1324, 1, 'Impresora HP MF DeskJet 2375 7WQ01A#AKY', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(1325, 1, 'TOMA CORRIENTE DE MATERIALES DE INSTALACION MANO DE OBRA', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(1326, 1, 'DVR HIKVISION TURBO HD 16 CANALES', 0, 1, '130.00', '130.00', 'SERVICIO', '0.00'),
(1327, 1, 'VIDEO BALUN', 0, 1, '4.50', '4.50', 'SERVICIO', '0.00'),
(1328, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 5 SALIDAS', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1329, 1, 'CAMARA TURBO DOMO 1080P, 2MPX, LENTE FIJO, PLASTICA, IR 20 MTS, MARCA HIKVISION', 0, 1, '26.35', '26.35', 'SERVICIO', '0.00'),
(1330, 1, 'CAMARA TURBO BALA 1080, LENTE FIJO, IP66, IR 20 MTS, MARCA HIKVISION', 0, 1, '27.00', '27.00', 'SERVICIO', '0.00'),
(1331, 1, 'ALOJAMIENTO DE PAGINA WEB Y RENOVACION DE DOMINIO DE INTERNET', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(1332, 1, 'Impresora epson matricial lx-350', 0, 1, '350.00', '350.00', 'SERVICIO', '0.00'),
(1333, 1, 'DVR SLIM TRUBO 8 CANALES, RESOLUCION MAXIMA 1080P, 4MP, MARCA HIKVISION', 0, 1, '110.00', '110.00', 'SERVICIO', '0.00'),
(1334, 1, 'UPS ORBITEC 600VA', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(1335, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO', 0, 1, '18.00', '18.00', 'SERVICIO', '0.00'),
(1336, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1337, 1, 'FUENTE DE PODER P/18 CAMARAS , 12V 20A', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(1338, 1, 'TERMINALES DE VIDEO Y CORRIENTE PARA CAMARAS CCTV', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1339, 1, 'SOPORTE PARA TV DE 32\"', 0, 1, '22.00', '22.00', 'SERVICIO', '0.00'),
(1340, 1, 'TV DE 32\", MARCA MOTOROLA', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(1341, 1, 'FUENTE DE PODER PARA 4 CAMARAS', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(1342, 1, 'CABLE DE VIDEO HDMI DE 15 METROS', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1343, 1, 'IMPRESOR MATRICIAL LX-890, CLASE A', 0, 1, '225.00', '225.00', 'SERVICIO', '0.00'),
(1344, 1, 'IMPRESOR MATRICIAL FX-890, CLASE A', 0, 1, '225.00', '225.00', 'SERVICIO', '0.00'),
(1345, 1, 'caja de papel termico 80mm, 50 unidades', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(1346, 1, 'MICROFONO PARA CAMARA CCTV', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(1347, 1, 'CAJA DE PAPEL TERMICO 80MM, 50 UNIDADES', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(1348, 1, 'TELEFONO IP MODELO GXP1615 DOBLE PUERTO ETHERNET 10/100 1 CUENTA', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1349, 1, 'FLETE POR ENVIO', 0, 1, '8.85', '8.85', 'SERVICIO', '0.00'),
(1350, 1, 'TELEFONO IP MODELO GXP1615 DOBLE PUERTO ETHERNET 10/100 1 CUENTA', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(1351, 1, 'KIT DE 4 CAMARAS 1080P, MARCA HIKVISION, CON INSTALACION GRATIS', 0, 1, '360.00', '360.00', 'SERVICIO', '0.00'),
(1352, 1, 'caja de papel termico 8mm, 50 unidades', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1353, 1, 'CAJA DE PAPEL TERMICO 80MM, 50 UNIDADES', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(1354, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE FEBRERO DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1355, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE JULIO DEL 2021', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1356, 1, 'MONITOR DE 22\", CLASE A', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(1357, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE FEBRERO DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1358, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE FEBRERO DE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(1359, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(1360, 1, 'PAGO DE PUBLICIDAD FACEBOOK CORRESPONDIENTE AL MES DE FEBRERO 2022', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(1361, 1, 'PAGO DE PUBLICIDAD FACEBOOK CORRESPONDIENTE AL MES DE ENERO 2022', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(1362, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE FEBRERO DE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(1363, 1, '	SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE ENERO DE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(1364, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE FREBRERO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1365, 1, '	SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE ENERO DE 2022', 0, 1, '88.49', '88.49', 'SERVICIO', '0.00'),
(1366, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE FEBRERO 2022', 0, 1, '323.01', '323.01', 'SERVICIO', '0.00'),
(1367, 1, '	SOPORTE TECNICO PARA SISTEMA EXTENDIDO DE INVENTARIO Y FACTURACION DEL MES DE FEBRERO 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1368, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES FEBRERO DE F DEL 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1369, 1, 'TERMINALES DE VIDEO Y CONECTORES DC PARA CAMARA DE VIDEO', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1370, 1, 'DVR SLIM TRUBO 8 CANALES, RESOLUCION MAXIMA 1080P, MARCA HIKVISION', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(1371, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION ', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1372, 1, 'MANTENIMIENTO PREVENTIVO A EQUIPO INFORMÁTICO, SISTEMA OPERATIVO, OFFIMATICA Y COMUNICACIÓN DE DATOS. ', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(1373, 1, 'DVR SLIM TRUBO 8 CANALES, RESOLUCION MAXIMA 1080P, MARCA HIKVISION', 0, 1, '125.00', '125.00', 'SERVICIO', '0.00'),
(1374, 1, 'COMPUTADORA DE ESCRITORIO OPTIPLEX 7010, SSD 240, RAM 8GB', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(1375, 1, 'SERVICIO DE ALOJAMIENTO Y NOMBRE DE DOMINIO 2021', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(1376, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES DE FEBRERO 2022', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1377, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES DE ENERO 2022', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1378, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES FEBRERO DE 2022', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(1379, 1, 'LECTOR DE CODIGO DE BARRAS SC100', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(1380, 1, 'MONITOR DE 22 PULGADAS CLASE A', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1381, 1, 'LENOVO THINKCENTRE M73 TINY CORE I5-4570T 2.90GHZ 4GB RAM SSD 240', 0, 1, '260.00', '260.00', 'SERVICIO', '0.00'),
(1382, 1, 'EQUIPO DE ESCRITORIO LENOVO TINY M73 I3-413OT 2.90 GHZ HD 250 GARANTIA DE 6 MESES, 4GB DE RAM', 0, 1, '135.00', '135.00', 'SERVICIO', '0.00'),
(1383, 1, 'CAMARAS XIAOMI   MEMORIA MICROSD 32GB', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(1384, 1, 'TERMINALES DE VIDEO Y CORRIENTE', 0, 1, '7.00', '7.00', 'SERVICIO', '0.00'),
(1385, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '162.82', '162.82', 'SERVICIO', '0.00'),
(1386, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1387, 1, 'GABINETE LINKBASIC 6U', 0, 1, '180.00', '180.00', 'SERVICIO', '0.00'),
(1388, 1, 'CABLE DE VIDEO HDMI DE 7 METROS', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(1389, 1, 'INSTALACION Y CONFIGURACION ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1390, 1, 'DVR SLIM TRUBO 8 CANALES, RESOLUCION MAXIMA 1080P, MARCA HIKVISION', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(1391, 1, 'INSTALACION Y CONFIGURACION ', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(1392, 1, 'DISCO DURO SEAGATE EXTERNO 1TB', 0, 1, '57.52', '57.52', 'SERVICIO', '0.00'),
(1393, 1, 'CABLE DE VIDEO HDMI DE 7 METROSCAL', 0, 1, '8.85', '8.85', 'SERVICIO', '0.00'),
(1394, 1, 'UPS 600VA ORBITEC', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(1395, 1, 'BANDEJA DE ACERO EMPOTRABLE EN PARED', 0, 1, '34.51', '34.51', 'SERVICIO', '0.00'),
(1396, 1, 'CABLE UTP CAT 5 INTERPERIE METRO', 0, 1, '0.49', '0.49', 'SERVICIO', '0.00'),
(1397, 1, 'KIT DE 4 CAMARAS 1080P, MARCA HIKVISION, CONINSTALACION GRATIS', 0, 1, '274.34', '274.34', 'SERVICIO', '0.00'),
(1398, 1, 'MANO DE OBRA POR INSTALACION DE DOS CAMARAS EXTRA', 0, 1, '43.50', '43.50', 'SERVICIO', '0.00'),
(1399, 1, 'MANO DE OBRA POR INSTALACION DE DOS CAMARAS EXTRA', 0, 1, '38.50', '38.50', 'SERVICIO', '0.00'),
(1400, 1, 'CABLE DE VIDEO HDMI DE 7 METROS', 0, 1, '8.85', '8.85', 'SERVICIO', '0.00'),
(1401, 1, 'UPS 600VA ORBITEC', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(1402, 1, 'CABLE UTP CAT 5 INTERPERIE METRO', 0, 1, '0.49', '0.49', 'SERVICIO', '0.00'),
(1403, 1, 'BANDEJA DE ACERO EMPOTRABLE EN PARED', 0, 1, '34.01', '34.01', 'SERVICIO', '0.00'),
(1404, 1, 'KIT DE 6 CAMARAS DE 1080, DVR DE 8 CANALES, DISCO DE 1TB', 0, 1, '336.29', '336.29', 'SERVICIO', '0.00'),
(1405, 1, 'CAJA DE PAPEL TERMICO PARA POS 57MM, 72 unidades', 0, 1, '63.72', '63.72', 'SERVICIO', '0.00'),
(1406, 1, 'caja de papel termico 80mm, 50 unidades', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1407, 1, 'caja de papel termico 80mm, 50 unidades', 0, 1, '77.43', '77.43', 'SERVICIO', '0.00'),
(1408, 1, 'TELEVISOR LED TCL 32 PULGADAS', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(1409, 1, 'CABLE UTP CAT 5 UNIDAD METRO', 0, 1, '0.30', '0.30', 'SERVICIO', '0.00'),
(1410, 1, 'MANO DE OBRA POR LA INSTALACION DE 8 CAMARAS', 0, 1, '175.00', '175.00', 'SERVICIO', '0.00'),
(1411, 1, 'JUAN ANTONIO GARCIAS', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1412, 1, 'BLANCA YAMILTEH HERNANDEZ AMAYA', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1413, 1, 'ERIKA JULISSA VANEGAS SEGOVIA', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1414, 1, 'DAVID ALEXANDER GONZALEZ REYES', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1415, 1, 'NANCY LISSETTE RIVERA AGUIRRE', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1416, 1, 'NOE ABEL GOMEZ LAZO', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1417, 1, 'FRANCISCO DANIEL CAMPOS MACHADO', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1418, 1, 'DENY ERNESTO SANCHEZ FLORES', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1419, 1, 'JOSE MIGUEL JOYA BENITEZ', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1420, 1, 'GIOVANI EMMANUEL LACAYO ORTEGA', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1421, 1, 'ROXANA YAMILETH POLIO FUENTES', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1422, 1, 'VANESA MARIA ALVARADO TURCIOS', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1423, 1, 'BANDEJA DE ACERO EMPOTRABLE EN PARED', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(1424, 1, 'kit de 2 camaras hikvision color vu 24/7, 1 dvr4, disco duro 1t, instalacion gratis', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1425, 1, 'kit de 2 camaras hikvision color vu 24/7, 1 dvr4 canales, disco duro 1t, instalacion gratis', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1426, 1, 'MATERIALES E INSTALACION PARA 2 PUNTOS DE RED (TIKETERA DE COSINA, ROUTER)', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(1427, 1, 'TOMAR CORRIENTE COMPLETO PARA  ROUTER', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1428, 1, 'ROUTER VANIN RX4-1500 JUPLINK AX15 1024QAM OFDMA MU-MIMO WIFI6 1500MBPS DUAL BAND GIGABIT', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(1429, 1, 'IMPRESOR MATRICIAL FX-890 CLASE A', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(1430, 1, 'REGLETA DE PODER', 0, 1, '4.42', '4.42', 'SERVICIO', '0.00'),
(1431, 1, 'CAJA DE INTERPERIE', 0, 1, '2.21', '2.21', 'SERVICIO', '0.00'),
(1432, 1, 'CAMARA TURBO 1080,HIKVISION ', 0, 1, '19.47', '19.47', 'SERVICIO', '0.00'),
(1433, 1, 'caja de papel termico 80mm, 50 unidades', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1434, 1, 'CAJA DE EFECTIVO 3NSTAR', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1435, 1, 'cable hdmi 22.5 metros', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1436, 1, 'BANDEJA DE ACERO EMPOTRABLE EN PARED', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(1437, 1, 'JOHANNA JEANNETTE MENDEZ GARCIA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(1438, 1, 'cable hdmi 7metros', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(1439, 1, '80 METROS DE CABLE UTP CAT5', 0, 1, '0.25', '0.25', 'SERVICIO', '0.00'),
(1440, 1, 'FUENTE DE PODER DE 4 SPLITTER', 0, 1, '22.00', '22.00', 'SERVICIO', '0.00'),
(1441, 1, 'DISCO DURO SEAGATE 1T', 0, 1, '57.00', '57.00', 'SERVICIO', '0.00'),
(1442, 1, 'MATERIALES DE INSTALACIÓN', 0, 1, '125.00', '125.00', 'SERVICIO', '0.00'),
(1443, 1, 'HIKVISION DVR 8 CANLES STANDALONE DVR', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1444, 1, 'CABLE HDMI 3METROS', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(1445, 1, 'KIT DE CAMARAS COLOR VU, 24/7 CON AUDIO', 0, 1, '325.00', '325.00', 'SERVICIO', '0.00'),
(1446, 1, 'IMPRESOR MATRICIAL LX-350', 0, 1, '309.73', '309.73', 'SERVICIO', '0.00'),
(1447, 1, 'EQUIPO LENOVO THINKCENTRE M73, CORE I3, 4GB RAM, 250GB HDD, 4ta Generación, garantia 6 meses', 0, 1, '106.19', '106.19', 'SERVICIO', '0.00'),
(1448, 1, 'MATERIALES DE INSTALACIÓN', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(1449, 1, 'DISCO DURO SEAGATE 1T', 0, 1, '50.44', '50.44', 'SERVICIO', '0.00'),
(1450, 1, 'HIKVISION DVR 8 CANLES STANDALONE DVR', 0, 1, '79.66', '79.66', 'SERVICIO', '0.00'),
(1451, 1, 'CONECTOR DE PODER PARA CAMARA CCTV', 0, 1, '2.28', '2.28', 'SERVICIO', '0.00'),
(1452, 1, 'VIDEO BALUN', 0, 1, '4.48', '4.48', 'SERVICIO', '0.00'),
(1453, 1, 'CABLE UTP CAT 5 UNIDAD METRO', 0, 1, '0.24', '0.24', 'SERVICIO', '0.00'),
(1454, 1, 'CAMARA TURBO DOMO 1080P, 2MPX, LENTE FIJO,PLASTICA, IR 20 MTS, MARCA HIKVISION', 0, 1, '19.47', '19.47', 'SERVICIO', '0.00'),
(1455, 1, 'CAMARA COLOR Y AUDIO 24/7', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(1456, 1, 'FUENTE DE PODER PARA CAMARA CCTV DE 1AM', 0, 1, '4.42', '4.42', 'SERVICIO', '0.00'),
(1457, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 5 SALIDA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1458, 1, 'CABLE HDMI 15 MTRS', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(1459, 1, 'UPS 600VA ORBITEC', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(1460, 1, 'BANDEJA DE ACERO EMPOTRABLE EN PARED', 0, 1, '30.97', '30.97', 'SERVICIO', '0.00'),
(1461, 1, 'CABLE VGA 4.5METROS', 0, 1, '13.00', '13.00', 'SERVICIO', '0.00'),
(1462, 1, 'MONITOR HP DE 22 PULGADAS, CLASE A, GARANTIA DE 6MESES', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(1463, 1, 'MOUSE INALAMBRICO GALO', 0, 1, '7.00', '7.00', 'SERVICIO', '0.00'),
(1464, 1, 'KIT COLOR VU 24/7 3 CAMARAS 1080 1 COLOR INSTALACION GRATIS, GARANTIA DE 1 A?0', 0, 1, '400.00', '400.00', 'SERVICIO', '0.00'),
(1465, 1, 'MONITOR HP CLASE A, 22 PULGADAS', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(1466, 1, 'CABLE VGA 4.5 METROS', 0, 1, '13.00', '13.00', 'SERVICIO', '0.00'),
(1467, 1, 'MOUSE INALAMBRICO GALO', 0, 1, '7.00', '7.00', 'SERVICIO', '0.00'),
(1468, 1, 'KIT COLOR VU 24/7 3 CAMARAS 1080 1 COLOR INSTALACION GRATIS', 0, 1, '400.00', '400.00', 'SERVICIO', '0.00'),
(1469, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DEABRIL  2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(1470, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE MARZO 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(1471, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE MARZO DE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(1472, 1, 'PAGO DE PUBLICIDAD FACEBOOK CORRESPONDIENTE AL MES DE MARZO 2022', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(1473, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE MARZO DE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(1474, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE MARZO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1475, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE MARZO DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1476, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE  AGOSTO DEL 2021', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1477, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE MARZO 2022', 0, 1, '323.01', '323.01', 'SERVICIO', '0.00'),
(1478, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE VIDEO SEGURIDAD', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1479, 1, 'FUENTE DE PODER 4 SPLITER 5A', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1480, 1, 'CAJA DE PAPEL TERMICO 58MM, 100 UNIDADES', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00');
INSERT INTO `servicios` (`id_servicio`, `estado`, `descripcion`, `id_sucursal`, `id_categoria`, `costo`, `precio`, `tipo_prod_servicio`, `precio_iva`) VALUES
(1481, 1, 'caja de papel termico 80mm, 50 unidades', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1482, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES MARZO  DE 2022', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(1483, 1, 'FUENTE DE PODER 4 SPLITTER 5A', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1484, 1, 'CAMARA COLOR VU', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1485, 1, 'FUENTE DE PODER 4 SPLITER 5 A', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1486, 1, 'COLOR VU 24/7 METALICA CON AUDIO 1080', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1487, 1, 'TURBO BALA HIKVISION METALICA 1080', 0, 1, '33.00', '33.00', 'SERVICIO', '0.00'),
(1488, 1, 'BOBINA DE CABLE UTP CAT5', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1489, 1, 'KIT DE 3 CAMARAS DOMO 1080, 1 COLOR VU, INSTALACION GRATIS', 0, 1, '287.61', '287.61', 'SERVICIO', '0.00'),
(1490, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE MARZO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1491, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE  FEBRERO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1492, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE ENERO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1493, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE MARZO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1494, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE FEBRERO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1495, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE ENERO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1496, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE MARZO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1497, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE FEBRERO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1498, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE ENERO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1499, 1, 'INSTALACION Y CONFIGURACION DE LAS CAMARAS DE SEGURIDAD', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(1500, 1, 'CAMARA MARCA HIKVISION EXTERIOR IP67', 0, 1, '33.00', '33.00', 'SERVICIO', '0.00'),
(1501, 1, 'DVR 16 CANALES MARCA HIKVISON ', 0, 1, '145.00', '145.00', 'SERVICIO', '0.00'),
(1502, 1, 'LECTOR DE CODIGOS DE BARRA 3NSTAR CS100', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(1503, 1, 'GAVINETE DE ACERO 9 ENTRADAS', 0, 1, '225.00', '225.00', 'SERVICIO', '0.00'),
(1504, 1, 'caja de papel termico 80mm, 50 unidades', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1505, 1, 'UPS 600VA ORBITEC, garantia de 1 año', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(1506, 1, 'EQUIPO CORE I5 LENOVO THINKCENTER M92P RAM 8GB DISCO SOLIDO 240GB, garantia 6 meses', 0, 1, '210.00', '210.00', 'SERVICIO', '0.00'),
(1507, 1, 'MONITOR DELL 19.5 DELL CLASE A, garantia de 6 meses, combo teclado y mouse, ', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1508, 1, 'MANO DE OBRA', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(1509, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA  PARA TOMA CORRIENTE', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(1510, 1, 'BOBINA DE CABLE UTP CAT5E INTERIOR', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1511, 1, 'lecotr codigo de barra 3nstar cs100', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(1512, 1, 'FUENTE DE PODER PARA 14 CAMARAS, 12v/20A', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(1513, 1, 'MATERIALES DE INSTALACION Y MANO DE OBRA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1514, 1, 'DVR 32CH HD/CVI/AHD/CVBS DVR 1080P/4MP-5MP LITE 2SATA', 0, 1, '420.00', '420.00', 'SERVICIO', '0.00'),
(1515, 1, 'SOPORTE TECNICO PARA SISTEMA EXTENDIDO DE INVENTARIO Y FACTURACION DEL MES DE MARZO 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1516, 1, 'TINTA PARA IMPRESORA', 0, 1, '8.00', '8.00', 'SERVICIO', '0.00'),
(1517, 1, 'IMPRESORA MATRICIAL EPSON', 0, 1, '225.00', '225.00', 'SERVICIO', '0.00'),
(1518, 1, 'CINTA PARA IMPRESORA EPSON LX350', 0, 1, '7.08', '7.08', 'SERVICIO', '0.00'),
(1519, 1, 'IMPRESORA MATRICIAL EPSON FX890 CLASE A', 0, 1, '199.12', '199.12', 'SERVICIO', '0.00'),
(1520, 1, 'TV SMART 32 PULGADAS ', 0, 1, '275.00', '275.00', 'SERVICIO', '0.00'),
(1521, 1, 'DVR 16 CANALES MARACA HIKVISION ', 0, 1, '145.00', '145.00', 'SERVICIO', '0.00'),
(1522, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '275.00', '275.00', 'SERVICIO', '0.00'),
(1523, 1, 'GAVINETE DE METAL DE 9', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(1524, 1, 'CAMARA EXTERIOR 1080P MARCA HIKVISION', 0, 1, '33.00', '33.00', 'SERVICIO', '0.00'),
(1525, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '275.00', '275.00', 'SERVICIO', '0.00'),
(1526, 1, 'TV SMART 32INCH ', 0, 1, '275.00', '275.00', 'SERVICIO', '0.00'),
(1527, 1, 'GABINETE DE ACERO 9 DIVISIONES', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(1528, 1, 'CAMARA IP67 MARACA HIKVISION 1080P ', 0, 1, '33.00', '33.00', 'SERVICIO', '0.00'),
(1529, 1, 'DVR MARCA HIKVISION  16 CANALES ', 0, 1, '145.00', '145.00', 'SERVICIO', '0.00'),
(1530, 1, 'CABLE ORIGINAL DE CAMARAS CON TERMINALES 20 METROS', 0, 1, '18.00', '18.00', 'SERVICIO', '0.00'),
(1531, 1, 'CABLE ORIGINAL DE CAMARAS CON TERMINALES 15 METROS', 0, 1, '13.00', '13.00', 'SERVICIO', '0.00'),
(1532, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 4 SALIDAS', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(1533, 1, 'CAMARA TURBO DOMO 1080P, 2MPX, LENTE FIJO, PLASTICA, IR 20 MTS, MARCA HIKVISION', 0, 1, '22.00', '22.00', 'SERVICIO', '0.00'),
(1534, 1, 'DISCO DURO 1TB BARRACUDA 3.5 SEAGATE', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1535, 1, '	DISCO DURO 1TB BARRACUDA 3.5 SEAGATE', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1536, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 4 SALIDAS', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(1537, 1, 'CAMARA TURBO DOMO 1080P, 2MPX, LENTE FIJO, IP67  IR 20 MTS, MARCA HIKVISION', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1538, 1, 'CABLE ORIGINAL DE CAMARAS CON TERMINALES 15 METROS', 0, 1, '13.00', '13.00', 'SERVICIO', '0.00'),
(1539, 1, 'CABLE ORIGINAL DE CAMARAS CON TERMINALES 20 METROS', 0, 1, '17.50', '17.50', 'SERVICIO', '0.00'),
(1540, 1, 'MOUSE INALAMBRICO GALO', 0, 1, '8.00', '8.00', 'SERVICIO', '0.00'),
(1541, 1, 'INSTALACION Y MANO DE OBRA ', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(1542, 1, 'COMBO TECLADO Y MOUSE ALAMBRICO', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(1543, 1, 'MONITOR HP DE 22\" CLASE A', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1544, 1, 'EQUIPO LENOVO TINKCENTRE M73,SSD240GB, RAM4GB, SERIE: MJ020X30', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(1545, 1, 'EQUIPO LENOVO TINKCENTRE M73,SSD240GB, RAM4GB, SERIE: MJ00TR4Y', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(1546, 1, 'EQUIPO LENOVO TINKCENTRE M73,SSD240GB, RAM4GB, SERIE: MJ00PGDN', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(1547, 1, 'EQUIPO LENOVO TINKCENTRE M73,SSD240GB, RAM4GB, SERIE: MJ034Z2Z', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(1548, 1, 'COMBO TECLADO Y MOUSE ALAMBRICO', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(1549, 1, 'MONITOR HP DE 22\" CLASE A', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1550, 1, 'LENOVO TINKCENTRE M73, CORE I3 SSD240GB, RAM4GB, SERIE: MJ020X30', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(1551, 1, 'LENOVO TINKCENTRE M73, CORE I3 SSD240GB, RAM4GB, SERIE: MJ00TR4Y', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(1552, 1, 'LENOVO TINKCENTRE M73, CORE I3 SSD 240GB, RAM4GB, SERIE: MJ00PGDN', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(1553, 1, ' LENOVO TINKCENTRE M73, CORE I3 SSD 240GB, RAM4GB, SERIE: MJ034Z2Z', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(1554, 1, 'CAMARA TURBO DOMO 1080P, 2MPX, LENTE FIJO, PLASTICA, IR 20 MTS, MARCA HIKVISION', 0, 1, '27.00', '27.00', 'SERVICIO', '0.00'),
(1555, 1, 'REVISION DE CAMARAS ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(1556, 1, ' rollos de papel termico 57mm', 0, 1, '1.24', '1.24', 'SERVICIO', '0.00'),
(1557, 1, 'impresor termico bluetooth portatil 2 rollos de papel termico 57mm', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(1558, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1559, 1, 'BOBINA DE CABLE UTP CAT5', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(1560, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1561, 1, 'BOBINA DE CABLE UTP CAT5', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(1562, 1, 'CAJA DE PAPEL TERMICO 80MM, 50 UNIDADES', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1563, 1, 'MANO DE OBRA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1564, 1, 'MATERIALES DE INSTALACION', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1565, 1, 'DISCO DURO PURPLE SURVEILLANCE WD40PURZ 4 TB', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(1566, 1, 'GABINETE DE 9U 19 PULGADAS NEGRO', 0, 1, '220.00', '220.00', 'SERVICIO', '0.00'),
(1567, 1, 'DVR 4 MEGAPIXEL (1080P) LITE / 8 CANALES TURBOHD AUDIO POR COAXITRON', 0, 1, '130.00', '130.00', 'SERVICIO', '0.00'),
(1568, 1, 'MANO DE OBRA', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1569, 1, 'MATERIALES DE INSTALACION ', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1570, 1, 'GABINETE DE 9U 19 PULGADAS NEGRO', 0, 1, '220.00', '220.00', 'SERVICIO', '0.00'),
(1571, 1, 'DISCO DURO PURPLE SURVEILLANCE WD40PURZ 4 TB', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(1572, 1, 'DVR 2 MEGAPIXEL (1080P) LITE / 8 CANALES TURBOHD AUDIO POR COAXITRON', 0, 1, '130.00', '130.00', 'SERVICIO', '0.00'),
(1573, 1, 'DVR SLIM TRUBO 8 CANALES, RESOLUCION MAXIMA 1080P, MARCA HIKVISION', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(1574, 1, 'INSTALACION DE CAMARAS', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1575, 1, 'DESINTALACION DE CAMARAS ', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1576, 1, 'DVR SLIM TRUBO 16 CANALES, RESOLUCION MAXIMA 1080P, MARCA HIKVISION', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(1577, 1, 'UPS FORZA 750 V', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1578, 1, 'UPS FORZA 750', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1579, 1, 'UPS FORZA 750V', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1580, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES MARZO DE F DEL 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1581, 1, 'REGLETA ', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(1582, 1, '	DISCO DURO 2 TERAS 3.5', 0, 1, '91.50', '91.50', 'SERVICIO', '0.00'),
(1583, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 4 SALIDAS', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(1584, 1, 'CAMARA 1080, LENTE FIJO, IP67, IR 20 MTS, AUDIO MARCA HIKVISION', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(1585, 1, 'HIK - 8CH HD/AHD/ANALOG DVR HD1080P LITE 1 SATA 1 RJ45 100M 	 	 	 	', 0, 1, '82.00', '82.00', 'SERVICIO', '0.00'),
(1586, 1, 'Cámara Domo Turbo 5 Mpx, lente fijo, IR de 20M,plástica, 4 híbrida, IP 67 marca Hikvision', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(1587, 1, 'Dvr Slim Turbo 4 canales, resolución máxima 5MP marca Hikvision', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1588, 1, 'DISCO DURO SEAGATE BARRACUDA', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1589, 1, 'ADAPTADOR DC MACHO/HEMBRA CCTV', 0, 1, '1.25', '1.25', 'SERVICIO', '0.00'),
(1590, 1, 'Cable UTP CAT5 ', 0, 1, '0.30', '0.30', 'SERVICIO', '0.00'),
(1591, 1, 'EVALUACIONES POLIGRAFICAS  PRE EMPLEO SAN MIGUEL', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(1592, 1, 'MANTEMINIENTO PREVENTIVO DE EQUIPO INFORMATICO Y SISTEMA DE VIDEO VIGILANCIA SUCURSAL CENTRAL', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1593, 1, 'MANTEMINIENTO PREVENTIVO DE EQUIPO INFORMATICO Y SISTEMA DE VIDEO VIGILANCIA SUCURSAL LAS MARIAS', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1594, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION DE CAMARAS DE VIGILANCIA', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1595, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '225.00', '225.00', 'SERVICIO', '0.00'),
(1596, 1, 'METRO DE CABLE UTP CAT 5', 0, 1, '0.30', '0.30', 'SERVICIO', '0.00'),
(1597, 1, 'METRO DE CABLE UTP CAT5', 0, 1, '0.30', '0.30', 'SERVICIO', '0.00'),
(1598, 1, '	FUENTE DE PODER 5 AMP CON XPLITER DE 4 SALIDAS', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1599, 1, 'CAMARA BALA TURBO 1080P 2.8MM IR 20M PLASTICO IP67', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(1600, 1, 'instalacion y configuracion de camaras de seguridad ', 0, 1, '220.00', '220.00', 'SERVICIO', '0.00'),
(1601, 1, 'DELL SFF DT VD 3681 i3--10100 4G1T W10P DVD SPAN', 0, 1, '844.68', '844.68', 'SERVICIO', '0.00'),
(1602, 1, 'MonitorAOC LED  19.5IN  WIDE VGA HDM 1600x900 I BLACK 20E1H', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1603, 1, 'DELL SFF DT VD 3681 i3--10100 4G1T W10P DVD SPAN', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1604, 1, 'CAJA DE EFECTIVO ELECTRONICA 3NSTAR', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1605, 1, '	IMPRESORA TERMICA DE RED PARA TICKET 3NSTAR', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(1606, 1, 'HP 15-DY2060LA - NOTEBOOK - 15 INTEL CORE I3 1125G4', 0, 1, '530.97', '530.97', 'SERVICIO', '0.00'),
(1607, 1, 'DESARROLLO DE APLICACIÓN PARA PUNTO DE COBRO DE TASAS MUNICIPALES', 0, 1, '1200.00', '1200.00', 'SERVICIO', '0.00'),
(1608, 1, 'IMPRESOR FX-890 EPSON CLASE A', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(1609, 1, 'MPRESOR MULTIFUNCIONAL EPSON L3150 PRINTER - COPIER - SCANNER', 0, 1, '260.00', '260.00', 'SERVICIO', '0.00'),
(1610, 1, 'IMPRESOR MULTIFUNCIONAL EPSON L3150 PRINTER - COPIER - SCANNER', 0, 1, '260.00', '260.00', 'SERVICIO', '0.00'),
(1611, 1, 'IMPRESOR MULTIFUNCIONAL EPSON L3150 PRINTER -COPIER - SCANNER', 0, 1, '260.00', '260.00', 'SERVICIO', '0.00'),
(1612, 1, 'CPU LENOVO THINKCENTRE M73 CORE I3 RAM 8GB SSD 240G', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(1613, 1, 'MONITOR DELL 22 PULGADAS CLASE A', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1614, 1, 'COMBO TECLADO MOUSE KLIP XTREME KCK-251S USB', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(1615, 1, 'CPU LENOVO THINKCENTRE M73 CORE I3 RAM 8GB SSD 240G', 0, 1, '138.36', '138.36', 'SERVICIO', '0.00'),
(1616, 1, 'caja de papel termico 80mm', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1617, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES DE MARZO 2022', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1618, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES DE ABRIL 2022', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1619, 1, 'KIT DE 4 CAMARAS DE SEGURIDAD', 0, 1, '275.00', '275.00', 'SERVICIO', '0.00'),
(1620, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1621, 1, 'METROS DE CABLE', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1622, 1, 'DVR 4 CANALES 2MP MARCA HIKVISION', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1623, 1, 'FUENTE DE ALIMENTACION DE 4 SALIDAS', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1624, 1, 'CAMARA BULLET1080P MARCA HIKVISION IP67', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1625, 1, 'CAMARA DOMO 1080P MARCA HIKVISION IP66', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1626, 1, 'DISCO DURO SEAGATE BARRACUDA 1 TB', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1627, 1, 'ADAPTADOR MACHO/HEMBRA', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1628, 1, 'VIDEO BALUN', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1629, 1, 'MONITOR DE 19 PULGADAS LENOVO', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(1632, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1633, 1, 'DISCO DURO SEAGATE 1 TB', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1634, 1, 'DVR 4 CANALES 2MP MARCA HIKVISION', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1635, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 4 SALIDAS', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(1636, 1, 'VIDEO BALUN', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(1640, 1, 'instalacion y configuracion del equipo', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1641, 1, ' Lenovo think centre m73 core  i3 memoria interna de 4 ram disco solido de 240', 0, 1, '215.00', '215.00', 'SERVICIO', '0.00'),
(1642, 1, 'Lenovo think centre m73 core i3 memoria  de 4GB ram disco solido de 240GB', 0, 1, '215.00', '215.00', 'SERVICIO', '0.00'),
(1643, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE ABRIL DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1644, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE ABRIL DE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(1645, 1, 'PAGO DE PUBLICIDAD FACEBOOK CORRESPONDIENTE AL MES DE ABRIL 2022', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(1646, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE ABRIL 2022', 0, 1, '323.01', '323.01', 'SERVICIO', '0.00'),
(1647, 1, 'rollo de papel termico 80mm', 0, 1, '1.77', '1.77', 'SERVICIO', '0.00'),
(1648, 1, 'CAJA DE EFECTIVO 3NSTAR', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1649, 1, 'CAJA DE EFECTIVO 3NSTAR ', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1650, 1, 'UPS  750V MARCA ORBITEC ', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1651, 1, 'FUENTE DE 1A INDIVIDUAL PARA CAMARAS DE SEGURIDAD', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(1652, 1, 'DISCO DURO DE 2TB SEAGATE BARRACUDA 3.5 INCH', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(1653, 1, 'HIK - 8CH HD/AHD/ANALOG DVR HD1080P LITE 1 SATA 1 RJ45 100M', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(1654, 1, '15 METROS DE CABLE ORIGINAL PARA CAMARAS DE VIDEO SEGURIDAD', 0, 1, '13.00', '13.00', 'SERVICIO', '0.00'),
(1655, 1, 'CAMARA BULLET  1080P OUTDOOR IP67 / EXIR 20M  MARCA HIKVISION', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1656, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ABRIL DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1657, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE ABRIL DE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(1658, 1, 'Activación de paquete ofimática y actualización de sistema', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(1659, 1, ' Instalación y configuración de sistema Operativo y paquete de offimatica', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1660, 1, 'Actualización y optimización de sistema operativo', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(1661, 1, 'Mantenimiento preventivo y correctivo a impresora de tinta continua', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(1662, 1, 'ALOJAMIENTO DE APLICACIÓN PARA PUNTO DE COBRO CON SOPORTE TECNICO CON FRECUENCIA MENSUAL', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1663, 1, 'Caja de papel termico 80mm', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(1664, 1, 'IMPRESOR MULTIFUNCIONAL EPSON L3210 PRINTER -COPIER - SCANNER', 0, 1, '230.09', '230.09', 'SERVICIO', '0.00'),
(1665, 1, 'MANO DE OBRA, PARA RETIRO DE CAMARAS DE VIGILANCIA', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(1666, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES ABRIL DE 2022', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(1667, 1, 'MANO DE OBRA, PARA RETIRO DE CAMARAS DE VIGILANCIA', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(1668, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES ABRIL DE 2022', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(1669, 1, 'IMPRESOR MATRICIAL EPSON Lx350 CLASE A  GARANTIA 6 MESES', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(1670, 1, '	UPS MARCA ORBITEC DE 750 VA', 0, 1, '71.50', '71.50', 'SERVICIO', '0.00'),
(1671, 1, 'KIT DE 4 CAMARAS 1080P, MARCA HIKVISION, CON INSTALACION GRATIS', 0, 1, '309.74', '309.74', 'SERVICIO', '0.00'),
(1672, 1, 'UPS 750V MARCA ORBITEC', 0, 1, '48.68', '48.68', 'SERVICIO', '0.00'),
(1673, 1, 'BANDEJA DE ACERO EMPOTRABLE EN PARED', 0, 1, '26.54', '26.54', 'SERVICIO', '0.00'),
(1674, 1, 'ALOJAMIENTO DE APLICACIÓN PARA PUNTO DE COBRO, GRATIS POR EL PERIODO DE UN AÑO', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1675, 1, 'UPS ORBITEC DE 750 VA', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1676, 1, 'MONITOR AOC LED 19.5IN WIDE VGA HDM 1600X900 I BLACK 20E1H', 0, 1, '210.00', '210.00', 'SERVICIO', '0.00'),
(1677, 1, 'DELL OP3080 SFF C81YR DT SPA CI5-10500', 0, 1, '1320.02', '1320.02', 'SERVICIO', '0.00'),
(1678, 1, 'instalacion y configuracion del equipo	', 0, 1, '21.75', '21.75', 'SERVICIO', '0.00'),
(1679, 1, 'Lenovo think centre m73 core i3 memoria de 4GB ram disco solido de 240GB', 0, 1, '190.64', '190.64', 'SERVICIO', '0.00'),
(1680, 1, 'ANTICIPO DEL 30% PARA EL DESAROLLO DE SISTEMA INFORMATICO FINANCIERO ', 0, 1, '6626.55', '6626.55', 'SERVICIO', '0.00'),
(1681, 1, 'ANTICIPO DEL 30% PARA EL DESARROLLO DE SISTEMA INFORMATICO FINANCIERO', 0, 1, '6596.46', '6596.46', 'SERVICIO', '0.00'),
(1682, 1, 'CPU LENOVO M73 icore 3 DISCO SOLIDO 240GB MEMORIA RAM DE 4GB', 0, 1, '185.00', '185.00', 'SERVICIO', '0.00'),
(1683, 1, 'COMBO DE MOUSE  Y TECLADO XTK160S', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(1684, 1, 'MONITOR ASUS 19 PULGADAS ', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(1685, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES ABRIL  DEL 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1686, 1, 'SOPORTE TECNICO PARA SISTEMA EXTENDIDO DE INVENTARIO Y FACTURACION DEL MES DE MARZO 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1687, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE MARZO 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(1688, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE FEBRERO 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(1689, 1, 'ANTONIO DE JESUS GUZMAN FUENTES', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1690, 1, 'ROSA MAGDALENA GARAY DE ARDON ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1691, 1, 'MARTHA LILIAN COREAS HERNANDEZ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1692, 1, 'JUANA MARGARITA MEDRANO GUZMAN', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1693, 1, '15 PERSONAS SOMETIDAS A LA EVALUACION  POLIGRAFICA DE PRE-EMPLEO ', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1694, 1, 'MATERIALES DE INSTALACION', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1695, 1, 'DESARROLLO DE APLICACIÓN PARA PORTAL CAUTIVO ', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1696, 1, 'KIT DE 4 CAMARAS DE VIDEO DE SEGURIDAD', 0, 1, '425.00', '425.00', 'SERVICIO', '0.00'),
(1697, 1, 'GARANTIA DE DOCE MESES', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1698, 1, 'DISCO DURO SEAGATE DE 1TB', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1699, 1, 'DVR DE 4 CANALES MARCA HIKVISION', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1700, 1, 'FUENTE DE PODER DE 4 SPLITTER', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1701, 1, 'ADAPTAPTADO MACHO Y HEMBRA ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1702, 1, 'VIDEO BALUN ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1703, 1, 'CAMARA BULLET 1080P', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1704, 1, 'CAMARA FULL COLOR VU', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1705, 1, 'CONFIGURACION E INTSTALACION DE CAMARAS DE SEGURIDAD ', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1706, 1, 'CABLE UTP DE RED CAT 5', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(1707, 1, '	FUENTE DE PODER CON SPLITTER DE 4 SALIDAS', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1708, 1, 'CAMARA BULLET CAMARA 1080P OUTDOOR IP67 / EXIR 20M', 0, 1, '27.00', '27.00', 'SERVICIO', '0.00'),
(1709, 1, 'ROLLO DE PAPEL TERMICO 80MM', 0, 1, '1.99', '1.99', 'SERVICIO', '0.00'),
(1710, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1711, 1, 'METRO DE CABLE UTP CAT 5', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(1712, 1, 'DVR 8CH HD/AHD/ANALOG DVR 1080P/2MP LITE 1SATA 1 RJ45 100M', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1713, 1, '	CAMARA BULLET CAMARA 1080P OUTDOOR IP67 / EXIR 20M ', 0, 1, '27.00', '27.00', 'SERVICIO', '0.00'),
(1714, 1, 'UPS MARCA FORZA 2200 VA', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1715, 1, 'MONITOR MARCA  BEMATCH PANTALLA TOUCH 15 PULGADAS ', 0, 1, '353.99', '353.99', 'SERVICIO', '0.00'),
(1716, 1, 'CABLE PRE ARMADO DE 20 METROS', 0, 1, '16.00', '16.00', 'SERVICIO', '0.00'),
(1717, 1, 'DVR 4CH HD/AHD/ANALOG DVR 1080P/2MP LITE 1SATA 1 RJ45 100M', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(1718, 1, 'DISCO DURO 3.5 1T SEAGATE BARRACUDA', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(1719, 1, 'CAMARA FULL COLOR 24/7 CON AUDIO INTEGRADO ', 0, 1, '50.50', '50.50', 'SERVICIO', '0.00'),
(1720, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1721, 1, 'METRO DE CABLE DE RED CAT 5 ', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(1722, 1, 'CAMARA DOMO MARCA HIKVISION 1080P', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1723, 1, 'CAMARA FULL COLOR 24/7 MARCA HIKVISION  1080P', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1724, 1, 'ANTICIPO DEL 30% PARA EL DESARROLLO DE SISTEMA INFORMATICO FINANCIERO', 0, 1, '6600.00', '6600.00', 'SERVICIO', '0.00'),
(1725, 1, 'DVR DE 16 CANALES MARCA HIKVISION 2M', 0, 1, '135.00', '135.00', 'SERVICIO', '0.00'),
(1726, 1, 'CAMARA COLOR FULL COLOR 24/7', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1727, 1, 'UPS 750V MARCA ORBITEC ', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1728, 1, '20 METROS DE CABLE PRE-ARMADO  PARA CAMARAS DE VIDEO SEGURIDAD', 0, 1, '16.00', '16.00', 'SERVICIO', '0.00'),
(1729, 1, 'CAMARA BULLET 1080P OUTDOOR IP67 / EXIR 20M MARCA HIKVISION', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1730, 1, 'DISCO DURO DE 2TB SEAGATE BARRACUDA 3.5 INCH', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(1731, 1, 'FUENTE DE 1A INDIVIDUAL PARA CAMARAS DE SEGURIDAD', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(1732, 1, 'MANTENIMIENTO Y CAMBIO DE ACCESORIOS', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(1733, 1, 'VIDEO BALUN', 0, 1, '6.00', '6.00', 'SERVICIO', '0.00'),
(1734, 1, 'CAJA DE PAPEL TERMICO 80MM 50 UNIDADES', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(1735, 1, '	COMBO TECLADO Y MOUSE ALAMBRICO', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(1736, 1, 'MONITOR HP DE 22\" CLASE A', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1737, 1, '	EQUIPO LENOVO TINKCENTRE M73,SSD240GB, RAM4GB, SERIE: MJ02CX3U', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(1738, 1, 'EQUIPO LENOVO TINKCENTRE M73,SSD240GB, RAM4GB, SERIE: MJ00TR4Y', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(1739, 1, 'EQUIPO LENOVO TINKCENTRE M73,SSD240GB, RAM4GB, SERIE: MJ00PGDN', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(1740, 1, 'EQUIPO LENOVO TINKCENTRE M73,SSD240GB, RAM4GB, SERIE: MJ034Z2Z', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(1741, 1, 'IMPRESORA TERMICA MARCA BEMATECH LR2000', 0, 1, '150.43', '150.43', 'SERVICIO', '0.00'),
(1742, 1, 'CAJA DE EFECTIVO MARCA 3NSTAR', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(1743, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1744, 1, 'METRO DE CABLE UTP CAT 75', 0, 1, '0.85', '0.85', 'SERVICIO', '0.00'),
(1745, 1, '	CAMARA BULLET CAMARA 1080P OUTDOOR IP67 / EXIR 20M *4 IN 1', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1746, 1, 'DVR 4CH HD/AHD/ANALOG DVR 1080P/2MP LITE 1SATA 1 RJ45 100M', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(1747, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1748, 1, 'CAMARA FULL COLOR 24/7 AUDIO MARCA HIKVISION', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1749, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1750, 1, 'CAMARA TURBO BALA 1080, LENTE FIJO, IP66, IR 20 MTS, MARCA HIKVISION', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1751, 1, 'COMBO DE MOUSE Y TECLADO XTK16OS', 0, 1, '12.00', '12.00', 'SERVICIO', '0.00'),
(1752, 1, 'CAMARA MARCA XIAOMI 1080P   MEMORIA DE 32GB E INSTALACION Y CONFIGURACION ', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1753, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE ABRIL', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(1754, 1, 'CHASIS', 0, 1, '26.55', '26.55', 'SERVICIO', '0.00'),
(1755, 1, 'DISCO SOLIDO DE 240GB ', 0, 1, '48.67', '48.67', 'SERVICIO', '0.00'),
(1756, 1, 'EQUIPO LENOVO THINKCENTRE M73, DISCO SOLIDO DE 240GB, MONITOR CLASE A MOUSE Y TECLADO', 0, 1, '238.94', '238.94', 'SERVICIO', '0.00'),
(1757, 1, 'REVISION DE RELOJ BIOMETRICO', 0, 1, '25.62', '25.62', 'SERVICIO', '0.00'),
(1758, 1, 'SOPORTE TECNICO DE PLANTA DE RED Y TELEFONIA', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1759, 1, 'CAMBIO DE CONECTORES RJ45 Y CABLEADO', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(1760, 1, 'UPS   de 750VA Marca ORBITEC Modelo TC-7508 Garantía 12 meses', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1761, 1, 'Impresora: Térmica de Red para Ticket,   Marca 3NSTAR Modelo RPT005 Garantía 12 meses', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(1762, 1, 'Caja de efectivo Electrónica Marca 3NSTAR ,  Modelo CD350 Garantía de  12 meses', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1763, 1, 'LG-8 5 / 2 0 2 2 A M S M  ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1764, 1, 'UPS   de 750VA Marca ORBITEC Modelo TC-7508 Garantía 12 meses', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1765, 1, 'Impresora: Térmica de Red para Ticket,   Marca 3NSTAR Modelo RPT005 Garantía 12 meses', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(1766, 1, 'Caja de efectivo Electrónica Marca 3NSTAR ,  Modelo CD350 Garantía de  12 meses', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1767, 1, 'LG-8 5 / 2 0 2 2 A M S M  ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1768, 1, 'UPS   de 750VA Marca ORBITEC Modelo TC-7508 Garantía 12 meses', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1769, 1, 'Impresora: Térmica de Red para Ticket,   Marca 3NSTAR Modelo RPT005 Garantía 12 meses', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(1770, 1, 'Caja de efectivo Electrónica Marca 3NSTAR ,  Modelo CD350 Garantía de  12 meses', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1771, 1, 'LG-8 5 / 2 0 2 2 A M S M  ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1772, 1, 'UPS   de 750VA Marca ORBITEC Modelo TC-7508 Garantía 12 meses', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1773, 1, 'Impresora: Térmica de Red para Ticket,   Marca 3NSTAR Modelo RPT005 Garantía 12 meses', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(1774, 1, 'Caja de efectivo Electrónica Marca 3NSTAR ,  Modelo CD350 Garantía de  12 meses', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1775, 1, 'LG-8 5 / 2 0 2 2 A M S M  ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1776, 1, 'UPS   de 750VA Marca ORBITEC Modelo TC-7508 Garantía 12 meses', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1777, 1, 'Impresora: Térmica de Red para Ticket,   Marca 3NSTAR Modelo RPT005 Garantía 12 meses', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(1778, 1, 'Caja de efectivo Electrónica Marca 3NSTAR ,  Modelo CD350 Garantía de  12 meses', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1779, 1, 'LG-8 5 / 2 0 2 2 A M S M  ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1780, 1, 'UPS   de 750VA Marca ORBITEC Modelo TC-7508 Garantía 12 meses', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1781, 1, 'Impresora: Térmica de Red para Ticket,   Marca 3NSTAR Modelo RPT005 Garantía 12 meses', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(1782, 1, 'Caja de efectivo Electrónica Marca 3NSTAR ,  Modelo CD350 Garantía de  12 meses', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1783, 1, 'LG-8 5 / 2 0 2 2 A M S M  ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1784, 1, 'UPS   de 750VA Marca ORBITEC Modelo TC-7508 Garantía 12 meses', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1785, 1, 'Impresora: Térmica de Red para Ticket,   Marca 3NSTAR Modelo RPT005 Garantía 12 meses', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(1786, 1, 'Caja de efectivo Electrónica Marca 3NSTAR ,  Modelo CD350 Garantía de  12 meses', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1787, 1, 'LG-8 5 / 2 0 2 2 A M S M  ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1788, 1, 'UPS   de 750VA Marca ORBITEC Modelo TC-7508 Garantía 12 meses', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1789, 1, 'Impresora: Térmica de Red para Ticket,   Marca 3NSTAR Modelo RPT005 Garantía 12 meses', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(1790, 1, 'Caja de efectivo Electrónica Marca 3NSTAR ,  Modelo CD350 Garantía de  12 meses', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1791, 1, 'LG-8 5 / 2 0 2 2 A M S M  ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1792, 1, 'UPS   de 750VA Marca ORBITEC Modelo TC-7508 Garantía 12 meses', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1793, 1, 'Impresora: Térmica de Red para Ticket,   Marca 3NSTAR Modelo RPT005 Garantía 12 meses', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(1794, 1, 'Caja de efectivo Electrónica Marca 3NSTAR ,  Modelo CD350 Garantía de  12 meses', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1795, 1, 'LG-8 5 / 2 0 2 2 A M S M  ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1796, 1, 'UPS   de 750VA Marca ORBITEC Modelo, TC-7508 Garantía 12 meses', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1797, 1, 'Impresora térmica de red para Ticket     Marca 3NSTAR, Modelo  RPT005 Garantía 12 meses', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(1798, 1, 'Caja de efectivo electrónica Marca 3NSTAR ,  Modelo CD350 Garantía de  12 meses', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1799, 1, 'LG-8 5 / 2 0 2 2 A M S M  ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1800, 1, 'UPS  de 750 VA,  Marca ORBITEC,  Modelo TC-7508, Garantía 12 meses', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1801, 1, 'Impresora térmica de red para ticket,  Marca 3NSTAR Modelo RPT005 Garantía 12 meses', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(1802, 1, 'Caja de efectivo electrónica, Marca 3NSTAR ,  Modelo CD350 Garantía de  12 meses', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1803, 1, 'LG-8 5 / 2 0 2 2 A M S M  ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1804, 1, 'UPS de 750VA Marca ORBITEC Modelo, TC-7508 Garantía 12 meses', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1805, 1, 'Impresora térmica de red para Ticket Marca 3NSTAR, Modelo RPT005 Garantía 12 meses', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(1806, 1, 'Caja de efectivo electrónica Marca 3NSTAR , Modelo CD350 Garantía de 12 meses', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1807, 1, 'LG-8 5 / 2 0 2 2 A M S M ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1808, 1, 'Sistema  desarrollo  de  aplicación  para  punto  de cobro de tasas municipales', 0, 1, '1200.00', '1200.00', 'SERVICIO', '0.00'),
(1809, 1, 'LG-8 5 / 2 0 2 2 A M S M ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1810, 1, 'Impresor, MarcaEPSON, Modelo FX-890 Garantíade 12 meses', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(1811, 1, 'Computadora de escritorio, Marca DELL, Modelo 0P3080, Garantía de 12 meses', 0, 1, '844.68', '844.68', 'SERVICIO', '0.00'),
(1812, 1, 'Monitor, Marca AOC LED Modelo 20E1H Garantía 12 meses', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1813, 1, 'LG-8 5 / 2 0 2 2 A M S M ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1814, 1, 'IMPRESORA TERMICA DE TICKET 58MM', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(1815, 1, 'IMPRESORA TERMICA DE TICKET 58MM GOJT', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1816, 1, 'SISTEMA CONTABLE DE INVENTARIO Y FACTURACIO MAS PUNTO DE VENTA.  ', 0, 1, '1000.00', '1800.00', 'SERVICIO', '0.00'),
(1817, 1, 'SISTEMA  CONTABLE DE INVENTARIO Y FACTURACION', 0, 1, '785.00', '785.00', 'SERVICIO', '0.00'),
(1818, 1, 'CPU MARCA LENOVO THINKCENTRE M73 I CORE 3 CON DISCO SOLIDO DE 240GB ROM Y RAM DE 4 GB, GARANTIA DE 12 MESES', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1819, 1, 'IMPRESORA MATRICIAL, MODELO FX-890, GARANTIA DE 12 MESES', 0, 1, '350.00', '350.00', 'SERVICIO', '0.00'),
(1820, 1, 'CAJA DE EFECTIVO, MARCA 3NSTAR , GARANTIA DE 12 MESES', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1821, 1, 'LECTOR DE BARRAS, MARCA 3NSTAR, GARANTIA DE 12 MESES', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1822, 1, 'IMPRESOR TERMICO, MARCA BEMATECH, GARANTIA DE 12 MESES', 0, 1, '180.00', '180.00', 'SERVICIO', '0.00'),
(1823, 1, 'MONITOR , MARCA ASUS, GARANTIA DE 12 MESES', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1824, 1, 'MOUSE Y TECLADO MARCA XTK160S GARANTIA DE 12 MESES	', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(1825, 1, 'Impresor Matrícial fx 890 ', 0, 1, '185.84', '185.84', 'SERVICIO', '0.00'),
(1826, 1, 'CPU LENOVO  I5  DISCO ESTADO SOLIDO  DE 240 GB MAS 8 GB DE RAM', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(1827, 1, 'MOUSE Y TECLADO ', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(1828, 1, 'MONITOR CLASE A 22 PULGADAS ', 0, 1, '15.49', '15.49', 'SERVICIO', '0.00'),
(1829, 1, 'SITEMA DE INVENTARIO Y FACTURACION,  12 MESES DE GARANTIA', 0, 1, '500.00', '500.00', 'SERVICIO', '0.00'),
(1830, 1, 'MONITOR VGA CLASE A 19.5 PULGADAS,  12 MESES DE GARANTIA', 0, 1, '68.87', '68.87', 'SERVICIO', '0.00'),
(1831, 1, 'IMPRESORA DE TICKET MARCA BEMATECH LR2000,  12 MESES DE GARANTIA', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1832, 1, 'LECTOR DE BARRA  MARCA  3NSTAR ,  12 MESES DE GARANTIA', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1833, 1, 'CPU LENOVO  THINKCENTRE M73 CORE I3 DISCO SOLIDO DE 240 GB MEMORIA DE 4GB, 12 MESES DE GARANTIA', 0, 1, '183.78', '183.78', 'SERVICIO', '0.00'),
(1834, 1, 'CAJA DE DINERO MARCA 3NSTAR, 12 MESES DE GARANTIA', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1835, 1, 'Sistema desarrollo de aplicación para punto de cobro de tasas municipales', 0, 1, '1200.00', '1200.00', 'SERVICIO', '0.00'),
(1836, 1, 'LG-8 5 / 2 0 2 2 A M S M ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1837, 1, 'Sistema desarrollo de aplicación para punto de cobro de tasas municipales', 0, 1, '1200.00', '1200.00', 'SERVICIO', '0.00'),
(1838, 1, 'LG-8 5 / 2 0 2 2 A M S M ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1839, 1, 'Sistema desarrollo de aplicación para punto de cobro de tasas municipales', 0, 1, '1200.00', '1200.00', 'SERVICIO', '0.00'),
(1840, 1, 'LG-8 5 / 2 0 2 2 A M S M ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1841, 1, 'UPS de 750 VA, Marca ORBITEC, Modelo TC-7508, Garantía 12 meses', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1842, 1, 'Impresora térmica de red para ticket, Marca 3NSTAR Modelo RPT005 Garantía 12 meses', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(1843, 1, 'Caja de efectivo electrónica Marca 3NSTAR , Modelo CD350 Garantía de 12 meses', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1844, 1, 'LG-8 5 / 2 0 2 2 A M S M ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(1845, 1, 'Impresor, Marca EPSON, Modelo FX-890 Garantíade 12 meses', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(1846, 1, 'Computadora de escritorio, Marca DELL, Modelo 0P3080, Garantía de 12 meses', 0, 1, '844.68', '844.68', 'SERVICIO', '0.00'),
(1847, 1, 'Monitor, Marca AOC LED Modelo 20E1H Garantía 12 meses', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1848, 1, 'INSTALACION Y CONFIGURACION  DE CAMARAS DE SEGURIDAD', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1849, 1, 'METRO DE CABLE UTP CAT 5', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(1850, 1, 'CAMARA BULLET CAMARA 1080P OUTDOOR IP67 / EXIR 20M *4 IN 1', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1851, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 4 SALIDAS', 0, 1, '28.00', '28.00', 'SERVICIO', '0.00'),
(1852, 1, 'METRO DE CABLE UTP CAT 5 INTEMPERIE', 0, 1, '0.85', '0.85', 'SERVICIO', '0.00'),
(1853, 1, 'METRO DE CABLE UTP CAT 5 ', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(1854, 1, '	CAMARA BULLET CAMARA 1080P OUTDOOR IP67 / EXIR 20M *4 IN 1', 0, 1, '28.00', '28.00', 'SERVICIO', '0.00'),
(1855, 1, 'DISCO DURO SEAGATE BARRACUDA 1TB', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(1856, 1, 'BOBINA DE CABLE UTP CAT 5 INTERIOR 305MT', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1857, 1, 'CAMARA DOMO 1080P MARCA HIKVISION  2 MP', 0, 1, '22.00', '22.00', 'SERVICIO', '0.00'),
(1858, 1, 'CAMARA BULLET CAMARA 1080P OUTDOOR IP67 / EXIR 20M *4 IN 1', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1859, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 4 SALIDAS', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1860, 1, 'CPU MARCA LENOVO THINKCENTRE M73 CORE I3', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1861, 1, 'Impresor Matricial clase A Fx-890', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(1862, 1, '20 METROS DE CABLE PRE-ARMADO  PARA CAMARASDE VIDEO SEGURIDAD', 0, 1, '16.00', '16.00', 'SERVICIO', '0.00'),
(1863, 1, 'DVR DE 4 CH RESOLUCION DE  2MP, MARCA HIKVISION,', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(1864, 1, 'DISCO DURO DE 1TB SEAGATE BARRACUDA 3.5 INC', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(1865, 1, 'FUENTE DE 1A INDIVIDUAL PARA CAMARAS DESEGURIDAD', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(1866, 1, 'CAMARA BULLET 1080P OUTDOOR IP67 / EXIR 20MMARCA HIKVISION', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1867, 1, 'CAMARA FULL COLOR 24/7, MARCA HIKVISION', 0, 1, '47.50', '47.50', 'SERVICIO', '0.00'),
(1868, 1, 'IMPRESOR MATRICIAL CLASE A Fx-890', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(1869, 1, 'CPU MARCA LENOVO THINKCENTRE M73 CORE I3', 0, 1, '141.59', '141.59', 'SERVICIO', '0.00'),
(1870, 1, 'CAMARA FULL COLOR 24/7 MARCA HIKVISION', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(1871, 1, 'IMPRESORA DE TICKET MARCA 3NSTAR, GARANTIA DE 12 MESES', 0, 1, '145.00', '145.00', 'SERVICIO', '0.00'),
(1872, 1, 'UPS 600V MARCA ORBITEC, GARANTIA DE 12 MESES', 0, 1, '38.10', '38.10', 'SERVICIO', '0.00'),
(1873, 1, 'LECTOR DE BARRA 3NSTAR, GARANTIA DE 12 MESES', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(1874, 1, 'CAJA DE EFECTIVO MARCA 3NSTAR, GARANTIA DE 12 MESES', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(1875, 1, 'CPU LENOVO THINK CENTRE  CORE I3  MEMORIA DE 240 GARANTIA DE 12 MESES', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(1876, 1, 'MONITOR CLASE A 19.5 GARANTIA DE 12 MESES', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(1877, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1878, 1, 'DISCO DURO SEAGATE BARRACUDA CAPACIDAD DE 1TB', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(1879, 1, 'UPS 750VA ORBITEC', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(1880, 1, 'BOBINA DE CABLE INTEMPERIE CAT 5 ', 0, 1, '130.00', '130.00', 'SERVICIO', '0.00'),
(1881, 1, 'CAMARA BULLET CAMARA 1080P OUTDOOR IP67 / EXIR 20M *4 IN 1', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1882, 1, 'CAMARA FULL COLOR 24  /7  MARCA HIKVISION', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(1883, 1, 'Impresor matricial clase fx-890', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(1884, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1885, 1, 'METRO DE CABLE UTP CAT 5 ', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(1886, 1, 'CAMARA FULL COLOR 24 /7 MARCA HIKVISION', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(1887, 1, 'COMBO DE MOUSE Y TECLADO XTK160S', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(1888, 1, 'CPU LENOVO THINKCENTRE M73 CORE I3 CAPACIDAD DE MEMORIA INTERNA 960GB Y 8 GB DE MEMORIA RAM', 0, 1, '340.00', '340.00', 'SERVICIO', '0.00'),
(1889, 1, 'MONITOR 19.5 CLASE A', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(1890, 1, 'COMBO DE MOUSE Y TECLADO XTK160', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(1891, 1, 'MONITOR 19.5 CLASE A', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(1892, 1, 'CPU LENOVO THINKCENTRE M73 CORE I5 CAPACIDADDE MEMORIA INTERNA 960GB Y 8 GB DE MEMORIA RAM', 0, 1, '400.00', '400.00', 'SERVICIO', '0.00'),
(1893, 1, 'LECTORA BARRA 3NSTAR POS-CS100  W/BASE LECTURA', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1894, 1, 'CAJA DE PAPEL TERMICO 80MM 50 UNIDADES', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1895, 1, 'IMPRESOR POS 3NSTAR RPT008 DIRECT THERMALPRINTER', 0, 1, '154.86', '154.86', 'SERVICIO', '0.00'),
(1896, 1, 'CAJA DE DINERO BEMATECH, TIEMPO DE GARANTIA: 1 AÑO', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(1897, 1, 'PAGO DE 2NDA  CUOTA POR EQUIPO DE PUNTO DE VENTA', 0, 1, '253.10', '253.10', 'SERVICIO', '0.00'),
(1898, 1, 'JORGE LUIS MARTINEZ ALBERTO', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1899, 1, 'PABLO ALBERTO VIGIL MARTINEZ', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1900, 1, 'CONSUELO YAMILETH HERNANDEZ AMAYA', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1901, 1, 'JOSELINE CLARIBEL SAGASTUME DE GONZALEZ ', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1902, 1, 'JORGE ALBERTO DIAZ AMAYA', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1903, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(1904, 1, 'METRO DE CABLE UTP CAT 5', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(1905, 1, 'CAMARA BULLET, RESOLUCION DE 1080P MARCA HIKVISION', 0, 1, '26.00', '26.00', 'SERVICIO', '0.00'),
(1906, 1, 'KIT DE 6 CAMARAS DE SEGURIDAD 2 FULL COLOR 24/7 Y 4 CAMARAS Y DISCO DURO DE CAPACIDAD DE 1TB', 0, 1, '424.78', '424.78', 'SERVICIO', '0.00'),
(1907, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 4 SALIDAS', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1908, 1, 'CABLE PREARMADO SALIDA DE VIDEO Y FUENTE DE ENERGIA ', 0, 1, '16.00', '16.00', 'SERVICIO', '0.00'),
(1909, 1, 'DISCO DURO SEAGATE BARRACUDA 1TB', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(1910, 1, 'CAMARA TURBO BALA 1080, LENTE FIJO, IP66, IR 20 MTS, MARCA HIKVISION', 0, 1, '24.00', '24.00', 'SERVICIO', '0.00'),
(1911, 1, 'DVR 4CH HD/AHD/ANALOG DVR 1080P/4MP LITE 1SATA 1 RJ45 100M', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(1912, 1, 'METRO DE CABLE UTP CAT 5', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(1913, 1, 'CAJA DE EFECTIVO ELECTRONICA 3NSTAR', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(1914, 1, 'CAJA DE PAPEL DE 50 UNIDADES 80MM', 0, 1, '88.49', '88.49', 'SERVICIO', '0.00'),
(1915, 1, 'UPS MARCA  ORBITEC 2,200V 12 MESES DE GARANTIA', 0, 1, '154.87', '154.87', 'SERVICIO', '0.00'),
(1916, 1, 'SISTEMA DE INVENTARIO Y FACTURACION ', 0, 1, '1.00', '1.00', 'SERVICIO', '0.00'),
(1917, 1, 'KIT DE PUNTO DE VENTA ', 0, 1, '1.00', '1.00', 'SERVICIO', '0.00'),
(1918, 1, 'CAJA SUPERFICIAL', 0, 1, '3.00', '3.00', 'SERVICIO', '0.00'),
(1919, 1, 'PLACA SENCILLA PARA PARED', 0, 1, '1.50', '1.50', 'SERVICIO', '0.00'),
(1920, 1, 'PATCH PANEL 48U', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(1921, 1, 'ORDENADOR DE CABLE DE 48 U', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(1922, 1, 'ROLLO DE VELCRO', 0, 1, '28.00', '28.00', 'SERVICIO', '0.00'),
(1923, 1, 'BOBINA DE CABLE UTP CAT 6 INTERIOR', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1924, 1, 'PATCH CORD CAT6 1M', 0, 1, '3.50', '3.50', 'SERVICIO', '0.00'),
(1925, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1926, 1, 'DISCO DURO SEAGATE 3.5 1TB', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(1927, 1, 'TV SMART MARCA TCLLONGITUD DE 32 PULGADAS', 0, 1, '275.00', '275.00', 'SERVICIO', '0.00'),
(1928, 1, 'ROLLO DE PAPEL TERMICO DE 80MM ', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(1929, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '125.00', '125.00', 'SERVICIO', '0.00'),
(1930, 1, 'METRO DE CABLE UTP CAT 5', 0, 1, '0.30', '0.30', 'SERVICIO', '0.00'),
(1931, 1, 'CAMARA FULL COLOR 24/7 MARCA HIKVISION ', 0, 1, '41.00', '41.00', 'SERVICIO', '0.00'),
(1932, 1, 'DISCO DURO BARRACUDA SEAGATE CAPACIDAD DE 1TB', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(1933, 1, 'DVR CON INTELIGENCIA ARTIFICIAL 4CH MARCA HIKVISION', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(1934, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '125.00', '125.00', 'SERVICIO', '0.00'),
(1935, 1, 'DVR CON INTELIGENCIA ARTIFICIAL 4CH MARCA HIKVISION', 0, 1, '115.00', '115.00', 'SERVICIO', '0.00'),
(1936, 1, 'MONITOR LCD DELL 19.5 E2016HV VGA 1600X900 BLACK 3Y', 0, 1, '220.00', '220.00', 'SERVICIO', '0.00'),
(1937, 1, 'DELL OPIPLEX 3080 SFF C81YR DT SPA Ci5-10500 8G DE RAM  256G SSD WINDOWS 10Pro', 0, 1, '1050.00', '1050.00', 'SERVICIO', '0.00'),
(1938, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE MAYO DE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(1939, 1, 'PAGO DE PUBLICIDAD FACEBOOK CORRESPONDIENTE AL MES DE MAYO 2022', 0, 1, '13.00', '13.00', 'SERVICIO', '0.00'),
(1940, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE MAYO DE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(1941, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE MAYO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1942, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE MAYO DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1943, 1, 'SOPORTE TECNICO PARA SISTEMA EXTENDIDO DE INVENTARIO Y FACTURACION DEL MES DE MAYO 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1944, 1, 'SOPORTE TECNICO PARA SISTEMA EXTENDIDO DE INVENTARIO Y FACTURACION DEL MES DE MAYO 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1945, 1, 'SOPORTE TECNICO PARA SISTEMA EXTENDIDO DE INVENTARIO Y FACTURACION DEL MES DE MAYO 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1946, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE MAYO', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(1947, 1, 'SISTEMA DE GESTION HOSPITALARIA', 0, 1, '3500.00', '3500.00', 'SERVICIO', '0.00'),
(1948, 1, 'SISTEMA PARA LABORATORIO', 0, 1, '1695.00', '1695.00', 'SERVICIO', '0.00'),
(1949, 1, 'SISTEMA GESTION DE CAFETERIA ', 0, 1, '1356.00', '1356.00', 'SERVICIO', '0.00'),
(1950, 1, 'CABLE PRE ARMADO CCTV 50 MTS', 0, 1, '23.00', '23.00', 'SERVICIO', '0.00'),
(1951, 1, 'UPS ORBITEC 600VA', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(1952, 1, 'MONITOR DE 19 PULGADAS CUADRADO', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(1953, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE MAYO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1954, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE ABRIL DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(1955, 1, 'PERSONAS SOMETIDAS A LA EVALUACION POLIGRAFICA DE PRE-EMPLEO ', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(1956, 1, 'CAJA DE PAPEL DE 50 UNI 80 MM', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1957, 1, 'CAJA DE PAPEL TERMICO 5O U 80MM', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1958, 1, 'CAJA DE PAPEL TERMICO DE 50 UN 80MM', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00');
INSERT INTO `servicios` (`id_servicio`, `estado`, `descripcion`, `id_sucursal`, `id_categoria`, `costo`, `precio`, `tipo_prod_servicio`, `precio_iva`) VALUES
(1959, 1, 'CAJA DE PAPEL TERMICO DE 50 U  MEDIDA 80MM', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(1960, 1, 'CAJA DE PAPEL TERMICO DE 80MM 50 U', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(1961, 1, 'SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '375.00', '375.00', 'SERVICIO', '0.00'),
(1962, 1, 'EQUIPO LENOVO THINKCENTRE, PROCESADOR I5, MODELO M73, RAM 4GB, SSD 240GB.', 0, 1, '260.00', '260.00', 'SERVICIO', '0.00'),
(1963, 1, 'EQUIPO DELL OPTIPLEX 790 I5 3.3GHZ, 8 GB DE RAM, SSD 240GB', 0, 1, '310.00', '310.00', 'SERVICIO', '0.00'),
(1964, 1, 'MONITOR LCD DELL 19.5 E2016HV VGA 1600X900 BLACK 3Y', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(1965, 1, 'FUENTE DE PODER CONMUTADA 18 CHANNEL 120WATT', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(1966, 1, 'MATERIALES DE INSTALACION', 0, 1, '116.00', '116.00', 'SERVICIO', '0.00'),
(1967, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES MAYO DEL 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1968, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES MAYO DEL 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(1969, 1, 'MANTENIMIENTO A SISTEMA DE VIDEO VIGILANCIA', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1970, 1, 'MANTENIMIENTO A SISTEMA TELEFONIA', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(1971, 1, 'INSTALACION MAS CONFIGURACION DE 3 CAMARAS DE SEGURIDAD', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(1972, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES MAYO DE 2022', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(1973, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(1974, 1, 'BOBINA DE CABLE UTP CAT 5 INTERIOR 305M', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(1975, 1, 'REGLETA ', 0, 1, '6.00', '6.00', 'SERVICIO', '0.00'),
(1976, 1, 'CAMARA BULLET HILOOK RESOLUCION DE 1080P', 0, 1, '26.00', '26.00', 'SERVICIO', '0.00'),
(1977, 1, 'CAMARA DOMO CON AUDIO INCORPORADO MARCA HIKVISION', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(1978, 1, 'DISCO DURO SEAGATE BARRACUDA 2TB', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(1979, 1, 'CABLE VNC 20 METROS', 0, 1, '15.08', '15.08', 'SERVICIO', '0.00'),
(1980, 1, 'INSTALCION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '170.00', '170.00', 'SERVICIO', '0.00'),
(1981, 1, 'DISCO DURO SEAGATE DE 1TB ', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(1982, 1, 'METRO DE CABLE UTP CAT 5', 0, 1, '0.30', '0.30', 'SERVICIO', '0.00'),
(1983, 1, 'CAMARA BULLET RESOLUCION 1080P MARCA HIKLOOK', 0, 1, '24.00', '24.00', 'SERVICIO', '0.00'),
(1984, 1, 'CAMARA DOMO RESOLUCION 1080P MARCA HIKLOOK', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(1985, 1, '	INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '170.00', '170.00', 'SERVICIO', '0.00'),
(1986, 1, 'CABLE PREARMADO DE 20MT', 0, 1, '16.00', '16.00', 'SERVICIO', '0.00'),
(1987, 1, 'DISCO DURO SEAGATE BARRACUDA ', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(1988, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 4 SALIDAS', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(1989, 1, 'SERVICIO DE ALOJAMIENTO Y SOPORTE AÑO 2021 Y 2022', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(1990, 1, 'SERVICIO DE 5 CUENTAS DE CORREO AÑO 2022', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(1991, 1, 'SERVICIO DE 5 CUENTAS DE CORREO AÑO 2021 ', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(1992, 1, 'Soporte a Sistema de inventario y facturación para farmacia,correspondiente al mes de Mayo de 2022', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(1993, 1, 'Soporte a Sistema de inventario y facturación para farmacia,correspondiente al mes de Abril de 2022', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(1994, 1, 'CINTA PARA IMPRESORA LX-350', 0, 1, '8.00', '8.00', 'SERVICIO', '0.00'),
(1995, 1, 'MOUSE MARCA XTECH', 0, 1, '6.78', '6.78', 'SERVICIO', '0.00'),
(1996, 1, 'TECLADO MARCA XTECH', 0, 1, '6.78', '6.78', 'SERVICIO', '0.00'),
(1997, 1, 'TECLADO MARCA XTECH', 0, 1, '6.78', '6.78', 'SERVICIO', '0.00'),
(1998, 1, 'MOUSE MARCA XTECH', 0, 1, '6.78', '6.78', 'SERVICIO', '0.00'),
(1999, 1, 'CUSTOM D4 102 IMPRESORA DE ETIQUETAS TERMICA DIRECTA TRANSFERENCIA TERMICA CUSTOM AMERICA GARANTIA DE 12 MESES', 0, 1, '275.00', '275.00', 'SERVICIO', '0.00'),
(2000, 1, 'FUENTE DE PODER PARA COMPUTADORE DELL OPTIPLEX 2030', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(2001, 1, '	CAJA RECTANGULAR COMPLETA', 0, 1, '2.75', '2.75', 'SERVICIO', '0.00'),
(2002, 1, 'METRO DE CABLE UTP CAT 5 DE RED', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(2003, 1, 'METRO DE CABLE UTP CAT 5 INTEMPERIE', 0, 1, '0.85', '0.85', 'SERVICIO', '0.00'),
(2004, 1, 'MANO DE OBRA ', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2005, 1, 'IMPRESORA MATRICIAL FX-890 GARANTIA DE 6 MESES', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2006, 1, 'IMPRESORA MATRICIAL FX-890 GARANTIA DE 6 MESES', 0, 1, '225.66', '225.66', 'SERVICIO', '0.00'),
(2007, 1, 'LENOVO THINK CENTRE CORE I3 DISCO DE ESTADO SOLIDO DE 240GB  4 GB RAM GARANTIA DE 12 MESES', 0, 1, '215.00', '215.00', 'SERVICIO', '0.00'),
(2008, 1, 'MONITOR CLASE A 17.5 GARANTIA DE 12 MESES', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(2009, 1, 'CPU LENOVO THINK CENTRE CORE I3 DISCO DE ESTADO SOLIDO DE 240GB 4 GB RAM GARANTIA DE 12 MESES', 0, 1, '225.00', '225.00', 'SERVICIO', '0.00'),
(2010, 1, 'CAMARA BULLET VARIFOCAL MONOTORIZADA RESOLUCION DE 5MP  IMAGEN DE ALTA CALIDAD', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(2011, 1, 'DVR DE 8CH DE 4MP MARCA  HIKVISION ', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(2012, 1, '	CAMARA BULLET CAMARA 1080P OUTDOOR IP67 / EXIR 20M *', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(2013, 1, 'LECTOR DE BARRA 3NSTAR 12 MESES DE GARANTIA ', 0, 1, '70.79', '70.79', 'SERVICIO', '0.00'),
(2014, 1, 'CAJA DE EFECTIVO ELECTRONICA CUSTOM AMERICA 12 MESES DE GARANTIA ', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(2015, 1, 'DISCO DURO SATA 500GB', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2016, 1, 'MONITOR  WIDESCREEN CLASE A ', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(2017, 1, 'LENOVO M73 THINKCENTRE CORE I3 DISCO ESTADO SOLIDO DE 240GB Y 4 GB DE RAM 12 MESES DE GARANTIA   COMBO DE MOUSE Y TECLADO', 0, 1, '220.00', '220.00', 'SERVICIO', '0.00'),
(2018, 1, 'MONITOR WIDESCREEN CLASE A ', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(2019, 1, 'LENOVO M73 THINKCENTRE CORE I5 DISCO ESTADO SOLIDO DE 240GB Y 4 GB DE RAM 12 MESES DE GARANTIA COMBO DE MOUSE Y TECLADO', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(2020, 1, 'CPU LENOVO M73 THINKCENTRE CORE I3 DISCO DE ESTADO SOLIDO DE 240GB RAM DE 4GB 12 MESES DE GARANTIA', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(2021, 1, 'Revision y diagnostico de sistema de vigilancia ', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(2022, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2023, 1, 'BOBINA DE CABLE UTP CAT 5 ', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(2024, 1, 'CAMARA AUDIO MARCA HIKVISION', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2025, 1, 'SISTEMA DE INVENTARIO Y FACTURACION MAS PUNTO DE VENTA', 0, 1, '1800.00', '1800.00', 'SERVICIO', '0.00'),
(2026, 1, 'UPS MARCA ORBITEC DE 600 VA', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(2027, 1, 'VGA PRE FABRICADO DE 50 MT', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(2028, 1, 'IMPRESORA DE VIÑETAS CUSTOM AMERICA 12 MESES DE GARANTIA', 0, 1, '265.49', '265.49', 'SERVICIO', '0.00'),
(2029, 1, 'PAGO DE SISTEMA DE INVENTARIO Y FACTURACION ', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2030, 1, 'PAGO DE TERCERA CUOTA POR EQUIPO DE PUNTO DE VENTA', 0, 1, '253.10', '253.10', 'SERVICIO', '0.00'),
(2031, 1, 'CAJA DE EFECTIVO', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2032, 1, 'SISTEMA DE INVENTARIO Y FACTURACION MAS EQUIPO DE PUNTO DE VENTA ', 0, 1, '353.98', '353.98', 'SERVICIO', '0.00'),
(2033, 1, 'SISTEMA DE INVENTARIO Y FACTURACION MAS EQUIPO DE PUNTO DE VENTA ', 0, 1, '353.98', '353.98', 'SERVICIO', '0.00'),
(2034, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '125.00', '125.00', 'SERVICIO', '0.00'),
(2035, 1, 'METRO DE CABLE CAT5 ', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(2036, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(2037, 1, 'CAMARA TIPO DOMO MICROFONO INCORPORADO  IP67 RESOLUCION 1080P', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2038, 1, 'CPU LENOVO THINKCENTRE M73 CORE I3 RAM 8GB SSD 240G', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(2039, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES jUNIO 2022', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2040, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES DE MAYO 2022', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2041, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES DE JUNIO 2022', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2042, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES DE MAYO 2022', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2043, 1, 'MONITOR CLASE A 19.5 6 MESES DE GARANTIA', 0, 1, '63.54', '63.54', 'SERVICIO', '0.00'),
(2044, 1, 'CPU LENOVO M73 THINK CENTRE CORE I3 DE 250GB 4GB DE RAM 12 MESES DE GARANTIA MAS COMBO DE MOUSE Y TECLADO GRATIS ', 0, 1, '140.00', '140.00', 'SERVICIO', '0.00'),
(2045, 1, 'CAJA DE PAPEL TERMICO 80MM 50 UN', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2046, 1, 'IMPRESORA MATRICIAL FX-890 CLASE A ', 0, 1, '225.66', '225.66', 'SERVICIO', '0.00'),
(2047, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE MAYO 2022', 0, 1, '323.01', '323.01', 'SERVICIO', '0.00'),
(2048, 1, 'Cajas de papel Térmico 80 mm 50 unidades', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(2049, 1, 'UPS de 750VA Marca: ORBITEC Modelo TC-7508  Garantía: 12 MESES', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(2050, 1, 'Monitor, Marca: AOC LED Modelo 20E1H, Garantía: 12 MESES ', 0, 1, '210.00', '210.00', 'SERVICIO', '0.00'),
(2051, 1, 'Computadora de escritorio Marca DELL, Modelo 0P3080, Memoria RAM 8GB, Procesador Core I5-10500, Disco solido de 256, Windows 10 español, Garantía:12 MESES', 0, 1, '1320.02', '1320.02', 'SERVICIO', '0.00'),
(2052, 1, 'EQUIPO LENOVO M73 THINK CENTRE CORE I5 MEMORIA RAM DE 8GB Y DISCO DE ESTADO SOLIDO DE 240GB GARANTIA DE 12 MESES ', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2053, 1, 'EQUIPO LENOVO M73 THINK CENTRE CORE I3 MEMORIDELIDO EQUIPO LENOVO M73 THINK CENTRE CORE I3 MEMORIA RAM DE 8GB Y DISCO DE ESTADO SOLIDO DE 240 GB ,MONITOR DE 17 PULGADAS MARCA DELL,COMBO DE MOUSE Y TECLADO, GARANTIA DE 12 MESES ', 0, 1, '252.21', '252.21', 'SERVICIO', '0.00'),
(2054, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE JUNIO2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2055, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE MAYO 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2056, 1, 'INSTALACION Y CONFIFURACIO DE CAMARAS DE SEGURIDAD ', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(2057, 1, 'METRO DE CABLE UTP CAT 5 ', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(2058, 1, 'EQUIPOS PARA PUNTO DE VENTA COMPLETO, CPU, MONITOR, CAJA DE DINERO, LECTOR DE CODIGO DE BARRAS,  UPS, IMPRESOR DE RECIBOS', 0, 1, '700.00', '700.00', 'SERVICIO', '0.00'),
(2059, 1, 'IMPRESORA FX-2190 CLASE A GARANTIA DE 12 MESES ', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(2060, 1, 'APC Back-UPS BX1000L-LM - UPS - CA 110/120 V', 0, 1, '145.00', '145.00', 'SERVICIO', '0.00'),
(2061, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '203.57', '203.57', 'SERVICIO', '0.00'),
(2062, 1, 'UPS APC BX1000L-LM 1000VA 120V AVR 2Y   ', 0, 1, '145.00', '145.00', 'SERVICIO', '0.00'),
(2063, 1, 'Teclado y mouse Wireless Español USB ARGOM ARG-KB-7436      ', 0, 1, '22.00', '22.00', 'SERVICIO', '0.00'),
(2064, 1, 'CARTUCHO TINTA CANON PG-145XL BLACK           ', 0, 1, '31.49', '31.49', 'SERVICIO', '0.00'),
(2065, 1, ' CARTUCHO TINTA CANON CL-146XL TRICOLOR         ', 0, 1, '42.71', '42.71', 'SERVICIO', '0.00'),
(2066, 1, 'EPSON Exceed You Vision Black     ', 0, 1, '16.00', '16.00', 'SERVICIO', '0.00'),
(2067, 1, 'Botes Black ETouch Canon-Hp  ', 0, 1, '13.00', '13.00', 'SERVICIO', '0.00'),
(2068, 1, 'BOLSA DE CONECTORES RJ45 100 UNIDADES', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2069, 1, 'UPS FORZA 750 VA 375 WATT', 0, 1, '48.67', '48.67', 'SERVICIO', '0.00'),
(2070, 1, 'UPS APC BX1000L-LM 1000VA 120V AVR 2Y ', 0, 1, '145.00', '145.00', 'SERVICIO', '0.00'),
(2071, 1, 'TECLADO Y MOUSE WIRELESS ESPANOL USB ARGOM ARG-KB-7436 ', 0, 1, '22.00', '22.00', 'SERVICIO', '0.00'),
(2072, 1, 'CARTUCHO TINTA CANON PG-145XL BLACK ', 0, 1, '31.49', '31.49', 'SERVICIO', '0.00'),
(2073, 1, 'CARTUCHO TINTA CANON CL-146XL TRICOLOR          ', 0, 1, '42.71', '42.71', 'SERVICIO', '0.00'),
(2074, 1, 'EPSON Exceed You Vision Black ', 0, 1, '16.00', '16.00', 'SERVICIO', '0.00'),
(2075, 1, 'Botes Black ETouch Canon-Hp ', 0, 1, '12.00', '12.00', 'SERVICIO', '0.00'),
(2076, 1, 'BOLSA DE CONECTORES RJ45 100 UNIDADES', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2077, 1, 'CPU LENOVO M73 THINK CENTRE CORE I3 240GB DISCO SOLIDO Y 8 DE RAM MOUSE Y TECLADO', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(2078, 1, 'HIK - 8CH HD/AHD/ANALOG DVR HD1080P LITE 1 SATA 1 RJ45 100M', 0, 1, '78.30', '78.30', 'SERVICIO', '0.00'),
(2079, 1, 'CAMARA TURBO BALA 1080, LENTE FIJO, IP66, IR 20 MTS, MARCA HIKVISION', 0, 1, '21.75', '21.75', 'SERVICIO', '0.00'),
(2080, 1, 'FUENTE DE PODER PARA CAMARA CCTV DE 1AMP', 0, 1, '5.22', '5.22', 'SERVICIO', '0.00'),
(2081, 1, 'REGLETA DE PODER', 0, 1, '4.35', '4.35', 'SERVICIO', '0.00'),
(2082, 1, 'INSTALACION DE PUNTO DE RED', 0, 1, '22.75', '22.75', 'SERVICIO', '0.00'),
(2083, 1, 'SWITCH DE 5 PUERTOS', 0, 1, '13.05', '13.05', 'SERVICIO', '0.00'),
(2084, 1, 'CABLE UTP CAT 5 UNIDAD METRO', 0, 1, '0.24', '0.24', 'SERVICIO', '0.00'),
(2085, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACIÓN', 0, 1, '88.49', '88.49', 'SERVICIO', '0.00'),
(2086, 1, 'TP-LINK ROUTER TL-WR841N WIRELE88 300ME', 0, 1, '34.98', '34.98', 'SERVICIO', '0.00'),
(2087, 1, 'CABLE HDMI 5MTS', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(2088, 1, 'CABLE HDMI 5MTS', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(2089, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '350.00', '350.00', 'SERVICIO', '0.00'),
(2090, 1, 'GAVINETE DE 12 UNIDADES ', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(2091, 1, 'MONITOR LENOVO 24 PULGADAS CLASE A 12 MESES DE GARANTIA', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2092, 1, 'SOPORTE TECNICO PARA SISTEMA EXTENDIDO DE INVENTARIO Y FACTURACION DEL MES DE JUNIO 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2093, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE JUNIO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2094, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE JUNIO DE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2095, 1, 'PAGO DE PUBLICIDAD FACEBOOK CORRESPONDIENTE AL MES DE JUNIO 2022', 0, 1, '13.00', '13.00', 'SERVICIO', '0.00'),
(2096, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE JUNIO DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2097, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE jUNIO DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2098, 1, 'CUOTA #1 POR SISTEMA DE INVENTARIO Y FACTURACION MAS EQUIPO DE VENTA GARANTIA DE 12 MESES', 0, 1, '700.00', '700.00', 'SERVICIO', '0.00'),
(2099, 1, 'CUOTA #1 SISTEMA DE INVENTARIO Y FACTURACION MAS EQUIPO DE PUNTO DE VENTA GARANTIA DE 12 MESES', 0, 1, '619.47', '619.47', 'SERVICIO', '0.00'),
(2100, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES JUNIO DE 2022', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2101, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE JUNIO', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(2102, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE JUNIO DE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2103, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE JUNIO DE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2104, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE JUNIO 2022', 0, 1, '323.01', '323.01', 'SERVICIO', '0.00'),
(2105, 1, 'CAMARA BALA TURBO 1080P 2.8MM IR 20M PLASTICO IP667 EXTERIOR', 0, 1, '24.00', '24.00', 'SERVICIO', '0.00'),
(2106, 1, 'Kingston XS2000 DISCO DE ESTADO SOLIDO–CAPACIDAD DE 1 TBMARCA KINGSTON 12 MESES DE GARANTIA', 0, 1, '168.14', '168.14', 'SERVICIO', '0.00'),
(2107, 1, 'CAJA DE EFECTIVO  3NSTAR  12 MESES DE GARANTIA', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(2108, 1, 'MONITOR CLASE A 17.5 PULGADAS DELL', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(2109, 1, 'EQUIPO COMPLETO LENOVO M73 CORE I5 DISCO SSD 128GB 8GB RAM, MONITOR DE WIDESCREEM  DE 17 PULGADAS,  COMBO DE MOUSE Y TECLADO', 0, 1, '275.00', '275.00', 'SERVICIO', '0.00'),
(2110, 1, 'METRO  DE CANALETA ', 0, 1, '3.25', '3.25', 'SERVICIO', '0.00'),
(2111, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(2112, 1, 'CAMARA TURBO BALA 1080P, 2MPX, LENTE FIJO, PLASTICA, IR 20 MTS, MARCA HIKVISION', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(2113, 1, 'PASTA TERMICA 800MG MARCA SABO', 0, 1, '13.00', '13.00', 'SERVICIO', '0.00'),
(2114, 1, 'EQUIPO LENOVO M73 CORE I3 128GB, MONITOR DE 17 PULGADAS WIDESCREEM Y COMBO DE MOUSE Y TECLADO', 0, 1, '204.00', '204.00', 'SERVICIO', '0.00'),
(2115, 1, 'FLETE', 0, 1, '8.85', '8.85', 'SERVICIO', '0.00'),
(2116, 1, 'ENVIO', 0, 1, '2.65', '2.65', 'SERVICIO', '0.00'),
(2117, 1, 'CINTA  FX 890 MARCA EPSON', 0, 1, '11.07', '11.07', 'SERVICIO', '0.00'),
(2118, 1, 'IMPRESORA MATRICIAL  MODELO FX890, MARCA EPSON, 6 MESES DE GARANTIA', 0, 1, '225.66', '225.66', 'SERVICIO', '0.00'),
(2119, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '508.33', '508.33', 'SERVICIO', '0.00'),
(2120, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(2121, 1, 'METRO DE CABLE UTP CAT 5 ', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(2122, 1, 'INSTALACION DE  2 CAMARAS DE SEGURIDAD XIAOMI', 0, 1, '168.14', '168.14', 'SERVICIO', '0.00'),
(2123, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES JUNIO DEL 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2124, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES JUNIO DEL 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2125, 1, 'IMPRESORA MATRICIAL FX-890  CLASE A 6 MESES DE GARANTIA', 0, 1, '203.54', '203.54', 'SERVICIO', '0.00'),
(2126, 1, 'IMPRESORA MATRICIAL FX-890  CLASE A 6 MESES DE GARANTIA', 0, 1, '203.54', '203.54', 'SERVICIO', '0.00'),
(2127, 1, 'CAJA DE EFECTIVO 3NSTAR 12 MESES DE GARANTIA', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(2128, 1, 'MONITOR CLASE A 19 PULGADAS ', 0, 1, '53.10', '53.10', 'SERVICIO', '0.00'),
(2129, 1, 'CAJA DE EFECTIVO 3NSTAR ', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2130, 1, 'IMPRESORA TERMICA 3NSTAR ', 0, 1, '148.67', '148.67', 'SERVICIO', '0.00'),
(2131, 1, 'CAJA DE PAPEL TERMICO  80MM ', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2132, 1, 'EQUIPO LENONO THINKCENTRE CORE I3 TODO EN 1 CON DISCO SOLIDO DE 128GB MEMORIA DE 8GB GARANTIA 12 MESES', 0, 1, '265.49', '265.49', 'SERVICIO', '0.00'),
(2133, 1, 'CAJA DE EFECTIVO 3NSTAR GARANTIA DE 12 MESES', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(2134, 1, 'IMPRESORA MATRICIAL FX-890 GARANTIA DE 12 MESES', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2135, 1, 'IMPRESORA MATRICIAL EPSON FX-890 GARANTIA DE 12 MESES', 0, 1, '203.54', '203.54', 'SERVICIO', '0.00'),
(2136, 1, 'SERVICIO DE MARKETING CORRESPONDIENTE AL  MES DE JULIO', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(2137, 1, 'SERVICIO DE MARKETING CORRESPONDIENTE AL MES DE JUNIO', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(2138, 1, 'SERVICIO DE MARKETING CORRESPONDIENTE AL MES DE MAYO', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(2139, 1, 'KIT DE CAMARAS  3 CAMARAS 1080P, 1 FULL COLOR 24/7 CON INSTALACION ', 0, 1, '309.74', '309.74', 'SERVICIO', '0.00'),
(2140, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(2141, 1, 'METRO DE CABLE UTP CAT 5 ', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(2142, 1, 'METRO DE CABLE UTP CAT 5 ', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(2143, 1, 'MANTENIMIENTO PREVENTIVO A SISTEMA DE VIDEO VIGILANCIA', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(2144, 1, 'RESET A CLAVE DE DVR HIKVISION', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2145, 1, 'SERVICIO DE SOPORTE TECNICO ', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2146, 1, 'RECUPERACIÓN DE CUANTA DE ADMINISTRADOR DE DVR', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(2147, 1, '	MONITOR 17 PULGADAS CLASE A', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(2148, 1, '	MONITOR 17 PULGADAS CLASE A', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(2149, 1, '2 CAMARAS EWTTO RESOLUCION 1080P MAS MODEM T-MOBILE  GARANTIA DE 12 MESES ', 0, 1, '240.00', '240.00', 'SERVICIO', '0.00'),
(2150, 1, 'EQUIPO DE PUNTO DE VENTA COMPLETO MAS  SISTEMA DE INVENTARIO Y FACURACION', 0, 1, '2090.00', '2090.00', 'SERVICIO', '0.00'),
(2151, 1, 'EQUIPO DE PUNTO DE VENTA COMPLETO 12 MESES DE GARANTIA MAS SITEMA DE INVENTARIO Y FACTURACION', 0, 1, '2090.00', '2090.00', 'SERVICIO', '0.00'),
(2152, 1, 'EQUIPO DE PUNTO DE VENTA COMPLETO 12 MESES DE GARANTIA MAS SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '2090.00', '2090.00', 'SERVICIO', '0.00'),
(2153, 1, 'METRO DE CABLE UTP CAT5', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(2154, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(2155, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(2156, 1, 'DISCO DURO DE 500GB', 0, 1, '40.70', '40.70', 'SERVICIO', '0.00'),
(2157, 1, 'CUBO DE 5V 1 AMP', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(2158, 1, 'ROLLO DE PAPEL TERMICO 80MM', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(2159, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO', 0, 1, '18.00', '18.00', 'SERVICIO', '0.00'),
(2160, 1, 'CAJA DE EFECTIVO ELCTRONICA 12 MESES DE GARANTIA', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(2161, 1, 'MATERIALES DE INSTALACIÓN', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(2162, 1, 'INSTALACIÓN DE 6 CÁMARAS CCTV', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2163, 1, '25 ROLLOS DE PAPEL TERMICO 80MM', 0, 1, '42.03', '42.03', 'SERVICIO', '0.00'),
(2164, 1, 'IMPRESORA MATRICIAL FX890 CLASE A ', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2165, 1, 'PRUEBA POLIGRAFICA PRE- EMPLEO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(2166, 1, ' 5 ROLLO DE VINETAS PARA IMPRESORA ZBRA', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(2167, 1, 'PAGO DE 2GUNDA CUOTA DE SISTEMA DE INVENTARIO Y FACTURACION ', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2168, 1, 'CAJA DE PAPEL TERMICO DE 50 U MEDIDA 80MM', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2169, 1, 'CAJA DE PAPEL TERMICO DE 50 U MEDIDA 80MM', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2170, 1, 'CAJA DE PAPEL TERMICO 80MM 50U', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(2171, 1, 'FUENTE DE PODER DE 1AMP ', 0, 1, '5.30', '5.30', 'SERVICIO', '0.00'),
(2172, 1, 'ADAPTADOR MACHO Y HEMBRA ', 0, 1, '2.21', '2.21', 'SERVICIO', '0.00'),
(2173, 1, 'VIDEO BALUN ', 0, 1, '3.98', '3.98', 'SERVICIO', '0.00'),
(2174, 1, 'CAMARA DOMO  COLO VU 24/7 RESOLUCION 1080P', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2175, 1, 'CAMARA EZVIZ 1080P', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(2176, 1, ' PAGO DE SISTEMA DE INVENTARIO Y FACTURACION OPENPYME', 0, 1, '619.47', '619.47', 'SERVICIO', '0.00'),
(2177, 1, 'IMPRESORA MATRICIAL FX-890', 0, 1, '221.23', '221.23', 'SERVICIO', '0.00'),
(2178, 1, 'LECTOR DE BARRA 3NSTAR', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(2179, 1, 'CAJA DE EFECTIVO 3NSTAR', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(2180, 1, 'MOUSE RGB FANTECH', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(2181, 1, 'MONITOR LENOVO DE 24 PULGADAS CLASE A', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(2182, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '175.00', '175.00', 'SERVICIO', '0.00'),
(2183, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES JULIO 2022', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2184, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES JUNIO 2022', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2185, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES MAYO 2022', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2186, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES MAYO 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2187, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES JUNIO 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2188, 1, 'DISCO DURO SEAGATE EXTERNO 1TB', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(2189, 1, 'PRUEBA POLIGRAFICA  DE PRE-EMPLEO', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(2190, 1, 'PRUEBA POLIGRAFICA PRE-EMPLEO', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(2191, 1, 'CAJA DE PAPEL TERMICO DE 80MM 50U', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2192, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(2193, 1, 'DESMONTAJE DE 4 CAMARAS DE SEGURIDAD', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(2194, 1, 'CAMBIO DE DVR DE 4 CANALES A 8 CANALES', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(2195, 1, 'EVALUACION POLIGRAFICA PRE-EMPLEO SAN MIGUEL', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(2196, 1, 'EQUIPO HP 800-G1 , DISCO DE 240 SSD 4 RAM, MONITOR DE 19.5 CLASE A, MOUSE Y TECLADO GARANTIA DE 12 MESES BOCINA GRATIS', 0, 1, '238.94', '238.94', 'SERVICIO', '0.00'),
(2197, 1, 'DISCO BARRACUDA SEAGATE 4TB ', 0, 1, '180.00', '180.00', 'SERVICIO', '0.00'),
(2198, 1, 'DVR DE 16 CANALES RESOLUCION MAXIMA DE 4MP ', 0, 1, '145.00', '145.00', 'SERVICIO', '0.00'),
(2199, 1, 'CAMARA COLOR VU 3K', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(2200, 1, 'DVR DE 16 CH RESOLUCION MAXIMA DE 4MP', 0, 1, '145.00', '145.00', 'SERVICIO', '0.00'),
(2201, 1, 'DISCO DURO SEAGATE 4TB', 0, 1, '180.00', '180.00', 'SERVICIO', '0.00'),
(2202, 1, 'CAMARA COLOR VU 3K MICROFONO INCORPORADO', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(2203, 1, 'PRUEBA POLIGRAFICA PRE-EMPLEO ', 0, 1, '15.93', '15.93', 'SERVICIO', '0.00'),
(2204, 1, 'FUENTE DE PODER 4 SPLITTER 5 AMP', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(2205, 1, 'CABLE PREAERMADO 20MT ', 0, 1, '13.28', '13.28', 'SERVICIO', '0.00'),
(2206, 1, 'CONFIGURACION E INSTALCION DE CAMARAS DE SEGURIDAD ', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(2207, 1, 'CAMARA COLOR VU, 40 METROS DE VISION', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(2208, 1, 'SSD KINGSTON 480GB 2.5 SA400S37-480G SOLIDO 3Y 7MM', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(2209, 1, 'HD SEAGATE 8TB EXTERIOR 3.5', 0, 1, '270.00', '270.00', 'SERVICIO', '0.00'),
(2210, 1, 'CPU MARCA DELL CORE I3 4GB DE RAM DISCO MECANICO DE 250 MODELO 7010 12 MESES DE GARANTIA', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(2211, 1, 'DISCO DE 500GB ', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2212, 1, 'CAMARA COLOR VU 24/7 MICROFONO INCORPORADO', 0, 1, '30.97', '30.97', 'SERVICIO', '0.00'),
(2213, 1, 'CAMBIO DE CAMARA Y TERMINALES DE CORRIENTE ', 0, 1, '16.00', '16.00', 'SERVICIO', '0.00'),
(2214, 1, 'SISTEMA DE GESTION DE RECURSOS HUMANOS', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(2215, 1, 'caja de papel termico de 80mm 50 u', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2216, 1, 'INSTALACION DE TOMA CORRIENTE ', 0, 1, '13.00', '13.00', 'SERVICIO', '0.00'),
(2217, 1, 'SOPORTE PARA SMART TV', 0, 1, '31.35', '31.35', 'SERVICIO', '0.00'),
(2218, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE JULIO DE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2219, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE JULIO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2220, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE JUNIO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2221, 1, 'DISCO EXTERNO DE 6 TB MARCA KINGSTON ', 0, 1, '180.00', '180.00', 'SERVICIO', '0.00'),
(2222, 1, 'DISCO DURO EXTERNO DE 6TB MARCA SEAGATE', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(2223, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES JULIO DE 2022', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2224, 1, 'SOPORTE TECNICO PARA SISTEMA EXTENDIDO DE INVENTARIO Y FACTURACION DEL MES DE JULIO 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2225, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE JULIO DE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2226, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE JUNIO DE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2227, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE JULIO DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2228, 1, 'SOPORTE TECNICO CORRESPONDIENTE AL MES JULIO DEL 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2229, 1, 'SERVICIO DE SOPORTE TECNICO CORRESPONDIENTE DEL MES DE JULIO', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2230, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE JULIO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2231, 1, 'VENTILADOR PARA GABINETE', 0, 1, '19.20', '19.20', 'SERVICIO', '0.00'),
(2232, 1, 'UPS APC BE600M1-LM 600VA 330W 7OUTLETS 1US CHARGING', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(2233, 1, 'MONITOR CLASE A 19 PULGADAS', 0, 1, '53.10', '53.10', 'SERVICIO', '0.00'),
(2234, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE JULIO 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2235, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE JUNIO 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2236, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE MAYO 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2237, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE ABRIL 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2238, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE MAYO 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2239, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE ABRIL 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2240, 1, 'MONITOR CLASE A 19.5 PULGADAS ', 0, 1, '53.10', '53.10', 'SERVICIO', '0.00'),
(2241, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(2242, 1, 'METRO DE CABLE UTP CAT 5', 0, 1, '0.30', '0.30', 'SERVICIO', '0.00'),
(2243, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(2244, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(2245, 1, 'METRO DE CABLE UTP CAT 5', 0, 1, '0.30', '0.30', 'SERVICIO', '0.00'),
(2246, 1, 'ID-CARD-125K TARJETA DE PROXIMIDAD SOLO LECTURA', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(2247, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(2248, 1, 'METRO DE CABLE UTP CAT 5', 0, 1, '0.30', '0.30', 'SERVICIO', '0.00'),
(2249, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD Y MATERIALES VARIOS', 0, 1, '180.00', '180.00', 'SERVICIO', '0.00'),
(2250, 1, 'METRO DE CABLE UTP CAT 5 ', 0, 1, '0.30', '0.30', 'SERVICIO', '0.00'),
(2251, 1, 'DVR DE 8 CH   MARCA HIKVISION', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(2252, 1, 'CAMARA DOMO MICROFONO INCORPORADO IP67', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(2253, 1, 'CAJA DE PAPEL TERMICO DE 80MM 50U', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(2254, 1, 'Impresor Matricia Marca: Epson Modelo:LX-350l Garantía GARANTIA DE 12 MESES', 0, 1, '450.00', '450.00', 'SERVICIO', '0.00'),
(2255, 1, 'Computadora LAPTOP: Lenovo IdeaPad 3 14IML05 81WA - Intel Core i3 10110U / 2.1 GHz - Win 11 Home Lenovo UHD Graphics 8 GB RAM 256 GB SSD NVMe 14\" TN 1366 x 768 (HD) Wi-Fi 5 gris platino kbd: español (Latinoamérica GARANTIA DE 12 MESES ', 0, 1, '700.00', '700.00', 'SERVICIO', '0.00'),
(2256, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE JULIO 2022', 0, 1, '323.01', '323.01', 'SERVICIO', '0.00'),
(2257, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE JULIO 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2258, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE JULIO2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2259, 1, 'LECTORA BARRA 3NSTAR POS-CS100 W/BASE LECTURA AUTOMATIC', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2260, 1, 'PABLO ALBERTO VIGIL MARTINEZ(ELECTRICISTA)', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(2261, 1, 'JORGE LUIS MARTINEZ ALBERTO(AUXILIAR DE ELECTRCISTA)', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(2262, 1, 'ADATA Premier Series  DDR4  módulo 16gb SO-DIMM de 260 contactos 3200 MHz / PC4-25600', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(2263, 1, 'MANO DE OBRA POR INSTALACION Y REVISION ', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(2264, 1, 'CABLE VGA 30MT ', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(2265, 1, 'MONITOR AOC DE 14.5 ', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(2266, 1, 'TARJETA WIFI MARCA NEXXT', 0, 1, '4.42', '4.42', 'SERVICIO', '0.00'),
(2267, 1, 'EQUIPO DELL OPTIPLEX 370 CORE I3  SSD 240GB 4 DE RAM MONITOR HDMI 19.5 COMBO DE MOUSE Y TECLADO', 0, 1, '238.94', '238.94', 'SERVICIO', '0.00'),
(2268, 1, 'EQUIPO DELL OPTIPLEX 390 CORE I3  SSD 240GB 4 DE RAM MONITOR VGA 19.5 COMBO DE MOUSE Y TECLADO', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2269, 1, 'EQUIPO LENOVO M73 CORE I3 DISCO SSD 240GB 4GB DE RAM MONITOR VGA 19.5 MOUSE Y TECLADO', 0, 1, '185.84', '185.84', 'SERVICIO', '0.00'),
(2270, 1, 'DOMO COLOR VU FULL COLOR 24/7  RESOLUCION MAXIMA DE 1080P ', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(2271, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(2272, 1, 'METRO DE CABLE UTP CAT 5 INTERIOR', 0, 1, '0.30', '0.30', 'SERVICIO', '0.00'),
(2273, 1, 'Camara Domo microfono incorporado 1080p', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(2274, 1, 'Camara Color vu Hilook resolucion de 1080p', 0, 1, '42.00', '42.00', 'SERVICIO', '0.00'),
(2275, 1, 'DVR FULL HD 8CH MARCA HIKVISION', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(2276, 1, 'CAMARA COLOR VU DOMO FULL COLOR 24/7 MICROFONO INCORPORADO', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(2277, 1, 'CAMARA COLOR VU HILOOK1080P METALICA', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2278, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '125.00', '125.00', 'SERVICIO', '0.00'),
(2279, 1, 'TECLADO LOGITECH K850 PERFORMANCE', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2280, 1, 'KIT DE 4 CAMARAS   E INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD ', 0, 1, '365.00', '365.00', 'SERVICIO', '0.00'),
(2281, 1, 'MEDIA CAJA DE PAPEL TERMICO 80MM', 0, 1, '42.04', '42.04', 'SERVICIO', '0.00'),
(2282, 1, 'PAGO DE 3ERA CUOTA DE SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2283, 1, 'CAJA DE PAPEL TERMICO 80MM 50 U', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2284, 1, 'IMPRESORA DE ESCRITORIO ZD230 MARCA ZEBRA GARANTIA DE 12 MESES', 0, 1, '350.00', '350.00', 'SERVICIO', '0.00'),
(2285, 1, 'FLETE ', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(2286, 1, 'DISCO DURO DE 2TB MARCA SEAGATE BARRACUDA', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(2287, 1, 'DVR DE 4CH MARCA HIKVISION 4K ', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(2288, 1, 'CABLE PRE ARMADO DE 25 MT', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(2289, 1, 'CAMARA COLOR VU  FULL COLOR DOMO IP65', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(2290, 1, 'CAMARA  DOMO FULL COLOR 24-7 IP67', 0, 1, '47.50', '47.50', 'SERVICIO', '0.00'),
(2291, 1, 'CONFIGURACION E INSTALACION DE CAMARAS DE SEGURIDAD ', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2292, 1, 'CAMARA BULLET 1080P MARCA HILOOK', 0, 1, '22.00', '22.00', 'SERVICIO', '0.00'),
(2293, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE AGOSTO 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2294, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE AGOSTO 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2295, 1, 'MINI CAMARA ESPIA RESOLUCION MAXIMA DE 1080P', 0, 1, '30.97', '30.97', 'SERVICIO', '0.00'),
(2296, 1, 'instalacion de camaras de seguridad ', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(2297, 1, 'EQUIPO LENOVO M93 CORE I5 DISCO SOLIDO DE 240GB MEMORIA RAM DE 8GB MONITOR DE 19.5 MOUSE Y TECLADO', 0, 1, '274.34', '274.34', 'SERVICIO', '0.00'),
(2298, 1, 'EQUIPO LENOVO M92 CORE I5 SSD 240GB MEMORIA RAM 8GB, MONITOR DE 19.5, MOUSE Y TECLADO', 0, 1, '274.34', '274.34', 'SERVICIO', '0.00'),
(2299, 1, '25 Rollos de papel termico 80 mm', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(2300, 1, 'PRUEBA POLIGRAFICA PRE-EMPLEO', 0, 1, '18.00', '18.00', 'SERVICIO', '0.00'),
(2301, 1, 'CAJA DE PAPEL TERMICO DE 50 UN 80MM	', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(2302, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE AGOSTO DE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2303, 1, '	SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE AGOSTO 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2304, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE AGOSTO DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2305, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE AGOSTO DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2306, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE AGOSTO DE 2022', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2307, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE AGOSTO DE 2022', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2308, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE AGOSTO DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2309, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE AGOSTO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2310, 1, 'PRUEBAS POLIGRAFICAS PRE-EMPLEO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(2311, 1, 'PRUEBA POLIGRAFICA PRE-EMPLEO', 0, 1, '15.93', '15.93', 'SERVICIO', '0.00'),
(2312, 1, 'UPS ORBITEC 600VA', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(2313, 1, 'EQUIPO COMPLETO PARA PUNTO DE VENTA(MONITOR, CPU, TECLADO Y MOUSE)', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(2314, 1, 'LECTOR CODIGO DE BARRA', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(2315, 1, 'IMPRESOR DE TICKET', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(2316, 1, 'SISTEMA DE INVENTARIO Y FACTURACION PARA SUPERMECADO', 0, 1, '700.00', '700.00', 'SERVICIO', '0.00'),
(2317, 1, 'PAGO DE 3 CUOTA DE SISTEMA DE GESTION HOSPITALARIA, LABORATORIO Y CAFETERIA, CORRESPONDIENTE AL MES DE JULIO', 0, 1, '966.22', '966.22', 'SERVICIO', '0.00'),
(2318, 1, 'PAGO DE 2 CUOTA DE SISTEMA DE GESTION HOSPITALARIA, LABORATORIO Y CAFETERIA, CORRESPONDIENTE AL MES DE JUNIO', 0, 1, '966.22', '966.22', 'SERVICIO', '0.00'),
(2319, 1, 'PAGO DE 1 CUOTA DE SISTEMA DE GESTION HOSPITALARIA, LABORATORIO Y CAFETERIA, CORRESPONDIENTE AL MES DE MAYO', 0, 1, '966.22', '966.22', 'SERVICIO', '0.00'),
(2320, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(2321, 1, 'PRUEBA POLIGRAFICA  PRE-EMPLEO ', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(2322, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(2323, 1, 'EVALUACIONES POLIGRAFICAS PRE-EMPLEO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(2324, 1, 'IMPRESORA MATRICIAL F-X890 CLASE A', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2325, 1, 'KIT DE 4 CAMARAS DE SEGURIDAD COLOR VU LITE MARCA HILOOK', 0, 1, '309.74', '309.74', 'SERVICIO', '0.00'),
(2326, 1, 'KIT DE 4 CAMARAS  DE SEGURIDAD COLOR VU LITE ', 0, 1, '309.74', '309.74', 'SERVICIO', '0.00'),
(2327, 1, 'KIT DE 4 CAMARAS DE SEGURIDAD COLOR VU LITE MARCA HILOOK', 0, 1, '309.73', '309.73', 'SERVICIO', '0.00'),
(2328, 1, 'CAJA DE PAPEL TERMICO 80MM 50 UNIDADES', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(2329, 1, 'MONITOR DELL CLASE A 23 PULGADA, GARANTIA 6 MESES', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2330, 1, 'CAJA DE PAPEL TERMICO 80MM 50 UNIDADES', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2331, 1, 'MANTENIMIENTO DE EQUIPOS', 0, 1, '53.10', '53.10', 'SERVICIO', '0.00'),
(2332, 1, 'MATERIALES DE INSTALACION ', 0, 1, '51.33', '51.33', 'SERVICIO', '0.00'),
(2333, 1, 'MONITOR DELL CLASE A PULGADA 19.5', 0, 1, '61.95', '61.95', 'SERVICIO', '0.00'),
(2334, 1, 'EQUIPO LENOVO M92 CORE I5, RAM 8GB, SSD 240GB', 0, 1, '194.69', '194.69', 'SERVICIO', '0.00'),
(2335, 1, 'INSTALACION DE CAMARAS ', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2336, 1, 'REMOVER CAMARAS EX-LOCAL PANAMERICANA', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2337, 1, 'ADAPTADOR USB WIFI', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(2338, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE AGOSTO DE 2022', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2339, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE AGOSTO', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(2340, 1, 'Soporte a Sistema de inventario y facturación para farmacia, correspondiente al mes de Agosto de 2022', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(2341, 1, 'Soporte a Sistema de inventario y facturación para farmacia, correspondiente al mes de Julio de 2022', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(2342, 1, 'BATERIA PARA DVR', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(2343, 1, 'MANTENIMIENTO DE EQUIPOS ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2344, 1, 'MANTENIMIENTO DE DVR', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(2345, 1, 'REPETIDOR WIFI XIAOMI', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2346, 1, 'MODEN ALCATEL LINKZONE2, 4G LTE HOSTPOT', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(2347, 1, 'DISCO DE ESTADO SOLIDO 240GB(INSTALADO EN EQUIPO DE CAJA)', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2348, 1, 'PRIMER CUOTA DE INSTALACION DE CAMARAS', 0, 1, '179.91', '179.91', 'SERVICIO', '0.00'),
(2349, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE JULIO 2022', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(2350, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE AGOSTO 2022', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(2351, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(2352, 1, 'CAJA DE PAPEL TERMICO 80MM 50 UN', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2353, 1, 'CINTA PARA IMPRESORA LX-350  S015631', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(2354, 1, 'LECTOR CODIGO DE BARRA 3NSTAR CS100', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(2355, 1, 'EQUIPO LENOVO THINKCENTRE CORE I3 6TA TODO EN 1 CON DISCO SOLIDO DE 240GB RAM 8GB ', 0, 1, '230.09', '230.09', 'SERVICIO', '0.00'),
(2356, 1, 'FUENTE DE PODER GAMMER', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(2357, 1, 'PAGO DE PUBLICACIONES PARA SOCIALMEDIA CORRESPONDIENTE AL MES DE AGOSTO 2022', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2358, 1, 'PAGO DE DOMINIO ANUAL PUNTOOPTICO.COM.SV', 0, 1, '30.97', '30.97', 'SERVICIO', '0.00'),
(2359, 1, 'MEMORIA KINGSTON USB 64GB', 0, 1, '10.62', '10.62', 'SERVICIO', '0.00'),
(2360, 1, 'SOPORTE PARA SMART TV', 0, 1, '30.97', '30.97', 'SERVICIO', '0.00'),
(2361, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE AGOSTO DE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2362, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE AGOSTO DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2363, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(2364, 1, 'CAMARA COLOR VU DOMO MARCA HIKVISION MICROFONO INCORPORADO ', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(2365, 1, 'caja de papel termico 80mm, 50 unidades', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(2366, 1, ' HP Ink Tank Wireless 415 All-in-One - Impresora multifunción - color - chorro de tinta', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2367, 1, 'PAGO DE 3 CUOTA DE SISTEMA DE GESTION HOSPITALARIA, LABORATORIO Y CAFETERIA, CORRESPONDIENTE AL MES DE AGOSTO 2022', 0, 1, '966.22', '966.22', 'SERVICIO', '0.00'),
(2368, 1, 'PAGO DE 4 CUOTA DE SISTEMA DE GESTION HOSPITALARIA, LABORATORIO Y CAFETERIA, CORRESPONDIENTE AL MES DE AGOSTO 2022', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(2369, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '8.51', '8.51', 'SERVICIO', '0.00'),
(2370, 1, 'MATERIALES DE INSTALACION PARA TOMA CORIIENTE EN SUCURSAL ROOSVELT ', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2371, 1, 'CINTA PARA IMPRESORAS MATRICIALES LX-350', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(2372, 1, 'MANTENIMIENTO DE IMPRESORAS  MATRICIALES', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(2373, 1, 'DISCO DE ESTADO SOLIDO 240GB', 0, 1, '21.78', '21.78', 'SERVICIO', '0.00'),
(2374, 1, 'UPS 600VA ORBITEC', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2375, 1, 'MONITOR HP 19 PULGADA, CLASE A', 0, 1, '61.95', '61.95', 'SERVICIO', '0.00'),
(2376, 1, 'HP ELITE DESK 800 G1 CORE I5 RAM 8GB SSD 240GB', 0, 1, '194.69', '194.69', 'SERVICIO', '0.00'),
(2377, 1, 'IMPRESOR 3NSTAR POS-RPT010', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(2378, 1, 'IMPRESORA MULTIFUNCIONAL TANK WIRELESS 415ALL-IN-ONE -', 0, 1, '185.84', '185.84', 'SERVICIO', '0.00'),
(2379, 1, 'CINTA PARA IMPRESORAS MATRICIALES LX-350', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(2380, 1, 'MANTENIMIENTO DE IMPRESORAS MATRICIALES', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(2381, 1, 'UPS 600VA ORBITEC', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2382, 1, 'MONITOR HP 19 PULGADA, CLASE A', 0, 1, '61.95', '61.95', 'SERVICIO', '0.00'),
(2383, 1, 'HP ELITE DESK 800 G1 CORE I5 RAM 8GB SSD 240GB', 0, 1, '194.69', '194.69', 'SERVICIO', '0.00'),
(2384, 1, 'IMPRESOR 3NSTAR POS-RPT010', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(2385, 1, 'IMPRESORA MULTIFUNCIONAL TANK WIRELESS415ALL-IN-ONE -', 0, 1, '31.99', '31.99', 'SERVICIO', '0.00'),
(2386, 1, 'MATERIALES DE INSTALACION PARA TOMA CORIIENTEEN SUCURSAL ROOSVELT', 0, 1, '34.00', '34.00', 'SERVICIO', '0.00'),
(2387, 1, 'UPS 600VA ORBITEC', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(2388, 1, 'MONITOR HP 19 PULGADA, CLASE A', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(2389, 1, 'HP ELITE DESK 800 G1 CORE I5 RAM 8GB SSD 240GB 	 	 	 	 	', 0, 1, '240.00', '240.00', 'SERVICIO', '0.00'),
(2390, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION ', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(2391, 1, 'MANTENIMIENTO PREVENTIVO PARA UNA SUCURSAL', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(2392, 1, 'ACTUALIZACION DE EQUIPO SERVIDOR DE LA CASA MATRIZ', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(2393, 1, 'CAJA DE PAPELTERMICO 50UN MEDIDAD 80 X 80', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(2394, 1, 'CAJA SUPERFICIAL INTEMPERIE', 0, 1, '3.00', '3.00', 'SERVICIO', '0.00'),
(2395, 1, 'MANO DE OBRA INSTALACION DE 6 CAMARAS', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(2396, 1, 'CABLE UTP CAT5 INDOOR METRO', 0, 1, '0.30', '0.30', 'SERVICIO', '0.00'),
(2397, 1, 'METRO DE CABLE UTP CAT 5 INTEMPERIE', 0, 1, '0.95', '0.95', 'SERVICIO', '0.00'),
(2398, 1, ' METRO DE CABLE UTP CAT 5 INTERIOR', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(2399, 1, 'DVR  8CH RESOLUCION MAXIMA 1080P MARCA HILOOK', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(2400, 1, 'CABLE VGA', 0, 1, '7.08', '7.08', 'SERVICIO', '0.00'),
(2401, 1, 'MANTENIMIENTO PREVENTIVO DE EQUIPO DE ESCRITORIO', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(2402, 1, 'Servicio de Alojamiento de Sistema Informatico,desde Mayo de 2021 hasta Mayo 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2403, 1, 'MATERIALES DE INSTALACION', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(2404, 1, 'CABLE HDMI', 0, 1, '2.65', '2.65', 'SERVICIO', '0.00'),
(2405, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACIÓN', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2406, 1, 'DISCO DURO SATA INTERNO 3.5', 0, 1, '57.52', '57.52', 'SERVICIO', '0.00'),
(2407, 1, 'CAMARA TURBO DOMO 1080P, 2MPX, LENTE FIJO,PLASTICA, IR 20 MTS, MARCA HIKVISION', 0, 1, '19.47', '19.47', 'SERVICIO', '0.00'),
(2408, 1, 'DVR HILOOK  STANDALONE DVR - 8 VIDEO CHANNEL', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(2409, 1, 'PAGO ANUAL SERVICIO DE ALOJAMIENTO ', 0, 1, '104.42', '104.42', 'SERVICIO', '0.00'),
(2410, 1, 'PAGO ANUAL DE SERVICIO DE CORREO', 0, 1, '104.42', '104.42', 'SERVICIO', '0.00'),
(2411, 1, 'PAGO MENSUAL POR SERVICIO DE MARKETING CORRESPONDIENTE AL MES DE OCTUBRE', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(2412, 1, 'PAGO MENSUAL POR SERVICIO DE MARKETING CORRESPONDIENTE AL MES DE SEPTIEMBRE', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(2413, 1, 'PAGO MENSUAL POR SERVICIO DE MARKETING CORRESPONDIENTE AL MES DE AGOSTO', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(2414, 1, 'KIT DE 4 CAMARAS 2 CAMARAS DOMO COLOR VU MICROFONO INCORPORADO Y DOS CAMARAS BULLET 1080P', 0, 1, '309.73', '309.73', 'SERVICIO', '0.00'),
(2415, 1, 'MANTENIMIENTO DE EQUIPOS INFORMATICO', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(2416, 1, 'CABLE HADMI 5MT', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(2417, 1, 'INSTALACION Y CAMBIO DE 4 CAMARAS DE SEGURIDAD ', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(2418, 1, 'CAMARA CON MICROFONO INCORPORADO RESOLUCION DE 1080P', 0, 1, '38.00', '38.00', 'SERVICIO', '0.00'),
(2419, 1, 'CONFIGURACION E INSTALACION DE CAMARAS DE SEGURIDAD 1080P', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(2420, 1, 'METRO DE CABLE UTP CAT 5', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(2421, 1, 'DVR TURBO SLIM DE 16 CH RESOLUCION MAXIMA DE 1080P MARCA HIKVISION', 0, 1, '180.00', '180.00', 'SERVICIO', '0.00');
INSERT INTO `servicios` (`id_servicio`, `estado`, `descripcion`, `id_sucursal`, `id_categoria`, `costo`, `precio`, `tipo_prod_servicio`, `precio_iva`) VALUES
(2422, 1, 'cable xtech usb 2.0, xtc 307', 0, 1, '6.19', '6.19', 'SERVICIO', '0.00'),
(2423, 1, 'equipo lenovo thinkcentre m73 , core i3, 4ta generacion, ram 4gb, hd 250gb, serie: MJ02U27Y, garantia 6 meses', 0, 1, '123.89', '123.89', 'SERVICIO', '0.00'),
(2424, 1, 'equipo lenovo thinkcentre m73 , core i3, 4ta generacion, ram 4gb, hd 250gb,  serie: MJ02LQHL garantia 6 meses', 0, 1, '123.89', '123.89', 'SERVICIO', '0.00'),
(2425, 1, 'DVR TURBO SLIM MARCA HILOOKRESOLUCION MAXIMA DE 1080P', 0, 1, '82.00', '82.00', 'SERVICIO', '0.00'),
(2426, 1, 'teclado multimedia  xtech xtk 160s', 0, 1, '11.30', '11.30', 'SERVICIO', '0.00'),
(2427, 1, 'combo teclado y mouse xtech', 0, 1, '16.95', '16.95', 'SERVICIO', '0.00'),
(2428, 1, 'PAGO DE 4 CUOTA DE SISTEMA DE GESTION HOSPITALARIA, LABORATORIO Y CAFETERIA, CORRESPONDIENTE AL MES DE AGOSTO 2022', 0, 1, '966.22', '966.22', 'SERVICIO', '0.00'),
(2429, 1, 'PAGO DE 4 CUOTA DE SISTEMA DE GESTION HOSPITALARIA, LABORATORIO Y CAFETERIA, CORRESPONDIENTE AL MES DE AGOSTO 2022', 0, 1, '966.22', '966.22', 'SERVICIO', '0.00'),
(2430, 1, 'KIT DE 4 CAMARAS COLOR VU 1080P MICROFONO INCORPORADO ', 0, 1, '398.23', '398.23', 'SERVICIO', '0.00'),
(2431, 1, 'ROLLO DE PAPEL TERMICO 80MM', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(2432, 1, 'INSTALACION DE CAMARAS DE SEGURIDAD DE CAMARAS DE SEGURIDAD ', 0, 1, '120.00', '120.00', 'SERVICIO', '0.00'),
(2433, 1, 'METRO  DE CABLE UTP CAT 5', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(2434, 1, 'SISTEMA DE INVENTARIO Y FACTURACION ', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(2435, 1, 'CPU LENOVO THINKCENTRE M73 CORE I3 4RTA GENERACION  8GB DE RAM 128 SSD S/N: MJ02LQ04', 0, 1, '168.14', '168.14', 'SERVICIO', '0.00'),
(2436, 1, 'KIT DE 4 CAMARAS 2 CAMARAS DOMO COLOR VU MICROFONO INCORPORADO 5MPX Y DOS CAMARAS BULLET 1080P', 0, 1, '353.98', '353.98', 'SERVICIO', '0.00'),
(2437, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(2438, 1, 'METRO DE CABLE UTP CAT 5 ', 0, 1, '0.35', '0.35', 'SERVICIO', '0.00'),
(2439, 1, 'MONITOR DE 14.5 CLASE A ', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(2440, 1, 'DISCO DURO 500GB', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(2441, 1, 'CAJA DE PAPEL TERMICO 80MM PARA POS', 0, 1, '68.14', '68.14', 'SERVICIO', '0.00'),
(2442, 1, 'caja de papel termico 80mm, 50 unidades', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(2443, 1, 'RELOJ BIOMETRICO ZKT ECO MODELO K40 ', 0, 1, '175.00', '175.00', 'SERVICIO', '0.00'),
(2444, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE SEPTIEMBRE DE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2445, 1, 'MANTENIMIENTO A IMPRESORA MATRICIAL ', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(2446, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE SEPTIEMBRE DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2447, 1, 'PAGO DE PUBLICACIONES PARA SOCIALMEDIA CORRESPONDIENTE AL MES DE SEPTIEMBRE 2022', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2448, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE SEPTIEMBRE DE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2449, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE SEPTIEMBRE DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2450, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE SEPTIEMBRE DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2451, 1, 'MEMORIA RAM 4GB', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2452, 1, 'DISCO DE ESTADO SOLIDO 240GB', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(2453, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE SEPTIEMBRE DE 2022', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2454, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE SEPTIEMBRE DE 2022', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2455, 1, 'SEGUNDA CUOTA DE INSTALACION DE CAMARAS', 0, 1, '179.91', '179.91', 'SERVICIO', '0.00'),
(2456, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE SEPTIEMBRE 2022', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(2457, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE SEPTIEMBRE DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2458, 1, '	SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE SEPTIEMBRE DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2459, 1, 'FLETE DE ENVIO', 0, 1, '2.65', '2.65', 'SERVICIO', '0.00'),
(2460, 1, 'ROLLOS DE PAPEL TERMICO PARA POS 57MM', 0, 1, '0.88', '0.88', 'SERVICIO', '0.00'),
(2461, 1, 'PRUEBA POLIGRAFICA PRE-EMPLEO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(2462, 1, 'PRUEBA POLIGRAFICA  PRE-EMPLEO', 0, 1, '17.00', '17.00', 'SERVICIO', '0.00'),
(2463, 1, 'FLETE POR ENVIO', 0, 1, '2.65', '2.65', 'SERVICIO', '0.00'),
(2464, 1, 'PAPEL BOND', 0, 1, '5.75', '5.75', 'SERVICIO', '0.00'),
(2465, 1, 'ROLLOS DE PAPEL TERMICO PARA POS 57MM', 0, 1, '0.88', '0.88', 'SERVICIO', '0.00'),
(2466, 1, 'PATCHCORD DE 1M LINKBASIC', 0, 1, '1.99', '1.99', 'SERVICIO', '0.00'),
(2467, 1, 'CAJA DE PAPEL DE 50 UN MEDIDA 80MM', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2468, 1, 'PAGO DE 4 CUOTA DE SISTEMA DE GESTION HOSPITALARIA, LABORATORIO Y CAFETERIA, CORRESPONDIENTE AL MES DE AGOSTO 2022', 0, 1, '966.22', '966.22', 'SERVICIO', '0.00'),
(2469, 1, 'PAGO DE 5 CUOTA DE SISTEMA DE GESTION HOSPITALARIA, LABORATORIO Y CAFETERIA, CORRESPONDIENTE AL MES DE SEPTIEMBRE 2022', 0, 1, '966.22', '966.22', 'SERVICIO', '0.00'),
(2470, 1, 'CAJA DE PAPEL TERMICO 80 MM 50 UN', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(2471, 1, 'CABLE UTP CAT6 INTERIOR 305MTS', 0, 1, '140.01', '140.01', 'SERVICIO', '0.00'),
(2472, 1, 'IMPRESORA MATRICIAL LQ590, CLASE A', 0, 1, '216.81', '216.81', 'SERVICIO', '0.00'),
(2473, 1, 'CAJA DE DINERO 3NSTAR', 0, 1, '78.32', '78.32', 'SERVICIO', '0.00'),
(2474, 1, 'EQUIPO COMPLETO, CPU LENOVO M73 CORE I3 4RTA GENERACION, SSD 240, MONITOR CLASE A 19.5, MOUSE Y TECLADO', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2475, 1, 'INSTALACION Y CONFIGURACION DE CAMARAS DE SEGURIDAD', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(2476, 1, 'HIKVISION CCTV - DVR 8CH - DVR-108G-F1 1080P ', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(2477, 1, 'FUENTE DE 2 AMP', 0, 1, '6.00', '6.00', 'SERVICIO', '0.00'),
(2478, 1, 'CAJA DE PAPEL TERMICO 100 UNIDADES', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2479, 1, 'CAMARA IWTTO 360 RESISTENTE AL AGUA Y SOL  WIFI MEMORIA SD DE 128 INSTALACION INCLUIDA', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(2480, 1, 'CABLE USB A PARALELO', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(2481, 1, 'EQUIPO DE PUNTO DE VENTA COMPLETO ,  SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '1238.94', '1238.94', 'SERVICIO', '0.00'),
(2482, 1, 'DIFERENCIA DE CAMARA CON AUDIO', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(2483, 1, 'EQUIPO LENOVO M73 CORE I3 DISCO MECANICO 250GB, 4 RAM, MONITOR DELL 19,5 CLASE MOUSE Y TECLADO', 0, 1, '194.69', '194.69', 'SERVICIO', '0.00'),
(2484, 1, 'EQUIPO LENOVO M73 CORE I3 DISCO MECANICO 250GB, 4 RAM, MONITOR DELL 19,5 CLASE MOUSE Y TECLADO', 0, 1, '194.69', '194.69', 'SERVICIO', '0.00'),
(2485, 1, 'EQUIPO LENOVO M73 CORE I3 DISCO MECANICO 250GB, 4 RAM, MONITOR DELL 19,5 CLASE MOUSE Y TECLADO', 0, 1, '194.69', '194.69', 'SERVICIO', '0.00'),
(2486, 1, 'EQUIPO LENOVO M73 CORE I3 DISCO MECANICO 250GB, 4 RAM, MONITOR DELL 19,5 CLASE MOUSE Y TECLADO', 0, 1, '194.69', '194.69', 'SERVICIO', '0.00'),
(2487, 1, 'DISCO DURO DE 1TB', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(2488, 1, 'VIDEO BALUN ', 0, 1, '4.50', '4.50', 'SERVICIO', '0.00'),
(2489, 1, 'CAMARA FULL COLOR 24/7 MARCA HIKVSION TIPO DOMO', 0, 1, '47.50', '47.50', 'SERVICIO', '0.00'),
(2490, 1, 'CAMARA FULL COLOR 24/7 MARCA HIKVSION TIPO BULLET', 0, 1, '47.50', '47.50', 'SERVICIO', '0.00'),
(2491, 1, 'DVR DE 8 CANALES MARCA HILOOK ', 0, 1, '72.00', '72.00', 'SERVICIO', '0.00'),
(2492, 1, 'CAMARA FULL COLOR 24/7 MARCA HIKVSION TIPO BULLET', 0, 1, '47.50', '47.50', 'SERVICIO', '0.00'),
(2493, 1, 'CAMARA FULL COLOR 24/7 MARCA HIKVSION TIPO BULLET', 0, 1, '47.50', '47.50', 'SERVICIO', '0.00'),
(2494, 1, 'CAJA DE PAPEL TERMICO 50U MEDIDA 80 X 80', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2495, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE OCTUBRE 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2496, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE SEPTIEMBRE 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2497, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE OCTUBRE 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2498, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE SEPTIEMBRE 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2499, 1, 'PAPEL TERMICO DE 58MM 100U', 0, 1, '106.19', '106.19', 'SERVICIO', '0.00'),
(2500, 1, 'FLETE', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(2501, 1, 'DISCO DURO DE 1TB', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(2502, 1, 'DVR 8CH MARCA HIKVISION 100MT ', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(2503, 1, 'FUENTE DE PODER DE 1 AMP', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(2504, 1, 'VIDEO BALUN ', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(2505, 1, 'CONECTORES DC ADAPTADOR MACHO Y HEMBRA', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(2506, 1, 'METRO DE CABLE UTP CAT 5', 0, 1, '0.30', '0.30', 'SERVICIO', '0.00'),
(2507, 1, 'CAMARA HIKVISON 1080P TIPO BULLET', 0, 1, '27.00', '27.00', 'SERVICIO', '0.00'),
(2508, 1, 'COBRO EXTRA DVR DE 8', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(2509, 1, 'SISTEMA DE INVENTARIO Y FACTURACION ', 0, 1, '530.97', '530.97', 'SERVICIO', '0.00'),
(2510, 1, 'MANTENIMIENTO DE CAMARAS EN SUCURSALES SAN MIGUEL Y LA UNION', 0, 1, '53.10', '53.10', 'SERVICIO', '0.00'),
(2511, 1, 'KIT DE 4 CAMARAS DE SEGURIDAD 1080P', 0, 1, '300.88', '300.88', 'SERVICIO', '0.00'),
(2512, 1, 'KIT DE 4 CAMARAS DE SEGURIDAD 1080P CON INSTALACION ', 0, 1, '300.88', '300.88', 'SERVICIO', '0.00'),
(2513, 1, 'kit de camaras, 2 color vu hikvision con audio, 2 color vu hilook', 0, 1, '430.00', '430.00', 'SERVICIO', '0.00'),
(2514, 1, 'kit de 4 camaras, 2 color vu hikvision con audio, 2 color vu hilook, 1 disco duro 1t, dvr4 canales hikvision, monitor 22 pulgadas clase a', 0, 1, '430.00', '430.00', 'SERVICIO', '0.00'),
(2515, 1, 'kit de 4 camaras, 2 color vu hikvision con audio, 2 color vu hilook, 1 disco duro 1t, dvr4 canales hikvision, monitor 22 pulgadas clase a', 0, 1, '430.00', '430.00', 'SERVICIO', '0.00'),
(2516, 1, 'EQUIPO CPU HP, CORE I5, 3ERA GENERACION, RAM 8GB', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2517, 1, 'SWITCH 5 PUEROS MARCA TENDA MODEL SG105', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(2518, 1, 'caja de efectivo 3nstar ', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(2519, 1, 'INSTALACION DE CAMARA HIKVISION CON AUDIO', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(2520, 1, 'MENSUALIDAD DE SISTEMA DE GESTION DE RECURSOS HUMANOS', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(2521, 1, 'caja de papel termico 80 mm, 50 unidades', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(2522, 1, 'EQUIPO COMPLETO HP CORE I5  elitedesk 800 8GB RAM 128 SDD, MONITOR DE 19.5 COMBO MOUSE Y TECLADO ', 0, 1, '325.00', '325.00', 'SERVICIO', '0.00'),
(2523, 1, 'ROTURA DE PANTALLAS , ASISTENCIA:  364900', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(2524, 1, 'TECNOLOGICA, ASISTENCIA: 356129', 0, 1, '10.62', '10.62', 'SERVICIO', '0.00'),
(2525, 1, 'SWITCH 8 PUERTOS  MARCA TP-LINK ', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(2526, 1, 'CAJA DE PAPEL TERMICO 80MM, 50 UNIDADES ', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(2527, 1, 'KIT DE CAMARAS COLOR VU, 24/7 CON AUDIO,  CAMARAS COLOR 24/7, 1 DISCO DURO DE 1T, DVR 4 CANALES, BANDEJA DE ACERO EMPOTRABLE EN PARED, MOUSE INALAMBRICO GALO INSTALACION DE CAMARAS GRATIS, ', 0, 1, '487.00', '487.00', 'SERVICIO', '0.00'),
(2528, 1, 'caja de papel termico 80mm, 50 unidades', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(2529, 1, 'EQUIPO COMPLETO HP CORE I5 elitedesk 800, RAM 8GB RAM 240 SSD, MONITOR DE 19.5 COMBO TECLADO Y MOUSE , SERIE: 2UA4330LZK, GARANTIA 6 MESES', 0, 1, '325.00', '325.00', 'SERVICIO', '0.00'),
(2530, 1, 'EQUIPO COMPLETO HP CORE I5 elitedesk 800, RAM 8GB RAM 240 SSD, MONITOR DE 19.5 COMBO TECLADO Y MOUSE , SERIE: 2UA4330LZK, GARANTIA 6 MESES', 0, 1, '287.61', '287.61', 'SERVICIO', '0.00'),
(2531, 1, 'CAJAS DE REGISTRO', 0, 1, '0.89', '0.89', 'SERVICIO', '0.00'),
(2532, 1, 'TAPADERA DE UN PUERTO', 0, 1, '0.89', '0.89', 'SERVICIO', '0.00'),
(2533, 1, 'CANALETA DE 20MM X 15MM CON ADHESIVO ', 0, 1, '0.89', '0.89', 'SERVICIO', '0.00'),
(2534, 1, 'fuentes de podes 5amp', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(2535, 1, 'CONFIGURACIÓN DE EXTENSIONES  Y PLANTA TELEFÓNICA', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(2536, 1, 'EQUIPO COMPLETO LENOVO M73  CORE I3 DISCO SSD DE 240GB  8 DE RAM, MONITOR DE 19,5 CLASE A, MOUSE Y TECLADO.', 0, 1, '275.00', '275.00', 'SERVICIO', '0.00'),
(2537, 1, 'HP Ink Tank Wireless 415 All-in-One - Impresora multifunción ', 0, 1, '210.00', '210.00', 'SERVICIO', '0.00'),
(2538, 1, '	IMPRESOR MATRICIAL FX-890 CLASE A ', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(2539, 1, 'EQUIPO DELL CORE I3, 4 RAM, SSD DE 120GB', 0, 1, '225.00', '225.00', 'SERVICIO', '0.00'),
(2540, 1, 'CABLE PARA CAMARA, SUCURSAL LA BASE', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(2541, 1, 'REPETIDOR XIAMI', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2542, 1, 'REPARACIÓN DE EQUIPO DE OFICINA CIUDAD REAL', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(2543, 1, 'TECLADO CLASE A', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(2544, 1, 'EXTENSIÓN CABLE USB 2MTS', 0, 1, '2.65', '2.65', 'SERVICIO', '0.00'),
(2545, 1, 'CAMARA IWTTO 360', 0, 1, '48.68', '48.68', 'SERVICIO', '0.00'),
(2546, 1, 'MANO DE OBRA INSTALACIÓN DE 7 CAMARAS ', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(2547, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES OCTUBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2548, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES SEPTIEMBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2549, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES AGOSTO 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2550, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES OCTUBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2551, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES SEPTIEMBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2552, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES AGOSTO 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2553, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES OCTUBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2554, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES SEPTIEMBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2555, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES AGOSTO 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2556, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES OCTUBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2557, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES SEPTIEMBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2558, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES AGOSTO 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2559, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES OCTUBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2560, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES SEPTIEMBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2561, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES AGOSTO 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2562, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES OCTUBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2563, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES SEPTIEMBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2564, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES AGOSTO 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2565, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES OCTUBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2566, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES SEPTIEMBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2567, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES AGOSTO 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2568, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES OCTUBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2569, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES SEPTIEMBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2570, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES AGOSTO 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2571, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES OCTUBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2572, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES SEPTIEMBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2573, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES AGOSTO 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2574, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES OCTUBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2575, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES SEPTIEMBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2576, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES AGOSTO 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2577, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES OCTUBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2578, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES SEPTIEMBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2579, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES AGOSTO 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2580, 1, 'instalacion de camaras hikvision color vu 1080', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(2581, 1, 'cambio de dvr de 4 canales por el de dvr8 canales marca hikvision', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(2582, 1, 'pago 1 de: SISTEMA  INVENTARIO,  FACTURACIÓN Y CONTROL DE TRANSPORTE LICENCIA INICIAL, MODALIDAD EN LINEA.', 0, 1, '833.33', '833.33', 'SERVICIO', '0.00'),
(2583, 1, 'pago 1 de: SISTEMA INVENTARIO, FACTURACIÓN Y CONTROL DE TRANSPORTE LICENCIA INICIAL, MODALIDAD EN LINEA.', 0, 1, '833.33', '833.33', 'SERVICIO', '0.00'),
(2584, 1, 'EQUIPO DE ESCRITORIO LENOVO TINY M73 I3-413OT 2.90 GHZ SSD 240 RAM 8G TECLADO MOUSE', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2585, 1, 'PAGO DE PUBLICACIONES PARA SOCIALMEDIA CORRESPONDIENTE AL MES DE OCTUBRE 2022', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2586, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE OCTUBRE DE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2587, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE OCTUBRE DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2588, 1, 'ROLLO DE PAPEL TERMICO MEDIDA 80X40', 0, 1, '1.46', '1.46', 'SERVICIO', '0.00'),
(2589, 1, 'CAJA DE PAPEL TERMICO MEDIDA 57MM, 75 UNIDADES', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(2590, 1, 'IMPRESOR MATRICIAL EPSON MODELO FX-890, GARANTIA 1 AÑO', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(2591, 1, 'GABINETE DE 9U LINKBASIC LINKBASIC', 0, 1, '230.00', '230.00', 'SERVICIO', '0.00'),
(2592, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE OCTUBRE  2022', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(2593, 1, 'TERCERA CUOTA DE INSTALACION DE CAMARAS', 0, 1, '179.91', '179.91', 'SERVICIO', '0.00'),
(2594, 1, 'CABLE  VNC 20 METROS', 0, 1, '21.67', '21.67', 'SERVICIO', '0.00'),
(2595, 1, 'impresor termico 3nstar', 0, 1, '154.87', '154.87', 'SERVICIO', '0.00'),
(2596, 1, 'ups orbitec 600va', 0, 1, '48.67', '48.67', 'SERVICIO', '0.00'),
(2597, 1, 'equipo lenovo thinkcentre m73, ssd120gb, ram 4gb, monitor dell 20 pulgadas, combo teclado y mouse', 0, 1, '203.54', '203.54', 'SERVICIO', '0.00'),
(2598, 1, 'caja de efectivo 3nstar', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(2599, 1, 'lector codigo de barra 3nstar', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(2600, 1, 'instalacion de 2 camaras xiaomi, sucuarsal santa ana', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(2601, 1, 'instalacion de 2 camaras xiaomi, sucursal aguilares', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(2602, 1, 'licencia de sistema de inventario y facturacion, para sucursal santa ana', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2603, 1, 'impresor termico 3nstar', 0, 1, '154.87', '154.87', 'SERVICIO', '0.00'),
(2604, 1, 'ups orbitec 600va', 0, 1, '48.67', '48.67', 'SERVICIO', '0.00'),
(2605, 1, 'equipo lenovo thinkcentre m73, ssd120gb, ram 4gb, monitor dell 20 pulgadas, combo teclado y mouse', 0, 1, '203.54', '203.54', 'SERVICIO', '0.00'),
(2606, 1, 'caja de efectivo 3nstar', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(2607, 1, 'lector codigo de barra 3nstar', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(2608, 1, 'instalacion de 2 camaras xiaomi, sucuarsal santa ana', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(2609, 1, 'instalacion de 2 camaras xiaomi, sucursal aguilares', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(2610, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE OCTUBRE DE 2022', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2611, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE OCTUBRE DE 2022', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2612, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE OCTUBRE DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2613, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE OCTUBRE DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2614, 1, 'SISTEMA DE INVENTARIO Y FACTURACION ', 0, 1, '530.97', '530.97', 'SERVICIO', '0.00'),
(2615, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '302.74', '302.74', 'SERVICIO', '0.00'),
(2616, 1, 'PATCH PANEL 16 PUERTOS CAT5E', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(2617, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '240.00', '240.00', 'SERVICIO', '0.00'),
(2618, 1, 'CPU LENOVO THINKCENTRE M73 CORE I3 RAM 8GB SSD 240G', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(2619, 1, 'APC BACK-UPS BX800L-LM - UPS - CA 120 V', 0, 1, '145.00', '145.00', 'SERVICIO', '0.00'),
(2620, 1, 'COMBO DE TECLADO Y MOUSE', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2621, 1, 'HP 235 - JUEGO DE TECLADO Y RATON INALAMBRICO', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2622, 1, 'CANALETA DE PISO DE 3 PULGADAS', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(2623, 1, 'KEYSTONE CAT5E', 0, 1, '3.00', '3.00', 'SERVICIO', '0.00'),
(2624, 1, 'SWITCH DE 16 PUERTOS', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(2625, 1, 'PATCH PANEL 24 PUERTOS CAT5E', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(2626, 1, 'DVR 16CH HD/AHD/ANALOG DVR 1080P/4MP LITE 1SATA 1 RJ45 1000M', 0, 1, '230.00', '230.00', 'SERVICIO', '0.00'),
(2627, 1, 'GABINETE DE 9U 19 PULGADAS NEGRO', 0, 1, '210.00', '210.00', 'SERVICIO', '0.00'),
(2628, 1, 'FUENTE DE PODER CONMUTADA 16 CHANNEL 120WATT', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(2629, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE OCTUBRE DE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2630, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE SEPTIEMBRE DE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2631, 1, 'LICENCIA DE SISTEMA INVENTARIO Y FACTURACIÓN SUCURSAL 2', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(2632, 1, 'Camara tipo bala de 5MPX, H.265 , IP67, imagenes full color 24/7. Microfono integrado y Ranura de almacenamiento SD hasta 256GB', 0, 1, '127.99', '127.99', 'SERVICIO', '0.00'),
(2633, 1, 'Cámara tipo Bala IP, resolución 2 Mpx, lente fijo, H.265 , Ip67, IR 30 mts, marca Hikvision', 0, 1, '63.11', '63.11', 'SERVICIO', '0.00'),
(2634, 1, 'NVR Mini 8 PoE 1U de 8 canales, soporta camaras 1.000 105.963900 de hasta 6 mpx, Hdmi, H.265  grabacion hasta 4mpx', 0, 1, '143.68', '143.68', 'SERVICIO', '0.00'),
(2635, 1, 'materiales de instalacion y mano de obra para punto de red', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(2636, 1, 'ups orbitec 750va', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(2637, 1, 'equipo lenovo thikcentre m73 ssd 240gb, ram 8gb, garianta 6 meses', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2638, 1, 'INSTALACION Y CONFIGURACION DE ESTACIONES DE COBRO PARA SISTEMA INFORMATICO', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2639, 1, 'fuente de poder 2 amp', 0, 1, '11.50', '11.50', 'SERVICIO', '0.00'),
(2640, 1, 'DAHUA CÁMARA  BULLET 2MP, 2.8MM, IR 20M, IP67, 4 EN 1', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(2641, 1, 'DAHUA CÁMARA DOMO 5MP,FULL COLOR, MICRÓFONO INTERADO,2.8MM, IR 30M, IP67, 4 EN 1', 0, 1, '48.00', '48.00', 'SERVICIO', '0.00'),
(2642, 1, 'INSTALACION Y CONFIGURACION DE SISTEMA DE COBROS DE SERVICIOS MUNICIPALES', 0, 1, '1.00', '1.00', 'SERVICIO', '0.00'),
(2643, 1, 'MANO DE OBRA', 0, 1, '1.00', '1.00', 'SERVICIO', '0.00'),
(2644, 1, 'CONECTORES DC', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(2645, 1, 'FUENTES DE PODER 2AMP', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(2646, 1, 'PARES DE VIDEO BALU', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(2647, 1, 'DISCO DURO SEAGATE EXTERNO 1T', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(2648, 1, 'DVR 4 CANALES 4PX, MARCA HIKVISION ', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(2649, 1, 'CAMARA 1080 CON AUDIO TIPO BULLET  MARCA HIKVISION ', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2650, 1, 'CAMARA TURBO HD COLOR TIPO BULLTE, MARCA HIKVISION ', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(2651, 1, 'gabinete 15u, nexxt', 0, 1, '280.00', '280.00', 'SERVICIO', '0.00'),
(2652, 1, 'gabinete de 15u, marca nexxt', 0, 1, '280.00', '280.00', 'SERVICIO', '0.00'),
(2653, 1, 'gabinete de 15u, marca nexxt', 0, 1, '280.00', '280.00', 'SERVICIO', '0.00'),
(2654, 1, 'MATERIALES DE INSTALACION PARA PROTECCION DE CABLE DE DATOS  Y MANO DE OBRA', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(2655, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE OCTUBRE DE 2022', 0, 1, '309.73', '309.73', 'SERVICIO', '0.00'),
(2656, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE OCTUBRE DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2657, 1, 'CAJA DE PAPEL TERMICO 80MM, 50 UNIDADES', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2658, 1, 'ROLLOS DE PAPEL TERMICO 80MM', 0, 1, '1.50', '1.50', 'SERVICIO', '0.00'),
(2659, 1, 'GABINETE DE 4 UNIDADES LINKBASIC ', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(2660, 1, 'EQUIPO LENOVO M73 CORE I3 4RTA GENERACION SSD 240 GB, 8GB DE RAM, MONITOR DE 19.5,MOUSE Y TECLADI', 0, 1, '212.39', '212.39', 'SERVICIO', '0.00'),
(2661, 1, 'IMPRESOR HP SMART TANK 720 AIO ESCANER IMPRESOR CHORRO DE TINTA', 0, 1, '300.88', '300.88', 'SERVICIO', '0.00'),
(2662, 1, 'mantenimiento preventivo de equipo,  instalacion de sistema operativo y respaldo de datos', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2663, 1, 'equipo lenovo m73, core i3 4ta generacion, ram 4gb, ssd 240, monitor 19 clase a, teclado y mouse, garantia de 1 año', 0, 1, '225.00', '225.00', 'SERVICIO', '0.00'),
(2664, 1, 'CAJA RECTANGULAR PARA CAJA REGISTRO 10 ENTRADAS 150X110X70 MM PG21 IP54 CON CONO', 0, 1, '8.00', '8.00', 'SERVICIO', '0.00'),
(2665, 1, 'EQUIPO DELL 720 CORE I5 RAM 4G SSD 240GB', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(2666, 1, 'rollos de papel termico 80 mm', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(2667, 1, 'computadora lenovo thikcentre m73, monitor 19\' clase a, garantia 6 meses', 0, 1, '194.69', '194.69', 'SERVICIO', '0.00'),
(2668, 1, 'camara con audio 1080p, hikvision', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2669, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO AL MES NOVIEMBRE 2022', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2670, 1, 'tinta para impresora de tanque epson 544 color negro', 0, 1, '12.39', '12.39', 'SERVICIO', '0.00'),
(2671, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE NOVIEMBRE DE 2022', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2672, 1, 'MENSUALIDAD DE SISTEMA DE GESTION DE RECURSOS HUMANOS CORRESPONDIENTE AL MES DE NOVIEMBRE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2673, 1, 'MENSUALIDAD DE SISTEMA DE GESTION DE RECURSOS HUMANOS CORRESPONDIENTE AL MES DE NOVIEMBRE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2674, 1, 'PAGO DE PUBLICACIONES PARA SOCIALMEDIA CORRESPONDIENTE AL MES DE NOVIEMBRE 2022', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2675, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE NOVIEMBRE DE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2676, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE NOVIEMBRE DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2677, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE NOVIEMBRE DE 2022', 0, 1, '309.73', '309.73', 'SERVICIO', '0.00'),
(2678, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE NOVIEMBRE DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2679, 1, 'CUARTA Y ULTIMA  CUOTA DE INSTALACION DE CAMARAS', 0, 1, '179.91', '179.91', 'SERVICIO', '0.00'),
(2680, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE NOVIEMBRE 2022', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(2681, 1, 'SOPORTE A SISTEMA DE GESTION ADMINISTRATIVO CORRESPONDIENTE AL MES DE NOVIEMBRE', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(2682, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE NOVIEMBRE DE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2683, 1, 'MOUSE INALAMBRICO XTECH', 0, 1, '8.00', '8.00', 'SERVICIO', '0.00'),
(2684, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE NOVIEMBRE DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2685, 1, 'MENSUALIDAD DE SISTEMA DE GESTION DE RECURSOS HUMANOS CORRESPONDIENTE AL MES DE NOVIEMBRE 2022', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(2686, 1, 'ACCES POINT D-LINK DAP-1325 N300 WIFI EXT. IPV6 5Y', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(2687, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE NOVIEMBRE DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2688, 1, 'UPS TU-1208LCD  ORBITEC 1200VA-720W LCD CON USB 5-15R OUTLET 8 RJ-11', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(2689, 1, 'FLETE POR ENVIO', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(2690, 1, 'caja de papel termico 80mm, 50 unidades', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2691, 1, 'Forza HT-1000LCD UPS 1000VA/600W 120V 12-NEMA 2-USB 50/60Hz', 0, 1, '115.04', '115.04', 'SERVICIO', '0.00'),
(2692, 1, 'Sensor de movimiento para alarma Hikvision', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(2693, 1, 'Sirena 110dbi para alarma Hikvision', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(2694, 1, 'Sensor de puerta para palarma hikvision', 0, 1, '22.00', '22.00', 'SERVICIO', '0.00'),
(2695, 1, 'KIT Alarma Hikvision Control panel  Wireless 48 zonas', 0, 1, '220.00', '220.00', 'SERVICIO', '0.00'),
(2696, 1, 'EQUIPO LENOVO M73 CORE I3 4RTA GENERACION 240 SSD, 8GB RAM, MONITOR 23, MOUSE Y TECLADO.', 0, 1, '212.39', '212.39', 'SERVICIO', '0.00'),
(2697, 1, 'EQUIPO LENOVO M73 CORE I3 4RTA GENERACION 240 SSD, 8GB RAM, MONITOR 19.5, MOUSE Y TECLADO', 0, 1, '230.09', '230.09', 'SERVICIO', '0.00'),
(2698, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE NOVIEMBRE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2699, 1, 'TURACION PARA SISTEMA DE FARMACIA, AL MES DE OCTUBRE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2700, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE SEPTIEMBRE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2701, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE NOVIEMBRE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2702, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE OCTUBRE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2703, 1, 'SERVICIO DE SOPORTE DE INVENTARIO Y FACTURACION PARA SISTEMA DE FARMACIA, AL MES DE SEPTIEMBRE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2704, 1, 'IMPRESOR 3NSTAR POS-RPT006', 0, 1, '170.00', '170.00', 'SERVICIO', '0.00'),
(2705, 1, 'CAMARA TIPO BALA RESOLUCION DE 1080P 5MP, MICROFONO INCORPORADO, MARCA HIKVISION.', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(2706, 1, 'CAMARA turbo bala HIKVISION, 5MPX con audio', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2707, 1, '2 CABLES DE RED 2MTRS', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(2708, 1, 'CABLE HDMI ', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(2709, 1, 'CABLE DE PODER PARA IMPRESOR MATRICIAL', 0, 1, '4.00', '4.00', 'SERVICIO', '0.00'),
(2710, 1, '1ERA CUOTA DE INSTALACION DE EQUIPO PARA PUNTO DE VENTA MATERIALES Y MANO DE OBRA', 0, 1, '188.79', '188.79', 'SERVICIO', '0.00'),
(2711, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE NOVIEMBRE DE 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2712, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE OCTUBRE DE 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2713, 1, 'ROLLOS DE ETIQUETAS 2 X 1', 0, 1, '7.96', '7.96', 'SERVICIO', '0.00'),
(2714, 1, 'SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '1238.94', '1238.94', 'SERVICIO', '0.00'),
(2715, 1, 'IMPRESOR ZEBRA ZD220', 0, 1, '309.73', '309.73', 'SERVICIO', '0.00'),
(2716, 1, 'cinta para impresora epson, ERC-38b', 0, 1, '7.50', '7.50', 'SERVICIO', '0.00'),
(2717, 1, 'CAJA DE EFECTIVO 3NSTAR', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(2718, 1, 'MANO DE OBRA Y CONFIGURACIÓN DE CÁMARAS', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(2719, 1, 'CAJA RECTANGULAR PESADA 1/2 PLG 3 ENTRADAS INTEMPERIE', 0, 1, '4.25', '4.25', 'SERVICIO', '0.00'),
(2720, 1, 'CONECTOR MACHO TECNO-DUCTO 1/2 PLG', 0, 1, '1.50', '1.50', 'SERVICIO', '0.00'),
(2721, 1, 'CAMATA TURBO TIPO BALA 8MP IR DE 30MTS', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(2722, 1, 'DVR TURBO 4 CANALES 8MP CON TECNOLOGIA ACUSENSE', 0, 1, '115.00', '115.00', 'SERVICIO', '0.00'),
(2723, 1, '30 rollos de papel termico ', 0, 1, '1.02', '1.02', 'SERVICIO', '0.00'),
(2724, 1, 'DISCO DURO DE 1T', 0, 1, '53.98', '53.98', 'SERVICIO', '0.00'),
(2725, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE DICIEMBRE DE 2022', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2726, 1, 'disco de estado solido ssd240gb', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(2727, 1, 'PAGO DE PUBLICACIONES PARA SOCIALMEDIA CORRESPONDIENTE AL MES DE DICIEMBRE 2022', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2728, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE DICIEMBRE DE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2729, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE DICIEMBRE DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2730, 1, 'disco de estado solido ssd240gb', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2731, 1, 'PAGO DE PUBLICACIONES PARA SOCIALMEDIA CORRESPONDIENTE AL MES DE DICIEMBRE 2022', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2732, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE DICIEMBRE DE 2022', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2733, 1, 'SOPORTE TECNICO PARA SISTEMA INFORMATICO CORRESPONDIENTE AL MES DE DICIEMBRE DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2734, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE NOVIEMBRE DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2735, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE DICIEMBRE 2022', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(2736, 1, 'SOPORTE A SISTEMA DE GESTION ADMINISTRATIVO CORRESPONDIENTE AL MES DE DICIEMBRE', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(2737, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE DICIEMBRE DE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2738, 1, 'caja de papel termico 80mm 50 unidades', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2739, 1, 'UPS 600VA ORBITEC', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2740, 1, 'UPS ORBITEC DE 750 VA', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2741, 1, 'DISCO ESTADO  SOLIDO 240', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2742, 1, 'CAMARA EZVIZ 1080P 2MP', 0, 1, '51.33', '51.33', 'SERVICIO', '0.00'),
(2743, 1, 'CAMARA TURBO BALA 1080, LENTE FIJO, IP66, IR 20 MTS,MARCA HIKVISION', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(2744, 1, 'REPETIDOR XIAMI', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(2745, 1, 'CPU LENOVO THINKCENTRE M73 CORE I3 RAM 4GB SSD240G', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2746, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 5 SALIDA', 0, 1, '20.35', '20.35', 'SERVICIO', '0.00'),
(2747, 1, 'IMPRESORA DE TANQUE MULTIFUNCIONAL EPSON', 0, 1, '194.69', '194.69', 'SERVICIO', '0.00'),
(2748, 1, 'TECLADO CLASE A', 0, 1, '8.85', '8.85', 'SERVICIO', '0.00'),
(2749, 1, 'CAMARA IWTTO 360', 0, 1, '48.68', '48.68', 'SERVICIO', '0.00'),
(2750, 1, 'MONITOR HP 19 PULGADA, CLASE A', 0, 1, '61.95', '61.95', 'SERVICIO', '0.00'),
(2751, 1, 'BOBINA DE CABLE UTP CAT5', 0, 1, '56.00', '56.00', 'SERVICIO', '0.00'),
(2752, 1, 'MANO DE OBRA INSTALACIÓN DE 7 CAMARA', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(2753, 1, 'CAMARA TURBO BALA, 2 MPX. LENTE FIJO. IR 20MT', 0, 1, '30.97', '30.97', 'SERVICIO', '0.00'),
(2754, 1, 'Rollos de papel termico ', 0, 1, '1.33', '1.33', 'SERVICIO', '0.00'),
(2755, 1, 'ROLLO PAPEL TERMICO PARA POS', 0, 1, '1.63', '1.63', 'SERVICIO', '0.00'),
(2756, 1, 'EQUIPO LENOVO M73 CORE I3 MEMORIA 8GB SDD 240GB MONITOR DE 20 PULDAS COMBO TECLADO MOUSE', 0, 1, '192.92', '192.92', 'SERVICIO', '0.00'),
(2757, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE DICIEMBRE DE 2022', 0, 1, '309.73', '309.73', 'SERVICIO', '0.00'),
(2758, 1, 'INSTALACIÓN DE 2 REPETIDORES ', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(2759, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE DICIEMBRE DE 2022', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2760, 1, 'CAPACITACIÓN DE USO DE DISPOSITIVO', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(2761, 1, 'INSTALACION DE RED PARA TERMINAR BIOMETRICA Y MATERIALES', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(2762, 1, 'Hikvision - Face recognition terminal - lectura hasta 1.5mts', 0, 1, '325.00', '325.00', 'SERVICIO', '0.00'),
(2763, 1, 'KIT DE 4 CAMARAS 1 CON AUDIO E INSTALACION', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(2764, 1, 'KIT DE 8 CAMARAS 2 CON AUDIO E INSTALACION', 0, 1, '600.00', '600.00', 'SERVICIO', '0.00'),
(2765, 1, 'cambio de dvr4canales por dvr8 canales, marca hikvision', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(2766, 1, 'mano de obra y configuracion por 2 camaras ya instaladas', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2767, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 5 SALIDAS', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(2768, 1, 'INSTALACION DE KIT DE CAMARAS', 0, 1, '355.00', '355.00', 'SERVICIO', '0.00'),
(2769, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE DICIEMBRE DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2770, 1, 'EQUIPO DE FACTURACION PARA PUNTO DE VENTA: 1- MONITOR, 1 CPU LENOVO, 1-LECTOR DE BARRA, 1-CAJA DE EFECTIVO, 1-IMPRESOR TERMICO, TECLADO Y MOUSE', 0, 1, '1178.76', '1178.76', 'SERVICIO', '0.00'),
(2771, 1, 'revision de cable red', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2772, 1, 'estructura para conexion de punto de red para equipo de toma de ordenes', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2773, 1, 'EQUIPO LENOVO THINKCENTRE M710Q I3-6100T 3.2GHZ, monitor clase a dell, ram 8gb, ssd 240gb, cable de poder, teclado y mouse, garantia 1 año', 0, 1, '266.67', '266.67', 'SERVICIO', '0.00'),
(2774, 1, 'CABLE CCTV DE 20 METROS, PARA CAMARA DE SEGURIDAD', 0, 1, '13.28', '13.28', 'SERVICIO', '0.00'),
(2775, 1, 'CAJA RECTANGULAR PARA CAJA REGISTRO 10 ENTRADAS 175X110X66 MM PG21 IP54 CON CONO', 0, 1, '3.00', '3.00', 'SERVICIO', '0.00'),
(2776, 1, 'eva esperanza romero membreño', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2777, 1, 'silvia areli gomez amaya', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2778, 1, 'jose eliseo lemus hernandez', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2779, 1, 'MENSUALIDAD DE SISTEMA DE GESTION DE RECURSOS HUMANOS CORRESPONDIENTE AL MES DE DICIEMBRE 2022', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2780, 1, 'MENSUALIDAD DE SISTEMA DE GESTION DE RECURSOS HUMANOS CORRESPONDIENTE AL MES DE DICIEMBRE  2022', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(2781, 1, 'Mantenimiento y reparación de equipo', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(2782, 1, '1ERA CUOTA DE INSTALACION DE EQUIPO PARA PUNTO DE VENTA MATERIALES Y MANO DE OBRA', 0, 1, '188.79', '188.79', 'SERVICIO', '0.00'),
(2783, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE DICIEMBRE DE 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2784, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE NOVIEMBRE DE 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2785, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE OCTUBRE DE 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2786, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE ENERO 2023', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2787, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE DICIEMBRE 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2788, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE NOVIEMBRE 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2789, 1, 'servicios no contemplados en los tdr', 0, 1, '2325.00', '2325.00', 'SERVICIO', '0.00'),
(2790, 1, 'pago parcial del módulo de créditos', 0, 1, '3000.00', '3000.00', 'SERVICIO', '0.00'),
(2791, 1, 'monitor hp, clase a, hdmi, garantia 6 meses', 0, 1, '61.95', '61.95', 'SERVICIO', '0.00'),
(2792, 1, 'EQUIPO LENOVO THINKCENTRE M710Q I3-6100T 3.2GHZ, cable de poder, ram 8gb,hd 500gb, garantia 6 meses', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2793, 1, 'JUAN CARLOS ARGUETA HERNÁNDEZ ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(2794, 1, 'TERMINAL BIOMETRICA ZKTECO K40', 0, 1, '141.59', '141.59', 'SERVICIO', '0.00'),
(2795, 1, 'EQUIPO LENOVO THINKCENTRE M710Q I3-6100T 3.2GHZ, ssd 240gb, ram 8gb, cable de poder, garantia 6 meses', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2796, 1, 'CINTA PARA IMPRESORA MATRICIAL LX-350', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(2797, 1, 'UPS ORBITEC 600VA', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2798, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE ENERO 2023', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(2799, 1, '2DA CUOTA DE INSTALACION DE EQUIPO PARA PUNTO DE VENTA MATERIALES Y MANO DE OBRA', 0, 1, '188.79', '188.79', 'SERVICIO', '0.00'),
(2800, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ENERO DE 2023', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2801, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE DICIEMBRE DE 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2802, 1, '1ERA CUOTA DE INSTALACION DE EQUIPO PARA PUNTO DE VENTA MATERIALES Y MANO DE OBRA', 0, 1, '188.79', '188.79', 'SERVICIO', '0.00'),
(2803, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE NOVIEMBRE DE 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2804, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE OCTUBRE DE 2022', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2805, 1, 'regleta de conexion multiple', 0, 1, '5.31', '5.31', 'SERVICIO', '0.00'),
(2806, 1, 'CINTA PARA IMPRESORA MATRICIAL LX-350', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(2807, 1, 'UPS ORBITEC 600VA', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(2808, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE ENERO 2023', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(2809, 1, 'rollos de papel termico ', 0, 1, '1.33', '1.33', 'SERVICIO', '0.00'),
(2810, 1, 'FUENTE DE PODER PARA REPARACION DE EQUIPO DE CAJA ', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(2811, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ENERO DE 2023', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2812, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE DICIEMBRE DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2813, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE OCTUBRE DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2814, 1, 'Mantenimiento de kit de camaras en carpinteria', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2815, 1, 'camara con audio 5mp', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2816, 1, 'DVR EPCOM EV4016TURBOR  16CH  4MP LITE PENTAHIBRID 4.265  12VCD HD-TVI CVBS AHD CVI IP', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2817, 1, 'INSTALACIÓN DE 2 CÁMARAS Y MATERIALES ', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(2818, 1, 'fuente de poder 750w, Serie: 1703520702802646', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(2819, 1, 'fuente de poder 750w, Serie: 1703520702802646', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(2820, 1, '1ERA CUOTA PARA SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '265.49', '265.49', 'SERVICIO', '0.00'),
(2821, 1, '1ERA CUOTA PARA SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '442.48', '442.48', 'SERVICIO', '0.00'),
(2822, 1, 'SOPORTE A SISTEMA DE GESTION ADMINISTRATIVO CORRESPONDIENTE AL MES DE ENERO 2023', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(2823, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ENERO 2023', 0, 1, '309.73', '309.73', 'SERVICIO', '0.00'),
(2824, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE ENERO 2023', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2825, 1, 'PAGO DE PUBLICACIONES PARA SOCIALMEDIA CORRESPONDIENTE AL MES DE ENERO 2023', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2826, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE ENERO 2023', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2827, 1, 'SOPORTE TECNICO PARA SISENERO 2023TEMA INFORMATICO CORRESPONDIENTE AL MES DE ', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2828, 1, 'EQUIPO LENOVO THINKCENTRE M710Q I3-6100T 3.2GHZ, ssd 240gb, ram 8gb, monitor clase a, teclado y mouse, garantia 6 meses', 0, 1, '265.49', '265.49', 'SERVICIO', '0.00'),
(2829, 1, 'rollos de papel termico', 0, 1, '1.33', '1.33', 'SERVICIO', '0.00'),
(2830, 1, 'SERVICIO DE SOPORTE A SISTEMA DE FINANCIERO MES DICIEMBRE Y ENERO 2023', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2831, 1, 'CPU Lenovo Thinkcentre m710q i3 7100T Tiny RAM 8GB  SSD 240GB ', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2832, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ENERO DE 2023', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2833, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ENERO 2023', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2834, 1, 'EQUIPO LENOVO THINKCENTRE M73, core i5, ssd 240gb, ram 8gb, monitor clase a, teclado y mouse, garantia 6 meses', 0, 1, '194.69', '194.69', 'SERVICIO', '0.00'),
(2835, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE ENERO 2023', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2836, 1, 'RIMER PAGO DEL 30% PARA EL DISEÑO, CREACIÓN Y DESARROLLO DE SOFTWARE PARA LA GESTIÓN ADMINISTRATIVA Y FINANCIERA DE PROYECTO DE AGUA ADESCOMAV', 0, 1, '840.00', '840.00', 'SERVICIO', '0.00'),
(2837, 1, 'pago anual de dominio operacionesdg.com ', 0, 1, '30.97', '30.97', 'SERVICIO', '0.00'),
(2838, 1, 'ACTUALIZACIÓN COMPLETA DE SISTEMA INFORMÁTICO', 0, 1, '500.00', '500.00', 'SERVICIO', '0.00'),
(2839, 1, 'DISCO ESTADO SOLIDO 480GB', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(2840, 1, 'ROLLO PAPEL TERMICO 80 X 40 MM', 0, 1, '1.92', '1.92', 'SERVICIO', '0.00'),
(2841, 1, 'MATERIALES DE INSTALACION', 0, 1, '61.95', '61.95', 'SERVICIO', '0.00'),
(2842, 1, 'instalacion de 4 camaras ezviz ty1 360, garantia 1 año', 0, 1, '510.00', '510.00', 'SERVICIO', '0.00'),
(2843, 1, 'instalacion de 6 camaras ezviz ty1 360, garantia 1 año', 0, 1, '510.00', '510.00', 'SERVICIO', '0.00'),
(2844, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN CORRESPONDIENTE AL MES DE OCTUBRE DE 2022', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2845, 1, 'equipo lenovo thinkcentre m73, core i5, 4ta generacion, ram 8gb, ssd 240gb, fuente de poder, garantia 1 año', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(2846, 1, 'TELEFONO ALCATEL T65', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(2847, 1, 'EQUIPO LENOVO CORE I5 4TH RAM 8GB SSD 240 UN 12 MESES DE GARANTIA', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(2848, 1, 'MARIA LISSETH OCHOA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2849, 1, 'ULISES JAVIER VELÁSQUEZ REYES ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2850, 1, 'ERICK JAVIER JURADO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2851, 1, 'monitor clase a, garantia 6 meses', 0, 1, '57.52', '57.52', 'SERVICIO', '0.00'),
(2852, 1, 'EQUIPO PUNTO DE VENTA ----lector codigo de barra, impresora, CPU, monitor, teclado, mouse, UPS, caja de efectivo', 0, 1, '1769.91', '1769.91', 'SERVICIO', '0.00'),
(2853, 1, 'KIT DE 3 CAMARAS HIKVISION(DVR 4 CANALES, 2 CAMARAS DOMO 1080P, 1 CAMARA BULLET 1080P, DISCO DURO 1T)  INSTALACION', 0, 1, '252.21', '252.21', 'SERVICIO', '0.00');
INSERT INTO `servicios` (`id_servicio`, `estado`, `descripcion`, `id_sucursal`, `id_categoria`, `costo`, `precio`, `tipo_prod_servicio`, `precio_iva`) VALUES
(2854, 1, 'KIT DE 2 CAMARAS(1 CAMARA BULLET 1080P, 1 CAMARA DOMO 1080P,  DVR 4 CANALES, 1 DISCO DURO 1T,) INSTALACION', 0, 1, '230.00', '230.00', 'SERVICIO', '0.00'),
(2855, 1, 'KIT DE 4 CAMARAS(2 CAMARAS DOMO HIKVISION 1080, 2 CAMARAS BULLET HIKVISION 1080, DISCO DURO 1T, DVR 8 CANALES) INSTALACION', 0, 1, '340.00', '340.00', 'SERVICIO', '0.00'),
(2856, 1, 'IMPRESOR MATRICIAL LX-350', 0, 1, '309.73', '309.73', 'SERVICIO', '0.00'),
(2857, 1, 'FLETE POR ENVIO', 0, 1, '8.85', '8.85', 'SERVICIO', '0.00'),
(2858, 1, 'CAJA DE PAPEL TERMICO 57MM', 0, 1, '69.03', '69.03', 'SERVICIO', '0.00'),
(2859, 1, 'Impresores Bluetooth PT', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2860, 1, 'PAGO DEL 70% PARA EL DISEÑO, CREACIÓN Y DESARROLLO DE SOFTWARE PARA LA GESTIÓN ADMINISTRATIVA Y FINANCIERA DE PROYECTO DE AGUA ADESCOMAV, Y FOTOCOPIADORA MULTIFUNCIONAL MARCA KYOCERA, MODELO: M2040 DN/L', 0, 1, '1940.00', '1940.00', 'SERVICIO', '0.00'),
(2861, 1, 'PAGO DEL 70% PARA EL DISEÑO, CREACIÓN Y DESARROLLO DE SOFTWARE PARA LA GESTIÓN ADMINISTRATIVA Y FINANCIERA DE PROYECTO DE AGUA ADESCOMAV, Y FOTOCOPIADORA MULTIFUNCIONAL MARCA KYOCERA, MODELO: M2040 DN/L', 0, 1, '1940.00', '1940.00', 'SERVICIO', '0.00'),
(2862, 1, 'Vanessa Jeamileth Santos Quintallina', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2863, 1, 'Melvin Omar Berrios Salmerón', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2864, 1, 'cambio de dvr 16 canales hikvision', 0, 1, '116.00', '116.00', 'SERVICIO', '0.00'),
(2865, 1, '	DISCO DURO SATA INTERNO 3.5', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(2866, 1, 'SERVICIO DE SOPORTE A INVENTARIO Y FACTURACION EN LINEA, CORRESPONDIENTE DE FEBRERO 2023', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2867, 1, 'monitor  dell 19 pulgadas, clase a, 1 año de garantia', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(2868, 1, 'MONITOR 19 PULGADAS MARCA DELL', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(2869, 1, 'KIT DE 4 CAMARAS HIKVISION  CON AUDIO   MONITOR CLASE A,   GARANTIA 12 MESES', 0, 1, '398.23', '398.23', 'SERVICIO', '0.00'),
(2870, 1, 'DISCO DURO SATA 3.5 500GB', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(2871, 1, 'ROLLO TERMICO DE 80mm, PARA TICKET', 0, 1, '2.04', '2.04', 'SERVICIO', '0.00'),
(2872, 1, 'IMPRESOR MATRICIAL LX-350', 0, 1, '331.86', '331.86', 'SERVICIO', '0.00'),
(2873, 1, 'UPS ORBITEC DE 750 VA', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2874, 1, 'CPU LENOVO THINKCENTRE M710Q I3 7100T TINY RAM 8GB SSD 240GB', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(2875, 1, 'AURICULARES JBL TUNE 110', 0, 1, '10.62', '10.62', 'SERVICIO', '0.00'),
(2876, 1, 'EQUIPO COMPLETO LENOVO THINKCENTRE M710Q, CORE I3, RAM 8GB, SSD 240GB ,    MONITOR CLASE A ,     TECLADO Y MOUSE,   GARANTIA 12 MESES', 0, 1, '243.36', '243.36', 'SERVICIO', '0.00'),
(2877, 1, 'CAMARA TURBO BALA 1080, LENTE FIJO, IP66, IR 20 MTS, MARCA HIKVISION', 0, 1, '33.00', '33.00', 'SERVICIO', '0.00'),
(2878, 1, 'CAMARA TURBO BALA 1080, LENTE FIJO, IP66, IR 20 MTS, MARCA HIKVISION', 0, 1, '23.00', '23.00', 'SERVICIO', '0.00'),
(2879, 1, 'DISCO DURO SATA INTERNO 3.5', 0, 1, '53.10', '53.10', 'SERVICIO', '0.00'),
(2880, 1, 'FUENTE DE PODER PARA CAMARA CCTV DE 1AMP', 0, 1, '4.43', '4.43', 'SERVICIO', '0.00'),
(2881, 1, 'FUENTE DE PODER 5 AMP CON XPLITER DE 4 SALIDAS', 0, 1, '20.35', '20.35', 'SERVICIO', '0.00'),
(2882, 1, 'VIDEO BALUN', 0, 1, '3.98', '3.98', 'SERVICIO', '0.00'),
(2883, 1, 'ADAPTADOR DC MACHO/HEMBRA CCTV', 0, 1, '1.77', '1.77', 'SERVICIO', '0.00'),
(2884, 1, 'CABLE UTP CAT 5 UNIDAD METRO', 0, 1, '0.24', '0.24', 'SERVICIO', '0.00'),
(2885, 1, 'DVR SLIM TRUBO 8 CANALES, RESOLUCION MAXIMA 1080P, MARCA HIKVISION', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(2886, 1, 'CAMARA TURBO DOMO 1080, CON AUDIO, MARCA HIKVISION', 0, 1, '29.20', '29.20', 'SERVICIO', '0.00'),
(2887, 1, 'CAMARA TURBO BALA 1080, LENTE FIJO, IP66, IR 20 MTS, MARCA HIKVISION', 0, 1, '20.35', '20.35', 'SERVICIO', '0.00'),
(2888, 1, 'MENSUALIDAD DE SISTEMA DE GESTION DE RECURSOS HUMANOS CORRESPONDIENTE AL MES DE ENERO 2023', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(2889, 1, 'INSTALACIÓN DE AIRE CONDICIONADO DE 12000 BTU E INSTALACIÓN ELÉCTRICA 110', 0, 1, '190.00', '190.00', 'SERVICIO', '0.00'),
(2890, 1, 'ANDERSON ESTID VÁSQUEZ MENJÍVAR', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(2891, 1, 'ANDERSON ESTID VÁSQUEZ MENJÍVAR', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2892, 1, 'ANDERSON ESTID VÁSQUEZ MENJÍVAR', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2893, 1, 'KIT 4 CAMARAS 1080 2MP CON INSTALACION', 0, 1, '292.04', '292.04', 'SERVICIO', '0.00'),
(2894, 1, '2DA CUOTA PARA SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '265.49', '265.49', 'SERVICIO', '0.00'),
(2895, 1, '1ER PAGO DE SISTEMA DE INVENTARIO Y FACTURACION ', 0, 1, '530.97', '530.97', 'SERVICIO', '0.00'),
(2896, 1, 'SOPORTE PARA MONITOR DE 15 A 35 PULGADAS', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(2897, 1, 'CAMARA HILOOK 1080 2MP AUDIO INTEGRADO', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2898, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE FEBRERO 2023', 0, 1, '309.73', '309.73', 'SERVICIO', '0.00'),
(2899, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE FEBRERO DE 2023', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(2900, 1, 'caja de papel twrmia alq', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(2901, 1, 'PAGO DE PUBLICACIONES PARA SOCIALMEDIA CORRESPONDIENTE AL MES DE FEBRERO 2023', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2902, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE FEBRERO 2023', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2903, 1, 'SOPORTE TECNICO PARA SISENERO 2023TEMA INFORMATICO CORRESPONDIENTE AL MES DE FEBRERO 2023', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2904, 1, 'ALOJAMIENTO DE PAGINA WEB Y RENOVACION DE DOMINIO DE INTERNET', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(2905, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE FEBRERO 2023', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2906, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DEFEBRERO 2023', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2907, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE FEBRERO 2023', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2908, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE FEBRERO', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(2909, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE FEBRERO 2023', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(2910, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE FEBRERO 2023', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(2911, 1, 'SOPORTE A SISTEMA DE GESTION ADMINISTRATIVO CORRESPONDIENTE AL MES DE FEBRERO 2023', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(2912, 1, 'monitor HDMI de 22\" hp, garantia 6 meses', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2913, 1, 'MANO DE OBRE E INSTALACION DE KIT DE 8 CAMARAS CCTV', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2914, 1, 'MONITOR LCD 20\" (19.5\" VISIBLES) ACER V206HQL - 1600 X 900 HD  60 Hz', 0, 1, '145.00', '145.00', 'SERVICIO', '0.00'),
(2915, 1, 'EQUIPO LENOVO THINKCENTRE M710Q I3-6100T 3.2GHZ', 0, 1, '225.00', '225.00', 'SERVICIO', '0.00'),
(2916, 1, 'EQUIPO PARA SERVIDOR DELL OPTIPLEX 5040 i5 6500', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(2917, 1, 'CAMARAS COLOR VU  HIKVISION  1080, 3K', 0, 1, '56.00', '56.00', 'SERVICIO', '0.00'),
(2918, 1, 'kit de 4 camaras, 1 camara con audio, 2 camaras de metal, 1 camara 1080p, ', 0, 1, '340.00', '340.00', 'SERVICIO', '0.00'),
(2919, 1, 'kit de 4 camaras, 2 camaras con audio, hikvision', 0, 1, '330.00', '330.00', 'SERVICIO', '0.00'),
(2920, 1, '1ER PAGO DE SISTEMA DE INVETARIO Y FACTURACION PARA FARMACIA', 0, 1, '575.22', '575.22', 'SERVICIO', '0.00'),
(2921, 1, 'KIT 3 CAMARAS HIKVISION', 0, 1, '290.00', '290.00', 'SERVICIO', '0.00'),
(2922, 1, 'kit de 4 camaras, 2 camaras con audio, 1 camara de metal, 1 camara 1080p', 0, 1, '350.00', '350.00', 'SERVICIO', '0.00'),
(2923, 1, 'kit de 4 camaras, 1 camara con audio, 2 camaras de metal, 1 camara 1080p', 0, 1, '340.00', '340.00', 'SERVICIO', '0.00'),
(2924, 1, 'kit de 3 camaras hikvision', 0, 1, '256.64', '256.64', 'SERVICIO', '0.00'),
(2925, 1, 'KIT DE 4 CAMARAS DVR 4CH', 0, 1, '340.00', '340.00', 'SERVICIO', '0.00'),
(2926, 1, 'KIT 4 CAMARAS 1080 2MP DVR DE 8CH', 0, 1, '350.00', '350.00', 'SERVICIO', '0.00'),
(2927, 1, '2 PUNTOS DE RED Y 2 TOMAS CORRIENTE Y MATERIALES', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(2928, 1, 'INSTALACION DE 4 TIMBRES ', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2929, 1, 'COMBO TECLADO MOUSE', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(2930, 1, 'CAMARA 360 ', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(2931, 1, 'BOTE DE TINTA EPSON 664 BLACK', 0, 1, '12.00', '12.00', 'SERVICIO', '0.00'),
(2932, 1, 'instalacion de camaras 3 camaras hikvision', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2933, 1, 'CAMARA 1080 2MP HIKVISION', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(2934, 1, 'instalacion de 2  camras hikvision    accesorios', 0, 1, '420.35', '420.35', 'SERVICIO', '0.00'),
(2935, 1, 'instalacion de camaras hikvision, sucursal del centro', 0, 1, '552.21', '552.21', 'SERVICIO', '0.00'),
(2936, 1, 'IMPRESOR MATRICIAL FX-890 CLASE A', 0, 1, '265.49', '265.49', 'SERVICIO', '0.00'),
(2937, 1, 'soporte a inventario y facturacion correspondiente al mes de febrero 2023', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2938, 1, 'instalacion de 2 camras hikvision accesorios', 0, 1, '420.35', '420.35', 'SERVICIO', '0.00'),
(2939, 1, 'instalacion de camaras hikvision, sucursal del centro', 0, 1, '552.21', '552.21', 'SERVICIO', '0.00'),
(2940, 1, 'IMPRESOR MATRICIAL FX-890 CLASE A	26', 0, 1, '265.49', '265.49', 'SERVICIO', '0.00'),
(2941, 1, 'camara hikvision 1080p', 0, 1, '26.55', '26.55', 'SERVICIO', '0.00'),
(2942, 1, 'flete de viaticos', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(2943, 1, 'SOPORTE EXTENDIDO CORRESPONDIENTE AL MES DE MARZO ', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(2944, 1, 'SISTEMA PARA INVENTARIO Y FACTURACION CON EQUIPO PUNTO DE VENTA ... CPU LUNEOVO THINKCENTRE M710Q, MONITOR CLASE A, CAJA PARA EFECTIVO, LECTOR CODIGO DE BARRA, IMPRESOR DE TICKET, UPS ORBITEC 600VA', 0, 1, '1150.44', '1150.44', 'SERVICIO', '0.00'),
(2945, 1, 'DISCO DURO 500 GB (VARIEDAD MARCA)', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(2946, 1, 'DISCO DURO DE ESTADO RIGIDO O HDD - 1 TB - 3.5\" ', 0, 1, '56.00', '56.00', 'SERVICIO', '0.00'),
(2947, 1, 'DISCO DE ESTADO SOLIDO MARCA KINGSTON - SSD - 480 GB - 2.5\"', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(2948, 1, 'DISCO DE ESTADO SOLIDO MARCA KINGSTON - SSD - 240 GB - 2.5\"', 0, 1, '32.00', '32.00', 'SERVICIO', '0.00'),
(2949, 1, 'LECTOR DE CODIGOS DE BARRAS MARCA 3nStar', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(2950, 1, 'MANTENIMIENTO PREVENTIVO DE EQUIPO DE OFICINA E INSTALACION DE SISTEMA OPERATIVO INCLUYENDO RESPALDO DE INFORMACION', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(2951, 1, 'MANTENIMIENTO PREVENTIVO DE EQUIPO DE OFICINA', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(2952, 1, 'SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '1283.19', '1283.19', 'SERVICIO', '0.00'),
(2953, 1, 'CPU LENOVO THINKCENTRE M71Q, CORE I3 DE 7MA GENERACION, RAM 8GB, SSD 240GB, MONITOR 22 PULGADA HDMI CLASE A, UPS ORBITEC 600VA', 0, 1, '270.00', '270.00', 'SERVICIO', '0.00'),
(2954, 1, '2DO PAGO DE SISTEMA DE INVENTARIO Y FACTURACION PARA FARMACIA', 0, 1, '650.00', '650.00', 'SERVICIO', '0.00'),
(2955, 1, '2 SERVICIO DE POLIGRAFIA PARA JOSE OSMIN LEMUS, LUIS ALONSO FLORES CRUZ ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2956, 1, 'SISTEMA DE INVENTARIO Y FACTURACION', 0, 1, '641.59', '641.59', 'SERVICIO', '0.00'),
(2957, 1, 'CAMARA JORTAN CON MEMORIA SD DE 32GB', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(2958, 1, 'instalacion de puntos de red', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(2959, 1, 'instalacion de tomas corrientes', 0, 1, '22.12', '22.12', 'SERVICIO', '0.00'),
(2960, 1, 'KIT DE 4 CAMARAS HILOOK CON AUDIO, DVR DE 4 CANALES, FUENTE DE PODER, VIDEO VALUM, CONECTORES DC, 80 MTEROS DE CABLE UTP, DISCO DURO DE 500GB ', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(2961, 1, 'INSTALACION DE CAMARAS IP ', 0, 1, '17.70', '17.70', 'SERVICIO', '0.00'),
(2962, 1, 'REVISION ELECTRICA', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(2963, 1, '3RA CUOTA PARA SISTEMA DE INVENTARIO Y FACTURACION ', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(2964, 1, 'PAPEL TERMICO POS DE 57MM', 0, 1, '1.25', '1.25', 'SERVICIO', '0.00'),
(2965, 1, 'CAJA DE PAPEL TERMICO 80MM 50 UNIDADES', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(2966, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE FEBRERO DE 2023', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2967, 1, '2DA CUOTA DE INSTALACION DE EQUIPO PARA PUNTO DE VENTA MATERIALES Y MANO DE OBRA', 0, 1, '188.79', '188.79', 'SERVICIO', '0.00'),
(2968, 1, 'TELÉFONO IP MARCA GRANDSTREAM, MODELO GXP1625, DOBLE PUERTO ETHERNET 10/100, 2 CUENTAS SIP, POE', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(2969, 1, 'CONFIGURACIÓN Y MOVER CAMARAS CCTV  CON MANTENIMIENTO', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(2970, 1, 'INSTALACIÓN DE  DE 3 TOMA CORRIENTE CON MATERIALES', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(2971, 1, 'KIT DE 4 CAMARAS DOMO HDD 1T DVR 4CH', 0, 1, '359.16', '359.16', 'SERVICIO', '0.00'),
(2972, 1, 'CPU LENOVO THINKCENTRE M710q I3 7100T RAM 8GB SSD 240GB SERIE: MJ06S96B', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2973, 1, 'CPU LENOVO THINKCENTRE M710q I3 7100T RAM 8GB SSD 240GB SERIE: MJ06S9NT', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2974, 1, 'CPU LENOVO THINKCENTRE M710q I3 7100T RAM 8GB SSD 240GB SERIE: MJ06S97E', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2975, 1, 'CPU LENOVO THINKCENTRE M710q I3 7100T RAM 8GB SSD 240GB SERIE: MJ06S9NB', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2976, 1, 'MONITOR 19.5 CLASE A ', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(2977, 1, 'CAJA DE EFECTIVO 3NSTAR ', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(2978, 1, 'UPS ORBITEC 600V A', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(2979, 1, 'REMOVER CAMARAS ', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(2980, 1, 'INSTALACIÓN Y CONFIGURACIÓN DE SISTEMA DE RESTAURANTE Y CAMARAS', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(2981, 1, 'caja de papel termico 57mm, para pos', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(2982, 1, 'FLETE DE ENVIO DE TRANSPORTE ', 0, 1, '4.42', '4.42', 'SERVICIO', '0.00'),
(2983, 1, 'PAPEL POS 57MM', 0, 1, '1.33', '1.33', 'SERVICIO', '0.00'),
(2984, 1, 'FLETE DE ENVIO TRANSPORTE', 0, 1, '4.42', '4.42', 'SERVICIO', '0.00'),
(2985, 1, 'PAPEL POS 57 MM', 0, 1, '1.33', '1.33', 'SERVICIO', '0.00'),
(2986, 1, 'CAMARAS EPCOM BULLETH DE METAL ', 0, 1, '24.00', '24.00', 'SERVICIO', '0.00'),
(2987, 1, 'CAMARAS HIKVISION TIPO DOMO ', 0, 1, '23.00', '23.00', 'SERVICIO', '0.00'),
(2988, 1, 'CAMARAS HIKVISION TIPO BULLETH, 2 METAL Y 4 PLASTICAS', 0, 1, '24.00', '24.00', 'SERVICIO', '0.00'),
(2989, 1, 'DVR DE 8 CANALES HIKVISION ', 0, 1, '80.00', '80.00', 'SERVICIO', '0.00'),
(2990, 1, 'DISCO DURO DE 1TB ', 0, 1, '57.00', '57.00', 'SERVICIO', '0.00'),
(2991, 1, 'CAMARA WIFI 360 MEMORIA DE SD DE 128GB AUDIO EN 2 VIAS ALARMA DETECTOR DE MOVIMIENTO', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(2992, 1, 'DAHUA CAMARA  EYEBALL DH-HAC-HDW1239TN-A-LED 2MP 40M-2.8mm-S2 FULL COLOR HDCVI 6923172506245', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(2993, 1, 'PAGO DE PUBLICACIONES PARA SOCIALMEDIA CORRESPONDIENTE AL MES DE MARZO 2023', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(2994, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE MARZO 2023', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(2995, 1, 'SOPORTE TECNICO PARA SISENERO 2023TEMA INFORMATICO CORRESPONDIENTE AL MES DE MARZO  2023', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2996, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE MARZO 2023', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(2997, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE MARZO 2023', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(2998, 1, 'SOPORTE A SISTEMA DE GESTION ADMINISTRATIVO CORRESPONDIENTE AL MES DE MARZO 2023', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(2999, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE MARZO DE 2023', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(3000, 1, 'DISCO DE ESTADO SOLIDO SSD 960GB', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(3001, 1, 'SOPORTE A SISTEMA DE FINANCIERO CORRESPONDIENTE AL MES DE FEBRERO 2023', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(3002, 1, 'SOPORTE A SISTEMA DE FINANCIERO CORRESPONDIENTE AL MES DE MARZO 2023', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(3003, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE MARZO DE 2023', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(3004, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE FEBRERO DE 2023', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(3005, 1, 'SISTEMA DE INVENTARIO Y PUNTO DE VENTA PARA FERRETERIA, CPU LENOVO, UPS ORBITEC 600VA, MONITOR 19 PULGADAS, LECTOR DE BARRA, CAJA DE EFECTIVO 3NSTAR, TECLADO, MOUSE, IMPRESOR TERMICO 3NSTAR', 0, 1, '1238.94', '1238.94', 'SERVICIO', '0.00'),
(3006, 1, 'DISCO DE ESTADO SOLIDOS 480 GB', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(3007, 1, 'MANTENIMIENTO DE EQUIPOS INFORMATICOS', 0, 1, '125.00', '125.00', 'SERVICIO', '0.00'),
(3008, 1, 'ACTUALIZACION COMPLETA DE SISTEMA INFORMATICO', 0, 1, '600.00', '600.00', 'SERVICIO', '0.00'),
(3009, 1, 'EQUIPO CPU CORRE I3 7TH GENERACION MONITOR DE 19 PULAGADAS Y TECLADO ', 0, 1, '243.36', '243.36', 'SERVICIO', '0.00'),
(3010, 1, 'FLETE DE ENVIO ', 0, 1, '4.42', '4.42', 'SERVICIO', '0.00'),
(3011, 1, 'ROLLOS DE PAPEL TERMICO 80MM ', 0, 1, '53.10', '53.10', 'SERVICIO', '0.00'),
(3012, 1, 'BATERIA 7 AH PARA UPS', 0, 1, '19.47', '19.47', 'SERVICIO', '0.00'),
(3013, 1, 'DISCO DE ESTADO SOLIDOS SSD240GB', 0, 1, '30.97', '30.97', 'SERVICIO', '0.00'),
(3014, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE MARZO  2023', 0, 1, '309.73', '309.73', 'SERVICIO', '0.00'),
(3015, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE MARZO 2023', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(3016, 1, 'SISTEMA DE INVENTARIO Y FACTURACION, EQUIPO COMPLETO PARA PUNTO DE VENTA', 0, 1, '1106.19', '1106.19', 'SERVICIO', '0.00'),
(3017, 1, 'SISTEMA DE INVENTARIO Y FACTURACION ', 0, 1, '700.00', '700.00', 'SERVICIO', '0.00'),
(3018, 1, 'TV Xiaomi Mi P1 - 32\" Clase diagonal TV LCD con retroiluminación LED - Smart TV', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(3019, 1, 'Terminal multi-biométrica IP para la gestión de tiempo y asistencia de empleados, soporta métodos de verificación por medio de rostro, huella digital, tarjeta, contraseña y combinaciones entre los anteriores además de funciones básicas de Control de Acceso, incluye actualización adms, marca ZKTeco ', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(3020, 1, 'SISTEMA DE INVENTARIO Y FACTURACION, EQUIPO COMPLETO PARA PUNTO DE VENTA', 0, 1, '1592.92', '1592.92', 'SERVICIO', '0.00'),
(3021, 1, 'MONITOR CLASE A, 19 PULGADAS', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(3022, 1, 'EQUIPO CPU LENOVO THINKCENTRE M710Q,  TECLADO Y MOUSE', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(3023, 1, 'IMPRESOR DE TICKET 3NSTAR', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(3024, 1, 'LECTOR PARA CODIGO DE BARRA', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(3025, 1, 'CAJA PARA EFECTIVO 3NSTAR', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(3026, 1, 'SISTEMA DE INVENTARIO Y FACTURACION ', 0, 1, '787.61', '787.61', 'SERVICIO', '0.00'),
(3027, 1, 'CAMARA HIKVISION 5MP ', 0, 1, '35.39', '35.39', 'SERVICIO', '0.00'),
(3028, 1, 'DVR 16CH 4MP EPCOM', 0, 1, '203.53', '203.53', 'SERVICIO', '0.00'),
(3029, 1, '2 CAJAS DE PAPEL TERMICO 80 MM ', 0, 1, '158.41', '158.41', 'SERVICIO', '0.00'),
(3030, 1, 'CAJA DE EFECTIVO 3NSTAR', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(3031, 1, 'SOPORTE DE INVENTARIO Y FACTURACION CORRESPONDIENTE A LOS MESES DE ENERO A DICIEMBRE DE 2021', 0, 1, '6213.27', '6213.27', 'SERVICIO', '0.00'),
(3032, 1, 'EQUIPO DELL OPTIPLEX 7040 CORE I5 DE 6TA GENERACION, RAM 8GB, SSD 240G, HD 500GB', 0, 1, '353.98', '353.98', 'SERVICIO', '0.00'),
(3033, 1, 'SERVICIO DE PRUEBA POLIGRAFO PARA LOS ASPIRANTE:  SALVADOR VILLATORO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3034, 1, 'JAIME OTTONIEL ALVARENGA SARAVIA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3035, 1, 'EDGAR FRANCISCO MEDRANO GOMEZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3036, 1, 'LUIS ALBERTO ROMERO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3037, 1, 'JOSE DIMAS GONZALES GUERRERO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3038, 1, 'KEVIN FABRICIO AGUILAR ARGUETA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3039, 1, 'DOMINGO DEL CARMEN VASQUEZ ORELLANA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3040, 1, 'JOSE OBDULIO SERPAS LOZANO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3041, 1, 'SISTEMA DE INVENTARIO Y FACTURACION, EQUIPO CPU LENOVO THINKCENTRE M710Q, SERIE MJ06S88Q, TECLADO Y MOUSE, MONITOR DE 19 PULGADAS, IMPRESOR DE TICKET 3NSTAR, LECTOR PARA CODIGO DE BARRA, CAJA DE EFECTIVO 3NSTAR', 0, 1, '1300.00', '1300.00', 'SERVICIO', '0.00'),
(3042, 1, 'SERVICIO DE PRUEBA POLIGRAFO PRE EMPLEO DE LOS ASPIRANTES: SALVADOR VILLATORO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3043, 1, 'JAIME OTTONIEL ALVARENGA SARAVIA ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3044, 1, 'DAMIS ALEXIS GONZALES MOREIRA ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3045, 1, 'EDGAR FRANCISCO MEDRANO GOMEZ ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3046, 1, 'LUIS ALBERTO ROMERO ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3047, 1, 'JOSE DIMAS GONZALES GUERRERO ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3048, 1, 'KEVIN FABRICIO AGUILAR ARGUETA ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3049, 1, 'DOMINGO DEL CARMEN VASQUEZ ORELLANA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3050, 1, 'JOSE OBDULIO SERPAS LOZANO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3051, 1, 'Rollo de viñeta y flete de envio ', 0, 1, '11.50', '11.50', 'SERVICIO', '0.00'),
(3052, 1, 'CAJA DE 50 UNIDADES PAPEL TERMICO 80MM ', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(3053, 1, 'Asistencia Tecnológica caso No. 433426', 0, 1, '10.62', '10.62', 'SERVICIO', '0.00'),
(3054, 1, 'CAJA DE PAPEL DE 60 UNIDADES DE 80MM ', 0, 1, '108.00', '108.00', 'SERVICIO', '0.00'),
(3055, 1, 'ROLLOS DE PAPEL TERMICO DE 80 MM', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(3056, 1, 'ROLLOS DE PAPEL TERMICO DE 80 MM', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(3057, 1, 'Rollo de viñeta', 0, 1, '7.96', '7.96', 'SERVICIO', '0.00'),
(3058, 1, 'kit de 8 camaras hikvision, con instalcion ', 0, 1, '619.47', '619.47', 'SERVICIO', '0.00'),
(3059, 1, 'cepu lenovo thinkcentre m710q, garantia 12 meses', 0, 1, '220.00', '220.00', 'SERVICIO', '0.00'),
(3060, 1, 'IMPRESOR MATRICIAL LX-350', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(3061, 1, 'ACTUALIZACION DE SISTEMA Y CALIBRACION DE FACTURAS', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(3062, 1, 'IMPRESOR MATRICIAL FX-890, CLASE A', 0, 1, '250.00', '250.00', 'SERVICIO', '0.00'),
(3063, 1, 'SOPORTE EXTENDIDO CORRESPONDIENTE AL MES DE ABRIL ', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(3064, 1, 'SOPORTE EXTENDIDO CORRESPONDIENTE AL MES DE MARZO ', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(3065, 1, 'KIT DE 4 CAMARAS HIKVISION CON INSTALACION ', 0, 1, '330.00', '330.00', 'SERVICIO', '0.00'),
(3066, 1, 'disco duro para servidor dell 3.5 2t, hot swap', 0, 1, '333.33', '333.33', 'SERVICIO', '0.00'),
(3067, 1, 'CAJA DE 50 UNIDADES PAPEL TERMICO 80MM ', 0, 1, '77.43', '77.43', 'SERVICIO', '0.00'),
(3068, 1, 'MENSUALIDAD DE SISTEMA DE GESTION DE RECURSOS HUMANOS CORRESPONDIENTE AL MES DE MARZO 2023', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(3069, 1, 'REUBICACION DE CAMARA ', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(3070, 1, 'MENSUALIDAD DE SISTEMA DE GESTION DE RECURSOS HUMANOS CORRESPONDIENTE AL MES DE MARZO 2023', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(3071, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE MARZO 2023', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(3072, 1, 'disco duro 500gb, marca variedad ', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(3073, 1, 'SERVICIO DE MANTENIMIENTO A EQUIPO Y CAMBIO DE DISCO SSD ', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(3074, 1, 'SERVICIO DE PRUEBA POLIGRAFO PRE EMPLEO DE LOS ASPIRANTES: EDWIN FERNANDO SALMERON TREJO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3075, 1, 'YEISON OMAR URRUTIA ULLOA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3076, 1, 'LUIS ALBERTO CASTILLO BLANCO ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3077, 1, 'JOSE MANUEL VELASQUEZ CHICAS ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3078, 1, 'MIGUEL ANGEL SOLIS MORENO ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3079, 1, 'SERVICIO DE MANTENIMIENTO A EQUIPO, CAMBIO DE DISCO DURO Y MEMORIA RAM DE 4 GB', 0, 1, '57.52', '57.52', 'SERVICIO', '0.00'),
(3080, 1, 'SERVICIO DE PRUEBA POLIGRAFO PRE EMPLEO DE LOS ASPIRANTES: FREDIS ARISTIDES VASQUEZ Y REMBER ALEXANDER SALAMANCA REYES', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3081, 1, 'DISCOS SOLIDOS SSD 960GB KINSTONG', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(3082, 1, 'DISCO SOLIDO SSD 960GB KINSTONG', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(3083, 1, 'DISCO SOLIDOS', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(3084, 1, 'DISCO SOLIDO SSD 960GB KINSTONG', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(3085, 1, 'cable hdmi', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(3086, 1, 'kit de 8 camaras hikvision con audio 1080p, dvr 8 canales, disco duro de 2t, 2 fuentes de poder, tv led tcl 32 pulgadas, gabinete de 9unidades, cable hdmi 15mts, soporte para tv, 200mts de cable utp, ups orbtitec 600va, mano de obra', 0, 1, '1489.00', '1489.00', 'SERVICIO', '0.00'),
(3087, 1, 'SERVICIO DE PRUEBA POLIGRAFO PARA LOS ASPIRANTES EVALUADOS: FABRICIO JOEL BERRIOS SANDOVAL', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3088, 1, 'ELVIRA DE LA PAZ ALFARO COCA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3089, 1, 'JUAN CARLOS ROGEL RODRIGUEZ ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3090, 1, 'CARLOS MAURICIO VASQUEZ ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3091, 1, 'KEVIN ALEXIS ORELLANA GUZMAN', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3092, 1, 'MARINA DEL CARMEN GARCIA FUENTES', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3093, 1, 'KAREN BEATRIZ VALENCIA SORTO ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3094, 1, 'BELYIN GUADALUPE CHAVEZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3095, 1, 'JESSICA YANETH SORIANO DE CONTRERAS', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3096, 1, 'WENDY ELISETH GUZMAN DE FERNANDEZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3097, 1, 'SERVICIO DE PRUEBA POLIGRAFO DEL ASPIRANTE NOE OSMAR DIAZ ORTIZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3098, 1, 'EVALUACIONES POLIGRAFICAS PRE EMPLEO ', 0, 1, '15.04', '15.04', 'SERVICIO', '0.00'),
(3099, 1, 'caja de papel termico 80mm, 50 unidades', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(3100, 1, 'EQUIPO LENOVO THINKCENTRE ', 0, 1, '275.00', '275.00', 'SERVICIO', '0.00'),
(3101, 1, 'UPS ORBITEC 600VA', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(3102, 1, 'SERVICIO DE PRUEBA POLIGRAFO PRE EMPLEO DE LOS ASPIRANTES: OSCAR NAPOLEON PAIZ PAIZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3103, 1, 'EDENILSON ALEXANDER VILLALOBOS', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3104, 1, 'NOE OSMAR DIAZ ORTIZ ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3105, 1, 'BATERIA PARA UPS 7ah', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(3106, 1, 'TARJETA WIFI MICRO USB', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(3107, 1, '4TA CUOTA PARA SISTEMA DE INVENTARIO Y FACTURACION ', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(3108, 1, 'FELETE DE ENVIO', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(3109, 1, 'CAJA CANALETA BLANCO ', 0, 1, '1.60', '1.60', 'SERVICIO', '0.00'),
(3110, 1, 'SOPORTE DE TV 25-52 PULGADAS ', 0, 1, '17.50', '17.50', 'SERVICIO', '0.00'),
(3111, 1, 'CAJA DE PAPEL DE 50 UNIDADES 80MM', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(3112, 1, 'CAJA DE PAPEL DE 50 UNIDADES DE 80MM', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(3113, 1, 'PAPEL ', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(3114, 1, 'CAJA DE PAPEL 50 UNIDADES 80MM', 0, 1, '84.07', '84.07', 'SERVICIO', '0.00'),
(3115, 1, 'MONITOR HDMI ', 0, 1, '60.00', '60.00', 'SERVICIO', '0.00'),
(3116, 1, 'EQUIPO CPU CORRE I3 7THA GENERACION 8RAM 240 DISCO SOLIDO ', 0, 1, '170.00', '170.00', 'SERVICIO', '0.00'),
(3117, 1, 'PAGO DE PUBLICACIONES PARA SOCIALMEDIA CORRESPONDIENTE AL MES DEABRIL 2023', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3118, 1, 'SERVICIO DE SOCIAL MEDIA CORRESPONDIENTE AL MES DE ABRIL 2023', 0, 1, '110.62', '110.62', 'SERVICIO', '0.00'),
(3119, 1, 'SOPORTE TECNICO PARA SISENERO 2023TEMA INFORMATICO CORRESPONDIENTE AL MES DE ABRIL 2023', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(3120, 1, 'IMPRESOR DE TICKET 3NSTAR', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(3121, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ABRIL 2023', 0, 1, '309.73', '309.73', 'SERVICIO', '0.00'),
(3122, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACIÓN DEL MES DE ABRIL 2023', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(3123, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE ABRIL 2023', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(3124, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION DEL MES DE MARZO 2023', 0, 1, '66.37', '66.37', 'SERVICIO', '0.00'),
(3125, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ABRIL 2023', 0, 1, '221.24', '221.24', 'SERVICIO', '0.00'),
(3126, 1, 'PAGO DE SOPORTE CORRESPONDIENTE MES DE ABRIL ', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(3127, 1, 'FLETE DE ENVIO ', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(3128, 1, 'CABLE PREARMADO ORIGINAL DE 20 METROS ', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(3129, 1, 'EQUIPO DE OFICINA CPU6TH GENERACION, MONITOR DE 22 PULGADAS, TECLADO Y MOUSE', 0, 1, '243.36', '243.36', 'SERVICIO', '0.00'),
(3130, 1, 'KIT DE 4 CAMARAS HIKVISION ', 0, 1, '256.64', '256.64', 'SERVICIO', '0.00'),
(3131, 1, 'CONFIGURACION DE  DVR DE 4 CANALES ', 0, 1, '30.97', '30.97', 'SERVICIO', '0.00'),
(3132, 1, 'UPS ORBITEC 600VA', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(3133, 1, 'SERVICIO DE SOPORTE DE LABORATORIO CLINICO, CORRESPONDIENTE AL MES DE ABRIL  2023', 0, 1, '39.82', '39.82', 'SERVICIO', '0.00'),
(3134, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ABRIL DE 2023', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(3135, 1, 'SOPORTE A SISTEMA DE GESTION ADMINISTRATIVO CORRESPONDIENTE AL MES DE ABRIL 2023', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(3136, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ABRIL DE 2023', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(3137, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE MARZODE 2023', 0, 1, '35.40', '35.40', 'SERVICIO', '0.00'),
(3138, 1, 'KIT DE 3 CAMARAS TURBO BALA, 1 CAMARA CON AUDIO HIKVISION CON 1 A DE GARANTIA ', 0, 1, '330.00', '330.00', 'SERVICIO', '0.00'),
(3139, 1, 'IMPRESOR 890 II CLASE A', 0, 1, '300.00', '300.00', 'SERVICIO', '0.00'),
(3140, 1, 'FLETE DE ENVIO ', 0, 1, '4.42', '4.42', 'SERVICIO', '0.00'),
(3141, 1, 'ROLLOS DE PAPEL TERMICO POS 50 UNIDADES ', 0, 1, '50.88', '50.88', 'SERVICIO', '0.00'),
(3142, 1, 'EVALUACIONES POLIGRAFO PRE EMPLEO DE LOS ASPIRANTES: JORGE ISAAC RIVAS MADRIR', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3143, 1, 'RODRIGO ERNESTO DE LA O CEREN ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3144, 1, 'WALTER ANTONIO MANZANO', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3145, 1, 'WILLIAM OMAR GARAY FLORES', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3146, 1, 'FLETE POR ENVIO', 0, 1, '4.42', '4.42', 'SERVICIO', '0.00'),
(3147, 1, 'TECLADO Y MOUSE', 0, 1, '15.93', '15.93', 'SERVICIO', '0.00'),
(3148, 1, 'EQUIPO LENOVO THINKCENTRE M710Q CORE I3 DE 7MA, RAM 8GB, SSD 240GB, ', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(3149, 1, 'configuracion de dvr 4 canales', 0, 1, '30.97', '30.97', 'SERVICIO', '0.00'),
(3150, 1, 'kit de 4 camaras hikvision', 0, 1, '256.64', '256.64', 'SERVICIO', '0.00'),
(3151, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ABRIL DE 2023', 0, 1, '132.74', '132.74', 'SERVICIO', '0.00'),
(3152, 1, 'CANALETA 60X20MMX2M PLASTICO LG', 0, 1, '9.00', '9.00', 'SERVICIO', '0.00'),
(3153, 1, 'CANALETA DERIVACIONES ELECTRICAS 75X17MMX2M PLASTICO GRIS PISO', 0, 1, '14.00', '14.00', 'SERVICIO', '0.00'),
(3154, 1, 'IMPRESOR MATRICIAL LQ 590', 0, 1, '203.54', '203.54', 'SERVICIO', '0.00'),
(3155, 1, 'TARJETA WIFI MICRO USB ', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(3156, 1, 'BATERIA PARA UPS 7AH ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(3157, 1, 'TARJETA WIFI MICRO USB', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(3158, 1, 'BATERIA PARA UPS 7 AH', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(3159, 1, 'FLETE DE ENVIO ', 0, 1, '4.42', '4.42', 'SERVICIO', '0.00'),
(3160, 1, 'PAPEL TERMICO DE 80 MM 25 UNIDADES', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(3161, 1, 'REPETIDOR WIFI ', 0, 1, '30.97', '30.97', 'SERVICIO', '0.00'),
(3162, 1, 'IMPRESOR MATRICIAL FX-890 CLASE A', 0, 1, '199.12', '199.12', 'SERVICIO', '0.00'),
(3163, 1, '	EQUIPO LENOVO THINKCENTRE M710Q CORE I3 DE 7MA, RAM 8GB, SSD 240GB, ', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(3164, 1, 'CONFIGURACION DE DVR 4 CANALES', 0, 1, '30.97', '30.97', 'SERVICIO', '0.00'),
(3165, 1, 'KIT DE 4 CAMARAS HIKVISION 108P, CON INSTALACION ', 0, 1, '256.64', '256.64', 'SERVICIO', '0.00'),
(3166, 1, 'KIT DE 4 CAMARAS HIKVISION 1080P, CON INSTALACION', 0, 1, '256.64', '256.64', 'SERVICIO', '0.00'),
(3167, 1, 'TP-LINK ROUTER  ARCHER AX10 WIFI 6 AX1500 NEXT GEN 1.5GBPS', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(3168, 1, 'Xiaomi Mi P1 - 50\" Clase diagonal TV LCD con retroiluminación LED', 0, 1, '500.00', '500.00', 'SERVICIO', '0.00'),
(3169, 1, 'PANTALLA TCL LED 50 PULGADAS ', 0, 1, '2.00', '2.00', 'SERVICIO', '0.00'),
(3170, 1, 'Xtech - HDMI Splitter - 1 Input to 4 Outputs', 0, 1, '35.00', '35.00', 'SERVICIO', '0.00'),
(3171, 1, 'CAJA DE PAPEL TERMICO DE 80MM 50 UNIDADES', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(3172, 1, 'SOPORTE TECNICO A SISTEMA DE INVENTARIO Y FACTURACION CORRESPONDIENTE AL MES DE ABRIL DE 2023', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(3173, 1, '1er abono de instalacion de camaras ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(3174, 1, '1er abono de instalacion de camaras', 0, 1, '619.47', '619.47', 'SERVICIO', '0.00'),
(3175, 1, 'SERVICIO DE PRUEBA POLIGRAFO PRE-EMPLEO DE LOS ASPIRANTES EVALUADOS: EVER ALFRESO ZELAYA RIVAS', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3176, 1, 'JASON STANLEY SERRANO VILLALTA ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3177, 1, 'VICTOR ALFONSO MACHUCA LARIOS', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3178, 1, 'ELMER ALEXANDER AYALA NAVARRETE ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3179, 1, 'HD TOSHIBA  4TB VIDEO INT.3.5 HD WT140UZSVAR S300 5400RPM 723844000622', 0, 1, '180.00', '180.00', 'SERVICIO', '0.00'),
(3180, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(3181, 1, 'HD TOSHIBA  4TB VIDEO INT.3.5 HD WT140UZSVAR S300 5400RPM 723844000622', 0, 1, '180.00', '180.00', 'SERVICIO', '0.00'),
(3182, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION ', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(3183, 1, 'SERVICIO DE PRUEBA POLIGRAFO PRE EMPLEO DEL ASPIRANTE EVALUADO: EDWARD JEASON PORTILLO MELGAR', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3184, 1, 'IMPRESOR DE VIñETAS ZEBRA', 0, 1, '438.05', '438.05', 'SERVICIO', '0.00'),
(3185, 1, 'USP ORBITEC 750VA', 0, 1, '48.67', '48.67', 'SERVICIO', '0.00'),
(3186, 1, 'CAJA PARA EFECTIVO 3NSTAR', 0, 1, '70.80', '70.80', 'SERVICIO', '0.00'),
(3187, 1, 'LECTOR CODIGO DE BARRA 3NSTAR CS100', 0, 1, '75.22', '75.22', 'SERVICIO', '0.00'),
(3188, 1, 'IMPRESOR DE TICKET 3NSTAR ', 0, 1, '159.29', '159.29', 'SERVICIO', '0.00'),
(3189, 1, 'EQUIPO CPU LENOVO THINKCENTRE M710Q, RAM 8GB, SSD 240GB, MONITOR CLASE A, COMBO TECLADO Y MOUSE', 0, 1, '176.99', '176.99', 'SERVICIO', '0.00'),
(3190, 1, 'SISTEMA PARA INVENTARIO Y FACTURACION', 0, 1, '707.96', '707.96', 'SERVICIO', '0.00'),
(3191, 1, 'disco de 3tb', 0, 1, '130.00', '130.00', 'SERVICIO', '0.00'),
(3192, 1, 'SERVICIO DE PRUEBA POLIGRAFO DE LOS ASPIRANTES EVALUADOS: JOSE BALMORE ROMERO ANDRADE', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3193, 1, 'LUIS ALFREDO RIVAS CRUZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3194, 1, 'JUAN CONTRERAS PEREZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3195, 1, 'OSCAR NAPOLEON PAIZ PAIZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3196, 1, 'EDENILSON ALEXANDER VILLALOBOS', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3197, 1, 'SERVICIO DE PRUEBA POLIGRAFO DE LOS ASPIRANTES: JOSE BALMORE ROMERO ANDRADE', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3198, 1, 'LUIS ALFREDO RIVAS CRUZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3199, 1, 'JUAN CONTRERAS PEREZ', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3200, 1, 'CINTA EPSON LX-350 ', 0, 1, '13.27', '13.27', 'SERVICIO', '0.00'),
(3201, 1, 'Ubiquiti UniFi 6 Lite - Punto de acceso inalámbrico', 0, 1, '160.00', '160.00', 'SERVICIO', '0.00'),
(3202, 1, 'CUENTA DE CORREO CON SERVICIO DE GMAIL 5 USUARIOS', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(3203, 1, 'CUENTA DE CORREO CON SERVICIO DE GMAIL 3 USUARIOS', 0, 1, '28.00', '28.00', 'SERVICIO', '0.00'),
(3204, 1, 'FLETE DE ENVIO ', 0, 1, '4.42', '4.42', 'SERVICIO', '0.00'),
(3205, 1, 'CAJA DE PAPEL TERMICO DE 80MM 50 UNIDADES', 0, 1, '79.65', '79.65', 'SERVICIO', '0.00'),
(3206, 1, 'kit de 4 camaras hikvision ', 0, 1, '330.00', '330.00', 'SERVICIO', '0.00'),
(3207, 1, 'DISCO 4TB  ', 0, 1, '185.00', '185.00', 'SERVICIO', '0.00'),
(3208, 1, 'MANTENIMIENTO PREVENTIVO DE CAMARAS ', 0, 1, '200.00', '200.00', 'SERVICIO', '0.00'),
(3209, 1, 'EQUIPO CPU LENOVO, MONITOR, TECLADO Y MOUSE ', 0, 1, '207.00', '207.00', 'SERVICIO', '0.00'),
(3210, 1, 'DVR 8 CANALES ', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(3211, 1, 'CAMARAS FULL COLOR DAHUA ', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(3212, 1, 'SISTEMA DE INVENTARIO ', 0, 1, '700.00', '700.00', 'SERVICIO', '0.00'),
(3213, 1, 'PUNTO DE VENTA, MONITOR, IMPRESOR DE TICKET, EQUIPO DE OFICINA', 0, 1, '800.00', '800.00', 'SERVICIO', '0.00'),
(3214, 1, 'CAMARA IP JT CLEAR ', 0, 1, '65.00', '65.00', 'SERVICIO', '0.00'),
(3215, 1, 'MONITOR DE 19 PULGADAS CLASE A', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(3216, 1, 'FLETE DE ENVIO ', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(3217, 1, 'MODEM DE INTERNET ', 0, 1, '70.00', '70.00', 'SERVICIO', '0.00'),
(3218, 1, 'CAJA DE PAPEL TERMICO 50 UNIDADES 80MM', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(3219, 1, 'FLETE DE ENVIO ', 0, 1, '5.00', '5.00', 'SERVICIO', '0.00'),
(3220, 1, 'MEDIA CAJA DE PAPEL 80MM', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(3221, 1, 'CABLE HDMI', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(3222, 1, 'SOPORTE DE PARED ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(3223, 1, 'MONITOR DELL 19 PULGADAS ', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(3224, 1, 'CAMARA BULLETH CON AUDIO ', 0, 1, '45.00', '45.00', 'SERVICIO', '0.00'),
(3225, 1, 'MANTENIMIENTO A IMPRESOR', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(3226, 1, 'CAJA DE EFECTIVO ', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(3227, 1, 'BOBINA DE CABLE UTP ', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(3228, 1, '15 ROLLOS DE PAPEL 80 MM ', 0, 1, '2.50', '2.50', 'SERVICIO', '0.00'),
(3229, 1, 'TECLADO Y MOUSE ', 0, 1, '15.00', '15.00', 'SERVICIO', '0.00'),
(3230, 1, 'CINTA PARA IMPRESOR MATRICIAL LX350 ', 0, 1, '18.00', '18.00', 'SERVICIO', '0.00'),
(3231, 1, 'CAJA DE EFECTIVO', 0, 1, '85.00', '85.00', 'SERVICIO', '0.00'),
(3232, 1, 'CABLE HDMI', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(3233, 1, 'CAMARAS NEXXT', 0, 1, '55.00', '55.00', 'SERVICIO', '0.00'),
(3234, 1, 'PAPEL TERMICO POS CAJA', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(3235, 1, 'MONITOR HP 19 PULGADAS', 0, 1, '75.00', '75.00', 'SERVICIO', '0.00'),
(3236, 1, 'CPU LENOVO ', 0, 1, '205.00', '205.00', 'SERVICIO', '0.00'),
(3237, 1, 'MEDIA CAJA DE PAPEL POS', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(3238, 1, 'CAJAS DE SUPERFICIE BLANCA', 0, 1, '1.75', '1.75', 'SERVICIO', '0.00'),
(3239, 1, 'sistema de inventario para restaurante', 0, 1, '700.00', '700.00', 'SERVICIO', '0.00'),
(3240, 1, 'Mantenimiento de pc ', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(3241, 1, 'disco 2tb', 0, 1, '90.00', '90.00', 'SERVICIO', '0.00'),
(3242, 1, 'CAJA DE PAPEL POS 100 UNIDADES', 0, 1, '88.50', '88.50', 'SERVICIO', '0.00'),
(3243, 1, 'CAJA DE PAPEL 57MM PARA POS 95 UNIDADES', 0, 1, '95.00', '95.00', 'SERVICIO', '0.00'),
(3244, 1, 'PAGO DE SISTEMA PARA INVENTARIO Y FACTURACION, IMPRESOR MATRICIAL EPSON FX-890 CLASE A', 0, 1, '725.66', '725.66', 'SERVICIO', '0.00'),
(3245, 1, 'SERVICIO DE PRUEBA POLIGRAFO PRE EMPLEO DEL ASPIRANTE: DAVID SALOMON BELTRAN RIVERA', 0, 1, '20.00', '20.00', 'SERVICIO', '0.00'),
(3246, 1, 'FLETE DE ENVIO ', 0, 1, '4.42', '4.42', 'SERVICIO', '0.00'),
(3247, 1, 'CAJA DE PAPEL 80MM 50 UNIDADES', 0, 1, '90.58', '90.58', 'SERVICIO', '0.00'),
(3248, 1, 'MANO DE OBRA Y MATERIALES DE INSTALACION ', 0, 1, '973.45', '973.45', 'SERVICIO', '0.00'),
(3249, 1, 'DISCO DURO 2TB', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(3250, 1, 'CANALETA ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(3251, 1, 'MONITOR DE 19 PULGADAS', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(3252, 1, 'REGLETA DE PODER', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(3253, 1, 'BOBINA DE CABLE UTP', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(3254, 1, 'ADAPTADOR DC ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(3255, 1, 'VIDEO BALUN PASIVO', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(3256, 1, 'CAMARA DOMO 2MP', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(3257, 1, 'CAMARA TURBO BALA 2MP', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(3258, 1, 'FUENTES DE PODER 5 AMP', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(3259, 1, 'DVR 16 CANALES ', 0, 1, '0.00', '0.00', 'SERVICIO', '0.00'),
(3260, 1, 'DVR DE 16 CANALES, 8 CAMARAS DOMO INTERIOR, 4 CAMARAS TIPO BALA EXTERIOR, DISCO DE 2TB, 3 FUENTES DE PODER, 12 VIDEO BALUN, 12 CONECTORES DC, 1 MONITOR 19 PULGADAS, 1 BOBINA DE CABLE, 10 CANALETA , 2 REGLETAS DE PODER MANO DE OBRA Y MATERIALES DE INSTALACION ', 0, 1, '973.45', '973.45', 'SERVICIO', '0.00'),
(3263, 1, 'MANTENIMIENTO SISTEMA', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(3264, 1, 'soporte tecnico', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(3265, 1, 'soporte tecnico', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(3266, 1, 'SOPORTE TECNICO', 0, 1, '99.00', '99.00', 'SERVICIO', '0.00'),
(3267, 1, 'soporte tecnico', 0, 1, '100.00', '100.00', 'SERVICIO', '0.00'),
(3268, 1, 'servicio prueba 23 junio 2023', 0, 1, '150.00', '150.00', 'SERVICIO', '0.00'),
(3269, 1, 'SOPORTE REVISION DE CAMARAS', 0, 1, '40.00', '40.00', 'SERVICIO', '0.00'),
(3270, 1, 'REVISION Y MANTENIMIENTO CAMARAS Y CABLEADO', 0, 1, '44.25', '44.25', 'SERVICIO', '0.00'),
(3271, 1, 'REVISION DE SISTEMA VIDEOVIGILANCIA', 0, 1, '30.00', '30.00', 'SERVICIO', '0.00'),
(3272, 1, 'MANTENIMIENTO DE IMPRESORES MATRICIAL Y PUNTO DE VENTA', 0, 1, '50.00', '50.00', 'SERVICIO', '0.00'),
(3273, 1, 'ENVIO DEL HELADO', 0, 1, '25.00', '25.00', 'SERVICIO', '0.00'),
(3274, 1, 'ENVIO', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(3275, 1, 'ENVIO #3', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(3276, 1, 'ENVIO #2', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(3277, 1, 'ENVIO #1', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(3278, 1, 'ENVIO', 0, 1, '10.00', '10.00', 'SERVICIO', '0.00'),
(3279, 1, 'ENVIO', 0, 1, '2.50', '2.50', 'SERVICIO', '0.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_detalle`
--

CREATE TABLE `servicio_detalle` (
  `id_detalle` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicio_detalle`
--

INSERT INTO `servicio_detalle` (`id_detalle`, `id_servicio`, `descripcion`) VALUES
(8, 29, 'Respaldo de base de datos diario'),
(9, 29, 'Seguridad de Servidores y mantenimiento '),
(10, 29, 'La renovación incluye un año de servicio '),
(11, 29, 'Configuración de DNS y dominio personalizado '),
(21, 32, 'Modulo de Personal '),
(22, 32, 'Configuración de franjas de horarios '),
(23, 32, 'Gestión de permisos '),
(24, 32, 'Gestión de marcar de personal '),
(25, 32, 'Gestión de Revisión de marcas de personal '),
(26, 32, 'Administración de departamentos'),
(27, 32, 'Generación de Reporte de descuento '),
(28, 32, 'Generación de Reporte de horas extras '),
(29, 32, 'Generación de reporte de permisos '),
(30, 32, 'Capacitación a personal involucrado por 3 días '),
(31, 32, 'Tiempo para implementar 15 días '),
(32, 36, 'Equipo usado en buenas condiciones '),
(537, 1816, 'CPU MARCA LENOVO THINKCENTRE M73 I CORE 3 CON DISCO SOLIDO DE 240GB ROM Y RAM DE 4 GB, GARANTIA DE 12 MESES'),
(538, 1816, 'MONITOR , MARCA ASUS, GARANTIA DE 12 MESES'),
(539, 1816, 'IMPRESORA MATRICIAL, MODELO FX-890, GARANTIA DE 12 MESES'),
(540, 1816, 'CAJA DE EFECTIVO, MARCA 3NSTAR , GARANTIA DE 12 MESES'),
(541, 1816, 'LECTOR DE BARRAS, MARCA 3NSTAR, GARANTIA DE 12 MESES'),
(542, 1816, 'IMPRESOR TERMICO, MARCA BEMATECH, GARANTIA DE 12 MESES'),
(543, 1816, 'MOUSE Y TECLADO MARCA XTK160S GARANTIA DE 12 MESES');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_stock` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `stock` decimal(11,4) NOT NULL,
  `stock_local` decimal(11,4) NOT NULL,
  `precio_unitario` float NOT NULL,
  `costo_unitario` float NOT NULL,
  `create_date` date NOT NULL,
  `update_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`id_server`, `unique_id`, `id_sucursal`, `id_stock`, `id_producto`, `stock`, `stock_local`, `precio_unitario`, `costo_unitario`, `create_date`, `update_date`) VALUES
(0, 'S64a84e7a704813.23754328', 1, 1, 1, '76.0000', '80.0000', 2, 1, '2023-07-07', '2023-07-25'),
(0, 'S64a9e64ba45b60.80388073', 2, 2, 1, '70.0000', '100.0000', 2, 1, '2023-07-08', '2023-07-10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_ubicacion`
--

CREATE TABLE `stock_ubicacion` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_su` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(11,4) NOT NULL,
  `id_ubicacion` int(11) NOT NULL,
  `id_estante` int(11) NOT NULL,
  `id_posicion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock_ubicacion`
--

INSERT INTO `stock_ubicacion` (`id_server`, `unique_id`, `id_sucursal`, `id_su`, `id_producto`, `cantidad`, `id_ubicacion`, `id_estante`, `id_posicion`) VALUES
(0, 'S64a84e7a6fdca5.51399123', 1, 1, 1, '66.0000', 1, 0, 0),
(0, 'S64a9e64ba28f36.23010504', 2, 2, 1, '70.0000', 7, 0, 0),
(0, 'S64bfdcb51ffdc6.80678952', 1, 3, 1, '10.0000', 2, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE `sucursal` (
  `id_sucursal` int(11) NOT NULL,
  `descripcion` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `direccion` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `telefono` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `telefono1` varchar(10) NOT NULL,
  `telefono2` varchar(10) NOT NULL,
  `casa_matriz` tinyint(1) NOT NULL,
  `id_usuario_recibe` int(11) NOT NULL,
  `nrc` varchar(15) NOT NULL,
  `nit` varchar(20) NOT NULL,
  `iva` double NOT NULL,
  `monto_retencion1` float NOT NULL,
  `monto_retencion10` float NOT NULL,
  `monto_percepcion` float NOT NULL,
  `serie_cof` varchar(50) NOT NULL,
  `serie_ccf` varchar(50) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `nombre_comercial` varchar(250) NOT NULL,
  `cod_act_eco` varchar(100) NOT NULL,
  `id_departamento` smallint(2) NOT NULL,
  `id_municipio` smallint(3) NOT NULL,
  `email` varchar(250) NOT NULL,
  `privKey` text DEFAULT NULL,
  `pubKey` text DEFAULT NULL,
  `urlMH` varchar(250) NOT NULL,
  `url_dte` varchar(250) NOT NULL,
  `clv` varchar(256) NOT NULL,
  `user` varchar(20) NOT NULL,
  `pwd` text NOT NULL,
  `iv` varchar(150) NOT NULL COMMENT 'iv generated',
  `logo` varchar(250) NOT NULL,
  `giro` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id_sucursal`, `descripcion`, `direccion`, `telefono`, `telefono1`, `telefono2`, `casa_matriz`, `id_usuario_recibe`, `nrc`, `nit`, `iva`, `monto_retencion1`, `monto_retencion10`, `monto_percepcion`, `serie_cof`, `serie_ccf`, `nombre`, `nombre_comercial`, `cod_act_eco`, `id_departamento`, `id_municipio`, `email`, `privKey`, `pubKey`, `urlMH`, `url_dte`, `clv`, `user`, `pwd`, `iv`, `logo`, `giro`) VALUES
(1, 'SUPERMERCADO EL SOL', 'CTON. HUISQUIL, CONCHAGUA, LA UNION', '', '', '', 0, 0, '317243-8', '04722895-8', 13, 100, 100, 0, '', '', 'SUPER MERCADO EL SOL', 'CANALES RAMIRES, MAURICIO JAVIER', '47111', 12, 17, 'operaciones@tumundolaboral.com.sv', '', '', '', '', '', '', '', '', 'img/64b5c8b349c9b_elsol1.jpeg', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tanque`
--

CREATE TABLE `tanque` (
  `id` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `numero` smallint(6) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `tipo_combustible` int(11) NOT NULL COMMENT '1-REGULAR\r\n2-SUPER\r\n3-DIESEL',
  `activa` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tanque_diario`
--

CREATE TABLE `tanque_diario` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_stock` int(11) NOT NULL,
  `id_tanque` int(11) NOT NULL COMMENT '1-REGULAR\r\n2-SUPER\r\n3-DIESEL',
  `galones_dia` decimal(12,2) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodoc`
--

CREATE TABLE `tipodoc` (
  `idtipodoc` int(3) NOT NULL,
  `nombredoc` varchar(30) DEFAULT NULL,
  `cliente` int(1) DEFAULT NULL,
  `provee` int(1) DEFAULT NULL,
  `interno` int(1) DEFAULT NULL,
  `alias` char(4) DEFAULT NULL,
  `correlativo` int(1) DEFAULT NULL,
  `numerop` int(4) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL,
  `codigoMH` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tipodoc`
--

INSERT INTO `tipodoc` (`idtipodoc`, `nombredoc`, `cliente`, `provee`, `interno`, `alias`, `correlativo`, `numerop`, `activo`, `codigoMH`) VALUES
(0, 'VENTA', 0, 0, 1, 'VEN', NULL, NULL, 0, NULL),
(1, 'TICKET', 1, 0, 0, 'TIK', 0, 0, 1, NULL),
(2, 'FACTURA', 1, 1, 0, 'COF', 0, 0, 1, '01'),
(3, 'COMPROBANTE CREDITO FISCAL', 1, 1, 0, 'CCF', 0, 0, 1, '03'),
(4, 'DEVOLUCION', 0, 0, 0, 'DEV', 0, 0, 0, NULL),
(5, 'VALE', 0, 0, 0, 'VAL', 0, 0, 0, NULL),
(6, 'EXPORTACION', 0, 0, 0, 'EXP', 0, 0, 0, NULL),
(7, 'NOTA DE REMISION', 1, 0, 0, 'NR', 0, 0, 1, '04'),
(8, 'NOTA DE CREDITO', 0, 0, 0, 'NC', 0, 0, 0, '05'),
(9, 'NOTA DE DEBITO', 0, 0, 0, 'NDD', 0, 0, 0, '06'),
(10, 'NOTA DE RETENCION', 0, 0, 0, 'NTR', 0, 0, 0, NULL),
(11, 'ENTRADAS', 0, 0, 1, 'ENT', 0, 141, 0, NULL),
(12, 'SALIDAS', 0, 0, 1, 'SAL', 0, 1463, 0, NULL),
(13, 'CAPTURA FISICA', 0, 0, 1, 'FIS', 0, 0, 0, NULL),
(14, 'CAMBIOS', 0, 0, 0, 'CM', 0, 0, 0, NULL),
(15, 'CHEQUE', 0, 0, 0, 'CHQ', 0, 0, 0, NULL),
(16, 'LISTA DE PEDIDO', 0, 0, 0, 'LPE', 0, 0, 0, NULL),
(17, 'COMPRA', 0, 0, 0, 'COM', 0, 0, 0, NULL),
(18, 'NOTA DE ABONO', 0, 0, 0, 'NDA', 0, 0, 0, NULL),
(19, 'REPOSICION', 0, 0, 0, 'REP', 0, 0, 0, NULL),
(20, 'SALIDA POR TRASLADO', 0, 0, 1, 'TRA', 0, 0, 0, NULL),
(21, 'RESERVA PRODUCTO', 0, 0, 1, 'RES', 0, 0, 0, NULL),
(22, 'ENTRADA POR TRASLADO', 0, 0, 1, 'EPT', 0, 0, 0, NULL),
(23, 'ANULACION DE TRASLADO', 0, 0, 1, 'ADT', 0, 0, 0, NULL),
(24, 'GARANTIA PROVEEDOR', 0, 0, 0, 'GAP', 0, 0, 0, NULL),
(25, 'INGRESO INVENTARIO', 0, 1, NULL, 'INI', 0, 0, 0, NULL),
(27, 'IMPORTACION', NULL, 1, 1, 'IMP', 0, 0, 0, NULL),
(28, 'FACTURA SUJETO EXCLUIDO', 0, 1, 0, 'FSE', NULL, NULL, 1, '14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_caja`
--

CREATE TABLE `tipo_caja` (
  `id` smallint(3) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_caja`
--

INSERT INTO `tipo_caja` (`id`, `descripcion`) VALUES
(1, 'TIENDA'),
(2, 'PISTA'),
(3, 'TRANSPORTE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_empleado`
--

CREATE TABLE `tipo_empleado` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_tipo_empleado` int(11) NOT NULL,
  `descripcion` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tipo_empleado`
--

INSERT INTO `tipo_empleado` (`id_server`, `unique_id`, `id_tipo_empleado`, `descripcion`) VALUES
(1, 'O5f05eba6d7a449.64890848', 1, 'Administrador'),
(2, 'O5f05eba6d93257.30798688', 2, 'Vendedor'),
(3, 'O5f05eba6db4a16.71013016', 3, 'Cajero'),
(4, 'O5f05eba6dd5c20.32959094', 4, 'Bodeguero'),
(5, 'O5f05eba6df7252.09792126', 5, 'Oficios Varios ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_pago`
--

CREATE TABLE `tipo_pago` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_tipopago` int(11) NOT NULL,
  `alias_tipopago` char(3) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_pago`
--

INSERT INTO `tipo_pago` (`id_server`, `unique_id`, `id_tipopago`, `alias_tipopago`, `descripcion`, `activo`) VALUES
(1, 'O5f05eba6e23ce1.97808373', -1, 'N/A', 'NO SELECCIONADO', 0),
(1, 'O5f05eba6e23ce1.97808373', 0, 'CON', 'CONTADO', 1),
(3, 'O5f05eba6e5c752.59341489', 1, 'CRE', 'CREDITO', 1),
(2, 'O5f05eba6e3c170.13829318', 2, 'TAR', 'TARJETA DEBITO/CREDITO', 1),
(0, 'O5f05eba6e3c170.43348762', 4, 'COI', 'CONSUMO INTERNO', 0),
(4, 'O5f05eba6e7d292.43341976', 5, 'VAL', 'CUPON', 0),
(6, 'O5f05eba6ec12d7.46494495', 6, 'CHE', 'CHEQUE', 1),
(5, 'O5f05eba6e9f643.40351170', 7, 'TRA', 'TRANSFERENCIA', 1),
(8, 'O5f05eba6e9f643.40354345', 9, 'OTR', 'OTRO', 0),
(9, 'O5f05eba6e9f643.40354345', 10, 'BTC', 'BITCOIN', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_proveedor`
--

CREATE TABLE `tipo_proveedor` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_proveedor`
--

INSERT INTO `tipo_proveedor` (`id_server`, `unique_id`, `id_tipo`, `nombre`, `descripcion`) VALUES
(1, 'O5f05eba6eec1b2.07494052', 1, 'Costo', ''),
(2, 'O5f05eba6f04176.60112373', 2, 'Gasto', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_vehiculo`
--

CREATE TABLE `tipo_vehiculo` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulo_bienes_MH`
--

CREATE TABLE `titulo_bienes_MH` (
  `id` int(11) NOT NULL,
  `codigo` char(2) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `titulo_bienes_MH`
--

INSERT INTO `titulo_bienes_MH` (`id`, `codigo`, `descripcion`) VALUES
(1, '01', 'Depósito'),
(2, '02', 'Propiedad'),
(3, '03', 'Consignación'),
(4, '04', 'Traslado'),
(5, '05', 'Otros');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token_auth_dia`
--

CREATE TABLE `token_auth_dia` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `token` text NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `token_auth_dia`
--

INSERT INTO `token_auth_dia` (`id`, `fecha`, `hora`, `token`, `id_sucursal`) VALUES
(1, '2023-04-24', '22:47:32', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjgyMzk4MDQ1LCJleHAiOjE2ODI0ODQ0NDV9.DQ2c2-vgkkVT6P3zsvo5hiEyudz4YM4ecK7VQGkWCtJav-lGre5CgqO7P6NwsD1-zrSjXPUcpSB6XSVH1jaKPA', 1),
(2, '2023-04-25', '09:20:20', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjgyNDM2MDIwLCJleHAiOjE2ODI1MjI0MjB9.LiDOmOuHbz_2WIpCFqDo4pDdASfbr5ZYLrT3KV6KCHVkpRYE1z_2ibrdZ3O35RQIf1BL6nM_oSXlNV-ALdTivg', 1),
(3, '2023-04-26', '12:42:24', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjgyNTM0NTQ0LCJleHAiOjE2ODI2MjA5NDR9.GF1OIiHaN43zpmPG9r6xEzWtPcXqL1BCdGWnzT6Ua3FPVoTMPgWpKOnH43NMtAbPOmPx4EAl4TXE3eixOGYqPA', 1),
(4, '2023-04-29', '16:18:35', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjgyODA2NzE1LCJleHAiOjE2ODI4OTMxMTV9.zwGKwRb-6GXJP0D5V79JnTEkc3nN7XaraqEF6MHnJXkSZWhsZwDSBJ46aJaAM38497S9ntydFzyWsNdoVeStIA', 1),
(5, '2023-05-03', '19:34:10', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjgzMTY0MDUwLCJleHAiOjE2ODMyNTA0NTB9.6UKaX_wahWPGFsicZYYGfa96VAZHQ1VeSsuiplevz9rSLPpasX0TjFlZgmLfWU3nF77KV6u4I0RXLEdBtVOPeA', 1),
(6, '2023-05-04', '09:16:46', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjgzMjEzNDA2LCJleHAiOjE2ODMyOTk4MDZ9.f_AofoMSIl7J3IlgZKTj_kSO4XfTFi0wLAmVUopcRluuM51L4eRfUXKhlHJcxFNaRsMxCd3awyRUjFbtpmgE4g', 1),
(7, '2023-05-06', '09:46:40', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjgzMzg4MDAwLCJleHAiOjE2ODM0NzQ0MDB9.5OS0SjktQ7OK0RXT79CkkzEVhe7AEi3glnSbGH5u6r6t3oEcoDsk-aNrDE7u3yNTWGzftAoYPLm9R2dDf3wpcw', 1),
(8, '2023-05-09', '07:32:00', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjgzNjM5MTIwLCJleHAiOjE2ODM3MjU1MjB9.AL-xmFlI_kP7_jPoSJFbyND_aLOdtxp-OWYS6CRwRRu6Y6njkbG-tjGqfWllgoT1mwp8ooN8kAHT08Agps0mww', 1),
(9, '2023-05-12', '16:43:42', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjgzOTMxNDIyLCJleHAiOjE2ODQwMTc4MjJ9.hrhqYo6q5z2aQ1Kz3U-KiEinJ4LG-avruqJXHYBTGwCRBREeajkd5aG8V5hbgO1WvoS3zGO-Im1UIevXAWn57w', 1),
(10, '2023-05-18', '20:15:29', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjg0NDYyNTI5LCJleHAiOjE2ODQ1NDg5Mjl9.BCfVyPPDwatS7bi4LEXCr43cq6h2QnIRnM858Y2Ffu2ARR4oyPdQYPhScLxm0mJj2XbfnuHE-QLxPC0oO0Sfjw', 1),
(11, '2023-05-20', '15:56:10', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjg0NjE5NzcwLCJleHAiOjE2ODQ3MDYxNzB9.FM6O-F8LBSzST0Lsg2chpGDHja5w5F9nvjqPcuVHyzKoUshTdn5iS3h4xei6d8cePjOgKpmaRRam8TiGNm5cSw', 1),
(12, '2023-05-23', '10:29:01', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjg0ODU5MzQwLCJleHAiOjE2ODQ5NDU3NDB9.yXAmB3unRnmi2onfujqbzrtJQ_HFnjkD_7TTW4rOxUqtIgyopgRTOfADNd7tH8QBt5jZxZZjD94-mEwQU9_0mA', 1),
(13, '2023-05-24', '13:54:21', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjg0OTU4MDYxLCJleHAiOjE2ODUwNDQ0NjF9.-meDJjzQTwtvJZXnWdS2zN0e6nnau_GAplfiwHNVKvPE_VrfY8tMe7U-lmsWGMm6Y-nACPxzD48Kl8Fik0pYQA', 1),
(14, '2023-05-29', '22:31:11', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjg1NDIxMDcxLCJleHAiOjE2ODU1MDc0NzF9.oa3R8ez0xaDPFZmmzDQjLSQ6EAe_tQcihrI5wSBAXKZbe1bSK8owgpxBfYbf6rMqykreX6XVO8OMXZt1TX3HUw', 1),
(15, '2023-05-30', '09:35:35', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjg1NDYwOTM1LCJleHAiOjE2ODU1NDczMzV9.uOTgV8RoC7-dMZ3QSrVv8t3e0569vV7BAk5aIc4BuEmFIBPkAY3_mHmOooC8Eo8ra_5rMVmdxtDaWrZPz0GLcg', 1),
(16, '2023-06-14', '15:41:26', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjg2Nzc4ODg2LCJleHAiOjE2ODY4NjUyODZ9.fTWaO_QBdYWTnDwgl3Aze4UhBwDXF5j93gqeYZrFywU_mSQOKj76C3euq7ofztCMSbN65H5an9fgXpwyW81GLg', 1),
(17, '2023-06-15', '15:57:50', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjg2ODY2MjcwLCJleHAiOjE2ODY5NTI2NzB9.AyVe3UoE36KpFlXBZxyICUNL6aKaWK5U4uwLVGTOTrOXJzQ14MZODe0VMl9prM83ZKknMPFhF2mrpccg7Hjftg', 1),
(18, '2023-06-16', '09:30:07', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjg2OTI5NDA3LCJleHAiOjE2ODcwMTU4MDd9.C0zx37P1LU5yNeRWE9NmbeYPys6JHxHBDqWv1PdeYVS0XDN7qWTHkLecPhkgMw7fR7ZoJVzUm5eOXVdUvUd5Kg', 1),
(19, '2023-06-21', '11:18:29', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjg3MzY3OTA5LCJleHAiOjE2ODc0NTQzMDl9.GgFCJlOIxwZlNaBxaw6goXbqsKUf_76p_Hkl23-fp4-DYk5nsOojcKxFo4cv7QDfLJbsDvOgN0T3IASYAAyiSg', 1),
(20, '2023-06-22', '23:12:49', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjg3NDk3MTY5LCJleHAiOjE2ODc1ODM1Njl9.iIScz3oJAZ3-W6fjk_mVjkYSdwlXizw1PvKX20gOsN1NSzwkpz09SkLdB7NKXlkJBR8yKpENaoq0wcZRsxfcFQ', 1),
(21, '2023-06-23', '14:34:52', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjQwMTIwMTAxOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjg3NTUyNDkxLCJleHAiOjE2ODc2Mzg4OTF9.BaEb6BQzxdao5rfDYAPe3URlVddkgNvaUIqfQ_NL-cHsJ1NR4fd2f_VPYCA2BQYwHBjX-2PbpkIk3BIjSMuuUA', 1),
(22, '2023-06-27', '09:35:40', 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxMjE3MjYwNDIzMTAyOCIsImF1dGhvcml0aWVzIjpbIlVTRVIiLCJVU0VSX0FQSSIsIlVzdWFyaW8iXSwiaWF0IjoxNjg3ODgwMTM5LCJleHAiOjE2ODc5NjY1Mzl9.zMRFke3_450Gl8_qinrOGn0dPsgNCarUUIdGmkhSiHw5xIwXiuD5MFvj5CXEKgKLNZuYFaEamAy4--qcksRlZQ', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `to_corte`
--

CREATE TABLE `to_corte` (
  `id` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `to_corte_producto`
--

CREATE TABLE `to_corte_producto` (
  `id` int(11) NOT NULL,
  `id_corte` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `to_corte_producto_detalle`
--

CREATE TABLE `to_corte_producto_detalle` (
  `id` int(11) NOT NULL,
  `id_ref` int(11) NOT NULL,
  `cantidad` decimal(10,4) DEFAULT NULL,
  `stock_anterior` decimal(10,4) DEFAULT NULL,
  `stock_actual` decimal(10,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslado`
--

CREATE TABLE `traslado` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal_origen` int(11) NOT NULL,
  `id_sucursal_destino` int(11) NOT NULL,
  `id_traslado` int(11) NOT NULL,
  `n_vale` varchar(50) NOT NULL,
  `id_ubicacion_destino` int(11) NOT NULL,
  `concepto` varchar(50) DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_empleado_envia` int(11) NOT NULL,
  `id_empleado_recibe` int(11) NOT NULL,
  `empleado_envia` varchar(250) NOT NULL,
  `empleado_recibe` varchar(250) NOT NULL,
  `total` float NOT NULL,
  `anulada` tinyint(4) NOT NULL,
  `finalizada` tinyint(4) NOT NULL,
  `id_origen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslado_detalle`
--

CREATE TABLE `traslado_detalle` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal_origen` int(11) NOT NULL,
  `id_sucursal_destino` int(11) NOT NULL,
  `id_detalle_traslado` int(11) NOT NULL,
  `id_traslado` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_server_prod` int(11) NOT NULL,
  `cantidad` decimal(11,4) NOT NULL,
  `unidad` int(11) NOT NULL,
  `costo` float NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `id_server_presen` int(11) NOT NULL,
  `presentacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslado_detalle_g`
--

CREATE TABLE `traslado_detalle_g` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal_origen` int(11) NOT NULL,
  `id_sucursal_destino` int(11) NOT NULL,
  `id_detalle_traslado` int(11) NOT NULL,
  `id_traslado` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_server_prod` int(11) NOT NULL,
  `cantidad` decimal(11,4) NOT NULL,
  `unidad` int(11) NOT NULL,
  `costo` float NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `id_server_presen` int(11) NOT NULL,
  `presentacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslado_detalle_recibido`
--

CREATE TABLE `traslado_detalle_recibido` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal_origen` int(11) NOT NULL,
  `id_sucursal_destino` int(11) NOT NULL,
  `id_detalle_traslado_recibido` int(11) NOT NULL,
  `id_traslado` int(11) NOT NULL,
  `id_traslado_server` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_server_prod` int(11) NOT NULL,
  `cantidad` decimal(11,4) NOT NULL,
  `recibido` decimal(11,4) NOT NULL,
  `unidad` int(11) NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `id_server_presen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslado_g`
--

CREATE TABLE `traslado_g` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal_origen` int(11) NOT NULL,
  `id_sucursal_destino` int(11) NOT NULL,
  `id_traslado` int(11) NOT NULL,
  `n_vale` varchar(50) NOT NULL,
  `id_ubicacion_destino` int(11) NOT NULL,
  `concepto` varchar(50) DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_empleado_envia` int(11) NOT NULL,
  `id_empleado_recibe` int(11) NOT NULL,
  `empleado_envia` varchar(250) NOT NULL,
  `empleado_recibe` varchar(250) NOT NULL,
  `total` float NOT NULL,
  `anulada` tinyint(4) NOT NULL,
  `finalizada` tinyint(4) NOT NULL,
  `id_origen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion`
--

CREATE TABLE `ubicacion` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_ubicacion` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `bodega` tinyint(1) NOT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `ubicacion`
--

INSERT INTO `ubicacion` (`id_server`, `unique_id`, `id_sucursal`, `id_ubicacion`, `descripcion`, `bodega`, `borrado`) VALUES
(0, 'S62a21014622916.65263989', 1, 1, 'TIENDA', 0, 0),
(0, 'S6313a1bb07b170.99932428', 1, 2, 'BODEGA 1', 1, 0),
(0, 'S62a21014622916.65263990', 2, 7, 'TIENDA', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_medidaMH`
--

CREATE TABLE `unidad_medidaMH` (
  `id` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `unidad_medidaMH`
--

INSERT INTO `unidad_medidaMH` (`id`, `codigo`, `nombre`) VALUES
(1, 1, 'Metro'),
(2, 2, 'Yarda'),
(3, 3, 'Vara'),
(4, 4, 'Pie'),
(5, 5, 'Pulgada'),
(6, 6, 'Milímetro'),
(7, 8, 'Milla cuadrada'),
(8, 9, 'Kilómetro cuadrado'),
(9, 10, 'Hectárea'),
(10, 11, 'Manzana'),
(11, 12, 'Acre'),
(12, 13, 'Metro cuadrado'),
(13, 14, 'Yarda cuadrada'),
(14, 15, 'Vara cuadrada'),
(15, 16, 'Pie cuadrado'),
(16, 17, 'Pulgada cuadrada'),
(17, 18, 'Metro cúbico'),
(18, 19, 'Yarda cúbica'),
(19, 20, 'Barril'),
(20, 21, 'Pie cúbico'),
(21, 22, 'Galón'),
(22, 23, 'Litro'),
(23, 24, 'Botella'),
(24, 25, 'Pulgada cúbica'),
(25, 26, 'Mililitro'),
(26, 27, 'Onza fluida'),
(27, 29, 'Tonelada métrica'),
(28, 30, 'Tonelada'),
(29, 31, 'Quintal métrico'),
(30, 32, 'Quintal'),
(31, 33, 'Arroba'),
(32, 34, 'Kilogramo'),
(33, 35, 'Libra troy'),
(34, 36, 'Libra'),
(35, 37, 'Onza troy'),
(36, 38, 'Onza'),
(37, 39, 'Gramo'),
(38, 40, 'Miligramo'),
(39, 42, 'Megawatt'),
(40, 43, 'Kilowatt'),
(41, 44, 'Watt'),
(42, 45, 'Megavoltio-amperio'),
(43, 46, 'Kilovoltio-amperio'),
(44, 47, 'Voltio-amperio'),
(45, 49, 'Gigawatt-hora'),
(46, 50, 'Megawatt-hora'),
(47, 51, 'Kilowatt-hora'),
(48, 52, 'Watt-hora'),
(49, 53, 'Kilovoltio'),
(50, 54, 'Voltio'),
(51, 55, 'Millar'),
(52, 56, 'Medio millar'),
(53, 57, 'Ciento'),
(54, 58, 'Docena'),
(55, 59, 'Unidad'),
(56, 99, 'Otra');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `nombre` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `password` varchar(250) NOT NULL,
  `admin` int(11) NOT NULL,
  `precios` int(11) NOT NULL,
  `latitud_ultima` double NOT NULL,
  `longitud_ultima` double NOT NULL,
  `fecha_tracking` date NOT NULL,
  `hora_tracking` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_server`, `unique_id`, `id_sucursal`, `id_usuario`, `id_empleado`, `nombre`, `usuario`, `password`, `admin`, `precios`, `latitud_ultima`, `longitud_ultima`, `fecha_tracking`, `hora_tracking`) VALUES
(1, 'O5f05eba70a0f04.98442249', 1, 1, -1, 'Administrador Suc. 1', 'admin1', 'f90d1250fd96b918b6d474a2e549510c', 1, 7, 0, 0, '0000-00-00', '00:00:00'),
(0, 'S64b5caf5339380.94421014', 1, 2, 1, 'CAJA1', 'caja1', 'e10adc3949ba59abbe56e057f20f883e', 0, 3, 0, 0, '0000-00-00', '00:00:00'),
(0, 'S64c02d5dd3ab17.24802055', 1, 3, 1, 'caja2', 'caja2', 'e10adc3949ba59abbe56e057f20f883e', 0, 1, 0, 0, '0000-00-00', '00:00:00'),
(0, 'S64c02d6f76cfe7.29116794', 1, 4, 1, 'caja3', 'caja3', 'e10adc3949ba59abbe56e057f20f883e', 0, 1, 0, 0, '0000-00-00', '00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_modulo`
--

CREATE TABLE `usuario_modulo` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_mod_user` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuario_modulo`
--

INSERT INTO `usuario_modulo` (`id_server`, `unique_id`, `id_sucursal`, `id_mod_user`, `id_modulo`, `id_usuario`) VALUES
(1, 'O5f05eba71ae8e9.81652603', 0, 1, 43, 47),
(2, 'O5f05eba71c1c60.19319526', 0, 2, 44, 47),
(3, 'O5f05eba71e0183.18929924', 0, 3, 68, 47),
(4, 'O5f05eba72018f7.09616987', 0, 4, 82, 47),
(5, 'O5f05eba7221f99.12348082', 0, 5, 83, 47),
(6, 'O5f05eba7244727.85262800', 0, 6, 1, 47),
(7, 'O5f05eba72642d0.41656359', 0, 7, 2, 47),
(8, 'O5f05eba7284cb6.06937575', 0, 8, 3, 47),
(9, 'O5f05eba72a90d1.48385613', 0, 9, 4, 47),
(10, 'O5f05eba72cbfb2.90806916', 0, 10, 5, 47),
(11, 'O5f05eba72eb022.85998647', 0, 11, 19, 47),
(12, 'O5f05eba730d496.36874177', 0, 12, 20, 47),
(13, 'O5f05eba73506d0.13785795', 0, 13, 21, 47),
(14, 'O5f05eba7371ab6.29594900', 0, 14, 22, 47),
(15, 'O5f05eba7391838.43509738', 0, 15, 23, 47),
(16, 'O5f05eba73b1477.98020687', 0, 16, 45, 47),
(17, 'O5f05eba73d1ef4.70581627', 0, 17, 46, 47),
(18, 'O5f05eba73f3c84.95446309', 0, 18, 47, 47),
(19, 'O5f05eba7420546.12793440', 0, 19, 48, 47),
(20, 'O5f05eba743b002.90327676', 0, 20, 49, 47),
(21, 'O5f05eba745b6a0.32624680', 0, 21, 77, 47),
(22, 'O5f05eba747da56.31031507', 0, 22, 78, 47),
(23, 'O5f05eba749e622.03335530', 0, 23, 79, 47),
(24, 'O5f05eba74bff29.65350240', 0, 24, 80, 47),
(25, 'O5f05eba74e0ad1.31748918', 0, 25, 81, 47),
(26, 'O5f05eba7503ff7.04224687', 0, 26, 98, 47),
(27, 'O5f05eba7524737.58081008', 0, 27, 99, 47),
(28, 'O5f05eba7545905.17483547', 0, 28, 100, 47),
(29, 'O5f05eba75675d8.12053181', 0, 29, 101, 47),
(30, 'O5f05eba7587d54.01453946', 0, 30, 103, 47),
(31, 'O5f05eba75a8a96.85164063', 0, 31, 72, 47),
(32, 'O5f05eba75ca113.32222166', 0, 32, 73, 47),
(33, 'O5f05eba75eb9d1.76648377', 0, 33, 74, 47),
(34, 'O5f05eba760c7d5.36478921', 0, 34, 75, 47),
(35, 'O5f05eba762d692.81865422', 0, 35, 76, 47),
(36, 'O5f05eba764ed30.82665757', 0, 36, 134, 47),
(37, 'O5f05eba766fe78.84673792', 0, 37, 135, 47),
(38, 'O5f05eba7691e96.26340448', 0, 38, 85, 47),
(39, 'O5f05eba76b1ac1.50503102', 0, 39, 86, 47),
(40, 'O5f05eba76d3b75.49413181', 0, 40, 87, 47),
(41, 'O5f05eba76f5e25.18755398', 0, 41, 88, 47),
(42, 'O5f05eba77182a6.71673330', 0, 42, 89, 47),
(43, 'O5f05eba7737c81.04834757', 0, 43, 90, 47),
(44, 'O5f05eba77598a7.18481436', 0, 44, 91, 47),
(45, 'O5f05eba777ab77.26591328', 0, 45, 92, 47),
(46, 'O5f05eba779b1d2.72962904', 0, 46, 93, 47),
(47, 'O5f05eba77bbad5.35651718', 0, 47, 94, 47),
(48, 'O5f05eba77de3c6.10790877', 0, 48, 95, 47),
(49, 'O5f05eba77fff06.08419738', 0, 49, 96, 47),
(50, 'O5f05eba7820c13.32140160', 0, 50, 97, 47),
(51, 'O5f05eba7842381.48159371', 0, 51, 137, 47),
(52, 'O5f05eba7863e60.82665602', 0, 52, 35, 47),
(53, 'O5f05eba7885cd4.70495642', 0, 53, 36, 47),
(54, 'O5f05eba78a6733.09101369', 0, 54, 106, 47),
(55, 'O5f05eba78c74f5.43801742', 0, 55, 107, 47),
(56, 'O5f05eba78e9161.19252888', 0, 56, 108, 47),
(57, 'O5f05eba7909ab4.25410698', 0, 57, 109, 47),
(58, 'O5f05eba792ad76.36825392', 0, 58, 110, 47),
(59, 'O5f05eba794cc86.39267265', 0, 59, 118, 47),
(60, 'O5f05eba796e393.47061949', 0, 60, 120, 47),
(61, 'O5f05eba7991759.35793907', 0, 61, 37, 47),
(62, 'O5f05eba79b2080.00714355', 0, 62, 38, 47),
(63, 'O5f05eba79d3731.62562662', 0, 63, 50, 47),
(64, 'O5f05eba79f50f4.88840057', 0, 64, 69, 47),
(65, 'O5f05eba7a17af3.20611541', 0, 65, 70, 47),
(66, 'O5f05eba7a38183.03729821', 0, 66, 71, 47),
(67, 'O5f05eba7a597c9.15747388', 0, 67, 84, 47),
(68, 'O5f05eba7a7a266.94183765', 0, 68, 124, 47),
(69, 'O5f05eba7a9c9f1.79898422', 0, 69, 133, 47),
(70, 'O5f05eba7abc960.58338406', 0, 70, 119, 47),
(71, 'O5f05eba7ade0b8.79836068', 0, 71, 121, 47),
(72, 'O5f05eba7b00639.00888099', 0, 72, 122, 47),
(73, 'O5f05eba7b239b3.56982231', 0, 73, 123, 47),
(74, 'O5f05eba7b43684.46772651', 0, 74, 126, 47),
(75, 'O5f05eba7b64225.03197469', 0, 75, 128, 47),
(76, 'O5f05eba7b85d77.32441486', 0, 76, 129, 47),
(77, 'O5f05eba7ba8d30.40335699', 0, 77, 130, 47),
(78, 'O5f05eba7bc9183.86333960', 0, 78, 138, 47),
(79, 'O5f05eba7becab5.68796319', 0, 79, 41, 47),
(80, 'O5f05eba7c0ef78.37119244', 0, 80, 42, 47),
(81, 'O5f05eba7c2e516.11550719', 0, 81, 111, 47),
(82, 'O5f05eba7c4f402.52634619', 0, 82, 112, 47),
(83, 'O5f05eba7c70597.38519073', 0, 83, 113, 47),
(84, 'O5f05eba7c90da5.38499666', 0, 84, 114, 47),
(85, 'O5f05eba7cb42b8.85182139', 0, 85, 115, 47),
(86, 'O5f05eba7cd43e3.47873502', 0, 86, 116, 47),
(87, 'O5f05eba7cf64d5.47061684', 0, 87, 117, 47),
(88, 'O5f05eba7d176f5.55129864', 0, 88, 127, 47),
(89, 'O5f05eba7d386a6.63325660', 0, 89, 132, 47),
(90, 'O5f05eba7d597a4.13054155', 0, 90, 54, 47),
(91, 'O5f05eba7d7acc7.69634282', 0, 91, 55, 47),
(92, 'O5f05eba7d9b8e8.24641520', 0, 92, 56, 47),
(93, 'O5f05eba7dbd589.11615068', 0, 93, 57, 47),
(94, 'O5f05eba7ddf661.45030385', 0, 94, 58, 47),
(95, 'O5f05eba7e013c0.31923651', 0, 95, 59, 47),
(96, 'O5f05eba7e20bd4.91132737', 0, 96, 60, 47),
(97, 'O5f05eba7e42517.76543079', 0, 97, 61, 47),
(98, 'O5f05eba7e64229.03928088', 0, 98, 62, 47),
(99, 'O5f05eba7e85709.62950319', 0, 99, 63, 47),
(100, 'O5f05eba7ea6331.73019304', 0, 100, 64, 47),
(101, 'O5f05eba7ec70f9.42771710', 0, 101, 65, 47),
(102, 'O5f05eba7ee8d81.46827640', 0, 102, 66, 47),
(103, 'O5f05eba7f0a469.54744761', 0, 103, 6, 47),
(104, 'O5f05eba7f2b435.26593953', 0, 104, 7, 47),
(105, 'O5f05eba800a520.28061681', 0, 105, 8, 47),
(106, 'O5f05eba802b7b1.98921420', 0, 106, 9, 47),
(107, 'O5f05eba804f479.63434537', 0, 107, 10, 47),
(108, 'O5f05eba80702b4.98892614', 0, 108, 11, 47),
(109, 'O5f05eba8091147.10241504', 0, 109, 12, 47),
(110, 'O5f05eba80b2d10.96914387', 0, 110, 13, 47),
(111, 'O5f05eba80d4dc7.19986469', 0, 111, 14, 47),
(112, 'O5f05eba80f5f37.24226037', 0, 112, 15, 47),
(113, 'O5f05eba8116f22.87618661', 0, 113, 16, 47),
(114, 'O5f05eba8139221.94620868', 0, 114, 17, 47),
(115, 'O5f05eba815a987.64215049', 0, 115, 18, 47),
(116, 'O5f05eba817b765.87393680', 0, 116, 139, 47),
(117, 'O5f05eba819c827.97737739', 0, 117, 24, 47),
(118, 'O5f05eba81bd494.43717212', 0, 118, 25, 47),
(119, 'O5f05eba81e0127.76879077', 0, 119, 26, 47),
(120, 'O5f05eba82017d9.88033404', 0, 120, 27, 47),
(121, 'O5f05eba8222aa0.44156906', 0, 121, 28, 47),
(122, 'O5f05eba8243690.45810191', 0, 122, 29, 47),
(123, 'O5f05eba8265b53.69713114', 0, 123, 30, 47),
(124, 'O5f05eba8286d02.94293890', 0, 124, 31, 47),
(125, 'O5f05eba82a8141.39828863', 0, 125, 32, 47),
(126, 'O5f05eba82c8f57.12915533', 0, 126, 33, 47),
(127, 'O5f05eba82eb007.30435545', 0, 127, 34, 47),
(128, 'O5f05eba830cc85.08525726', 0, 128, 39, 47),
(129, 'O5f05eba832e367.82924487', 0, 129, 40, 47),
(130, 'O5f05eba83506d3.69516042', 0, 130, 136, 47),
(0, 'S5f9dd8351cb739.55056043', 0, 494, 1, 9),
(0, 'S5f9dd8351edbe3.35054956', 0, 495, 2, 9),
(0, 'S5f9dd83520dfc9.90322170', 0, 496, 3, 9),
(0, 'S5f9dd83522fa39.09415688', 0, 497, 4, 9),
(0, 'S5f9dd83524eba6.98203605', 0, 498, 5, 9),
(0, 'S5f9dd835272153.46813536', 0, 499, 77, 9),
(0, 'S5f9dd835295e02.17174528', 0, 500, 78, 9),
(0, 'S5f9dd8352b6153.45823754', 0, 501, 79, 9),
(0, 'S5f9dd8352d6c72.15814187', 0, 502, 80, 9),
(0, 'S5f9dd8352f92b6.88099284', 0, 503, 81, 9),
(0, 'S5f9dd83531bf12.29642684', 0, 504, 36, 9),
(0, 'S5f9dd83533a8d3.05049042', 0, 505, 106, 9),
(0, 'S5f9dd83535b830.61149278', 0, 506, 107, 9),
(0, 'S5f9dd83537d365.99971622', 0, 507, 108, 9),
(0, 'S5f9dd8353a0386.65400090', 0, 508, 41, 9),
(0, 'S5f9dd8353c0450.00496912', 0, 509, 42, 9),
(0, 'S5f9dd8353e3e48.61773460', 0, 510, 111, 9),
(0, 'S5f9dd835403f31.14504898', 0, 511, 112, 9),
(0, 'S5f9dd835426617.18773987', 0, 512, 113, 9),
(0, 'S5f9dd835446eb8.51617413', 0, 513, 114, 9),
(0, 'S5f9dd835469935.83910080', 0, 514, 115, 9),
(0, 'S5f9dd8354890f4.05521166', 0, 515, 117, 9),
(0, 'S5f9dd8354a8088.77037238', 0, 516, 127, 9),
(0, 'S5f9dd8354ca035.32782339', 0, 517, 132, 9),
(0, 'S610acf6659f4d2.91427082', 0, 693, 1, 7),
(0, 'S610acf665a9939.58147207', 0, 694, 2, 7),
(0, 'S610acf665b91e8.87181552', 0, 695, 3, 7),
(0, 'S610acf665c9a84.45173514', 0, 696, 5, 7),
(0, 'S610acf665d5e57.07420105', 0, 697, 19, 7),
(0, 'S610acf665e1532.16614734', 0, 698, 20, 7),
(0, 'S610acf665ed4b0.02487903', 0, 699, 21, 7),
(0, 'S610acf665f6489.59624445', 0, 700, 22, 7),
(0, 'S610acf666045a3.58056820', 0, 701, 23, 7),
(0, 'S610acf6660faf0.93307743', 0, 702, 50, 7),
(0, 'S610acf66617944.41770454', 0, 703, 41, 7),
(0, 'S610acf6661d444.96133946', 0, 704, 42, 7),
(0, 'S610acf666254f4.23685395', 0, 705, 111, 7),
(0, 'S610acf6662b3f1.01891326', 0, 706, 112, 7),
(0, 'S610acf666312a7.78360296', 0, 707, 113, 7),
(0, 'S610acf66638f02.62112956', 0, 708, 114, 7),
(0, 'S610acf6663f779.69507209', 0, 709, 115, 7),
(0, 'S610acf666484f6.96390967', 0, 710, 117, 7),
(0, 'S610acf6664f145.79788480', 0, 711, 132, 7),
(0, 'S610acf66657957.73167172', 0, 712, 35, 7),
(0, 'S610acf6665d968.74286760', 0, 713, 36, 7),
(0, 'S610acf66664c25.75433756', 0, 714, 106, 7),
(0, 'S610acf6666a864.85481602', 0, 715, 107, 7),
(0, 'S610acf666701c9.74839551', 0, 716, 108, 7),
(0, 'S610acf66677460.54093903', 0, 717, 109, 7),
(0, 'S610acf6667d444.04904823', 0, 718, 110, 7),
(0, 'S610acf66685f05.82722856', 0, 719, 118, 7),
(0, 'S610acf6668bea6.67286549', 0, 720, 120, 7),
(0, 'S610acf66692eb5.09017823', 0, 721, 142, 7),
(0, 'S610acf66698bf7.47694386', 0, 722, 143, 7),
(0, 'S610ad0d795af08.79252694', 0, 723, 43, 8),
(0, 'S610ad0d7961502.02598228', 0, 724, 44, 8),
(0, 'S610ad0d7967c78.07963726', 0, 725, 134, 8),
(0, 'S610ad0d796e069.75347579', 0, 726, 154, 8),
(0, 'S610ad0d7973fe4.17982929', 0, 727, 155, 8),
(0, 'S610ad0d7979066.44427605', 0, 728, 1, 8),
(0, 'S610ad0d797f0c6.37899458', 0, 729, 2, 8),
(0, 'S610ad0d7984908.35992109', 0, 730, 3, 8),
(0, 'S610ad0d7989732.72948911', 0, 731, 5, 8),
(0, 'S610ad0d79900f4.53175389', 0, 732, 68, 8),
(0, 'S610ad0d7995370.94694903', 0, 733, 82, 8),
(0, 'S610ad0d799a905.05274928', 0, 734, 83, 8),
(0, 'S610ad0d79a1427.26848814', 0, 735, 151, 8),
(0, 'S610ad0d79a65b0.18121550', 0, 736, 19, 8),
(0, 'S610ad0d79ab923.59255402', 0, 737, 20, 8),
(0, 'S610ad0d79b2341.95376187', 0, 738, 21, 8),
(0, 'S610ad0d79b7523.97911586', 0, 739, 22, 8),
(0, 'S610ad0d79bc966.01838953', 0, 740, 23, 8),
(0, 'S610ad0d79c2cd7.54557885', 0, 741, 45, 8),
(0, 'S610ad0d79ca775.63340847', 0, 742, 46, 8),
(0, 'S610ad0d79d0c49.13090112', 0, 743, 47, 8),
(0, 'S610ad0d79d9204.83531332', 0, 744, 48, 8),
(0, 'S610ad0d79df012.66835963', 0, 745, 49, 8),
(0, 'S610ad0d79e40d5.23667441', 0, 746, 77, 8),
(0, 'S610ad0d79e97f8.33104884', 0, 747, 78, 8),
(0, 'S610ad0d79ef752.24067824', 0, 748, 79, 8),
(0, 'S610ad0d79f4615.93668846', 0, 749, 80, 8),
(0, 'S610ad0d79f9e26.60192428', 0, 750, 81, 8),
(0, 'S610ad0d7a001f1.00726869', 0, 751, 37, 8),
(0, 'S610ad0d7a051f7.60882030', 0, 752, 38, 8),
(0, 'S610ad0d7a0a716.92592320', 0, 753, 50, 8),
(0, 'S610ad0d7a107c7.67396708', 0, 754, 69, 8),
(0, 'S610ad0d7a158c9.73328492', 0, 755, 70, 8),
(0, 'S610ad0d7a1cc08.79701621', 0, 756, 84, 8),
(0, 'S610ad0d7a23230.18841886', 0, 757, 133, 8),
(0, 'S610ad0d7a28964.81627331', 0, 758, 92, 8),
(0, 'S610ad0d7a2ff90.02463156', 0, 759, 93, 8),
(0, 'S610ad0d7a37527.61838001', 0, 760, 94, 8),
(0, 'S610ad0d7a3f979.01412762', 0, 761, 95, 8),
(0, 'S610ad0d7a48bb4.85850331', 0, 762, 96, 8),
(0, 'S610ad0d7a50c15.27089714', 0, 763, 97, 8),
(0, 'S610ad0d7a56640.54363430', 0, 764, 137, 8),
(0, 'S610ad0d7a5c685.72228978', 0, 765, 121, 8),
(0, 'S610ad0d7a615b4.39968199', 0, 766, 122, 8),
(0, 'S610ad0d7a66672.73909177', 0, 767, 126, 8),
(0, 'S610ad0d7a6ce17.97006599', 0, 768, 144, 8),
(0, 'S610ad0d7a71fe4.45611875', 0, 769, 145, 8),
(0, 'S610ad0d7a77661.47159942', 0, 770, 146, 8),
(0, 'S610ad0d7a7cf93.64908214', 0, 771, 147, 8),
(0, 'S610ad0d7a81fe2.81848657', 0, 772, 152, 8),
(0, 'S610ad0d7a87290.22153305', 0, 773, 153, 8),
(0, 'S610ad0d7a8d289.20634218', 0, 774, 41, 8),
(0, 'S610ad0d7a92431.17473811', 0, 775, 42, 8),
(0, 'S610ad0d7a987a0.77550513', 0, 776, 111, 8),
(0, 'S610ad0d7a9d915.09086430', 0, 777, 112, 8),
(0, 'S610ad0d7aa2bc3.66461596', 0, 778, 113, 8),
(0, 'S610ad0d7aa8f93.12985297', 0, 779, 114, 8),
(0, 'S610ad0d7aae069.92787137', 0, 780, 115, 8),
(0, 'S610ad0d7ab32a1.51420224', 0, 781, 116, 8),
(0, 'S610ad0d7ab9273.45874111', 0, 782, 117, 8),
(0, 'S610ad0d7abe412.46752421', 0, 783, 132, 8),
(0, 'S610ad0d7ac3865.86496844', 0, 784, 35, 8),
(0, 'S610ad0d7ac98a7.56022867', 0, 785, 36, 8),
(0, 'S610ad0d7ace962.37026478', 0, 786, 106, 8),
(0, 'S610ad0d7ad4297.71761676', 0, 787, 107, 8),
(0, 'S610ad0d7adc321.60236916', 0, 788, 108, 8),
(0, 'S610ad0d7ae27e4.83617105', 0, 789, 109, 8),
(0, 'S610ad0d7ae8e51.97789267', 0, 790, 110, 8),
(0, 'S610ad0d7aee587.17389883', 0, 791, 118, 8),
(0, 'S610ad0d7af4325.03680388', 0, 792, 120, 8),
(0, 'S610ad0d7afa9f4.10521781', 0, 793, 142, 8),
(0, 'S610ad0d7b02b95.08625829', 0, 794, 143, 8),
(0, 'S610ad0d7b09d69.59448799', 0, 795, 6, 8),
(0, 'S610ad0d7b10707.93177565', 0, 796, 7, 8),
(0, 'S610ad0d7b19be1.30534853', 0, 797, 8, 8),
(0, 'S610ad0d7b20f77.83863206', 0, 798, 10, 8),
(0, 'S610ad0d7b280e6.34314460', 0, 799, 11, 8),
(0, 'S610ad0d7b2dc42.02421081', 0, 800, 12, 8),
(0, 'S610ad0d7b35343.94482126', 0, 801, 13, 8),
(0, 'S610ad0d7b4f843.96183538', 0, 802, 14, 8),
(0, 'S610ad0d7b60c88.83573618', 0, 803, 15, 8),
(0, 'S610ad0d7b6e2a0.12332708', 0, 804, 16, 8),
(0, 'S610ad0d7b88776.14776280', 0, 805, 17, 8),
(0, 'S610ad0d7bcf221.08111281', 0, 806, 148, 8),
(0, 'S610ad0d7bd6a59.28161841', 0, 807, 149, 8),
(0, 'S610ad0d7beff90.76224818', 0, 808, 150, 8),
(0, 'S610ad0d7bf56a9.45015996', 0, 809, 24, 8),
(0, 'S610ad0d7bfbbd7.97316669', 0, 810, 25, 8),
(0, 'S610ad0d7c02215.82131546', 0, 811, 26, 8),
(0, 'S610ad0d7c07620.61624655', 0, 812, 27, 8),
(0, 'S610ad0d7c0cae9.14655271', 0, 813, 28, 8),
(0, 'S610ad0d7c12af9.64539044', 0, 814, 29, 8),
(0, 'S610ad0d7c17c81.24240193', 0, 815, 30, 8),
(0, 'S610ad0d7c1d337.17014139', 0, 816, 31, 8),
(0, 'S610ad0d7c23da2.07566763', 0, 817, 32, 8),
(0, 'S610ad0d7c290c2.68029828', 0, 818, 33, 8),
(0, 'S610ad0d7c2f346.30774898', 0, 819, 34, 8),
(0, 'S610ad0d7c34480.57485593', 0, 820, 39, 8),
(0, 'S610ad0d7c39a52.04036532', 0, 821, 40, 8),
(0, 'S610ad0d7c40c92.79003274', 0, 822, 136, 8),
(0, 'S610ad0d7c463a1.54389001', 0, 823, 140, 8),
(0, 'S610ad0d7c4be88.52438741', 0, 824, 141, 8),
(0, 'S611bd8929e8a00.01005015', 0, 825, 1, 6),
(0, 'S611bd8929f3652.04524507', 0, 826, 2, 6),
(0, 'S611bd8929fbda9.29150217', 0, 827, 3, 6),
(0, 'S611bd892a055a8.75837380', 0, 828, 5, 6),
(0, 'S611bd892a0f310.25890027', 0, 829, 50, 6),
(0, 'S611bd892a171a1.75786386', 0, 830, 41, 6),
(0, 'S611bd892a20d79.26541391', 0, 831, 42, 6),
(0, 'S611bd892a29347.78066606', 0, 832, 111, 6),
(0, 'S611bd892a334a5.16515600', 0, 833, 112, 6),
(0, 'S611bd892a3d9c1.26957435', 0, 834, 113, 6),
(0, 'S611bd892a45fc6.29978253', 0, 835, 114, 6),
(0, 'S611bd892a4fa58.17548682', 0, 836, 115, 6),
(0, 'S611bd892a576f9.83138873', 0, 837, 117, 6),
(0, 'S611bd892a60827.60242191', 0, 838, 132, 6),
(0, 'S611bd892a686f4.51896453', 0, 839, 36, 6),
(0, 'S611bd892a71220.59681834', 0, 840, 106, 6),
(0, 'S611bd892a77c76.65306181', 0, 841, 107, 6),
(0, 'S611bd892a80205.33211469', 0, 842, 108, 6),
(0, 'S611bd892a86c54.16680536', 0, 843, 109, 6),
(0, 'S611bd892a8f144.22090698', 0, 844, 110, 6),
(0, 'S611bd892a95947.54525661', 0, 845, 118, 6),
(0, 'S611bd892a9db11.99508921', 0, 846, 120, 6),
(0, 'S611bd892aa3e66.70478364', 0, 847, 142, 6),
(0, 'S611bd892aac101.25241261', 0, 848, 143, 6),
(0, 'S62a74cfdab13c1.94588802', 0, 960, 41, 15),
(0, 'S62a74cfdabf5b8.06767248', 0, 961, 42, 15),
(0, 'S62a74cfdacd1f8.40975392', 0, 962, 111, 15),
(0, 'S62a74cfdad9c68.51030116', 0, 963, 112, 15),
(0, 'S62a74cfdae6760.55247022', 0, 964, 113, 15),
(0, 'S62a74cfdaf2fd7.29988742', 0, 965, 114, 15),
(0, 'S62a74cfdafec65.46138807', 0, 966, 115, 15),
(0, 'S62a74cfdb0ac54.36980285', 0, 967, 116, 15),
(0, 'S62a74cfdb14a71.73142137', 0, 968, 117, 15),
(0, 'S62a74cfdb19326.09827834', 0, 969, 132, 15),
(0, 'S62a74cfdb1cdf5.56187077', 0, 970, 36, 15),
(0, 'S62a74cfdb204b8.73143857', 0, 971, 106, 15),
(0, 'S62a74cfdb23ab4.54905501', 0, 972, 107, 15),
(0, 'S62a74cfdb271d8.14525167', 0, 973, 108, 15),
(0, 'S62a74cfdb2a600.88786760', 0, 974, 109, 15),
(0, 'S62a74cfdb2dd24.67621606', 0, 975, 110, 15),
(0, 'S62a74cfdb31076.38236283', 0, 976, 120, 15),
(0, 'S62a74cfdb34495.31383941', 0, 977, 165, 15),
(0, 'S62a74cfdb376c1.23439892', 0, 978, 6, 15),
(0, 'S62a74cfdb3aa47.13614311', 0, 979, 7, 15),
(0, 'S62a74cfdb3dda4.80114074', 0, 980, 8, 15),
(0, 'S62a74cfdb410d8.14146853', 0, 981, 10, 15),
(0, 'S62a74cfdb44326.71667931', 0, 982, 11, 15),
(0, 'S62a74cfdb476d4.87850306', 0, 983, 12, 15),
(0, 'S62a74cfdb4aa20.27740893', 0, 984, 13, 15),
(0, 'S62a74cfdb4dea5.99283834', 0, 985, 15, 15),
(0, 'S62a74cfdb51317.69281297', 0, 986, 16, 15),
(0, 'S62a74cfdb5b943.12778751', 0, 987, 17, 15),
(0, 'S62a74cfdb5ec96.96066558', 0, 988, 148, 15),
(0, 'S62a74cfdb61f72.10385662', 0, 989, 149, 15),
(0, 'S62a74cfdb65642.00958353', 0, 990, 150, 15),
(0, 'S62c5ef7ee31f85.80884874', 0, 1025, 168, 18),
(0, 'S62c5ef7ee353d6.45647299', 0, 1026, 37, 18),
(0, 'S62c5ef7ee38737.46462547', 0, 1027, 38, 18),
(0, 'S62c5ef7ee3ccb1.55427872', 0, 1028, 50, 18),
(0, 'S62c5ef7ee405f5.03162202', 0, 1029, 69, 18),
(0, 'S62c5ef7ee43a78.17745372', 0, 1030, 70, 18),
(0, 'S62c5ef7ee46ea1.35297837', 0, 1031, 84, 18),
(0, 'S62c5ef7ee4aa05.80984881', 0, 1032, 41, 18),
(0, 'S62c5ef7ee4e712.55108880', 0, 1033, 42, 18),
(0, 'S62c5ef7ee52de1.87358926', 0, 1034, 111, 18),
(0, 'S62c5ef7ee566e3.96397310', 0, 1035, 112, 18),
(0, 'S62c5ef7ee59f64.67912482', 0, 1036, 113, 18),
(0, 'S62c5ef7ee5dac1.59609575', 0, 1037, 117, 18),
(0, 'S62c5ef7ee610f2.71790884', 0, 1038, 132, 18),
(0, 'S62c5ef7ee646e8.90767548', 0, 1039, 36, 18),
(0, 'S62c5ef7ee67dc3.23251901', 0, 1040, 106, 18),
(0, 'S62c5ef7ee6b363.87161132', 0, 1041, 107, 18),
(0, 'S62c5ef7ee6e899.79214167', 0, 1042, 165, 18),
(0, 'S6324bd9032c301.86556582', 0, 1065, 43, 26),
(0, 'S6324bd9033c716.43137017', 0, 1066, 44, 26),
(0, 'S6324bd90341720.57589752', 0, 1067, 167, 26),
(0, 'S6324bd903458c0.31124724', 0, 1068, 168, 26),
(0, 'S6324bd90349d05.56603851', 0, 1069, 1, 26),
(0, 'S6324bd9034e7c9.46917472', 0, 1070, 2, 26),
(0, 'S6324bd9035e203.73170468', 0, 1071, 3, 26),
(0, 'S6324bd90364898.90821532', 0, 1072, 5, 26),
(0, 'S6324bd9036d898.61383048', 0, 1073, 68, 26),
(0, 'S6324bd90373388.07164907', 0, 1074, 82, 26),
(0, 'S6324bd903779d7.64490038', 0, 1075, 83, 26),
(0, 'S6324bd9037bf50.12865300', 0, 1076, 151, 26),
(0, 'S6324bd903830d4.46712943', 0, 1077, 19, 26),
(0, 'S6324bd903878d7.25194071', 0, 1078, 20, 26),
(0, 'S6324bd9038d2c0.00948027', 0, 1079, 21, 26),
(0, 'S6324bd90393f54.02775722', 0, 1080, 22, 26),
(0, 'S6324bd90398c85.16520562', 0, 1081, 23, 26),
(0, 'S6324bd9039f2a9.58905088', 0, 1082, 45, 26),
(0, 'S6324bd903a3b54.32203486', 0, 1083, 46, 26),
(0, 'S6324bd903a9487.82089418', 0, 1084, 47, 26),
(0, 'S6324bd903af0e5.45084375', 0, 1085, 48, 26),
(0, 'S6324bd903b35b8.29477308', 0, 1086, 49, 26),
(0, 'S6324bd903bbba8.36040084', 0, 1087, 77, 26),
(0, 'S6324bd903c2fe0.75070140', 0, 1088, 78, 26),
(0, 'S6324bd903cdc25.18460374', 0, 1089, 79, 26),
(0, 'S6324bd903d2c06.40311374', 0, 1090, 80, 26),
(0, 'S6324bd903d6dd6.28651414', 0, 1091, 81, 26),
(0, 'S6324bd903e1223.78573923', 0, 1092, 50, 26),
(0, 'S6324bd903e57a8.22597122', 0, 1093, 41, 26),
(0, 'S6324bd903ea089.83656069', 0, 1094, 42, 26),
(0, 'S6324bd903efd15.10569881', 0, 1095, 111, 26),
(0, 'S6324bd903f4513.08149373', 0, 1096, 112, 26),
(0, 'S6324bd903f8b90.88045367', 0, 1097, 113, 26),
(0, 'S6324bd903fec70.04664528', 0, 1098, 115, 26),
(0, 'S6324bd90403039.79193398', 0, 1099, 117, 26),
(0, 'S6324bd90407de3.97005320', 0, 1100, 132, 26),
(0, 'S6324bd90414345.76870808', 0, 1101, 36, 26),
(0, 'S6324bd90418f67.20890378', 0, 1102, 106, 26),
(0, 'S6324bd9041f454.07815182', 0, 1103, 107, 26),
(0, 'S6324bd90423c29.65464394', 0, 1104, 108, 26),
(0, 'S6324bd904282f1.21939617', 0, 1105, 109, 26),
(0, 'S6324bd9042d1e6.02709651', 0, 1106, 110, 26),
(0, 'S6324bd904314e6.13744394', 0, 1107, 118, 26),
(0, 'S6324bd9043bca0.73769483', 0, 1108, 120, 26),
(0, 'S6324bd90447399.81257238', 0, 1109, 142, 26),
(0, 'S6324bd9044ee71.52733731', 0, 1110, 165, 26),
(0, 'S6324bd904542a8.68319724', 0, 1111, 6, 26),
(0, 'S6324bd90459b22.26237386', 0, 1112, 7, 26),
(0, 'S6324bd9045ec43.24661242', 0, 1113, 8, 26),
(0, 'S6324bd90464989.78018886', 0, 1114, 10, 26),
(0, 'S6324bd90469f20.57021483', 0, 1115, 11, 26),
(0, 'S6324bd9046ecf0.20106051', 0, 1116, 12, 26),
(0, 'S6324bd90473dc8.11781817', 0, 1117, 13, 26),
(0, 'S6324bd904791f9.21930366', 0, 1118, 14, 26),
(0, 'S6324bd9047d604.96220233', 0, 1119, 15, 26),
(0, 'S6324bd90481ae2.92263689', 0, 1120, 16, 26),
(0, 'S6324bd904867f3.24101580', 0, 1121, 17, 26),
(0, 'S6324bd9048b8c1.07104038', 0, 1122, 148, 26),
(0, 'S6324bd90496455.35429157', 0, 1123, 149, 26),
(0, 'S6324bd9049d0a9.60793350', 0, 1124, 150, 26),
(0, 'S6324bd904a1fa2.17662466', 0, 1125, 24, 26),
(0, 'S6324bd904a8fd0.38739411', 0, 1126, 25, 26),
(0, 'S6324bd904ada40.59169201', 0, 1127, 26, 26),
(0, 'S6324bd904b24a3.16600723', 0, 1128, 27, 26),
(0, 'S6324bd904b78b3.27932202', 0, 1129, 28, 26),
(0, 'S6324bd904bc236.80208267', 0, 1130, 29, 26),
(0, 'S6324bd904c07d9.26705007', 0, 1131, 30, 26),
(0, 'S6324bd904c5267.31861785', 0, 1132, 31, 26),
(0, 'S6324bd904cc193.23311795', 0, 1133, 32, 26),
(0, 'S6324bd904d0582.85861802', 0, 1134, 33, 26),
(0, 'S6324bd904d56f1.88755497', 0, 1135, 34, 26),
(0, 'S6324bd904dd663.72784488', 0, 1136, 39, 26),
(0, 'S6324bd904f4772.38418340', 0, 1137, 40, 26),
(0, 'S6324bd904fa0a9.04271889', 0, 1138, 136, 26),
(0, 'S6324bd90500ad4.18493415', 0, 1139, 140, 26),
(0, 'S6324bd90509419.62392269', 0, 1140, 141, 26),
(0, 'S6324bd90510822.85200984', 0, 1141, 121, 26),
(0, 'S6324bd90516ea9.80894662', 0, 1142, 122, 26),
(0, 'S6324bd9051c230.97519398', 0, 1143, 126, 26),
(0, 'S6324bd90521dc2.05046430', 0, 1144, 144, 26),
(0, 'S6324bd90528538.74425099', 0, 1145, 145, 26),
(0, 'S6324bd905310f4.46705171', 0, 1146, 146, 26),
(0, 'S6324bd905376e1.52436347', 0, 1147, 147, 26),
(0, 'S6324bd9053cc35.55565318', 0, 1148, 152, 26),
(0, 'S6324bd90542369.74397828', 0, 1149, 153, 26),
(0, 'S6324bd9054a0f8.48684305', 0, 1150, 156, 26),
(0, 'S6324bd90550065.00397489', 0, 1151, 157, 26),
(0, 'S6324bd905573f9.60138138', 0, 1152, 158, 26),
(0, 'S6324bd9055db01.06247212', 0, 1153, 159, 26),
(0, 'S6324bd90564145.23103799', 0, 1154, 160, 26),
(0, 'S6324bd90569a24.42415055', 0, 1155, 161, 26),
(0, 'S6324bd9056f612.39646137', 0, 1156, 162, 26),
(0, 'S6324bd90575f70.33985811', 0, 1157, 163, 26),
(0, 'S6324bd9057c100.76584474', 0, 1158, 169, 26),
(0, 'S6329e2d9c11371.89589083', 0, 1481, 41, 20),
(0, 'S6329e2d9c196b7.17160925', 0, 1482, 42, 20),
(0, 'S6329e2d9c24ca7.92501915', 0, 1483, 111, 20),
(0, 'S6329e2d9c2f638.68838553', 0, 1484, 112, 20),
(0, 'S6329e2d9c37324.22337047', 0, 1485, 113, 20),
(0, 'S6329e2d9c3fde4.78738912', 0, 1486, 114, 20),
(0, 'S6329e2d9c46566.48589516', 0, 1487, 115, 20),
(0, 'S6329e2d9c50fa0.55711295', 0, 1488, 116, 20),
(0, 'S6329e2d9c637a7.45843646', 0, 1489, 117, 20),
(0, 'S6329e2d9c6d6c1.72632403', 0, 1490, 132, 20),
(0, 'S6329e2d9c75252.42442813', 0, 1491, 36, 20),
(0, 'S6329e2d9c7e725.23615916', 0, 1492, 106, 20),
(0, 'S6329e2d9c85d95.89404796', 0, 1493, 107, 20),
(0, 'S6329e2d9c8f451.88393427', 0, 1494, 108, 20),
(0, 'S6329e2d9c9d2a7.22100369', 0, 1495, 109, 20),
(0, 'S632a22bc37c6e7.79043352', 0, 1504, 43, 27),
(0, 'S632a22bc398ec5.27257839', 0, 1505, 44, 27),
(0, 'S632a22bc3ae4e9.69352568', 0, 1506, 167, 27),
(0, 'S632a22bc3bd515.98498566', 0, 1507, 168, 27),
(0, 'S632a22bc3c6184.10777711', 0, 1508, 134, 27),
(0, 'S632a22bc3cfab8.94634602', 0, 1509, 154, 27),
(0, 'S632a22bc3d8c19.30903945', 0, 1510, 155, 27),
(0, 'S632a22bc3e5a84.28453496', 0, 1511, 1, 27),
(0, 'S632a22bc3ee019.23253320', 0, 1512, 2, 27),
(0, 'S632a22bc3f6487.21208712', 0, 1513, 3, 27),
(0, 'S632a22bc405124.66909625', 0, 1514, 5, 27),
(0, 'S632a22bc40f805.49560981', 0, 1515, 68, 27),
(0, 'S632a22bc415519.96872190', 0, 1516, 82, 27),
(0, 'S632a22bc41c0e0.92027805', 0, 1517, 83, 27),
(0, 'S632a22bc4219c6.30434075', 0, 1518, 151, 27),
(0, 'S632a22bc426f50.32533007', 0, 1519, 19, 27),
(0, 'S632a22bc4305e6.19804684', 0, 1520, 20, 27),
(0, 'S632a22bc437238.50090019', 0, 1521, 21, 27),
(0, 'S632a22bc43f442.25486850', 0, 1522, 22, 27),
(0, 'S632a22bc446430.47537743', 0, 1523, 23, 27),
(0, 'S632a22bc44ef06.83246722', 0, 1524, 45, 27),
(0, 'S632a22bc455b64.14089991', 0, 1525, 46, 27),
(0, 'S632a22bc45d2a8.80420865', 0, 1526, 47, 27),
(0, 'S632a22bc4634a3.96507319', 0, 1527, 48, 27),
(0, 'S632a22bc46a553.67153921', 0, 1528, 49, 27),
(0, 'S632a22bc4718c1.80159739', 0, 1529, 77, 27),
(0, 'S632a22bc4796a7.89365919', 0, 1530, 78, 27),
(0, 'S632a22bc4804d7.51799234', 0, 1531, 79, 27),
(0, 'S632a22bc488a42.86726764', 0, 1532, 80, 27),
(0, 'S632a22bc4921d3.67656220', 0, 1533, 81, 27),
(0, 'S632a22bc49b3e8.91368219', 0, 1534, 50, 27),
(0, 'S632a22bc4a2e97.37560727', 0, 1535, 133, 27),
(0, 'S632a22bc4abcb5.10235447', 0, 1536, 92, 27),
(0, 'S632a22bc4b2ca6.46317203', 0, 1537, 93, 27),
(0, 'S632a22bc4bb328.59047650', 0, 1538, 137, 27),
(0, 'S632a22bc4c2682.67760692', 0, 1539, 41, 27),
(0, 'S632a22bc4da841.63861450', 0, 1540, 42, 27),
(0, 'S632a22bc4e43d8.14346790', 0, 1541, 111, 27),
(0, 'S632a22bc4ec2c0.78496923', 0, 1542, 112, 27),
(0, 'S632a22bc4f5c22.23063680', 0, 1543, 113, 27),
(0, 'S632a22bc4fe812.71755601', 0, 1544, 114, 27),
(0, 'S632a22bc5066f4.27326330', 0, 1545, 115, 27),
(0, 'S632a22bc50e4e1.40755198', 0, 1546, 117, 27),
(0, 'S632a22bc515868.15778380', 0, 1547, 132, 27),
(0, 'S632a22bc51d619.32604813', 0, 1548, 35, 27),
(0, 'S632a22bc524098.11275484', 0, 1549, 36, 27),
(0, 'S632a22bc52bfa4.38331532', 0, 1550, 106, 27),
(0, 'S632a22bc532b15.69761948', 0, 1551, 107, 27),
(0, 'S632a22bc53b680.09399609', 0, 1552, 108, 27),
(0, 'S632a22bc541cc3.74956177', 0, 1553, 109, 27),
(0, 'S632a22bc549a87.55890913', 0, 1554, 110, 27),
(0, 'S632a22bc54ffa2.08037958', 0, 1555, 118, 27),
(0, 'S632a22bc55d0a7.80656670', 0, 1556, 120, 27),
(0, 'S632a22bc566210.17053113', 0, 1557, 142, 27),
(0, 'S632a22bc570c72.63244503', 0, 1558, 143, 27),
(0, 'S632a22bc579e89.68848429', 0, 1559, 165, 27),
(0, 'S632a22bc580b36.53548303', 0, 1560, 6, 27),
(0, 'S632a22bc588c02.52776538', 0, 1561, 7, 27),
(0, 'S632a22bc58f793.52433485', 0, 1562, 8, 27),
(0, 'S632a22bc596e94.26601160', 0, 1563, 10, 27),
(0, 'S632a22bc59d3a1.31920209', 0, 1564, 11, 27),
(0, 'S632a22bc5a5d97.28996362', 0, 1565, 12, 27),
(0, 'S632a22bc5ac804.96508817', 0, 1566, 13, 27),
(0, 'S632a22bc5b55e2.92109339', 0, 1567, 14, 27),
(0, 'S632a22bc5bb347.99773185', 0, 1568, 15, 27),
(0, 'S632a22bc5c3ff3.75380726', 0, 1569, 16, 27),
(0, 'S632a22bc5cb066.03382952', 0, 1570, 17, 27),
(0, 'S632a22bc5d44f8.20992556', 0, 1571, 24, 27),
(0, 'S632a22bc5db329.39435184', 0, 1572, 25, 27),
(0, 'S632a22bc5e2fc2.51464741', 0, 1573, 26, 27),
(0, 'S632a22bc5e96e6.07443774', 0, 1574, 27, 27),
(0, 'S632a22bc5efbf3.87123542', 0, 1575, 28, 27),
(0, 'S632a22bc5f7387.47125320', 0, 1576, 29, 27),
(0, 'S632a22bc5fdfc6.82722772', 0, 1577, 30, 27),
(0, 'S632a22bc605ca7.38604467', 0, 1578, 31, 27),
(0, 'S632a22bc60c2d0.86281161', 0, 1579, 32, 27),
(0, 'S632a22bc6139e3.23595852', 0, 1580, 33, 27),
(0, 'S632a22bc61a6b1.57610130', 0, 1581, 34, 27),
(0, 'S632a22bc6220d7.14193600', 0, 1582, 39, 27),
(0, 'S632a22bc628728.51743411', 0, 1583, 40, 27),
(0, 'S632a22bc630ac7.50432351', 0, 1584, 136, 27),
(0, 'S632a22bc637222.91722965', 0, 1585, 140, 27),
(0, 'S632a22bc63d320.91986132', 0, 1586, 141, 27),
(0, 'S632a22bc6453c2.95750104', 0, 1587, 121, 27),
(0, 'S632a22bc64c738.42673381', 0, 1588, 122, 27),
(0, 'S632a22bc6540f4.01372860', 0, 1589, 126, 27),
(0, 'S632a22bc65b004.42347721', 0, 1590, 144, 27),
(0, 'S632a22bc662c19.03203924', 0, 1591, 145, 27),
(0, 'S632a22bc669586.93441324', 0, 1592, 146, 27),
(0, 'S632a22bc670c95.35632963', 0, 1593, 147, 27),
(0, 'S632a22bc6775d2.42528230', 0, 1594, 152, 27),
(0, 'S632a22bc67ee05.80173501', 0, 1595, 153, 27),
(0, 'S632a22bc6855e3.89199679', 0, 1596, 156, 27),
(0, 'S632a22bc68c4b9.52996655', 0, 1597, 157, 27),
(0, 'S632a22bc696283.73680024', 0, 1598, 158, 27),
(0, 'S632a22bc69ef74.98007430', 0, 1599, 159, 27),
(0, 'S632a22bc6a56b8.83284502', 0, 1600, 160, 27),
(0, 'S632a22bc6ad4c1.03329524', 0, 1601, 161, 27),
(0, 'S632a22bc6b4173.30454572', 0, 1602, 162, 27),
(0, 'S632a22bc6ba587.67449551', 0, 1603, 163, 27),
(0, 'S632a22bc6c21b1.98697218', 0, 1604, 169, 27),
(0, 'S632e12f754ef85.19181294', 0, 1612, 41, 28),
(0, 'S632e12f755aed3.38983483', 0, 1613, 42, 28),
(0, 'S632e12f7563039.75818391', 0, 1614, 111, 28),
(0, 'S632e12f756ac78.07830230', 0, 1615, 112, 28),
(0, 'S632e12f7572a47.30440329', 0, 1616, 113, 28),
(0, 'S632e12f757a371.48347934', 0, 1617, 114, 28),
(0, 'S632e12f7581046.09378706', 0, 1618, 115, 28),
(0, 'S632e12f75ab883.83438019', 0, 1619, 116, 28),
(0, 'S632e12f75b5639.07207995', 0, 1620, 117, 28),
(0, 'S632e12f75be461.21160533', 0, 1621, 132, 28),
(0, 'S632e12f75c78f2.88327381', 0, 1622, 36, 28),
(0, 'S632e12f75d0486.97421305', 0, 1623, 106, 28),
(0, 'S632e12f75d7f25.48052666', 0, 1624, 107, 28),
(0, 'S632e12f75df3e9.40610250', 0, 1625, 108, 28),
(0, 'S632e12f75eb148.37017900', 0, 1626, 109, 28),
(0, 'S632e12f75f5908.53899244', 0, 1627, 110, 28),
(0, 'S632e12f75feda1.41948676', 0, 1628, 118, 28),
(0, 'S633607b612fe15.72084749', 0, 1639, 41, 29),
(0, 'S633607b6136d77.29305827', 0, 1640, 42, 29),
(0, 'S633607b613ee52.95365302', 0, 1641, 111, 29),
(0, 'S633607b6144eb6.17263019', 0, 1642, 112, 29),
(0, 'S633607b614b8e6.63068235', 0, 1643, 113, 29),
(0, 'S633607b61534d2.42369280', 0, 1644, 114, 29),
(0, 'S633607b615a482.65683668', 0, 1645, 115, 29),
(0, 'S633607b6161115.71842458', 0, 1646, 117, 29),
(0, 'S633607b61672e4.17429425', 0, 1647, 132, 29),
(0, 'S633607b616e593.25875338', 0, 1648, 36, 29),
(0, 'S63360a9fa32d72.12061587', 0, 1670, 41, 17),
(0, 'S63360a9fa38f73.72064469', 0, 1671, 42, 17),
(0, 'S63360a9fa3e935.66596997', 0, 1672, 111, 17),
(0, 'S63360a9fa44a15.32335587', 0, 1673, 112, 17),
(0, 'S63360a9fa4b039.99968007', 0, 1674, 113, 17),
(0, 'S63360a9fa50687.03166390', 0, 1675, 114, 17),
(0, 'S63360a9fa5d217.77583605', 0, 1676, 115, 17),
(0, 'S63360a9fa62be1.91153853', 0, 1677, 116, 17),
(0, 'S63360a9fa6bb34.12434808', 0, 1678, 117, 17),
(0, 'S63360a9fa718d2.17714315', 0, 1679, 132, 17),
(0, 'S63360a9fa7a260.39724376', 0, 1680, 36, 17),
(0, 'S63360a9fa7f8a7.04136045', 0, 1681, 106, 17),
(0, 'S63360a9fab3687.67762342', 0, 1682, 107, 17),
(0, 'S63360a9fabe9d0.21399421', 0, 1683, 108, 17),
(0, 'S63360a9fac4ec2.47825150', 0, 1684, 109, 17),
(0, 'S63360a9fac9c17.49458096', 0, 1685, 120, 17),
(0, 'S63360a9facfe38.48296054', 0, 1686, 165, 17),
(0, 'S63370fa0d6dd40.05204531', 0, 1705, 132, 30),
(0, 'S63370fa0d75fc8.73636242', 0, 1706, 107, 30),
(0, 'S63370fa0d7cae0.41979707', 0, 1707, 108, 30),
(0, 'S63370fa0d84db0.98262767', 0, 1708, 165, 30),
(0, 'S633c7823d0e020.01025547', 0, 1709, 43, 25),
(0, 'S633c7823d1ce41.92120199', 0, 1710, 44, 25),
(0, 'S633c7823d23270.17181426', 0, 1711, 167, 25),
(0, 'S633c7823d2c4c8.93802299', 0, 1712, 168, 25),
(0, 'S633c7823d32979.38167475', 0, 1713, 134, 25),
(0, 'S633c7823d3b559.25954742', 0, 1714, 154, 25),
(0, 'S633c7823d41a00.30369823', 0, 1715, 155, 25),
(0, 'S633c7823d488a5.29896613', 0, 1716, 1, 25),
(0, 'S633c7823d4e211.31739884', 0, 1717, 2, 25),
(0, 'S633c7823d55cb6.80028145', 0, 1718, 3, 25),
(0, 'S633c7823d5bfc6.30928431', 0, 1719, 5, 25),
(0, 'S633c7823d62888.56250632', 0, 1720, 68, 25),
(0, 'S633c7823d69386.94081992', 0, 1721, 82, 25),
(0, 'S633c7823d6ee61.42715178', 0, 1722, 83, 25),
(0, 'S633c7823d75323.36472955', 0, 1723, 151, 25),
(0, 'S633c7823d7a1d2.10658587', 0, 1724, 19, 25),
(0, 'S633c7823d7ee32.90236373', 0, 1725, 20, 25),
(0, 'S633c7823d84ed8.13316285', 0, 1726, 21, 25),
(0, 'S633c7823d8a1f0.81222818', 0, 1727, 22, 25),
(0, 'S633c7823d8f2e4.64648989', 0, 1728, 23, 25),
(0, 'S633c7823d95e58.05950313', 0, 1729, 45, 25),
(0, 'S633c7823d9afc3.69924002', 0, 1730, 46, 25),
(0, 'S633c7823da13f4.29001721', 0, 1731, 47, 25),
(0, 'S633c7823da6232.27038204', 0, 1732, 48, 25),
(0, 'S633c7823dab7d8.52249309', 0, 1733, 49, 25),
(0, 'S633c7823db2875.74950815', 0, 1734, 77, 25),
(0, 'S633c7823db7a20.10847596', 0, 1735, 78, 25),
(0, 'S633c7823dbd092.15737134', 0, 1736, 79, 25),
(0, 'S633c7823dc3382.68815098', 0, 1737, 80, 25),
(0, 'S633c7823dc7c95.44889186', 0, 1738, 81, 25),
(0, 'S633c7823dcca70.19163169', 0, 1739, 50, 25),
(0, 'S633c7823dda6a9.61787051', 0, 1740, 170, 25),
(0, 'S633c7823de4e67.51879152', 0, 1741, 92, 25),
(0, 'S633c7823dee0e2.34167145', 0, 1742, 93, 25),
(0, 'S633c7823dfe234.72893423', 0, 1743, 94, 25),
(0, 'S633c7823e0ac55.02275222', 0, 1744, 137, 25),
(0, 'S633c7823e11b75.62703482', 0, 1745, 41, 25),
(0, 'S633c7823e17f77.29550947', 0, 1746, 42, 25),
(0, 'S633c7823e1edd2.41385041', 0, 1747, 111, 25),
(0, 'S633c7823e27be0.37401421', 0, 1748, 112, 25),
(0, 'S633c7823e309d0.97460539', 0, 1749, 113, 25),
(0, 'S633c7823e37291.92490822', 0, 1750, 114, 25),
(0, 'S633c7823e3ef55.48806578', 0, 1751, 115, 25),
(0, 'S633c7823e45bb5.85021670', 0, 1752, 116, 25),
(0, 'S633c7823e4c002.77552077', 0, 1753, 117, 25),
(0, 'S633c7823e552a7.55220608', 0, 1754, 132, 25),
(0, 'S633c7823e5bc03.14571227', 0, 1755, 171, 25),
(0, 'S633c7823e661b2.12671718', 0, 1756, 35, 25),
(0, 'S633c7823e6ed27.98830785', 0, 1757, 36, 25),
(0, 'S633c7823e74f64.69793785', 0, 1758, 106, 25),
(0, 'S633c7823e7b1b4.05939350', 0, 1759, 107, 25),
(0, 'S633c7823e82c93.59326719', 0, 1760, 108, 25),
(0, 'S633c7823e88c80.33234704', 0, 1761, 109, 25),
(0, 'S633c7823e90106.06166405', 0, 1762, 110, 25),
(0, 'S633c7823e97535.17725292', 0, 1763, 118, 25),
(0, 'S633c7823e9ee22.64365244', 0, 1764, 120, 25),
(0, 'S633c7823ea5e79.21406346', 0, 1765, 142, 25),
(0, 'S633c7823eaf429.40583603', 0, 1766, 143, 25),
(0, 'S633c7823eb45c9.83394341', 0, 1767, 165, 25),
(0, 'S633c7823ec06d3.03472515', 0, 1768, 6, 25),
(0, 'S633c7823ec63f8.75407537', 0, 1769, 7, 25),
(0, 'S633c7823ecc228.90541779', 0, 1770, 8, 25),
(0, 'S633c7823ed2955.77058793', 0, 1771, 10, 25),
(0, 'S633c7823ed8c54.40446872', 0, 1772, 11, 25),
(0, 'S633c7823edf556.83609925', 0, 1773, 12, 25),
(0, 'S633c7823ee4fe0.83049117', 0, 1774, 13, 25),
(0, 'S633c7823eec038.70666333', 0, 1775, 14, 25),
(0, 'S633c7823ef1102.81453702', 0, 1776, 15, 25),
(0, 'S633c7823ef7133.71285515', 0, 1777, 16, 25),
(0, 'S633c7823efd767.15973969', 0, 1778, 17, 25),
(0, 'S633c7823f02f89.77821053', 0, 1779, 148, 25),
(0, 'S633c7823f0b6c8.00038318', 0, 1780, 149, 25),
(0, 'S633c7823f11850.51923553', 0, 1781, 150, 25),
(0, 'S633c7823f17654.20424068', 0, 1782, 24, 25),
(0, 'S633c7823f1d163.59279076', 0, 1783, 25, 25),
(0, 'S633c7823f23c70.47667511', 0, 1784, 26, 25),
(0, 'S633c7823f2a3a2.56971399', 0, 1785, 27, 25),
(0, 'S633c7823f2f368.34253800', 0, 1786, 28, 25),
(0, 'S633c7823f340e6.90273581', 0, 1787, 29, 25),
(0, 'S633c7823f3a856.11657804', 0, 1788, 30, 25),
(0, 'S633c7823f3f980.33709449', 0, 1789, 31, 25),
(0, 'S633c7824002a61.74348996', 0, 1790, 32, 25),
(0, 'S633c7824009208.19378558', 0, 1791, 33, 25),
(0, 'S633c782400e159.34665824', 0, 1792, 34, 25),
(0, 'S633c7824015ed9.85101146', 0, 1793, 39, 25),
(0, 'S633c782401b064.27932317', 0, 1794, 40, 25),
(0, 'S633c782401fff7.68139278', 0, 1795, 136, 25),
(0, 'S633c7824026fa6.16019919', 0, 1796, 140, 25),
(0, 'S633c782402c179.30226255', 0, 1797, 141, 25),
(0, 'S633c78240313d7.48134181', 0, 1798, 121, 25),
(0, 'S633c7824038515.67138906', 0, 1799, 122, 25),
(0, 'S633c782403da74.64389532', 0, 1800, 126, 25),
(0, 'S633c78240438c7.36444337', 0, 1801, 144, 25),
(0, 'S633c78240488c8.19744415', 0, 1802, 145, 25),
(0, 'S633c782404da04.49220769', 0, 1803, 146, 25),
(0, 'S633c7824054946.30380575', 0, 1804, 147, 25),
(0, 'S633c78240599f7.05406691', 0, 1805, 152, 25),
(0, 'S633c7824060630.37650005', 0, 1806, 153, 25),
(0, 'S633c78240673f5.00886130', 0, 1807, 156, 25),
(0, 'S633c782406c2d4.67395934', 0, 1808, 157, 25),
(0, 'S633c7824071c85.96796255', 0, 1809, 158, 25),
(0, 'S633c7824076b57.04708816', 0, 1810, 159, 25),
(0, 'S633c782407d339.39504590', 0, 1811, 160, 25),
(0, 'S633c7824084035.03222866', 0, 1812, 161, 25),
(0, 'S633c78240892f8.86192660', 0, 1813, 162, 25),
(0, 'S633c782408ea99.26654157', 0, 1814, 163, 25),
(0, 'S633c78240a07d6.83230001', 0, 1815, 169, 25),
(0, 'S633cb0a3df5163.62096261', 0, 2006, 166, 31),
(0, 'S633cb0a3dfc012.24256479', 0, 2007, 43, 31),
(0, 'S633cb0a3e04685.01310285', 0, 2008, 44, 31),
(0, 'S633cb0a3e0b745.01696049', 0, 2009, 167, 31),
(0, 'S633cb0a3e13648.55939337', 0, 2010, 168, 31),
(0, 'S633cb0a3e1a564.50239980', 0, 2011, 134, 31),
(0, 'S633cb0a3e22d76.10813800', 0, 2012, 154, 31),
(0, 'S633cb0a3e29592.81428480', 0, 2013, 155, 31),
(0, 'S633cb0a3e30945.07868522', 0, 2014, 1, 31),
(0, 'S633cb0a3e36db8.75543757', 0, 2015, 2, 31),
(0, 'S633cb0a3e3e2b2.04952253', 0, 2016, 3, 31),
(0, 'S633cb0a3e44ec6.73615803', 0, 2017, 5, 31),
(0, 'S633cb0a3e4c632.94188198', 0, 2018, 68, 31),
(0, 'S633cb0a3e545f2.55613312', 0, 2019, 82, 31),
(0, 'S633cb0a3e62dd6.52255137', 0, 2020, 83, 31),
(0, 'S633cb0a3e69e81.44517708', 0, 2021, 151, 31),
(0, 'S633cb0a3e716b8.05266622', 0, 2022, 19, 31),
(0, 'S633cb0a3e79286.80164256', 0, 2023, 20, 31),
(0, 'S633cb0a3e80b98.39719793', 0, 2024, 21, 31),
(0, 'S633cb0a3e89872.76084378', 0, 2025, 22, 31),
(0, 'S633cb0a3e92907.67099626', 0, 2026, 23, 31),
(0, 'S633cb0a3e99a51.51756778', 0, 2027, 45, 31),
(0, 'S633cb0a3ea2589.83426166', 0, 2028, 46, 31),
(0, 'S633cb0a3ea9284.66476749', 0, 2029, 47, 31),
(0, 'S633cb0a3eb38c6.67270996', 0, 2030, 48, 31),
(0, 'S633cb0a3ebb9f1.61846563', 0, 2031, 49, 31),
(0, 'S633cb0a3ec2011.00528611', 0, 2032, 77, 31),
(0, 'S633cb0a3ec88f8.79421711', 0, 2033, 78, 31),
(0, 'S633cb0a3ecff68.90696928', 0, 2034, 79, 31),
(0, 'S633cb0a3ed6f14.66366055', 0, 2035, 80, 31),
(0, 'S633cb0a3edee27.88493506', 0, 2036, 81, 31),
(0, 'S633cb0a3ee5692.23100190', 0, 2037, 37, 31),
(0, 'S633cb0a3eed3d0.71752978', 0, 2038, 38, 31),
(0, 'S633cb0a3ef3665.93445371', 0, 2039, 50, 31),
(0, 'S633cb0a3efa788.41095874', 0, 2040, 69, 31),
(0, 'S633cb0a3f00ff4.92343850', 0, 2041, 70, 31),
(0, 'S633cb0a3f0c362.26870074', 0, 2042, 84, 31),
(0, 'S633cb0a3f12bf0.67802234', 0, 2043, 133, 31),
(0, 'S633cb0a3f19a29.39960778', 0, 2044, 170, 31),
(0, 'S633cb0a3f1fec9.67964327', 0, 2045, 92, 31),
(0, 'S633cb0a3f26889.02661665', 0, 2046, 93, 31),
(0, 'S633cb0a3f31e28.50507873', 0, 2047, 94, 31),
(0, 'S633cb0a3f395c5.55229795', 0, 2048, 95, 31),
(0, 'S633cb0a3f3f646.10603737', 0, 2049, 96, 31),
(0, 'S633cb0a4003462.09792081', 0, 2050, 97, 31),
(0, 'S633cb0a400b1b6.17487404', 0, 2051, 137, 31),
(0, 'S633cb0a4011882.92986854', 0, 2052, 164, 31),
(0, 'S633cb0a40199e0.59054653', 0, 2053, 41, 31),
(0, 'S633cb0a40203f9.98095017', 0, 2054, 42, 31),
(0, 'S633cb0a4027760.60726783', 0, 2055, 111, 31),
(0, 'S633cb0a402e261.11233317', 0, 2056, 112, 31),
(0, 'S633cb0a4036099.14876787', 0, 2057, 113, 31),
(0, 'S633cb0a403cad9.29158562', 0, 2058, 114, 31),
(0, 'S633cb0a40440f0.97406155', 0, 2059, 115, 31),
(0, 'S633cb0a404a294.42908542', 0, 2060, 116, 31),
(0, 'S633cb0a4050953.52388995', 0, 2061, 117, 31),
(0, 'S633cb0a4057db9.49398105', 0, 2062, 132, 31),
(0, 'S633cb0a405e6e7.41982464', 0, 2063, 171, 31),
(0, 'S633cb0a406f706.45595035', 0, 2064, 35, 31),
(0, 'S633cb0a4075f11.56725907', 0, 2065, 36, 31),
(0, 'S633cb0a407e5d6.48530824', 0, 2066, 106, 31),
(0, 'S633cb0a40878f0.32519470', 0, 2067, 107, 31),
(0, 'S633cb0a408d246.44546710', 0, 2068, 108, 31),
(0, 'S633cb0a4092ad1.21876616', 0, 2069, 109, 31),
(0, 'S633cb0a4098dd0.69464697', 0, 2070, 110, 31),
(0, 'S633cb0a409e597.56169918', 0, 2071, 118, 31),
(0, 'S633cb0a40a4325.41323702', 0, 2072, 120, 31),
(0, 'S633cb0a40aa0c2.08482480', 0, 2073, 142, 31),
(0, 'S633cb0a40b1465.48505626', 0, 2074, 143, 31),
(0, 'S633cb0a40b9873.25547323', 0, 2075, 165, 31),
(0, 'S633cb0a40c00d2.18902232', 0, 2076, 6, 31),
(0, 'S633cb0a40c6d88.47939246', 0, 2077, 7, 31),
(0, 'S633cb0a40cc288.67127702', 0, 2078, 8, 31),
(0, 'S633cb0a40d5ad3.64996151', 0, 2079, 10, 31),
(0, 'S633cb0a40dae80.19427841', 0, 2080, 11, 31),
(0, 'S633cb0a40e1d73.49321993', 0, 2081, 12, 31),
(0, 'S633cb0a40e6dc7.95043845', 0, 2082, 13, 31),
(0, 'S633cb0a40ef558.12788669', 0, 2083, 14, 31),
(0, 'S633cb0a40f6504.37693131', 0, 2084, 15, 31),
(0, 'S633cb0a40fb954.28935653', 0, 2085, 16, 31),
(0, 'S633cb0a4101d87.77423065', 0, 2086, 17, 31),
(0, 'S633cb0a4107a64.86947259', 0, 2087, 148, 31),
(0, 'S633cb0a410d0d8.42119997', 0, 2088, 149, 31),
(0, 'S633cb0a4113967.82757092', 0, 2089, 150, 31),
(0, 'S633cb0a4119155.06530065', 0, 2090, 24, 31),
(0, 'S633cb0a41219f5.33015798', 0, 2091, 25, 31),
(0, 'S633cb0a4128b46.14569301', 0, 2092, 26, 31),
(0, 'S633cb0a4132641.70686068', 0, 2093, 27, 31),
(0, 'S633cb0a413faa1.39635113', 0, 2094, 28, 31),
(0, 'S633cb0a4145955.97445006', 0, 2095, 29, 31),
(0, 'S633cb0a414bb23.19192558', 0, 2096, 30, 31),
(0, 'S633cb0a4152fa0.28301753', 0, 2097, 31, 31),
(0, 'S633cb0a415e918.14859103', 0, 2098, 32, 31),
(0, 'S633cb0a4165ce6.62024696', 0, 2099, 33, 31),
(0, 'S633cb0a416c229.61832822', 0, 2100, 34, 31),
(0, 'S633cb0a4172a82.33776808', 0, 2101, 39, 31),
(0, 'S633cb0a4177b27.30408596', 0, 2102, 40, 31),
(0, 'S633cb0a417e4d1.11729616', 0, 2103, 136, 31),
(0, 'S633cb0a4184073.21294492', 0, 2104, 140, 31),
(0, 'S633cb0a418c346.89437499', 0, 2105, 141, 31),
(0, 'S633cb0a4194123.00486521', 0, 2106, 121, 31),
(0, 'S633cb0a419f7c1.37478675', 0, 2107, 122, 31),
(0, 'S633cb0a41a7d10.58102929', 0, 2108, 126, 31),
(0, 'S633cb0a41b1349.16955843', 0, 2109, 144, 31),
(0, 'S633cb0a41b6f40.28512009', 0, 2110, 145, 31),
(0, 'S633cb0a41bd7c6.62378878', 0, 2111, 146, 31),
(0, 'S633cb0a41c3a39.02037640', 0, 2112, 147, 31),
(0, 'S633cb0a41cae47.00977705', 0, 2113, 152, 31),
(0, 'S633cb0a41cff93.51262404', 0, 2114, 153, 31),
(0, 'S633cb0a41d5464.47339595', 0, 2115, 156, 31),
(0, 'S633cb0a41dcec3.15456477', 0, 2116, 157, 31),
(0, 'S633cb0a41e2fc0.34924776', 0, 2117, 158, 31),
(0, 'S633cb0a41eada9.64235589', 0, 2118, 159, 31),
(0, 'S633cb0a41f2765.47482702', 0, 2119, 160, 31),
(0, 'S633cb0a41fb4c4.42172264', 0, 2120, 161, 31),
(0, 'S633cb0a4203873.38959907', 0, 2121, 162, 31),
(0, 'S633cb0a420b6f1.99747632', 0, 2122, 163, 31),
(0, 'S633cb0a4211056.67706130', 0, 2123, 169, 31),
(0, 'S6341b8383b6a91.09142896', 0, 2186, 41, 19),
(0, 'S6341b8385b30f0.35073600', 0, 2187, 111, 19),
(0, 'S6341b838603ed6.67944549', 0, 2188, 112, 19),
(0, 'S6341b838615a39.43772955', 0, 2189, 113, 19),
(0, 'S6341b838634121.42340026', 0, 2190, 115, 19),
(0, 'S6341b83865e590.70591678', 0, 2191, 132, 19),
(0, 'S6341b8386733d3.23308587', 0, 2192, 165, 19),
(0, 'S6341b84badad63.57658327', 0, 2193, 41, 22),
(0, 'S6341b84bae0a86.03410700', 0, 2194, 111, 22),
(0, 'S6341b84baf6099.64977294', 0, 2195, 112, 22),
(0, 'S6341b84bafd000.19377146', 0, 2196, 113, 22),
(0, 'S6341b84bb06035.16620635', 0, 2197, 115, 22),
(0, 'S6341b84bb0d229.49762675', 0, 2198, 132, 22),
(0, 'S6341b84bb14ca4.68320020', 0, 2199, 165, 22),
(0, 'S6341b85b31b712.88051660', 0, 2200, 41, 23),
(0, 'S6341b85b32a7d4.86884141', 0, 2201, 111, 23),
(0, 'S6341b85b334349.49344111', 0, 2202, 112, 23),
(0, 'S6341b85b33b041.69174544', 0, 2203, 113, 23),
(0, 'S6341b85b34ae98.86338477', 0, 2204, 115, 23),
(0, 'S6341b85b354085.48201178', 0, 2205, 132, 23),
(0, 'S6341b85b35b4a1.48642099', 0, 2206, 165, 23),
(0, 'S6341b8676130c0.20865032', 0, 2207, 41, 24),
(0, 'S6341b867618e63.24764869', 0, 2208, 111, 24),
(0, 'S6341b86761fe75.15760377', 0, 2209, 112, 24),
(0, 'S6341b867625869.96306382', 0, 2210, 113, 24),
(0, 'S6341b86762b8e9.01380158', 0, 2211, 115, 24),
(0, 'S6341b867637408.84095892', 0, 2212, 132, 24),
(0, 'S6341b86763ecb4.69761599', 0, 2213, 165, 24),
(0, 'S64b5cc36becb08.85511161', 0, 2336, 1, 2),
(0, 'S64b5cc36bf4c01.63252713', 0, 2337, 2, 2),
(0, 'S64b5cc36bff474.20873927', 0, 2338, 3, 2),
(0, 'S64b5cc36c07882.50870169', 0, 2339, 5, 2),
(0, 'S64b5cc36c11ee5.10325412', 0, 2340, 77, 2),
(0, 'S64b5cc36c1ca21.23592754', 0, 2341, 78, 2),
(0, 'S64b5cc36c24941.58884957', 0, 2342, 79, 2),
(0, 'S64b5cc36c2f1a5.43424459', 0, 2343, 81, 2),
(0, 'S64b5cc36c36bf7.02160774', 0, 2344, 50, 2),
(0, 'S64b5cc36c40708.27309056', 0, 2345, 41, 2),
(0, 'S64b5cc36c49759.32406722', 0, 2346, 42, 2),
(0, 'S64b5cc36c500e3.89364637', 0, 2347, 111, 2),
(0, 'S64b5cc36c58cf5.24715732', 0, 2348, 112, 2),
(0, 'S64b5cc36c60973.12383674', 0, 2349, 113, 2),
(0, 'S64b5cc36c69430.03576909', 0, 2350, 115, 2),
(0, 'S64b5cc36c703b3.12234744', 0, 2351, 117, 2),
(0, 'S64b5cc36c7a4c3.30681543', 0, 2352, 132, 2),
(0, 'S64b5cc36c81004.63262556', 0, 2353, 171, 2),
(0, 'S64b5cc36c89d27.28459188', 0, 2354, 36, 2),
(0, 'S64b5cc36c90105.48890054', 0, 2355, 106, 2),
(0, 'S64b5cc36c98922.90538263', 0, 2356, 107, 2),
(0, 'S64b5cc36c9ed61.07800935', 0, 2357, 108, 2),
(0, 'S64b5cc36ca7392.18116094', 0, 2358, 109, 2),
(0, 'S64b5cc6759ced5.53041735', 0, 2359, 1, 4),
(0, 'S64b5cc675bba77.09978173', 0, 2360, 2, 4),
(0, 'S64b5cc675da965.72600781', 0, 2361, 3, 4),
(0, 'S64b5cc675f2452.28592362', 0, 2362, 5, 4),
(0, 'S64b5cc67602bf0.82634830', 0, 2363, 77, 4),
(0, 'S64b5cc67612a12.60727586', 0, 2364, 78, 4),
(0, 'S64b5cc67623408.93081990', 0, 2365, 79, 4),
(0, 'S64b5cc67653c75.22306593', 0, 2366, 81, 4),
(0, 'S64b5cc676644b7.52347589', 0, 2367, 50, 4),
(0, 'S64b5cc67673533.80250321', 0, 2368, 41, 4),
(0, 'S64b5cc67681d49.22652613', 0, 2369, 42, 4),
(0, 'S64b5cc6768ef10.65869333', 0, 2370, 111, 4),
(0, 'S64b5cc6769ef38.19158999', 0, 2371, 112, 4),
(0, 'S64b5cc676b0411.01686793', 0, 2372, 113, 4),
(0, 'S64b5cc676c0bd9.51492297', 0, 2373, 115, 4),
(0, 'S64b5cc676dfd67.95715432', 0, 2374, 117, 4),
(0, 'S64b5cc676fd285.95058825', 0, 2375, 132, 4),
(0, 'S64b5cc6771a120.14647030', 0, 2376, 171, 4),
(0, 'S64b5cc67738239.03556007', 0, 2377, 35, 4),
(0, 'S64b5cc67755be2.59458188', 0, 2378, 36, 4),
(0, 'S64b5cc6777a590.92890609', 0, 2379, 106, 4),
(0, 'S64b5cc67797c20.48060985', 0, 2380, 107, 4),
(0, 'S64b5cc677b5b88.03925575', 0, 2381, 108, 4),
(0, 'S64b5cc677cfa66.49748637', 0, 2382, 109, 4),
(0, 'S64b5cc9bb2f322.00392471', 0, 2383, 1, 3),
(0, 'S64b5cc9bb3ac41.77532781', 0, 2384, 2, 3),
(0, 'S64b5cc9bb45da3.45481288', 0, 2385, 3, 3),
(0, 'S64b5cc9bb51be5.69451844', 0, 2386, 5, 3),
(0, 'S64b5cc9bb597a1.00744003', 0, 2387, 77, 3),
(0, 'S64b5cc9bb640c9.23436995', 0, 2388, 78, 3),
(0, 'S64b5cc9bb6b871.33114770', 0, 2389, 79, 3),
(0, 'S64b5cc9bb75a54.14147322', 0, 2390, 81, 3),
(0, 'S64b5cc9bb80202.52913120', 0, 2391, 41, 3),
(0, 'S64b5cc9bb86ea2.18019392', 0, 2392, 42, 3),
(0, 'S64b5cc9bb90428.83307923', 0, 2393, 111, 3),
(0, 'S64b5cc9bb96b16.61395570', 0, 2394, 112, 3),
(0, 'S64b5cc9bb9f565.53573465', 0, 2395, 113, 3),
(0, 'S64b5cc9bba5b96.72918593', 0, 2396, 115, 3),
(0, 'S64b5cc9bbb27f4.93046908', 0, 2397, 117, 3),
(0, 'S64b5cc9bbb9d50.87003319', 0, 2398, 132, 3),
(0, 'S64b5cc9bbc5210.20726621', 0, 2399, 36, 3),
(0, 'S64b5cc9bbcfd87.97142630', 0, 2400, 106, 3),
(0, 'S64b5cc9bbd6f72.38643376', 0, 2401, 107, 3),
(0, 'S64b5cc9bbe01f6.93764443', 0, 2402, 108, 3),
(0, 'S64b5cc9bbe7685.78789987', 0, 2403, 109, 3),
(0, 'S64b5cc9bbf0f02.10779622', 0, 2404, 118, 3),
(0, 'S64b8557d103e50.34670089', 0, 2405, 134, 1),
(0, 'S64b8557d12d654.24145098', 0, 2406, 154, 1),
(0, 'S64b8557d14cbe4.46175628', 0, 2407, 155, 1),
(0, 'S64b8557d16baa2.88079325', 0, 2408, 1, 1),
(0, 'S64b8557d1962e9.01679049', 0, 2409, 2, 1),
(0, 'S64b8557d1b5eb6.01680432', 0, 2410, 3, 1),
(0, 'S64b8557d1d6023.51970208', 0, 2411, 5, 1),
(0, 'S64b8557d1f5675.24344081', 0, 2412, 43, 1),
(0, 'S64b8557d20c2e7.84251974', 0, 2413, 44, 1),
(0, 'S64b8557d221b21.52080444', 0, 2414, 172, 1),
(0, 'S64b8557d23f6d1.62861949', 0, 2415, 173, 1),
(0, 'S64b8557d25a1a8.14159443', 0, 2416, 68, 1),
(0, 'S64b8557d2763e6.44773968', 0, 2417, 82, 1),
(0, 'S64b8557d29a204.49142295', 0, 2418, 83, 1),
(0, 'S64b8557d2bb665.47566331', 0, 2419, 151, 1),
(0, 'S64b8557d2d3c09.90669336', 0, 2420, 19, 1),
(0, 'S64b8557d2f5645.33027546', 0, 2421, 20, 1),
(0, 'S64b8557d30d923.05612891', 0, 2422, 21, 1),
(0, 'S64b8557d325494.44911870', 0, 2423, 22, 1),
(0, 'S64b8557d34c6f7.28874959', 0, 2424, 23, 1),
(0, 'S64b8557d36bc72.57469136', 0, 2425, 45, 1),
(0, 'S64b8557d38b7f1.84366482', 0, 2426, 46, 1),
(0, 'S64b8557d3b3117.61475958', 0, 2427, 47, 1),
(0, 'S64b8557d3d4923.84772645', 0, 2428, 48, 1),
(0, 'S64b8557d3f4906.71183768', 0, 2429, 49, 1),
(0, 'S64b8557d41e725.06875675', 0, 2430, 77, 1),
(0, 'S64b8557d43eb50.37139099', 0, 2431, 78, 1),
(0, 'S64b8557d460b03.39972059', 0, 2432, 79, 1),
(0, 'S64b8557d489351.17135696', 0, 2433, 80, 1),
(0, 'S64b8557d4a8988.62474872', 0, 2434, 81, 1),
(0, 'S64b8557d4be921.14898507', 0, 2435, 37, 1),
(0, 'S64b8557d4def46.66991737', 0, 2436, 38, 1),
(0, 'S64b8557d4f71b9.89517034', 0, 2437, 50, 1),
(0, 'S64b8557d50f610.47695954', 0, 2438, 69, 1),
(0, 'S64b8557d5301e8.26734122', 0, 2439, 70, 1),
(0, 'S64b8557d547216.11340675', 0, 2440, 84, 1),
(0, 'S64b8557d55e107.02972427', 0, 2441, 133, 1),
(0, 'S64b8557d5803e8.50490372', 0, 2442, 170, 1),
(0, 'S64b8557d5972d2.81829450', 0, 2443, 92, 1),
(0, 'S64b8557d5ae4b1.57938522', 0, 2444, 93, 1),
(0, 'S64b8557d5cf074.25522795', 0, 2445, 94, 1),
(0, 'S64b8557d5e81b7.49584103', 0, 2446, 95, 1),
(0, 'S64b8557d6024d0.15202476', 0, 2447, 96, 1),
(0, 'S64b8557d629bb6.79845187', 0, 2448, 97, 1),
(0, 'S64b8557d64b7c2.14721842', 0, 2449, 137, 1),
(0, 'S64b8557d66b9a8.48075420', 0, 2450, 164, 1),
(0, 'S64b8557d69a318.15972119', 0, 2451, 41, 1),
(0, 'S64b8557d6c15a5.26819271', 0, 2452, 42, 1),
(0, 'S64b8557d6e1f09.25016157', 0, 2453, 111, 1),
(0, 'S64b8557d710914.09851141', 0, 2454, 112, 1),
(0, 'S64b8557d733107.09663937', 0, 2455, 113, 1),
(0, 'S64b8557d754383.04925499', 0, 2456, 114, 1),
(0, 'S64b8557d7749a8.01516382', 0, 2457, 115, 1),
(0, 'S64b8557d78f218.13120168', 0, 2458, 116, 1),
(0, 'S64b8557d7a9c55.39477983', 0, 2459, 117, 1),
(0, 'S64b8557d7d5be6.19117127', 0, 2460, 132, 1),
(0, 'S64b8557d7f9c00.05550814', 0, 2461, 171, 1),
(0, 'S64b8557d81bec0.10497203', 0, 2462, 35, 1),
(0, 'S64b8557d844238.69551319', 0, 2463, 36, 1),
(0, 'S64b8557d8636d8.06056732', 0, 2464, 106, 1),
(0, 'S64b8557d885fc2.41241945', 0, 2465, 107, 1),
(0, 'S64b8557d8a9241.23835644', 0, 2466, 108, 1),
(0, 'S64b8557d8be473.58119174', 0, 2467, 109, 1),
(0, 'S64b8557d8d0e23.77846398', 0, 2468, 110, 1),
(0, 'S64b8557d8ebfc5.08654503', 0, 2469, 118, 1),
(0, 'S64b8557d900530.14086198', 0, 2470, 120, 1),
(0, 'S64b8557d91ba96.46798638', 0, 2471, 142, 1),
(0, 'S64b8557d92e8c3.00287647', 0, 2472, 143, 1),
(0, 'S64b8557d941e29.04825672', 0, 2473, 165, 1),
(0, 'S64b8557d95f5c5.23051678', 0, 2474, 178, 1),
(0, 'S64b8557d9743e7.66170161', 0, 2475, 6, 1),
(0, 'S64b8557d987ab5.01355576', 0, 2476, 7, 1),
(0, 'S64b8557d9a39f0.97735549', 0, 2477, 8, 1),
(0, 'S64b8557d9b6ba7.19537669', 0, 2478, 10, 1),
(0, 'S64b8557d9cb5f1.66896835', 0, 2479, 11, 1),
(0, 'S64b8557d9f1298.44805309', 0, 2480, 12, 1),
(0, 'S64b8557da118d8.22230953', 0, 2481, 13, 1),
(0, 'S64b8557da32d87.53233259', 0, 2482, 14, 1),
(0, 'S64b8557da5f0c4.26317429', 0, 2483, 15, 1),
(0, 'S64b8557da84519.37613776', 0, 2484, 16, 1),
(0, 'S64b8557daa5625.27975139', 0, 2485, 17, 1),
(0, 'S64b8557dad01a0.00479761', 0, 2486, 174, 1),
(0, 'S64b8557daf2c36.19011765', 0, 2487, 175, 1),
(0, 'S64b8557db14d68.91031780', 0, 2488, 176, 1),
(0, 'S64b8557db3fcd7.62775188', 0, 2489, 177, 1),
(0, 'S64b8557db60434.78572747', 0, 2490, 24, 1),
(0, 'S64b8557db81328.25140892', 0, 2491, 25, 1),
(0, 'S64b8557dba9bc7.87896285', 0, 2492, 26, 1),
(0, 'S64b8557dbc8ae1.42441173', 0, 2493, 27, 1),
(0, 'S64b8557dbe6ca7.23431445', 0, 2494, 28, 1),
(0, 'S64b8557dc0e223.48854499', 0, 2495, 29, 1),
(0, 'S64b8557dc2bdb7.90649940', 0, 2496, 30, 1),
(0, 'S64b8557dc493b0.31169971', 0, 2497, 31, 1),
(0, 'S64b8557dc705e8.83270199', 0, 2498, 32, 1),
(0, 'S64b8557dc8d927.17706136', 0, 2499, 33, 1),
(0, 'S64b8557dcb50d4.37729580', 0, 2500, 34, 1),
(0, 'S64b8557dcd41a2.89490247', 0, 2501, 39, 1),
(0, 'S64b8557dcf50e1.88845675', 0, 2502, 40, 1),
(0, 'S64b8557dd1e016.24748063', 0, 2503, 136, 1),
(0, 'S64b8557dd3e848.15978284', 0, 2504, 140, 1),
(0, 'S64b8557dd62561.85410719', 0, 2505, 141, 1),
(0, 'S64b8557dd8f641.34184707', 0, 2506, 121, 1),
(0, 'S64b8557ddb0556.24277509', 0, 2507, 122, 1),
(0, 'S64b8557ddd32b5.38092097', 0, 2508, 126, 1),
(0, 'S64b8557ddfd4f0.19080834', 0, 2509, 144, 1);
INSERT INTO `usuario_modulo` (`id_server`, `unique_id`, `id_sucursal`, `id_mod_user`, `id_modulo`, `id_usuario`) VALUES
(0, 'S64b8557de1f407.22643719', 0, 2510, 145, 1),
(0, 'S64b8557de3e631.54507935', 0, 2511, 146, 1),
(0, 'S64b8557de5a652.58173741', 0, 2512, 147, 1),
(0, 'S64b8557de6dbc0.64806185', 0, 2513, 152, 1),
(0, 'S64b8557de7d871.98937190', 0, 2514, 153, 1),
(0, 'S64b8557de95e87.54370458', 0, 2515, 156, 1),
(0, 'S64b8557dea8965.88954126', 0, 2516, 157, 1),
(0, 'S64b8557deb9ed9.22983719', 0, 2517, 158, 1),
(0, 'S64b8557ded1cd4.03595433', 0, 2518, 159, 1),
(0, 'S64b8557dee3af3.80363955', 0, 2519, 160, 1),
(0, 'S64b8557def3280.62006374', 0, 2520, 161, 1),
(0, 'S64b8557df09992.75780609', 0, 2521, 162, 1),
(0, 'S64b8557df203a0.09311344', 0, 2522, 163, 1),
(0, 'S64b856170386a1.20927554', 0, 2523, 134, 5),
(0, 'S64b8561705be63.89003246', 0, 2524, 154, 5),
(0, 'S64b85617074707.38744885', 0, 2525, 155, 5),
(0, 'S64b8561708e571.15500999', 0, 2526, 1, 5),
(0, 'S64b856170b17c0.67102672', 0, 2527, 2, 5),
(0, 'S64b856170c9408.42161788', 0, 2528, 3, 5),
(0, 'S64b856170e2e51.33775405', 0, 2529, 5, 5),
(0, 'S64b85617106548.84964574', 0, 2530, 43, 5),
(0, 'S64b8561711d459.41058126', 0, 2531, 44, 5),
(0, 'S64b856171347a2.10759601', 0, 2532, 172, 5),
(0, 'S64b85617155f08.71508733', 0, 2533, 173, 5),
(0, 'S64b8561716d062.19315512', 0, 2534, 68, 5),
(0, 'S64b85617184ab4.64879869', 0, 2535, 82, 5),
(0, 'S64b856171a7688.51933081', 0, 2536, 83, 5),
(0, 'S64b856171bf1f0.18940866', 0, 2537, 151, 5),
(0, 'S64b856171d7979.72164609', 0, 2538, 19, 5),
(0, 'S64b856171f7ac0.11702127', 0, 2539, 20, 5),
(0, 'S64b85617210793.59701360', 0, 2540, 21, 5),
(0, 'S64b8561722cd79.59326044', 0, 2541, 22, 5),
(0, 'S64b856172502a2.03388163', 0, 2542, 23, 5),
(0, 'S64b856172687a2.17463457', 0, 2543, 45, 5),
(0, 'S64b85617280b01.17702689', 0, 2544, 46, 5),
(0, 'S64b856172a2402.57484701', 0, 2545, 47, 5),
(0, 'S64b856172bbf77.05187765', 0, 2546, 48, 5),
(0, 'S64b856172d4787.76011270', 0, 2547, 49, 5),
(0, 'S64b856172f72e9.29563776', 0, 2548, 77, 5),
(0, 'S64b8561730fa63.90801903', 0, 2549, 78, 5),
(0, 'S64b85617327bb8.62111772', 0, 2550, 79, 5),
(0, 'S64b856173490e0.61756013', 0, 2551, 80, 5),
(0, 'S64b85617363a61.59609326', 0, 2552, 81, 5),
(0, 'S64b8561737ccc1.97792190', 0, 2553, 37, 5),
(0, 'S64b8561739e2f6.87711350', 0, 2554, 38, 5),
(0, 'S64b856173b61d4.33084588', 0, 2555, 50, 5),
(0, 'S64b856173d01e4.55265795', 0, 2556, 69, 5),
(0, 'S64b856173f48f5.58577726', 0, 2557, 70, 5),
(0, 'S64b8561740df16.03546571', 0, 2558, 84, 5),
(0, 'S64b85617427ba9.22339807', 0, 2559, 133, 5),
(0, 'S64b85617449307.24531189', 0, 2560, 170, 5),
(0, 'S64b85617460be7.86288663', 0, 2561, 92, 5),
(0, 'S64b85617477d11.38365092', 0, 2562, 93, 5),
(0, 'S64b856174999f1.39438258', 0, 2563, 94, 5),
(0, 'S64b856174b2ae5.18813783', 0, 2564, 95, 5),
(0, 'S64b856174ceda0.78408864', 0, 2565, 96, 5),
(0, 'S64b856174f47e4.16924102', 0, 2566, 97, 5),
(0, 'S64b8561750cdc3.37765203', 0, 2567, 137, 5),
(0, 'S64b856175276a0.32957633', 0, 2568, 164, 5),
(0, 'S64b8561754c379.50528814', 0, 2569, 41, 5),
(0, 'S64b85617568244.57091837', 0, 2570, 42, 5),
(0, 'S64b85617584a09.68287413', 0, 2571, 111, 5),
(0, 'S64b856175a46f5.38290663', 0, 2572, 112, 5),
(0, 'S64b856175bf0a6.60128862', 0, 2573, 113, 5),
(0, 'S64b856175d98c3.69672540', 0, 2574, 114, 5),
(0, 'S64b856175ffcc6.68032832', 0, 2575, 115, 5),
(0, 'S64b856176294f8.81253669', 0, 2576, 116, 5),
(0, 'S64b85617649fa1.58613467', 0, 2577, 117, 5),
(0, 'S64b8561766c333.63528782', 0, 2578, 132, 5),
(0, 'S64b8561768e375.25953170', 0, 2579, 171, 5),
(0, 'S64b856176b1677.21093274', 0, 2580, 35, 5),
(0, 'S64b856176ddb24.88396568', 0, 2581, 36, 5),
(0, 'S64b85617700a96.83339711', 0, 2582, 106, 5),
(0, 'S64b85617717131.80292422', 0, 2583, 107, 5),
(0, 'S64b85617734981.92130294', 0, 2584, 108, 5),
(0, 'S64b85617748a95.55109746', 0, 2585, 109, 5),
(0, 'S64b8561775bd31.27510803', 0, 2586, 110, 5),
(0, 'S64b856177793b9.09064668', 0, 2587, 118, 5),
(0, 'S64b8561778f2b5.34553493', 0, 2588, 120, 5),
(0, 'S64b856177a3f19.10598648', 0, 2589, 142, 5),
(0, 'S64b856177c24c0.28173763', 0, 2590, 143, 5),
(0, 'S64b856177d7592.48517758', 0, 2591, 165, 5),
(0, 'S64b856177eb701.61028463', 0, 2592, 178, 5),
(0, 'S64b85617809c59.48509600', 0, 2593, 6, 5),
(0, 'S64b8561781e387.80546693', 0, 2594, 7, 5),
(0, 'S64b85617831078.58370337', 0, 2595, 8, 5),
(0, 'S64b8561784ee47.80810520', 0, 2596, 10, 5),
(0, 'S64b85617862c29.69372733', 0, 2597, 11, 5),
(0, 'S64b85617878192.76638955', 0, 2598, 12, 5),
(0, 'S64b856178a0b23.89281340', 0, 2599, 13, 5),
(0, 'S64b856178c22f4.15938409', 0, 2600, 14, 5),
(0, 'S64b856178e4767.48042195', 0, 2601, 15, 5),
(0, 'S64b85617911bf6.19465865', 0, 2602, 16, 5),
(0, 'S64b85617934a77.99881802', 0, 2603, 17, 5),
(0, 'S64b85617954ea3.51990422', 0, 2604, 174, 5),
(0, 'S64b8561797f462.13365027', 0, 2605, 175, 5),
(0, 'S64b856179a07d1.05888926', 0, 2606, 176, 5),
(0, 'S64b856179c01a6.91697168', 0, 2607, 177, 5),
(0, 'S64b856179e3314.91983311', 0, 2608, 24, 5),
(0, 'S64b856179fb5f6.32118943', 0, 2609, 25, 5),
(0, 'S64b85617a13187.72430998', 0, 2610, 26, 5),
(0, 'S64b85617a3c237.22927929', 0, 2611, 27, 5),
(0, 'S64b85617a601d3.46299315', 0, 2612, 28, 5),
(0, 'S64b85617a87964.64722966', 0, 2613, 29, 5),
(0, 'S64b85617ab5c44.37531135', 0, 2614, 30, 5),
(0, 'S64b85617ad72b7.50190375', 0, 2615, 31, 5),
(0, 'S64b85617af90e0.92859122', 0, 2616, 32, 5),
(0, 'S64b85617b23b01.54719279', 0, 2617, 33, 5),
(0, 'S64b85617b472f2.16924831', 0, 2618, 34, 5),
(0, 'S64b85617b67384.89425677', 0, 2619, 39, 5),
(0, 'S64b85617b8b387.32985092', 0, 2620, 40, 5),
(0, 'S64b85617ba3aa7.56037689', 0, 2621, 136, 5),
(0, 'S64b85617bbb2e4.72773700', 0, 2622, 140, 5),
(0, 'S64b85617bde208.72645102', 0, 2623, 141, 5),
(0, 'S64b85617bf6130.42389983', 0, 2624, 121, 5),
(0, 'S64b85617c0de57.89451427', 0, 2625, 122, 5),
(0, 'S64b85617c379f8.15062308', 0, 2626, 126, 5),
(0, 'S64b85617c59477.92957626', 0, 2627, 144, 5),
(0, 'S64b85617c7cdd5.76348675', 0, 2628, 145, 5),
(0, 'S64b85617cb11f3.87472335', 0, 2629, 146, 5),
(0, 'S64b85617cd0df6.52353678', 0, 2630, 147, 5),
(0, 'S64b85617ce7cc2.49820181', 0, 2631, 152, 5),
(0, 'S64b85617d084f3.03370247', 0, 2632, 153, 5),
(0, 'S64b85617d1d902.27607577', 0, 2633, 156, 5),
(0, 'S64b85617d342c4.12569712', 0, 2634, 157, 5),
(0, 'S64b85617d60473.21004617', 0, 2635, 158, 5),
(0, 'S64b85617d82bb4.86773619', 0, 2636, 159, 5),
(0, 'S64b85617daa983.52795420', 0, 2637, 160, 5),
(0, 'S64b85617dd4f78.76864445', 0, 2638, 161, 5),
(0, 'S64b85617de99c9.73303490', 0, 2639, 162, 5),
(0, 'S64b85617dfad73.58299467', 0, 2640, 163, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo`
--

CREATE TABLE `vehiculo` (
  `id` int(11) NOT NULL,
  `id_marca` int(11) NOT NULL,
  `id_modelo` int(11) NOT NULL,
  `tipo_vehiculo` int(11) NOT NULL,
  `tipo_combustible` int(11) NOT NULL,
  `placa` char(8) NOT NULL,
  `vin` varchar(25) NOT NULL,
  `anio` smallint(4) NOT NULL,
  `numero_unidad` int(11) NOT NULL,
  `llantas` smallint(2) NOT NULL,
  `ejes` smallint(2) NOT NULL,
  `color` varchar(20) NOT NULL,
  `mes_vence_tarjeta` smallint(2) NOT NULL,
  `capacidad` varchar(150) NOT NULL,
  `imagen` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_cuota`
--

CREATE TABLE `venta_cuota` (
  `id_venta_cuota` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `prima` decimal(10,2) NOT NULL,
  `abono` decimal(10,2) NOT NULL,
  `saldo` decimal(10,2) NOT NULL,
  `valorcuota` decimal(10,2) NOT NULL,
  `numerocuotas` smallint(3) NOT NULL,
  `cuotaspagadas` smallint(4) NOT NULL,
  `diavence` smallint(2) NOT NULL,
  `anulada` tinyint(1) NOT NULL DEFAULT 0,
  `porcent_total` decimal(6,2) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `comision_venta` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `voucher`
--

CREATE TABLE `voucher` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_voucher` int(11) NOT NULL,
  `forma_pago` varchar(50) NOT NULL,
  `referencia_pago` varchar(50) NOT NULL,
  `numero_doc` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `monto` float NOT NULL,
  `responsable` varchar(200) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `id_movimiento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `voucher_mov`
--

CREATE TABLE `voucher_mov` (
  `id_server` int(11) NOT NULL,
  `unique_id` text NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_mv` int(11) NOT NULL,
  `id_movimiento` int(11) NOT NULL,
  `id_cuenta_pagar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `abono_credito`
--
ALTER TABLE `abono_credito`
  ADD PRIMARY KEY (`id_abono_credito`);

--
-- Indices de la tabla `abono_historial`
--
ALTER TABLE `abono_historial`
  ADD PRIMARY KEY (`id_abono_historial`);

--
-- Indices de la tabla `access_conf`
--
ALTER TABLE `access_conf`
  ADD PRIMARY KEY (`id_conf`);

--
-- Indices de la tabla `altclitocli`
--
ALTER TABLE `altclitocli`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `apertura_caja`
--
ALTER TABLE `apertura_caja`
  ADD PRIMARY KEY (`id_apertura`);

--
-- Indices de la tabla `arqueo_conceptos`
--
ALTER TABLE `arqueo_conceptos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `arqueo_corte`
--
ALTER TABLE `arqueo_corte`
  ADD PRIMARY KEY (`id_arqueo`),
  ADD KEY `id_apertura` (`id_apertura`);

--
-- Indices de la tabla `banco`
--
ALTER TABLE `banco`
  ADD PRIMARY KEY (`id_banco`);

--
-- Indices de la tabla `bomba`
--
ALTER TABLE `bomba`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bomba_manguera`
--
ALTER TABLE `bomba_manguera`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`id_caja`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `categoria_proveedor`
--
ALTER TABLE `categoria_proveedor`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `cat_doc_MH`
--
ALTER TABLE `cat_doc_MH`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `cheque`
--
ALTER TABLE `cheque`
  ADD PRIMARY KEY (`id_cheque`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `cliente_dif`
--
ALTER TABLE `cliente_dif`
  ADD PRIMARY KEY (`id_dif`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id_compra`);

--
-- Indices de la tabla `compra2`
--
ALTER TABLE `compra2`
  ADD PRIMARY KEY (`id_compra`);

--
-- Indices de la tabla `compra_imp_combust`
--
ALTER TABLE `compra_imp_combust`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id_configuracion`);

--
-- Indices de la tabla `config_dir`
--
ALTER TABLE `config_dir`
  ADD PRIMARY KEY (`id_config_dir`);

--
-- Indices de la tabla `config_pos`
--
ALTER TABLE `config_pos`
  ADD PRIMARY KEY (`id_config_pos`);

--
-- Indices de la tabla `consignacion`
--
ALTER TABLE `consignacion`
  ADD PRIMARY KEY (`id_consignacion`);

--
-- Indices de la tabla `consignacion_detalle`
--
ALTER TABLE `consignacion_detalle`
  ADD PRIMARY KEY (`id_consignacion_detalle`),
  ADD KEY `id_consignacion` (`id_consignacion`);

--
-- Indices de la tabla `consumo_mes_dif`
--
ALTER TABLE `consumo_mes_dif`
  ADD PRIMARY KEY (`id_consumo_dif`);

--
-- Indices de la tabla `controlcaja`
--
ALTER TABLE `controlcaja`
  ADD PRIMARY KEY (`id_corte`);

--
-- Indices de la tabla `correlativo`
--
ALTER TABLE `correlativo`
  ADD PRIMARY KEY (`id_numdoc`);

--
-- Indices de la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD PRIMARY KEY (`id_cotizacion`);

--
-- Indices de la tabla `cotizacion_detalle`
--
ALTER TABLE `cotizacion_detalle`
  ADD PRIMARY KEY (`id_detalle`);

--
-- Indices de la tabla `credito`
--
ALTER TABLE `credito`
  ADD PRIMARY KEY (`id_credito`);

--
-- Indices de la tabla `cuentas_por_pagar_abonos`
--
ALTER TABLE `cuentas_por_pagar_abonos`
  ADD PRIMARY KEY (`id_abono`);

--
-- Indices de la tabla `cuenta_banco`
--
ALTER TABLE `cuenta_banco`
  ADD PRIMARY KEY (`id_cuenta`);

--
-- Indices de la tabla `cuenta_pagar`
--
ALTER TABLE `cuenta_pagar`
  ADD PRIMARY KEY (`id_cuenta_pagar`);

--
-- Indices de la tabla `cuota`
--
ALTER TABLE `cuota`
  ADD PRIMARY KEY (`id_cuota`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`id_departamento`);

--
-- Indices de la tabla `departamentoMH`
--
ALTER TABLE `departamentoMH`
  ADD PRIMARY KEY (`id_departamento`);

--
-- Indices de la tabla `detalle_apertura`
--
ALTER TABLE `detalle_apertura`
  ADD PRIMARY KEY (`id_detalle`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id_det_compra`);

--
-- Indices de la tabla `detalle_compra2`
--
ALTER TABLE `detalle_compra2`
  ADD PRIMARY KEY (`id_det_compra`);

--
-- Indices de la tabla `detalle_voucher`
--
ALTER TABLE `detalle_voucher`
  ADD PRIMARY KEY (`id_detalle`);

--
-- Indices de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD PRIMARY KEY (`id_dev`);

--
-- Indices de la tabla `devoluciones_corte`
--
ALTER TABLE `devoluciones_corte`
  ADD PRIMARY KEY (`id_dev`);

--
-- Indices de la tabla `devoluciones_det`
--
ALTER TABLE `devoluciones_det`
  ADD PRIMARY KEY (`id_dev_det`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id_empleado`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`idempresa`);

--
-- Indices de la tabla `estante`
--
ALTER TABLE `estante`
  ADD PRIMARY KEY (`id_estante`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id_factura`);

--
-- Indices de la tabla `factura_detalle`
--
ALTER TABLE `factura_detalle`
  ADD PRIMARY KEY (`id_factura_detalle`);

--
-- Indices de la tabla `factura_pago`
--
ALTER TABLE `factura_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `fact_imp_combust`
--
ALTER TABLE `fact_imp_combust`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `giroMH`
--
ALTER TABLE `giroMH`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `impuestosMH`
--
ALTER TABLE `impuestosMH`
  ADD PRIMARY KEY (`id`),
  ADD KEY `codigo` (`codigo`);

--
-- Indices de la tabla `impuestos_gasolina`
--
ALTER TABLE `impuestos_gasolina`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `laboratorio`
--
ALTER TABLE `laboratorio`
  ADD PRIMARY KEY (`id_laboratorio`);

--
-- Indices de la tabla `lectura_bomba`
--
ALTER TABLE `lectura_bomba`
  ADD PRIMARY KEY (`id_lectura`);

--
-- Indices de la tabla `lectura_detalle_bomba`
--
ALTER TABLE `lectura_detalle_bomba`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_bomba` (`id_bomba`),
  ADD KEY `id_tipo_combustible` (`id_tipo_combustible`),
  ADD KEY `id_lectura` (`id_lectura`);

--
-- Indices de la tabla `lectura_lub_dia`
--
ALTER TABLE `lectura_lub_dia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_bomba` (`id_producto`),
  ADD KEY `id_apertura` (`id_apertura`);

--
-- Indices de la tabla `log_cambio_local`
--
ALTER TABLE `log_cambio_local`
  ADD PRIMARY KEY (`id_log_cambio`);

--
-- Indices de la tabla `log_detalle_cambio_local`
--
ALTER TABLE `log_detalle_cambio_local`
  ADD PRIMARY KEY (`id_detalle_cambio`);

--
-- Indices de la tabla `log_update_local`
--
ALTER TABLE `log_update_local`
  ADD PRIMARY KEY (`id_log_cambio`);

--
-- Indices de la tabla `lote`
--
ALTER TABLE `lote`
  ADD PRIMARY KEY (`id_lote`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id_marca`);

--
-- Indices de la tabla `margen_cols_form`
--
ALTER TABLE `margen_cols_form`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indices de la tabla `modelo`
--
ALTER TABLE `modelo`
  ADD PRIMARY KEY (`id_modelo`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`id_modulo`);

--
-- Indices de la tabla `movimiento_caja_tipo`
--
ALTER TABLE `movimiento_caja_tipo`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `movimiento_producto`
--
ALTER TABLE `movimiento_producto`
  ADD PRIMARY KEY (`id_movimiento`);

--
-- Indices de la tabla `movimiento_producto_detalle`
--
ALTER TABLE `movimiento_producto_detalle`
  ADD PRIMARY KEY (`id_detalle`);

--
-- Indices de la tabla `movimiento_producto_pendiente`
--
ALTER TABLE `movimiento_producto_pendiente`
  ADD PRIMARY KEY (`id_detalle`);

--
-- Indices de la tabla `movimiento_stock_ubicacion`
--
ALTER TABLE `movimiento_stock_ubicacion`
  ADD PRIMARY KEY (`id_movimiento`);

--
-- Indices de la tabla `mov_caja`
--
ALTER TABLE `mov_caja`
  ADD PRIMARY KEY (`id_movimiento`);

--
-- Indices de la tabla `mov_cta_banco`
--
ALTER TABLE `mov_cta_banco`
  ADD PRIMARY KEY (`id_movimiento`);

--
-- Indices de la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD PRIMARY KEY (`id_municipio`);

--
-- Indices de la tabla `municipioMH`
--
ALTER TABLE `municipioMH`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `nc_corte`
--
ALTER TABLE `nc_corte`
  ADD PRIMARY KEY (`id_nc`);

--
-- Indices de la tabla `paises`
--
ALTER TABLE `paises`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `parametros_cuota`
--
ALTER TABLE `parametros_cuota`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_factura`);

--
-- Indices de la tabla `pedidos_detalle`
--
ALTER TABLE `pedidos_detalle`
  ADD PRIMARY KEY (`id_factura_detalle`);

--
-- Indices de la tabla `pedidos_pago`
--
ALTER TABLE `pedidos_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  ADD PRIMARY KEY (`id_pedido_detalle`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `pedido_prov`
--
ALTER TABLE `pedido_prov`
  ADD PRIMARY KEY (`id_pedido_prov`);

--
-- Indices de la tabla `pedido_prov_detalle`
--
ALTER TABLE `pedido_prov_detalle`
  ADD PRIMARY KEY (`id_pedido_detalle`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `posicion`
--
ALTER TABLE `posicion`
  ADD PRIMARY KEY (`id_posicion`);

--
-- Indices de la tabla `precio_aut`
--
ALTER TABLE `precio_aut`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  ADD PRIMARY KEY (`id_presentacion`);

--
-- Indices de la tabla `presentacion_producto`
--
ALTER TABLE `presentacion_producto`
  ADD PRIMARY KEY (`id_pp`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `barcode` (`barcode`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `resolucion`
--
ALTER TABLE `resolucion`
  ADD PRIMARY KEY (`id_resolucion`);

--
-- Indices de la tabla `rutas`
--
ALTER TABLE `rutas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indices de la tabla `servicio_detalle`
--
ALTER TABLE `servicio_detalle`
  ADD PRIMARY KEY (`id_detalle`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id_stock`);

--
-- Indices de la tabla `stock_ubicacion`
--
ALTER TABLE `stock_ubicacion`
  ADD PRIMARY KEY (`id_su`);

--
-- Indices de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD PRIMARY KEY (`id_sucursal`);

--
-- Indices de la tabla `tanque`
--
ALTER TABLE `tanque`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tanque_diario`
--
ALTER TABLE `tanque_diario`
  ADD PRIMARY KEY (`id_stock`);

--
-- Indices de la tabla `tipodoc`
--
ALTER TABLE `tipodoc`
  ADD PRIMARY KEY (`idtipodoc`);

--
-- Indices de la tabla `tipo_caja`
--
ALTER TABLE `tipo_caja`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_empleado`
--
ALTER TABLE `tipo_empleado`
  ADD PRIMARY KEY (`id_tipo_empleado`);

--
-- Indices de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  ADD PRIMARY KEY (`id_tipopago`);

--
-- Indices de la tabla `tipo_proveedor`
--
ALTER TABLE `tipo_proveedor`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `tipo_vehiculo`
--
ALTER TABLE `tipo_vehiculo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `titulo_bienes_MH`
--
ALTER TABLE `titulo_bienes_MH`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `token_auth_dia`
--
ALTER TABLE `token_auth_dia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `to_corte`
--
ALTER TABLE `to_corte`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `to_corte_producto`
--
ALTER TABLE `to_corte_producto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `to_corte_producto_detalle`
--
ALTER TABLE `to_corte_producto_detalle`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `traslado`
--
ALTER TABLE `traslado`
  ADD PRIMARY KEY (`id_traslado`);

--
-- Indices de la tabla `traslado_detalle`
--
ALTER TABLE `traslado_detalle`
  ADD PRIMARY KEY (`id_detalle_traslado`);

--
-- Indices de la tabla `traslado_detalle_g`
--
ALTER TABLE `traslado_detalle_g`
  ADD PRIMARY KEY (`id_detalle_traslado`);

--
-- Indices de la tabla `traslado_detalle_recibido`
--
ALTER TABLE `traslado_detalle_recibido`
  ADD PRIMARY KEY (`id_detalle_traslado_recibido`);

--
-- Indices de la tabla `traslado_g`
--
ALTER TABLE `traslado_g`
  ADD PRIMARY KEY (`id_traslado`);

--
-- Indices de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD PRIMARY KEY (`id_ubicacion`);

--
-- Indices de la tabla `unidad_medidaMH`
--
ALTER TABLE `unidad_medidaMH`
  ADD PRIMARY KEY (`id`),
  ADD KEY `codigo` (`codigo`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `usuario_modulo`
--
ALTER TABLE `usuario_modulo`
  ADD PRIMARY KEY (`id_mod_user`);

--
-- Indices de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `venta_cuota`
--
ALTER TABLE `venta_cuota`
  ADD PRIMARY KEY (`id_venta_cuota`);

--
-- Indices de la tabla `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id_voucher`);

--
-- Indices de la tabla `voucher_mov`
--
ALTER TABLE `voucher_mov`
  ADD PRIMARY KEY (`id_mv`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `abono_credito`
--
ALTER TABLE `abono_credito`
  MODIFY `id_abono_credito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `abono_historial`
--
ALTER TABLE `abono_historial`
  MODIFY `id_abono_historial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `access_conf`
--
ALTER TABLE `access_conf`
  MODIFY `id_conf` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `altclitocli`
--
ALTER TABLE `altclitocli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `apertura_caja`
--
ALTER TABLE `apertura_caja`
  MODIFY `id_apertura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `arqueo_conceptos`
--
ALTER TABLE `arqueo_conceptos`
  MODIFY `id` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `arqueo_corte`
--
ALTER TABLE `arqueo_corte`
  MODIFY `id_arqueo` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `banco`
--
ALTER TABLE `banco`
  MODIFY `id_banco` int(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bomba`
--
ALTER TABLE `bomba`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bomba_manguera`
--
ALTER TABLE `bomba_manguera`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categoria_proveedor`
--
ALTER TABLE `categoria_proveedor`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `cat_doc_MH`
--
ALTER TABLE `cat_doc_MH`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `cheque`
--
ALTER TABLE `cheque`
  MODIFY `id_cheque` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cliente_dif`
--
ALTER TABLE `cliente_dif`
  MODIFY `id_dif` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `compra2`
--
ALTER TABLE `compra2`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `compra_imp_combust`
--
ALTER TABLE `compra_imp_combust`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id_configuracion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `config_dir`
--
ALTER TABLE `config_dir`
  MODIFY `id_config_dir` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `config_pos`
--
ALTER TABLE `config_pos`
  MODIFY `id_config_pos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `consignacion`
--
ALTER TABLE `consignacion`
  MODIFY `id_consignacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `consignacion_detalle`
--
ALTER TABLE `consignacion_detalle`
  MODIFY `id_consignacion_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `consumo_mes_dif`
--
ALTER TABLE `consumo_mes_dif`
  MODIFY `id_consumo_dif` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `controlcaja`
--
ALTER TABLE `controlcaja`
  MODIFY `id_corte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `correlativo`
--
ALTER TABLE `correlativo`
  MODIFY `id_numdoc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  MODIFY `id_cotizacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cotizacion_detalle`
--
ALTER TABLE `cotizacion_detalle`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `credito`
--
ALTER TABLE `credito`
  MODIFY `id_credito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cuentas_por_pagar_abonos`
--
ALTER TABLE `cuentas_por_pagar_abonos`
  MODIFY `id_abono` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuenta_banco`
--
ALTER TABLE `cuenta_banco`
  MODIFY `id_cuenta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuenta_pagar`
--
ALTER TABLE `cuenta_pagar`
  MODIFY `id_cuenta_pagar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuota`
--
ALTER TABLE `cuota`
  MODIFY `id_cuota` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_apertura`
--
ALTER TABLE `detalle_apertura`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id_det_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `detalle_compra2`
--
ALTER TABLE `detalle_compra2`
  MODIFY `id_det_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_voucher`
--
ALTER TABLE `detalle_voucher`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  MODIFY `id_dev` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `devoluciones_corte`
--
ALTER TABLE `devoluciones_corte`
  MODIFY `id_dev` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `devoluciones_det`
--
ALTER TABLE `devoluciones_det`
  MODIFY `id_dev_det` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estante`
--
ALTER TABLE `estante`
  MODIFY `id_estante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `factura_detalle`
--
ALTER TABLE `factura_detalle`
  MODIFY `id_factura_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `factura_pago`
--
ALTER TABLE `factura_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `fact_imp_combust`
--
ALTER TABLE `fact_imp_combust`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `giroMH`
--
ALTER TABLE `giroMH`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=857;

--
-- AUTO_INCREMENT de la tabla `impuestosMH`
--
ALTER TABLE `impuestosMH`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `impuestos_gasolina`
--
ALTER TABLE `impuestos_gasolina`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `laboratorio`
--
ALTER TABLE `laboratorio`
  MODIFY `id_laboratorio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de la tabla `lectura_bomba`
--
ALTER TABLE `lectura_bomba`
  MODIFY `id_lectura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lectura_detalle_bomba`
--
ALTER TABLE `lectura_detalle_bomba`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lectura_lub_dia`
--
ALTER TABLE `lectura_lub_dia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `log_cambio_local`
--
ALTER TABLE `log_cambio_local`
  MODIFY `id_log_cambio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `log_detalle_cambio_local`
--
ALTER TABLE `log_detalle_cambio_local`
  MODIFY `id_detalle_cambio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `log_update_local`
--
ALTER TABLE `log_update_local`
  MODIFY `id_log_cambio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT de la tabla `lote`
--
ALTER TABLE `lote`
  MODIFY `id_lote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id_marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `margen_cols_form`
--
ALTER TABLE `margen_cols_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `modelo`
--
ALTER TABLE `modelo`
  MODIFY `id_modelo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT de la tabla `movimiento_caja_tipo`
--
ALTER TABLE `movimiento_caja_tipo`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimiento_producto`
--
ALTER TABLE `movimiento_producto`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `movimiento_producto_detalle`
--
ALTER TABLE `movimiento_producto_detalle`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `movimiento_producto_pendiente`
--
ALTER TABLE `movimiento_producto_pendiente`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimiento_stock_ubicacion`
--
ALTER TABLE `movimiento_stock_ubicacion`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `mov_caja`
--
ALTER TABLE `mov_caja`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mov_cta_banco`
--
ALTER TABLE `mov_cta_banco`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `municipio`
--
ALTER TABLE `municipio`
  MODIFY `id_municipio` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID del municipio', AUTO_INCREMENT=263;

--
-- AUTO_INCREMENT de la tabla `municipioMH`
--
ALTER TABLE `municipioMH`
  MODIFY `id` smallint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=264;

--
-- AUTO_INCREMENT de la tabla `nc_corte`
--
ALTER TABLE `nc_corte`
  MODIFY `id_nc` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paises`
--
ALTER TABLE `paises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `parametros_cuota`
--
ALTER TABLE `parametros_cuota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'p';

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos_detalle`
--
ALTER TABLE `pedidos_detalle`
  MODIFY `id_factura_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos_pago`
--
ALTER TABLE `pedidos_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido_detalle`
--
ALTER TABLE `pedido_detalle`
  MODIFY `id_pedido_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido_prov`
--
ALTER TABLE `pedido_prov`
  MODIFY `id_pedido_prov` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido_prov_detalle`
--
ALTER TABLE `pedido_prov_detalle`
  MODIFY `id_pedido_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `posicion`
--
ALTER TABLE `posicion`
  MODIFY `id_posicion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `precio_aut`
--
ALTER TABLE `precio_aut`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  MODIFY `id_presentacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `presentacion_producto`
--
ALTER TABLE `presentacion_producto`
  MODIFY `id_pp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `resolucion`
--
ALTER TABLE `resolucion`
  MODIFY `id_resolucion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `rutas`
--
ALTER TABLE `rutas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3280;

--
-- AUTO_INCREMENT de la tabla `servicio_detalle`
--
ALTER TABLE `servicio_detalle`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=544;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `stock_ubicacion`
--
ALTER TABLE `stock_ubicacion`
  MODIFY `id_su` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  MODIFY `id_sucursal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tanque`
--
ALTER TABLE `tanque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tanque_diario`
--
ALTER TABLE `tanque_diario`
  MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipodoc`
--
ALTER TABLE `tipodoc`
  MODIFY `idtipodoc` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `tipo_caja`
--
ALTER TABLE `tipo_caja`
  MODIFY `id` smallint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_empleado`
--
ALTER TABLE `tipo_empleado`
  MODIFY `id_tipo_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  MODIFY `id_tipopago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tipo_proveedor`
--
ALTER TABLE `tipo_proveedor`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_vehiculo`
--
ALTER TABLE `tipo_vehiculo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `titulo_bienes_MH`
--
ALTER TABLE `titulo_bienes_MH`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `token_auth_dia`
--
ALTER TABLE `token_auth_dia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `to_corte`
--
ALTER TABLE `to_corte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `to_corte_producto`
--
ALTER TABLE `to_corte_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `to_corte_producto_detalle`
--
ALTER TABLE `to_corte_producto_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `traslado`
--
ALTER TABLE `traslado`
  MODIFY `id_traslado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `traslado_detalle`
--
ALTER TABLE `traslado_detalle`
  MODIFY `id_detalle_traslado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `traslado_detalle_g`
--
ALTER TABLE `traslado_detalle_g`
  MODIFY `id_detalle_traslado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `traslado_detalle_recibido`
--
ALTER TABLE `traslado_detalle_recibido`
  MODIFY `id_detalle_traslado_recibido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `traslado_g`
--
ALTER TABLE `traslado_g`
  MODIFY `id_traslado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  MODIFY `id_ubicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `unidad_medidaMH`
--
ALTER TABLE `unidad_medidaMH`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario_modulo`
--
ALTER TABLE `usuario_modulo`
  MODIFY `id_mod_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2641;

--
-- AUTO_INCREMENT de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `venta_cuota`
--
ALTER TABLE `venta_cuota`
  MODIFY `id_venta_cuota` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `voucher`
--
ALTER TABLE `voucher`
  MODIFY `id_voucher` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `voucher_mov`
--
ALTER TABLE `voucher_mov`
  MODIFY `id_mv` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

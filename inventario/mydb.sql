-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-05-2025 a las 13:35:07
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mydb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `categoria_id` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`categoria_id`, `nombre`, `descripcion`) VALUES
(1, 'Ropa', 'Ropa de moda'),
(2, 'Calzado', 'Zapatos y tenis'),
(3, 'Accesorios', 'Gorras, cinturones, etc.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `cliente_id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `direccion` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `telefono` varchar(45) NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cliente_id`, `nombre`, `apellido`, `direccion`, `email`, `telefono`, `fecha_registro`) VALUES
(1, 'Carlos', 'López', 'Calle Luna 45', 'carlos@example.com', '3311111111', '2025-05-01 10:00:00'),
(2, 'María', 'García', 'Calle Sol 12', 'maria@example.com', '3322222222', '2025-05-05 14:30:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleventa`
--

CREATE TABLE `detalleventa` (
  `detalle_id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `variante_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `descuento_unitario` decimal(10,2) DEFAULT 0.00,
  `total_linea` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `detalleventa`
--

INSERT INTO `detalleventa` (`detalle_id`, `venta_id`, `variante_id`, `cantidad`, `precio_unitario`, `descuento_unitario`, `total_linea`) VALUES
(1, 1, 1, 1, 199.99, 0.00, 199.99),
(2, 1, 2, 1, 899.50, 0.00, 899.50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `empleado_id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `puesto` enum('Vendedor','Supervisor','Gerente','Almacenero','Administrativo') NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `contraseña` varchar(45) NOT NULL,
  `activo` enum('Activo','Inactivo','Suspendido','Vacaciones') DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`empleado_id`, `nombre`, `apellido`, `puesto`, `usuario`, `contraseña`, `activo`) VALUES
(1, 'Laura', 'Hernández', 'Vendedor', 'laura', '1234', 'Activo'),
(2, 'Pedro', 'Ramírez', 'Almacenero', 'pedro', '5678', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oferta`
--

CREATE TABLE `oferta` (
  `oferta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `porcentaje_descuento` decimal(5,2) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `activa` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `oferta`
--

INSERT INTO `oferta` (`oferta_id`, `producto_id`, `porcentaje_descuento`, `fecha_inicio`, `fecha_fin`, `activa`) VALUES
(1, 1, 15.00, '2025-05-01 00:00:00', '2025-05-31 23:59:59', 1),
(2, 3, 10.00, '2025-05-10 00:00:00', '2025-06-10 23:59:59', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `producto_id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `precio_base` decimal(10,2) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `proveedor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`producto_id`, `nombre`, `descripcion`, `precio_base`, `categoria_id`, `proveedor_id`) VALUES
(1, 'Playera Negra', 'Playera de algodón', 199.99, 1, 1),
(2, 'Tenis Deportivos', 'Tenis para correr', 899.50, 2, 2),
(3, 'Gorra Roja', 'Gorra ajustable', 149.00, 3, 1),
(7, 'Ohlf', 'fdfsdfsdfwdqdwqwdqwdwqd', 999.00, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `proveedor_id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `contacto` varchar(45) DEFAULT NULL,
  `telefono` varchar(15) NOT NULL,
  `email` varchar(45) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `ruc` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`proveedor_id`, `nombre`, `contacto`, `telefono`, `email`, `direccion`, `ruc`) VALUES
(1, 'Proveedor A', 'Juan Pérez', '3312345678', 'proveedora@example.com', 'Calle Falsa 123', 'RUC001'),
(2, 'Proveedor B', 'Ana Ruiz', '3311122233', 'proveedorb@example.com', 'Avenida Siempre Viva 742', 'RUC002');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `reporte_id` int(11) NOT NULL,
  `tipo` enum('Queja','Reclamo','Sugerencia','Reporte') NOT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `descripcion` varchar(200) NOT NULL,
  `estado` enum('Abierto','En proceso','Resuelto','Cerrado') DEFAULT 'Abierto',
  `cliente_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `empleado_id` int(11) DEFAULT NULL,
  `venta_id` int(11) DEFAULT NULL,
  `fecha_cierre` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `reportes`
--

INSERT INTO `reportes` (`reporte_id`, `tipo`, `fecha_creacion`, `descripcion`, `estado`, `cliente_id`, `producto_id`, `empleado_id`, `venta_id`, `fecha_cierre`) VALUES
(1, 'Queja', '2025-05-12 09:00:00', 'Producto con defecto', 'En proceso', 1, 1, 2, 1, NULL),
(2, 'Sugerencia', '2025-05-13 11:30:00', 'Agregar más tallas', 'Abierto', 2, 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `varianteproducto`
--

CREATE TABLE `varianteproducto` (
  `variante_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `talla` varchar(10) NOT NULL,
  `color` varchar(30) NOT NULL,
  `cantidad_stock` int(11) NOT NULL DEFAULT 0,
  `sku` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `varianteproducto`
--

INSERT INTO `varianteproducto` (`variante_id`, `producto_id`, `talla`, `color`, `cantidad_stock`, `sku`) VALUES
(1, 1, 'M', 'Negro', 50, 'SKU-PLN-M-N'),
(2, 2, '42', 'Blanco', 30, 'SKU-TEN-42-B'),
(3, 3, 'Única', 'Rojo', 70, 'SKU-GOR-U-R');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `venta_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `decuento_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `impuesto` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL,
  `metodo_pago` enum('EFECTIVO','TARJETA_CREDITO','TARJETA_DEBITO','TRANSFERENCIA','OTRO') DEFAULT NULL,
  `estado` enum('COMPLETADA','CANCELADA','DEVOLUCION','PENDIENTE') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`venta_id`, `cliente_id`, `empleado_id`, `fecha_hora`, `subtotal`, `decuento_total`, `impuesto`, `total`, `metodo_pago`, `estado`) VALUES
(1, 1, 1, '2025-05-10 12:00:00', 1048.99, 0.00, 167.84, 1216.83, 'TARJETA_CREDITO', 'COMPLETADA');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`categoria_id`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cliente_id`);

--
-- Indices de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD PRIMARY KEY (`detalle_id`),
  ADD KEY `fk_detalle_venta_idx` (`venta_id`),
  ADD KEY `fk_detalle_variante_idx` (`variante_id`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`empleado_id`);

--
-- Indices de la tabla `oferta`
--
ALTER TABLE `oferta`
  ADD PRIMARY KEY (`oferta_id`),
  ADD KEY `fk_oferta_producto_idx` (`producto_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`producto_id`),
  ADD KEY `fk_producto_categoria_idx` (`categoria_id`),
  ADD KEY `fk_producto_proveedor_idx` (`proveedor_id`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`proveedor_id`),
  ADD UNIQUE KEY `ruc_UNIQUE` (`ruc`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`reporte_id`),
  ADD KEY `fk_reporte_cliente_idx` (`cliente_id`),
  ADD KEY `fk_reporte_producto_idx` (`producto_id`),
  ADD KEY `fk_reporte_empleado_idx` (`empleado_id`),
  ADD KEY `fk_reporte_venta_idx` (`venta_id`);

--
-- Indices de la tabla `varianteproducto`
--
ALTER TABLE `varianteproducto`
  ADD PRIMARY KEY (`variante_id`),
  ADD UNIQUE KEY `sku_UNIQUE` (`sku`),
  ADD KEY `fk_variannteProducto_idx` (`producto_id`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`venta_id`),
  ADD KEY `fk_venta_cliente_idx` (`cliente_id`),
  ADD KEY `fk_venta_empleado_idx` (`empleado_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  MODIFY `detalle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `empleado_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `oferta`
--
ALTER TABLE `oferta`
  MODIFY `oferta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `producto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `proveedor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `reporte_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `varianteproducto`
--
ALTER TABLE `varianteproducto`
  MODIFY `variante_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `venta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD CONSTRAINT `fk_detalle_variante` FOREIGN KEY (`variante_id`) REFERENCES `varianteproducto` (`variante_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detalle_venta` FOREIGN KEY (`venta_id`) REFERENCES `venta` (`venta_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `oferta`
--
ALTER TABLE `oferta`
  ADD CONSTRAINT `fk_oferta_producto` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`producto_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_producto_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedor` (`proveedor_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `fk_reporte_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`cliente_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_reporte_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`empleado_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_reporte_producto` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`producto_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_reporte_venta` FOREIGN KEY (`venta_id`) REFERENCES `venta` (`venta_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `varianteproducto`
--
ALTER TABLE `varianteproducto`
  ADD CONSTRAINT `fk_variannteProducto` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`producto_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `fk_venta_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`cliente_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_venta_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`empleado_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

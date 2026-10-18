-- =====================================================================
-- SysWeb - Datos simulados (seed data)
-- NO toca la tabla `usuarios`.
-- Ejecutar DESPUÉS de: 1) la estructura original  2) sql/actualizacion_final.sql
--
-- Este script:
--   1) Borra los datos existentes de todas las tablas de negocio
--      (en orden seguro respecto a las foreign keys).
--   2) Carga datos de ejemplo coherentes entre sí:
--      departamento -> ciudad -> clientes
--      deposito, u_medida, tipo_producto -> producto
--      proveedor -> compra -> detalle_compra -> stock
--      venta -> det_venta (y descuenta del stock ya cargado)
--
-- Asume que ya existe el usuario con id_user = 1 (el "Super Admin" que
-- viene en el dump original) para las columnas id_user de compra/venta.
-- Si tu usuario admin tiene otro id_user, reemplazá el valor 1 por el
-- que corresponda antes de ejecutar.
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1) Limpieza de datos existentes -------------------------------------
TRUNCATE TABLE det_venta;
TRUNCATE TABLE venta;
TRUNCATE TABLE detalle_compra;
TRUNCATE TABLE compra;
TRUNCATE TABLE stock;
TRUNCATE TABLE clientes;
TRUNCATE TABLE ciudad;
TRUNCATE TABLE departamento;
TRUNCATE TABLE producto;
TRUNCATE TABLE proveedor;
TRUNCATE TABLE tipo_producto;
TRUNCATE TABLE u_medida;
TRUNCATE TABLE deposito;
TRUNCATE TABLE tmp;

SET FOREIGN_KEY_CHECKS = 1;

-- 2) Referenciales geográficos ------------------------------------------

INSERT INTO departamento (id_departamento, dep_descripcion) VALUES
(1, 'Central'),
(2, 'Alto Paraná'),
(3, 'Itapúa'),
(4, 'Cordillera'),
(5, 'Guairá');

INSERT INTO ciudad (cod_ciudad, descrip_ciudad, id_departamento) VALUES
(1, 'Asunción', 1),
(2, 'Luque', 1),
(3, 'San Lorenzo', 1),
(4, 'Ciudad del Este', 2),
(5, 'Hernandarias', 2),
(6, 'Encarnación', 3),
(7, 'Cambyretá', 3),
(8, 'Caacupé', 4),
(9, 'Villarrica', 5),
(10, 'Fernando de la Mora', 1);

-- 3) Clientes -------------------------------------------------------------

INSERT INTO clientes (id_cliente, ci_ruc, cli_nombre, cli_apellido, cli_direccion, cli_telefono, cod_ciudad) VALUES
(1, '4123456', 'María', 'González', 'Av. España 123', 981123456, 1),
(2, '3987654', 'Carlos', 'Benítez', 'Calle Palma 456', 982234567, 3),
(3, '4456789', 'Laura', 'Ortiz', 'Ruta 2 Km 8', 983345678, 2),
(4, '3789012', 'Diego', 'Rojas', 'Av. Boquerón 234', 984456789, 4),
(5, '4321098', 'Sofía', 'Cabrera', 'Calle Mcal. Estigarribia 89', 985567890, 6),
(6, '3654321', 'Miguel', 'Duarte', 'Av. San Blas 12', 986678901, 8),
(7, '4567890', 'Valentina', 'Sosa', 'Ruta 1 Km 20', 987789012, 9),
(8, '3210987', 'Andrés', 'Fernández', 'Barrio Obrero, calle 5', 988890123, 1);

-- 4) Referenciales de compras ---------------------------------------------

INSERT INTO deposito (cod_deposito, descrip) VALUES
(1, 'Depósito Central'),
(2, 'Depósito Sucursal Norte'),
(3, 'Depósito Sucursal Este');

INSERT INTO u_medida (id_u_medida, u_descrip) VALUES
(1, 'Unidad'),
(2, 'Kilogramo'),
(3, 'Litro'),
(4, 'Caja'),
(5, 'Docena');

INSERT INTO tipo_producto (cod_tipo_prod, t_p_descrip) VALUES
(1, 'Bebidas'),
(2, 'Almacén'),
(3, 'Limpieza'),
(4, 'Lácteos'),
(5, 'Electrónica');

INSERT INTO producto (cod_producto, cod_tipo_prod, id_u_medida, p_descrip, precio) VALUES
(1, 1, 1, 'Coca Cola 1.5L', 9500),
(2, 1, 1, 'Agua Mineral 500ml', 3500),
(3, 1, 1, 'Jugo Del Valle 1L', 8000),
(4, 2, 2, 'Arroz Guaraní', 6500),
(5, 2, 2, 'Azúcar', 5800),
(6, 2, 1, 'Aceite Girasol 900ml', 12500),
(7, 2, 1, 'Fideo Espagueti 500g', 4200),
(8, 3, 1, 'Detergente Ala 750ml', 9800),
(9, 3, 1, 'Lavandina Espadol 1L', 6500),
(10, 3, 5, 'Jabón de Tocador (pack x12)', 21000),
(11, 4, 3, 'Leche Trébol 1L', 7200),
(12, 4, 1, 'Yogurt Yakult 6pack', 9900),
(13, 4, 1, 'Queso Paraguay 500g', 18500),
(14, 5, 1, 'Pilas AA Duracell x4', 15500),
(15, 5, 1, 'Cargador USB-C', 45000);

INSERT INTO proveedor (cod_proveedor, razon_social, ruc, direccion, telefono) VALUES
(1, 'Distribuidora Central S.A.', '80012345', 'Av. Eusebio Ayala 1234, Asunción', 21555111),
(2, 'Comercial Guaraní S.R.L.', '80023456', 'Ruta 2 Km 15, San Lorenzo', 21555222),
(3, 'Importadora del Este', '80034567', 'Av. Monseñor Rodríguez 456, C.D.E.', 61555333),
(4, 'Lácteos Trébol S.A.', '80045678', 'Ruta 1 Km 30, Ypané', 21555444),
(5, 'ElectroHogar Import', '80056789', 'Av. Mcal. López 789, Asunción', 21555555);

-- 5) Compras y su detalle ---------------------------------------------------
-- (id_user = 1: usuario Super Admin del dump original)

INSERT INTO compra (cod_compra, cod_proveedor, nro_factura, fecha, estado, cod_deposito, hora, total_compra, id_user) VALUES
(1, 1, '001-001-0000123', '2026-06-01', 'activo', 1, '09:15:00', 415000, 1),
(2, 2, '001-001-0000456', '2026-06-03', 'activo', 1, '10:30:00', 662000, 1),
(3, 3, '002-001-0000789', '2026-06-05', 'activo', 2, '14:00:00', 481000, 1),
(4, 4, '003-001-0000234', '2026-06-07', 'activo', 1, '08:45:00', 1006500, 1),
(5, 5, '004-001-0000012', '2026-06-08', 'activo', 3, '16:20:00', 1014500, 1);

INSERT INTO detalle_compra (cod_producto, cod_compra, cod_deposito, precio, cantidad) VALUES
-- Compra 1 (Depósito Central)
(1, 1, 1, 9500, 20),
(2, 1, 1, 3500, 30),
(3, 1, 1, 8000, 15),
-- Compra 2 (Depósito Central)
(4, 2, 1, 6500, 50),
(5, 2, 1, 5800, 40),
(7, 2, 1, 4200, 25),
-- Compra 3 (Depósito Sucursal Norte)
(8, 3, 2, 9800, 20),
(9, 3, 2, 6500, 20),
(14, 3, 2, 15500, 10),
-- Compra 4 (Depósito Central)
(11, 4, 1, 7200, 60),
(12, 4, 1, 9900, 30),
(13, 4, 1, 18500, 15),
-- Compra 5 (Depósito Sucursal Este)
(15, 5, 3, 45000, 10),
(6, 5, 3, 12500, 25),
(10, 5, 3, 21000, 12);

-- 6) Ventas y su detalle -----------------------------------------------------
-- Venta 1 y 2: activas (descuentan stock). Venta 3: anulada, a modo de
-- ejemplo para probar el botón "Anular" (el stock ya está como si se
-- hubiese restituido, es decir, NO se descontó de los totales de stock).

INSERT INTO venta (cod_venta, id_cliente, fecha, total_venta, estado, hora, nro_factura, id_user) VALUES
(1, 1, '2026-06-10', 140100, 'activo', '11:00:00', 1001, 1),
(2, 3, '2026-06-11', 114500, 'activo', '15:20:00', 1002, 1),
(3, 5, '2026-06-12', 39200, 'anulado', '09:40:00', 1003, 1);

INSERT INTO det_venta (cod_producto, cod_venta, cod_deposito, det_precio_unit, det_cantidad) VALUES
-- Venta 1
(1, 1, 1, 9500, 5),
(2, 1, 1, 3500, 10),
(11, 1, 1, 7200, 8),
-- Venta 2
(4, 2, 1, 6500, 10),
(12, 2, 1, 9900, 5),
-- Venta 3 (anulada)
(3, 3, 1, 8000, 2),
(5, 3, 1, 5800, 4);

-- 7) Stock final --------------------------------------------------------------
-- = cantidades compradas - cantidades vendidas en ventas ACTIVAS
-- (la venta 3 está anulada, por eso producto 3 y 5 conservan el total comprado)

INSERT INTO stock (cod_deposito, cod_producto, cantidad) VALUES
-- Depósito Central (1)
(1, 1, 15),   -- 20 - 5
(1, 2, 20),   -- 30 - 10
(1, 3, 15),   -- sin descuento (venta anulada)
(1, 4, 40),   -- 50 - 10
(1, 5, 40),   -- sin descuento (venta anulada)
(1, 7, 25),
(1, 11, 52),  -- 60 - 8
(1, 12, 25),  -- 30 - 5
(1, 13, 15),
-- Depósito Sucursal Norte (2)
(2, 8, 20),
(2, 9, 20),
(2, 14, 10),
-- Depósito Sucursal Este (3)
(3, 6, 25),
(3, 10, 12),
(3, 15, 10);

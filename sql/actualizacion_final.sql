-- =====================================================================
-- SysWeb - Script de actualización para el examen final
-- Ejecutar DESPUÉS de haber cargado la estructura original de la base
-- de datos (tablas: usuarios, departamento, ciudad, clientes, deposito,
-- u_medida, tipo_producto, producto, proveedor, compra, detalle_compra,
-- stock, venta, det_venta, tmp).
--
-- Este script:
--   1) Agrega la columna id_user a la tabla venta (para poder saber
--      qué usuario registró cada venta, igual que ya se hace en compra).
--   2) Crea las vistas que usan los formularios de compras, ventas y
--      stock (v_compras, v_det_compra, v_venta, v_det_venta, v_stock).
-- =====================================================================

-- 1) Trazabilidad de usuario en venta (igual que en compra)
ALTER TABLE venta ADD COLUMN id_user INT(11) NULL AFTER nro_factura;

-- 2) Vistas -------------------------------------------------------------

-- Cabecera de compras con datos de proveedor, depósito y usuario
CREATE OR REPLACE VIEW v_compras AS
SELECT
    c.cod_compra,
    c.cod_proveedor,
    p.razon_social,
    p.ruc,
    c.cod_deposito,
    d.descrip,
    c.nro_factura,
    c.fecha,
    c.hora,
    c.estado,
    c.total_compra,
    c.id_user,
    u.name_user
FROM compra c
INNER JOIN proveedor p ON p.cod_proveedor = c.cod_proveedor
INNER JOIN deposito d ON d.cod_deposito = c.cod_deposito
LEFT JOIN usuarios u ON u.id_user = c.id_user;

-- Detalle de compras con datos de producto, tipo y unidad de medida
CREATE OR REPLACE VIEW v_det_compra AS
SELECT
    dc.cod_compra,
    dc.cod_producto,
    dc.cod_deposito,
    dc.precio,
    dc.cantidad,
    pr.p_descrip,
    pr.cod_tipo_prod,
    tp.t_p_descrip,
    pr.id_u_medida,
    um.u_descrip
FROM detalle_compra dc
INNER JOIN producto pr ON pr.cod_producto = dc.cod_producto
INNER JOIN tipo_producto tp ON tp.cod_tipo_prod = pr.cod_tipo_prod
INNER JOIN u_medida um ON um.id_u_medida = pr.id_u_medida;

-- Cabecera de ventas con datos de cliente y usuario
CREATE OR REPLACE VIEW v_venta AS
SELECT
    v.cod_venta,
    v.id_cliente,
    cl.cli_nombre,
    cl.cli_apellido,
    cl.ci_ruc,
    v.fecha,
    v.hora,
    v.nro_factura,
    v.estado,
    v.total_venta,
    v.id_user,
    u.name_user
FROM venta v
INNER JOIN clientes cl ON cl.id_cliente = v.id_cliente
LEFT JOIN usuarios u ON u.id_user = v.id_user;

-- Detalle de ventas con datos de producto, tipo, unidad de medida y depósito
CREATE OR REPLACE VIEW v_det_venta AS
SELECT
    dv.cod_venta,
    dv.cod_producto,
    dv.cod_deposito,
    dep.descrip AS descrip_deposito,
    dv.det_precio_unit,
    dv.det_cantidad,
    pr.p_descrip,
    pr.cod_tipo_prod,
    tp.t_p_descrip,
    pr.id_u_medida,
    um.u_descrip
FROM det_venta dv
INNER JOIN producto pr ON pr.cod_producto = dv.cod_producto
INNER JOIN tipo_producto tp ON tp.cod_tipo_prod = pr.cod_tipo_prod
INNER JOIN u_medida um ON um.id_u_medida = pr.id_u_medida
INNER JOIN deposito dep ON dep.cod_deposito = dv.cod_deposito;

-- Stock con datos de depósito, producto, tipo y unidad de medida
CREATE OR REPLACE VIEW v_stock AS
SELECT
    s.cod_deposito,
    d.descrip,
    s.cod_producto,
    pr.p_descrip,
    pr.cod_tipo_prod,
    tp.t_p_descrip,
    pr.id_u_medida,
    um.u_descrip,
    s.cantidad
FROM stock s
INNER JOIN deposito d ON d.cod_deposito = s.cod_deposito
INNER JOIN producto pr ON pr.cod_producto = s.cod_producto
INNER JOIN tipo_producto tp ON tp.cod_tipo_prod = pr.cod_tipo_prod
INNER JOIN u_medida um ON um.id_u_medida = pr.id_u_medida;

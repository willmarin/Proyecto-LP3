# SysWeb – Proyecto Final

## 1) Antes de correr el proyecto
1. Importar la estructura SQL original que ya tenías.
2. Ejecutar `sql/actualizacion_final.sql` (agrega `venta.id_user` y crea las vistas
   `v_compras`, `v_det_compra`, `v_venta`, `v_det_venta`, `v_stock` que usan los
   formularios nuevos).

## 2) Módulos referenciales agregados (view / form / proses / print / print_view)
- `modules/u_medida`
- `modules/tipo_producto`
- `modules/deposito`
- `modules/producto` (combos de Tipo de Producto y Unidad de Medida)
- `modules/proveedor`

Todos siguen la misma lógica que `departamento` y `ciudad`: código autogenerado,
alta/edición con validación de campos requeridos, baja con confirmación, listado
con DataTables y reporte PDF con Html2Pdf.

## 3) Módulo de Ventas (`modules/ventas`)
- `view.php`: lista las ventas activas (vista `v_venta`), con acciones
  **Anular** e **Imprimir**.
- `form.php`: alta de venta (cliente, depósito, N° de factura) + buscador de
  productos (reutiliza el mismo carrito temporal `ajax/productos_pedido.php` y
  `ajax/agregar_pedido.php` que ya usaba Compras).
- `proses.php`:
  - **Insertar**: antes de grabar, valida que haya stock suficiente en el
    depósito elegido para cada producto del carrito (si no alcanza, no se
    graba nada y se muestra un aviso). Si todo está OK, inserta la cabecera
    en `venta`, el detalle en `det_venta`, descuenta el `stock` y vacía el
    carrito temporal de esa sesión.
  - **Anular**: cambia el estado de la venta a `anulado` y **devuelve las
    cantidades vendidas al stock** (requisito de los 5 puntos). Controla que
    la venta no esté anulada previamente.
- `print.php` / `print_view.php`: factura de venta en PDF.

## 4) Stock (`modules/stock/view.php`)
Se agregó un segundo combo (buscador tipo Chosen) para filtrar por **Producto**,
además del combo de **Depósito** que ya existía. Al enviar el formulario, la
tabla se recarga filtrando por ambos criterios (el producto es opcional).

## 5) Menús según perfil de usuario
- `modules/start/view.php`: ya mostraba accesos directos distintos según el
  perfil (Super Admin ve Compras+Ventas+Stock; Compras ve Compras+Stock;
  Ventas ve Ventas+Stock).
- `sidebar-menu.php`: se agregó la rama completa para el perfil **Ventas**
  (Referenciales Generales, Referenciales de Ventas y Movimientos), y se
  reemplazaron los enlaces `#` de "Referenciales de Compras" por enlaces
  reales a Depósito, Proveedor, Producto y Unidad de medida, tanto para
  Super Admin como para el perfil Compras. También se agregó el enlace al
  nuevo módulo de Ventas dentro de "Movimientos".

## 6) `content.php`
Se agregaron las rutas de los módulos nuevos: `u_medida`, `tipo_producto`,
`deposito`, `producto`, `proveedor`, `ventas` (y sus respectivos `form_*`).

## 7) Notas / mejoras respecto al módulo de Compras original
Se detectaron y corrigieron, solo en el módulo de Ventas, dos detalles del
módulo de Compras que estaba usando como referencia:
- El carrito temporal (`tmp`) ahora se filtra y se limpia por `session_id`
  al confirmar la venta (en Compras el carrito nunca se vaciaba).
- La consulta de actualización de stock al anular usa correctamente
  `cod_producto` y `cod_deposito` (en Compras había una variable repetida).

Si querés, puedo aplicar las mismas dos correcciones al módulo de Compras
para que ambos queden 100% simétricos.

## 9) Revisión y correcciones al flujo de Compras y Ventas (movimientos)

Se hizo una revisión detallada de ambos módulos de movimiento y se corrigieron
los siguientes problemas:

**Compras (bugs encontrados en el código original, no escrito por mí):**
- El formulario **no tenía el campo oculto `id_user`**, por lo que el INSERT de
  cabecera quedaba con un valor vacío y fallaba con error de sintaxis SQL. Se
  agregó el campo oculto, tomado siempre de `$_SESSION['id_user']`.
- El carrito temporal (`tmp`) se leía **sin filtrar por `session_id`** al
  confirmar la compra, mezclando los carritos de distintos usuarios
  conectados al mismo tiempo. Ahora se filtra por sesión y además **se vacía**
  después de guardar (antes quedaba "pegado" para la próxima compra).
- El link de **Anular** no tenía el signo `=` en el parámetro GET
  (`cod_compra` en vez de `cod_compra=`), por lo que la acción nunca se
  ejecutaba. Corregido.
- La consulta que restaba stock al anular comparaba `cod_producto` contra la
  variable de depósito (bug de tipeo). Corregido.
- La fecha se guardaba tal cual la mostraba el datepicker (`dd-mm-yyyy`), un
  formato inválido para una columna `DATE`. Ahora se convierte a `yyyy-mm-dd`
  antes de insertar.
- Se agregó protección para no poder anular dos veces la misma compra.
- El total de la compra ahora se **recalcula en el servidor** a partir del
  carrito (antes se confiaba ciegamente en el total que mandaba el navegador).

**Ventas:**
- Mismo endurecimiento aplicado desde el inicio: usuario tomado de la sesión
  (no del formulario), carrito filtrado y vaciado por sesión, total
  recalculado en el servidor, validación de stock antes de descontar, y
  protección contra anular dos veces.
- **N° de Factura autogenerado**: ya no se carga a mano. El formulario
  consulta `MAX(nro_factura)` en `venta` y precarga el siguiente número
  (o `1001` si es la primera venta del sistema) en un campo de solo lectura,
  igual que ya se hacía con el código de venta.

Con estos cambios, Compras y Ventas quedan simétricos en robustez: ambos
validan sesión, cabecera, stock (cuando corresponde) y limpian el carrito
temporal correctamente.


## 10) Pendiente de tu lado
- Grabar el video explicativo mostrando: alta/baja/modificación de cada
  referencial, el flujo completo de una venta (armar carrito → guardar →
  ver que baja el stock → anular → ver que el stock vuelve), el flujo de una
  compra (armar carrito → guardar → ver que sube el stock → anular → ver que
  baja de nuevo), y el cambio de menú al iniciar sesión con un usuario de
  perfil Compras y otro Ventas.

## 11) Bug crítico: productos duplicados en el carrito (Compras y Ventas)

**Síntoma reportado:** al guardar una venta aparecía
`Duplicate entry '1-6' for key 'det_venta.PRIMARY'` y, a pesar del error,
quedaba algo guardado a medias (cabecera de venta creada, stock descontado
parcialmente).

**Causa raíz:** `ajax/agregar_pedido.php` insertaba una fila nueva en `tmp`
cada vez que se agregaba un producto, incluso si ese mismo producto ya
estaba en el carrito. Esto generaba líneas duplicadas en la grilla, y al
guardar, el sistema intentaba insertar dos veces la combinación
(producto, venta) en `det_venta`, cuya clave primaria es justamente esa
combinación → error fatal a mitad del proceso, dejando datos a medias.
Además, al validar stock por fila separada (en vez de por cantidad total
del producto), una combinación de filas duplicadas podía superar el stock
disponible sin que la validación lo detectara antes de intentar guardar.

**Corrección aplicada (en 3 capas):**
1. `ajax/agregar_pedido.php`: si el producto ya está en el carrito de la
   sesión, ahora **suma la cantidad a la fila existente** en vez de crear
   una nueva. Esto elimina el problema de raíz: ya no pueden aparecer
   productos repetidos en la grilla.
2. `modules/ventas/proses.php` y `modules/compras/proses.php`: antes de
   validar stock y guardar, el carrito se **consolida por producto** (por
   si quedara algún duplicado remanente), sumando cantidades. La
   validación de stock ahora compara la cantidad total pedida por producto
   contra el stock disponible, no fila por fila.
3. Los `INSERT` a `det_venta` / `detalle_compra` ahora usan
   `ON DUPLICATE KEY UPDATE` como red de seguridad adicional a nivel de
   base de datos: si por cualquier motivo llegara a repetirse la clave,
   sivamente suma la cantidad en vez de fallar.

**Extra:** se agregó un `try/catch` alrededor de las operaciones de guardar
y anular en ambos módulos. Desde PHP 8.1, `mysqli` lanza excepciones por
defecto ante cualquier error de SQL (por eso el error que viste salió como
una pantalla de error de PHP con stack trace, en vez del mensaje amigable
`die('Error'...)`). Ahora, ante cualquier error inesperado de base de datos,
el sistema registra el detalle en el log del servidor y redirige con un
aviso claro para el usuario, sin exponer una pantalla de error técnica.

También se agregó una validación de cantidad/precio mayor a 0 al agregar un
producto al carrito, para evitar líneas con valores en 0 o vacíos.

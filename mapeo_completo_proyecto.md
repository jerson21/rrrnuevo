# 📋 MAPEO COMPLETO DEL SISTEMA RESPALDOSCHILE

## 🏢 **INFORMACIÓN GENERAL**
- **Empresa:** RespaldosChile
- **Tipo de Negocio:** Fabricación y venta de respaldos/muebles tapizados
- **Sistema:** ERP completo de gestión empresarial
- **Base de Datos:** MySQL (`cre61650_agenda`)
- **Tecnologías:** PHP, JavaScript, Bootstrap, jQuery, DataTables

---

## 🗄️ **ARQUITECTURA DE BASE DE DATOS**

### **📊 TABLAS PRINCIPALES (Core Business)**

#### **1. GESTIÓN DE PEDIDOS**
```
pedido (Orden principal)
├── num_orden (PK, AUTO_INCREMENT)
├── rut_cliente → clientes.rut
├── fecha_ingreso
├── despacho (costo envío)
├── total_pagado
├── vendedor
├── metodo_entrega
├── estado
└── orden_ext (pedidos tienda online)

pedido_detalle (Productos individuales)
├── id (PK, AUTO_INCREMENT)
├── num_orden → pedido.num_orden
├── modelo, tamano, alturabase
├── tipotela, color
├── cantidad, precio
├── tapicero_id → usuarios.id
├── estadopedido → procesos.idProceso
├── direccion, numero, dpto, comuna
├── comentarios, detalles_fabricacion
├── ruta_asignada → rutas.id
├── metodo_entrega, detalle_entrega
└── cod_ped_anterior (reemisiones)
```

#### **2. GESTIÓN DE CLIENTES**
```
clientes
├── rut (PK)
├── nombre
├── telefono
├── instagram
└── correo

direccion_clientes (Múltiples direcciones)
├── id (PK)
├── rut_cliente → clientes.rut
├── direccion, numero, dpto
├── region, comuna
├── referencia
└── estado
```

#### **3. SISTEMA DE ESTADOS Y PROCESOS**
```
procesos (Catálogo de estados)
├── idProceso (PK)
├── NombreProceso
└── detalle

pedido_etapas (Historial de cambios)
├── idEtapa (PK)
├── idPedido → pedido_detalle.id
├── idProceso → procesos.idProceso
├── fecha
├── usuario → usuarios.usuario
└── observacion
```

#### **4. GESTIÓN DE USUARIOS**
```
usuarios
├── id (PK)
├── usuario, password
├── nombres, apaterno, amaterno
├── rut, correo
├── privilegios (roles del sistema)
├── s_diario (salario diario)
└── pago (forma de pago)
```

#### **5. SISTEMA DE PAGOS**
```
pagos
├── id (PK)
├── num_orden → pedido.num_orden
├── metodo_pago
├── id_cartola → cartola_bancaria.id
├── datos_adicionales (JSON)
├── monto
├── usuario → usuarios.usuario
└── fecha_mov

cartola_bancaria (Movimientos bancarios)
├── id (PK)
├── fecha, fecha_date
├── rut, nombre, banco
├── numero (cuenta origen)
├── monto, detalle
├── orden_asoc → pedido.num_orden
└── internal_id
```

### **📦 TABLAS DE INVENTARIO Y PRODUCCIÓN**

#### **6. GESTIÓN DE STOCK**
```
stock_productos (Productos terminados)
stock_telas (Inventario de telas)
stock_esqueletos (Estructura de muebles)
bodega (Inventario general)
sectores (Ubicaciones en bodega)
```

#### **7. PRODUCCIÓN**
```
cortes_tela (Control de cortes)
pago_produccion (Pagos a tapiceros)
productos_venta (Catálogo de precios)
```

#### **8. LOGÍSTICA**
```
rutas (Rutas de despacho)
├── id_circuit
├── fecha, horario_inicio, horario_termino
├── despachador → usuarios.id
├── estado
└── tipo

agencias (Empresas de envío)
├── Starken, Pullman, Cruz del Sur
```

---

## 👥 **SISTEMA DE ROLES Y PRIVILEGIOS**

### **NIVELES DE ACCESO:**
```
privilegios = 0:  Tapiceros
├── Solo ven su producción asignada
├── Pueden actualizar estados de sus productos
└── Acceso limitado a módulo de producción

privilegios = 4:  Vendedores
├── Gestión de ventas en sala
├── Stock y retiro de clientes
├── Sin acceso a fabricación

privilegios = 5:  Supervisores de Producción
├── Vista general de producción
├── Corte de telas y esqueletos
├── Asignación de trabajo

privilegios >= 20: Administradores
├── Acceso completo al sistema
├── Dashboard principal
├── Gestión de pedidos
├── Control de bodega y stock
├── Logística y rutas
├── Administración de clientes

privilegios >= 21: Contabilidad/Finanzas
├── Todo lo anterior +
├── Gestión de costos
├── Contabilidad avanzada
├── Reportes financieros
```

---

## 🔄 **FLUJO DE TRABAJO DEL NEGOCIO**

### **1. PROCESO DE PEDIDOS**
```
Cliente → Pedido → Validación Pago → Fabricación → Despacho → Entrega
   ↓         ↓           ↓             ↓           ↓         ↓
 clientes  pedido     pagos      pedido_detalle  rutas   estado=9
```

### **2. ESTADOS DEL PROCESO (procesos table)**
```
1  → PEDIDO ACEPTADO
2  → ENVIADO A FABRICACIÓN
3  → TELA CORTADA
4  → CORTE Y ARMADO DE ESQUELETO
5  → FABRICANDO (tapicero trabajando)
6  → FABRICADO (producto listo)
7  → DESPACHO INICIADO
8  → CARGADO EN CAMION
9  → PRODUCTO ENTREGADO
10-20 → Estados de devolución y gestión especial
```

### **3. ASIGNACIÓN DE TRABAJO**
```
Administrador → Asigna tapicero → pedido_detalle.tapicero_id
Tapicero → Ve sus productos → Actualiza estados
Sistema → Registra cambios → pedido_etapas
```

---

## 🎯 **MÓDULOS DEL SISTEMA**

### **1. DASHBOARD (Principal)**
- Vista general de pedidos ingresados
- Estados en tiempo real
- Gestión rápida de órdenes
- **Archivo:** `dashboard2024.php`

### **2. VENTAS**
- Venta en sala física
- Control de stock sala
- Retiro de clientes
- Re-impresión de documentos

### **3. PEDIDOS**
- Validación de pagos
- Agregar nuevos pedidos
- Vista de todos los pedidos
- Pedidos eliminados

### **4. PRODUCCIÓN**
- Asignación a tapiceros
- Control de fabricación
- Corte de telas y esqueletos
- Vista por tapicero

### **5. BODEGA & STOCK**
- Inventario general
- Ingreso de productos
- Gestión de telas
- Control de ubicaciones

### **6. LOGÍSTICA**
- Creación de rutas
- Gestión de despachos
- Integración con Starken
- Seguimiento de envíos

### **7. ADMINISTRACIÓN**
- Gestión de clientes
- Catálogo de colores
- Costos y contabilidad
- Configuración del sistema

---

## 🌐 **ARQUITECTURA DEL FRONTEND**

### **ESTRUCTURA DE ARCHIVOS**
```
assets/
├── js/
│   ├── dashboard.js (Dashboard principal)
│   ├── utils.js (Funciones compartidas)
│   ├── ventas.js (Módulo ventas)
│   ├── produccion.js (Módulo producción)
│   └── pedidos.js (Gestión pedidos)
├── css/
│   └── custom.css
└── img/

views/
├── header.php
├── sidebar.php
├── topbar.php
├── footer.php
├── modal_editar_orden.php
└── modal_validarpagos.php

api/
├── extraer_ordenes.php
├── get_order_details.php
├── actualizar_estado.php
├── buscarPagos.php
├── anadirpago.php
└── getpedido.php
```

### **TECNOLOGÍAS UTILIZADAS**
- **Backend:** PHP 8.2, MySQL/MariaDB
- **Frontend:** Bootstrap 5, jQuery 3.7, DataTables
- **Complementos:** SweetAlert2, FontAwesome
- **Integraciones:** API de Starken, Sistemas bancarios

---

## 🔧 **APIs Y ENDPOINTS**

### **1. GESTIÓN DE PEDIDOS**
```
GET/POST api/extraer_ordenes.php
├── modulo=dashboard → Lista pedidos principales
├── modulo=dashboardretiro → Pedidos retiro tienda
└── Retorna: Datos organizados por num_orden con detalles

GET/POST api/getpedido.php
├── method=num_orden → Detalle completo de orden
├── Parámetros: id, ruta
└── Retorna: Pedidos, totales, pagos
```

### **2. GESTIÓN DE PAGOS**
```
GET api/buscarPagos.php
├── criterio=rut → Buscar por RUT
├── criterio=numeroTransaccion → Por ID transacción
├── criterio=por_n_orden → Pagos de una orden
├── funcion=asociarPago → Asociar pago a orden
└── funcion=eliminarPago → Desasociar pago

GET api/anadirpago.php
├── Agregar pago manual
├── Soporte para Transbank/efectivo
```

### **3. ESTADOS Y PRODUCCIÓN**
```
POST api/actualizar_estado.php
├── id_detalle → ID del producto
├── estado_id → Nuevo estado
└── Actualiza pedido_detalle.estadopedido
```

---

## 📱 **FUNCIONALIDADES PRINCIPALES**

### **1. DASHBOARD INTERACTIVO**
- ✅ Tabla principal con DataTables
- ✅ Detalles expandibles por pedido
- ✅ Estados visuales con colores
- ✅ Botones de acción rápida
- ✅ Filtros y búsqueda avanzada

### **2. GESTIÓN DE ESTADOS**
- ✅ Cambio de estados en tiempo real
- ✅ Historial completo de cambios
- ✅ Asignación de tapiceros
- ✅ Observaciones por cambio

### **3. SISTEMA DE PAGOS**
- ✅ Integración bancaria automática
- ✅ Búsqueda inteligente de pagos
- ✅ Asociación automática/manual
- ✅ Validación de montos

### **4. CONTROL DE PRODUCCIÓN**
- ✅ Vista por tapicero
- ✅ Control de tiempos
- ✅ Gestión de materiales
- ✅ Seguimiento de calidad

### **5. LOGÍSTICA INTEGRADA**
- ✅ Creación automática de rutas
- ✅ Optimización de despachos
- ✅ Integración con empresas de envío
- ✅ Seguimiento en tiempo real

---

## 🔐 **SEGURIDAD Y CONTROL**

### **AUTENTICACIÓN**
```php
$_SESSION["s_usuario"]     // Usuario actual
$_SESSION["privilegios"]   // Nivel de acceso
```

### **CONTROL DE ACCESO**
- Validación por página según privilegios
- Restricciones a nivel de API
- Logs de actividad por usuario
- Control de modificaciones

### **AUDITORÍA**
- `pedido_etapas` → Historial completo
- `usuario` y `fecha` en todos los cambios
- `observacion` para detalles adicionales

---

## 📊 **INTEGRATIONS & APIS EXTERNAS**

### **1. SISTEMA BANCARIO**
```
AWS Lambda Functions:
├── Cartola automática desde bancos
├── Procesamiento de transferencias
├── Actualización automática de pagos
└── Almacenamiento en cartola_bancaria
```

### **2. EMPRESAS DE ENVÍO**
```
Starken API:
├── Cotización automática
├── Generación de órdenes
├── Seguimiento de envíos
└── Confirmación de entregas
```

### **3. WHATSAPP INTEGRATION**
```
Enlaces automáticos:
├── Generación de URLs de WhatsApp
├── Plantillas de mensajes
├── Integración con números de clientes
```

---

## 🎯 **MÉTRICAS Y KPIs**

### **INDICADORES CLAVE**
- Pedidos por estado
- Tiempo promedio de fabricación
- Eficiencia por tapicero
- Pagos pendientes/completados
- Entregas puntuales

### **REPORTES AUTOMÁTICOS**
- Dashboard en tiempo real
- Reportes de producción
- Estados financieros
- Análisis de ventas

---

## 🚀 **ROADMAP DE DESARROLLO**

### **FUNCIONALIDADES ACTUALES**
- ✅ Gestión completa de pedidos
- ✅ Control de producción
- ✅ Sistema de pagos
- ✅ Logística básica

### **PRÓXIMAS MEJORAS**
- 🔄 Dashboard mejorado (en desarrollo)
- 🔄 Modal avanzado con historial
- 📋 Sistema de notificaciones
- 📋 App móvil para tapiceros
- 📋 BI y analytics avanzados
- 📋 Integración con más bancos

---

## 📝 **DOCUMENTACIÓN TÉCNICA**

### **CONVENCIONES DE CÓDIGO**
- Variables PHP: snake_case
- Variables JS: camelCase
- Clases CSS: kebab-case
- Tablas DB: snake_case

### **ESTRUCTURA DE ARCHIVOS**
- Modularización por funcionalidad
- APIs separadas por dominio
- Vistas reutilizables
- Assets organizados por tipo

### **ESTÁNDARES DE BASE DE DATOS**
- IDs auto-incrementales
- Foreign keys documentadas
- Índices optimizados
- Campos de auditoría

---

## 🏁 **CONCLUSIÓN**

El sistema RespaldosChile es una **aplicación ERP completa** que maneja:

1. **Todo el ciclo de vida del producto** (pedido → fabricación → entrega)
2. **Gestión multi-usuario** con roles específicos
3. **Integración financiera** automatizada
4. **Control de producción** en tiempo real
5. **Logística optimizada** con múltiples carriers

**Base sólida para continuar desarrollo y mejoras incrementales.**
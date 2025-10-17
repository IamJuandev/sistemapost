# Sistema Post - Gestión de Inventario y Ventas

## Descripción General

Sistema Post es una aplicación completa de gestión de inventario y ventas desarrollada en Laravel 12, con Livewire 3+ y Volt, usando Tailwind CSS para la interfaz de usuario. La aplicación está especialmente diseñada para cumplir con los requisitos de facturación electrónica colombiana, incluyendo CUFE, códigos QR y gestión de responsabilidades tributarias.

## Características Principales

- **Gestión de Inventario**: Control completo de productos con código de barras, precios, costos y stock
- **Facturación Electrónica Colombiana**: Cumple con los requisitos de la DIAN para facturación electrónica (CUFE, QR, responsabilidades tributarias)
- **Gestión de Compras y Ventas**: Registro y seguimiento completo de transacciones
- **Autenticación Segura**: Con Laravel Fortify y autenticación de dos factores
- **Interfaz Moderna**: Interfaz de usuario interactiva con Livewire y estilizada con Tailwind CSS
- **Gestión de Clientes y Proveedores**: Con control de crédito y responsabilidades tributarias
- **Panel de Control**: Con métricas en tiempo real, alertas de bajo stock y ventas recientes
- **Soporte Multi-moneda**: Gestión de transacciones en diferentes divisas
- **Gestión de Créditos**: Control de límites de crédito y deudas pendientes de los clientes
- **Componentes de Formulario Reutilizables**: Conjunto de componentes Blade estandarizados para entrada de datos

## Tecnologías Utilizadas

- **Backend**: Laravel 12
- **Frontend**: Livewire 3+ con Volt
- **Estilos**: Tailwind CSS
- **Base de Datos**: MySQL
- **Autenticación**: Laravel Fortify con autenticación de dos factores
- **Servidor**: Compatible con Apache/Nginx

## Instalación

1. Clona el repositorio:
```bash
git clone <URL_DEL_REPOSITORIO>
```

2. Instala las dependencias de PHP:
```bash
composer install
```

3. Instala las dependencias de Node.js:
```bash
npm install
```

4. Configura las variables de entorno:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configura tu base de datos en el archivo `.env`

6. Ejecuta las migraciones:
```bash
php artisan migrate
```

7. Compila los assets:
```bash
npm run build
```

8. Inicia el servidor:
```bash
php artisan serve
```

## Componentes del Sistema

### Modelos Principales

- **CompanyInfo**: Almacena la información de la empresa para facturación electrónica
- **Product**: Gestión de productos con cálculo automático de precios
- **Category**: Categorización de productos
- **Supplier**: Gestión de proveedores con responsabilidades tributarias
- **Customer**: Gestión de clientes con control de crédito
- **Purchase**: Transacciones de compra con datos de facturación electrónica
- **Sale**: Transacciones de venta con métodos de pago
- **PurchaseItem**: Detalles de los artículos en compras
- **SaleItem**: Detalles de los artículos en ventas
- **Payment**: Registro de pagos relacionados con ventas
- **User**: Autenticación de usuarios

### Módulos del Sistema

#### 1. Autenticación y Seguridad
- Registro e inicio de sesión de usuarios
- Autenticación de dos factores
- Gestión de perfiles con actualización de información personal y contraseña
- Control de acceso basado en roles

#### 2. Gestión de Productos
- CRUD completo de productos
- Identificación por código de barras
- Cálculo automático del precio de venta según costo, impuestos y margen de ganancia
- Control de stock con alertas de bajo inventario
- Categorización de productos

#### 3. Gestión de Proveedores
- Gestión completa de información de proveedores
- Registro de responsabilidades tributarias
- Días de visita programables
- Información de contacto y ubicación

#### 4. Gestión de Clientes
- Perfiles de clientes con datos de contacto
- Límites de crédito y seguimiento de deudas
- Historial de compras
- Gestión de información fiscal

#### 5. Compras
- Registro de transacciones de compra
- Integración con facturación electrónica colombiana
- Generación automática de CUFE y códigos QR
- Control de proveedores y productos adquiridos
- Gestión de estados (pendiente, completada)

#### 6. Ventas
- Gestión completa de transacciones de venta
- Soporte para ventas al contado y crédito
- Generación automática de CUFE y códigos QR
- Control de métodos de pago y estados de factura
- Gestión de artículos vendidos

#### 7. Reportes y Dashboard
- Panel de control con métricas en tiempo real
- Información sobre productos, clientes y proveedores
- Ventas y compras del mes
- Alertas de bajo stock
- Listado de ventas recientes

### 8. Componentes de Formulario Reutilizables

El sistema incluye un conjunto de componentes Blade estandarizados para entrada de datos:

- **Input Component**: Campo de entrada de texto con validación, etiquetas y mensajes de error
- **Select Component**: Campo de selección con opciones personalizables, validación y mensajes de error
- **Textarea Component**: Área de texto multilinea con validación y mensajes de error
- **Button Component**: Botones con variantes estilísticas (primary, secondary, danger, success, warning), tamaños (sm, md, lg) y estados (cargando, deshabilitado)

Estos componentes se utilizan en todas las vistas de formularios del sistema para mantener consistencia visual y funcionalidad.

## Características Especiales de Facturación Electrónica Colombiana

El sistema incluye funcionalidades específicas para cumplir con los requisitos de facturación electrónica en Colombia:

- **CUFE (Código Único de Facturación Electrónica)**: Generación automática de códigos únicos
- **Códigos QR**: Visualización de códigos para verificación de facturas
- **Responsabilidades Tributarias**: Gestión de responsabilidades de contribuyentes
- **Datos de la DIAN**: Campos específicos para información de facturación electrónica
- **Prefijos de Factura**: Soporte para diferentes formatos de numeración

## Estructura del Proyecto

```
app/
├── Http/
├── Livewire/           # Componentes de Livewire organizados por módulos
│   ├── Actions/
│   ├── Categories/
│   ├── Customers/
│   ├── Products/
│   ├── Purchases/
│   ├── Reports/
│   ├── Sales/
│   └── Suppliers/
├── Models/             # Modelos Eloquent
└── Providers/
database/
├── factories/
├── migrations/         # Migraciones de base de datos
└── seeders/
resources/
├── css/
├── js/
└── views/              # Vistas Blade organizadas por componentes
    ├── components/     # Componentes Blade reutilizables
    │   └── form/       # Componentes de formulario
    ├── layouts/
    ├── livewire/
    └── partials/
routes/
├── web.php             # Rutas principales
└── auth.php            # Rutas de autenticación
```

## Características Técnicas

- **Arquitectura**: Seguimiento de patrones MVC y componentes Livewire
- **Seguridad**: Validación de formularios, protección CSRF, sanitización de datos
- **Relaciones de Datos**: Relaciones Eloquent bien definidas entre modelos
- **Optimización**: Carga eficiente de relaciones y consultas optimizadas para evitar N+1
- **Calculadora en Tiempo Real**: Cálculo automático de precios basado en costos, impuestos y márgenes
- **UI Responsiva**: Interfaz completamente responsive usando Tailwind CSS
- **Componentes Reutilizables**: Conjunto estandarizado de componentes Blade para formularios

## Contribuciones

Las contribuciones son bienvenidas. Por favor, siga estos pasos:

1. Haga un fork del proyecto
2. Cree una rama para su característica (`git checkout -b feature/Mejora`)
3. Haga commit de sus cambios (`git commit -m 'Agrega nueva característica'`)
4. Suba sus cambios (`git push origin feature/Mejora`)
5. Abra un Pull Request

## Licencia

Este proyecto está licenciado bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para más detalles.

## Soporte

Si tiene preguntas o necesita ayuda con la implementación, por favor contacte al equipo de desarrollo.
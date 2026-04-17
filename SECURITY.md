# 🔐 PROTECCIONES DE SEGURIDAD - HOBBYHUB

## Resumen de protecciones implementadas

### 1. Protección de carpetas sensibles en `.htaccess` (Apache)

#### `src/config/` - BLOQUEADA
**Contiene:**
- `database.php` - Credenciales y conexión a BD
- `user_functions.php` - Funciones críticas de autenticación

**Por qué bloqueada:** Evita que los atacantes accedan directamente a la configuración de la base de datos o funciones de seguridad.

#### `src/includes/` - BLOQUEADA
**Contiene:**
- `header.php` - Plantilla del encabezado
- `footer.php` - Plantilla del pie
- `sidebar.php` - Barra lateral
- `breadcrumbs.php` - Migajas de pan

**Por qué bloqueada:** Estos son archivos que solo se sirven como parte del template via `require_once`, no deben servirse directamente.

### 2. Archivos de mantención bloqueados en raíz

#### Armivos bloqueados:
- `setup_db.php` - Setup inicial de base de datos
- `verify_db.php` - Verificación de BD
- `update_admin_hash.php` - Actualización de hash de admin
- `hash.php` - Generador de hashes

**Por qué bloqueados:** Estos son scripts de desarrollo/administración que no deben ser públicos.

### 3. Desactivar listado de directorios

**Agregado:** `Options -Indexes` en `.htaccess`

**Por qué:** Evita que los usuarios vean la estructura de carpetas del proyecto.

### 4. Páginas de error personalizadas

Configuradas redirecciones a páginas de error en lugar de mostrar errores genéricos del servidor:
- 400, 401, 403, 404, 405, 500 → páginas personalizadas en `src/errors/`

### 5. Configuraciones para Nginx (alternativa)

Si despliegas en Nginx, ve el archivo `nginx-config.example` para configuración equivalente.

## Configuración del servidor web

### Para Apache (XAMPP, Shared Hosting, VPS)

**Archivos modificados:**
- `src/.htaccess` - Protecciones en carpeta src
- `.htaccess` (raíz) - Protecciones de archivos sensibles en raíz

**Si tienes acceso a VirtualHost**, usa la configuración en `apache-config.example`.

### Para Nginx

Ve el archivo `nginx-config.example` para una configuración completa usando bloques `location`.

## Protecciones a nivel PHP

Además de las protecciones del servidor web:

### 1. Roles basados en autenticación
- `src/config/user_functions.php`:
  - `requireLogin()` - Requiere sesión activa
  - `requireRole('admin')` - Requiere rol específico

### 2. Protecciones de CRUD
- `src/admin/create.php`, `edit.php`, `delete.php` - Requieren rol admin
- `src/api/posts.php` - Acciones create/update/delete requieren admin

### 3. Validaciones AJAX
- Todas las APIs validan el header `X-Requested-With: XMLHttpRequest`
- Previene acceso vía formularios en sitios maliciosos

## Próximos pasos de seguridad (opcional)

1. **Certificado SSL/TLS:** Usar HTTPS en producción
2. **Encabezados de seguridad:** 
   - X-Frame-Options (previene clickjacking)
   - X-Content-Type-Options (previene sniffing)
   - strict-transport-security (HSTS)
3. **Rate limiting:** Limitar intentos de login
4. **WAF (Web Application Firewall):** Usar ModSecurity en Apache
5. **Auditoría de logs:** Monitorear archivos de acceso/error
6. **Actualizar PHP:** Usar versión reciente y mantener dependencias actualizadas

## Checklist de verificación

- ✅ Carpeta `config/` bloqueada
- ✅ Carpeta `includes/` bloqueada
- ✅ Archivos de setup bloqueados
- ✅ Listado de directorios desactivado
- ✅ Roles de usuario implementados en PHP
- ✅ Páginas de error personalizadas
- ✅ CRUD protegido por roles


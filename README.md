# Proyecto Backend - Laravel API para Gestión de Usuarios y Productos

Este proyecto es un backend desarrollado en **Laravel**, que expone APIs RESTful para la gestión de **usuarios** y **productos**. Está diseñado para ser consumido por cualquier frontend, como la aplicación Angular asociada.

---

## **Tecnologías utilizadas**

- **Framework**: Laravel 10
- **Base de datos**: MySQL
- **Autenticación**: Laravel Sanctum
- **Almacenamiento**: Sistema de archivos (local storage)
- **Servidor**: Apache/Nginx (XAMPP, Laragon, etc.)
- **ORM**: Eloquent ORM
- **Gestión de tokens**: Bearer Tokens (Sanctum)
- **Versionado**: Git y GitHub

---

## **Instalación y configuración**

### 1. Clonar el repositorio

```bash
git clone https://github.com/MauricioLopezR12/proyecto-backend.git
cd proyecto-backend
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar variables de entorno

Crea un archivo `.env` a partir del ejemplo `.env.example`:

```bash
cp .env.example .env
```

Modifica las siguientes variables para configurar la base de datos:

```plaintext
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario
DB_PASSWORD=contraseña
```

### 4. Generar la clave de la aplicación

```bash
php artisan key:generate
```

### 5. Migrar la base de datos y ejecutar seeders

```bash
php artisan migrate --seed
```

### 6. Iniciar el servidor

```bash
php artisan serve
```

La aplicación estará disponible en: `http://127.0.0.1:8000`.

---

## **Rutas de la API**

### **Autenticación**

1. **Registro de usuario**  
   - **Método**: `POST`  
   - **Ruta**: `/api/register`  
   - **Descripción**: Registra un nuevo usuario.  
   - **Parámetros**:  
     - `name` (string, requerido)  
     - `email` (string, requerido, único)  
     - `password` (string, requerido, mínimo 8 caracteres)

   - **Ejemplo de solicitud**:  
     ```json
     {
       "name": "John Doe",
       "email": "john@example.com",
       "password": "password123"
     }
     ```

2. **Login de usuario**  
   - **Método**: `POST`  
   - **Ruta**: `/api/login`  
   - **Descripción**: Inicia sesión y genera un token de acceso.  
   - **Parámetros**:  
     - `email` (string, requerido)  
     - `password` (string, requerido)  

   - **Ejemplo de solicitud**:  
     ```json
     {
       "email": "john@example.com",
       "password": "password123"
     }
     ```

   - **Respuesta exitosa**:  
     ```json
     {
       "message": "Usuario logueado correctamente",
       "user": {
         "name": "John Doe",
         "email": "john@example.com",
         "image": "http://127.0.0.1:8000/storage/users/john.png"
       },
       "token": "token_generado"
     }
     ```

3. **Logout de usuario**  
   - **Método**: `POST`  
   - **Ruta**: `/api/logout`  
   - **Descripción**: Cierra sesión y elimina el token actual.  
   - **Requiere Token**: Sí (Bearer Token en el Header).  

---

### **Gestión de Productos**

1. **Obtener todos los productos**  
   - **Método**: `GET`  
   - **Ruta**: `/api/products`  
   - **Descripción**: Devuelve una lista de todos los productos.  
   - **Requiere Token**: Sí  

   - **Ejemplo de respuesta**:  
     ```json
     [
       {
         "id": 1,
         "name": "Manzana",
         "price": 2.50,
         "quantity": 20,
         "image": "http://127.0.0.1:8000/storage/products/manzana.jpg"
       },
       {
         "id": 2,
         "name": "Pera",
         "price": 3.00,
         "quantity": 15,
         "image": "http://127.0.0.1:8000/storage/products/pera.jpg"
       }
     ]
     ```

2. **Agregar un producto**  
   - **Método**: `POST`  
   - **Ruta**: `/api/products`  
   - **Descripción**: Crea un nuevo producto.  
   - **Requiere Token**: Sí  
   - **Parámetros**:  
     - `name` (string, requerido)  
     - `price` (float, requerido)  
     - `quantity` (integer, requerido)  
     - `image` (archivo, opcional)

3. **Actualizar un producto**  
   - **Método**: `POST` con `_method=PUT`  
   - **Ruta**: `/api/products/{id}`  
   - **Descripción**: Actualiza un producto existente.  
   - **Requiere Token**: Sí  

4. **Eliminar un producto**  
   - **Método**: `DELETE`  
   - **Ruta**: `/api/products/{id}`  
   - **Descripción**: Elimina un producto.  
   - **Requiere Token**: Sí  

---

## **Seguridad**

- **Laravel Sanctum** se utiliza para la autenticación mediante tokens Bearer.
- Se requiere un token válido en el Header `Authorization` para acceder a rutas protegidas.

---

## **Estructura del proyecto**

```plaintext
proyecto-backend/
│
├── app/                # Lógica del backend (Controladores, Modelos)
├── database/           # Migraciones y seeders
├── routes/             # Definición de rutas (api.php)
├── public/             # Archivos accesibles públicamente (imágenes, etc.)
├── storage/            # Archivos subidos
├── .env                # Configuración de entorno
├── composer.json       # Dependencias de Laravel
└── README.md           # Documentación del proyecto
```

---

## **Autor**

Proyecto desarrollado por **JorgeMauricioLopezReyes**.  

---



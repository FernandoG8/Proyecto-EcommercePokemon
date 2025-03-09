# 🍕 Pizzería E-commerce

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Acerca del Proyecto

Este proyecto es un sistema de e-commerce desarrollado en Laravel, especializado en la venta de pizzas en línea. El sistema incluye las siguientes funcionalidades principales:

- Catálogo de productos (pizzas, bebidas, complementos)
- Carrito de compras funcional
- Sistema de usuarios y autenticación
- Panel de administración
- Procesamiento de pedidos
- Historial de compras

## Requisitos Previos

- PHP >= 8.1
- Composer
- MySQL
- Node.js y npm
- Servidor web (Apache, Nginx o similar)

## Instalación

### 1. Clonar el Repositorio

bash
git clone https://github.com/tu-usuario/pizzeria-ecommerce.git
cd pizzeria-ecommerce


### 2. Instalar Dependencias

bash
composer install


### 3. Configurar el Entorno

Crear y configurar el archivo .env en la raíz del proyecto:

bash
cp .env.example .env


Editar el archivo .env con la configuración de tu base de datos:


DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Pizzeria
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña


### 4. Generar Clave de Aplicación

bash
php artisan key:generate


### 5. Configurar Almacenamiento

bash
php artisan storage:link


### 6. Crear Base de Datos

Crear una base de datos MySQL con el nombre Pizzeria.

### 7. Ejecutar Migraciones

bash
php artisan migrate


### 8. (Opcional) Cargar Datos de Prueba

bash
php artisan migrate:refresh --seed


### 9. Iniciar el Servidor

bash
php artisan serve


La aplicación estará disponible en: http://localhost:8000/
El panel de administrado se encuentra en http://localhost:8000/admin/products, debe ser logeado como Administrador para poder acceder a ella
Pero es necesario navega a http://localhost:8000/Inicio para acceder a la página principal

## Estructura del Proyecto

- app/ - Contiene los modelos, controladores y lógica de negocio
- database/ - Migraciones y seeders
- public/ - Archivos públicos (CSS, JS, imágenes)
- resources/ - Vistas, archivos de idioma y assets sin compilar
- routes/ - Definiciones de rutas
- tests/ - Tests automatizados

## Enlaces de Interés
- [Documemtación del Proyecto](https://docs.google.com/document/d/1qkylvoLV21Ce2uiN5mUZWdKNzYqT-1JyDQxAue80ECY/edit?usp=sharing))
- [Documentación de Laravel](https://laravel.com/docs)
- [Laracasts](https://laracasts.com)
- [Laravel News](https://laravel-news.com)
- [Laravel Forge](https://forge.laravel.com)
- [GitHub](https://github.com/laravel/laravel)

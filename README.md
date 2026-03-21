# Ecommerce Laravel 12 + MySQL con look & feel Wolmart

Este paquete entrega una base funcional de ecommerce con dos áreas:

1. **Portal de venta**
2. **Backoffice administrativo**

## Qué incluye

- Catálogo público
- Página de producto
- Carrito en sesión
- Checkout con creación de pedido
- Backoffice con dashboard
- CRUD de categorías
- CRUD de productos
- Listado y detalle de pedidos
- Listado y detalle de clientes
- Seeders con datos demo
- Assets visuales reutilizados del zip de referencia Wolmart

## Requisitos

- PHP 8.2+
- Composer 2+
- MySQL 8+

## Puesta en marcha

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## URLs

- Store: `/`
- Shop: `/shop`
- Cart: `/cart`
- Checkout: `/checkout`
- Backoffice: `/admin`

## Nota

Los assets y el look & feel fueron tomados del zip de referencia en `Sources`, y la lógica ecommerce fue construida en Laravel para que sirva como base real de desarrollo.

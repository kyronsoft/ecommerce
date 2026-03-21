<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $defaultStore = Store::create([
            'name' => 'La Tienda de Mi Abue',
            'owner_name' => 'Administrador',
            'email' => env('ADMIN_DEFAULT_EMAIL', 'jaruizr74@gmail.com'),
            'phone' => '3000000000',
            'location' => 'Bogotá, Colombia',
            'short_description' => 'Marketplace cálido para negocios y emprendimientos que quieren vender con una identidad cercana.',
            'description' => 'Tienda principal de referencia dentro de la aplicación. Sirve como ejemplo del perfil público que verá el cliente final en el marketplace.',
            'logo' => 'wolmart/assets/images/la-tienda-de-mi-abue-logo.png',
            'banner' => 'wolmart/assets/images/vendor/dokan/1.jpg',
            'website' => env('APP_URL'),
            'business_hours' => "Lunes a viernes: 8:00 - 18:00\nSábado: 8:00 - 13:00\nDomingo: atención digital",
            'highlights' => ['Mercado local', 'Emprendimientos', 'Compra segura'],
            'is_active' => true,
            'is_featured' => true,
        ]);

        $categories = collect([
            ['name' => 'Electrónica', 'image' => 'wolmart/assets/images/demos/demo1/categories/1.jpg'],
            ['name' => 'Moda', 'image' => 'wolmart/assets/images/demos/demo1/categories/2.jpg'],
            ['name' => 'Hogar', 'image' => 'wolmart/assets/images/demos/demo1/categories/3.jpg'],
            ['name' => 'Deportes', 'image' => 'wolmart/assets/images/demos/demo1/categories/4.jpg'],
            ['name' => 'Belleza', 'image' => 'wolmart/assets/images/demos/demo1/categories/5.jpg'],
            ['name' => 'Accesorios', 'image' => 'wolmart/assets/images/demos/demo1/categories/6.jpg'],
        ])->map(fn (array $item) => Category::create($item));

        $catalog = [
            ['category' => 'Electrónica', 'name' => 'Smart Watch Pro', 'sku' => 'ELE-001', 'price' => 349000, 'compare_price' => 399000, 'stock' => 22, 'featured' => true, 'image' => 'wolmart/assets/images/demos/demo11/products/smart-watch-1-300x338.jpg', 'gallery' => ['wolmart/assets/images/demos/demo11/products/smart-watch-1-300x338.jpg','wolmart/assets/images/demos/demo11/products/smart-watch-2-300x338.jpg']],
            ['category' => 'Electrónica', 'name' => 'Super Pixel Camera', 'sku' => 'ELE-002', 'price' => 1299000, 'compare_price' => 1459000, 'stock' => 8, 'featured' => true, 'image' => 'wolmart/assets/images/demos/demo11/products/super-pixel-camera-1-300x338.jpg', 'gallery' => ['wolmart/assets/images/demos/demo11/products/super-pixel-camera-1-300x338.jpg','wolmart/assets/images/demos/demo11/products/super-pixel-camera-4-300x338.jpg']],
            ['category' => 'Moda', 'name' => 'Gold Watch Edition', 'sku' => 'MOD-001', 'price' => 459000, 'compare_price' => 599000, 'stock' => 14, 'featured' => true, 'image' => 'wolmart/assets/images/demos/demo11/products/gold-watch-1-300x338.jpg', 'gallery' => ['wolmart/assets/images/demos/demo11/products/gold-watch-1-300x338.jpg','wolmart/assets/images/demos/demo11/products/gold-watch-2-300x338.jpg']],
            ['category' => 'Deportes', 'name' => 'Pair of Dumbbells', 'sku' => 'DEP-001', 'price' => 219000, 'compare_price' => 259000, 'stock' => 5, 'featured' => true, 'image' => 'wolmart/assets/images/demos/demo11/products/pair-of-dumbbells-1-300x338.jpg', 'gallery' => ['wolmart/assets/images/demos/demo11/products/pair-of-dumbbells-1-300x338.jpg','wolmart/assets/images/demos/demo11/products/pair-of-dumbbells-2-300x338.jpg']],
            ['category' => 'Electrónica', 'name' => 'Mobile Charger Dark Grey', 'sku' => 'ELE-003', 'price' => 89000, 'compare_price' => 109000, 'stock' => 35, 'featured' => false, 'image' => 'wolmart/assets/images/demos/demo11/products/dark-grey-mobile-charger-1-300x338.jpg', 'gallery' => ['wolmart/assets/images/demos/demo11/products/dark-grey-mobile-charger-1-300x338.jpg','wolmart/assets/images/demos/demo11/products/dark-grey-mobile-charger-2-300x338.jpg']],
            ['category' => 'Electrónica', 'name' => 'Professional Camera Set', 'sku' => 'ELE-004', 'price' => 2199000, 'compare_price' => 2499000, 'stock' => 4, 'featured' => false, 'image' => 'wolmart/assets/images/demos/demo11/products/professional-camera-set-2-300x338.jpg', 'gallery' => ['wolmart/assets/images/demos/demo11/products/professional-camera-set-2-300x338.jpg','wolmart/assets/images/demos/demo11/products/professional-camera-set-3-300x338.jpg']],
            ['category' => 'Moda', 'name' => 'Red Cap Sound Marker', 'sku' => 'MOD-002', 'price' => 119000, 'compare_price' => 149000, 'stock' => 17, 'featured' => false, 'image' => 'wolmart/assets/images/demos/demo11/products/red-cap-sound-marker-1.jpg', 'gallery' => ['wolmart/assets/images/demos/demo11/products/red-cap-sound-marker-1.jpg']],
            ['category' => 'Accesorios', 'name' => 'Friendly Product Pack', 'sku' => 'ACC-001', 'price' => 99000, 'compare_price' => 129000, 'stock' => 44, 'featured' => false, 'image' => 'wolmart/assets/images/demos/demo11/products/friedly-products/1.jpg', 'gallery' => ['wolmart/assets/images/demos/demo11/products/friedly-products/1.jpg','wolmart/assets/images/demos/demo11/products/friedly-products/2.jpg']],
        ];

        foreach ($catalog as $item) {
            $category = $categories->firstWhere('name', $item['category']);
            Product::create([
                'category_id' => $category->id,
                'store_id' => $defaultStore->id,
                'name' => $item['name'],
                'sku' => $item['sku'],
                'short_description' => 'Producto demo de ecommerce construido sobre la referencia visual Wolmart.',
                'description' => 'Este registro sirve como base para el portal público, el carrito, el checkout y el backoffice administrativo. Puedes reemplazar el contenido por tu catálogo real.',
                'price' => $item['price'],
                'compare_price' => $item['compare_price'],
                'stock' => $item['stock'],
                'is_active' => true,
                'is_featured' => $item['featured'],
                'image' => $item['image'],
                'gallery' => $item['gallery'],
            ]);
        }

        $customer = Customer::create([
            'first_name' => 'Javier',
            'last_name' => 'Ruiz',
            'email' => 'USER_EMAIL',
            'phone' => '3000000000',
            'city' => 'Bogotá',
            'address' => 'Calle Example 123',
        ]);

        $order = Order::create([
            'customer_id' => $customer->id,
            'number' => 'ORD-000001',
            'status' => 'processing',
            'subtotal' => 808000,
            'shipping' => 0,
            'tax' => 153520,
            'total' => 961520,
            'payment_method' => 'card',
            'shipping_address' => 'Calle Example 123, Bogotá',
            'notes' => 'Pedido demo inicial',
        ]);

        $products = Product::take(3)->get();
        foreach ($products as $product) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'quantity' => 1,
                'unit_price' => $product->price,
                'total' => $product->price,
            ]);
        }

        $this->call(AdminUserSeeder::class);
    }
}

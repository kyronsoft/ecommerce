<?php

namespace App\Support;

class EntrepreneurPlans
{
    public static function all(): array
    {
        return [
            [
                'slug' => 'inicia',
                'name' => 'Plan Inicia',
                'price' => 150000,
                'price_label' => '$150.000',
                'headline' => 'Ideal para empezar a mostrar tu marca dentro del ecommerce.',
                'features' => [
                    'banner_superior' => false,
                    'banner_inferior' => true,
                    'popup_salida' => true,
                    'newsletter' => false,
                    'destacados' => false,
                    'historias' => false,
                ],
            ],
            [
                'slug' => 'impulsa',
                'name' => 'Plan Impulsa',
                'price' => 300000,
                'price_label' => '$300.000',
                'headline' => 'Mas visibilidad para marcas que ya quieren empujar conversion y recordacion.',
                'features' => [
                    'banner_superior' => false,
                    'banner_inferior' => true,
                    'popup_salida' => true,
                    'newsletter' => true,
                    'destacados' => false,
                    'historias' => true,
                ],
            ],
            [
                'slug' => 'lidera',
                'name' => 'Plan Lidera',
                'price' => 600000,
                'price_label' => '$600.000',
                'headline' => 'La opcion de mayor impacto para protagonizar la vitrina comercial.',
                'features' => [
                    'banner_superior' => true,
                    'banner_inferior' => true,
                    'popup_salida' => true,
                    'newsletter' => true,
                    'destacados' => true,
                    'historias' => true,
                ],
            ],
        ];
    }

    public static function featureGroups(): array
    {
        return [
            [
                'label' => 'Web',
                'items' => [
                    ['key' => 'banner_superior', 'label' => 'Banner superior Home'],
                    ['key' => 'banner_inferior', 'label' => 'Banner inferior Home'],
                    ['key' => 'popup_salida', 'label' => 'Pop Up de salida x 24 horas'],
                ],
            ],
            [
                'label' => 'Email Marketing',
                'items' => [
                    ['key' => 'newsletter', 'label' => 'Recomendacion de la semana en newsletter'],
                    ['key' => 'destacados', 'label' => 'Zona de destacados en fechas especiales'],
                ],
            ],
            [
                'label' => 'Redes Sociales',
                'items' => [
                    ['key' => 'historias', 'label' => 'Historias en redes sociales'],
                ],
            ],
        ];
    }

    public static function find(string $slug): ?array
    {
        foreach (self::all() as $plan) {
            if ($plan['slug'] === $slug) {
                return $plan;
            }
        }

        return null;
    }
}

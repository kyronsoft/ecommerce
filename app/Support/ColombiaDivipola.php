<?php

namespace App\Support;

class ColombiaDivipola
{
    protected static ?array $catalog = null;

    public static function all(): array
    {
        if (self::$catalog !== null) {
            return self::$catalog;
        }

        $path = resource_path('data/colombia-divipola-dane.json');
        $decoded = json_decode((string) file_get_contents($path), true);

        self::$catalog = is_array($decoded) ? $decoded : [];

        return self::$catalog;
    }

    public static function departments(): array
    {
        return array_map(
            static fn (array $department) => [
                'code' => (string) ($department['code'] ?? ''),
                'name' => (string) ($department['name'] ?? ''),
            ],
            self::all()
        );
    }

    public static function citiesByDepartment(): array
    {
        $cities = [];

        foreach (self::all() as $department) {
            $code = (string) ($department['code'] ?? '');

            if ($code === '') {
                continue;
            }

            $cities[$code] = array_map(
                static fn (array $city) => [
                    'code' => (string) ($city['code'] ?? ''),
                    'name' => (string) ($city['name'] ?? ''),
                ],
                $department['cities'] ?? []
            );
        }

        return $cities;
    }

    public static function findDepartment(string $code): ?array
    {
        foreach (self::all() as $department) {
            if ((string) ($department['code'] ?? '') === $code) {
                return $department;
            }
        }

        return null;
    }

    public static function findCity(string $departmentCode, string $cityCode): ?array
    {
        $department = self::findDepartment($departmentCode);

        if (! $department) {
            return null;
        }

        foreach ($department['cities'] ?? [] as $city) {
            if ((string) ($city['code'] ?? '') === $cityCode) {
                return $city;
            }
        }

        return null;
    }
}

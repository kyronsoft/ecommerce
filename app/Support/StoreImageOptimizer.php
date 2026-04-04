<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use RuntimeException;

class StoreImageOptimizer
{
    public static function optimizeToWebp(UploadedFile $file, string $field): string
    {
        if (! $file->isValid()) {
            throw new RuntimeException('La imagen no se pudo cargar correctamente.');
        }

        $contents = $file->get();
        $image = @imagecreatefromstring($contents);

        if (! $image) {
            throw new RuntimeException('No fue posible procesar la imagen seleccionada.');
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        [$targetWidth, $targetHeight] = self::targetDimensions(
            imagesx($image),
            imagesy($image),
            $field,
        );

        if ($targetWidth !== imagesx($image) || $targetHeight !== imagesy($image)) {
            $resized = imagecreatetruecolor($targetWidth, $targetHeight);

            imagealphablending($resized, false);
            imagesavealpha($resized, true);

            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefilledrectangle($resized, 0, 0, $targetWidth, $targetHeight, $transparent);

            imagecopyresampled(
                $resized,
                $image,
                0,
                0,
                0,
                0,
                $targetWidth,
                $targetHeight,
                imagesx($image),
                imagesy($image),
            );

            imagedestroy($image);
            $image = $resized;
        }

        ob_start();
        imagewebp($image, null, self::qualityFor($field));
        $binary = (string) ob_get_clean();

        imagedestroy($image);

        if ($binary === '') {
            throw new RuntimeException('No fue posible optimizar la imagen seleccionada.');
        }

        return $binary;
    }

    protected static function targetDimensions(int $width, int $height, string $field): array
    {
        $maxWidth = $field === 'banner' ? 2200 : 1200;
        $maxHeight = $field === 'banner' ? 1200 : 1200;

        if ($width <= $maxWidth && $height <= $maxHeight) {
            return [$width, $height];
        }

        $ratio = min($maxWidth / $width, $maxHeight / $height);

        return [
            max(1, (int) round($width * $ratio)),
            max(1, (int) round($height * $ratio)),
        ];
    }

    protected static function qualityFor(string $field): int
    {
        return $field === 'banner' ? 86 : 88;
    }
}

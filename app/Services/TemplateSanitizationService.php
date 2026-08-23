<?php

namespace App\Services;

use App\Models\Dinas;

class TemplateSanitizationService
{
    /**
     * Sanitize the template HTML and replace the logo URL with a placeholder.
     *
     * @param string $html
     * @param Dinas|null $dinas
     * @return string
     */
    public function execute(string $html, ?Dinas $dinas): string
    {
        // Cara paling andal: deteksi via data-logo marker yang ditambahkan oleh JS editor
        $sanitizedHtml = preg_replace('/<img[^>]*data-logo=["\']1["\'][^>]*\/?>/i', '[LOGO_DINAS]', $html);

        // Fallback: cari img yang src-nya mengarah ke /storage/ path logo dinas
        if ($dinas && $dinas->logo) {
            $logoFileName = basename($dinas->logo);
            $sanitizedHtml = preg_replace(
                '/<img[^>]*src=["\'][^"\']*' . preg_quote($logoFileName, '/') . '[^"\']*["\'][^>]*\/?>/i',
                '[LOGO_DINAS]',
                $sanitizedHtml
            );
        }

        return $sanitizedHtml;
    }
}

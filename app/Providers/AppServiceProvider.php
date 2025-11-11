<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forzar HTTPS cuando la app está detrás de proxy/CDN que sirve en HTTPS
        // Evita contenido mixto al generar URLs (por ejemplo, para imágenes)
        try {
            $proto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ($_SERVER['HTTPS'] ?? null);
            if (($proto && stripos((string)$proto, 'https') !== false) || app()->environment('production')) {
                URL::forceScheme('https');
            }
        } catch (\Throwable $e) {
            // Silenciar: si no se puede determinar, no forzamos
        }
    }
}

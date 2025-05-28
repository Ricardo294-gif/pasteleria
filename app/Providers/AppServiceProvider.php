<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;

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
        Schema::defaultStringLength(191);

        // Configurar el correo del administrador
        if (env('ADMIN_EMAIL')) {
            Config::set('mail.admin', env('ADMIN_EMAIL'));
        }

        // Forzar HTTPS en producción
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // Definir una URL para acceder a los archivos en storage
        URL::macro('storage', function ($path) {
            return url('storage/' . $path);
        });

        // Añadir directiva de Blade para manejar múltiples rutas de imágenes
        Blade::directive('productoImagen', function ($expression) {
            return "<?php echo asset(
                file_exists(public_path('img/productos/' . {$expression}))
                    ? 'img/productos/' . {$expression}
                    : (file_exists(public_path('storage/productos/' . {$expression}))
                        ? 'storage/productos/' . {$expression}
                        : (file_exists(public_path('storage/' . {$expression}))
                            ? 'storage/' . {$expression}
                            : 'img/productos/default.jpg'))); ?>";
        });
    }
}

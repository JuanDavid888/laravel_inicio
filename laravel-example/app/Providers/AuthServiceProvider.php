<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider AS ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    // Policia de las politicas
    protected $policies = [
        //
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Definir Tokens
        Passport::tokensExpireIn(now()->addHours(2));
        Passport::refreshTokensExpireIn(now()->addDay(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        // Scopes (Alcances - Permisos)
        // recurso.accion
        Passport::tokensCan([
            'posts.read' => 'Leer posts',
            'posts.write' => 'Crear o Editar posts',
            'posts.delete' => 'Puede eliminar posts',
            'posts.admin' => 'Acceso VIP'
        ]);

        Passport::defaultScopes([
            'posts.read',
        ]);
    }
}

<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider AS ServiceProvider;
use Laravel\Passport\Passport;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Policies\PostPolicy;

class AuthServiceProvider extends ServiceProvider
{
    // Policia de las politicas
    protected $policies = [
        PostPolicy::class
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

        // Gate
        Gate::define('view-health', function(User $user) {
            return $user->hasRole(['editor', 'viewer']);
        });

        Gate::define('view-health-admin', function(User $user) {
            return $user->hasRole(['editor', 'admin']) || $user->tokenCan('posts.admin');
        });

        // No recomendado, pero útil para pruebas
        Gate::before(function(User $user, string $ability) {
            return $user->hasRole(['admin']) ? true : null; // true -> concede los permisos (ability)
        });

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

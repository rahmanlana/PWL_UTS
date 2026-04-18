<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher;

class CustomAuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Auth::provider('username-eloquent', function ($app, array $config) {
            return new class($app['hash'], $config['model']) extends EloquentUserProvider {
                public function retrieveByCredentials(array $credentials)
                {
                    $query = $this->createModel()->newQuery();
                    foreach ($credentials as $key => $value) {
                        if ($key === 'password') continue;
                        $query->where($key, $value);
                    }
                    return $query->first();
                }
            };
        });
    }
}

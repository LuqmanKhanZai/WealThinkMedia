<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use App\Repositories\Configuration\City\CityInterface;
use App\Repositories\Configuration\City\CityRepository;
use App\Repositories\Configuration\Category\CategoryInterface;
use App\Repositories\Configuration\Category\CategoryRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Response::macro('success', function (string $value) {
            return response()->json([
                'msg' => $value,
                'status' => 200,
            ]);
            // return Response::make(strtoupper($value));
        });

        Response::macro('error', function (string $value) {
            return response()->json([
                'msgErr' => $value,
                'status' => 201,
            ], 406);
            // return Response::make(strtoupper($value));
        });

        Response::macro('notfound', function (string $value) {
            return response()->json([
                'msg' => $value,
                'status' => 404,
            ], 404);
            // return Response::make(strtoupper($value));
        });

        Response::macro('exist', function (string $value) {
            return response()->json([
                'msg' => $value,
                'status' => 409,
            ], 409);
        });

        Response::macro('warning', function (string $value) {
            return response()->json([
                'msgWarning' => $value,
                'status' => 202,
            ]);
            // return Response::make(strtoupper($value));
        });


        Schema::defaultStringLength(191);
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(CityInterface::class, CityRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

    }
}

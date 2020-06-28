<?php
/**
 * Copyright (C) 2020  Peter Lembke, CharZam soft
 * the program is distributed under the terms of the GNU General Public License
 *
 * KeyValueServiceProvider.php is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * KeyValueServiceProvider.php is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with KeyValueServiceProvider.php.	If not, see <https://www.gnu.org/licenses/>.
 */

namespace PeterLembke\KeyValue;

use PeterLembke\KeyValue\Console\Commands\Read;
use PeterLembke\KeyValue\Console\Commands\Write;
use Illuminate\Support\ServiceProvider;

/**
 * Class KeyValueServiceProvider
 * @package PeterLembke\KeyValue
 * This is the main file in your package
 * This file are found by Laravel because it is registered in your package composer.json
 * In this file you register everything you want to work in your package
 * @see https://laravel.com/docs/7.x/packages
 * From the above documentation: "Packages are the primary way of adding functionality to Laravel"
 */
class KeyValueServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        // Other packages can bind your interface and override your class
        $this->app->bind(
            'PeterLembke\KeyValue\Repositories\KeyValueRepositoryInterface',
            'PeterLembke\KeyValue\Repositories\KeyValueRepository'
        );

        // First mention the interface THEN the class
        $this->app->bind(
            'PeterLembke\KeyValue\MyLogic\MyLogicInterface',
            'PeterLembke\KeyValue\MyLogic\MyLogic'
        );

        // Have these last. If they have a constructor that use one of the above classes then they must first be bound.
        $this->app->make('PeterLembke\KeyValue\Controllers\TestController');
        $this->app->make('PeterLembke\KeyValue\Controllers\AnotherController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Read::class,
                Write::class,
            ]);
        }

        // $this->loadViewsFrom(__DIR__.'/views', 'timezones');

        /*
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/laraveldaily/timezones'),
        ]);
        */
    }
}

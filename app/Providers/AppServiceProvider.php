<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('newPaginate', function ($perPage = null) {
            $perPage = $perPage ?: intval(request('limit')) ?: $this->model->getPerPage();
            if (!request()->has('limit')) return $this->get();
            return $this->paginate($perPage);
        });
    }
}

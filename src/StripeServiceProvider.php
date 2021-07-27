<?php


namespace Netmarket\Stripe;


use Carbon\Laravel\ServiceProvider;


class StripeServiceProvider extends ServiceProvider
{

    // will bootstrap the package

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__. '/views', 'stripe');

    }

    public function register()
    {

    }
}

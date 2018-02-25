<?php

namespace DervisGroup\Pesa\Mpesa;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use DervisGroup\Pesa\Commands\Registra;
use DervisGroup\Pesa\Mpesa\Library\BulkSender;
use DervisGroup\Pesa\Mpesa\Library\Core;
use DervisGroup\Pesa\Mpesa\Library\IdCheck;
use DervisGroup\Pesa\Mpesa\Library\RegisterUrl;
use DervisGroup\Pesa\Mpesa\Library\StkPush;

class MpesaServiceProvider extends ServiceProvider
{
    /**
     * @throws \DervisGroup\Pesa\Exceptions\MpesaException
     */
    public function register()
    {
        $core = new Core(new Client(['http_errors' => false]));
        $this->app->bind(Core::class, function () use ($core) {
            return $core;
        });
        $this->commands(
            [
                Registra::class
            ]
        );

        $this->registerFacades();
    }

    /**
     * Register facade accessors
     */
    private function registerFacades()
    {
        $this->app->bind(
            'mpesa_stk', function () {
                return $this->app->make(StkPush::class);
            }
        );
        $this->app->bind(
            'mpesa_registrar', function () {
                return $this->app->make(RegisterUrl::class);
            }
        );
        $this->app->bind(
            'mpesa_identity', function () {
                return $this->app->make(IdCheck::class);
            }
        );
        $this->app->bind(
            'mpesa_b2c', function () {
                return $this->app->make(BulkSender::class);
            }
        );
    }
}
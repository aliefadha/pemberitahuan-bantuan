<?php

namespace App\Providers;

use App\Services\Notifications\Channels\WhatsAppChannel;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Notification::extend('whatsapp', function ($app) {
            return new WhatsAppChannel($app->make(WhatsAppService::class));
        });
    }
}

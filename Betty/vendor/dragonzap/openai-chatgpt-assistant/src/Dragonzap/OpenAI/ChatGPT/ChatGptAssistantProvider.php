<?php

/*
 * Licensed under GPLv2
 * Author: Daniel McCarthy
 * Email: daniel@dragonzap.com
 * Dragon Zap Publishing
 * Website: https://dragonzap.com
 */

namespace Dragonzap\OpenAI\ChatGPT;

use Illuminate\Support\ServiceProvider;

class ChatGptAssistantProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/dragonzap.php' => config_path('dragonzap.php'),
        ], 'config');
    
        $this->mergeConfigFrom(
            __DIR__.'/config/dragonzap.php', 'dragonzap'
        );
    }
    
    public function register()
    {
        // Code for bindings, if necessary
    }
}


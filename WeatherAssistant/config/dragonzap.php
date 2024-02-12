<?php
use App\Assistants\BettyAssistant;
use App\Assistants\WeatherAssistant;

/*
 * Licensed under GPLv2
 * Author: Daniel McCarthy
 * Dragon Zap Publishing
 * Website: https://dragonzap.com
 */

 
return [
    'openai' => [
        'key' => env('OPENAI_CHATGPT_KEY', 'default-key-value')
    ],

    'assistants' => [
        'betty' => [
            'class' => BettyAssistant::class
        ],
        'weather' => [
            'class' => WeatherAssistant::class
        ]
    ]
];

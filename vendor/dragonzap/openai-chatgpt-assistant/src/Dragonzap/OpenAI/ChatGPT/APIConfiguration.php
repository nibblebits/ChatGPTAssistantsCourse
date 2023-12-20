<?php

/*
 * Licensed under GPLv2
 * Author: Daniel McCarthy
 * Email: daniel@dragonzap.com
 * Dragon Zap Publishing
 * Website: https://dragonzap.com
 */


namespace Dragonzap\OpenAI\ChatGPT;

/**
 * This class represents the API configuration for talking with OpenAI CHATGPT 
 */
class APIConfiguration
{
    protected $api_key;
    public function __construct($api_key=NULL)
    {
        $this->api_key = $api_key;
    }
   
    public function getApiKey()
    {
        return $this->api_key;
    }
    
}

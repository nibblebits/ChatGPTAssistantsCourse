<?php

/*
 * Licensed under GPLv2
 * Author: Daniel McCarthy
 * Email: daniel@dragonzap.com
 * Dragon Zap Publishing
 * Website: https://dragonzap.com
 */

namespace Dragonzap\OpenAI\ChatGPT;
use JsonSerializable;

/**
 * Represents a function call that GPT has made.
 * This class implements the JsonSerializable interface to enable easy JSON serialization.
 */
class GPTFunctionCall implements JsonSerializable
{
    /**
     * @var string The name of the function that was called.
     */
    protected $function_name;

    /**
     * @var array The arguments passed to the function.
     */
    protected array $function_arguments;

    /**
     * @var string|array The response from the function call, can be either string or array.
     */
    protected string|array $response;

    /**
     * Constructor for the GPTFunctionCall class.
     *
     * @param string $function_name The name of the function.
     * @param array $function_arguments The arguments passed to the function.
     * @param string|array $response The response from the function.
     */
    public function __construct(string $function_name, array $function_arguments, string|array $response)
    {
        $this->function_name = $function_name;
        $this->function_arguments = $function_arguments;
        $this->response = $response;
    }

    /**
     * Gets the name of the function.
     *
     * @return string The name of the function.
     */
    public function getFunctionName() : string
    {
        return $this->function_name;
    }

    /**
     * Gets the arguments passed to the function.
     *
     * @return array The arguments of the function.
     */
    public function getFunctionArguments() : array
    {
        return $this->function_arguments;
    }

    /**
     * Gets the response from the function.
     *
     * @return string|array The response, which could be a string or an array.
     */
    public function getFunctionResponse() : string|array
    {
        return $this->response;
    }

    /**
     * Specify data which should be serialized to JSON.
     * This method is called automatically when the object is serialized with json_encode.
     *
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array The array representation of the object for JSON serialization.
     */
    public function jsonSerialize(): array
    {
        return [
            'function_name' => $this->getFunctionName(),
            'function_arguments' => $this->getFunctionArguments(),
            'response' => $this->getFunctionResponse()
        ];
    }
}

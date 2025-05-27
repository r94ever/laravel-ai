<?php

namespace R94ever\PHPAI\Facades;

use Illuminate\Support\Facades\Facade;
use R94ever\PHPAI\Services\Chatbot;

/**
 * @method static Chatbot chatbot()
 */
class AI extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \R94ever\PHPAI\AI::class;
    }
}
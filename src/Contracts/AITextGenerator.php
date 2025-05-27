<?php

namespace R94ever\PHPAI\Contracts;

interface AITextGenerator
{
    public function generate(): AITextGeneratorResponse;
}
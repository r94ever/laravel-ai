<?php

namespace R94ever\PHPAI\Contracts;

interface EmbeddingProvider
{
    /**
     * Generate embeddings for the given text content.
     *
     * @param string $text
     * @return array
     */
    public function embedContent(string $text): array;
}

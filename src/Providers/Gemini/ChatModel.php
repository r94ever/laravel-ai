<?php

namespace R94ever\PHPAI\Providers\Gemini;

enum ChatModel: string
{
    /**
     * Best model in terms of price-performance, offering well-rounded capabilities.
     * Gemini 2.5 Flash rate limits are more restricted since it is an experimental / preview model.
     */
    case Gemini2_5FlashPreview05_20 = 'gemini-2.5-flash-preview-05-20';

    /**
     * Gemini 2.5 Pro is state-of-the-art thinking model, capable of reasoning over complex problems in
     * code, math, and STEM, as well as analyzing large datasets, codebases, and documents using long context.
     * Gemini 2.5 Pro rate limits are more restricted since it is a preview model.
     */
    case Gemini2_5ProPreview = 'gemini-2.5-pro-preview-05-06';

    /**
     * Gemini 2.0 Flash delivers next-gen features and improved capabilities,
     * including superior speed,native tool use, and a 1M token context window.
     */
    case Gemini2_5Flash = 'gemini-2.0-flash';

    /**
     * A Gemini 2.0 Flash model optimized for cost efficiency and low latency.
     */
    case Gemini2_0FlashLite = 'gemini-2.0-flash-lite';

    /**
     * Gemini 1.5 Flash is a fast and versatile multimodal model for scaling across diverse tasks.
     */
    case Gemini1_5Flash = 'gemini-1.5-flash';

    /**
     * Gemini 1.5 Flash-8B is a small model designed for lower intelligence tasks.
     */
    case Gemini1_5Flash8b = 'gemini-1.5-flash-8b';

    /**
     * Gemini 1.5 Pro is a mid-size multimodal model that is optimized for a wide-range of reasoning tasks.
     * 1.5 Pro can process large amounts of data at once, including 2 hours of video, 19 hours of audio,
     * codebases with 60,000 lines of code, or 2,000 pages of text.
     */
    case Gemini1_5Pro = 'gemini-1.5-pro';

    /**
     * The Gemini 2.0 Flash Live model works with the Live API to enable low-latency bidirectional
     * voice and video interactions with Gemini. The model can process text, audio, and video input,
     * and it can provide text and audio output.
     */
    case Gemini2_0FlashLive = 'gemini-2.0-flash-live-001';
}

<?php

declare(strict_types=1);

namespace RunApi\RunwayAleph;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\RunwayAleph\Resources\EditVideo;

/**
 * Provides Runway Aleph prompt-driven video editing.
 *
 * Exposes typed model resources plus the universal files and account resources.
 */
final class RunwayAlephClient extends BaseClient
{
    /**
     * Edit video operations.
     */
    public readonly EditVideo $editVideo;

    /**
     * Create a Runway Aleph client with optional API key, base URL, and transport overrides.
     */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->editVideo = EditVideo::fromHttp($this->http);
    }
}

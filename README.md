# Runway Aleph PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/runway-aleph)](https://packagist.org/packages/runapi-ai/runway-aleph)
[![License](https://img.shields.io/github/license/runapi-ai/runway-aleph-php)](https://github.com/runapi-ai/runway-aleph-php/blob/main/LICENSE)

The Runway Aleph PHP SDK is the Composer package for Runway Aleph on RunAPI. Use it when your PHP application needs associative-array request bodies, task status lookup, polling helpers, file helpers, and consistent RunAPI errors.

## Install

```bash
composer require runapi-ai/runway-aleph
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\RunwayAleph\RunwayAlephClient;

$client = new RunwayAlephClient(); // reads RUNAPI_API_KEY

$task = $client->editVideo->create([
    'model' => 'runway-aleph',
    'prompt' => 'A precise product render on white marble',
    'source_video_url' => 'https://cdn.runapi.ai/public/samples/video.mp4',
]);

$status = $client->editVideo->get($task->id);

$result = $client->editVideo->run([
    'model' => 'runway-aleph',
    'prompt' => 'A serene mountain lake at dawn',
    'source_video_url' => 'https://cdn.runapi.ai/public/samples/video.mp4',
]);

echo $result->videos[0]->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest task state, and `run()` when a script should create and poll until completion. In web request handlers, prefer `create()` plus webhook or later `get()` polling so a worker is not held open.

Returned file URLs are temporary. Download and store generated files in your own durable storage within the retention window.

All SDK exceptions inherit from `RunApi\Core\Errors\RunApiException`, including validation, authentication, rate limit, task failure, and task timeout errors.

## Links

- Model page: https://runapi.ai/models/runway-aleph
- SDK docs: https://runapi.ai/docs#sdk-runway-aleph
- Product docs: https://runapi.ai/docs#runway-aleph
- Pricing and rate limits: https://runapi.ai/models/runway-aleph
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/runway-aleph-php
- Multi-language SDK repository: https://github.com/runapi-ai/runway-aleph-sdk

## License

Licensed under the Apache License, Version 2.0.

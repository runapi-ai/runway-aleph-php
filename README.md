# Runway Aleph PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/runway-aleph)](https://packagist.org/packages/runapi-ai/runway-aleph)
[![License](https://img.shields.io/github/license/runapi-ai/runway-aleph-php)](https://github.com/runapi-ai/runway-aleph-php/blob/main/LICENSE)

The Runway Aleph PHP SDK is the language-specific package for Runway Aleph
on RunAPI. Use this package when your application needs Composer installs,
associative-array request bodies, task status lookup, and consistent RunAPI
errors in PHP.

This README is the PHP package guide for the public `runway-aleph-php` split
repository. For model details, use https://runapi.ai/models/runway-aleph; for API
reference, use https://runapi.ai/docs/api/runway-aleph/edit-video; for SDK docs, use
https://runapi.ai/docs/resources/sdks.

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
    'aspect_ratio' => '16:9',
    'prompt' => 'A precise product render on white marble',
    'reference_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
    'seed' => 1,
    'source_video_url' => 'https://cdn.runapi.ai/public/samples/video.mp4',
    'watermark' => 'sample',
]);

$status = $client->editVideo->get($task->id);

$result = $client->editVideo->run([
    'model' => 'runway-aleph',
    'aspect_ratio' => '16:9',
    'prompt' => 'A serene mountain lake at dawn',
    'reference_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
    'seed' => 1,
    'source_video_url' => 'https://cdn.runapi.ai/public/samples/video.mp4',
    'watermark' => 'sample',
]);

echo $result->videos[0]->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest
task state, and `run()` when a script should create and poll until completion.
In web request handlers, prefer `create()` plus webhook or later `get()`
polling so a worker is not held open.


RunAPI-generated file URLs are temporary. Download and store generated files
in your own durable storage within the retention window; do not treat returned
URLs as long-term assets.

## Language notes

Pass request parameters as associative arrays with snake_case keys. The
available resources are `editVideo`. Keep `RUNAPI_API_KEY` in the environment
or your secret manager; never commit API keys or callback secrets.

## Links

- Model page: https://runapi.ai/models/runway-aleph
- SDK docs: https://runapi.ai/docs/resources/sdks
- Product docs: https://runapi.ai/docs/api/runway-aleph/edit-video
- Pricing and rate limits: https://runapi.ai/models/runway-aleph
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/runway-aleph-php
- Multi-language SDK repository: https://github.com/runapi-ai/runway-aleph-sdk

## License

Licensed under the Apache License, Version 2.0.

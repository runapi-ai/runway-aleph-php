<?php

declare(strict_types=1);

namespace RunApi\RunwayAleph\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\RunwayAleph\Models\CompletedVideoTaskResponse;
use RunApi\RunwayAleph\Resources\EditVideo;
use RunApi\RunwayAleph\RunwayAlephClient;

final class RunwayAlephClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new RunwayAlephClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(EditVideo::class, $client->editVideo);
    }

    public function testCreatePostsCompactedBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
        ]);
        $client = new RunwayAlephClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->editVideo->create([
            'model' => 'runway-aleph',
            'aspect_ratio' => '16:9',
            'prompt' => 'A product render',
            'source_video_url' => 'https://cdn.runapi.ai/public/samples/video.mp4',
            'callback_url' => '',
            'seed' => null,
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/runway_aleph/edit_video', $transport->requests[0]->getUri()->getPath());
        self::assertSame('runway-aleph', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('seed', $body);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","videos":[{"url":"https://file.runapi.ai/result"}],"extra_field":"kept"}'),
        ]);
        $client = new RunwayAlephClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->editVideo->run([
            'model' => 'runway-aleph',
            'aspect_ratio' => '16:9',
            'prompt' => 'A product render',
            'source_video_url' => 'https://cdn.runapi.ai/public/samples/video.mp4',
        ]);

        self::assertInstanceOf(CompletedVideoTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->videos[0]->url);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/runway_aleph/edit_video/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testCompletedResponseRequiresResultFiles(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed"}'),
        ]);
        $client = new RunwayAlephClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('videos is required');

        $client->editVideo->run([
            'model' => 'runway-aleph',
            'aspect_ratio' => '16:9',
            'prompt' => 'A product render',
            'source_video_url' => 'https://cdn.runapi.ai/public/samples/video.mp4',
        ]);
    }

    public function testRejectsInvalidContractEnum(): void
    {
        $client = new RunwayAlephClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('aspect_ratio must be one of the allowed values');

        $client->editVideo->create([
        'model' => 'runway-aleph',
        'prompt' => 'A product render',
        'source_video_url' => 'https://cdn.runapi.ai/public/samples/video.mp4',
        'aspect_ratio' => 'not-valid',
        ]);
    }

    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_2"}'),
        ]);
        $client = new RunwayAlephClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->editVideo->create([
            'model' => 'runway-aleph',
            'aspect_ratio' => '16:9',
            'prompt' => 'A product render',
            'source_video_url' => 'https://cdn.runapi.ai/public/samples/video.mp4',
        ]);

        self::assertSame('/api/v1/runway_aleph/edit_video', $transport->requests[0]->getUri()->getPath());
    }
}

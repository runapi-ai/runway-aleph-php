<?php

declare(strict_types=1);

namespace RunApi\RunwayAleph\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\RunwayAleph\Models\CompletedVideoTaskResponse;
use RunApi\RunwayAleph\Models\VideoTaskResponse;
use RunApi\RunwayAleph\Types;

/**
 * Transforms an existing video using a text prompt. Optionally provide a reference_image_url to guide the visual style of the transformation.
 */
readonly class EditVideo extends TypedConfiguredResource
{
    /**
     * Submits a video-editing task and returns immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   source_video_url: string,
     *   aspect_ratio?: string,
     *   callback_url?: string
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Fetches the current status of a video-editing task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): VideoTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var VideoTaskResponse $response */
        return $response;
    }

    /**
     * Submits a video-editing task and polls until it completes.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   source_video_url: string,
     *   aspect_ratio?: string,
     *   callback_url?: string
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedVideoTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedVideoTaskResponse $response */
        return $response;
    }

    /**
     * Create the resource using the shared RunAPI HTTP transport.
     */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/runway_aleph/edit_video',
            'runway-aleph/edit-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
            Types::EDIT_VIDEO_MODELS,
            'edit-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
        );
    }
}

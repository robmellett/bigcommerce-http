<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create multiple Channel Metafields in a single request.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-batch-metafields#create-multiple-metafields
 */
class CreateChannelsMetafieldsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, array<string, mixed>>  $data  e.g. [['namespace' => 'Sales Department', 'key' => 'Location', 'value' => '4HG', 'permission_set' => 'write', 'resource_type' => 'channel', 'resource_id' => 1]]
     */
    public function __construct(
        protected readonly array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/channels/metafields';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}

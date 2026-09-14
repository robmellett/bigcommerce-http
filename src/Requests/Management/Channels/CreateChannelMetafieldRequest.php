<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Channel Metafield.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-metafields#create-a-channel-metafield
 */
class CreateChannelMetafieldRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly int $channelId,
        protected readonly string $namespace,
        protected readonly string $key,
        protected readonly string $value,
        protected readonly string $permissionSet,
        protected readonly ?string $description = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/metafields";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return array_filter([
            'namespace' => $this->namespace,
            'key' => $this->key,
            'value' => $this->value,
            'permission_set' => $this->permissionSet,
            'description' => $this->description,
        ], static fn (mixed $value): bool => $value !== null);
    }
}

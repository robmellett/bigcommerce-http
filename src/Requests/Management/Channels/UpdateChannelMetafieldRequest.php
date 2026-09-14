<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update a single Channel Metafield.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-metafields#update-a-channel-metafield
 */
class UpdateChannelMetafieldRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected readonly int $channelId,
        protected readonly int $metafieldId,
        protected readonly ?string $namespace = null,
        protected readonly ?string $key = null,
        protected readonly ?string $value = null,
        protected readonly ?string $permissionSet = null,
        protected readonly ?string $description = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/metafields/{$this->metafieldId}";
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

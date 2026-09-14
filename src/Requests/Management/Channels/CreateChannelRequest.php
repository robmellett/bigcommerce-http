<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Channel.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels#create-a-channel
 */
class CreateChannelRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['name' => 'My Marketplace', 'platform' => 'custom', 'type' => 'storefront', 'is_listable_from_ui' => true]
     */
    public function __construct(
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/channels';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}

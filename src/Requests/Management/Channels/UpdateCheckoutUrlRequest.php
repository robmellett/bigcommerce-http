<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create or update (upsert) a Site's checkout URL.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-site-checkout-url#upsert-a-site-s-checkout-url
 */
class UpdateCheckoutUrlRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected readonly int $channelId,
        protected readonly string $url,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/site/checkout-url";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'url' => $this->url,
        ];
    }
}

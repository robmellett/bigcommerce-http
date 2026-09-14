<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a Site's checkout URL. After deletion, a shared checkout URL is used.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-site-checkout-url#delete-a-site-s-checkout-url
 */
class DeleteCheckoutUrlRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $channelId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/site/checkout-url";
    }
}

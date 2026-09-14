<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Site for a specific Channel. Alias of `POST /sites`.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-site#create-a-channel-site
 */
class CreateChannelSiteRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['url' => 'https://kittens.mybigcommerce.com/', 'channel_id' => 123]
     */
    public function __construct(
        protected readonly int $channelId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/site";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}

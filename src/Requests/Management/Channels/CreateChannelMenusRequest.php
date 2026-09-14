<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create or replace the list of control panel side navigation menus for a Channel.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-menus#create-channel-menus
 */
class CreateChannelMenusRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['bigcommerce_protected_app_sections' => ['social', 'domains'], 'custom_app_sections' => [['title' => 'Overview', 'query_path' => 'overview']]]
     */
    public function __construct(
        protected readonly int $channelId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/channel-menus";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}

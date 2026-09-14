<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete currency assignments for a specific Channel. The Channel will then inherit the store's currency settings.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-currency-assignment#delete-channel-currency-assignments
 */
class DeleteSingleChannelCurrencyAssignmentsRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $channelId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/currency-assignments";
    }
}

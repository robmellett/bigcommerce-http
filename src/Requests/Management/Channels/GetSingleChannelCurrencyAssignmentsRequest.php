<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Channel Currency Assignments for a specific Channel.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-currency-assignment#get-channel-currency-assignments
 */
class GetSingleChannelCurrencyAssignmentsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $channelId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/currency-assignments";
    }
}

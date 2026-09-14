<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update enabled currencies and default currency for multiple Channels.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-currency-assignment#update-multiple-channels-currency-assignments
 */
class UpdateMultipleChannelsCurrencyAssignmentsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<int, array<string, mixed>>  $data  e.g. [['channel_id' => 1, 'enabled_currencies' => ['USD', 'GBP'], 'default_currency' => 'USD']]
     */
    public function __construct(
        protected readonly array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/channels/currency-assignments';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}

<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update enabled currencies and default currency for a specific Channel.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-currency-assignment#update-channel-currency-assignments
 */
class UpdateSingleChannelCurrencyAssignmentsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<int, string>  $enabledCurrencies  e.g. ['USD', 'GBP', 'AUD']
     */
    public function __construct(
        protected readonly int $channelId,
        protected readonly array $enabledCurrencies,
        protected readonly string $defaultCurrency,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/currency-assignments";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'enabled_currencies' => $this->enabledCurrencies,
            'default_currency' => $this->defaultCurrency,
        ];
    }
}

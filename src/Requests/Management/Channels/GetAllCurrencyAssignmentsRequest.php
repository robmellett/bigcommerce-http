<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List currency assignments across all Channels.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-currency-assignment#get-all-currency-assignments
 */
class GetAllCurrencyAssignmentsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['channel_id:in' => '1,2', 'limit' => 50]
     */
    public function __construct(
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/channels/currency-assignments';
    }

    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}

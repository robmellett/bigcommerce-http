<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete one or more Customers by ID.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers#delete-customers
 */
class DeleteCustomersRequest extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  array<int, int>  $ids  e.g. [11, 12, 13]
     */
    public function __construct(
        protected readonly array $ids,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/customers';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return [
            'id:in' => implode(',', $this->ids),
        ];
    }
}

<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete one or more Customer Attributes by ID.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-attributes#delete-customer-attributes
 */
class DeleteCustomersAttributesRequest extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  array<int, int>  $ids  e.g. [3, 4]
     */
    public function __construct(
        protected readonly array $ids,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/customers/attributes';
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

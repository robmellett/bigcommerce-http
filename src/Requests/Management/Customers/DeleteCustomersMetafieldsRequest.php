<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete all Customer Metafields belonging to the authenticating app.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-metafields/batch#delete-customers-metafields
 */
class DeleteCustomersMetafieldsRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function resolveEndpoint(): string
    {
        return '/customers/metafields';
    }
}

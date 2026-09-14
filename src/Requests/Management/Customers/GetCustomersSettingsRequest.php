<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get the store's global Customer settings.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-settings#get-customers-settings
 */
class GetCustomersSettingsRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/customers/settings';
    }
}

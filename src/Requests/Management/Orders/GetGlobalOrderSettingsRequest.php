<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get the store's global Order settings.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-settings#get-global-order-settings
 */
class GetGlobalOrderSettingsRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/orders/settings';
    }
}

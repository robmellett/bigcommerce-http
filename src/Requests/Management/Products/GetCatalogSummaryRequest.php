<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a lightweight inventory summary from the BigCommerce Catalog (counts,
 * values, and price ranges across all variants).
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/summary#get-a-catalog-summary
 */
class GetCatalogSummaryRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/catalog/summary';
    }
}

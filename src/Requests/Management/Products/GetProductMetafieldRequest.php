<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a single Product Metafield.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-metafields#get-a-product-metafield
 */
class GetProductMetafieldRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $productId,
        protected readonly int $metafieldId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/metafields/{$this->metafieldId}";
    }
}

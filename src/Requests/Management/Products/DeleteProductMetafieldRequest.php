<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a Product Metafield.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-metafields#delete-a-product-metafield
 */
class DeleteProductMetafieldRequest extends Request
{
    protected Method $method = Method::DELETE;

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

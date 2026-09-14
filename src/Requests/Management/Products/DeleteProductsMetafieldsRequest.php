<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Delete multiple Product Metafields in a single batch call, by ID.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/batch-product-metafields#delete-multiple-metafields
 */
class DeleteProductsMetafieldsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::DELETE;

    /**
     * @param  array<int, int>  $ids  e.g. [8, 9, 10]
     */
    public function __construct(
        protected readonly array $ids,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/catalog/products/metafields';
    }

    /**
     * @return array<int, int>
     */
    protected function defaultBody(): array
    {
        return $this->ids;
    }
}

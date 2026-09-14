<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Assign Products to Categories (for MSF-enabled stores). Note this
 * operation uses HTTP PUT per BigCommerce's API design.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-category-assignments#create-products-category-assignments
 */
class CreateProductsCategoryAssignmentsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<int, array<string, mixed>>  $data  e.g. [['product_id' => 123, 'category_id' => 24]]
     */
    public function __construct(
        protected readonly array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/catalog/products/category-assignments';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}

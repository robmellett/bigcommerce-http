<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update a Product Review.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-reviews#update-a-product-review
 */
class UpdateProductReviewRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['status' => 'approved']
     */
    public function __construct(
        protected readonly int $productId,
        protected readonly int $reviewId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/reviews/{$this->reviewId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}

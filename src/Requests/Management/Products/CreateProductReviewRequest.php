<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Product Review.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-reviews#create-a-product-review
 */
class CreateProductReviewRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['title' => 'Great product', 'text' => 'Works as expected.', 'status' => 'approved', 'rating' => 5, 'email' => 'jane@example.com', 'name' => 'Jane Doe']
     */
    public function __construct(
        protected readonly int $productId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/reviews";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}

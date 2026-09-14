<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Product Image, either from an uploaded `image_file` or a remote
 * `image_url`.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-images#create-product-image
 */
class CreateProductImageRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['image_url' => 'https://example.com/image.jpg', 'is_thumbnail' => true]
     */
    public function __construct(
        protected readonly int $productId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/images";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}

<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Product Video (references an external video, e.g. on YouTube).
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-videos#create-product-video
 */
class CreateProductVideoRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['title' => 'Unboxing', 'video_id' => 'dQw4w9WgXcQ', 'type' => 'youtube']
     */
    public function __construct(
        protected readonly int $productId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/videos";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}

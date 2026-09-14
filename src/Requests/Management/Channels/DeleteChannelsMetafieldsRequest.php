<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Delete multiple Channel Metafields in a single request.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-batch-metafields#delete-multiple-metafields
 */
class DeleteChannelsMetafieldsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::DELETE;

    /**
     * @param  array<int, int>  $ids  e.g. [42, 43, 44]
     */
    public function __construct(
        protected readonly array $ids,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/channels/metafields';
    }

    /**
     * @return array<int, int>
     */
    protected function defaultBody(): array
    {
        return $this->ids;
    }
}

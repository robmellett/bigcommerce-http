<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Validate a Customer's login credentials.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers#validate-customer-credentials
 */
class ValidateCustomerCredentialsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $email,
        protected readonly string $password,
        protected readonly ?int $channelId = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/customers/validate-credentials';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return array_filter([
            'email' => $this->email,
            'password' => $this->password,
            'channel_id' => $this->channelId,
        ], static fn (mixed $value): bool => $value !== null);
    }
}

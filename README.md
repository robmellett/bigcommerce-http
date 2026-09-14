# A Saloon-based HTTP client for the BigCommerce API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/robmellett/bigcommerce-http.svg?style=flat-square)](https://packagist.org/packages/robmellett/bigcommerce-http)
[![GitHub Tests Action Status](https://github.com/robmellett/bigcommerce-http/actions/workflows/run-tests.yml/badge.svg)](https://github.com/robmellett/bigcommerce-http/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://github.com/robmellett/bigcommerce-http/actions/workflows/fix-php-code-style-issues.yml/badge.svg)](https://github.com/robmellett/bigcommerce-http/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/robmellett/bigcommerce-http.svg?style=flat-square)](https://packagist.org/packages/robmellett/bigcommerce-http)

A [Saloon](https://docs.saloon.dev)-based HTTP client for the BigCommerce API, packaged for Laravel. It ships one Request class per API operation, so every endpoint is a typed, testable, mockable object.

**Current coverage** is the core resources most storefronts and admin integrations need:

- **Storefront REST API** (`api/storefront`, same-origin/cookie authenticated): Carts, Checkouts, Customers
- **Management API v3** (`api.bigcommerce.com`, token authenticated): Products (Catalog), Customers, Orders (payments/refunds/metafields/settings), Channels

The long tail of BigCommerce's API surface (v2 legacy endpoints, Payments API, the rest of the Catalog like categories/brands/variants, Webhooks, Themes, Tax, etc.) isn't covered yet — contributions welcome.

## Installation

You can install the package via composer:

```bash
composer require robmellett/bigcommerce-http
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="bigcommerce-http-config"
```

This is the contents of the published config file:

```php
return [
    'store_hash' => env('BIGCOMMERCE_STORE_HASH'),
    'access_token' => env('BIGCOMMERCE_ACCESS_TOKEN'),
    'client_id' => env('BIGCOMMERCE_CLIENT_ID'),
    'store_domain' => env('BIGCOMMERCE_STORE_DOMAIN'),
];
```

## Configuration

Add the following to your `.env`:

```dotenv
BIGCOMMERCE_STORE_HASH=abc123
BIGCOMMERCE_ACCESS_TOKEN=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
BIGCOMMERCE_CLIENT_ID=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
BIGCOMMERCE_STORE_DOMAIN=yourstore.example.com
```

- `BIGCOMMERCE_STORE_HASH` and `BIGCOMMERCE_ACCESS_TOKEN` come from an API account under **Settings > API accounts** in the store's control panel. See [Authentication and example requests](https://developer.bigcommerce.com/docs/start/authentication) for how to generate one. `BIGCOMMERCE_CLIENT_ID` is optional and only needed if your API account requires it.
- `BIGCOMMERCE_STORE_DOMAIN` is the storefront's URL authority (e.g. `yourstore.example.com`, or a custom domain), used for Storefront API requests.

## Usage

This package exposes two Saloon connectors, matching BigCommerce's two REST APIs:

| Connector | Base URL | Auth | Used for |
|---|---|---|---|
| `RobMellett\BigcommerceHttp\Connectors\ManagementApiConnector` | `https://api.bigcommerce.com/stores/{store_hash}/v3` | `X-Auth-Token` header (API account access token) | Products, Customers, Orders, Channels, and most store admin data |
| `RobMellett\BigcommerceHttp\Connectors\StorefrontApiConnector` | `https://{store_domain}/api/storefront` | Same-origin CORS / the shopper's storefront session cookie — no BigCommerce token needed | Carts, Checkouts, storefront Customer creation |

The `BigcommerceHttp` facade builds either connector from your config for you:

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductsRequest;

$response = BigcommerceHttp::management()->send(new GetProductsRequest(
    queryParams: ['include' => 'images,variants', 'limit' => 50],
));

$products = $response->json('data');
```

The Storefront API is authenticated by the shopper's own storefront session cookie rather than a store-wide token, so when calling it from a Laravel backend (rather than the browser directly) forward the visitor's session cookie:

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Storefront\Carts\GetCartRequest;

$response = BigcommerceHttp::storefront(cookie: request()->headers->get('Cookie'))
    ->send(new GetCartRequest());

$cart = $response->json();
```

You can also construct either connector directly, which is useful in multi-tenant apps where the store hash/token/domain aren't fixed in config:

```php
use RobMellett\BigcommerceHttp\Connectors\ManagementApiConnector;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductRequest;

$connector = new ManagementApiConnector(storeHash: 'abc123', accessToken: 'xxxxxxxx');

$response = $connector->send(new GetProductRequest(productId: 118));
```

### Error handling

Both connectors throw on non-2xx responses (via Saloon's `AlwaysThrowOnErrors`), so you can wrap calls in a try/catch instead of checking `$response->failed()` manually:

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductRequest;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\Exceptions\Request\RequestException;

try {
    $response = BigcommerceHttp::management()->send(new GetProductRequest(productId: 118));
} catch (NotFoundException $exception) {
    // 404 from BigCommerce
} catch (RequestException $exception) {
    // any other 4xx/5xx from BigCommerce
}
```

### Pagination and filtering

Management API `GET` list endpoints (e.g. `GetProductsRequest`, `GetCustomersRequest`, `GetChannelsRequest`) accept a `queryParams` array that's sent through as-is, so you can pass any of BigCommerce's filter, sort, `include`, `page`, and `limit` parameters documented for that endpoint:

```php
$response = BigcommerceHttp::management()->send(new GetProductsRequest(
    queryParams: ['page' => 2, 'limit' => 50, 'name:like' => 'Shirt'],
));

$pagination = $response->json('meta.pagination');
```

---

## Storefront API

### Carts

Manage cart operations and data on BigCommerce-hosted storefronts (`/api/storefront/carts`).

**Get a Cart**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Storefront\Carts\GetCartRequest;

$response = BigcommerceHttp::storefront(cookie: $cookie)->send(
    new GetCartRequest(include: ['lineItems.physicalItems.options']),
);

$cart = $response->json();
```

**Create a Cart**

```php
use RobMellett\BigcommerceHttp\Requests\Storefront\Carts\CreateCartRequest;

$response = BigcommerceHttp::storefront(cookie: $cookie)->send(new CreateCartRequest(payload: [
    'lineItems' => [
        ['quantity' => 2, 'productId' => 230],
    ],
    'locale' => 'en',
]));

$cart = $response->json();
```

**Delete a Cart**

```php
use RobMellett\BigcommerceHttp\Requests\Storefront\Carts\DeleteCartRequest;

BigcommerceHttp::storefront(cookie: $cookie)->send(new DeleteCartRequest(cartId: $cart['id']));
```

**Add Cart Line Items**

```php
use RobMellett\BigcommerceHttp\Requests\Storefront\Carts\AddCartLineItemRequest;

$response = BigcommerceHttp::storefront(cookie: $cookie)->send(new AddCartLineItemRequest(
    cartId: $cart['id'],
    payload: [
        'lineItems' => [
            ['quantity' => 2, 'productId' => 230, 'variantId' => 124],
        ],
        'version' => 1,
    ],
));
```

**Update Cart Line Item**

```php
use RobMellett\BigcommerceHttp\Requests\Storefront\Carts\UpdateCartLineItemRequest;

$response = BigcommerceHttp::storefront(cookie: $cookie)->send(new UpdateCartLineItemRequest(
    cartId: $cart['id'],
    itemId: $itemId,
    payload: ['lineItem' => ['productId' => 229, 'variantId' => 191, 'quantity' => 10], 'version' => 1],
));
```

**Delete Cart Line Item**

```php
use RobMellett\BigcommerceHttp\Requests\Storefront\Carts\DeleteCartLineItemRequest;

BigcommerceHttp::storefront(cookie: $cookie)->send(new DeleteCartLineItemRequest(
    cartId: $cart['id'],
    itemId: $itemId,
    payload: ['version' => 1],
));
```

**Update Cart Currency**

```php
use RobMellett\BigcommerceHttp\Requests\Storefront\Carts\UpdateCartCurrencyRequest;

$response = BigcommerceHttp::storefront(cookie: $cookie)->send(new UpdateCartCurrencyRequest(
    cartId: $cart['id'],
    currencyCode: 'CAD',
));
```

### Checkouts

Manage checkout operations and data (`/api/storefront/checkouts`). The checkout ID is the same as the cart ID.

**Get a Checkout**

```php
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\GetCheckoutRequest;

$response = BigcommerceHttp::storefront(cookie: $cookie)->send(new GetCheckoutRequest(
    checkoutId: $checkoutId,
    include: ['consignments.availableShippingOptions'],
));

$checkout = $response->json();
```

**Update Customer Messages**

```php
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\UpdateCheckoutRequest;

BigcommerceHttp::storefront(cookie: $cookie)->send(new UpdateCheckoutRequest(
    checkoutId: $checkoutId,
    customerMessage: 'Please leave the package with the doorman.',
));
```

**Update / Delete a Checkout Line Item**

```php
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\UpdateCheckoutLineItemRequest;
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\DeleteCheckoutLineItemRequest;

BigcommerceHttp::storefront(cookie: $cookie)->send(new UpdateCheckoutLineItemRequest(
    checkoutId: $checkoutId,
    cartId: $checkoutId,
    itemId: $itemId,
    payload: ['lineItem' => ['productId' => 229, 'variantId' => 191, 'quantity' => 3]],
));

BigcommerceHttp::storefront(cookie: $cookie)->send(new DeleteCheckoutLineItemRequest(
    checkoutId: $checkoutId,
    cartId: $checkoutId,
    itemId: $itemId,
));
```

**Add / Update Billing Address**

```php
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\AddCheckoutBillingAddressRequest;
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\UpdateCheckoutBillingAddressRequest;

$address = [
    'firstName' => 'Jane',
    'lastName' => 'Doe',
    'email' => 'jane@example.com',
    'address1' => '123 Main Street',
    'city' => 'Austin',
    'stateOrProvince' => 'Texas',
    'postalCode' => '78751',
    'countryCode' => 'US',
];

BigcommerceHttp::storefront(cookie: $cookie)->send(new AddCheckoutBillingAddressRequest(
    checkoutId: $checkoutId,
    payload: $address,
));

BigcommerceHttp::storefront(cookie: $cookie)->send(new UpdateCheckoutBillingAddressRequest(
    checkoutId: $checkoutId,
    addressId: $addressId,
    payload: $address,
));
```

**Create / Update / Delete a Consignment**

```php
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\CreateCheckoutConsignmentRequest;
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\UpdateCheckoutConsignmentRequest;
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\DeleteCheckoutConsignmentRequest;

$response = BigcommerceHttp::storefront(cookie: $cookie)->send(new CreateCheckoutConsignmentRequest(
    checkoutId: $checkoutId,
    consignments: [[
        'address' => $address,
        'lineItems' => [['itemId' => $itemId, 'quantity' => 2]],
    ]],
    include: ['consignments.availableShippingOptions'],
));

$consignmentId = $response->json('consignments.0.id');

BigcommerceHttp::storefront(cookie: $cookie)->send(new UpdateCheckoutConsignmentRequest(
    checkoutId: $checkoutId,
    consignmentId: $consignmentId,
    payload: ['shippingOptionId' => $shippingOptionId, 'lineItems' => [['itemId' => $itemId, 'quantity' => 2]]],
));

BigcommerceHttp::storefront(cookie: $cookie)->send(new DeleteCheckoutConsignmentRequest(
    checkoutId: $checkoutId,
    consignmentId: $consignmentId,
));
```

**Gift Certificates, Coupons & Store Credit**

```php
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\AddCheckoutGiftCertificateRequest;
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\DeleteCheckoutGiftCertificateRequest;
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\AddCheckoutCouponRequest;
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\DeleteCheckoutCouponRequest;
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\AddCheckoutStoreCreditRequest;
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\RemoveCheckoutStoreCreditRequest;

BigcommerceHttp::storefront(cookie: $cookie)->send(new AddCheckoutGiftCertificateRequest(
    checkoutId: $checkoutId,
    giftCertificateCode: 'GIFT-1234',
));

BigcommerceHttp::storefront(cookie: $cookie)->send(new DeleteCheckoutGiftCertificateRequest(
    checkoutId: $checkoutId,
    giftCertificateCode: 'GIFT-1234',
));

BigcommerceHttp::storefront(cookie: $cookie)->send(new AddCheckoutCouponRequest(
    checkoutId: $checkoutId,
    couponCode: 'S2549JM0Y',
));

BigcommerceHttp::storefront(cookie: $cookie)->send(new DeleteCheckoutCouponRequest(
    checkoutId: $checkoutId,
    couponCode: 'S2549JM0Y',
));

BigcommerceHttp::storefront(cookie: $cookie)->send(new AddCheckoutStoreCreditRequest(checkoutId: $checkoutId));

BigcommerceHttp::storefront(cookie: $cookie)->send(new RemoveCheckoutStoreCreditRequest(checkoutId: $checkoutId));
```

**Spam Protection**

```php
use RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts\CheckoutSpamProtectionRequest;

BigcommerceHttp::storefront(cookie: $cookie)->send(new CheckoutSpamProtectionRequest(
    checkoutId: $checkoutId,
    token: $recaptchaToken,
));
```

### Customers (Storefront)

**Create a Customer** (e.g. during guest checkout)

```php
use RobMellett\BigcommerceHttp\Requests\Storefront\Customers\CreateCustomerRequest;

BigcommerceHttp::storefront(cookie: $cookie)->send(new CreateCustomerRequest(payload: [
    'firstName' => 'Jane',
    'lastName' => 'Doe',
    'email' => 'jane@example.com',
    'password' => 'SecurePassword123!',
    'acceptsMarketingEmails' => true,
]));
```

---

## Management API

### Products

The Catalog API's Products resource group covers everything under `/catalog/products` and `/catalog/summary` on the BigCommerce Management API: products themselves, images, videos, complex rules, custom fields, bulk pricing rules, metafields, reviews, and MSF channel/category assignments. All requests below run through the Management connector.

#### Products

Core CRUD and batch operations on products.

**Get All Products**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductsRequest;

$response = BigcommerceHttp::management()->send(new GetProductsRequest(
    queryParams: ['limit' => 10, 'is_visible' => true],
));

$products = $response->json('data');
```

**Update Products (Batch)**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\UpdateProductsRequest;

$response = BigcommerceHttp::management()->send(new UpdateProductsRequest(
    data: [
        ['id' => 123, 'name' => 'Smith Journal 13', 'price' => 19.95],
        ['id' => 124, 'is_visible' => false],
    ],
));

$products = $response->json('data');
```

**Create a Product**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\CreateProductRequest;

$response = BigcommerceHttp::management()->send(new CreateProductRequest(
    payload: [
        'name' => 'Smith Journal 13',
        'type' => 'physical',
        'sku' => 'SM-13',
        'weight' => 0.5,
        'price' => 19.95,
        'categories' => [24],
    ],
));

$product = $response->json('data');
```

**Delete Products**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\DeleteProductsRequest;

BigcommerceHttp::management()->send(new DeleteProductsRequest(
    queryParams: ['id:in' => '123,124,125'],
));
```

**Get a Product**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductRequest;

$response = BigcommerceHttp::management()->send(new GetProductRequest(
    productId: 123,
    queryParams: ['include' => 'images,variants,custom_fields'],
));

$product = $response->json('data');
```

**Update a Product**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\UpdateProductRequest;

$response = BigcommerceHttp::management()->send(new UpdateProductRequest(
    productId: 123,
    payload: ['name' => 'Smith Journal 13', 'price' => 24.95],
));

$product = $response->json('data');
```

**Delete a Product**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\DeleteProductRequest;

BigcommerceHttp::management()->send(new DeleteProductRequest(productId: 123));
```

#### Product Images

Manage the images attached to a product.

**Get All Product Images**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductImagesRequest;

$response = BigcommerceHttp::management()->send(new GetProductImagesRequest(productId: 123));

$images = $response->json('data');
```

**Create a Product Image**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\CreateProductImageRequest;

$response = BigcommerceHttp::management()->send(new CreateProductImageRequest(
    productId: 123,
    payload: ['image_url' => 'https://example.com/image.jpg', 'is_thumbnail' => true],
));

$image = $response->json('data');
```

**Get a Product Image**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductImageRequest;

$response = BigcommerceHttp::management()->send(new GetProductImageRequest(
    productId: 123,
    imageId: 485,
));

$image = $response->json('data');
```

**Update a Product Image**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\UpdateProductImageRequest;

$response = BigcommerceHttp::management()->send(new UpdateProductImageRequest(
    productId: 123,
    imageId: 485,
    payload: ['is_thumbnail' => true, 'sort_order' => 1],
));

$image = $response->json('data');
```

**Delete a Product Image**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\DeleteProductImageRequest;

BigcommerceHttp::management()->send(new DeleteProductImageRequest(
    productId: 123,
    imageId: 485,
));
```

#### Product Videos

Manage third-party (YouTube) videos attached to a product.

**Get All Product Videos**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductVideosRequest;

$response = BigcommerceHttp::management()->send(new GetProductVideosRequest(productId: 123));

$videos = $response->json('data');
```

**Create a Product Video**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\CreateProductVideoRequest;

$response = BigcommerceHttp::management()->send(new CreateProductVideoRequest(
    productId: 123,
    payload: ['title' => 'Unboxing', 'video_id' => 'dQw4w9WgXcQ', 'type' => 'youtube'],
));

$video = $response->json('data');
```

**Get a Product Video**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductVideoRequest;

$response = BigcommerceHttp::management()->send(new GetProductVideoRequest(
    productId: 123,
    videoId: 6,
));

$video = $response->json('data');
```

**Update a Product Video**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\UpdateProductVideoRequest;

$response = BigcommerceHttp::management()->send(new UpdateProductVideoRequest(
    productId: 123,
    videoId: 6,
    payload: ['title' => 'Unboxing (updated)'],
));

$video = $response->json('data');
```

**Delete a Product Video**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\DeleteProductVideoRequest;

BigcommerceHttp::management()->send(new DeleteProductVideoRequest(
    productId: 123,
    videoId: 6,
));
```

#### Product Complex Rules

Rules that adjust a product's price, weight, image, or availability based on selected modifier/option values.

**Get Complex Rules**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductComplexRulesRequest;

$response = BigcommerceHttp::management()->send(new GetProductComplexRulesRequest(productId: 123));

$rules = $response->json('data');
```

**Create a Product Complex Rule**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\CreateProductComplexRuleRequest;

$response = BigcommerceHttp::management()->send(new CreateProductComplexRuleRequest(
    productId: 123,
    payload: [
        'enabled' => true,
        'conditions' => [
            ['modifier_id' => 55, 'modifier_value_id' => 256, 'variant_id' => 1],
        ],
        'price_adjuster' => ['adjuster' => 'relative', 'adjuster_value' => 10],
    ],
));

$rule = $response->json('data');
```

**Get a Product Complex Rule**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductComplexRuleRequest;

$response = BigcommerceHttp::management()->send(new GetProductComplexRuleRequest(
    productId: 123,
    complexRuleId: 5,
));

$rule = $response->json('data');
```

**Update a Product Complex Rule**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\UpdateProductComplexRuleRequest;

$response = BigcommerceHttp::management()->send(new UpdateProductComplexRuleRequest(
    productId: 123,
    complexRuleId: 5,
    payload: ['enabled' => false],
));

$rule = $response->json('data');
```

**Delete a Product Complex Rule**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\DeleteProductComplexRuleRequest;

BigcommerceHttp::management()->send(new DeleteProductComplexRuleRequest(
    productId: 123,
    complexRuleId: 5,
));
```

#### Product Custom Fields

Freeform name/value pairs shown on the product page, orders, etc. (e.g. a book's ISBN).

**Get All Custom Fields**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductCustomFieldsRequest;

$response = BigcommerceHttp::management()->send(new GetProductCustomFieldsRequest(productId: 123));

$customFields = $response->json('data');
```

**Create a Product Custom Field**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\CreateProductCustomFieldRequest;

$response = BigcommerceHttp::management()->send(new CreateProductCustomFieldRequest(
    productId: 123,
    name: 'ISBN',
    value: '1234567890123',
));

$customField = $response->json('data');
```

**Get a Product Custom Field**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductCustomFieldRequest;

$response = BigcommerceHttp::management()->send(new GetProductCustomFieldRequest(
    productId: 123,
    customFieldId: 6,
));

$customField = $response->json('data');
```

**Update a Product Custom Field**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\UpdateProductCustomFieldRequest;

$response = BigcommerceHttp::management()->send(new UpdateProductCustomFieldRequest(
    productId: 123,
    customFieldId: 6,
    name: 'ISBN',
    value: '9876543210987',
));

$customField = $response->json('data');
```

**Delete a Product Custom Field**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\DeleteProductCustomFieldRequest;

BigcommerceHttp::management()->send(new DeleteProductCustomFieldRequest(
    productId: 123,
    customFieldId: 6,
));
```

#### Product Bulk Pricing Rules

Quantity-discount tiers for a product (e.g. buy 10+ and save).

**Create a Bulk Pricing Rule**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\CreateBulkPricingRuleRequest;

$response = BigcommerceHttp::management()->send(new CreateBulkPricingRuleRequest(
    productId: 123,
    payload: ['quantity_min' => 10, 'quantity_max' => 50, 'type' => 'price', 'amount' => 10],
));

$rule = $response->json('data');
```

**Get all Bulk Pricing Rules**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetAllBulkPricingRulesRequest;

$response = BigcommerceHttp::management()->send(new GetAllBulkPricingRulesRequest(productId: 123));

$rules = $response->json('data');
```

**Get a Bulk Pricing Rule**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetBulkPricingRuleRequest;

$response = BigcommerceHttp::management()->send(new GetBulkPricingRuleRequest(
    productId: 123,
    bulkPricingRuleId: 83,
));

$rule = $response->json('data');
```

**Update a Bulk Pricing Rule**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\UpdateBulkPricingRuleRequest;

$response = BigcommerceHttp::management()->send(new UpdateBulkPricingRuleRequest(
    productId: 123,
    bulkPricingRuleId: 83,
    payload: ['quantity_max' => 100, 'amount' => 15],
));

$rule = $response->json('data');
```

**Delete a Bulk Pricing Rule**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\DeleteBulkPricingRuleRequest;

BigcommerceHttp::management()->send(new DeleteBulkPricingRuleRequest(
    productId: 123,
    bulkPricingRuleId: 83,
));
```

#### Product Metafields

Namespaced key/value metadata attached to a single product.

**Get Product Metafields**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductMetafieldsRequest;

$response = BigcommerceHttp::management()->send(new GetProductMetafieldsRequest(
    productId: 123,
    queryParams: ['namespace' => 'Warehouse Locations'],
));

$metafields = $response->json('data');
```

**Create a Product Metafield**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\CreateProductMetafieldRequest;

$response = BigcommerceHttp::management()->send(new CreateProductMetafieldRequest(
    productId: 123,
    key: 'location_id',
    value: 'Shelf 3, Bin 5',
    namespace: 'Warehouse Locations',
    permissionSet: 'read',
));

$metafield = $response->json('data');
```

**Get a Product Metafield**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductMetafieldRequest;

$response = BigcommerceHttp::management()->send(new GetProductMetafieldRequest(
    productId: 123,
    metafieldId: 8,
));

$metafield = $response->json('data');
```

**Update a Product Metafield**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\UpdateProductMetafieldRequest;

$response = BigcommerceHttp::management()->send(new UpdateProductMetafieldRequest(
    productId: 123,
    metafieldId: 8,
    value: 'Shelf 4, Bin 2',
));

$metafield = $response->json('data');
```

**Delete a Product Metafield**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\DeleteProductMetafieldRequest;

BigcommerceHttp::management()->send(new DeleteProductMetafieldRequest(
    productId: 123,
    metafieldId: 8,
));
```

#### Product Reviews

Customer reviews left on a product.

**Get Product Reviews**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductReviewsRequest;

$response = BigcommerceHttp::management()->send(new GetProductReviewsRequest(
    productId: 123,
    queryParams: ['status' => 1, 'limit' => 50],
));

$reviews = $response->json('data');
```

**Create a Product Review**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\CreateProductReviewRequest;

$response = BigcommerceHttp::management()->send(new CreateProductReviewRequest(
    productId: 123,
    payload: [
        'title' => 'Great product',
        'text' => 'Works as expected.',
        'status' => 'approved',
        'rating' => 5,
        'email' => 'jane@example.com',
        'name' => 'Jane Doe',
        'date_reviewed' => now()->toIso8601String(),
    ],
));

$review = $response->json('data');
```

**Get a Product Review**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductReviewRequest;

$response = BigcommerceHttp::management()->send(new GetProductReviewRequest(
    productId: 123,
    reviewId: 82495037,
));

$review = $response->json('data');
```

**Update a Product Review**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\UpdateProductReviewRequest;

$response = BigcommerceHttp::management()->send(new UpdateProductReviewRequest(
    productId: 123,
    reviewId: 82495037,
    payload: ['status' => 'approved'],
));

$review = $response->json('data');
```

**Delete a Product Review**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\DeleteProductReviewRequest;

BigcommerceHttp::management()->send(new DeleteProductReviewRequest(
    productId: 123,
    reviewId: 82495037,
));
```

#### Product Channel Assignments

Assign products to channels on multi-storefront (MSF) enabled stores.

**Get Products Channel Assignments**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductsChannelAssignmentsRequest;

$response = BigcommerceHttp::management()->send(new GetProductsChannelAssignmentsRequest(
    queryParams: ['product_id:in' => '123,124', 'channel_id' => 1],
));

$assignments = $response->json('data');
```

**Create Products Channel Assignments**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\CreateProductsChannelAssignmentsRequest;

BigcommerceHttp::management()->send(new CreateProductsChannelAssignmentsRequest(
    data: [
        ['product_id' => 123, 'channel_id' => 1],
        ['product_id' => 124, 'channel_id' => 1],
    ],
));
```

**Delete Products Channel Assignments**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\DeleteProductsChannelAssignmentsRequest;

BigcommerceHttp::management()->send(new DeleteProductsChannelAssignmentsRequest(
    queryParams: ['product_id:in' => '123,124', 'channel_id' => 1],
));
```

#### Product Category Assignments

Assign products to categories on multi-storefront (MSF) enabled stores.

**Get Products Category Assignments**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductsCategoryAssignmentsRequest;

$response = BigcommerceHttp::management()->send(new GetProductsCategoryAssignmentsRequest(
    queryParams: ['product_id:in' => '123,124', 'category_id' => 24],
));

$assignments = $response->json('data');
```

**Create Products Category Assignments**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\CreateProductsCategoryAssignmentsRequest;

BigcommerceHttp::management()->send(new CreateProductsCategoryAssignmentsRequest(
    data: [
        ['product_id' => 123, 'category_id' => 24],
        ['product_id' => 124, 'category_id' => 24],
    ],
));
```

**Delete Products Category Assignments**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\DeleteProductsCategoryAssignmentsRequest;

BigcommerceHttp::management()->send(new DeleteProductsCategoryAssignmentsRequest(
    queryParams: ['product_id:in' => '123,124', 'category_id' => 24],
));
```

#### Catalog Summary

A lightweight, store-wide inventory summary.

**Get a Catalog Summary**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetCatalogSummaryRequest;

$response = BigcommerceHttp::management()->send(new GetCatalogSummaryRequest());

$summary = $response->json('data');
```

#### Batch Product Metafields

Read, create, update, and delete metafields across the whole product catalog in one call, rather than one product at a time.

**Get All Product Metafields**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductsMetafieldsRequest;

$response = BigcommerceHttp::management()->send(new GetProductsMetafieldsRequest(
    queryParams: ['namespace' => 'Warehouse Locations', 'limit' => 50],
));

$metafields = $response->json('data');
```

**Create multiple Metafields**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\CreateProductsMetafieldsRequest;

$response = BigcommerceHttp::management()->send(new CreateProductsMetafieldsRequest(
    data: [
        [
            'resource_id' => 123,
            'key' => 'location_id',
            'value' => '4HG',
            'namespace' => 'Warehouse Locations',
            'permission_set' => 'read',
        ],
    ],
));

$metafields = $response->json('data');
```

**Update multiple Metafields**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\UpdateProductsMetafieldsRequest;

$response = BigcommerceHttp::management()->send(new UpdateProductsMetafieldsRequest(
    data: [
        ['id' => 8, 'value' => 'Shelf 4, Bin 2'],
    ],
));

$metafields = $response->json('data');
```

**Delete Multiple Metafields**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\DeleteProductsMetafieldsRequest;

BigcommerceHttp::management()->send(new DeleteProductsMetafieldsRequest(
    ids: [8, 9, 10],
));
```

---

### Customers

Manage customers, their addresses, custom attributes, consent, and metafields via the BigCommerce Management API v3 `/customers` resource group.

#### Customers

**Get All Customers**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\GetCustomersRequest;

$response = BigcommerceHttp::management()->send(new GetCustomersRequest(
    queryParams: ['email:in' => 'jane@example.com', 'limit' => 50],
));

$customers = $response->json('data');
```

**Create Customers**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\CreateCustomersRequest;

$response = BigcommerceHttp::management()->send(new CreateCustomersRequest(data: [
    ['email' => 'jane@example.com', 'first_name' => 'Jane', 'last_name' => 'Doe'],
]));

$customers = $response->json('data');
```

**Update Customers**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\UpdateCustomersRequest;

BigcommerceHttp::management()->send(new UpdateCustomersRequest(data: [
    ['id' => 11, 'first_name' => 'Janet'],
]));
```

**Delete Customers**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\DeleteCustomersRequest;

BigcommerceHttp::management()->send(new DeleteCustomersRequest(ids: [11, 12]));
```

#### Customer Addresses

**Get All Customer Addresses**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\GetCustomersAddressesRequest;

$response = BigcommerceHttp::management()->send(new GetCustomersAddressesRequest(
    queryParams: ['customer_id:in' => '11,12'],
));

$addresses = $response->json('data');
```

**Create Customer Addresses**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\CreateCustomersAddressesRequest;

$response = BigcommerceHttp::management()->send(new CreateCustomersAddressesRequest(data: [
    [
        'customer_id' => 11,
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'address1' => '123 Main St',
        'city' => 'Austin',
        'country_code' => 'US',
        'postal_code' => '78751',
    ],
]));
```

**Update Customer Addresses**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\UpdateCustomersAddressesRequest;

BigcommerceHttp::management()->send(new UpdateCustomersAddressesRequest(data: [
    ['id' => 5, 'city' => 'Round Rock'],
]));
```

**Delete Customer Addresses**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\DeleteCustomersAddressesRequest;

BigcommerceHttp::management()->send(new DeleteCustomersAddressesRequest(
    queryParams: ['id:in' => '5,6'],
));
```

#### Customer Credentials

**Validate Customer Credentials**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\ValidateCustomerCredentialsRequest;

$response = BigcommerceHttp::management()->send(new ValidateCustomerCredentialsRequest(
    email: 'jane@example.com',
    password: 'SecurePassword123!',
));

$isValid = $response->json('data.is_valid');
```

#### Customer Settings

**Get / Update Global Customer Settings**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\GetCustomersSettingsRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\UpdateCustomersSettingsRequest;

$response = BigcommerceHttp::management()->send(new GetCustomersSettingsRequest());

BigcommerceHttp::management()->send(new UpdateCustomersSettingsRequest(
    payload: ['registration_enabled' => true, 'guest_checkout_enabled' => true],
));
```

**Get / Update Customer Settings for a Channel**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\GetCustomersSettingsChannelRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\UpdateCustomersSettingsChannelRequest;

$response = BigcommerceHttp::management()->send(new GetCustomersSettingsChannelRequest(channelId: 1));

BigcommerceHttp::management()->send(new UpdateCustomersSettingsChannelRequest(
    channelId: 1,
    payload: ['registration_enabled' => true],
));
```

#### Customer Attributes

**Get / Create / Update / Delete Customer Attributes** (custom field definitions)

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\GetCustomersAttributesRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\CreateCustomersAttributesRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\UpdateCustomersAttributesRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\DeleteCustomersAttributesRequest;

$response = BigcommerceHttp::management()->send(new GetCustomersAttributesRequest());

$response = BigcommerceHttp::management()->send(new CreateCustomersAttributesRequest(data: [
    ['name' => 'Birthday', 'type' => 'date'],
]));

BigcommerceHttp::management()->send(new UpdateCustomersAttributesRequest(data: [
    ['id' => 3, 'name' => 'Anniversary'],
]));

BigcommerceHttp::management()->send(new DeleteCustomersAttributesRequest(ids: [3, 4]));
```

#### Customer Attribute Values

**Get / Upsert / Delete Customer Attribute Values**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\GetCustomersAttributeValuesRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\UpsertCustomersAttributeValuesRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\DeleteCustomersAttributeValuesRequest;

$response = BigcommerceHttp::management()->send(new GetCustomersAttributeValuesRequest(
    queryParams: ['customer_id:in' => '11,12'],
));

BigcommerceHttp::management()->send(new UpsertCustomersAttributeValuesRequest(data: [
    ['attribute_id' => 3, 'customer_id' => 11, 'attribute_value' => '1990-05-04'],
]));

BigcommerceHttp::management()->send(new DeleteCustomersAttributeValuesRequest(
    queryParams: ['customer_id:in' => '11'],
));
```

#### Customer Form Field Values

**Get / Update Customer Form Field Values**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\GetCustomersFormFieldValuesRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\UpdateCustomerFormFieldValuesRequest;

$response = BigcommerceHttp::management()->send(new GetCustomersFormFieldValuesRequest(
    queryParams: ['customer_id:in' => '11'],
));

BigcommerceHttp::management()->send(new UpdateCustomerFormFieldValuesRequest(data: [
    ['customer_id' => 11, 'name' => 'How did you hear about us?', 'value' => 'Google'],
]));
```

#### Customer Consent

**Get / Update Customer Consent**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\GetCustomerConsentRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\UpdateCustomerConsentRequest;

$response = BigcommerceHttp::management()->send(new GetCustomerConsentRequest(customerId: 11));

BigcommerceHttp::management()->send(new UpdateCustomerConsentRequest(
    customerId: 11,
    payload: ['channel_id' => 1, 'consent' => ['tracking' => true, 'performance_cookie' => true]],
));
```

#### Stored Payment Instruments

**Get Customer Stored Instruments**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\GetCustomerStoredInstrumentsRequest;

$response = BigcommerceHttp::management()->send(new GetCustomerStoredInstrumentsRequest(customerId: 11));

$instruments = $response->json('data');
```

#### Customer Metafields

**Get / Create / Get One / Update / Delete a Customer's Metafields**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\GetCustomersMetafieldsRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\CreateCustomerMetafieldsRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\GetMetafieldsCustomerIdRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\UpdateCustomerMetafieldRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\DeleteCustomerMetafieldsIdRequest;

$response = BigcommerceHttp::management()->send(new GetCustomersMetafieldsRequest(customerId: 11));

$response = BigcommerceHttp::management()->send(new CreateCustomerMetafieldsRequest(
    customerId: 11,
    key: 'vip',
    value: 'true',
    namespace: 'Sales Department',
    permissionSet: 'read',
));

$metafieldId = $response->json('data.id');

BigcommerceHttp::management()->send(new GetMetafieldsCustomerIdRequest(customerId: 11, metafieldId: $metafieldId));

BigcommerceHttp::management()->send(new UpdateCustomerMetafieldRequest(
    customerId: 11,
    metafieldId: $metafieldId,
    value: 'false',
));

BigcommerceHttp::management()->send(new DeleteCustomerMetafieldsIdRequest(customerId: 11, metafieldId: $metafieldId));
```

**Batch Metafields Across All Customers**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Customers\GetAllCustomersMetafieldsRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\CreateCustomersMetafieldsRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\UpdateCustomersMetafieldsRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Customers\DeleteCustomersMetafieldsRequest;

$response = BigcommerceHttp::management()->send(new GetAllCustomersMetafieldsRequest(
    queryParams: ['namespace' => 'Sales Department'],
));

BigcommerceHttp::management()->send(new CreateCustomersMetafieldsRequest(data: [
    ['resource_id' => 11, 'key' => 'vip', 'value' => 'true', 'namespace' => 'Sales Department', 'permission_set' => 'read'],
]));

BigcommerceHttp::management()->send(new UpdateCustomersMetafieldsRequest(data: [
    ['id' => 42, 'value' => 'false'],
]));

// Deletes every metafield owned by the authenticating app across all customers.
BigcommerceHttp::management()->send(new DeleteCustomersMetafieldsRequest());
```

---

### Orders

The Orders v3 resource group covers order payments, refunds, transactions, metafields, and settings via the BigCommerce Management API. Core order CRUD (creating/listing/reading orders themselves) lives on the legacy v2 API, which is outside this package's current coverage.

#### Order Payments

**Capture / Void a Payment**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Orders\CaptureOrderPaymentRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Orders\VoidOrderPaymentRequest;

BigcommerceHttp::management()->send(new CaptureOrderPaymentRequest(
    orderId: 100,
    payload: ['amount' => '19.95'],
));

BigcommerceHttp::management()->send(new VoidOrderPaymentRequest(orderId: 100));
```

**Get Order Transactions**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Orders\GetOrderTransactionsRequest;

$response = BigcommerceHttp::management()->send(new GetOrderTransactionsRequest(orderId: 100));

$transactions = $response->json('data');
```

**Refund Quotes & Refunds**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Orders\CreateOrderRefundQuotesRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Orders\CreateOrderRefundRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Orders\GetOrderRefundsRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Orders\GetOrderRefundRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Orders\GetOrdersRefundsRequest;

$quote = BigcommerceHttp::management()->send(new CreateOrderRefundQuotesRequest(
    orderId: 100,
    payload: ['lineItems' => [['id' => 1, 'quantity' => 1]]],
));

$refund = BigcommerceHttp::management()->send(new CreateOrderRefundRequest(
    orderId: 100,
    payload: ['lineItems' => [['id' => 1, 'quantity' => 1]], 'reason' => 'Customer request'],
));

BigcommerceHttp::management()->send(new GetOrderRefundsRequest(orderId: 100));

BigcommerceHttp::management()->send(new GetOrderRefundRequest(refundId: $refund->json('data.id')));

// Refunds across every order in the store
BigcommerceHttp::management()->send(new GetOrdersRefundsRequest(queryParams: ['order_id:in' => '100,101']));
```

#### Order Metafields

```php
use RobMellett\BigcommerceHttp\Requests\Management\Orders\GetOrderMetafieldsRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Orders\CreateOrderMetafieldRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Orders\GetOrderMetafieldRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Orders\UpdateOrderMetafieldRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Orders\DeleteOrderMetafieldRequest;

$response = BigcommerceHttp::management()->send(new GetOrderMetafieldsRequest(orderId: 100));

$response = BigcommerceHttp::management()->send(new CreateOrderMetafieldRequest(
    orderId: 100,
    key: 'gift_message',
    value: 'Happy Birthday!',
    namespace: 'Sales Department',
));

$metafieldId = $response->json('data.id');

BigcommerceHttp::management()->send(new GetOrderMetafieldRequest(orderId: 100, metafieldId: $metafieldId));

BigcommerceHttp::management()->send(new UpdateOrderMetafieldRequest(
    orderId: 100,
    metafieldId: $metafieldId,
    value: 'Happy Anniversary!',
));

BigcommerceHttp::management()->send(new DeleteOrderMetafieldRequest(orderId: 100, metafieldId: $metafieldId));
```

**Batch Metafields Across All Orders**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Orders\GetOrdersMetafieldsRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Orders\CreateOrdersMetafieldsRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Orders\UpdateOrdersMetafieldsRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Orders\DeleteOrdersMetafieldsRequest;

BigcommerceHttp::management()->send(new GetOrdersMetafieldsRequest(queryParams: ['namespace' => 'Sales Department']));

BigcommerceHttp::management()->send(new CreateOrdersMetafieldsRequest(data: [
    ['resource_id' => 100, 'key' => 'gift_message', 'value' => 'Happy Birthday', 'namespace' => 'Sales Department', 'permission_set' => 'read'],
]));

BigcommerceHttp::management()->send(new UpdateOrdersMetafieldsRequest(data: [
    ['id' => 7, 'value' => 'Happy Anniversary'],
]));

BigcommerceHttp::management()->send(new DeleteOrdersMetafieldsRequest(ids: [7, 8]));
```

#### Order Settings

**Get / Update Global Order Settings**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Orders\GetGlobalOrderSettingsRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Orders\UpdateGlobalOrderSettingsRequest;

$response = BigcommerceHttp::management()->send(new GetGlobalOrderSettingsRequest());

BigcommerceHttp::management()->send(new UpdateGlobalOrderSettingsRequest(
    payload: ['is_multiple_notifications_enabled' => true],
));
```

**Get / Update Order Settings for a Channel**

```php
use RobMellett\BigcommerceHttp\Requests\Management\Orders\GetChannelOrderSettingsRequest;
use RobMellett\BigcommerceHttp\Requests\Management\Orders\UpdateChannelOrderSettingsRequest;

$response = BigcommerceHttp::management()->send(new GetChannelOrderSettingsRequest(channelId: 1));

BigcommerceHttp::management()->send(new UpdateChannelOrderSettingsRequest(
    channelId: 1,
    payload: ['is_multiple_notifications_enabled' => true],
));
```

---

### Channels

Create and manage sales channels, their sites, and their product listings via the BigCommerce Management API v3 `/channels` resource group.

#### Channels

Create, list, retrieve, and update the channels a store sells through (storefronts, marketplaces, POS systems, and marketing platforms).

**Get All Channels**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\GetChannelsRequest;

$response = BigcommerceHttp::management()->send(new GetChannelsRequest(queryParams: ['available' => true]));

$channels = $response->json('data');
```

**Create a Channel**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\CreateChannelRequest;

$response = BigcommerceHttp::management()->send(new CreateChannelRequest(
    payload: [
        'name' => 'eBay',
        'platform' => 'ebay',
        'type' => 'marketplace',
        'status' => 'connected',
    ],
));

$channel = $response->json('data');
```

**Get a Channel**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\GetChannelRequest;

$response = BigcommerceHttp::management()->send(new GetChannelRequest(channelId: 664179, include: 'currencies'));

$channel = $response->json('data');
```

**Update a Channel**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\UpdateChannelRequest;

$response = BigcommerceHttp::management()->send(new UpdateChannelRequest(
    channelId: 667159,
    payload: ['name' => 'Facebook by Meta', 'status' => 'connected'],
));

$channel = $response->json('data');
```

#### Active Theme

Look up the theme currently active on a channel's storefront.

**Get a Channel Active Theme**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\GetChannelActiveThemeRequest;

$response = BigcommerceHttp::management()->send(new GetChannelActiveThemeRequest(channelId: 1));

$activeTheme = $response->json('data');
```

#### Currency Assignments

Manage which currencies are enabled, and which is the default, for one or all channels. Currencies must already exist in the store's currency settings before they can be assigned.

**Get All Channels Currency Assignments**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\GetAllCurrencyAssignmentsRequest;

$response = BigcommerceHttp::management()->send(new GetAllCurrencyAssignmentsRequest());

$assignments = $response->json('data');
```

**Create Multiple Channels Currency Assignments**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\CreateMultipleChannelsCurrencyAssignmentsRequest;

$response = BigcommerceHttp::management()->send(new CreateMultipleChannelsCurrencyAssignmentsRequest(
    data: [
        ['channel_id' => 1, 'enabled_currencies' => ['USD'], 'default_currency' => 'USD'],
        ['channel_id' => 664177, 'enabled_currencies' => ['USD', 'GBP'], 'default_currency' => 'USD'],
    ],
));

$assignments = $response->json('data');
```

**Update Multiple Channels Currency Assignments**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\UpdateMultipleChannelsCurrencyAssignmentsRequest;

$response = BigcommerceHttp::management()->send(new UpdateMultipleChannelsCurrencyAssignmentsRequest(
    data: [
        ['channel_id' => 1, 'enabled_currencies' => ['USD', 'AUD'], 'default_currency' => 'USD'],
    ],
));

$assignments = $response->json('data');
```

**Get Channel Currency Assignments**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\GetSingleChannelCurrencyAssignmentsRequest;

$response = BigcommerceHttp::management()->send(new GetSingleChannelCurrencyAssignmentsRequest(channelId: 664177));

$assignment = $response->json('data');
```

**Create Channel Currency Assignments**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\CreateSingleChannelCurrencyAssignmentsRequest;

$response = BigcommerceHttp::management()->send(new CreateSingleChannelCurrencyAssignmentsRequest(
    channelId: 664177,
    enabledCurrencies: ['USD', 'GBP', 'AUD'],
    defaultCurrency: 'USD',
));

$assignment = $response->json('data');
```

**Update Channel Currency Assignments**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\UpdateSingleChannelCurrencyAssignmentsRequest;

$response = BigcommerceHttp::management()->send(new UpdateSingleChannelCurrencyAssignmentsRequest(
    channelId: 664177,
    enabledCurrencies: ['USD', 'GBP'],
    defaultCurrency: 'GBP',
));

$assignment = $response->json('data');
```

**Delete Channel Currency Assignments**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\DeleteSingleChannelCurrencyAssignmentsRequest;

$response = BigcommerceHttp::management()->send(new DeleteSingleChannelCurrencyAssignmentsRequest(channelId: 664177));
```

#### Channel Listings

Manage per-channel differences in a catalog — recommended for non-storefront channels like marketplaces, marketing channels, and POS.

**Get Channel Listings**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\GetChannelListingsRequest;

$response = BigcommerceHttp::management()->send(new GetChannelListingsRequest(
    channelId: 664179,
    queryParams: ['limit' => 50, 'product_id:in' => '80,100'],
));

$listings = $response->json('data');
```

**Create Channel Listings**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\CreateChannelListingsRequest;

$response = BigcommerceHttp::management()->send(new CreateChannelListingsRequest(
    channelId: 664179,
    data: [
        [
            'product_id' => 80,
            'state' => 'active',
            'name' => 'Orbit Terrarium - Large',
            'variants' => [
                ['product_id' => 80, 'variant_id' => 64, 'state' => 'active'],
            ],
        ],
    ],
));

$listings = $response->json('data');
```

**Update Channel Listings**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\UpdateChannelListingsRequest;

$response = BigcommerceHttp::management()->send(new UpdateChannelListingsRequest(
    channelId: 664179,
    data: [
        [
            'listing_id' => 882998595,
            'product_id' => 80,
            'state' => 'active',
            'variants' => [
                ['product_id' => 80, 'variant_id' => 64, 'state' => 'active'],
            ],
        ],
    ],
));

$listings = $response->json('data');
```

**Get a Channel Listing**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\GetChannelListingRequest;

$response = BigcommerceHttp::management()->send(new GetChannelListingRequest(channelId: 664179, listingId: 882998595));

$listing = $response->json('data');
```

#### Channel Checkout URL

Manage the checkout URL used by a channel's site.

**Upsert a Site's Checkout URL**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\UpdateCheckoutUrlRequest;

$response = BigcommerceHttp::management()->send(new UpdateCheckoutUrlRequest(
    channelId: 1,
    url: 'https://checkout.kittens.mybigcommerce.com',
));

$site = $response->json('data');
```

**Delete a Site's Checkout URL**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\DeleteCheckoutUrlRequest;

$response = BigcommerceHttp::management()->send(new DeleteCheckoutUrlRequest(channelId: 1));
```

#### Channel Site

Manage the domain (site) associated with a channel.

**Get a Channel Site**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\GetChannelSiteRequest;

$response = BigcommerceHttp::management()->send(new GetChannelSiteRequest(channelId: 123));

$site = $response->json('data');
```

**Update a Channel Site**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\UpdateChannelSiteRequest;

$response = BigcommerceHttp::management()->send(new UpdateChannelSiteRequest(
    channelId: 123,
    payload: ['url' => 'https://example.com/'],
));

$site = $response->json('data');
```

**Create a Channel Site**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\CreateChannelSiteRequest;

$response = BigcommerceHttp::management()->send(new CreateChannelSiteRequest(
    channelId: 123,
    payload: ['url' => 'https://kittens.mybigcommerce.com/', 'channel_id' => 123],
));

$site = $response->json('data');
```

**Delete a Channel Site**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\DeleteChannelSiteRequest;

$response = BigcommerceHttp::management()->send(new DeleteChannelSiteRequest(channelId: 123));
```

#### Channel Menus

Manage which control panel side navigation menus appear for a channel.

**Get Channel Menus**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\GetChannelMenusRequest;

$response = BigcommerceHttp::management()->send(new GetChannelMenusRequest(channelId: 667159));

$menus = $response->json('data');
```

**Create Channel Menus**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\CreateChannelMenusRequest;

$response = BigcommerceHttp::management()->send(new CreateChannelMenusRequest(
    channelId: 667159,
    payload: [
        'bigcommerce_protected_app_sections' => ['social', 'carousel', 'domains'],
        'custom_app_sections' => [
            ['title' => 'Overview', 'query_path' => 'overview'],
            ['title' => 'Products', 'query_path' => 'products'],
        ],
    ],
));

$menus = $response->json('data');
```

**Delete Channel Menus**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\DeleteChannelMenusRequest;

$response = BigcommerceHttp::management()->send(new DeleteChannelMenusRequest(channelId: 667159));
```

#### Channel Metafields

Store custom, structured data against a single channel, or manage metafields across all channels in batch.

**Get Channel Metafields**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\GetChannelMetafieldsRequest;

$response = BigcommerceHttp::management()->send(new GetChannelMetafieldsRequest(
    channelId: 1,
    queryParams: ['namespace' => 'Sales Department', 'limit' => 50],
));

$metafields = $response->json('data');
```

**Create a Channel Metafield**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\CreateChannelMetafieldRequest;

$response = BigcommerceHttp::management()->send(new CreateChannelMetafieldRequest(
    channelId: 1,
    namespace: 'Warehouse Locations',
    key: 'Location',
    value: '4HG',
    permissionSet: 'write',
    description: 'Location in the warehouse',
));

$metafield = $response->json('data');
```

**Get a Channel Metafield**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\GetChannelMetafieldRequest;

$response = BigcommerceHttp::management()->send(new GetChannelMetafieldRequest(channelId: 1, metafieldId: 42));

$metafield = $response->json('data');
```

**Update a Channel Metafield**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\UpdateChannelMetafieldRequest;

$response = BigcommerceHttp::management()->send(new UpdateChannelMetafieldRequest(
    channelId: 1,
    metafieldId: 42,
    value: '4HG-updated',
));

$metafield = $response->json('data');
```

**Delete a Channel Metafield**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\DeleteChannelMetafieldRequest;

$response = BigcommerceHttp::management()->send(new DeleteChannelMetafieldRequest(channelId: 1, metafieldId: 42));
```

**Get All Channel Metafields**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\GetChannelsMetafieldsRequest;

$response = BigcommerceHttp::management()->send(new GetChannelsMetafieldsRequest(queryParams: ['namespace' => 'Sales Department']));

$metafields = $response->json('data');
```

**Create multiple Metafields**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\CreateChannelsMetafieldsRequest;

$response = BigcommerceHttp::management()->send(new CreateChannelsMetafieldsRequest(
    data: [
        [
            'namespace' => 'Sales Department',
            'key' => 'Staff Name',
            'value' => 'Ronaldo',
            'permission_set' => 'write',
            'resource_type' => 'channel',
            'resource_id' => 1,
        ],
    ],
));

$metafields = $response->json('data');
```

**Update multiple Metafields**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\UpdateChannelsMetafieldsRequest;

$response = BigcommerceHttp::management()->send(new UpdateChannelsMetafieldsRequest(
    data: [
        ['id' => 42, 'value' => 'Ronaldinho'],
    ],
));

$metafields = $response->json('data');
```

**Delete Multiple Metafields**

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Channels\DeleteChannelsMetafieldsRequest;

$response = BigcommerceHttp::management()->send(new DeleteChannelsMetafieldsRequest(ids: [42, 43, 44]));
```

## Testing

```bash
composer test
```

### Mocking BigCommerce responses in your own tests

Because every endpoint is a Saloon `Request` class, you never need to hit the real BigCommerce API (or a HTTP-level fake like Guzzle's mock handler) to test code that uses this package. Saloon ships a `MockClient` that intercepts requests per-connector or globally and returns canned `MockResponse`s instead.

**Mocking a connector you construct directly**

Attach the mock client to the connector instance with `withMockClient()`:

```php
use RobMellett\BigcommerceHttp\Connectors\ManagementApiConnector;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

$mockClient = new MockClient([
    GetProductRequest::class => MockResponse::make(['data' => ['id' => 123, 'name' => 'Smith Journal 13']], 200),
]);

$connector = new ManagementApiConnector(storeHash: 'abc123', accessToken: 'token');
$connector->withMockClient($mockClient);

$response = $connector->send(new GetProductRequest(productId: 123));

$product = $response->json('data'); // ['id' => 123, 'name' => 'Smith Journal 13']

$mockClient->assertSent(GetProductRequest::class);
```

**Mocking the `BigcommerceHttp` facade / config-driven connectors**

`BigcommerceHttp::management()` and `BigcommerceHttp::storefront()` build a fresh connector on every call, so there's nothing to call `withMockClient()` on ahead of time. Instead, register a *global* mock client — Saloon checks for one automatically on every connector and request. `MockClient::global()` only registers a client once per process (later calls are ignored), so reset it in `tearDown()` or it'll leak mocked responses into your next test:

```php
use RobMellett\BigcommerceHttp\Facades\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductsRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class ListsProductsTest extends TestCase
{
    protected function tearDown(): void
    {
        MockClient::destroyGlobal();

        parent::tearDown();
    }

    public function test_it_lists_products_from_bigcommerce(): void
    {
        $mockClient = MockClient::global([
            GetProductsRequest::class => MockResponse::make([
                'data' => [
                    ['id' => 123, 'name' => 'Smith Journal 13'],
                ],
            ], 200),
        ]);

        $response = BigcommerceHttp::management()->send(new GetProductsRequest());

        $this->assertSame('Smith Journal 13', $response->json('data.0.name'));
        $mockClient->assertSent(GetProductsRequest::class);
    }
}
```

**Matching by URL and asserting request contents**

You can also key mock responses off a URL pattern (with wildcards) instead of a request class, and inspect the actual request that was sent — handy for asserting the payload your code built is correct. `assertSent()` accepts a closure typed against the request class, which receives the sent request instance:

```php
use RobMellett\BigcommerceHttp\Requests\Management\Products\GetProductRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

$mockClient = MockClient::global([
    'api.bigcommerce.com/stores/*/v3/catalog/products/*' => MockResponse::make(['data' => ['id' => 123]], 200),
]);

// ...call code that sends a GetProductRequest...

$mockClient->assertSent(function (GetProductRequest $request) {
    return $request->resolveEndpoint() === '/catalog/products/123';
});
```

**Simulating errors**

Return a non-2xx `MockResponse` to exercise your error handling — both connectors throw on failure responses (see [Error handling](#error-handling)), so a mocked 404/422/500 will raise the same `Saloon\Exceptions\Request\...` exceptions your code catches in production:

```php
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Exceptions\Request\Statuses\NotFoundException;

$mockClient = MockClient::global([
    GetProductRequest::class => MockResponse::make(['title' => 'Not Found'], 404),
]);

$this->expectException(NotFoundException::class);

BigcommerceHttp::management()->send(new GetProductRequest(productId: 999));
```

See Saloon's [testing documentation](https://docs.saloon.dev/testing/mocking-responses) for the full `MockClient`/`MockResponse` API, including sequenced responses, fixtures recorded from real requests, and more assertion helpers (`assertSentCount()`, `assertNotSent()`, `assertNothingSent()`, etc.).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Rob Mellett](https://github.com/robmellett)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

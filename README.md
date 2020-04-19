<!-- @format -->

[![Latest Stable Version](https://poser.pugx.org/tbclla/laravel-revolut-merchant/v/stable)](https://packagist.org/packages/tbclla/laravel-revolut-merchant)
[![License](https://poser.pugx.org/tbclla/laravel-revolut-merchant/license)](https://packagist.org/packages/tbclla/laravel-revolut-merchant)
[![Build Status](https://travis-ci.com/tbclla/laravel-revolut-merchant.svg?branch=master)](https://travis-ci.com/tbclla/laravel-revolut-merchant)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tbclla/laravel-revolut-merchant/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tbclla/laravel-revolut-merchant/?branch=master)

# Laravel-Revolut (Merchant)

An unofficial Laravel wrapper for [Revolut's Merchant API](https://developer.revolut.com/docs/merchant-api).<br>
A sister package for [Revolut's Open API for Business](https://developer.revolut.com/docs/business-api) can be found [here](https://github.com/tbclla/laravel-revolut-business).

## Requirements

- Laravel >=5.8
- PHP >=7.2

## Getting Started

Follow this guide on how to install the package, and read Revolut's [Merchant API documentation](https://developer.revolut.com/docs/merchant-api/#getting-started) to learn more about the functionalities offered by the API.

⚠️ **Please use a [sandbox account](https://sandbox-business.revolut.com/signup) when setting up this package, and only switch to your real-world account once you're happy that everything is working correclty.**

### Installing

Pull the package in through Composer

```
composer require tbclla/laravel-revolut-merchant
```

#### Service Provider & Facade

If you have disabled auto-discovery, add the service provider and facade to your `config/app.php`.

```php
'providers' => [
	// ...
	tbclla\RevolutMerchant\Providers\RevolutMerchantServiceProvider::class,
],

'aliases' => [
	// ...
	'Merchant' => tbclla\RevolutMerchant\Facades\Merchant::class,
]
```

#### Publish the configuration (optional)

If you would like to publish this package's configuration to your own config directory, use the below artisan command.<br>
```
php artisan vendor:publish --provider "tbclla\RevolutMerchant\Providers\RevolutMerchantServiceProvider"
```

#### Set environment variables

Add the following keys to your project's `.env` file, as all of the configuration values are read from there.<br>
❗Complete the `REVOLUT_MERCHANT_API_KEY` with the API key from your merchant account.

```
REVOLUT_MERCHANT_SANDBOX=true
REVOLUT_MERCHANT_API_KEY=
```

That's it, you're all done.

## How to use this package

To use the client, you can either instantiate a new `tbclla\RevolutMerchant\Client` which accepts your API key, and whether or not to run in sandbox mode:

```php
use tbclla\RevolutMerchant\Client;
// sandbox
$merchant = new Client('your_api_key', true);
// production
$merchant = new Client('your_api_key');

$merchant->order()->get($orderId);
```

Or you can use the facade, which will inject your environment values.<br>
For brevity, all of the examples in this documentation are using the facade.

```php
use tbclla\RevolutMerchant\Facades\Merchant;

Merchant::order()->get($id);
```

### Orders

Please refer to [Revolut's official documentation on orders](https://developer.revolut.com/docs/merchant-api/#backend-api-order-object) for additional information on how to use the orders endpoint.

#### Create an order

```php
Merchant::order()->create([
	"amount" => 200,
	"capture_mode" => "MANUAL",
	"merchant_order_id" => "00122",
	"customer_email" => "sally.gibson@gmail.com",
	"description" => "description",
	"currency" => "GBP",
	"settlement_currency" => "USD",
	"merchant_customer_id" => "sally01"
]);
```

#### Retrieve an order

```php
$orderId = 'd41c46db-5f82-4dd7-8a22-a43ac517b6df';

Merchant::order()->get($orderId);
```

#### Capture an order

```php
Merchant::order()->capture($orderId);
```

#### Cancel an order

```php
Merchant::order()->cancel($orderId);
```

#### Refund an order

```php
Merchant::order()->refund($orderId, [
	"amount" => 100,
	"currency" => "GBP",
	"merchant_order_id" => "00122",
	"description" => null
]);
```

### Web-Hooks

Read [Revolut's official documentation about web-hooks](https://developer.revolut.com/docs/merchant-api/#backend-api-webhooks) to learn more.

#### Create the web-hook

```php
Merchant::webhook()->create('https://myapp.com/webhook');
```

#### Revoke the web-hook

```php
Merchant::webhook()->revoke();
```

#### Retrieve web-hooks

```php
Merchant::webhook()->retrieve();
```

## Revolut Checkout Widget API

To use the checkout widget, you have to embed [a script from Revolut](https://developer.revolut.com/docs/merchant-api/#revolut-checkout-widget-api-embed-script) on any page that will use the checkout widget.

This package includes a `@revolutMerchantScript` blade directive which will embed this script for you and set the correct source depending on your configured environment.

```html
<head>
	<title>Checkout Page</title>

	@revolutMerchantScript
</head>
<body>
	...
</body>
```

## Brief example

Below is a quick and dirty example to illustrate how to create a new payment order, and subsequently display a payment pop-up.

For this example, the logged in user sends an AJAX request to a `/purchase` route, where a new payment order is created.
The payment order's `public_id` is then returned and passed to `RevolutCheckout()`.

#### PHP

```php
Route::post('/purchase', function() {
	// Get the logged in user
	$user = Auth::user();
	// Fetch the item being purchased
	$item = Item::find(request('item_id'));
	// Create a new payment order
	$paymentOrder = Merchant::order()->create([
		"currency" => "GBP",
		"description" => $item->name,
		"amount" => $item->price,
		"customer_email" => $user->email,
		"merchant_customer_id" => $user->id,
	]);
	// return the payment order's 'public_id'
	return $paymentOrder['public_id'];
});
```

#### JavaScript

❗Don't forget that any page which intends to use `RevolutCheckout()` must include the [above mentioned checkout widget script](https://github.com/tbclla/laravel-revolut-merchant#revolut-checkout-widget-api) **before** calling `RevolutCheckout()`.

```js
// the user triggers a request to the above route
axios.post('/purchase', {"item_id": 123456}).then((response) => {
	// get the 'public_id' from the response
	let publicId = response.data;
	// pass the 'public_id' to RevolutCheckout()
	RevolutCheckout(publicId).then(function(instance) {
		// Launch the pop-up
		instance.payWithPopup({
			onSuccess() { ... },
			onError(message) { ... }
		});
	});
});
```

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

<?php

namespace tbclla\RevolutMerchant\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use tbclla\RevolutMerchant\Client;

class RevolutMerchantServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__ . '/../config/revolut-merchant.php', 'revolut-merchant'
		);

		$this->app->singleton(Client::class, function() {
			return resolve('merchant');
		});

		$this->app->singleton('merchant', function() {
			return new Client(
				config('revolut-merchant.api_key'),
				config('revolut-merchant.sandbox', true)
			);;
		});
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/../config/revolut-merchant.php' => config_path('revolut-merchant.php')
		]);

		Blade::directive('revolutMerchantScript', function() {

			$src = config('revolut-merchant.sandbox', true)
				? 'https://sandbox-merchant.revolut.com/embed.js'
				: 'https://merchant.revolut.com/embed.js';

			return '<script>!function(e,o,n){e[n]=function(t){var r=o.createElement("script");r.id="revolut-checkout",r.src="' . $src . '",r.async=!0,o.head.appendChild(r);var c={then:function(c,i){r.onload=function(){c(e[n](t))},r.onerror=function(){o.head.removeChild(r),i&&i(new Error(n+" is failed to load"))}}};return"function"==typeof Promise?Promise.resolve(c):c}}(window,document,"RevolutCheckout");</script>';
		});
	}
}

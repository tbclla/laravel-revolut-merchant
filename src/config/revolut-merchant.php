<?php

return [

	/*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
	| Define whether or not to operate in the sandbox environment.
	| Default: true
    |
    */
	'sandbox' => env('REVOLUT_MERCHANT_SANDBOX', true),

	/*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    |
	| You can generate the API key on the Revolut business merchant portal.
	| In order to do so, please proceed to the Merchant tab in your Revolut
	| Business App, click on the API menu option, and you will find the
	| MERCHANT_API_KEY under the section called Production API key.
    |
    */
	'api_key' => env('REVOLUT_MERCHANT_API_KEY'),
];

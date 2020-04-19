<?php

namespace tbclla\RevolutMerchant\Tests;

use Orchestra\Testbench\TestCase;
use tbclla\RevolutMerchant\Client;
use tbclla\RevolutMerchant\Resources\Order;

class OrderApiTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return ['tbclla\RevolutMerchant\Providers\RevolutMerchantServiceProvider'];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('revolut-merchant.api_key', getenv('REVOLUT_MERCHANT_API_KEY'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->order = new Order(resolve(Client::class));
    }

    /** @test */
    public function an_order_can_be_created()
    {
        $email = 'sally.gibson@example.com';
        $customerId = 'sally01';
        $amount = 200;
        $currency = 'GBP';

        $response = $this->order->create([
            'amount' => $amount,
            'capture_mode' => 'MANUAL',
            'merchant_order_id' => '00122',
            'customer_email' => $email,
            'description' => 'description',
            'currency' => $currency,
            'settlement_currency' => 'USD',
            'merchant_customer_id' => $customerId,
        ]);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('public_id', $response);
        $this->assertArrayHasKey('created_date', $response);

        $this->assertEquals([
            'value' => $amount,
            'currency' => $currency
        ], $response['order_amount']);

        $this->assertEquals($email, $response['email']);
        $this->assertEquals($customerId, $response['merchant_customer_ext_ref']);
    }

    /** @test */
    public function an_order_can_be_retrieved()
    {
        $amount = 100;
        $currency = 'USD';

        $order = $this->order->create([
            'amount' => $amount,
            'currency' => $currency,
        ]);

        $id = $order['id'];

        $response = $this->order->get($id);

        $this->assertEquals([
            'value' => $amount,
            'currency' => $currency
        ], $response['order_amount']);
    }
}

<?php

namespace tbclla\RevolutMerchant\Tests;

use Orchestra\Testbench\TestCase;
use tbclla\RevolutMerchant\Client;
use tbclla\RevolutMerchant\Resources\Order;

class OrderApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client('aUlJUaOlSTUy9Wi3As0GfpHXyToXIWeT0bgg0P4u8aV5JN_lezSuYpjG6EB97381', true);

        $this->order = new Order($this->client);
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

<?php

namespace tbclla\RevolutMerchant\Tests;

use Orchestra\Testbench\TestCase;
use tbclla\RevolutMerchant\Client;
use tbclla\RevolutMerchant\Resources\Order;
use tbclla\RevolutMerchant\Resources\Resource;

class OrderTest extends TestCase
{
    protected function setUp() : void
    {
        parent::setUp();
        $this->mockClient = $this->mock(Client::class);
    }

    /** @test */
    public function the_client_can_return_an_order_resource()
    {
        $client = new Client('example_key');
        $order = $client->order();

        $this->assertInstanceOf(Order::class, $order);
        $this->assertInstanceOf(Resource::class, $order);
    }

    /** @test */
    public function orders_can_be_created()
    {
        $this->mockClient->shouldReceive()->post(Order::ENDPOINT, [
            'json' => []
        ]);

        $order = new Order($this->mockClient);
        $order->create([]);
    }

    /** @test */
    public function an_order_can_be_retrieved()
    {
        $id = 'abc123';

        $this->mockClient->shouldReceive()->get(Order::ENDPOINT . '/' . $id);

        $order = new Order($this->mockClient);
        $order->get($id);
    }

    /** @test */
    public function an_order_can_be_captured()
    {
        $id = 'abc123';

        $this->mockClient->shouldReceive()->post(Order::ENDPOINT . '/' . $id . '/capture');

        $order = new Order($this->mockClient);
        $order->capture($id);
    }

    /** @test */
    public function an_order_can_be_cancelled()
    {
        $id = 'abc123';

        $this->mockClient->shouldReceive()->post(Order::ENDPOINT . '/' . $id . '/cancel');

        $order = new Order($this->mockClient);
        $order->cancel($id);
    }

    /** @test */
    public function an_order_can_be_refunded()
    {
        $id = 'abc123';

        $this->mockClient->shouldReceive()->post(Order::ENDPOINT . '/' . $id . '/refund', [
            'json' => []
        ]);

        $order = new Order($this->mockClient);
        $order->refund($id, []);
    }
}

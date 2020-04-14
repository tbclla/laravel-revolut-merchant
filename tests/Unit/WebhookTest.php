<?php

namespace tbclla\RevolutMerchant\Tests;

use Orchestra\Testbench\TestCase;
use tbclla\RevolutMerchant\Client;
use tbclla\RevolutMerchant\Resources\Resource;
use tbclla\RevolutMerchant\Resources\Webhook;

class WebhookTest extends TestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->mockClient = $this->mock(Client::class);
	}

	/** @test */
	public function the_client_can_return_a_webhook_resource()
	{
		$client = new Client('example_key');
		$webhook = $client->webhook();

		$this->assertInstanceOf(Webhook::class, $webhook);
		$this->assertInstanceOf(Resource::class, $webhook);
	}

	/** @test */
	public function webhooks_can_be_set()
	{
		$url = 'http://test.com/webhook';

		$this->mockClient->shouldReceive()->post(Webhook::ENDPOINT, [
			'json' => [
				'url' => $url,
			]
		]);

		$webhook = new Webhook($this->mockClient);
		$webhook->set($url);
	}

	/** @test */
	public function webhooks_can_be_revoked()
	{
		$this->mockClient->shouldReceive()->post(Webhook::ENDPOINT, [
			'json' => [
				'url' => null
			]
		]);

		$webhook = new Webhook($this->mockClient);
		$webhook->revoke();
	}

	/** @test */
	public function webhooks_can_be_retrieved()
	{
		$this->mockClient->shouldReceive()->get(Webhook::ENDPOINT);

		$webhook = new Webhook($this->mockClient);
		$webhook->retrieve();
	}
}

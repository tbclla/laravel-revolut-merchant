<?php

namespace tbclla\RevolutMerchant\Tests;

use Orchestra\Testbench\TestCase;
use tbclla\RevolutMerchant\Client;
use tbclla\RevolutMerchant\Resources\Webhook;

class WebhookApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $client = new Client('aUlJUaOlSTUy9Wi3As0GfpHXyToXIWeT0bgg0P4u8aV5JN_lezSuYpjG6EB97381', true);

        $this->webhook = new Webhook($client);

        $this->url = 'http://example.test/webhook';
    }

    /** @test */
    public function a_webhook_can_be_set()
    {
        $response = $this->webhook->set($this->url);

        $this->assertEquals([
            'url' => $this->url
        ], $response);
    }

    /** @test */
    public function a_webhook_can_be_retrieved()
    {
        $response = $this->webhook->retrieve();

        $this->assertEquals([
            [
                'url' => $this->url,
            ]
        ], $response);
    }

    /** @test */
    public function a_webhook_can_be_revoked()
    {
        $response = $this->webhook->revoke();

        $this->assertEquals([], $response);
    }
}

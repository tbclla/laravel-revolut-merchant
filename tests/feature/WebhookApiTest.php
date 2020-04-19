<?php

namespace tbclla\RevolutMerchant\Tests;

use Orchestra\Testbench\TestCase;
use tbclla\RevolutMerchant\Client;
use tbclla\RevolutMerchant\Resources\Webhook;

class WebhookApiTest extends TestCase
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
        $app['config']->set('revolut-merchant.api_key', env('REVOLUT_MERCHANT_API_KEY'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->webhook = new Webhook(resolve(Client::class));

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

<?php

namespace tbclla\RevolutMerchant\Tests;

use Mockery;
use Orchestra\Testbench\TestCase;
use tbclla\RevolutMerchant\Client;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockHttpClient = $this->mock('overload:GuzzleHttp\Client');
    }

    /** @test */
    public function a_client_can_make_an_authorized_post_request()
    {
        $this->mockHttpClient->shouldReceive()->request('POST', Mockery::any(), [
            'headers' => [
                'Authorization' => 'Bearer api_key',
            ]
        ])->andReturn(new \GuzzleHttp\Psr7\Response());

        $client = new Client('api_key', true);

        $client->post('/endpoint', []);
    }

    /** @test */
    public function a_client_can_make_an_authorized_get_request()
    {
        $this->mockHttpClient->shouldReceive()->request('GET', Mockery::any(), [
            'headers' => [
                'Authorization' => 'Bearer api_key',
            ]
        ])->andReturn(new \GuzzleHttp\Psr7\Response());

        $client = new Client('api_key', true);

        $client->get('/endpoint');
    }
}

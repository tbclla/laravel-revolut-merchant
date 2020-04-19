<?php

namespace tbclla\RevolutMerchant;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use tbclla\RevolutMerchant\Exceptions\MerchantException;

/**
 * @method \tbclla\RevolutMerchant\Resources\Order order()
 * @method \tbclla\RevolutMerchant\Resources\Webhook webhook()
 */
class Client
{
    /**
     * The Revolut merchant Sandbox URL
     * 
     * @var string
     */
    const SANDBOX_URL = 'https://sandbox-merchant.revolut.com';

    /**
     * The Revolut merchant production URL
     * 
     * @var string
     */
    const PRODUCTION_URL = 'https://merchant.revolut.com';

    /**
     * The Revolut merchant API endpoint
     * 
     * @var string
     */
    const API_ENDPOINT = '/api';

    /**
     * The Revolut merchant API version
     * 
     * @var string
     */
    const API_VERSION = '1.0';

    /**
     * The API key
     *
     * @var string
     */
    private $apiKey;

    /**
     * The HTTP client
     *
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * Whether or not to use the sandbox environment
     *
     * @var bool
     */
    private $sandbox;

    /**
     * Create a new client
     *
     * @param string $apiKey
     * @param bool $sandbox
     * @return void
     */
    public function __construct(string $apiKey, bool $sandbox = false)
    {
        $this->apiKey = $apiKey;
        $this->sandbox = $sandbox;
        $this->httpClient = new GuzzleClient();
    }

    /**
     * @param string $name
     * @param mixed $args
     * @return mixed
     * @throws \tbclla\RevolutMerchant\Exceptions\MerchantException
     */
    public function __call($name, $args)
    {
        $class = __NAMESPACE__ . '\\Resources\\' . ucfirst($name);
        if (!class_exists($class)) {
            throw new MerchantException('"' . $class . '" is not a valid API resource.');
        }
        return new $class($this);
    }

    /**
     * Perform a 'POST' request
     *
     * @param string $endpoint The request endpoint
     * @param array $options The request options
     * @return array The response body
     * @throws \tbclla\RevolutMerchant\Exceptions\MerchantException
     */
    public function post(string $endpoint, array $options = [])
    {
        return $this->request('POST', $endpoint, $options);
    }

    /**
     * Perform a 'GET' request
     *
     * @param string $endpoint The request endpoint
     * @param array $options The request options
     * @return array The response body
     * @throws \tbclla\RevolutMerchant\Exceptions\MerchantException
     */
    public function get(string $endpoint, array $options = [])
    {
        return $this->request('GET', $endpoint, $options);
    }

    /**
     * Perform a request
     *
     * @param string $method The request method
     * @param string $endpoint The request endpoint
     * @param array $options The request options
     * @return array The response body
     * @throws \tbclla\RevolutMerchant\Exceptions\MerchantException
     */
    private function request(string $method, string $endpoint, array $options)
    {
        try {
            $response = $this->httpClient->request($method, self::baseUri() . $endpoint, $this->buildOptions($options));
        } catch (GuzzleException $e) {
            throw new MerchantException($e->getMessage(), $e->getCode(), $e);
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * Build the base URI for all API requests
     *
     * @return string
     */
    public function baseUri()
    {
        $url = $this->sandbox ? self::SANDBOX_URL : self::PRODUCTION_URL;

        return $url . $this->ApiUri();
    }

    /**
     * Build the API URI
     *
     * @return string
     */
    public static function ApiUri()
    {
        return self::API_ENDPOINT . '/' . self::API_VERSION;
    }

    /**
     * Build the request options
     *
     * @param array $options
     * @return array
     */
    private function buildOptions(array $options = [])
    {
        return array_merge($options, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey
            ],
        ]);
    }
}

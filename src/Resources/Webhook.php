<?php

namespace tbclla\RevolutMerchant\Resources;

class Webhook extends Resource
{
    /**
     * The Revolut merchant web-hook endpoint
     * 
     * @var string
     */
    const ENDPOINT = '/webhooks';

    /**
     * Set the web-hook
     *
     * @param string $url
     * @return array The request body
     * @throws \tbclla\RevolutMerchant\Exceptions\MerchantException
     */
    public function set(string $url = null)
    {
        return $this->client->post(self::ENDPOINT, [
            'json' => [
                'url' => $url,
            ]
        ]);
    }

    /**
     * Revoke the web-hook
     *
     * @return array The request body
     * @throws \tbclla\RevolutMerchant\Exceptions\MerchantException
     */
    public function revoke()
    {
        return $this->set();
    }

    /**
     * Retrieve the web-hooks
     *
     * @return array The request body
     * @throws \tbclla\RevolutMerchant\Exceptions\MerchantException
     */
    public function retrieve()
    {
        return $this->client->get(self::ENDPOINT);
    }
}

<?php

namespace tbclla\RevolutMerchant\Resources;

class Order extends Resource
{
    /**
     * The Revolut merchant payment orders endpoint
     * 
     * @var string
     */
    const ENDPOINT = '/orders';

    /**
     * Create a new payment order
     * 
     * @param array $json
     * @return array The response body
     * @throws \tbclla\RevolutMerchant\Exceptions\MerchantException
     */
    public function create(array $json)
    {
        return $this->client->post(self::ENDPOINT, ['json' => $json]);
    }

    /**
     * Retrieve a payment order by its ID
     * 
     * @param string $id The payment order ID
     * @return array The response body
     * @throws \tbclla\RevolutMerchant\Exceptions\MerchantException
     */
    public function get(string $id)
    {
        return $this->client->get(self::ENDPOINT . '/' . $id);
    }

    /**
     * Capture a authorised payment
     * 
     * @param string $id The payment order ID
     * @return array The response body
     * @throws \tbclla\RevolutMerchant\Exceptions\MerchantException
     */
    public function capture(string $id)
    {
        return $this->client->post(self::ENDPOINT . '/' . $id . '/capture');
    }

    /**
     * Cancel a payment which has not been captured yet
     * 
     * @param string $id The payment order ID
     * @return array The response body
     * @throws \tbclla\RevolutMerchant\Exceptions\MerchantException
     */
    public function cancel(string $id)
    {
        return $this->client->post(self::ENDPOINT . '/' . $id . '/cancel');
    }

    /**
     * Create a full or partial refund
     *  
     * @param string $id The payment order ID
     * @param array $json The request body
     * @return array The respone body
     * @throws \tbclla\RevolutMerchant\Exceptions\MerchantException
     */
    public function refund(string $id, array $json)
    {
        return $this->client->post(self::ENDPOINT . '/' . $id . '/refund', [
            'json' => $json,
        ]);
    }
}

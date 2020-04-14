<?php

namespace tbclla\RevolutMerchant\Resources;

use tbclla\RevolutMerchant\Client;

abstract class Resource
{
	/**
	 * the Revolut merchant client
	 *
	 * @var \tbclla\RevolutMerchant\Client
	 */
	protected $client;

	/**
	 * @param \tbclla\RevolutMerchant\Client $client
	 * @return void
	 */
	public function __construct(Client $client)
	{
		$this->client = $client;
	}
}

<?php

namespace WebPay\Api;

use WebPay\Model\Charge;

class Charges {

    /** @var WebPay */
    private $client;

    /**
     * @param WebPay $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Create a charge
     *
     * @param array $params
     * @return Charge
     */
    public function create(array $params)
    {
        return new Charge($this->client->request('charges.create', $params));
    }
}

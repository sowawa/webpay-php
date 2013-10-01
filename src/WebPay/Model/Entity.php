<?php

namespace WebPay\Model;

abstract class Entity extends AbstractModel
{
    /** @var WebPay */
    protected $client;

    public function __construct($client, $data)
    {
        parent::__construct($data);
        $this->client = $client;
    }
}

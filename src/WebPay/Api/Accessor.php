<?php

namespace WebPay\Api;

/**
 * Manage WebPay $client and provide utility methods
 */
abstract class Accessor implements AccessorInterface
{
    /** @var WebPay */
    protected $client;

    /**
     * @param WebPay $client
     */
    public function __construct($client = null)
    {
        $this->client = $client;
    }

    protected function assertId($id)
    {
        if (!is_string($id) || empty($id)) {
            throw \WebPay\Exception\InvalidRequestException::emptyIdException();
        }
    }

    public function setClient(\WebPay\WebPay $client)
    {
        $this->client = $client;
    }
}

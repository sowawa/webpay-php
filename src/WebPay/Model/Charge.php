<?php

namespace WebPay\Model;

class Charge extends Entity {

    public function __construct($client, $data)
    {
        if ($data['card']) {
            $data['card'] = new Card($data['card']);
        }
        parent::__construct($client, $data);
    }

    /**
     * Refund this charge
     *
     * @param integer $amount Amount to refund. Default is all.
     * @return self
     */
    public function refund($amount = null)
    {
        $this->data = $this->client->charges->refund($this->id, $amount)->data;
        return $this;
    }
}

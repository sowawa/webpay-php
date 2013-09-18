<?php

namespace WebPay\Model;

class Token extends Entity {

    public function __construct($client, $data)
    {
        if (array_key_exists('card', $data) && !empty($data['card'])) {
            $data['card'] = new Card($data['card']);
        }
        parent::__construct($client, $data);
    }
}

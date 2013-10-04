<?php

namespace WebPay\Model;

class Event extends Entity
{
    public function __construct($client, $data)
    {
        if (array_key_exists('data', $data) && !empty($data['data'])) {
            $data['data'] = new EventData($client, $data['data']);
        }
        parent::__construct($client, $data);
    }
}

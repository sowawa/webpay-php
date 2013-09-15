<?php

namespace WebPay\Model;

class EntityList extends AbstractModel {

    public function __construct($client, $data)
    {
        if (array_key_exists('data', $data) && !empty($data['data'])) {
            switch ($data['data'][0]['object']) {
            case 'charge':
                $constructor = function(array $item) use ($client) { return new Charge($client, $item); };
                break;
            case 'customer':
                $constructor = function(array $item) use ($client) { return new Customer($client, $item); };
                break;
            case 'event':
                $constructor = function(array $item) use ($client) { return new Event($client, $item); };
                break;
            default:
                // TODO: use APIConnectionError;
            }
            $data['data'] = array_map($constructor, $data['data']);
        }
        parent::__construct($data);
    }
}

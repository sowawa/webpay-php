<?php

namespace WebPay\Api;

use WebPay\Model\Event;
use WebPay\Model\EntityList;

class Events extends Accessor {

    /**
     * @param WebPay $client
     */
    public function __construct($client)
    {
        parent::__construct($client);
    }

    /**
     * Retrieve an existing event
     *
     * @param string $id
     * @return Event
     */
    public function retrieve($id)
    {
        $this->assertId($id);
        return new Event($this->client, $this->client->request('events.retrieve', array('id' => $id)));
    }

    /**
     * Get a list of existing events
     *
     * @param array $params
     * @return EntityList
     */
    public function all(array $params)
    {
        return new EntityList($this->client, $this->client->request('events.all', $params));
    }
}

<?php

namespace WebPay\Model;

class Customer extends Entity {

    /** var bool */
    private $updateEmail = false;

    /** var bool */
    private $updateDescription = false;

    /** var bool */
    private $updateCard = false;

    /** var array */
    private $newCard = null;

    public function __construct($client, $data)
    {
        if ($data['active_card']) {
            $data['active_card'] = new Card($data['active_card']);
        }
        parent::__construct($client, $data);
    }

    /**
     * Set new email address for save
     *
     * @param string $email The new email address
     */
    public function setEmail($email)
    {
        $this->data['email'] = $email;
        $this->updateEmail = true;
    }

    /**
     * Set new description address for save
     *
     * @param string $description The new description address
     */
    public function setDescription($description)
    {
        $this->data['description'] = $description;
        $this->updateDescription = true;
    }

    /**
     * Set new card parameters for save
     *
     * @param array $card The new card parameters
     */
    public function setNewCard(array $card)
    {
        $this->newCard = $card;
        $this->updateCard = true;
    }

    /**
     * Save this charge
     *
     * This method updates only changed fields.
     *
     * @return self
     */
    public function save()
    {
        $params = array();
        if ($this->updateEmail)
            $params['email'] = $this->email;
        if ($this->updateDescription)
            $params['description'] = $this->description;
        if ($this->updateCard)
            $params['card'] = $this->newCard;

        $this->data = $this->client->customers->save($this->id, $params)->data;
        $this->clearAfterSave();
        return $this;
    }

    private function clearAfterSave()
    {
        $this->newCard = null;
        $this->updateEmail = false;
        $this->updateDescription = false;
        $this->updateCard = false;
    }

    /**
     * Delete this charge
     *
     * @return bool True if succeeded
     */
    public function delete()
    {
        return $this->client->customers->delete($this->id);
    }
}

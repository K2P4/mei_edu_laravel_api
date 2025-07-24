<?php

namespace App\Repositories;

use App\Models\Contact;

class ContactRepository
{

    public function allContacts()
    {
        return Contact::all();
    }

    public function createContact($data)
    {
        return Contact::create($data);
    }

    public function getById(Contact $Contact)
    {
        return $Contact;
    }

    public function updateContact($data, $Contact)
    {
        $Contact->update($data);
        return $Contact;
    }

    public function deleteById(Contact $Contact)
    {
        $Contact->delete();
        return $Contact;
    }
}

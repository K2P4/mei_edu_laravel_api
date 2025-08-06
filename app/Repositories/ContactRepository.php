<?php

namespace App\Repositories;

use App\Models\Contact;

class ContactRepository
{

    public function allContacts($search = null)
    {
        $query = Contact::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->paginate(30);
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

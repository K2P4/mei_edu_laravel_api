<?php

namespace App\Http\Controllers\Api;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Repositories\ContactRepository;

class ContactController extends Controller
{
    use HttpResponse;
    protected $contactRepo;

    public function __construct(ContactRepository $contactRepo)
    {
        $this->contactRepo = $contactRepo;
    }
    public function index(Request $request)
    {
        try {
            $search = $request->query('search'); 
            $contacts = $this->contactRepo->allContacts($search);
            return ContactResource::collection($contacts); 
            // return $this->success('success', ContactResource::collection($contacts), 'Contact Fetched Successfully', 200);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function store(StoreContactRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $contacts = $this->contactRepo->createContact($validatedData);
            $createdContact = $this->contactRepo->getById($contacts);
            return $this->success('success', ContactResource::make($createdContact), 'Contact Created Successfully', 201);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function show(Contact $contact)
    {
        try {
            $contact = $this->contactRepo->getById($contact);
            return $this->success('success', ContactResource::make($contact), 'Contact Showed Successfully', 200);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        try {
            $validatedData = $request->validated();
            $contact = $this->contactRepo->updateContact($validatedData, $contact);
            $updatedContact = $this->contactRepo->getById($contact);
            return $this->success('success', ContactResource::make($updatedContact), 'Contact Updated Successfully', 200);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function destroy(Contact $contact)
    {
        try {
            $contact = $this->contactRepo->deleteById( $contact);
            return $this->success('success', null, 'Contact Deleted Successfully', 204);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }


}

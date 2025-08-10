<?php

namespace App\Http\Controllers\Api;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Volunteer\StoreVolunteerRequest;
use App\Http\Requests\Volunteer\UpdateVolunteerRequest;
use App\Http\Resources\VolunteerResource;
use App\Models\Volunteer;
use App\Repositories\VolunteerRepository;
use Illuminate\Support\Facades\Storage;

class VolunteerController extends Controller
{
    use HttpResponse;
    protected $volunteerRepo;

    public function __construct(VolunteerRepository $volunteerRepo)
    {
        $this->volunteerRepo = $volunteerRepo;
    }
    public function index(Request $request)
    {
        try {

            $search = $request->query('search');
            $isAll = $request->boolean('isAll');

            $volunteers = $this->volunteerRepo->allVolunteers($search, $isAll);
            return VolunteerResource::collection($volunteers);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function store(StoreVolunteerRequest $request)
    {
        try {
            $validatedData = $request->validated();

            if ($request->hasFile('image')) {
                $photoPath = $request->file('image')->store('volunteers', 'public');
                $validatedData['image'] =  asset('public/storage/' . $photoPath);
            }

            $volunteer = $this->volunteerRepo->createVolunteer($validatedData);
            $createdVolunteer = $this->volunteerRepo->getById($volunteer);

            return $this->success('success', VolunteerResource::make($createdVolunteer), 'Volunteer Created Successfully', 201);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function show(Volunteer $volunteer)
    {
        try {
            $volunteer = $this->volunteerRepo->getById($volunteer);
            return $this->success('success', VolunteerResource::make($volunteer), 'Volunteer Showed Successfully', 200);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function update(UpdateVolunteerRequest $request, Volunteer $volunteer)
    {
        try {
            $validatedData = $request->validated();

            if ($request->hasFile('image')) {
                if ($volunteer->image && Storage::disk('public')->exists($volunteer->image)) {
                    $dirPath = dirname($volunteer->image);
                    Storage::disk('public')->deleteDirectory($dirPath);
                }

                $photoPath = $request->file('image')->store('volunteers', 'public');
                $validatedData['image'] = asset('public/storage/' . $photoPath);
            }

            $volunteer = $this->volunteerRepo->updateVolunteer($validatedData, $volunteer);
            $updatedVolunteer = $this->volunteerRepo->getById($volunteer);

            return $this->success('success', VolunteerResource::make($updatedVolunteer), 'Volunteer Updated Successfully', 200);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }
    public function destroy(Volunteer $volunteer)
    {
        try {
            if ($volunteer->image && Storage::disk('public')->exists($volunteer->image)) {
                $dirPath = dirname($volunteer->image);
                Storage::disk('public')->deleteDirectory($dirPath);
            }
            $volunteer = $this->volunteerRepo->deleteById($volunteer);
            return $this->success('success', null, 'Volunteer Deleted Successfully', 204);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }
}

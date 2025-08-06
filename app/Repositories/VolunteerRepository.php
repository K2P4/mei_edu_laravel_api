<?php

namespace App\Repositories;

use App\Models\Volunteer;

class VolunteerRepository
{
    public function allVolunteers($search = null,$isAll = false)
    {
        $query = Volunteer::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if($isAll){
            return $query->get();
        }

        return $query->paginate(30);
    }

    public function createVolunteer($data)
    {
        return Volunteer::create($data);
    }

    public function getById(Volunteer $volunteer)
    {
        return $volunteer;
    }

    public function updateVolunteer($data, $volunteer)
    {
        $volunteer->update($data);
        return $volunteer;
    }

    public function deleteById(Volunteer $volunteer)
    {
        $volunteer->delete();
        return $volunteer;
    }
} 
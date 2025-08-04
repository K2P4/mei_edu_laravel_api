<?php

namespace App\Http\Controllers\Api;

use App\Traits\HttpResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResource;
use App\Models\Contact;
use App\Models\Course;
use App\Models\Event;
use App\Models\Volunteer;

class DashboardController extends Controller
{
    use HttpResponse;

    public function index()
    {
        try {
            $contacts = Contact::all();
            $volunteers = Volunteer::all();
            $events = Event::all();
            $courses = Course::all();
            
            return new DashboardResource([
                'contacts' => $contacts,
                'volunteers' => $volunteers,
                'events' => $events,
                'courses' => $courses,
            ]);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    use HttpResponse;
    protected $eventRepo;

    public function __construct(EventRepository $eventRepo)
    {
        $this->eventRepo = $eventRepo;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->query('search');
            $events = $this->eventRepo->allEvents($search);
            return EventResource::collection($events);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function store(StoreEventRequest $request)
    {
        try {
            $validatedData = $request->validated();

            if ($request->hasFile('image')) {
                $photoPath = $request->file('image')->store('events', 'public');
                $validatedData['image'] = asset('storage/' . $photoPath);
            }

            $event = $this->eventRepo->createEvent($validatedData);
            $createdEvent = $this->eventRepo->getById($event);

            return $this->success('success', EventResource::make($createdEvent), 'Event Created Successfully', 201);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function show(Event $event)
    {
        try {
            $event = $this->eventRepo->getById($event);
            return $this->success('success', EventResource::make($event), 'Event Showed Successfully', 200);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        try {
            $validatedData = $request->validated();
            
            if ($request->hasFile('image')) {
                if ($event->image && Storage::disk('public')->exists($event->image)) {
                    $dirPath = dirname($event->image);
                    Storage::disk('public')->deleteDirectory($dirPath);
                }

                $photoPath = $request->file('image')->store('events', 'public');
                $validatedData['image'] = asset('storage/' . $photoPath);
            }

            $event = $this->eventRepo->updateEvent($validatedData, $event);
            $updatedEvent = $this->eventRepo->getById($event);

            return $this->success('success', EventResource::make($updatedEvent), 'Event Updated Successfully', 200);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }

    public function destroy(Event $event)
    {
        try {
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                $dirPath = dirname($event->image);
                Storage::disk('public')->deleteDirectory($dirPath);
            }
            $event = $this->eventRepo->deleteById($event);
            return $this->success('success', null, 'Event Deleted Successfully', 204);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }
} 
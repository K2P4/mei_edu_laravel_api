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
            $images = $request->file('images');
            $event = $this->eventRepo->createEvent($validatedData);

            if (!empty($images)) {
                foreach ($images as $image) {
                    $path = $image->store('events', 'public');
                    $event->images()->create([
                        'image_path' => $path
                    ]);
                }
            }

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
            $images = $request->file('images');

            $event = $this->eventRepo->updateEvent($validatedData, $event);

            if ($images) {
                foreach ($event->images as $existing) {
                    Storage::disk('public')->delete($existing->image_path);
                    $existing->delete();
                }

                foreach ($images as $image) {
                    $path = $image->store('events', 'public');
                    $event->images()->create([
                        'image_path' => $path
                    ]);
                }
            }

            $updatedEvent = $this->eventRepo->getById($event);

            return $this->success('success', new EventResource($event->load('images')), 'Event Updated Successfully', 200);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }


    public function destroy(Event $event)
    {
        try {
            foreach ($event->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $event->images()->delete();

            $this->eventRepo->deleteById($event);

            return $this->success('success', null, 'Event Deleted Successfully', 204);
        } catch (\Exception $e) {
            return $this->fail('error', null, $e->getMessage(), 500);
        }
    }
}

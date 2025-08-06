<?php

namespace App\Repositories;

use App\Models\Event;

class EventRepository
{
    public function allEvents($search = null)
    {
        $query = Event::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->paginate(30);
    }

    public function createEvent($data)
    {
        return Event::create($data);
    }

    public function getById(Event $event)
    {
        return $event->load('images');
    }

    public function updateEvent($data, $event)
    {
        $event->update($data);
        return $event;
    }

    public function deleteById(Event $event)
    {
        $event->delete();
        return $event;
    }
}

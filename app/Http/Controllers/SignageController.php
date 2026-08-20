<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\View\View;
use Carbon\Carbon;

class SignageController extends Controller
{
    public function show(Room $room): View
    {
        $now = Carbon::now();

        $currentMeeting = $room->meetings()
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->first();

        $upcomingMeetings = $room->meetings()
            ->where('start_time', '>', $now)
            ->whereDate('start_time', $now->toDateString())
            ->orderBy('start_time')
            ->get();

        return view('signage.show', compact('room', 'currentMeeting', 'upcomingMeetings', 'now'));
    }
}
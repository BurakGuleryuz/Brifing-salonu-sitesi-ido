<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Room;
use App\Models\User;
use App\Mail\MeetingPriorityAlert;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class MeetingController extends Controller
{
    private function currentUser(): ?User
    {
        return User::find(session('user_id'));
    }

    private function isOzelKalemMuduru(): bool
    {
        $user = $this->currentUser();
        return $user && $user->role === 'ozel_kalem_muduru';
    }

    /**
     * Toplantıları düzenleme/silme yetkisi sadece Özel Kalem Müdürü'nde.
     * Normal personel toplantıları görebilir ama üzerinde değişiklik yapamaz.
     */
    private function canManageMeetings(): bool
    {
        return $this->isOzelKalemMuduru();
    }

    public function index(): View
    {
        $query = Meeting::with('room')->orderBy('start_time', 'desc');

        if (! $this->isOzelKalemMuduru()) {
            $query->where('type', '!=', 'yonetim_kurulu');
        }

        $meetings = $query->paginate(15);
        $canManageMeetings = $this->canManageMeetings();

        return view('meetings.index', compact('meetings', 'canManageMeetings'));
    }

    public function create(): View
    {
        $rooms = Room::where('is_active', true)->orderBy('name')->get();
        $canCreateBoardMeeting = $this->isOzelKalemMuduru();

        return view('meetings.create', compact('rooms', 'canCreateBoardMeeting'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'room_id'    => 'required|exists:rooms,id',
            'type'       => 'required|in:normal,yonetim_kurulu',
            'priority'   => 'required|in:normal,acil',
            'title'      => 'required|string|max:255',
            'organizer'  => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
        ]);

        if ($validated['type'] === 'yonetim_kurulu' && ! $this->isOzelKalemMuduru()) {
            abort(403, 'Yönetim Kurulu toplantısı oluşturma yetkiniz yok.');
        }

        $start = Carbon::parse($validated['start_time']);
        $end   = Carbon::parse($validated['end_time']);

        $hasConflict = Meeting::overlapping($validated['room_id'], $start, $end)->exists();

        if ($hasConflict) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Bu oda, seçilen saat aralığında zaten dolu. Lütfen başka bir saat veya oda seçin.']);
        }

        $meeting = Meeting::create($validated);

        if ($meeting->priority === 'acil') {
            $this->notifyAllUsers($meeting);
        }

        return redirect()->route('meetings.index')->with('success', 'Toplantı başarıyla oluşturuldu.');
    }

    public function edit(Meeting $meeting): View
    {
        // Düzenleme yetkisi sadece Özel Kalem Müdürü'nde
        if (! $this->canManageMeetings()) {
            abort(403, 'Toplantı düzenleme yetkiniz yok.');
        }

        $rooms = Room::where('is_active', true)->orderBy('name')->get();
        $canCreateBoardMeeting = $this->isOzelKalemMuduru();

        return view('meetings.edit', compact('meeting', 'rooms', 'canCreateBoardMeeting'));
    }

    public function update(Request $request, Meeting $meeting): RedirectResponse
    {
        if (! $this->canManageMeetings()) {
            abort(403, 'Toplantı düzenleme yetkiniz yok.');
        }

        $validated = $request->validate([
            'room_id'    => 'required|exists:rooms,id',
            'type'       => 'required|in:normal,yonetim_kurulu',
            'priority'   => 'required|in:normal,acil',
            'title'      => 'required|string|max:255',
            'organizer'  => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'end_time'   => 'required|date|after:start_time',
        ]);

        if ($validated['type'] === 'yonetim_kurulu' && ! $this->isOzelKalemMuduru()) {
            abort(403, 'Yönetim Kurulu toplantısı oluşturma yetkiniz yok.');
        }

        $start = Carbon::parse($validated['start_time']);
        $end   = Carbon::parse($validated['end_time']);

        $hasConflict = Meeting::overlapping($validated['room_id'], $start, $end, $meeting->id)->exists();

        if ($hasConflict) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Bu oda, seçilen saat aralığında zaten dolu. Lütfen başka bir saat veya oda seçin.']);
        }

        $meeting->update($validated);

        if ($meeting->priority === 'acil') {
            $this->notifyAllUsers($meeting);
        }

        return redirect()->route('meetings.index')->with('success', 'Toplantı güncellendi.');
    }

    public function destroy(Meeting $meeting): RedirectResponse
    {
        if (! $this->canManageMeetings()) {
            abort(403, 'Toplantı silme yetkiniz yok.');
        }

        $meeting->delete();
        return redirect()->route('meetings.index')->with('success', 'Toplantı silindi.');
    }

    private function notifyAllUsers(Meeting $meeting): void
    {
        $meeting->load('room');

        $users = User::whereNotNull('email')->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new MeetingPriorityAlert($meeting));
        }
    }
}